<?php
if (headers_sent($file, $line)) {
    error_log("Headers already sent in $file on line $line");
}else{
    session_name('User_Session');
    ini_set('session.gc_maxlifetime', 7200);
    session_set_cookie_params([
        'lifetime' => 7200,
        'path'     => '/',      // Available across your entire domain.
//        'domain'   => 'refringe.org',
//        'domain'   => 'localhost:8888',
//        'secure'   => true,     // If you're using HTTPS.
        'secure'   => false,
        'httponly' => true,
        'samesite' => 'Lax'     // Or 'Strict', depending on your needs.
    ]);
    session_start();
}    
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/Constants.php';

/* This class makes use of the "User_Session", which has just been started.
 */
class UserInfo{
    public static $ID = 0;
    public static $Roles_ID = 0;
    public static $Roles_name;
    public static $first_name;
    public static $last_name;
    public static $street;
    public static $city;
    public static $state;
    public static $zip;
    public static $phone;
    public static $email;
    public static $username;
    public static $password;
    public static $notes;

    //Static Methods:
    //---------------
    
    /**   Checks the current members, username and password, against the database.
     * if matched, then this method simply returns control to the caller.
     * If no match is found, then this method redirects to the Login page.
     *    Also, only allows access if the current user's role is within
     * the passed-in array.
     * 
     * Note: This method should be run at the top of every page that requires
     *       a valid login. No content should be displayed before running
     *       this method.
     * 
     * Parameters passed in:
     * 
     *      $strRoles -- (string) A comma-delimited string of role names,
     *          manually provided on each page that calls this method.
     */
    public static function checkLogin($strRoles){        
        self::fetchUserInfoFromSessionIntoMembers();
        
        $username = self::$username;
        $password = self::$password;
        $role = self::$Roles_name;
        
        try {
            //Database connection:
            $dsn = Constants::$dsn;
            $db = new PDO($dsn, Constants::$db_username, Constants::$db_password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //Prepare and execute the SQL:
            $stmt = $db->prepare("
                SELECT \"ID\", \"password\"
                FROM \"Users\"
                WHERE \"username\" = :username;
            ");
            //Bind parameters and execute the statement:
            $stmt->execute([':username' => $username]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //Close the connection:
            $db = null;

            if(count($rows) === 0) {
                echo "
                    <script>alert(\"Please login to view this page!-1\");
                    window.location.href = \"/TEST/Login/Login.php\";</script>
                ";
                exit();

            }else{
                $foundRow = [];
                foreach($rows as $row){
                    $boolGoodPassword = password_verify($password, $row["password"]);
                    $boolGoodRole = str_contains($strRoles, $role);

                    if($boolGoodPassword && $boolGoodRole){
                        $foundRow = $row;
                        break;
                    }
                }
                if(empty($foundRow)){
                    echo "
                        <script>alert(\"Please login to view this page!-2\");
                        window.location.href = \"/TEST/Login/Login.php\";</script>
                    ";
                    exit();
                }     
            }
            
        }catch(PDOException $e){
            echo "
                <script>alert(\"Please login to view this page!-3\");
                window.location.href = \"/TEST/Login/Login.php\";</script>
            ";
            exit();
        }
        
    }//End function "checkLogin".
    
    /**   From the passed-in username and password, attempts to fetch all fields
     * from the "Users" table that identify the user, and set them to SESSION
     * variables and public members of this class. Upon failure, displays an error
     * message via Javascript alert and then redirects to the Login page.
     * 
     * Note: This method is run upon a successful login, and sets all user info
     *       for the life of the application.
     * 
     * @return void (sets all public members of this class or 
     *    displays an error alert and redirects to the Login page.)
     */
    public static function fetchUserInfoFromDbIntoMembersAndSession($username, $password){
        try {
            //Database connection:
//            $dsn = "pgsql:host=localhost;dbname=wwwolamy_refringedb";
//            $db = new PDO($dsn, 'wwwolamy_tcarpenter', 'R0e1F1r9I2n5Ge');
            $dsn = Constants::$dsn;
            $db = new PDO($dsn, Constants::$db_username, Constants::$db_password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //Prepare and execute the SQL:
            $stmt = $db->prepare("
                SELECT \"Users\".\"ID\", \"Roles_ID\", \"Roles\".\"name\", \"first_name\", \"last_name\", \"street\", 
                       \"city\", \"state\", \"zip\", \"phone\", \"email\", \"username\", \"password\", \"notes\"
                FROM \"Users\" INNER JOIN \"Roles\"
                ON \"Users\".\"Roles_ID\" = \"Roles\".\"ID\"
                WHERE \"username\" = :username;
            ");
            //Bind parameters and execute the statement:
            $stmt->execute([':username' => $username]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //Close the connection:
            $db = null;

            if(count($rows) === 0) {
                echo "
                    <script>alert('Critical Error: Failure fetching User Info from database!');
                    window.location.href = \"/TEST/Login/Login.php\";</script>
                ";
                exit();

            }else{
                $foundRow = [];
                foreach($rows as $row){
                    if(password_verify($password, $row["password"])){
                        $foundRow = $row;
                        break;
                    }
                }
                if(empty($foundRow)){
                    echo "
                        <script>alert('Critical Error: Failure fetching User Info from database!');
                        window.location.href = \"/TEST/Login/Login.php\";</script>
                    ";
                    exit();
                }     
            }

            //Set static members with fetched data:
            //-------------------------------------
            self::$ID = $foundRow['ID'];
            $_SESSION['ID'] = $foundRow['ID'];
            self::$Roles_ID = $foundRow['Roles_ID'];
            $_SESSION['Roles_ID'] = $foundRow['Roles_ID'];
            self::$Roles_name = $foundRow['name'];
            $_SESSION['Roles_name'] = $foundRow['name'];
            self::$first_name = $foundRow['first_name'];
            $_SESSION['first_name'] = $foundRow['first_name'];
            self::$last_name = $foundRow['last_name'];
            $_SESSION['last_name'] = $foundRow['last_name'];
            self::$street = $foundRow['street'];
            $_SESSION['street'] = $foundRow['street'];
            self::$city = $foundRow['city'];
            $_SESSION['city'] = $foundRow['city'];
            self::$state = $foundRow['state'];
            $_SESSION['state'] = $foundRow['state'];
            self::$zip = $foundRow['zip']; 
            $_SESSION['zip'] = $foundRow['zip'];
            self::$phone = $foundRow['phone']; 
            $_SESSION['phone'] = $foundRow['phone'];
            self::$email = $foundRow['email'];
            $_SESSION['email'] = $foundRow['email'];
            self::$username = $foundRow['username'];     
            $_SESSION['username'] = $foundRow['username'];
            self::$password = $foundRow['password'];        
            $_SESSION['password'] = $password; //We need this not hashed for future "password_verify(...)" calls.
            self::$notes = $foundRow['notes'];
            $_SESSION['notes'] = $foundRow['notes'];

        }catch(PDOException $e){
            echo "
                <script>alert('Error: $e->getMessage()');
                window.location.href = \"/TEST/Login/Login.php\";</script>
            ";
            exit();
        }
    }//End function "fetchAllUserInfo".
    
    /**   Copies SESSION user info. into members of this class.
     */
    public static function fetchUserInfoFromSessionIntoMembers(){
        self::$ID = isset($_SESSION['ID'])? $_SESSION['ID']: 0;
        self::$Roles_ID = isset($_SESSION['Roles_ID'])? $_SESSION['Roles_ID']: 0;
        self::$Roles_name = isset($_SESSION['Roles_name'])? $_SESSION['Roles_name']: '';
        self::$first_name = isset($_SESSION['first_name'])? $_SESSION['first_name']: '';
        self::$last_name = isset($_SESSION['last_name'])? $_SESSION['last_name']: '';
        self::$street = isset($_SESSION['street'])? $_SESSION['street']: '';
        self::$city = isset($_SESSION['city'])? $_SESSION['city']: '';
        self::$state = isset($_SESSION['state'])? $_SESSION['state']: '';
        self::$zip = isset($_SESSION['zip'])? $_SESSION['zip']: '';
        self::$phone = isset($_SESSION['phone'])? $_SESSION['phone']: '';
        self::$email = isset($_SESSION['email'])? $_SESSION['email']: '';
        self::$username = isset($_SESSION['username'])? $_SESSION['username']: '';    
        self::$password = isset($_SESSION['password'])? $_SESSION['password']: '';  
        self::$notes = isset($_SESSION['notes'])? $_SESSION['notes']: '';  
    }
    
    /**   Clears all members of this class, erasing all stored
     * login info. from the application (not from the database).
     * 
     * Note: This method should be run whenever the Login 
     *       page is opened (so nobody can navigate to 
     *       another page without first logging in).
     * 
     */
    public static function clearUserInfo(){
        self::$ID = 0;
        self::$Roles_ID = 0;
        self::$Roles_name = '';
        self::$first_name = '';
        self::$last_name = '';
        self::$street = '';
        self::$city = '';
        self::$state = '';
        self::$zip = ''; 
        self::$phone = ''; 
        self::$email = '';
        self::$username = '';     
        self::$password = '';  
        self::$notes = '';
        
        //Clear all session variables:
        $_SESSION = [];
        //Destroy the session:
        session_destroy();   
    }
}
