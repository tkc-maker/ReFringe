<?php

class Resources_db {
    
    /**   Attempts to fetch an indexed associative array of all resources
     * from the "sched_Resources" table, where each row consists of "ID"
     * (a "sched_Resources" primary key), "title" (from "Proposal_details"),
     * and "name" (the resource's name). A JSON-encoded encoded message 
     * is returned of the form,
     * 
     *  Success: '["status" => "true", "aryResources" => "[...]"]', or
     *  Failure: '["status" => "false", "errMsg => an error message]'
     * 
     * @param PDO $db -- A PostgreSQL database connection.
     * @return JSON -- Described above.
     */
    public static function fetchAllResources($db){
        try{
            $stmt = $db->prepare("   
                SELECT false, \"sched_Resources\".\"ID\", \"title\", \"name\"
                FROM \"sched_Resources\" INNER JOIN \"Proposal_details\"
                ON \"sched_Resources\".\"home_Proposal_details_ID\" = \"Proposal_details\".\"ID\"
                WHERE \"isShareable\" = true
                ORDER BY \"title\", \"name\";
            ");
            
            $stmt->execute();                
            $aryResources = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if(empty($aryResources)){
                return json_encode(["status" => "false", 'errMsg' => "No resource records could be fetched from \"sched_Resources\"."]);
            }
            
            return json_encode(["status" => "true", "aryResources" => $aryResources]);
            
        }catch (PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDOError: ".$e->getMessage()]);
        }
    }//End function, "fetchAllResources".
    
    /**   Attempts to fetch an array of "sched_Resources" primary key values that have been selected
     * for the passed-in "Events_ID". These primary key values correspond to resources selected
     * for the current event. Each row of the returned array will contain one primary key value.
     * A JSON-encoded encoded message is returned of the form,
     * 
     *  Success: '["status" => "true", "arySelectedIDs" => "[...]"]', or
     *  Failure: '["status" => "false", "errMsg => an error message]'
     * 
     * @param integer $Events_ID
     * @param PDO $db -- A PostgreSQL database connection.
     * @return JSON -- Described above.
     */
    public static function fetchAllSelectedResourceIDs($Events_ID, $db){
        if(empty($Events_ID)){
            return json_encode(["status" => "false", "errMsg" => "Invalid \"Events_ID\" input."]);
        }
        
        try{
            $stmt = $db->prepare("   
                SELECT \"sched_Resources_ID\"
                FROM \"sched_Events_Resources\"
                WHERE \"sched_Events_ID\" = :Events_ID;
            ");
            
            $stmt->execute([':Events_ID' => $Events_ID]);            
            $arySelectedIDs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $aryResults = [];
            foreach ($arySelectedIDs  as $item){
                $aryResults[] = $item['sched_Resources_ID'];
            }           
            return json_encode(["status" => "true", "arySelectedIDs" => $aryResults]);
            
        }catch (PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDOError: ".$e->getMessage()]);
        }
    }//End function, "fetchAllSelectedResourceIDs".
    
    /**   Attempts to insert a single record into the "sched_Resources" table, along with a single record into the 
     * intersection table, "sched_Events_Resources", using the passed-in JSON string (of the fields, "Events_ID",
     * "home_Proposal_details_ID", "name", and "isShareable"). The "sched_Resources" record is uniquely identified 
     * by the created "ID" field, because several records could have been inserted with the same three values as 
     * in the JSON. Upon success, a "status" of true is returned; otherwise, a "status" of false is returned 
     * with an "errMsg". 
     * 
     * Note: This method executes a database transaction with the possibility of rollback upon error,
     *       since SQL-INSERTS are done over two related tables.
     * 
     * @param JSON Associative Array $jsonResource -- Described above.
     * @param PDO $db -- A database connection.
     * @return JSON Associative Array -- A "status" property indicating whether the SQL-INSERT 
     *    succeeded with an "errMsg" property (if "status" is false).
     */
    public static function insertResourceInDb($jsonResource, $db){
        try{
            $aryResource = json_decode($jsonResource, true);
            
            if(empty($aryResource['home_Proposal_details_ID']) || empty($aryResource['Events_ID'])){
                return(json_encode(["status" => "false", "errMsg" => 
                    "Invalid \"home_Proposal_details_ID\" or \"Events_ID\" input in the JSON."]));
            }            
        }catch (Exception $ex){
            return(json_encode(["status" => "false", "errMsg" => "Invalid JSON, \"jsonResource\" input."]));
        }

        try{
            //Begin the insert-resource transaction:
            $db ->beginTransaction();
            
            //Prepare the SQL statement with placeholders:
            $stmt_1 = $db->prepare("
                INSERT INTO \"sched_Resources\" (\"home_Proposal_details_ID\", \"name\", \"isShareable\") 
                VALUES (:home_Proposal_details_ID, :name, :isShareable)
                RETURNING \"ID\";
            ");

            //Bind parameters and execute the statement:
            $stmt_1->execute([':home_Proposal_details_ID' => $aryResource['home_Proposal_details_ID'],
                            ':name' => $aryResource['name'],
                            ':isShareable' => $aryResource['isShareable']? 1: 0]);

            //Get the "sched_Resources", "ID" value just created:
            $Resources_ID = $stmt_1 -> fetchColumn();

            if($Resources_ID === false){
                //Roleback the insert-resource transaction:
                $db->rollBack();
            
                return json_encode(["status" => "false", "errMsg" => "Transaction Rolled Back. The \"sched_Resources\" record could not be created."]);
            } 

            $stmt_2 = $db->prepare("
                INSERT INTO \"sched_Events_Resources\" (\"sched_Events_ID\", \"sched_Resources_ID\")
                VALUES (:Events_ID, :Resources_ID)
                RETURNING \"ID\";
            ");
            
            //Bind parameters and execute the statement:
            $stmt_2->execute([':Events_ID' => $aryResource['Events_ID'],
                              ':Resources_ID' => $Resources_ID]);

            $Events_Resources_ID = $stmt_2 -> fetchColumn();
            
            if($Events_Resources_ID === false){
                //Roleback the insert-resource transaction:
                $db->rollBack();
            
                return json_encode(["status" => "false", "errMsg" => "Transaction Rolled Back. The \"sched_Events_Resources\" intersection record could not be created."]);
            }
            
            //Commit the insert-resource transaction:
            $db->commit();

            return json_encode(["status" => "true"]);

        }catch(PDOException $e){
            //Roleback the insert-resource transaction:
            $db->rollBack();
            
            return json_encode(["status" => "false", "errMsg" => "Transaction Rolled Back. PDO Error: ".$e->getMessage()]);
        }
    }//End function, "insertResourceInDb".
    
    /**   Attempts to update resource selections in the "sched_Events_Resources" intersection table.
     * If successful the passed-in "$Events_ID" is used to delete all records having it from
     * the table. Then, the passed-in "$jsonSelectedResourceIDs" is looped through, and during
     * each iteration, a new entry is created in the table for "$Events_ID" and the current
     * selected resource ID. A JSON-encoded encoded message is returned of the form,
     * 
     *  Success: '["status" => "true"]', or
     *  Failure: '["status" => "false", "errMsg => an error message]'
     * 
     * Note: This method executes a database transaction with the possibility of rollback upon error,
     *       since several SQL-INSERTS are done following an SQL-DELETE.
     * 
     * @param JSON $jsonSelectedResourceIDs -- A JSON array of "Resources_ID" values to inserted.
     * @param integer $Events_ID -- Identifies the current record of "sched_Events".
     * @param PDO $db -- A PostgreSQL database connection.
     * @return JSON -- Described above.
     */
    public static function updateResourceSelectionsInDb($jsonSelectedResourceIDs, $Events_ID, $db){
        try{
            $arySelectedResourceIDs = json_decode($jsonSelectedResourceIDs, true);

            if(empty($Events_ID)){
                return(json_encode(["status" => "false", "errMsg" => "Invalid \"Events_ID\" input."]));
            }            
        }catch (Exception $ex){
            return(json_encode(["status" => "false", "errMsg" => "Invalid JSON, \"jsonSelectedResourceIDs\" input."]));
        }
        
        try{
            //Begin the update-resource-selections transaction:
            $db ->beginTransaction();
            
            //Prepare the SQL statement with placeholders:
            $stmt_1 = $db->prepare("
                DELETE FROM \"sched_Events_Resources\" 
                WHERE \"sched_Events_ID\" = :Events_ID;
            ");
            
            //Bind parameters and execute the statement:
            $stmt_1->execute([':Events_ID' => $Events_ID]);
            
            foreach ($arySelectedResourceIDs as $selectedResourceID) {
                $stmt_2 = $db->prepare("
                    INSERT INTO \"sched_Events_Resources\" (\"sched_Events_ID\", \"sched_Resources_ID\")
                    VALUES (:Events_ID, :Resources_ID)
                    RETURNING \"ID\";
                ");
                
                //Bind parameters and execute the statement:
                $stmt_2->execute([':Events_ID' => $Events_ID,
                                  ':Resources_ID' => $selectedResourceID]);
                
                $Events_Resources_ID = $stmt_2 -> fetchColumn();

                if($Events_Resources_ID === false){
                    //Roleback the update-resource-selections transaction:
                    $db->rollBack();

                    return json_encode(["status" => "false", "errMsg" => "Transaction Rolled Back. The \"sched_Events_Resources\" intersection records could not be updated."]);
                }
            }
            //Commit the update-resource-selections transaction:
            $db->commit();

            return json_encode(["status" => "true"]);
            
        }catch(PDOException $e){
            //Roleback the update-resource-selections transaction:
            $db->rollBack();
            
            return json_encode(["status" => "false", "errMsg" => "Transaction Rolled Back. PDO Error: ".$e->getMessage()]);
        }
    }//End function, "updateResourceSelectionsinDb".
    
    
}//End class, "Resources_db".
