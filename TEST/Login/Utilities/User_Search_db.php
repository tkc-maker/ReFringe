<?php
class User_Search_db{
    /**   Attempts to load SESSION variables with search results from
     * the database for a particular user. Upon success, the SESSION 
     * variables are loaded with user info, and true is returned. 
     * Otherwise, an error message is echoed to the screen, 
     * and false is returned.
     * 
     * @param Integer $varUsers_ID
     * @param PDO $db -- A database connection.
     * @return boolean -- Whether the SESSION variables were loaded.
     */
    public static function getSessionFromDb($varUsers_ID, $db){
        try { 
            $stmt = $db->prepare("  
                SELECT \"Roles_ID\", \"first_name\", \"last_name\", 
                       \"street\", \"city\", \"state\", \"zip\",
                       \"phone\", \"email\", \"username\", \"zip\", \"notes\"
                FROM \"Users\"
                WHERE \"ID\" = :ID;
            ");

            //Bind parameters and execute the statement:
            $stmt->execute([
                ':ID' => $varUsers_ID
            ]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //Check if the rows exist:
            if(!empty($rows)){
                $_SESSION['SR_Users_ID'] = $varUsers_ID;
                $_SESSION['SR_Roles_ID'] = $rows[0]['Roles_ID'];
                $_SESSION['SR_first_name'] = $rows[0]['first_name'];
                $_SESSION['SR_last_name'] = $rows[0]['last_name'];
                $_SESSION['SR_street'] = $rows[0]['street'];
                $_SESSION['SR_city'] = $rows[0]['city'];
                $_SESSION['SR_state'] = $rows[0]['state'];
                $_SESSION['SR_zip'] = $rows[0]['zip'];
                $_SESSION['SR_phone'] = $rows[0]['phone'];
                $_SESSION['SR_email'] = $rows[0]['email'];
                $_SESSION['SR_usernameOld'] = $rows[0]['username'];
                $_SESSION['SR_username'] = $rows[0]['username'];
                $_SESSION['SR_notes'] = $rows[0]['notes'];
                return true;
            }else{
                echo "No search results returned.";
                return false;
            }

        } catch (PDOException $e) {
            echo "PDO Error: No search results returned.";
            return false;
        }   
    }

    /**   Runs a database search for the criteria entered on the "User Search screen".
     * Creates an SQL-SELECT statement, and runs it over the passed-in db connection.
     * If results are returned, then they are listed vertically as links. After each
     * link is a "Delete" button, that can removed the associated User record from
     * the db.
     * 
     * @param JSON $jsonSearchUsers -- Contains all of the search criteria entered.
     * @param PDO $db -- A database connection.
     */
    public static function searchUsers($jsonSearchUsers, $db){
        try{
            $query = self::buildSearchSQL($jsonSearchUsers);

            if(empty($query)){
                echo "No search results returned.";
            }

            $stmt = $db->prepare($query);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //Check if the rows exist:
            if(!empty($rows)){
                echo "<table id=\"tblSearchResults\">";
                foreach($rows as $row){
                    echo "<tr><td class=\"tdResult\"><a href=\"/TEST/Login/User_Search_Results_Editor.php?varUsers_ID=".$row['ID']."\">".
                         $row['first_name']." ".$row['last_name'].", ".$row['name']."</a><br></td>".
                         "<td><input type=\"button\" class=\"btnDelete button\" value=\"Delete\" data-id=\"".$row['ID']."\"/></td></tr>";
                }
                echo "</table>";

            }else{
                echo "No search results returned.";
            }

        } catch (PDOException $e) {
            echo "No search results returned.";
        }   
    }

    /**   Dynamically builds an SQL-SELECT statement from the search criteria passed in
     * 
     * @param JSON $jsonSearchUsers -- Contains search criteria from the User "Search screen".
     * @return string -- And SQL-SELECT statement.
     */
    public static function buildSearchSQL($jsonSearchUsers){
        $arySearchUsers = json_decode($jsonSearchUsers, true);

        $_SESSION['SU_Include_1'] = $arySearchUsers['Include_1'];   
        $_SESSION['SU_Roles_ID_1'] = $arySearchUsers['Roles_ID_1'];
        $_SESSION['SU_first_name_1'] = $arySearchUsers['first_name_1'];
        $_SESSION['SU_last_name_1'] = $arySearchUsers['last_name_1'];
        $_SESSION['SU_street_1'] = $arySearchUsers['street_1'];
        $_SESSION['SU_city_1'] = $arySearchUsers['city_1'];
        $_SESSION['SU_state_1'] = $arySearchUsers['state_1'];
        $_SESSION['SU_zip_1'] = $arySearchUsers['zip_1'];
        $_SESSION['SU_phone_1'] = $arySearchUsers['phone_1'];
        $_SESSION['SU_email_1'] = $arySearchUsers['email_1'];
        $_SESSION['SU_username_1'] = $arySearchUsers['username_1'];
        $_SESSION['SU_notes_1'] = $arySearchUsers['notes_1'];

        $_SESSION['SU_Include_2'] = $arySearchUsers['Include_2'];   
        $_SESSION['SU_Roles_ID_2'] = $arySearchUsers['Roles_ID_2'];
        $_SESSION['SU_first_name_2'] = $arySearchUsers['first_name_2'];
        $_SESSION['SU_last_name_2'] = $arySearchUsers['last_name_2'];
        $_SESSION['SU_street_2'] = $arySearchUsers['street_2'];
        $_SESSION['SU_city_2'] = $arySearchUsers['city_2'];
        $_SESSION['SU_state_2'] = $arySearchUsers['state_2'];
        $_SESSION['SU_zip_2'] = $arySearchUsers['zip_2'];
        $_SESSION['SU_phone_2'] = $arySearchUsers['phone_2'];
        $_SESSION['SU_email_2'] = $arySearchUsers['email_2'];
        $_SESSION['SU_username_2'] = $arySearchUsers['username_2'];
        $_SESSION['SU_notes_2'] = $arySearchUsers['notes_2'];

        $_SESSION['SU_Include_3'] = $arySearchUsers['Include_3'];   
        $_SESSION['SU_Roles_ID_3'] = $arySearchUsers['Roles_ID_3'];
        $_SESSION['SU_first_name_3'] = $arySearchUsers['first_name_3'];
        $_SESSION['SU_last_name_3'] = $arySearchUsers['last_name_3'];
        $_SESSION['SU_street_3'] = $arySearchUsers['street_3'];
        $_SESSION['SU_city_3'] = $arySearchUsers['city_3'];
        $_SESSION['SU_state_3'] = $arySearchUsers['state_3'];
        $_SESSION['SU_zip_3'] = $arySearchUsers['zip_3'];
        $_SESSION['SU_phone_3'] = $arySearchUsers['phone_3'];
        $_SESSION['SU_email_3'] = $arySearchUsers['email_3'];
        $_SESSION['SU_username_3'] = $arySearchUsers['username_3'];
        $_SESSION['SU_notes_3'] = $arySearchUsers['notes_3'];

        $_SESSION['SU_Include_4'] = $arySearchUsers['Include_4'];   
        $_SESSION['SU_Roles_ID_4'] = $arySearchUsers['Roles_ID_4'];
        $_SESSION['SU_first_name_4'] = $arySearchUsers['first_name_4'];
        $_SESSION['SU_last_name_4'] = $arySearchUsers['last_name_4'];
        $_SESSION['SU_street_4'] = $arySearchUsers['street_4'];
        $_SESSION['SU_city_4'] = $arySearchUsers['city_4'];
        $_SESSION['SU_state_4'] = $arySearchUsers['state_4'];
        $_SESSION['SU_zip_4'] = $arySearchUsers['zip_4'];
        $_SESSION['SU_phone_4'] = $arySearchUsers['phone_4'];
        $_SESSION['SU_email_4'] = $arySearchUsers['email_4'];
        $_SESSION['SU_username_4'] = $arySearchUsers['username_4'];
        $_SESSION['SU_notes_4'] = $arySearchUsers['notes_4'];



        $aryCriteria_1 = [];
        $aryCriteria_2 = [];
        $aryCriteria_3 = [];
        $aryCriteria_4 = [];
        foreach ($arySearchUsers as $key => $value) {
            $segment = '';
            if(!empty($value)){
                if(str_contains($key, 'Roles_ID')){
                    $segment = "\"".substr($key, 0, -2)."\" = $value";

                }else if(!str_contains($key, 'Include')){
                    $segment = "\"".substr($key, 0, -2)."\" ILIKE '%$value%'";
                }

                if(str_ends_with($key, '_1')){
                    $aryCriteria_1[] = $segment;

                }elseif(str_ends_with($key, '_2')){
                    $aryCriteria_2[] = $segment;

                }elseif(str_ends_with($key, '_3')){
                    $aryCriteria_3[] = $segment;

                }elseif(str_ends_with($key, '_4')){
                    $aryCriteria_4[] = $segment;
                }
            }
        }

        $strWHERE_1 = '';
        if($arySearchUsers['Include_1']){
            foreach($aryCriteria_1 as $criterion){
                if($strWHERE_1 !== ''){
                    $strWHERE_1 .= ' AND ';
                }
                $strWHERE_1 .= $criterion;
            }
        }
        $strWHERE_2 = '';
        if($arySearchUsers['Include_2']){
            foreach($aryCriteria_2 as $criterion){
                if($strWHERE_2 !== ''){
                    $strWHERE_2 .= ' AND ';
                }
                $strWHERE_2 .= $criterion;
            }
        }
        $strWHERE_3 = '';
        if($arySearchUsers['Include_3']){
            foreach($aryCriteria_3 as $criterion){
                if($strWHERE_3 !== ''){
                    $strWHERE_3 .= ' AND ';
                }
                $strWHERE_3 .= $criterion;
            }
        }
        $strWHERE_4 = '';
        if($arySearchUsers['Include_4']){
            foreach($aryCriteria_4 as $criterion){
                if($strWHERE_4 !== ''){
                    $strWHERE_4 .= ' AND ';
                }
                $strWHERE_4 .= $criterion;
            }
        }

        if(empty($strWHERE_1) && empty($strWHERE_2) && empty($strWHERE_3) && empty($strWHERE_4)){
            return '';
        }

        $strWHERE_clause = '';
        if($strWHERE_1 !== ''){
           $strWHERE_clause .= " ($strWHERE_1) OR"; 
        }
        if($strWHERE_2 !== ''){
           $strWHERE_clause .= " ($strWHERE_2) OR"; 
        }
        if($strWHERE_3 !== ''){
           $strWHERE_clause .= " ($strWHERE_3) OR"; 
        }
        if($strWHERE_4 !== ''){
           $strWHERE_clause .= " ($strWHERE_4) OR"; 
        }
        $strWHERE_clause = substr($strWHERE_clause, 0, -2);

        $strSQL_SELECT = "SELECT \"Users\".\"ID\", \"first_name\", \"last_name\", \"Roles\".\"name\"".
            " FROM \"Users\" INNER JOIN \"Roles\"".
            " ON \"Roles_ID\" = \"Roles\".\"ID\"".
            ' WHERE '.$strWHERE_clause.
            " ORDER BY \"Roles\".\"name\", \"last_name\", \"first_name\";";

        return $strSQL_SELECT;
    }

    /**   Attempts to delete a record from the "Users" table, and cascade deletions
     * through other tables referencing the "Users_ID". A transaction is used, with
     * the possibility of rollback, in case any of the operations fail.
     * 
     * @param Integer $Users_ID
     * @param PDO $db -- A database connection.
     */
    public static function deleteUser($Users_ID, $db){
        try {
            //Begin the transaction:
            $db->beginTransaction();

            //Prepare the SQL statement:
            $stmt = $db->prepare("DELETE FROM \"Users\" WHERE \"ID\" = :Users_ID");

            //Bind the user ID parameter:
            $stmt->bindParam(':Users_ID', $Users_ID, PDO::PARAM_INT);

            //Execute the statement:
            $stmt->execute();

            //Commit the transaction:
            $db->commit();

            echo "User and related records deleted successfully.";

        } catch (PDOException $e) {
            // Rollback the transaction in case of an error
            $db->rollBack();
            echo "Error: ".$e->getMessage();
        }
    }

    /**   Clears all search criteria in SESSION.
     * 
     */
    public static function clearCriteria(){
        $_SESSION['SU_Include_1'] = true;   
        $_SESSION['SU_Roles_ID_1'] = '';
        $_SESSION['SU_first_name_1'] = '';
        $_SESSION['SU_last_name_1'] = '';
        $_SESSION['SU_street_1'] = '';
        $_SESSION['SU_city_1'] = '';
        $_SESSION['SU_state_1'] = '';
        $_SESSION['SU_zip_1'] = '';
        $_SESSION['SU_phone_1'] = '';
        $_SESSION['SU_email_1'] = '';
        $_SESSION['SU_username_1'] = '';
        $_SESSION['SU_notes_1'] = '';

        $_SESSION['SU_Include_2'] = false;   
        $_SESSION['SU_Roles_ID_2'] = '';
        $_SESSION['SU_first_name_2'] = '';
        $_SESSION['SU_last_name_2'] = '';
        $_SESSION['SU_street_2'] = '';
        $_SESSION['SU_city_2'] = '';
        $_SESSION['SU_state_2'] = '';
        $_SESSION['SU_zip_2'] = '';
        $_SESSION['SU_phone_2'] = '';
        $_SESSION['SU_email_2'] = '';
        $_SESSION['SU_username_2'] = '';
        $_SESSION['SU_notes_2'] = '';

        $_SESSION['SU_Include_3'] = false;   
        $_SESSION['SU_Roles_ID_3'] = '';
        $_SESSION['SU_first_name_3'] = '';
        $_SESSION['SU_last_name_3'] = '';
        $_SESSION['SU_street_3'] = '';
        $_SESSION['SU_city_3'] = '';
        $_SESSION['SU_state_3'] = '';
        $_SESSION['SU_zip_3'] = '';
        $_SESSION['SU_phone_3'] = '';
        $_SESSION['SU_email_3'] = '';
        $_SESSION['SU_username_3'] = '';
        $_SESSION['SU_notes_3'] = '';

        $_SESSION['SU_Include_4'] = false;   
        $_SESSION['SU_Roles_ID_4'] = '';
        $_SESSION['SU_first_name_4'] = '';
        $_SESSION['SU_last_name_4'] = '';
        $_SESSION['SU_street_4'] = '';
        $_SESSION['SU_city_4'] = '';
        $_SESSION['SU_state_4'] = '';
        $_SESSION['SU_zip_4'] = '';
        $_SESSION['SU_phone_4'] = '';
        $_SESSION['SU_email_4'] = '';
        $_SESSION['SU_username_4'] = '';
        $_SESSION['SU_notes_4'] = '';
    }

    /**   Updates User information in the db for the JSON passed in.
     * Whether successful or not, control is returned back to the
     * "Edit User screen". Upon success, a confirmation is displayed
     * in a JavaScript alert. If an error does occur, it is displayed
     * in a JavaScript alert.
     * 
     * @param JSON $jsonEditUser -- Contains User info for the search result
     *      selected and edited.
     * @param PDO $db -- A database connection.
     * @return type
     */
    public static function editUserSubmit($jsonEditUser, $db){
        $aryEditUser = json_decode($jsonEditUser, true);

        $_SESSION['SR_Roles_ID'] = $aryEditUser['selRoles_ID'];
        $_SESSION['SR_first_name'] = $aryEditUser['inFirstName'];
        $_SESSION['SR_last_name'] = $aryEditUser['inLastName'];
        $_SESSION['SR_street'] = $aryEditUser['inStreet'];
        $_SESSION['SR_city'] = $aryEditUser['inCity'];
        $_SESSION['SR_state'] = $aryEditUser['inState'];
        $_SESSION['SR_zip'] = $aryEditUser['inZip'];
        $_SESSION['SR_phone'] = $aryEditUser['inPhone'];
        $_SESSION['SR_email'] = $aryEditUser['inEmail'];
        $_SESSION['SR_username'] = $aryEditUser['inUsername'];
        $_SESSION['SR_password'] = $aryEditUser['inPassword'];
        $_SESSION['SR_notes'] = $aryEditUser['txtNotes'];

        //Check uniqueness of username and password:
        //------------------------------------------
        $uniquenessCheck = true;
        $message = '';
        if($aryEditUser['inUsername'] != $_SESSION['SR_usernameOld'] && !Login_db::isUsernameUnique($aryEditUser['inUsername'], $db)){
            $message .=  'The new username is already used!\n';
            $_SESSION['SR_username'] = '';
            $uniquenessCheck = false;
        }
        if(!empty($aryEditUser['inPassword']) && !Login_db::isPasswordUnique($aryEditUser['inPassword'], $db)){
            $message .= 'The new password is already used!';
            $_SESSION['SR_password'] = '';
            $uniquenessCheck = false;
        }
        if(!$uniquenessCheck){
            User_Search_ui::editUserDisplay($db);
            echo "<script>alert('$message');</script>";
            return;
        }

        try {
            //SQL-UPDATE most fields:
            //-----------------------
            //Prepare and execute the SQL:
            //(We can jump to the update, because we have the Users ID in SESSION.)
            $stmt_1 = $db->prepare("
                UPDATE \"Users\"
                SET \"Roles_ID\" = :selRoles_ID, \"first_name\" = :inFirstName, 
                    \"last_name\" = :inLastName, \"street\" = :inStreet, 
                    \"city\" = :inCity, \"state\" = :inState, \"zip\" = :inZip,
                    \"phone\" = :inPhone, \"email\" = :inEmail, \"username\" = :inUsername, 
                    \"notes\" = :txtNotes
                WHERE \"ID\" = :ID;
            ");

            //Bind parameters and execute the statement:
            $stmt_1->execute([
                ':selRoles_ID' => $aryEditUser['selRoles_ID'],
                ':inFirstName' => $aryEditUser['inFirstName'],
                ':inLastName' => $aryEditUser['inLastName'],
                ':inStreet' => $aryEditUser['inStreet'],
                ':inCity' => $aryEditUser['inCity'],
                ':inState' => $aryEditUser['inState'],
                ':inZip' => $aryEditUser['inZip'],
                ':inPhone' => $aryEditUser['inPhone'],
                ':inEmail' => $aryEditUser['inEmail'],
                ':inUsername' => $aryEditUser['inUsername'],
                ':txtNotes' => $aryEditUser['txtNotes'],
                ':ID' => $_SESSION['SR_Users_ID']
            ]);

            $rowsAffected = $stmt_1->rowCount();

            if($rowsAffected > 0){
                echo "<script>alert('User profile updated ok!');</script>";

            }else{
                echo "<script>alert('Failure updating user profile!');</script>";
            }

            //SQL-UPDATE password field if not empty:
            //---------------------------------------
            if(!empty($aryEditUser['inPassword'])){
                $stmt_2 = $db->prepare("
                    UPDATE \"Users\"
                    SET \"password\" = :inPassword
                    WHERE \"ID\" = :ID;
                ");

                $stmt_2->execute([
                    ':inPassword' => password_hash($aryEditUser['inPassword'], PASSWORD_DEFAULT),
                    ':ID' => $_SESSION['SR_Users_ID']
                ]);

                $rowsAffected = $stmt_2->rowCount();

                if($rowsAffected > 0){
                    echo "<script>alert('Password updated ok!');</script>";
                    $_SESSION['SR_password'] = '';
                    User_Search_ui::editUserDisplay($db);

                } else {
                    echo "<script>alert('Failure updating password!');</script>";
                    $_SESSION['SR_password'] = '';
                    User_Search_ui::editUserDisplay($db);
                }
            }else{
                User_Search_ui::editUserDisplay($db);
            }    

        }catch(PDOException $e){
            echo "<script>alert('PDO Error: Failure updating user profile or password!');</script>";
            User_Search_ui::editUserDisplay($db);
        }
    }
}//End class, "class User_Search_db".