<?php

class Select_Venue_db {
    
    /**   Attempts to fetch all records from the "Venues" table where the field,
     * "venue_check" is true. If successful, the format of the returned JSON array
     * would be '[[false, "ID", "name", "questions_answers"], ...]'  A JSON-encoded 
     * message is returned of the form,
     * 
     *  Success: '["status" => "true", "allCheckedVenues" => "[...]"]', or
     *  Failure: '["status" => "false", "errMsg => an error message]'
     * 
     * $param integer $selVenues_ID -- The "Venues_ID" value to preselect.
     * @param PDO $db -- A database connection.
     * @return JSON error message or JSON Indexed Associative Array -- Described above.
     */
    public static function fetchAllCheckedVenuesFromDb($selVenues_ID, $db){
        try{
            //Prepare and execute an SQL-SELECT statement to retrieve all "Venues" 
            //records where "venue_checked" === 1:
            //--------------------------------------------------------------------
            $stmt = $db->prepare("SELECT false AS \"selected\", \"ID\",\"name\",\"questions_answers\" FROM \"Venues\"".
                " WHERE \"venue_check\" = true ORDER BY \"name\"");
            
            //Bind parameters and execute the statement:
            $stmt->execute();
            $allCheckedVenues = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(empty($allCheckedVenues)){
                return json_encode(["status" => "false", 'errMsg' => "No \"Venues\" records could be fetched."]);
            }

            $aryRows = [];
            foreach ($allCheckedVenues as $row){
                //Change the "selected" value to true for "ID" field == "$_SESSION['Venues_ID']":
                //-------------------------------------------------------------------------------
                if($row['ID'] == $selVenues_ID){
                    $row['selected'] = true;
                }
                
                //Format the "Questions/Answers" column:
                //--------------------------------------
                $aryRow = json_decode($row['questions_answers'], true);
                $aryNewRow = [];
                foreach($aryRow as $item){
                    $aryNewRow[] = '<b>Q:</b> '.$item['question'].'<br><b>A:</b> '.$item['answer'].'<br>';
                }
                $row['questions_answers'] = implode('', $aryNewRow);

                $aryRows[] = $row;
            }
            return json_encode(["status" => "true", "allCheckedVenues" => $aryRows]);
            
        } catch (PDOException $e) {
            return json_encode(["status" => "false", "errMsg" => "PDOError: ".$e->getMessage()]);
        }        
    }//End function, "fetchAllCheckedVenuesFromDb".
    
    /**   Attempts to fetch the "Venues_ID" of the record associated with the passed-in
     * "Calendar_ID". A JSON-encoded message is returned of the form,
     * 
     *  Success: '["status" => "true", "selectedVenues_ID" => "[...]"]', or
     *  Failure: '["status" => "false", "errMsg => an error message]'
     * 
     * @param int $Calendar_ID -- A primary key value in the "Calendar" table.
     * @param PDO $db -- A database connection.
     * @return JSON array - Described above.
     */
    public static function getSelectedVenuesId($Calendar_ID, $db){
        if(empty($Calendar_ID)){
            return json_encode(["status" => "false", "errMsg" => "Invalid \"Calendar_ID\" input."]);
        }

        try{
            $stmt = $db->prepare("
                SELECT \"Venues_ID\"
                FROM \"sched_Calendar\"
                WHERE \"ID\" = :Calendar_ID");
            
            $stmt->execute([
                ':Calendar_ID' => $Calendar_ID
            ]);  
            $selectedVenue = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if(empty($selectedVenue)){
                return json_encode(["status" => "false", 'errMsg' => "No \"Venues_ID\" could be fetched."]);
            }
            
            return json_encode(["status" => "true", "selectedVenues_ID" => $selectedVenue]);
            
        }catch (PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDOError: ".$e->getMessage()]);
        }        
    }//End function, "getSelectedVenuesId".
    
}//End class, "Select_Venue_db".
