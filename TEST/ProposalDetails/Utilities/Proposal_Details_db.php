<?php
class Proposal_Details_db{
    /**   Attempts to retrieve the "Proposal_details" record corresponding to the ID passed in.
     * If such a record doesn't exist ("$Proposal_details_ID" == null or 0), then an "empty" one 
     * is created, containing only non-empty values for the created "ID" and "Users_ID" fields. 
     * So, upon success, a JSON array should be returned, containing the following fields: 
     * "ID", "Users_ID", "title", "Venues_ID", and "question_classes", the last being a JSON 
     * array of "ID" and "name" pairs. Should any of these operations fail, then false is returned
     * with an error message .
     * 
     * @param Integer $Proposal_details_ID
     * @param Integer $Users_ID
     * @param PDO $db -- A database connection.
     * @return JSON Associative Array -- A "status" indicating whether the fetch succeeded,
     *    and either a "proposalDetails" property or an "errMsg" property (if "status" is false).
     */
    public static function getProposalDetailsFromDb($Proposal_details_ID, $Users_ID, $db){   
        try{
            if(!empty($Users_ID)){
                //Prepare and execute an SQL-SELECT statement to retrieve the
                //"Proposal_Details" record for the variable passed in:
                //-----------------------------------------------------------
                $stmt_1 = $db->prepare("
                    SELECT \"ID\", \"Users_ID\", \"title\", \"question_classes\" FROM \"Proposal_details\"
                    WHERE \"ID\" = :Proposal_details_ID;
                ");
                //Bind parameters and execute the statement:
                $stmt_1->execute([':Proposal_details_ID' => $Proposal_details_ID]);
                $selectedProposalDetails = $stmt_1->fetch(PDO::FETCH_ASSOC);

                if($Proposal_details_ID != 0 || $selectedProposalDetails !== false){
                    $proposalDetails = json_encode($selectedProposalDetails, true);
                    return json_encode(["status" => 'true', "proposalDetails" => $proposalDetails]);

                }else{
                    //Prepare and execute an SQL_INSERT statement to create a new  
                    //"Proposal_details" record for the passed-in "Users_ID:
                    //-----------------------------------------------------------
                    $stmt_2 = $db->prepare("
                          INSERT INTO \"Proposal_details\" (\"Users_ID\")
                          VALUES(:Users_ID)
                          RETURNING \"ID\";
                     ");    
                    //Bind parameters and execute the statement:
                    $stmt_2->execute([':Users_ID' => $Users_ID]);

                    $lastInsertedID = $stmt_2 -> fetchColumn();
                    if($lastInsertedID === false){
                        return json_encode(["status" => 'false', "errMsg" => "Error: New \"Proposal_details\" record could not be created."]);
                    }
                        
                    $newProposalDetails = [
                        'ID' => $lastInsertedID,
                        'Users_ID' => (int)$Users_ID,
                        'title' => '',
                        'question_classes' => '[]'
                    ];
                    return json_encode(["status" => 'true', "proposalDetails" => $newProposalDetails]);
                }
            }else{
                return json_encode(["status" => 'false', "errMsg" => "Error: Invalid passed-in \"Users_ID\"."]);
            }

        } catch (PDOException $e) {
            return json_encode(["status" => 'false', "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
    }//End function, "getProposalDetailsFromDb".
    
    /**   Checks if the passed in "Proposal_details_ID" is in a record of the "sched_Calendar" table.
     * Upon success, returns a "status" of 'true'. If the fetch failed, then a status of 'false' is
     * returned. In the case of 'false', an "errMsg" may also be returned.
     * 
     * @param int $Proposal_details_ID
     * @param PDO $db -- A database connection.
     * @return JSON Associative Array -- A "status" indicating whether the fetch succeeded,
     *    and possibly an "errMsg" property (if "status" is false).
     */
    public static function checkForScheduledEvent($Proposal_details_ID, $db){
        try{
            if(!empty($Proposal_details_ID)){
                $stmt = $db->prepare("
                    SELECT \"ID\" FROM \"sched_Calendar\" 
                    WHERE \"Proposal_details_ID\" = :Proposal_details_ID;
                ");
                
                //Bind parameters and execute the statement:
                $stmt->execute([':Proposal_details_ID' => $Proposal_details_ID]);
                $selectedEvent = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if($selectedEvent !== false){
                    return json_encode(["status" => 'true']);
                }else{
                    return json_encode(["status" => 'false']);
                }
            
            }else{
                return json_encode(["status" => 'false', "errMsg" => "Error: Invalid passed-in \"Proposal_details_ID\"."]);
            }

        } catch (PDOException $e) {
            return json_encode(["status" => 'false', "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
    }//End function, "checkForScheduledEvent".
    
    /**    Inserts a minimal record into the "Proposal_details" table, containing only a "Users_ID"
     * and a "title" for a Quick Easy No Login proposal. Either returns a "Proposal_details_ID"
     * value, or a JSON error message.
     * 
     * @param int $Users_ID
     * @param int $title
     * @param PDO $db -- A database connection.
     * @return (int/JSON) Either the primary key value of the inserted record, or a JSON error message.
     */
    public static function insertQuickEasyNoLoginProposalDetails($Users_ID, $proposalTitle, $question_classes, $db){
        try{
            //Prepare and execute an SQL_INSERT statement to create a new  
            //"Proposal_details" record for the passed-in "Users_ID:
            //-----------------------------------------------------------
            $stmt = $db->prepare("
                  INSERT INTO \"Proposal_details\" (\"Users_ID\", \"title\", \"question_classes\")
                  VALUES(:Users_ID, :title, :question_classes::jsonb)
                  RETURNING \"ID\";
             ");    
            //Bind parameters and execute the statement:
            $stmt -> execute([
                ':Users_ID' => $Users_ID,
                ':title' => $proposalTitle,
                ':question_classes' => $question_classes
            ]);

            $lastInsertedID = $stmt -> fetchColumn();
            if($lastInsertedID === false){
                return json_encode(["status" => "false", "errMsg" => "Error: New \"Proposal_details\" record could not be created."]);
            }
            return $lastInsertedID;
            
        } catch (PDOException $e) {
            return json_encode(["status" => "false", "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
    }
    
    /**   Attempts to SQL-UPDATE a "Proposal_details" record for the passed-in "Proposal_details_ID",
     * setting the "updated_date_time" field to the current date/time value. Upon success, a "status" 
     * of true is returned. Otherwise, a "status" of false is returned with an "errMsg".
     * 
     * @param integer $Proposal_details_ID
     * @param PDO $db -- A database connection.
     * @return JSON Associative Array -- A "status" indicating whether the update succeeded,
     *    and possibly an "errMsg" property (if "status" is false).
     */
    public static function updateProposalDetailsDateTimeInDb($Proposal_details_ID, $db){
        try{
            if(!empty($Proposal_details_ID)){
                //Prepare and execute an SQL-UPDATE statement to update the 
                //"title" and "question_classes" fields for the passed-in values:
                //---------------------------------------------------------------
                $stmt = $db->prepare("
                     UPDATE \"Proposal_details\"
                     SET \"updated_date_time\" = :UDT
                     WHERE \"ID\" = :Proposal_details_ID;
                ");

                //Bind parameters and execute the statement:
                $stmt->execute([
                    ':Proposal_details_ID' => $Proposal_details_ID,
                    ':UDT' => date("Y-m-d H:i:s")
                ]);

                $rowsAffected = $stmt->rowCount();

                if($rowsAffected > 0){
                    return json_encode(["status" => true]);
                    
                }else{
                    return json_encode(["status" => false, "errMsg" => "Error: The \"updated_date_time\" field could not be updated in db."]);
                }

            }else{
                return json_encode(["status" => false, "errMsg" => "Error: Invalid \"Proposal_details_ID\" passed in."]);
            }
        }catch (PDOException $e){
            return json_encode(["status" => false, "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
    }//End function, "updateProposalDetailsDateTimeInDb".
    
    /**   Attempts to SQL-UPDATE a "Proposal_details" record for the passed-in "Proposal_details_ID",
     * and values for "title" and "selectedQuestionClasses". Upon success, a "status" of true is returned. 
     * Otherwise, a "status" of false is returned with an "errMsg".
     * 
     * @param integer $Proposal_details_ID
     * @param string $title
     * @param indexed assoc. array $selectedQuestionClasses
     * @param PDO $db -- A database connection.
     * @return JSON Associative Array -- A "status" indicating whether the update succeeded,
     *    and possibly an "errMsg" property (if "status" is false).
     */
    public static function updateProposalDetailsInDb($Proposal_details_ID, $title, 
                                                     $selectedQuestionClasses, $db){
        try{
            if(!empty($Proposal_details_ID)){
                //Prepare and execute an SQL-UPDATE statement to update the 
                //"title" and "question_classes" fields for the passed-in values:
                //---------------------------------------------------------------
                $stmt = $db->prepare("
                     UPDATE \"Proposal_details\"
                     SET \"title\" = :title, 
                     \"question_classes\" = :selectedQuestionClasses::jsonb
                     WHERE \"ID\" = :Proposal_details_ID;
                ");

                //Bind parameters and execute the statement:
                $stmt->execute([
                    ':Proposal_details_ID' => $Proposal_details_ID,
                    ':title' => $title,
                    ':selectedQuestionClasses' => $selectedQuestionClasses
                ]);

                $rowsAffected = $stmt->rowCount();

                if($rowsAffected > 0){
                    return json_encode(["status" => true]);
                    
                }else{
                    return json_encode(["status" => false, "errMsg" => "Error: Selected Question Classes could not be updated in db."]);
                }

            }else{
                return json_encode(["status" => false, "errMsg" => "Error: Invalid \"Proposal_details_ID\" passed in."]);
            }
        }catch (PDOException $e){
            return json_encode(["status" => false, "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
    }//End function, "updateProposalDetailsInDb".
    
    /**   Attempts to remove a record from the "Proposal_details" table
     * for the ID passed in. Upon success, a "status" of true is returned. 
     * Otherwise, a "status" of false is returned with an "errMsg".
     * 
     * @param integer $Proposal_details_ID
     * @param PDO $db -- A database connection.
     * @return JSON Associative Array -- A "status" indicating whether the deletion succeeded,
     *    and possibly an "errMsg" property (if "status" is false).
     */
    public static function deleteProposalFromDb($Proposal_details_ID, $db){
        try{
            if(!empty($Proposal_details_ID)){
                //Prepare and execute an SQL-DELETE statement to remove a 
                //record from the "Proposal_details" table for the passed-in 
                //"$Proposal_details_ID":
                //----------------------------------------------------------------
                $stmt = $db->prepare("
                     DELETE FROM \"Proposal_details\"
                     WHERE \"ID\" = :Proposal_details_ID;
                ");

                //Bind parameters and execute the statement:
                $stmt->execute([
                    ':Proposal_details_ID' => $Proposal_details_ID
                ]);

                $rowsAffected = $stmt->rowCount();

                if($rowsAffected > 0){
                    return json_encode(["status" => true]);
                    
                }else{
                    return json_encode(["status" => false, "errMsg" => "Error: Selected \"Proposal_details\" record could not be deleted from db."]);
                }
                
            }else{
                return json_encode(["status" => false, "errMsg" => "Error: Invalid \"Proposal_details_ID\" passed in."]);
            }
        }catch (PDOException $e){
            return json_encode(["status" => false, "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
    }//End function, "deleteProposalFromDb".
    
}//End class "Proposal_Details_db".