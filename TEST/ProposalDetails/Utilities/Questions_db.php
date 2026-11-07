<?php

class Questions_db{
    
    /**   Attempts to fetch a JSON-encoded array of questions/answers in the format,
     * '[{"question": "...", "answer": ""}, {...}, ...]', consistent with the passed-in
     * "$selectedQuestion_classes_ID". If successful, the JSON array is returned; otherwise,
     * an error message is returned.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param integer $selectedQuestion_classes_ID -- The primary key value of the selected
     *      question class.
     * @return JSON-Array/string -- (Described above.)
     */
    public static function loadSelectedQuestionsFromDb($db, $selectedQuestion_classes_ID){
        try{
            if($selectedQuestion_classes_ID){
                $stmt = $db->prepare("SELECT \"questions_answers\" FROM \"Questions\" WHERE \"Question_classes_ID\" = :id AND \"Proposal_details_ID\" = 0");
                $stmt->execute([':id' => $selectedQuestion_classes_ID]);
                $selectedQuestions = $stmt->fetch(PDO::FETCH_ASSOC);

                if(!empty($selectedQuestions['questions_answers'])){
                    $questionsAnswers = json_decode($selectedQuestions['questions_answers'], true);
                    return json_encode($questionsAnswers);
                    
                } else {
                    return json_encode('Error: No results returned.');
                }
            } else {
                return json_encode('Error: Invalid "Question_classes_ID" entered.');
            }
        } catch (PDOException $e){
            return json_encode('PDO Error: '.$e->getMessage());
        }
    }//End function, "loadSelectedQuestionsFromDb".
    
    /**   Attempts to update a specific question in a JSONb field in the DB.
     * Upon success, a positive message is returned; otherwise a negative
     * message is returned.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param integer $ordinal -- The question's position in the JSONb field.
     * @param integer $selectedQuestion_classes_ID -- The primary key value
     *      of the current question class.
     * @param string $updatedQuestion -- The edited question.
     * @return JSON -- Whether the edited question was updated in the database.
     */
    public static function updateQuestionInDb($db, $ordinal, $selectedQuestion_classes_ID, $updatedQuestion){
        if (empty($selectedQuestion_classes_ID) || empty($ordinal) || empty($updatedQuestion)) {
            return json_encode(['status' => 'error', 'message' => 'Invalid input']);
        }

        try{
            //Prepare the SQL statement with placeholders:
            $stmt = $db->prepare("
                UPDATE \"Questions\" 
                SET \"questions_answers\" = jsonb_set(
                    \"questions_answers\",
                    '{".($ordinal - 1).", question}',
                    :updatedQuestion::jsonb
                ) 
                WHERE \"Question_classes_ID\" = :id AND \"Proposal_details_ID\" = 0
            ");

            //Bind parameters and execute the statement:
            $stmt->execute([
                ':id' => $selectedQuestion_classes_ID,
                ':updatedQuestion' => json_encode($updatedQuestion)
            ]);

            $rowsAffected = $stmt->rowCount();

            if($rowsAffected > 0){
                return json_encode('Question updated ok.');
                
            } else {
                return json_encode('Error: Question update failed.');
            }
        }catch(PDOException $e){
            return json_encode('PDO Error: '.$e->getMessage());
        }
    }//End function, "updateQuestionInDb".
    
    /**   Attempts to delete a specific question from a JSONb field in the DB.
     * Upon success, a positive message is returned; otherwise a negative
     * message is returned.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param integer $ordinal -- The question's position in the JSONb field.
     * @param integer $selectedQuestion_classes_ID -- The primary key value
     *      of the current question class.
     * @return JSON -- Whether the selected question was deleted in the database.
     */
    public static function deleteQuestionInDb($db, $ordinal, $selectedQuestion_classes_ID){
        if (empty($selectedQuestion_classes_ID) || empty($ordinal)){
            return json_encode('Error: Invalid input');
        }

        try{
            //Prepare the SQL statement with placeholders:
            $stmt = $db->prepare("
                UPDATE \"Questions\" 
                SET \"questions_answers\" = (
                    SELECT jsonb_agg(elem)
                    FROM jsonb_array_elements(\"questions_answers\") WITH ORDINALITY arr(elem, idx)
                    WHERE idx != :ordinal
                )
                WHERE \"Question_classes_ID\" = :id AND \"Proposal_details_ID\" = 0
            ");

            // Bind parameters and execute the statement
            $result = $stmt->execute([
                ':ordinal' => $ordinal,
                ':id' => $selectedQuestion_classes_ID
            ]);
            $rowsAffected = $stmt->rowCount();

            if($rowsAffected > 0){
                return json_encode('Success: Question deleted ok.');
            } else {
                return json_encode('Error: Question deletion failed');
            }
        }catch(PDOException $e){
            return json_encode('PDO Error: '.$e->getMessage());
        }
    }//End function, "deleteQuestionInDb".
    
    /**   Attempts to add a new question to a JSONb field in the DB.
     * Upon success a positive message is returned; Otherwise, a
     * negative message is returned.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param integer $selectedQuestion_classes_ID -- The primary key value
     *      of the current question class.
     * @param $new_question -- A new question to be added under the current
     *      question class.
     * @return JSON -- Whether the new question was added in the database.
     */
    public static function addQuestionInDb($db, $selectedQuestion_classes_ID, $new_question){
        try{
            if(empty($selectedQuestion_classes_ID)){
                echo json_encode('Error: Invalid "Question_classes_ID" value.');
            }    
            $stmt = $db->prepare("
                UPDATE \"Questions\" SET \"questions_answers\" = \"questions_answers\" || :new_question::jsonb
                WHERE \"Question_classes_ID\" = :id AND \"Proposal_details_ID\" = 0
            ");

            $stmt->execute([
                ':new_question' => $new_question,
                ':id' => $selectedQuestion_classes_ID
            ]);

            $rowsAffected = $stmt->rowCount();

            if($rowsAffected > 0){
                echo json_encode('New Question added ok.');
            } else {
                echo json_encode('Error: Adding new question failed.');
            }                        
        }catch(PDOException $e){
            echo json_encode('PDO Error: '.$e->getMessage());
        }       
    }//End function, "addQuestionInDb".
    
    
    /**   Attempts to move a question/answer pair up or down in the list.
     * Upon success, the reordered list is returned; Otherwise, an
     * error message is returned.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param integer $selectedQuestion_classes_ID -- The primary key value
     *      of the current question class.
     * @param integer $ordinal -- The question's position in the JSONb field.
     * @param string $direction -- Either "up" or "down".
     * @return JSONb / Error message -- Either the new "questions_answers"
     *      field contents or a message indicating a problem with the move.
     */
    public static function moveQuestionUpDown($db, $selectedQuestion_classes_ID, $ordinal, $direction){
        if (empty($selectedQuestion_classes_ID) || $ordinal === false || empty($direction)) {
            return json_encode('Error: Invalid input');
        }
        
        try {
            //Step 1: Retrieve current questions_answers JSONB array:
            $query = $db->prepare("
                SELECT \"questions_answers\" FROM \"Questions\" 
                WHERE \"Question_classes_ID\" = :selectedQuestion_classes_ID AND
                      \"Proposal_details_ID\" = 0
            ");

            $query->execute([
                ':selectedQuestion_classes_ID' => $selectedQuestion_classes_ID
            ]);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $questions = json_decode($result['questions_answers'], true);

            //Step 2: Modify the array based on ordinal and direction:
            if ($direction === 'up' && $ordinal > 0) {
                //Swap with the previous item:
                $temp = $questions[$ordinal - 1];
                $questions[$ordinal - 1] = $questions[$ordinal];
                $questions[$ordinal] = $temp;

            } elseif ($direction === 'down' && $ordinal < count($questions) - 1) {
                //Swap with the next item:
                $temp = $questions[$ordinal + 1];
                $questions[$ordinal + 1] = $questions[$ordinal];
                $questions[$ordinal] = $temp;
            }

            //Step 3: Update the modified array back into the database:
            $updatedQuestions = json_encode($questions);
            $updateQuery = $db->prepare("
                UPDATE \"Questions\"
                SET \"questions_answers\" = :questions 
                WHERE \"Question_classes_ID\" = :selectedQuestion_classes_ID AND
                      \"Proposal_details_ID\" = 0
            ");
            $updateQuery->execute([
                ':questions' => $updatedQuestions, 
                ':selectedQuestion_classes_ID' => $selectedQuestion_classes_ID
            ]);
            
            //Step 4: Return the modified JSON array:
            return $updatedQuestions;
            
        } catch (PDOException $e) {
            return json_encode('PDO Error: '.$e->getMessage());
        }
    }//End function, "moveQuestionUpDown".    
    
    
    public static function attachQuestionClass($db, $mainQuestion_classes_ID, $attachQuestion_classes_ID, $attachDirection){
        if (empty($mainQuestion_classes_ID) || empty($attachQuestion_classes_ID) || empty($attachDirection)){
            return json_encode('Error: Invalid input');
        }

        try{
            //1.) Get the current list of questions for the passed in "mainQuestion_classes_ID"
            //    and "varProposal_details_ID":
            //----------------------------------------------------------------------------------
            $stmt_1 = $db->prepare("
                SELECT \"questions_answers\" FROM \"Questions\" 
                WHERE \"Question_classes_ID\" = :mainQuestion_classes_ID AND 
                \"Proposal_details_ID\" = 0
            ");
            $stmt_1->execute([
                ':mainQuestion_classes_ID' => $mainQuestion_classes_ID
            ]);
            $results_1 = $stmt_1->fetch(PDO::FETCH_ASSOC);
            $mainQuestions = json_decode($results_1['questions_answers'], true);

            //2.) Get the list of questions to attach to the main list for the passed in "attachQuestion_classes_ID:
            //------------------------------------------------------------------------------------------------------
            $stmt_2 = $db->prepare("
                SELECT \"questions_answers\" FROM \"Questions\" 
                WHERE \"Question_classes_ID\" = :attachQuestion_classes_ID AND \"Proposal_details_ID\" = 0
            ");
            $stmt_2->execute([':attachQuestion_classes_ID' => $attachQuestion_classes_ID]);
            $results_2 = $stmt_2->fetch(PDO::FETCH_ASSOC);
            $attachQuestions = json_decode($results_2['questions_answers'], true);

            //3.) Depending upon the passed in "attachDirection", either prepend or append the new array to the old:
            //------------------------------------------------------------------------------------------------------
            if($attachDirection === 'prepend'){
                $mainQuestions = array_merge($attachQuestions, $mainQuestions);

            }elseif($attachDirection === 'append'){
                $mainQuestions = array_merge($mainQuestions, $attachQuestions);
            }

            //4.) Update the modified array back into the database:
            //----------------------------------------------------- 
            $updatedMainQuestions = json_encode($mainQuestions);

            $stmt_3 = $db->prepare("
                UPDATE \"Questions\"
                SET \"questions_answers\" = :updatedMainQuestions 
                WHERE \"Question_classes_ID\" = :mainQuestion_classes_ID AND
                      \"Proposal_details_ID\" = 0
            ");

            $stmt_3->execute([
                ':updatedMainQuestions' => $updatedMainQuestions, 
                ':mainQuestion_classes_ID' => $mainQuestion_classes_ID
            ]);

            //5.) Return the modified JSON array:
            //-----------------------------------
            return $updatedMainQuestions;

        } catch (PDOException $e) {
            return json_encode('Error: '.$e->getMessage());
        }
    }//End function, "attachQuestionClass".
    
    
    
}//End class, "Questions_db".