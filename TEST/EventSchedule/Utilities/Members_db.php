<?php

class Members_db {
    
    /**   Attempts to fetch an indexed associative array of all members
     * from the "sched_Members" table, where each row consists of "ID"
     * (a "sched_Members" primary key), "title" (from "Proposal_details"),
     * and "name" (the member's name). A JSON-encoded encoded message 
     * is returned of the form,
     * 
     *  Success: '["status" => "true", "aryMembers" => "[...]"]', or
     *  Failure: '["status" => "false", "errMsg => an error message]'
     * 
     * @param PDO $db -- A PostgreSQL database connection.
     * @return JSON -- Described above.
     */
    public static function fetchAllMembers($db){
        try{
            $stmt = $db->prepare("   
                SELECT false, \"sched_Members\".\"ID\", \"title\", \"name\"
                FROM \"sched_Members\" INNER JOIN \"Proposal_details\"
                ON \"sched_Members\".\"home_Proposal_details_ID\" = \"Proposal_details\".\"ID\"
                WHERE \"isShareable\" = true
                ORDER BY \"title\", \"name\";
            ");
            
            $stmt->execute();                
            $aryMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if(empty($aryMembers)){
                return json_encode(["status" => "false", 'errMsg' => "No member records could be fetched from \"sched_Members\"."]);
            }
            
            return json_encode(["status" => "true", "aryMembers" => $aryMembers]);
            
        }catch (PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDOError: ".$e->getMessage()]);
        }
    }//End function, "fetchAllMembers".
    
    /**   Attempts to fetch an array of "sched_Members" primary key values that have been selected
     * for the passed-in "Events_ID". These primary key values correspond to members selected
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
    public static function fetchAllSelectedMemberIDs($Events_ID, $db){
        if(empty($Events_ID)){
            return json_encode(["status" => "false", "errMsg" => "Invalid \"Events_ID\" input."]);
        }
        
        try{
            $stmt = $db->prepare("   
                SELECT \"sched_Members_ID\"
                FROM \"sched_Events_Members\"
                WHERE \"sched_Events_ID\" = :Events_ID;
            ");
            
            $stmt->execute([':Events_ID' => $Events_ID]);            
            $arySelectedIDs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $aryResults = [];
            foreach ($arySelectedIDs as $item){
                $aryResults[] = $item['sched_Members_ID'];
            }           
            return json_encode(["status" => "true", "arySelectedIDs" => $aryResults]);
            
        }catch (PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDOError: ".$e->getMessage()]);
        }
    }//End function, "fetchAllSelectedMemberIDs".
    
    /**   Attempts to insert a single record into the "sched_Members" table, along with a single record into the 
     * intersection table, "sched_Events_Members", using the passed-in JSON string (of the fields, "Events_ID",
     * "home_Proposal_details_ID", "name", and "isShareable"). The "sched_Members" record is uniquely identified 
     * by the created "ID" field, because several records could have been inserted with the same three values as 
     * in the JSON. Upon success, a "status" of true is returned; otherwise, a "status" of false is returned 
     * with an "errMsg". 
     * 
     * Note: This method executes a database transaction with the possibility of rollback upon error,
     *       since SQL-INSERTS are done over two related tables.
     * 
     * @param JSON Associative Array $jsonMember -- Described above.
     * @param PDO $db -- A database connection.
     * @return JSON Associative Array -- A "status" property indicating whether the SQL-INSERT 
     *    succeeded with an "errMsg" property (if "status" is false).
     */
    public static function insertMemberInDb($jsonMember, $db){
        try{
            $aryMember = json_decode($jsonMember, true);

            if(empty($aryMember['home_Proposal_details_ID']) || empty($aryMember['Events_ID'])){
                return(json_encode(["status" => "false", "errMsg" => 
                    "Invalid \"home_Proposal_details_ID\" or \"Events_ID\" input in the JSON."]));
            }            
        }catch (Exception $ex){
            return(json_encode(["status" => "false", "errMsg" => "Invalid JSON, \"jsonMember\" input."]));
        }

        try{
            //Begin the insert-member transaction:
            $db ->beginTransaction();
            
            //Prepare the SQL statement with placeholders:
            $stmt_1 = $db->prepare("
                INSERT INTO \"sched_Members\" (\"home_Proposal_details_ID\", \"name\", \"isShareable\") 
                VALUES (:home_Proposal_details_ID, :name, :isShareable)
                RETURNING \"ID\";
            ");

            //Bind parameters and execute the statement:
            $stmt_1->execute([':home_Proposal_details_ID' => $aryMember['home_Proposal_details_ID'],
                            ':name' => $aryMember['name'],
                            ':isShareable' => $aryMember['isShareable']? 1: 0]);

            //Get the "sched_Members", "ID" value just created:
            $Members_ID = $stmt_1 -> fetchColumn();

            if($Members_ID === false){
                //Roleback the insert-member transaction:
                $db->rollBack();
            
                return json_encode(["status" => "false", "errMsg" => "Transaction Rolled Back. The \"sched_Members\" record could not be created."]);
            } 

            $stmt_2 = $db->prepare("
                INSERT INTO \"sched_Events_Members\" (\"sched_Events_ID\", \"sched_Members_ID\")
                VALUES (:Events_ID, :Members_ID)
                RETURNING \"ID\";
            ");
            
            //Bind parameters and execute the statement:
            $stmt_2->execute([':Events_ID' => $aryMember['Events_ID'],
                              ':Members_ID' => $Members_ID]);

            $Events_Members_ID = $stmt_2 -> fetchColumn();
            
            if($Events_Members_ID === false){
                //Roleback the insert-member transaction:
                $db->rollBack();
            
                return json_encode(["status" => "false", "errMsg" => "Transaction Rolled Back. The \"sched_Events_Members\" intersection record could not be created."]);
            }
            
            //Commit the insert-member transaction:
            $db->commit();

            return json_encode(["status" => "true"]);

        }catch(PDOException $e){
            //Roleback the insert-member transaction:
            $db->rollBack();
            
            return json_encode(["status" => "false", "errMsg" => "Transaction Rolled Back. PDO Error: ".$e->getMessage()]);
        }
    }//End function, "insertMemberInDb".
    
    /**   Attempts to update member selections in the "sched_Events_Members" intersection table.
     * If successful the passed-in "$Events_ID" is used to delete all records having it from
     * the table. Then, the passed-in "$jsonSelectedMemberIDs" is looped through, and during
     * each iteration, a new entry is created in the table for "$Events_ID" and the current
     * selected member ID. A JSON-encoded encoded message is returned of the form,
     * 
     *  Success: '["status" => "true"]', or
     *  Failure: '["status" => "false", "errMsg => an error message]'
     * 
     * Note: This method executes a database transaction with the possibility of rollback upon error,
     *       since several SQL-INSERTS are done following an SQL-DELETE.
     * 
     * @param JSON $jsonSelectedMemberIDs -- A JSON array of "Members_ID" values to inserted.
     * @param integer $Events_ID -- Identifies the current record of "sched_Events".
     * @param PDO $db -- A PostgreSQL database connection.
     * @return JSON -- Described above.
     */
    public static function updateMemberSelectionsInDb($jsonSelectedMemberIDs, $Events_ID, $db){
        try{
            $arySelectedMemberIDs = json_decode($jsonSelectedMemberIDs, true);

            if(empty($Events_ID)){
                return(json_encode(["status" => "false", "errMsg" => "Invalid \"Events_ID\" input."]));
            }            
        }catch (Exception $ex){
            return(json_encode(["status" => "false", "errMsg" => "Invalid JSON, \"jsonSelectedMemberIDs\" input."]));
        }
        
        try{
            //Begin the update-member-selections transaction:
            $db ->beginTransaction();
            
            //Prepare the SQL statement with placeholders:
            $stmt_1 = $db->prepare("
                DELETE FROM \"sched_Events_Members\" 
                WHERE \"sched_Events_ID\" = :Events_ID;
            ");
            
            //Bind parameters and execute the statement:
            $stmt_1->execute([':Events_ID' => $Events_ID]);
            
            foreach ($arySelectedMemberIDs as $selectedMemberID) {
                $stmt_2 = $db->prepare("
                    INSERT INTO \"sched_Events_Members\" (\"sched_Events_ID\", \"sched_Members_ID\")
                    VALUES (:Events_ID, :Members_ID)
                    RETURNING \"ID\";
                ");
                
                //Bind parameters and execute the statement:
                $stmt_2->execute([':Events_ID' => $Events_ID,
                                  ':Members_ID' => $selectedMemberID]);
                
                $Events_Members_ID = $stmt_2 -> fetchColumn();

                if($Events_Members_ID === false){
                    //Roleback the update-member-selections transaction:
                    $db->rollBack();

                    return json_encode(["status" => "false", "errMsg" => "Transaction Rolled Back. The \"sched_Events_Members\" intersection records could not be updated."]);
                }
            }
            //Commit the update-member-selections transaction:
            $db->commit();

            return json_encode(["status" => "true"]);
            
        }catch(PDOException $e){
            //Roleback the update-member-selections transaction:
            $db->rollBack();
            
            return json_encode(["status" => "false", "errMsg" => "Transaction Rolled Back. PDO Error: ".$e->getMessage()]);
        }
    }//End function, "updateMemberSelectionsinDb".
    
}//End class, "Members_db".
