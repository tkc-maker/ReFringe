<?php
class questions_answers_db{
    
    /**   Attempts to fetch a JSON array of "questions_answers" fields from the "Questions" table
     * for the passed-in "$Proposal_details_ID", consisting of representative questions/answers
     * under all selected question classes. Upon success, the JSON Indexed Associative Array is 
     * returned. 
     * 
     * (Notice the format of the returned JSON. It is possible for error message(s)
     * to be returned with a status of false, along with a "questions_answers" array. This would
     * happen if the array was fetched without problems, but the orphaned "Questions" records
     * could not be removed.)
     * 
     * @param Integer $Proposal_details_ID
     * @param JSON Indexed Associative Array $jsonQuestion_classes
     * @param PDO $db -- A database connection.
     * @return JSON Indexed Associative Array -- A "status" indicating whether the fetch succeeded,
     *    and either a "aryQuestionsAnswers" property and/or an "errMsg" property (if "status" is false).
     *    Here is the format of the JSON:
     * 
     *      [{"status": bool,
     *        "errMsg": string,
     *        "aryQuestionsAnswers":
     *            [{“Question_classes_ID”: <id>, 
     *              "Question_classes_name: "string",
     *              “questions_answers”: ‘[{“question”: “<question>”, “answer”: “<answer>”}, . . .]’},
     *             {…}, 
     *             …]
     *       }
     *      ]
     */
    public static function fetchProposalQuestionsAnswersForAllQuestionClassesFromDb($Proposal_details_ID, $jsonQuestion_classes, $db){
        $aryQuestion_classes = json_decode($jsonQuestion_classes, true);
        
        $errMsg = '';
        $aryResults = [];
        foreach ($aryQuestion_classes as $item) {
            $Question_classes_ID = $item['ID'];
            $Question_classes_name = $item['name'];
            
            $jsonQuestionsAnswers = self::getProposalQuestionsAnswersFromDb($Proposal_details_ID, $Question_classes_ID, $db);
            $aryQuestionsAnswers = json_decode($jsonQuestionsAnswers, true);
            
            if($aryQuestionsAnswers['status'] === true){
                $aryResults[] = ["Question_classes_ID" => $Question_classes_ID,
                                 "Question_classes_name" => $Question_classes_name,
                                 "questions_answers" => $aryQuestionsAnswers['questionsAnswers']];
            }else{
                $errMsg .= "\n ".$aryQuestionsAnswers['errMsg']." For '$Question_classes_name' (#$Question_classes_ID).";
            }         
        }
        
        $deleted = self::deleteProposalQuestionsAnswersFromView($Proposal_details_ID, $db);        
        if($deleted === false){
            $errMsg .= "\nOrphaned records could not be deleted from \"Questions\".";                     
        }
        
        //If there are error messages, set the status to false:
        $status = true;
        if(!empty($errMsg)){
            $status = false;
        }
        
        //If an array has been returned, sort it alphabetically by the "Question_classes_name" property:
        if(!empty($aryResults)){
            usort($aryResults, function($a, $b){
                return strcasecmp($a["Question_classes_name"], $b["Question_classes_name"]);
            });
        }
        return json_encode(["status" => $status, "errMsg" => $errMsg, "aryQuestionsAnswers" => $aryResults]);
        
    }//End function, "fetchProposalQuestionsAnswersForAllQuestionClassesFromDb".
    
    /**   Attempts to fetch the contents of the "questions_answers" field in the "Questions" table
     * for the passed in IDs. Upon success, true is returned along with the JSON Indexed Associative Array; 
     * Otherwise, false is returned along with an "errMsg".
     * 
     * @param Integer $proposal_details_ID
     * @param Integer $question_classes_ID
     * @param PDO $db -- A database connection.
     * @return JSON Indexed Associative Array -- A "status" indicating whether the fetch succeeded,
     *    and either a "questionsAnswers" property or an "errMsg" property (if "status" is false).
     */
    public static function getProposalQuestionsAnswersFromDb($proposal_details_ID, $question_classes_ID, $db){   
        try{
            if(!empty($question_classes_ID)){
                //Prepare and execute an SQL-SELECT statement to retrieve the "Questions" 
                //record for the variables passed in:
                //-----------------------------------------------------------------------
                $stmt_1 = $db->prepare("SELECT \"questions_answers\" FROM \"Questions\"".
                    " WHERE \"Question_classes_ID\" = :question_classes_ID AND \"Proposal_details_ID\" = :proposal_details_ID");
                //Bind parameters and execute the statement:
                $stmt_1->execute([':proposal_details_ID' => $proposal_details_ID,
                                  ':question_classes_ID' => $question_classes_ID]);
                $selectedQuestionsAnswers = $stmt_1->fetch(PDO::FETCH_ASSOC);

                if(!empty($selectedQuestionsAnswers)){
                    return json_encode(["status" => true, "questionsAnswers" => $selectedQuestionsAnswers['questions_answers']]);

                }else{
                    //Prepare and execute an SQL_INSERT statement to create a new  
                    //"Questions" record based upon the selected Question_class template:
                    //-------------------------------------------------------------------
                    $stmt_2 = $db->prepare("INSERT INTO \"Questions\" (\"Question_classes_ID\",".
                            "\"Proposal_details_ID\", \"questions_answers\")".
                            " SELECT \"Question_classes_ID\", :proposal_details_ID,". 
                                "\"questions_answers\"::jsonb".
                            " FROM \"Questions\"". 
                            " WHERE \"Question_classes_ID\" = :question_classes_ID AND". 
                                " \"Proposal_details_ID\" = 0");    
                    //Bind parameters and execute the statement:
                    $stmt_2->execute([':proposal_details_ID' => $proposal_details_ID,
                                      ':question_classes_ID' => $question_classes_ID]);

                    $rowsAffected = $stmt_2->rowCount();

                    if($rowsAffected > 0){
                        //Prepare and execute again an SQL-SELECT statement to retrieve the "Questions" 
                        //record for the passed-in "proposal_details_ID" and "question_classes_ID" values:
                        //--------------------------------------------------------------------------------
                        $stmt_3 = $db->prepare("SELECT \"questions_answers\" FROM \"Questions\"".
                            " WHERE \"Question_classes_ID\" = :question_classes_ID AND \"Proposal_details_ID\" = :proposal_details_ID");
                        //Bind parameters and execute the statement:
                        $stmt_3->execute([':proposal_details_ID' => $proposal_details_ID,
                                          ':question_classes_ID' => $question_classes_ID]);
                        $selectedQuestionsAnswers = $stmt_3->fetch(PDO::FETCH_ASSOC);

                        if (empty($selectedQuestionsAnswers) === false) {
                            return json_encode(["status" => true, "questionsAnswers" => $selectedQuestionsAnswers['questions_answers']]);

                        } else {
                            return json_encode(["status" => false, 'errMsg' => "New \"Questions\" record could not be fetched."]);
                        }
                    }else{
                        return json_encode(["status" => false, "errMsg" => "New \"Questions\" record could not be created."]);
                    }
                }
            }else{
                return json_encode(["status" => false, "errMsg" => "Invalid passed-in \"proposal_details_ID\" or \"question_classes_ID\"."]);
            }

        } catch (PDOException $e) {
            return json_encode(["status" => false, "errMsg" => "PDOError: ".$e->getMessage()]);
        }
    }//End function, "getProposalQuestionsAnswersFromDb".
    
    /**   Attempts to insert a questions/answers record from the No Login Proposal Editor
     * into the database. Upon success, JSON is returned with a "status" of "true" and
     * a message. Upon failure, "status" = "false", and "errMsg" is populated.
     * 
     * @param int $Proposal_details_ID
     * @param int $Question_classes_ID
     * @param JSON $jsonQuestionsAnswers -- A JSON array of {"question", "answer"}
     *      objects.
     * @param PDO $db -- A database connection.
     * @return JSON -- Either a success or an error message.
     */
    public static function insertQuickEasyNoLoginQuestionsAnswers($Proposal_details_ID,
                                                                  $Question_classes_ID,
                                                                  $jsonQuestionsAnswers, $db){
        try{
            //Prepare and execute an SQL_INSERT statement to create a new  
            //"Questions" record based upon the selected Question_class template:
            //-------------------------------------------------------------------
            $stmt = $db->prepare("INSERT INTO \"Questions\" (\"Proposal_details_ID\",".
                    " \"Question_classes_ID\", \"questions_answers\")".
                    " VALUES (:Proposal_details_ID, :Question_classes_ID, :questions_answers::jsonb)");
            
            //Bind parameters and execute the statement:
            $stmt->execute([':Proposal_details_ID' => $Proposal_details_ID,
                            ':Question_classes_ID' => $Question_classes_ID,
                            ':questions_answers' => $jsonQuestionsAnswers
                           ]);

            $rowsAffected = $stmt->rowCount();
            
            if($rowsAffected > 0){
                return json_encode(["status" => "true", "errMsg" => "New \"Questions\" record created ok."]);

            }else{
                return json_encode(["status" => "false", "errMsg" => "New \"Questions\" record could not be created."]);
            }
        }catch(PDOException $e) {
            return json_encode(["status" => "false", "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
    }
    
    /**   Attempts to delete a record from the "Questions" table , via 
     * the "orphan_questions_of_proposal_details_view", for the passed in 
     * "$proposal_details_ID". Upon success, true is returned; Otherwise, 
     * false is returned. 
     * 
     * @param Integer $proposal_details_ID
     * @param PDO $db -- A database connection.
     * @return bool -- Whether the deletion succeeded.
     */
    public static function deleteProposalQuestionsAnswersFromView($proposal_details_ID, $db){   
        try{
            if(!empty($proposal_details_ID)){
                //Prepare and execute an SQL_DELETE statement to remove
                //a "Questions" record from the DB:
                //-----------------------------------------------------
                $stmt = $db->prepare("DELETE FROM \"Questions\" USING \"orphan_questions_of_proposal_details_view\" v ".
                          "WHERE \"Questions\".\"ID\" = v.\"Questions_ID\" AND ".
                          "v.\"Proposal_details_ID\" = :proposal_details_ID");    
                //Bind parameters and execute the statement:
                $stmt->execute([':proposal_details_ID' => $proposal_details_ID]);

                //Whether, or not, rows were affected, return true,
                //because the checkboxes may have been unchanged in the UI:
                return true;
                
            }else{
                return false;
            }

        } catch (PDOException $e) {
            return false;
        }
    }//End function, "deleteProposalQuestionsAnswersFromDb".
    
    /**    Attempts to save all updated answers to the database. Loops through the passed-in "Question_classes_ID"
     * values and associated "questions_answers" bocks, updating corresponding records in the "Questions" table,
     * for the passed-in "Proposal_details_ID".
     * 
     * @param Integer $Proposal_details_ID
     * @param JSON Indexed Associative Array $jsonClassesQuestionsAnswers -- A JSON array of "Question_classes_ID"
     *      and associated "question_answer" values, for the "Proposal_details_ID" passed in. The following format
     *      is expected for the JSON:
     * 
     *          [{“Question_classes_ID”: <id>, 
     *            “questions_answers”: ‘[{“question”: “<question>”, “answer”: “<answer>”}, . . .]'},
     *           . . .]
     * 
     * @param PDO $db -- A database connection.
     * @return JSON Associative Array -- A "status" property indicating whether all updates 
     *    succeeded, and an "errMsg" property (if at least one "status" is false).
     */
    public static function updateProposalAnswersForAllQuestionClassesInDb($Proposal_details_ID, $jsonClassesQuestionsAnswers, $db){
        //Only decode the top-level. Leave each "question_answer" block as JSON:
        $aryClassesQuestionsAnswers = json_decode($jsonClassesQuestionsAnswers, true);
        $errMsg = '';
        foreach ($aryClassesQuestionsAnswers as $item){
            $Question_classes_ID = $item['Question_classes_ID'];
            $jsonQuestionsAnswers = json_encode($item['questions_answers']);
            $jsonResults = self::updateProposalAnswersInDb($Proposal_details_ID, $Question_classes_ID, $jsonQuestionsAnswers, $db);
            $results = json_decode($jsonResults, true);
            
            if($results["status"] === false){
                $errMsg .= "\n".$results["errMsg"];
            }
        }
        
        //If there are error messages, set the status to false:
        $status = true;
        if(!empty($errMsg)){
            $status = false;
        }
        
        return json_encode(["status" => $status, "errMsg" => $errMsg]);
        
    }//End function, "updateProposalAnswersForAllQuestionClassesInDb".
    
    /**   Attempts to update a single record in the "Questions" table with the passed-in JSON string (to the 
     * "questions_answers" field). The "Questions" record is uniquely identified by the passed-in IDs. Upon 
     * success, a "status" of true is returned; otherwise, a "status" of false is returned with an "errMsg". 
     * 
     * @param Integer $Proposal_details_ID
     * @param Integer $Question_classes_ID
     * @param JSON Indexed Associative Array $jsonQuestionsAnswers
     * @param PDO $db -- A database connection.
     * @return JSON Associative Array -- A "status" property indicating whether the update 
     *    succeeded with an "errMsg" property (if "status" is false).
     */
    public static function updateProposalAnswersInDb($Proposal_details_ID, $Question_classes_ID, $jsonQuestionsAnswers, $db){
        if (empty($Question_classes_ID) || empty($Proposal_details_ID)) {
            return(json_encode(["status" => false, "errMsg" => "Invalid \"Proposal_details_ID\" or \"Question_classes_ID\" values input."]));
        }

        try{
            $aryResults = [];
            
            //Prepare the SQL statement with placeholders:
            $stmt = $db->prepare("
                UPDATE \"Questions\" 
                SET \"questions_answers\" = :jsonQuestionsAnswers::jsonb
                WHERE \"Question_classes_ID\" = :Question_classes_ID AND 
                      \"Proposal_details_ID\" = :Proposal_details_ID;
            ");

            // Bind parameters and execute the statement
            $stmt->execute([':Question_classes_ID' => $Question_classes_ID,
                            ':Proposal_details_ID' => $Proposal_details_ID,
                            ':jsonQuestionsAnswers' => $jsonQuestionsAnswers]);

            $rowsAffected = $stmt->rowCount();

            if($rowsAffected > 0){
                $aryResults = ["status" => true];

            } else {
                $aryResults = ["status" => false,  "errMsg" => "Questions-Answers update failed for ".
                    "\"Proposal_details_ID\" = ".$Proposal_details_ID." and \"Question_classes_ID\" = ".$Question_classes_ID];
            }
            
            return json_encode($aryResults);

        }catch(PDOException $e){
            return json_encode(["status" => false, "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
    }//End function, "updateProposalAnswersInDb".
    
}//End class, "questions_answers_db".
