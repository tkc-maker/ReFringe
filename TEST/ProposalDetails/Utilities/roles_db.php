<?php
class roles_db{
    /**   Attempts to fetch an associative array of [ID, name] from the "Roles"
     * table. Upon success, the assoc array is returned. Otherwise, false is returned.
     *
     * @param PDO $db -- A database connection. 
     * @return bool/Assoc Array -- Either the array of IDs and Roles or false.
     */
    public static function getAllRolesFromDb($db){
        try {
            //Execute the statement:
            $stmt = $db->query("SELECT \"ID\", \"name\" FROM \"Roles\" ORDER BY \"name\"");

            //Fetch the result:
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //Check if the rows exist:
            if(empty($rows)) {
                echo "No rows found in \"Roles\".";
                return false;
            }

            //Return associative array of Roles:
            return $rows;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }        
    }//End function, "getAllRolesFromDb".
    
    /**   Attempts to save an array of "Roles" records
     * (their "ID" and "name" values) to the DB. Upon success,
     * true is returned; otherwise, false is returned.
     * 
     * Note: This method executes SQL-UPDATE statements for each
     *       row passed in. The loop of executions is within
     *       a transaction, with the possibility of rollback.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param JSON Array $jsonSelectedRoles -- (Described above.)
     * @return boolean -- Whether the save succeeded.
     */
    public static function updateSelectedRoles($db, $jsonSelectedRoles){
        $arySelectedRoles = json_decode($jsonSelectedRoles, true);
        
        try {
            //Begin the transaction:
            $db->beginTransaction();

            foreach($arySelectedRoles as $item){
                $ID = $item[0];
                $name = $item[1];
                if(empty($ID) || empty($name)){
                    continue;
                }
                $strSqlUpdate = "UPDATE \"Roles\" SET \"name\" = '$name' WHERE \"ID\" = $ID";
                $stmt = $db->prepare($strSqlUpdate);
                $stmt->execute();
                $rowsAffected = $stmt->rowCount();

                if($rowsAffected <= 0){
                    //Rollback the transaction in case of an error:
                    $db->rollBack();
                    return false;
                }
            }        
            //Commit the transaction:
            $db->commit();
            
        }catch (PDOException $e){
            //Rollback the transaction in case of an error:
            $db->rollBack();
            echo "PDO Error: ".$e->getMessage();
            return false;
        }
        return true;            
    }//End function, "saveSelectedRoles".
    
    /**   Attempts to delete an array of "Roles" records
     * (their "ID" and "name" values) from the DB. Upon success,
     * true is returned; otherwise, false is returned.
     * 
     * Note: This method executes SQL-DELETE statements for each
     *       row passed in. The loop of executions is within
     *       a transaction, with the possibility of rollback.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param JSON Array $jsonSelectedRoles -- (Described above.)
     * @return boolean -- Whether the deletion succeeded.
     */
    public static function deleteSelectedRoles($db, $jsonSelectedRoles){
        $arySelectedRoles = json_decode($jsonSelectedRoles, true);
        
        try {
            //Begin the transaction:
            $db->beginTransaction();

            foreach ($arySelectedRoles as $item) {
                $ID = $item[0];
                if(empty($ID)){
                    continue;
                }
                $strSqlDelete = "DELETE FROM \"Roles\" WHERE \"ID\" = $ID ";
                $stmt = $db->prepare($strSqlDelete);
                $stmt->execute();
                $rowsAffected = $stmt->rowCount();

                if($rowsAffected <= 0){
                    //Rollback the transaction in case of an error:
                    $db->rollBack();
                    return false;
                }
            }        
            //Commit the transaction:
            $db->commit();
            
        }catch (PDOException $e){
            //Rollback the transaction in case of an error:
            $db->rollBack();
            echo "PDO Error: ".$e->getMessage();
            return false;
        }
        return true;            
    }//End function, "deleteRoles".
    

    /**  Attempts to insert a new Role name into
     * the "Roles" table. If successful, the primary
     * key value of that record is returned. Otherwise,
     * false is returned.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param string $newRole -- The name of a new Role.
     * @return Integer/bool -- Either the primary key, "ID", of the
     *     new Role, or false.
     */
    public static function insertNewRole($db, $newRole){
        try {
            if(empty($newRole)){
                return false;
            }
            $strSqlInsert = "INSERT INTO \"Roles\" (\"name\") VALUES (:newRole)";
            $stmt = $db->prepare($strSqlInsert);
            $stmt->execute([
                ':newRole' => $newRole
            ]);
            $rowsAffected = $stmt->rowCount();

            if($rowsAffected <= 0){
                return false;
            }
            $lastInsertedID = $db->lastInsertId("roles_id_seq");
            
            return $lastInsertedID;
            
        }catch (PDOException $e){
            echo "PDO Error: ".$e->getMessage();
            return false;
        }
    }//End function, "insertNewRole".

}//End class, "roles_db".