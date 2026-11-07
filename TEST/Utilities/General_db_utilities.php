<?php

/**   Attempts to open a connection to the database.
 * If successful, the PDO connection is returned.
 * Otherwise, an error message is echoed to the screen
 * and false is returned.
 * 
 * @return bool|PDO -- Either the opened db connection
 *      or false.
 */
function openDatabaseConnection(){
    try{
        //Database connection:
        $dsn = Constants::$dsn;
        $db = new PDO($dsn, Constants::$db_username, Constants::$db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $db;
        
    }catch(PDOException $e){
        echo "Error connecting to db: ".($e->getMessage());
        return false;
    }    
}

/**   Attempts to close the passed-in database connection.
 * If successful, true is returned. Otherwise, an error
 * message is echoed to the screen and false is returned.
 * 
 * @param PDO $db -- Database connection.
 * @return bool -- Whether the connection was closed.
 */
function closeDatabaseConnection($db){
    try{
        $db = null;
        return true;
    
    }catch(PDOException $e){
        echo "Error closing db connection: ".($e->getMessage());
        return false;
    }    
}


/**   Used to manually change a password. 
 * Note: The user must be logged in for this to work.
 * 
 * @param type $ID
 * @param type $password
 */
function quick_and_dirty_password_update($ID, $password){
    try {
        //Database connection:
        require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/Constants.php';
        $dsn = Constants::$dsn;
        $db = new PDO($dsn, Constants::$db_username, Constants::$db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        //Prepare and execute the SQL:
        $stmt = $db->prepare("
            UPDATE \"Users\" 
            SET \"password\" = :password
            WHERE \"ID\" = :ID;
        ");
        
        //Bind parameters and execute the statement:
        $stmt->execute([
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':ID' => $ID
        ]);

        $rowsAffected = $stmt->rowCount();
        
        //Close the connection:
        $db = null;

        if($rowsAffected > 0){
            echo "<script>alert('Password changed ok!');</script>";
        } else {
            echo "<script>alert('Failure updating password!');</script>";
        }
        
    }catch(PDOException $e){
        echo "<script>alert('Error: $e->getMessage()');</script>)";
    }
}
