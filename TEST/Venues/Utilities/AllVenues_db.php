<?php

class AllVenues_db{
    
    /**   Attempts to retrieve an indexed associative array of [ID, name, venue_check] values
     * from the "Venues" table for all "ID" values > 0. Upon success, that array is returned. 
     * Otherwise, false is returned with an error message. A JSON-encoded encoded message is 
     * returned of the form,
     * 
     *  Success: '["status" => "true", "allVenues" => "[...]"]', or
     *  Failure: '["status" => "false", "errMsg => an error message]'
     * 
     * @param PDO $db -- A database connection.
     * @return JSON error message/JSON Indexed Associative Array -- Described above.
     */
    public static function getAllVenuesFromDb($db){
        try{
            $stmt = $db->prepare("
                SELECT \"ID\", \"name\", \"venue_check\"
                FROM \"Venues\"
                WHERE \"ID\" > 0
                ORDER BY \"name\";
            ");
            
            $stmt->execute();
            $allVenues = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if(!empty($allVenues)){
                return json_encode(["status" => "true", "allVenues" => $allVenues]);

            }else{
                return json_encode(["status" => "false", "errMsg" => "Venue records could not be fetched."]);
            }
        }catch (PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDOError: ".$e->getMessage()]);
        }
    }//End function, "getAllVenuesFromDb".
    
    /**   Attempts to remove a record from the "Venues" table
     * for the ID passed in. Upon success, a "status" of true is returned. 
     * Otherwise, a "status" of false is returned with an "errMsg".
     * 
     * @param integer $Venues_ID
     * @param PDO $db -- A database connection.
     * @return JSON Associative Array -- A "status" indicating whether the deletion succeeded,
     *    and possibly an "errMsg" property (if "status" is false).
     */
    public static function deleteVenueFromDb($Venues_ID, $db){
        try{
            if(empty($Venues_ID)){
                return json_encode(["status" => "false", "errMsg" => "Invalid \"Venues_ID\" passed in."]);
            }
            
            //Prepare and execute an SQL-DELETE statement to remove a 
            //record from the "Venues" table for the passed-in "$Venues_ID":
            //--------------------------------------------------------------
            $stmt = $db->prepare("
                 DELETE FROM \"Venues\"
                 WHERE \"ID\" = :Venues_ID;
            ");

            //Bind parameters and execute the statement:
            $stmt->execute([
                ':Venues_ID' => $Venues_ID
            ]);

            $rowsAffected = $stmt->rowCount();

            if($rowsAffected > 0){
                return json_encode(["status" => "true"]);

            }else{
                return json_encode(["status" => "false", "errMsg" => "Selected \"Venues\" record could not be deleted from db."]);
            }
                
        }catch (PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
    }//End function, "deleteVenueFromDb".
    
    /**   Attempts to save a single "Venue Check" value (whether the checkbox 
     * has been checked or not) to the DB. A JSON-encoded encoded message is 
     * returned of the form,
     * 
     *  Success: '["status" => "true"]', or
     *  Failure: '["status" => "false", "errMsg => an error message]'
     * 
     * @param integer $Venues_ID -- A primary key value in the "Venues" table.
     * @param boolean $isChecked -- Either true or false, corresponding to
     *      checked or unchecked.
     * @param PDO $db -- A database connection.
     * @return JSON Associative Array -- A "status" indicating whether the save 
     *     succeeded, and possibly an "errMsg" property (if "status" is false).
     */   
    public static function saveVenueChecksInDb($Venues_ID, $isChecked, $db){
        try{
            $stmt = $db->prepare("
                UPDATE \"Venues\"
                SET \"venue_check\" = :isChecked
                WHERE \"ID\" = :Venues_ID
            ");
            
            //Bind parameters and execute the statement:
            $stmt->execute([
                ':Venues_ID' => $Venues_ID,
                ':isChecked' => $isChecked   
            ]);

            $rowsAffected = $stmt->rowCount();

            if($rowsAffected > 0){
                return json_encode(["status" => "true"]);

            }else{
                return json_encode(["status" => "false", "errMsg" => "The \"Venues\" record (\"ID\" = $Venues_ID) could not be updated in the db."]);
            }
            
            
        }catch (PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
    }
    
}//End class, "AllVenues_db".