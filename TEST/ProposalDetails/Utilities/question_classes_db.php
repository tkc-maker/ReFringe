<?php
class question_classes_db{
    /**   Attempts to fetch an associative array of [ID, name] from the "Question_classes"
     * table. Upon success, the assoc array is returned. Otherwise, false is returned.
     *
     * @param PDO $db -- A database connection. 
     * @return bool/Assoc Array -- Either the array of IDs and Question Classes or false.
     */
    public static function getAllQuestionClassesFromDb($db){
        try {
            //Execute the statement:
            $stmt = $db->query("SELECT \"ID\", \"name\" FROM \"Question_classes\" ORDER BY 
                                CASE 
                                    WHEN \"name\" = '(Include in ALL)' THEN 0 
                                    ELSE 1 
                                END,
                                \"name\"");

            //Fetch the result:
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //Check if the rows exist:
            if(empty($rows)) {
                echo "No rows found in \"Question_classes\".";
                return false;
            }

            //Return associative array of Question Classes:
            return $rows;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }        
    }//End function, "getAllQuestionClassesFromDb".
    
    /**   Attempts to save an array of "Question_classes" records
     * (their "ID" and "name" values) to the DB. Upon success,
     * true is returned; otherwise, false is returned.
     * 
     * Note: This method executes SQL-UPDATE statements for each
     *       row passed in. The loop of executions is within
     *       a transaction, with the possibility of rollback.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param JSON Array $jsonSelectedQuestionClasses -- (Described above.)
     * @return boolean -- Whether the save succeeded.
     */
    public static function updateSelectedQuestionClasses($db, $jsonSelectedQuestionClasses){
        $arySelectedQuestionClasses = json_decode($jsonSelectedQuestionClasses, true);
        
        try {
            //Begin the transaction:
            $db->beginTransaction();

            foreach($arySelectedQuestionClasses as $item){
                $ID = $item[0];
                $name = $item[1];
                if(empty($ID) || empty($name)){
                    continue;
                }
                $strSqlUpdate = "UPDATE \"Question_classes\" SET \"name\" = '$name' WHERE \"ID\" = $ID";
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
    }//End function, "saveSelectedQuestionClasses".
    
    /**   Attempts to delete an array of "Question_classes" records
     * (their "ID" and "name" values) from the DB. Upon success,
     * true is returned; otherwise, false is returned.
     * 
     * Note: This method executes SQL-DELETE statements for each
     *       row passed in. The loop of executions is within
     *       a transaction, with the possibility of rollback.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param JSON Array $jsonSelectedQuestionClasses -- (Described above.)
     * @return boolean -- Whether the deletion succeeded.
     */
    public static function deleteSelectedQuestionClasses($db, $jsonSelectedQuestionClasses){
        $arySelectedQuestionClasses = json_decode($jsonSelectedQuestionClasses, true);
        
        try {
            //Begin the transaction:
            $db->beginTransaction();

            foreach ($arySelectedQuestionClasses as $item) {
                $ID = $item[0];
                if(empty($ID)){
                    continue;
                }
                $strSqlDelete = "DELETE FROM \"Question_classes\" WHERE \"ID\" = $ID ";
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
    }//End function, "deleteSelectedQuestionClasses".
    

    /**  Attempts to insert a new Question Class name into
     * the "Question_classes" table. If successful, the primary
     * key value of that record is returned. Otherwise, false
     * is returned.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param string $newQuestionClass -- The name of a new Question Class.
     * @return Integer/bool -- Either the primary key, "ID", of the
     *     new Question Class, or false.
     */
    public static function insertNewQuestionClass($db, $newQuestionClass){
        try {
            if(empty($newQuestionClass)){
                return false;
            }
            $strSqlInsert_1 = "INSERT INTO \"Question_classes\" (\"name\") VALUES (:newQuestionClass)";
            $stmt_1 = $db->prepare($strSqlInsert_1);
            $stmt_1->execute([
                ':newQuestionClass' => $newQuestionClass
            ]);
            $rowsAffected_1 = $stmt_1->rowCount();

            if($rowsAffected_1 <= 0){
                return false;
            }
            $lastInsertedID = $db->lastInsertId("question_classes_id_seq");
            
            //We must also create a corresponding record in the "Questions" table:
            $strSqlInsert_2 = "
                INSERT INTO \"Questions\" (\"Proposal_details_ID\", \"Question_classes_ID\")
                VALUES (0, :Question_classes_ID);
            ";
            $stmt_2 = $db->prepare($strSqlInsert_2);
            $stmt_2->execute([
                ':Question_classes_ID' => $lastInsertedID
            ]);
            $rowsAffected_2 = $stmt_2->rowCount();

            if($rowsAffected_2 <= 0){
                return false;
            }
            
            return $lastInsertedID;
            
        }catch (PDOException $e){
            echo "PDO Error: ".$e->getMessage();
            return false;
        }
    }//End function, "insertNewQuestionClass".

}//End class, "question_classes_db".