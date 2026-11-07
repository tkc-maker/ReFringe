<?php

class General_Event_db {
    
    /**   Attempts to fetch an array of Proposer Info for the passed-in "Proposal_Details_ID". 
     * A JSON-encoded encoded message is returned of the form,
     * 
     *  Success: '["status" => "true", "aryProposerInfo" => <array of Proposer Info>]', or
     *  Failure: '["status" => "false", "errMsg => an error message]'
     * 
     * $param integer $Proposal_details_ID -- A primary key value in the
     *      "Proposal_details" table.
     * @param PDO $db -- A database connection.
     * @return JSON error message or JSON array -- Described above.
     */
    public static function fetchProposerInfo($Proposal_details_ID, $db){
        if(empty($Proposal_details_ID)){
            return json_encode(["status" => "false", "errMsg" => "Invalid \"Proposal_details_ID\" value input."]);
        }
        
        try{
            $stmt = $db->prepare("      
                SELECT \"Roles\".\"name\" AS \"role\", \"first_name\", \"last_name\", \"phone\", \"email\"
                FROM \"Proposal_details\" INNER JOIN \"Users\" ON \"Proposal_details\".\"Users_ID\" = \"Users\".\"ID\"
                INNER JOIN \"Roles\" ON \"Users\".\"Roles_ID\" = \"Roles\".\"ID\"
                WHERE \"Proposal_details\".\"ID\" = :proposal_details_id
            ");
            
            $stmt->execute([
                ':proposal_details_id' => $Proposal_details_ID
            ]);                
            $aryProposerInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(empty($aryProposerInfo)){
                return json_encode(["status" => "false", 'errMsg' => "A \"Proposer Info\" record could be fetched from the database."]);
            }
            
            return json_encode(["status" => "true", "aryProposerInfo" => $aryProposerInfo]);
            
        }catch (PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDOError: ".$e->getMessage()]);
        }
    }//End function, "fetchProposerInfo".

    /**   Attempts to fetch a "title" value from the "Proposal_details" table 
     * for the passed-in ID. A JSON-encoded encoded message is returned of the form,
     * 
     *  Success: '["status" => "true", "title" => "..."]', or
     *  Failure: '["status" => "false", "errMsg => an error message]'
     * 
     * $param integer $Proposal_details_ID -- A primary key value in the
     *      "Proposal_details" table.
     * @param PDO $db -- A database connection.
     * @return JSON error message or JSON string value -- Described above.
     */
    public static function fetchEventTitle($Proposal_details_ID, $db){
        if(empty($Proposal_details_ID)){
            return json_encode(["status" => "false", "errMsg" => "Invalid \"Proposal_details_ID\" value input."]);
        }
        
        try{
            $stmt = $db->prepare("      
                SELECT \"title\" FROM \"Proposal_details\" 
                WHERE \"ID\" = :Proposal_details_ID
            ");
            
            $stmt->execute([
                ':Proposal_details_ID' => $Proposal_details_ID
            ]);                
            $aryTitle = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(empty($aryTitle)){
                return json_encode(["status" => "false", 'errMsg' => "A \"title\" record could be fetched from \"Proposal_details\"."]);
            }
            
            return json_encode(["status" => "true", "title" => $aryTitle["title"]]);
            
        }catch (PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDOError: ".$e->getMessage()]);
        }
    }//End function, "fetchEventTitle".
    
    /**   Attempts to retrieve the "sched_Events" record corresponding to the "Proposal_details_ID 
     * passed in. If such a record doesn't exist, then an "empty" one is created, containing only 
     * non-empty values for the created "ID" and "preferredTimes" fields.  So, upon success, a JSON 
     * array will be returned, containing the following fields: "ID", "duration", "maxOccurences", 
     * and "preferredTimes", the last being a JSON array of days (rows) and time-intervals (columns).
     * Should any of these operations fail, then false is returned with an error message. 
     * A JSON-encoded encoded message is returned of the form,
     * 
     *  Success: '["status" => "true", event" => "[...]"]', or
     *  Failure: '["status" => "false", "errMsg => an error message]'

     * 
     * @param Integer $Proposal_details_ID
     * @param PDO $db -- A database connection.
     * @return JSON Associative Array -- A "status" indicating whether the fetch succeeded,
     *    and either an "event" property or an "errMsg" property (if "status" is false).
     */
    public static function getEventFromDb($Proposal_details_ID, $db){  
        if(empty($Proposal_details_ID)){
            return json_encode(["status" => "false", "errMsg" => "Invalid \"Proposal_details_ID\" value input."]);
        }
        
        try{
            //Prepare and execute an SQL-SELECT statement to retrieve the
            //"Proposal_Details" record for the variable passed in:
            //-----------------------------------------------------------
            $stmt_1 = $db->prepare("
                SELECT \"ID\", \"duration\", \"maxOccurences\", \"preferredTimes\" 
                FROM \"sched_Events\"
                WHERE \"Proposal_details_ID\" = :Proposal_details_ID;
            ");
            //Bind parameters and execute the statement:
            $stmt_1->execute([
                ':Proposal_details_ID' => $Proposal_details_ID
            ]);
            $aryEvent = $stmt_1->fetch(PDO::FETCH_ASSOC);

            if(!empty($aryEvent)){
                return json_encode(["status" => 'true', "event" => $aryEvent]);

            }else{
                //Prepare and execute an SQL_INSERT statement to create a new  
                //"Events" record for the passed-in "Proposal_details_ID:
                //-----------------------------------------------------------
                $stmt_2 = $db->prepare("
                      INSERT INTO \"sched_Events\" (\"Proposal_details_ID\", \"preferredTimes\")
                      SELECT :Proposal_details_ID, \"preferredTimes\"::json
                      FROM \"sched_Events\"
                      WHERE \"ID\" = 0
                      RETURNING \"ID\";
                 ");    
                //Bind parameter and execute the statement:
                $stmt_2->execute([
                    ':Proposal_details_ID' => $Proposal_details_ID
                ]);

                $lastInsertedID = $stmt_2 ->fetchColumn();

                if($lastInsertedID !== false){                        
                    $stmt_3 = $db->prepare("
                        SELECT \"preferredTimes\" 
                        FROM \"sched_Events\"
                        WHERE \"ID\" = :lastInsertedID
                    ");

                    //Bind parameters and execute the statement:
                    $stmt_3->execute([
                        ':lastInsertedID' => $lastInsertedID
                    ]);
                    $aryPreferredTimes = $stmt_3->fetch(PDO::FETCH_ASSOC);

                    if(!empty($aryPreferredTimes)){                        
                        $newEvent = [
                            'ID' => $lastInsertedID,
                            'duration' => 0,
                            'maxOccurences' => 0,
                            'preferredTimes' => $aryPreferredTimes['preferredTimes']
                        ];
                        return json_encode(["status" => true, "event" => $newEvent]);
                        
                    }else{
                        return json_encode(["status" => false, "errMsg" => "Error: \"preferredTimes\" template could not be fetched."]);   
                    }
                }else{
                    return json_encode(["status" => false, "errMsg" => "Error: New \"sched_Events\" record could not be created."]);
                }
            }
        }catch (PDOException $e){
            return json_encode(["status" => false, "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
    }//End function, "getEventFromDb".
    
    /**   Attempts to update a single record in the "sched_Events" table with the passed-in JSON string (of the 
     * "Proposal_details_ID", "duration", "maxOccurences", and "preferredTimes" fields). The "sched_Events" record 
     * is uniquely identified by the passed-in "Proposal_details_ID" (in the JSON). Upon success, a "status" 
     * of true is returned; otherwise, a "status" of false is returned with an "errMsg". 
     * 
     * @param JSON Associative Array $jsonEvent -- Described above.
     * @param PDO $db -- A database connection.
     * @return JSON Associative Array -- A "status" property indicating whether the update 
     *    succeeded with an "errMsg" property (if "status" is false).
     */
    public static function updateEventInDb($jsonEvent, $db){
        try{
            $aryEvent = json_decode($jsonEvent, true);
            
            if(empty($aryEvent['Proposal_details_ID'])){
                return(json_encode(["status" => "false", "errMsg" => "Invalid \"Proposal_details_ID\" input in the JSON."]));
            }            
        }catch (Exception $ex){
            return(json_encode(["status" => "false", "errMsg" => "Invalid JSON, \"jsonEvent\" input."]));
        }

        try{
            //Prepare the SQL statement with placeholders:
            $stmt = $db->prepare("
                UPDATE \"sched_Events\" 
                SET \"duration\" = :duration,
                    \"maxOccurences\" = :maxOccurences,
                    \"preferredTimes\" = :preferredTimes::json
                WHERE \"Proposal_details_ID\" = :Proposal_details_ID;
            ");

            // Bind parameters and execute the statement
            $stmt->execute([':duration' => $aryEvent['duration'],
                            ':maxOccurences' => $aryEvent['maxOccurences'],
                            ':preferredTimes' => json_encode($aryEvent['preferredTimes']),
                            ':Proposal_details_ID' => $aryEvent['Proposal_details_ID']]);

            $rowsAffected = $stmt->rowCount();

            if($rowsAffected > 0){
                $aryResults = ["status" => "true"];

            } else {
                $aryResults = ["status" => "false",  "errMsg" => "The \"Events\" record update failed for ".
                    "\"Proposal_details_ID\" = ".$aryEvent['Proposal_details_ID']];
            }            
            return json_encode($aryResults);

        }catch(PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
    }//End function, "updateEventInDb".
    
}//End class, "Event_db".
