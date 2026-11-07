<?php
class Venue_Details_db{

    /**   Attempts to fetch the "name", "venue_check" and the "questions_answers" fields from the "Venues" table
     * for the passed in ID. Upon success, true is returned along with the above fields encoded in JSON; 
     * Otherwise, false is returned along with an "errMsg".
     * 
     * Note: If a "Venues_ID" value of 0 is passed in, then a new "Venues" record will be created and subsequently
     *       fetched.
     * 
     * @param Integer $Venues_ID
     * @param PDO $db -- A database connection.
     * @return JSON Associative Array -- A "status" indicating whether the fetch succeeded,
     *    and either the "name", "venueCheck", and "questionsAnswers" properties or an "errMsg"
     *    property (if "status" is "false").
     * 
     *    Here is the format of the returned JSON:
     * 
     *      [{"status": "true" or "false",
     *        "errMsg": string (if "status" is "false"),
     *        "name: "string",
     *        "venueCheck: "true" or "false",
     *        "questionsAnswers": ‘[{“question”: “<question>”, “answer”: “<answer>”}, . . .]’
     *      }]
     */
    public static function fetchVenueDetailsFromDb($Venues_ID, $db){
        if($Venues_ID === null){
            return json_encode(["status" => "false", "errMsg" => "Invalid passed-in \"Venues_ID\"."]);           
        }
        
        try{
            if($Venues_ID == 0){
                //Prepare and execute an SQL_INSERT statement to create a new  
                //"Venues" record based upon the selected Question_class template:
                //-------------------------------------------------------------------
                $stmt_1 = $db->prepare("INSERT INTO \"Venues\" (\"name\",\"venue_check\",\"questions_answers\")".
                        " SELECT '', \"venue_check\", \"questions_answers\"::jsonb".
                        " FROM \"Venues\"". 
                        " WHERE \"ID\" = :templateID".
                        " RETURNING \"ID\"");    
                
                //Bind parameters and execute the statement:
                $stmt_1->execute([':templateID' => 0]);

                $Venues_ID = $stmt_1->fetchColumn();
                if($Venues_ID === false){
                    return json_encode(["status" => "false", "errMsg" => "A new \"Venues\" record could not be created."]);                    
                }                
            }
            //Prepare and execute an SQL-SELECT statement to retrieve the "Venues" 
            //record for the new "Venuse_ID" value:
            //--------------------------------------------------------------------------
            $stmt_2 = $db->prepare("SELECT \"ID\",\"name\",\"venue_check\",\"questions_answers\" FROM \"Venues\"".
                " WHERE \"ID\" = :Venues_ID");
            //Bind parameters and execute the statement:
            $stmt_2->execute([':Venues_ID' => $Venues_ID]);
            $venueDetails = $stmt_2->fetch(PDO::FETCH_ASSOC);

            if (!empty($venueDetails)) {
                return json_encode(["status" => "true",
                                    "ID" => $venueDetails['ID'],
                                    "name" => $venueDetails['name'],
                                    "venueCheck" => $venueDetails['venue_check'],
                                    "questionsAnswers" => $venueDetails['questions_answers']]);
            } else {
                return json_encode(["status" => "false", 'errMsg' => "The \"Venues\" record could not be fetched."]);
            }

        } catch (PDOException $e) {
            return json_encode(["status" => "false", "errMsg" => "PDOError: ".$e->getMessage()]);
        }
    }//End function, "fetchVenueDetailsFromDb".
    
    /**   Attempts to update a single record in the "Venues" table with the passed-in JSON string (of the 
     * "name", "venueCheck", and "questionsAnswers" fields). The "Venues" record is uniquely identified 
     * by the passed-in "Venues_ID" (in the JSON). Upon success, a "status" of true is returned; otherwise, 
     * a "status" of false is returned with an "errMsg". 
     * 
     * @param JSON Associative Array $jsonVenueDetails -- Described above.
     * @param PDO $db -- A database connection.
     * @return JSON Associative Array -- A "status" property indicating whether the update 
     *    succeeded with an "errMsg" property (if "status" is false).
     */
    public static function updateVenueDetailsInDb($jsonVenueDetails, $db){
        try{
            $aryVenueDetails = json_decode($jsonVenueDetails, true);
            
            if(empty($aryVenueDetails['Venues_ID'])){
                return(json_encode(["status" => "false", "errMsg" => "Invalid \"Venues_ID\" input."]));
            }            
        }catch (Exception $ex){
            return(json_encode(["status" => "false", "errMsg" => "Invalid JSON input."]));
        }

        try{
            //Prepare the SQL statement with placeholders:
            $stmt = $db->prepare("
                UPDATE \"Venues\" 
                SET \"name\" = :name,
                    \"venue_check\" = :venueCheck,
                    \"questions_answers\" = :jsonQuestionsAnswers::jsonb
                WHERE \"ID\" = :Venues_ID;
            ");

            // Bind parameters and execute the statement
            $stmt->execute([':Venues_ID' => $aryVenueDetails['Venues_ID'],
                            ':name' => $aryVenueDetails['name'],
                            ':venueCheck' => $aryVenueDetails['venueCheck']? 1: 0,
                            ':jsonQuestionsAnswers' => $aryVenueDetails['questionsAnswers']]);

            $rowsAffected = $stmt->rowCount();

            if($rowsAffected > 0){
                $aryResults = ["status" => "true"];

            } else {
                $aryResults = ["status" => "false",  "errMsg" => "The \"Venues\" record update failed for ".
                    "\"Venues_ID\" = ".$Venues_ID];
            }            
            return json_encode($aryResults);

        }catch(PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
    }//End function, "updateVenueDetailsInDb".
    
}//End class, "Venue_Details_db".
