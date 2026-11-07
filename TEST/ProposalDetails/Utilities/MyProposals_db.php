<?php

class MyProposals_db{
    
    /**   Attempts to retrieve an indexed associative array of [Proposal_details_ID, title] values
     * from the "Proposal_details" table for the passed-in "Users_ID". Upon success, that array is
     * returned. Otherwise, false is returned with an error message echoed to the screen.
     * 
     * @param Integer $Users_ID
     * @param PDO $db -- A database connection.
     * @return bool/JSON Indexed Associative Array -- Either an indexed associative array or false,
     *     indicating that the fetch attempt returned no records (in which case a message is also
     *     returned).
     */
    public static function getMyProposalsFromDb($Users_ID, $db){
        if(empty($Users_ID)){
            echo json_encode(["error" => "Invalid \"Users_ID\" passed in."]);
            return false;
        }
        try{
            $stmt = $db->prepare("
                SELECT \"ID\", \"title\"
                FROM \"Proposal_details\"
                WHERE \"Users_ID\" = :Users_ID
                ORDER BY \"title\";
            ");
            
            $stmt->execute([':Users_ID' => $Users_ID]);
            $myProposals = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if(!empty($myProposals)){
                return json_encode($myProposals);

            }else{
                echo json_encode(['Error' => 'Proposal records could not be fetched.']);
                return false;
            }
        }catch (PDOException $e){
            echo json_encode(["PDOError" => $e->getMessage()]);
            return false;
        }
    }
    
    
}//End class, "MyProposals_db".