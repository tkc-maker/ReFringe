<?php

class Calendar_db {
    
    /**   Attempts to fetch all records from the "sched_Calendar" table.   
     * A JSON-encoded message is returned of the form,
     * 
     *  Success: '["status" => "true", "allCalendarEvents" => "[...]"]', or
     *  Failure: '["status" => "false", "errMsg => an error message]'
     * 
     * @param PDO $db -- A database connection.
     * @return JSON error message or JSON Indexed Associative Array -- Described above.
     */
    public static function fetchAllCalendarEventsFromDb($db){
        try{
            //Prepare and execute an SQL-SELECT statement to retrieve all  
            //"sched_Calendar" records with "title"s from "Proposal_details":
            //---------------------------------------------------------------
            $stmt = $db->prepare("
                SELECT \"sched_Calendar\".\"ID\" AS \"Calendar_ID\", \"Proposal_details_ID\", \"Venues_ID\", 
                \"title\", \"day\", \"start\", \"end\", \"tag\" 
                FROM \"sched_Calendar\" INNER JOIN \"Proposal_details\"
                ON \"sched_Calendar\".\"Proposal_details_ID\" = \"Proposal_details\".\"ID\"
                ORDER BY \"day\", \"start\", \"end\"");
            
            //Bind parameters and execute the statement:
            $stmt->execute();
            $allCalendarEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(empty($allCalendarEvents)){
                return json_encode(["status" => "false", 'errMsg' => "No \"sched_Calendar\" records could be fetched."]);
            }
            
            return json_encode(["status" => "true", "allCalendarEvents" => $allCalendarEvents]);
            
        } catch (PDOException $e) {
            return json_encode(["status" => "false", "errMsg" => "PDOError: ".$e->getMessage()]);
        }        
    }//End function, "fetchAllCalendarEventsFromDb".
    
    /**   Attempts to fetch all records from the "sched_Events" and related tables.  
     * The joins only go down as far as the intersection tables, because only IDs are
     * needed--not the names of Members and Resources--to assess conflicts. 
     * A JSON-encoded message is returned of the form,
     * 
     *  Success: '["status" => "true", "allGeneralEvents" => "[...]"]', or
     *  Failure: '["status" => "false", "errMsg => an error message]'
     * 
     * @param PDO $db -- A database connection.
     * @return JSON error message or JSON Indexed Associative Array -- Described above.
     */
    public static function fetchAllGeneralEventsFromDb($db){
        try{
            //Prepare and execute an SQL-SELECT statement to retrieve all  
            //"sched_Calendar" records with "title"s from "Proposal_details":
            //---------------------------------------------------------------
            $stmt = $db->prepare("
                SELECT e.\"Proposal_details_ID\", e.\"duration\", e.\"maxOccurences\", e.\"preferredTimes\"::text, 
                json_agg(DISTINCT em.\"sched_Members_ID\") AS members, json_agg(DISTINCT er.\"sched_Resources_ID\") AS resources 
                FROM \"sched_Events\" e LEFT JOIN \"sched_Events_Members\" em ON e.\"ID\" = em.\"sched_Events_ID\" 
                LEFT JOIN \"sched_Events_Resources\" er ON e.\"ID\" = er.\"sched_Events_ID\" 
                GROUP BY e.\"Proposal_details_ID\", e.\"duration\", e.\"maxOccurences\", e.\"preferredTimes\"::text;
            ");
            
            //Bind parameters and execute the statement:
            $stmt->execute();
            $allGeneralEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(empty($allGeneralEvents)){
                return json_encode(['status' => "false", 'errMsg' => "No \"sched_Events\" and related records could be fetched."]);
            }
            return json_encode(["status" => "true", "allGeneralEvents" => $allGeneralEvents]);
            
        } catch (PDOException $e) {
            return json_encode(["status" => "false", "errMsg" => "PDOError: ".$e->getMessage()]);
        }        
    }//End function, "fetchAllGeneralEventsFromDb".
    
    /**   Attempts to insert a new "sched_Calendar" record into the database
     * with the parameters listed below. A JSON-encoded encoded message is 
     * returned of the form,
     * 
     *  Success: '["status" => "true", "calendar_id" => new primary key value]', or
     *  Failure: '["status" => "false", "errMsg => an error message]'
     * 
     * @param int $Proposal_details_ID -- The primary key of a "Proposal_details"
     *      record.
     * @param int $Venues_ID -- A primary key value in the "Venues" table.
     * @param int day -- An integer representing a day of the festival (1 - 4).
     * @param 24hrTime start -- A start time for the event.
     * @param 24hrTime end -- An end time for the event.
     * @param string (<=100) tag -- A grouping tag for the event.
     * @param PDO $db -- A database connection.
     * @return JSON Associative Array -- A "status" indicating whether the insert
     *     succeeded, and possibly an "errMsg" property (if "status" is false).
     *     If successful, then "status" and "calendar_ID" are returned.
     */
    public static function insertCalendarEvent($Proposal_details_ID, $Venues_ID, $day, $start, $end, $tag, $db){
        if(empty($Proposal_details_ID) || empty($Venues_ID) || empty($day) || empty($start) || empty($end)){
            return json_encode(["status" => "false", "errMsg" => "Invalid fields input."]);
        }
        
        try{
            $stmt = $db->prepare("
                INSERT INTO \"sched_Calendar\" (\"Proposal_details_ID\", \"Venues_ID\", \"day\", \"start\", \"end\", \"tag\")
                VALUES (:Proposal_details_ID, :Venues_ID, :day, :start, :end, :tag)
                RETURNING \"ID\"
            ");
            
            $stmt->execute([
                ':Proposal_details_ID' => $Proposal_details_ID,
                ':Venues_ID' => $Venues_ID,
                ':day' => $day,
                ':start' => $start,
                ':end' => $end,
                ':tag' => $tag
            ]);    
            
            $rowsAffected = $stmt->rowCount();

            if($rowsAffected > 0){
                $Calendar_ID = $stmt -> fetchColumn();
                return json_encode(["status" => "true", "calendar_id" => $Calendar_ID]);

            }else{
                return json_encode(["status" => "false", "errMsg" => "New \"sched_Calendar\" record could not be inserted into db."]);
            }
        
        }catch (PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDOError: ".$e->getMessage()]);
        }        
    }
    
    /** Attempts to update an existing "sched_Calendar" record with the passed-in fields.
     *  A JSON-encoded encoded message is returned of the form,
     * 
     *  Success: '["status" => "true"]', or
     *  Failure: '["status" => "false", "errMsg => an error message]'
     *
     * @param int $Calendar_ID
     * @param int day -- An integer representing a day of the festival (1 - 4).
     * @param 24hrTime start -- A start time for the event.
     * @param 24hrTime end -- An end time for the event.
     * @param string (<=100) tag -- A grouping tag for the event.
     * @param PDO $db -- A database connection.
     * @return JSON Associative Array -- A "status" indicating whether the update
     *     succeeded, and possibly an "errMsg" property (if "status" is false).
     */
    public static function updateCalendarEventWithMainFields($Calendar_ID, $day, $start, $end, $tag, $db){
        if(empty($Calendar_ID) || empty($day) || empty($start) || empty($end)){
            return json_encode(["status" => "false", "errMsg" => "Invalid fields input."]);
        }
        
        try{
            $stmt = $db->prepare("
                UPDATE \"sched_Calendar\" 
                SET \"day\" = :day, \"start\" = :start, \"end\" = :end, \"tag\" = :tag
                WHERE \"ID\" = :Calendar_ID");
            
            $stmt->execute([
                ':Calendar_ID' => $Calendar_ID,
                ':day' => $day,
                ':start' => $start,
                ':end' => $end,
                ':tag' => $tag
            ]);    
            
            $rowsAffected = $stmt->rowCount();

            if($rowsAffected > 0){
                return json_encode(["status" => "true"]);

            }else{
                return json_encode(["status" => "false", "errMsg" => "The \"sched_Calendar\" record could not be iupdated in the db."]);
            }
        
        }catch (PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDOError: ".$e->getMessage()]);
        }                
    }//End function, "updateCalendarEventWithMainFields".
    
    /**   Attempts to update a record in the "sched_Calendar" table with the passed-in
     * "Venues_ID" for the passed-in "Proposal_details_ID" and "Calendar_ID" values. 
     * A JSON-encoded encoded message is returned of the form,
     * 
     *  Success: '["status" => "true"]', or
     *  Failure: '["status" => "false", "errMsg => an error message]'
     * 
     * @param int $Calendar_ID -- A primary key value in the "sched_Calendar" table.
     * @param int $Venues_ID -- A primary key value in the "Venues" table.
     * @param PDO $db -- A database connection.
     * @return JSON Associative Array -- A "status" indicating whether the update
     *     succeeded, and possibly an "errMsg" property (if "status" is false).
     */   
    public static function updateCalendarEventWithVenuesId($Calendar_ID, $Venues_ID, $db){
        if(empty($Calendar_ID) || empty($Venues_ID)){
            return json_encode(["status" => "false", "errMsg" => "Invalid \"Calendar_ID\" or \"Venues_ID\" input."]);
        }
        
        try{
            $stmt = $db->prepare("
                UPDATE \"sched_Calendar\" 
                SET \"Venues_ID\" = :Venues_ID
                WHERE \"ID\" = :Calendar_ID");
            
            $stmt->execute([
                ':Calendar_ID' => $Calendar_ID,
                ':Venues_ID' => $Venues_ID
            ]);    
            
            $rowsAffected = $stmt->rowCount();

            if($rowsAffected > 0){
                return json_encode(["status" => "true"]);

            }else{
                return json_encode(["status" => "false", "errMsg" => "Selected \"sched_Calendar\" record could not be updated in db."]);
            }
        
        }catch (PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDOError: ".$e->getMessage()]);
        }        
    }//End function, "updateCalendarEventWithVenuesId".
    
    /**   Attempts to delete a record from the "sched_Calendar" table.
     * A JSON-encoded encoded message is returned of the form,
     * 
     *  Success: '["status" => "true"]', or
     *  Failure: '["status" => "false", "errMsg => an error message]'
     * 
     * @param int $Calendar_ID -- A primary key value in the "sched_Calendar" table.
     * @param PDO $db -- A database connection.
     * @return JSON Associative Array -- A "status" indicating whether the deletion
     *     succeeded, and possibly an "errMsg" property (if "status" is false).
     */
    public static function deleteCalendarEvent($Calendar_ID, $db){
        if(empty($Calendar_ID)){
            return json_encode(["status" => "false", "errMsg" => "Invalid \"Calendar_ID\" input."]);
        }
        
        try{
            $stmt = $db->prepare("
                DELETE FROM \"sched_Calendar\"
                WHERE \"ID\" = :Calendar_ID");
            
            $stmt->execute([
                ':Calendar_ID' => $Calendar_ID
            ]);    
            
            $rowsAffected = $stmt->rowCount();

            if($rowsAffected > 0){
                return json_encode(["status" => "true"]);

            }else{
                return json_encode(["status" => "false", "errMsg" => "Selected \"sched_Calendar\" record could not be deleted from the db."]);
            }
        
        }catch (PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDOError: ".$e->getMessage()]);
        }        
        
    }//End function, "deleteCalendarEvent".
    
}//End class, "Calendar_db".
