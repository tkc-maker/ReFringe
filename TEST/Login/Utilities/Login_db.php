<?php
class Login_db{

    /**   Preselects and returns a dynamically built HTML dropdown.
     * The roles data is fetched from the db table "Roles", thus
     * the second parameter.
     * 
     * @param Int $selRoles_ID -- Value to preselect.
     * @param PDO $db -- An open database connection.
     * @return string -- A preselected HTML dropdown.
     */
    public static function populateSelRoles($selRoles_ID, $db){
        try{
            $stmt = $db->query("
                 SELECT \"ID\", \"name\"
                 FROM \"Roles\"
                 ORDER BY \"name\";
            ");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //Check if the rows exist:
            if(empty($rows)) {
                return "No rows found in \"Roles\".";
            }

            $return = "<select class=\"selRoles\">";
            foreach($rows as $row){
                $selected = ($row['ID'] == $selRoles_ID) ? 'selected' : '';
                $return .= "<option value=\"".$row['ID']."\" $selected>".
                    htmlspecialchars($row['name']).
                    "</option>";
            }
            $return .= '</select>';

            return $return;

        }catch(PDOException $e){
            return "Error: ".($e->getMessage());
        }
    }

    /**   Checks if the username entered is unique in the database.
     * If so, then true is returned; Otherwise, false is returned.
     * 
     * @param string $username
     * @param PDO $db -- A database connection.
     * @return bool -- Whether the username entered is unique in the db.
     */
    public static function isUsernameUnique($username, $db){
        try{
            //Prepare and execute the SQL:
            $stmt = $db->prepare("
                SELECT \"username\"
                FROM \"Users\"
                WHERE \"username\" = :username;
            ");
            //Bind parameters and execute the statement:
            $stmt->execute([':username' => trim($username)]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(count($rows) === 0) {
                return true;
            }else{
                return false;
            }        
        }catch(PDOException $e){
            return false;
            //echo "Error ".($e->getMessage());
        }
    }

    /**   Checks if the password entered is unique in the database.
     * If so, then true is returned; Otherwise, false is returned.
     * 
     * @param string $password
     * @param PDO $db -- A database connection.
     * @return bool -- Whether the password entered is unique in the db.
     */
    public static function isPasswordUnique($password, $db){
        try{
            //Prepare and execute the SQL:
            $stmt = $db->prepare("
                SELECT \"password\"
                FROM \"Users\";
            ");
            //Bind parameters and execute the statement:
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(count($rows) === 0) {
                return true;
            }else{
                foreach($rows as $row){
                    if(password_verify($password, $row["password"])){
                        return false;
                    }
                }
                return true;
            }        
        }catch(PDOException $e){
            return false;
            //echo "Error ".($e->getMessage());
        }
    }

    /**   Generates a password of random characters (a selection of lower and upper case letters, digits,
     * and the special characters, "!@#$%^&*()"). The password is generated at the length passed in,
     * which defaults at 12.
     * 
     * @param Integer $length -- The length of the generated password.
     * @return string -- The generated password.
     */
    public static function generate_password($length = 12) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        $charactersLength = strlen($characters);
        $randomPassword = '';

        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomPassword;
    }

    /**   Submits the contents of the "forgot username" form to the
     * database connection passed in. Sends the current username 
     * to the email address on file for the current user.
     * Should any of this fail, an appropriate message is displayed
     * along with the "forgot_username" form. Upon success, the email
     * sent contains a link back to the "login" form.
     * 
     * @param string $first_name
     * @param string $last_name
     * @param string $password
     * @param PDO $db -- A database connection.
     */
    public static function forgotUsernameSubmit($first_name, $last_name, $password, $db){
        try {
            //Prepare and execute the SQL:
            $stmt = $db->prepare("
                SELECT \"username\", \"password\", \"email\"
                FROM \"Users\"
                WHERE \"first_name\" = :first_name AND
                \"last_name\" = :last_name;
            ");
            //Bind parameters and execute the statement:
            $stmt->execute([
                ':first_name' => $first_name,
                ':last_name' => $last_name
            ]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(count($rows) === 0) {
                echo "<script>alert('No Username found from your entries!');</script>";
                login_ui::forgotUsernameDisplay();
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
                    echo "<script>alert('No Username found from your entries!');</script>";
                    login_ui::forgotUsernameDisplay();
                    return;
                }     
            }
            //A match has been found (we assume that the combination of
            //"first_name", "last_name", "password" is unique):
            $destination = $foundRow["email"];
            $subject = 'Username';
            $body = '<p>Here is your Username to log into https://refringe.org/TEST/Login/Login.php :<br><br><b>'.$foundRow["username"].'</b><p>';
            $altBody = 'Here is your Username to log into https://refringe.org/TEST/Login/Login.php : '.$foundRow["username"].'.';
            //Send an email with "PHPMailer":
            if(emailMessage($destination, $subject, $body, $altBody)){
                Login_ui::loginDisplay();
            }else{
                echo "<script>alert('Failure sending username email!');</script>";
                login_ui::forgotUsernameDisplay();
            }

        }catch(PDOException $e){
            echo "<script>alert('Error: $e->getMessage()');</script>";
            login_ui::forgotUsernameDisplay();
        }
    }

    /**   Submits the contents of the "forgot password" form to the
     * database connection passed in. Generates a new random password,
     * and sends it to the email address on file for the current user.
     * Should any of this fail, an appropriate message is displayed
     * along with the "forgot_password" form. Upon success, the email
     * sent contains a link back to the "login change" form.
     * 
     * @param string $first_name
     * @param string $last_name
     * @param string $username
     * @param PDO $db -- A database connection.
     */
    public static function forgotPasswordSubmit($first_name, $last_name, $username, $db){
        try {
            //C H E C K   E N T E R E D   D A T A :
            //-------------------------------------
            //Prepare and execute the SQL:
            $stmt_1 = $db->prepare("
                SELECT \"ID\", \"email\"
                FROM \"Users\"
                WHERE \"first_name\" = :first_name AND
                \"last_name\" = :last_name AND
                \"username\" = :username;
            ");
            //Bind parameters and execute the statement:
            $stmt_1->execute([
                ':first_name' => $first_name,
                ':last_name' => $last_name,
                ':username' => $username
            ]);

            $rows = $stmt_1->fetchAll(PDO::FETCH_ASSOC);

            if(count($rows) === 0) {
                echo "<script>alert('No password found from your entries!');</script>";
                login_ui::forgotPasswordDisplay();
                return;
            }else{
                //We are only checking the first row returned because 
                //we know that the username and password are unique:
                $ID = $rows[0]['ID'];
                $email = $rows[0]['email'];
            }

            //R E S E T   P A S S W O R D :
            //-----------------------------
            //Generate a new 12 random character password:
            $resetPassword = self::generate_password();

            //Hash the password:
            $resetPasswordHashed = password_hash($resetPassword, PASSWORD_DEFAULT);

            //Prepare and execute the SQL:
            $stmt_2 = $db->prepare("
                UPDATE \"Users\"
                SET \"password\" = :resetPasswordHashed
                WHERE \"ID\" = :ID;
            ");

            //Bind parameters and execute the statement:
            $stmt_2->execute([
                ':resetPasswordHashed' => $resetPasswordHashed,
                ':ID' => $ID
            ]);

            $rowsAffected = $stmt_2->rowCount();

            if($rowsAffected > 0){
                //A match has been found (we assume that the combination of
                //"first_name", "last_name", "username" is unique:
                $destination = $email;
                $subject = 'Reset Password';
                $body = "<p>Here is your reset password for ReFringe.org :<br><br><b>$resetPassword</b><br><br>
                    Please login to https://refringe.org/TEST/Login/Login.php and change it to your liking.</p>";
                $altBody = "Here is your reset password for refringe.org : $resetPassword .
                    Please login to https://refringe.org/TEST/Login/Login.php and change it to your liking.";
                //Send an email with "PHPMailer":
                if(emailMessage($destination, $subject, $body, $altBody)){
                    Login_ui::loginDisplay();
                }else{
                    echo "<script>alert('Failure sending password reset email!');</script>";
                    login_ui::forgotPasswordDisplay();
                }
            }else{
                echo "<script>alert('Failure resetting password!');</script>";
                login_ui::forgotPasswordDisplay();
            }
        }catch(PDOException $e){
            echo "<script>alert('Error: $e->getMessage()');</script>";
            login_ui::forgotPasswordDisplay();
        }      
    }

    /**   Attempts to log the user in. Should errors occur, then the "Login screen"
     * is shown cleared, and an error message is displayed. Upon a successful
     * login, the user is redirected to the Main Menu for his Role.
     * 
     * @param string $username
     * @param string $password
     * @param PDO $db -- A database connection.
     */
    public static function loginSubmit($username, $password, $db){
        try {
            //Prepare and execute the SQL:
            $stmt = $db->prepare("
                SELECT \"password\"
                FROM \"Users\"
                WHERE \"username\" = :username;
            ");
            //Bind parameters and execute the statement:
            $stmt->execute([':username' => $username]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(count($rows) === 0) {
                echo "<script>alert('Login Failed!');</script>";
                Login_ui::loginDisplay();
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
                    echo "<script>alert('Login Failed!');</script>";
                    Login_ui::loginDisplay();
                    return;
                }     
            }
            //Fetch user info into SESSION and members of the "UserInfo" class:
            UserInfo::fetchUserInfoFromDbIntoMembersAndSession($username, $password);

            echo "
                <script>alert('Login Successful!');
                window.location.href = \"/TEST/Menus/Main_Menus.php\";</script>
            ";
            exit();

        }catch(PDOException $e){
            echo "<script>alert('Error $e->getMessage()');</script>";
            Login_ui::loginDisplay();
        }
    }

    /**   Submits the basic user profile contents of the “Quick Easy No Login Proposal” form 
     * to the database. Upon success, the Users_ID is echoed back ($_SESSION['ID']). 
     * Should errors occur, they are displayed in a Javascript alert, and control remains 
     * on the “Enter New Proposal Now” form.
     * 
     * Note: This method checks the uniqueness of username and password.
     * 
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $phone
     * @param PDO $db -- A database connection.
     * @return (int/string) Either a new "Users_ID" value or an error message.
     */
    public static function enterNoLoginUserInfoSubmit($firstName, $lastName, $email, $phone, $db){
        $_SESSION['PN_first_name'] = $firstName;
        $_SESSION['PN_last_name'] = $lastName;
        $username = $_SESSION['PN_last_name'];
        $_SESSION['PN_email'] = $email;
        $password = $_SESSION['PN_email'];
        $_SESSION['PN_phone'] = $phone;

        //Check uniqueness of username and password:
        //------------------------------------------
        $uniquenessCheck = true;
        $message = '';
        if(!self::isUsernameUnique($username, $db)){
            $message .=  'The entered username ('.$username.') is already used! Try alternating the case of letters. ';
            $uniquenessCheck = false;
        }
        if(!self::isPasswordUnique($password, $db)){
            $message .= 'The entered password ('.$password.') is already used! Try alternating the case of letters.';
            $uniquenessCheck = false;
        }
        if(!$uniquenessCheck){
            return json_encode(["status" => "false", "errMsg" => $message]);
        }

        try {        
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            //Prepare and execute the SQL:
            $stmt = $db->prepare("
                INSERT INTO \"Users\" (
                    \"Roles_ID\", \"first_name\", \"last_name\", \"phone\", \"email\", \"username\", \"password\") 
                VALUES (
                    :selRoles_ID, :inFirstName, :inLastName, :inPhone, :inEmail, :inUsername, :inPassword)
                RETURNING \"ID\";
            ");
            //Bind parameters and execute the statement:
            $stmt->execute([
                ':selRoles_ID' => 2, // "Proposer".
                ':inFirstName' => $_SESSION['PN_first_name'],
                ':inLastName' => $_SESSION['PN_last_name'],
                ':inPhone' => $_SESSION['PN_phone'],
                ':inEmail' => $_SESSION['PN_email'],
                ':inUsername' => $username,
                ':inPassword' => $hashedPassword
            ]);

            $rowsAffected = $stmt->rowCount();

            if($rowsAffected > 0){
                $lastInsertedID = $stmt -> fetchColumn();
                unset($_SESSION['PN_first_name']);
                unset($_SESSION['PN_last_name']);
                unset($_SESSION['PN_phone']);
                unset($_SESSION['PN_email']);

                //Return the new "Users_ID" value:
                return $lastInsertedID;

            } else {
                return json_encode(["status" => "false", "errMsg" =>"Error: Failure adding basic user profile!"]);
            }
        }catch(PDOException $e){
            return json_encode(["status" => "false", "errMsg" =>"PDO Error $e->getMessage()"]);
        }
    }

    /**   Submits the contents of the "New User form" to the database, and then
     * redirects to the "Login screen". Should errors occur, they are displayed
     * in a Javascript alert, and control remains on the "New User form".
     * 
     * @param JSON $jsonNewUser -- The contents of the "New User form" as a JSON
     *      associative array.
     * @param PDO $db -- A database connection.
     */
    public static function newUserSubmit($jsonNewUser, $db){
        $aryNewUser = json_decode($jsonNewUser, true);

        $_SESSION['NU_Roles_ID'] = $aryNewUser['selRoles'];
        $_SESSION['NU_first_name'] = $aryNewUser['inFirstName'];
        $_SESSION['NU_last_name'] = $aryNewUser['inLastName'];
        $_SESSION['NU_street'] = $aryNewUser['inStreet'];
        $_SESSION['NU_city'] = $aryNewUser['inCity'];
        $_SESSION['NU_state'] = $aryNewUser['inState'];
        $_SESSION['NU_zip'] = $aryNewUser['inZip'];
        $_SESSION['NU_phone'] = $aryNewUser['inPhone'];
        $_SESSION['NU_email'] = $aryNewUser['inEmail'];
        $_SESSION['NU_username'] = $aryNewUser['inUsername'];
        $_SESSION['NU_password'] = $aryNewUser['inPassword'];
        $_SESSION['NU_rePassword'] = $aryNewUser['inPassword'];
        $_SESSION['NU_notes'] = $aryNewUser['txtNotes'];

        //Check uniqueness of username and password:
        //------------------------------------------
        $uniquenessCheck = true;
        $message = '';
        if(!self::isUsernameUnique($aryNewUser['inUsername'], $db)){
            $message .=  'The entered username is already used!\n';
            $uniquenessCheck = false;
        }
        if(!self::isPasswordUnique($aryNewUser['inPassword'], $db)){
            $message .= 'The entered password is already used!';
            $uniquenessCheck = false;
        }
        if(!$uniquenessCheck){
            echo "<script>alert('$message');</script>";
            Login_ui::newUserDisplay($db);
            return;
        }

        try {        
            $hashedPassword = password_hash($aryNewUser['inPassword'], PASSWORD_DEFAULT);

            //Prepare and execute the SQL:
            $stmt = $db->prepare("
                INSERT INTO \"Users\" (
                    \"Roles_ID\", \"first_name\", \"last_name\", \"street\", \"city\",
                    \"state\", \"zip\",\"phone\", \"email\", \"username\", \"password\", \"notes\") 
                VALUES (
                    :selRoles, :inFirstName, :inLastName, :inStreet, :inCity,
                    :inState, :inZip, :inPhone, :inEmail, :inUsername, :inPassword, :txtNotes);
            ");
            //Bind parameters and execute the statement:
            $stmt->execute([
                ':selRoles' => $aryNewUser['selRoles'],
                ':inFirstName' => $aryNewUser['inFirstName'],
                ':inLastName' => $aryNewUser['inLastName'],
                ':inStreet' => $aryNewUser['inStreet'],
                ':inCity' => $aryNewUser['inCity'],
                ':inState' => $aryNewUser['inState'],
                ':inZip' => $aryNewUser['inZip'],
                ':inPhone' => $aryNewUser['inPhone'],
                ':inEmail' => $aryNewUser['inEmail'],
                ':inUsername' => $aryNewUser['inUsername'],
                ':inPassword' => $hashedPassword,
                ':txtNotes' => $aryNewUser['txtNotes']
            ]);

            $rowsAffected = $stmt->rowCount();

            if($rowsAffected > 0){
                echo "<script>alert('New user profile added ok!');</script>";
                unset($_SESSION['NU_Roles_ID']);
                unset($_SESSION['NU_first_name']);
                unset($_SESSION['NU_last_name']);
                unset($_SESSION['NU_street']);
                unset($_SESSION['NU_city']);
                unset($_SESSION['NU_state']);
                unset($_SESSION['NU_zip']);
                unset($_SESSION['NU_phone']);
                unset($_SESSION['NU_email']);
                unset($_SESSION['NU_username']);
                unset($_SESSION['NU_password']);
                unset($_SESSION['NU_rePassword']);
                unset($_SESSION['NU_notes']);
                Login_ui::loginDisplay();

            } else {
                echo "<script>alert('Failure adding new user!');</script>";
                Login_ui::newUserDisplay($db);
            }
        }catch(PDOException $e){
            echo "<script>alert('Error $e->getMessage()');</script>";
            Login_ui::newUserDisplay($db);
        }
    }

}//End class, "Login_db".