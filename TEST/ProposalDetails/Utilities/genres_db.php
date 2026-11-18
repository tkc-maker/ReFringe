<?php
class genres_db{
    /**   Attempts to fetch an associative array of [ID, name] from the "Genres"
     * table. Upon success, the assoc array is returned. Otherwise, false is returned.
     * 
     * @param PDO $db -- A database connection. 
     * @return bool/Assoc Array -- Either the array of IDs and Genres or false;
     */
    public static function getAllGenresFromDb($db){
        try {
            //Execute the statement:
            $stmt = $db->query("SELECT \"ID\", \"name\" FROM \"Genres\" ORDER BY \"name\"");

            //Fetch the result:
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //Check if the rows exist:
            if($rows === false) {
                echo "No rows found in \"Genres\".";
                return false;
            }

            //Return associative array of Genres:
            return $rows;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }//End function, "getAllGenresFromDb".
    
    /**   Attempts to save an array of "Genres" records
     * (their "ID" and "name" values) to the DB. Upon success,
     * true is returned; otherwise, false is returned.
     * 
     * Note: This method executes SQL-UPDATE statements for each
     *       row passed in. The loop of executions is within
     *       a transaction, with the possibility of rollback.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param JSON Array $jsonSelectedGenres -- (Described above.)
     * @return boolean -- Whether the save succeeded.
     */
    public static function updateSelectedGenres($db, $jsonSelectedGenres){
        $arySelectedGenres = json_decode($jsonSelectedGenres, true);
        
        try {
            //Begin the transaction:
            $db->beginTransaction();

            if(!is_array($arySelectedGenres)){
                $db->rollBack();
                return false;
            }
            foreach($arySelectedGenres as $item){
                $ID = is_array($item) ? ($item[0] ?? null) : null;
                $name = is_array($item) ? ($item[1] ?? null) : null;
                if(empty($ID) || empty($name)){
                    continue;
                }
                //The values come from the browser, so they are never trusted:
                //the ID must be a whole number, and both values are passed as
                //bound parameters, never joined into the SQL text.
                if(filter_var($ID, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false ||
                   !is_scalar($name)){
                    $db->rollBack();
                    return false;
                }
                $strSqlUpdate = "UPDATE \"Genres\" SET \"name\" = :name WHERE \"ID\" = :ID";
                $stmt = $db->prepare($strSqlUpdate);
                $stmt->execute([
                    ':name' => (string)$name,
                    ':ID' => (int)$ID
                ]);
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
    }//End function, "saveSelectedGenres".
    
    /**   Attempts to delete an array of "Genres" records
     * (their "ID" and "name" values) from the DB. Upon success,
     * true is returned; otherwise, false is returned.
     * 
     * Note: This method executes SQL-DELETE statements for each
     *       row passed in. The loop of executions is within
     *       a transaction, with the possibility of rollback.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param JSON Array $jsonSelectedGenres -- (Described above.)
     * @return boolean -- Whether the deletion succeeded.
     */
    public static function deleteSelectedGenres($db, $jsonSelectedGenres){
        $arySelectedGenres = json_decode($jsonSelectedGenres, true);
        
        try {
            //Begin the transaction:
            $db->beginTransaction();

            if(!is_array($arySelectedGenres)){
                $db->rollBack();
                return false;
            }
            foreach ($arySelectedGenres as $item) {
                $ID = is_array($item) ? ($item[0] ?? null) : null;
                if(empty($ID)){
                    continue;
                }
                //The ID comes from the browser: it must be a whole number,
                //and is passed as a bound parameter.
                if(filter_var($ID, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false){
                    $db->rollBack();
                    return false;
                }
                $strSqlDelete = "DELETE FROM \"Genres\" WHERE \"ID\" = :ID";
                $stmt = $db->prepare($strSqlDelete);
                $stmt->execute([':ID' => (int)$ID]);
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
    }//End function, "deleteGenres".
    

    /**  Attempts to insert a new Genre name into
     * the "Genres" table. If successful, the primary
     * key value of that record is returned. Otherwise,
     * false is returned.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param string $newGenre -- The name of a new Genre.
     * @return Integer/bool -- Either the primary key, "ID", of the
     *     new Genre, or false.
     */
    public static function insertNewGenre($db, $newGenre){
        try {
            if(empty($newGenre)){
                return false;
            }
            $strSqlInsert = "INSERT INTO \"Genres\" (\"name\") VALUES (:newGenre)";
            $stmt = $db->prepare($strSqlInsert);
            $stmt->execute([
                ':newGenre' => $newGenre
            ]);
            $rowsAffected = $stmt->rowCount();

            if($rowsAffected <= 0){
                return false;
            }
            $lastInsertedID = $db->lastInsertId("genres_id_seq");
            
            return $lastInsertedID;
            
        }catch (PDOException $e){
            echo "PDO Error: ".$e->getMessage();
            return false;
        }
    }//End function, "insertNewGenre".

}//End class, "genres_db".