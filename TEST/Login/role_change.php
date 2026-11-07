<?PHP 
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser, Organizer, Proposer, Viewer");
    
    $newRole = trim(htmlspecialchars($_GET['newRole'], ENT_QUOTES, 'UTF-8'));

    if(empty($newRole)){
        exit();
    }
    
    changeRole($newRole);
    
    function changeRole($newRole){
        try {
            //Database connection:
            require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/Constants.php';
            $dsn = Constants::$dsn;
            $db = new PDO($dsn, Constants::$db_username, Constants::$db_password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //Fetch the ID and role of the user logged on:
            //--------------------------------------------
            //Prepare and execute the SQL:
            $stmt_1 = $db->prepare("
                SELECT \"ID\", \"password\", \"Roles_ID\"
                FROM \"Users\"
                WHERE \"username\" = :username;
            ");
        
            //Bind parameters and execute the statement:
            $stmt_1->execute([':username' => UserInfo::$username]);
            $rows_1 = $stmt_1->fetchAll(PDO::FETCH_ASSOC);
        
            if(count($rows_1) === 0) {
                echo "<script>alert('Role Change Failed!');</script>";
                return;

            }else{
                $foundRow = [];
                foreach($rows_1 as $row){
                    if(password_verify(UserInfo::$password, $row["password"])){
                        $foundRow = $row;
                        break;
                    }
                }
                if(empty($foundRow)){
                    echo "<script>alert('Role Change Failed!');</script>";
                    return;
                }     
            }

        //Fetch the "ID" of the role with the passed-in "name":
        //-----------------------------------------------------
        $stmt_2 = $db->prepare("
            SELECT \"ID\" 
            FROM \"Roles\"
            WHERE \"name\" = :Roles_name;
        ");

        //Bind parameters and execute the statement:
        $stmt_2->execute([
            ':Roles_name' => $newRole
        ]);
                
        $rows_2 = $stmt_2->fetchAll(PDO::FETCH_ASSOC);
        $Roles_ID = $rows_2[0]['ID'];

        //Update the current record in "Users" with the new "$Roles_ID":
        //--------------------------------------------------------------
        $stmt_3 = $db->prepare("
            UPDATE \"Users\"
            SET \"Roles_ID\" = :Roles_ID
            WHERE \"ID\" = :ID;     
        ");

        //Bind parameters and execute the statement:
        $stmt_3->execute([
            ':Roles_ID' => $Roles_ID,
            ':ID' => $foundRow['ID']
        ]);

        $rowsAffected = $stmt_3->rowCount();
        
        //Close the connection:
        $db = null;

        if($rowsAffected > 0){
            $fullName = htmlspecialchars(UserInfo::$first_name, ENT_QUOTES, 'UTF-8')." ".
                 htmlspecialchars(UserInfo::$last_name, ENT_QUOTES, 'UTF-8');
            $newRole = htmlspecialchars($newRole, ENT_QUOTES, 'UTF-8');
            
            echo "<script>alert('Successful Role Change for ".$fullName."! New Role: ".$newRole.".');
                  window.location.href = \"https://refringe.org/TEST/Login/Login.php\";</script>";
            
        } else {
            echo "<script>alert('Failure Changing Role!');</script>";
        }
    }catch(PDOException $e){
        echo "<script>alert('Failure Changing Role!');</script>";
    }
}

?>
<!DOCTYPE html>
