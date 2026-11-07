<?php
class Login_Change_db{
    /**   Attempts to change a username and password. Upon success, the user will receive
     * a confirmation alert and be redirected to the login page. If an error transpires,
     * the user will receive an alert with details and remain on the "Login Change screen".
     * 
     * @param string $usernameCurrent
     * @param string $passwordCurrent
     * @param string $usernameNew
     * @param string $passwordNew
     * @param PDO $db -- A database connection.
     */
    public static function loginChangeSubmit($usernameCurrent, $passwordCurrent, $usernameNew, $passwordNew, $db){
        //Check uniqueness of username and password:
        //------------------------------------------
        $uniquenessCheck = true;
        $message = '';
        if($usernameNew != $usernameCurrent && !Login_db::isUsernameUnique($usernameNew, $db)){
            $message .=  'The new username is already used!\n';
            $uniquenessCheck = false;
        }
        if($passwordNew != $passwordCurrent && !Login_db::isPasswordUnique($passwordNew, $db)){
            $message .= 'The new password is already used!';
            $uniquenessCheck = false;
        }
        if(!$uniquenessCheck){
            Login_Change_ui::loginChangeDisplay();
            echo "<script>alert('$message');</script>";
            return;
        }
        //Update username and password in the database:
        //---------------------------------------------
        try {        
            $stmt_1 = $db->prepare("
                SELECT \"ID\", \"password\" 
                FROM \"Users\"
                WHERE \"username\" = :usernameCurrent;
            ");

            $stmt_1->execute([
                ':usernameCurrent' => $usernameCurrent
            ]);
            $rows = $stmt_1->fetchAll(PDO::FETCH_ASSOC);

            $message = '';
            if(count($rows) === 0) {
                $message = 'Error: Current username is incorrect!';

            }else{
                $foundRow = [];
                foreach($rows as $row){
                    if(password_verify($passwordCurrent, $row["password"])){
                        $foundRow = $row;
                        break;
                    }
                }
                if(empty($foundRow)){
                    $message = 'Error: Current password is incorrect!';
                } 
            }
            if(!empty($message)){
                echo "<script>alert('$message');</script>";
                Login_Change_ui::loginChangeDisplay();
                return;
            }

            $ID = $foundRow["ID"];

            //Prepare and execute the SQL:
            $stmt_2 = $db->prepare("
                UPDATE \"Users\" 
                SET \"username\" = :usernameNew,
                    \"password\" = :passwordNew
                WHERE \"ID\" = :ID;
            ");
            //Bind parameters and execute the statement:
            $stmt_2->execute([
                ':usernameNew' => $usernameNew,
                ':passwordNew' => password_hash($passwordNew, PASSWORD_DEFAULT),
                ':ID' => $ID
            ]);

            $rowsAffected = $stmt_2->rowCount();

            if($rowsAffected > 0){
                //unset all "Login_Change_Session" variables:
                unset($_SESSION['usernameCurrent']);
                unset($_SESSION['passwordCurrent']);
                unset($_SESSION['usernameNew']);
                unset($_SESSION['passwordNew']);
                unset($_SESSION['rePasswordNew']);

                //Perform the redirect using Javascript:
                echo "<script>
                    alert('Username and password changed ok!');
                    window.location.href = \"https://refringe.org/TEST/Login/Login.php\";
                </script>";
                exit();

            } else {
                echo "<script>alert('Failure updating username and password!');</script>";
                Login_Change_ui::loginChangeDisplay();
            }

        }catch(PDOException $e){
            echo "<script>alert('Error: $e->getMessage()');</script>)";
            Login_Change_ui::loginChangeDisplay();
        }
    }

    /**   Attempts to update the User in the database with the JSON data passed in
     * (corresponding to screen-field entries). If successful, then an alert will
     * confirm, and the user will be redirected to the "Login Change screen". Upon
     * failure, an alert will show the details, and the user will remain on the
     * "Edit User screen".
     * 
     * @param JSON $jsonEditUser
     * @param PDO $db -- A database connection.
     */
    public static function editUserSubmit($jsonEditUser, $db){
        $aryEditUser = json_decode($jsonEditUser, true);

        $_SESSION['Roles_ID'] = $aryEditUser['selRoles_ID'];
        $_SESSION['first_name'] = $aryEditUser['inFirstName'];
        $_SESSION['last_name'] = $aryEditUser['inLastName'];
        $_SESSION['street'] = $aryEditUser['inStreet'];
        $_SESSION['city'] = $aryEditUser['inCity'];
        $_SESSION['state'] = $aryEditUser['inState'];
        $_SESSION['zip'] = $aryEditUser['inZip'];
        $_SESSION['phone'] = $aryEditUser['inPhone'];
        $_SESSION['email'] = $aryEditUser['inEmail'];
        $_SESSION['notes'] = $aryEditUser['txtNotes'];

        try {        
            //Prepare and execute the SQL:
            $stmt_1 = $db->prepare("
                SELECT \"ID\", \"password\"
                FROM \"Users\"
                WHERE \"username\" = :username;
            ");

            //Bind parameters and execute the statement:
            $username = $_SESSION['username'];
            $password = $_SESSION['password'];

            //Bind parameters and execute the statement:
            $stmt_1->execute([':username' => $username]);
            $rows = $stmt_1->fetchAll(PDO::FETCH_ASSOC);

            if(count($rows) === 0) {
                echo "<script>alert('User Info Modification Failed!');</script>";
                Login_Change_ui::editUserDisplay($db);
                return;

            }else{
                $foundRow = [];
                foreach($rows as $row){
                    if(password_verify($password, $row["password"])){
                        $foundRow = $row;
                        break;
                    }
                }
                if(empty($foundRow)){
                    echo "<script>alert('User Info Modification Failed!');</script>";
                    Login_Change_ui::editUserDisplay($db);
                    return;
                }     
            }

            //Prepare and execute the SQL:
            $stmt_2 = $db->prepare("
                UPDATE \"Users\"
                SET \"Roles_ID\" = :selRoles_ID, \"first_name\" = :inFirstName, 
                    \"last_name\" = :inLastName, \"street\" = :inStreet, 
                    \"city\" = :inCity, \"state\" = :inState, \"zip\" = :inZip,
                    \"phone\" = :inPhone, \"email\" = :inEmail, \"notes\" = :txtNotes
                WHERE \"ID\" = :ID;     
            ");

            //Bind parameters and execute the statement:
            $stmt_2->execute([
                ':selRoles_ID' => $aryEditUser['selRoles_ID'],
                ':inFirstName' => $aryEditUser['inFirstName'],
                ':inLastName' => $aryEditUser['inLastName'],
                ':inStreet' => $aryEditUser['inStreet'],
                ':inCity' => $aryEditUser['inCity'],
                ':inState' => $aryEditUser['inState'],
                ':inZip' => $aryEditUser['inZip'],
                ':inPhone' => $aryEditUser['inPhone'],
                ':inEmail' => $aryEditUser['inEmail'],
                ':txtNotes' => $aryEditUser['txtNotes'],
                ':ID' => $foundRow['ID']
            ]);

            $rowsAffected = $stmt_2->rowCount();

            if($rowsAffected > 0){
                echo "<script>alert('User profile updated ok!');</script>";
                Login_Change_ui::loginChangeDisplay();

            } else {
                echo "<script>alert('Failure updating user!');</script>";
                Login_Change_ui::editUserDisplay($db);
            }
        }catch(PDOException $e){
            echo "<script>alert('Error: $e->getMessage()');</script>";
            Login_Change_ui::editUserDisplay($db);
        }
    }
}//End class, "Login_Change_db".