<?php

class Venue_Questions_db{
    
    /**   Attempts to fetch a JSON-encoded array of questions/answers in the format,
     * '[{"question": "...", "answer": ""}, {...}, ...]', for venues. If successful, 
     * the JSON array is returned; otherwise, an error message is returned.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @return JSON-Array/string -- (Described above.)
     */
    public static function loadVenueQuestionsFromDb($db){
        try{
            $stmt = $db->prepare("SELECT \"questions_answers\" FROM \"Venues\" WHERE \"ID\" = 0");
            $stmt->execute();
            $venueQuestions = $stmt->fetch(PDO::FETCH_ASSOC);

            if(empty($venueQuestions['questions_answers'])){
                return json_encode(["status" => "false", "errMsg" => "No results returned."]);
            }
            $questionsAnswers = json_decode($venueQuestions['questions_answers'], true);
            return json_encode(["status" => "true", "questionsAnswers" => $questionsAnswers]);
                    
               
        } catch (PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
    }//End function, "loadSelectedVenueQuestionsFromDb".
    
    /**   Attempts to create an empty Template record ("ID" === 0) in the "Venues" table.
     *  A JSON-encoded encoded message is returned of the form,
     * 
     *  Success: '["status" => "true", "questionsAnswers" => "[]"]',
     *  Failure: '["status" => "false", "errMsg => an error message]'
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @return JSON -- Described above.
     */
    public static function insertTemplateVenuesRecordIntoDb($db){
        try{
            $stmt = $db->prepare("INSERT INTO \"Venues\" (\"ID\",\"name\",\"venue_check\") VALUES (0,'TEMPLATE',false)");
            $stmt->execute();
            
            $rowsAffected = $stmt->rowCount();

            if($rowsAffected > 0){
                return json_encode(["status" => "true", "questionsAnswers" => []]);
                
            } else {
                return json_encode(["status" => "false", "errMsg" => "Venue TEMPLATE could not be created."]);
            }
        }catch(PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
    }//End function, "insertTemplateVenuesRecordIntoDb".
    
    
    /**   Attempts to update a specific TEMPLATE question in a JSONb field in the DB.
     * Upon success, a positive message is returned; otherwise a negative message 
     * is returned.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param integer $ordinal -- The question's position in the JSONb field.
     * @param string $updatedQuestion -- The edited question.
     * @return JSON -- Whether the edited question was updated in the database.
     */
    public static function updateVenueQuestionInDb($db, $ordinal, $updatedQuestion){
        if (empty($ordinal) || empty($updatedQuestion)) {
            return json_encode(["status" => "false", "errMsg" => "Invalid input"]);
        }

        try{
            //Prepare the SQL statement with placeholders:
            $stmt = $db->prepare("
                UPDATE \"Venues\" 
                SET \"questions_answers\" = jsonb_set(
                    \"questions_answers\",
                    '{".($ordinal - 1).", question}',
                    :updatedQuestion::jsonb
                ) 
                WHERE \"ID\" = 0
            ");

            //Bind parameters and execute the statement:
            $stmt->execute([
                ':updatedQuestion' => json_encode($updatedQuestion)
            ]);

            $rowsAffected = $stmt->rowCount();

            if($rowsAffected > 0){
                return json_encode(["status" => "true", "errMsg" => ""]);
                
            } else {
                return json_encode(["status" => "false", "errMsg" => "Question update failed."]);
            }
        }catch(PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
    }//End function, "updateVenueQuestionInDb".
    
    /**   Attempts to delete a specific TEMPLATE question from a JSONb field in the DB.
     * Upon success, a positive message is returned; otherwise a negative message is 
     * returned.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param integer $ordinal -- The question's position in the JSONb field.
     * @return JSON -- Whether the selected question was deleted in the database.
     */
    public static function deleteVenueQuestionInDb($db, $ordinal){
        if (empty($ordinal)){
            return json_encode(["status" => "false", "errMsg" => "Invalid input"]);
        }

        try{
            //Prepare the SQL statement with placeholders:
            $stmt = $db->prepare("
                UPDATE \"Venues\" 
                SET \"questions_answers\" = (
                    SELECT jsonb_agg(elem)
                    FROM jsonb_array_elements(\"questions_answers\") WITH ORDINALITY arr(elem, idx)
                    WHERE idx != :ordinal
                )
                WHERE \"ID\" = 0
            ");

            // Bind parameters and execute the statement
            $stmt->execute([
                ':ordinal' => $ordinal
            ]);
            $rowsAffected = $stmt->rowCount();

            if($rowsAffected > 0){
                return json_encode(["status" => "true", "errMsg" => ""]);
            } else {
                return json_encode(["status" => "false", "errMsg" => "Question deletion failed"]);
            }
        }catch(PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
    }//End function, "deleteVenueQuestionInDb".
    
    /**   Attempts to add a new TEMPLATE question to a JSONb field in the DB.
     * Upon success a positive message is returned; Otherwise, a negative message 
     * is returned.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param $new_question -- A new question to be added under the current
     *      question class.
     * @return JSON -- Whether the new question was added in the database.
     */
    public static function addVenueQuestionInDb($db, $new_question){
        try{
            $stmt = $db->prepare("
                UPDATE \"Venues\" SET \"questions_answers\" = \"questions_answers\" || :new_question::jsonb
                WHERE \"ID\" = 0
            ");

            $stmt->execute([
                ':new_question' => $new_question
            ]);

            $rowsAffected = $stmt->rowCount();

            if($rowsAffected > 0){
                return json_encode(["status" => "true", "errMsg" => ""]);
                
            } else {
                return json_encode(["status" => "false", "errMsg" => "Adding new question failed."]);
            }                        
        }catch(PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDO Error: ".$e->getMessage()]);
        }       
    }//End function, "addVenueQuestionInDb".
    
    
    /**   Attempts to move a TEMPLATE question/answer pair up or down in the list.
     * Upon success, the reordered list is returned; Otherwise, an error message 
     * is returned.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param integer $ordinal -- The question's position in the JSONb field.
     * @param string $direction -- Either "up" or "down".
     * @return JSONb / Error message -- Either the new "questions_answers"
     *      field contents or a message indicating a problem with the move.
     */
    public static function moveVenueQuestionUpDown($db, $ordinal, $direction){
        if ($ordinal === false || empty($direction)) {
            return json_encode(["status" => "false", "errMsg" => "Invalid input"]);
        }
        
        try {
            //Step 1: Retrieve current questions_answers JSONB array:
            $query = $db->prepare("
                SELECT \"questions_answers\" FROM \"Venues\" 
                WHERE \"ID\" = 0
            ");

            $query->execute();
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

            //Step 3: Save the modified array back into the database:
            $updatedQuestions = json_encode($questions);
            $updateQuery = $db->prepare("
                UPDATE \"Venues\"
                SET \"questions_answers\" = :questions 
                WHERE \"ID\" = 0
            ");
            $updateQuery->execute([
                ':questions' => $updatedQuestions
            ]);
            
            //Step 4: Return the modified JSON array:
            return json_encode(["status" => "true", "questionsAnswers" => $updatedQuestions]);
            
        } catch (PDOException $e) {
            return json_encode(["status" => "false", "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
    }//End function, "moveVenueQuestionUpDown".    
       
}//End class, "Questions_db".