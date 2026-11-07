<?php
class Screenfield_validation_db{
    
    /**   Attempts to test the validity of the input screen-field data ($answer)
     * against the rule (first part of input $question). Searches the DB for the rule.
     * If found, the corresponding regular expression is retrieved and applied
     * to "$answer". If a valid match, then a "status" of "true" is returned.
     * Otherwise, a "status" of "false" is returned along with an "errMsg" 
     * (which is extracted from the matching record).
     * 
     * Note: If "$questionRule" doesn't start with "*", but "$answer" is empty, expect
     * 
     *          '["status" => "true", "errMsg" => ""]'.
     * 
     * @param string $question -- The question or caption, whose validation rule is
     *      at the beginning, separated from the rest of the question by a colon, ":".
     * @param string $answer -- The answer or input data to test against the validation
     *      rule.
     * @param PDO $db -- A database connection. 
     */
    public static function isScreenfieldDataValid($question, $answer, $db){
        //Set the "$isRequired" flag, and remove the "*" from "$question" if present:
        if(substr($question, 0, 1) === '*'){
            $isRequired = true;
            $question = substr($question, 1);            
        }else{
            $isRequired = false;
        }
        
        //Extract the common language "$rule" from "$question".
        //Note: We must make sure that only one ":" exists in
        //      "$question", to delimit the rule.
        $rule = explode(':', $question)[0];
        if($rule === $question){
            $rule = '';
        }
        
        if(!$isRequired && empty($rule)){
            return json_encode(["status" => "true", "errMsg" => ""]);
        }
        
        if($isRequired && empty($answer)){
           return json_encode(["status" => "false", "errMsg" => "This field is required!"]);
           
        }
        
        if(!$isRequired && empty($answer)){
           return json_encode(["status" => "true", "errMsg" => ""]);                
        }
                      
        if(empty($rule)){
            return json_encode(["status" => "true", "errMsg" => ""]);
        }

        //Execute the statement:
        try{
            $stmt = $db->prepare("SELECT \"regex\", \"errMsg\" FROM \"Screenfield_validation\" WHERE \"rule\" ILIKE :rule");
            //Bind parameter and execute the statement:
            $stmt->execute([':rule' => $rule]);
            $validationRecord = $stmt->fetch(PDO::FETCH_ASSOC);

            if(empty($validationRecord)){
                return json_encode(["status" => "false", "errMsg" => "Problem: No codes match \"$rule\" in database."]); 
            }

            $regex = $validationRecord['regex'];
            $errMsg = $validationRecord['errMsg'];

            if(!preg_match($regex, $answer)) {
                return json_encode(["status" => "false", "errMsg" => $errMsg]); 
            }

            return json_encode(["status" => "true", "errMsg" => ""]);

        } catch (PDOException $e) {
            return json_encode(["status" => "false", "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
    }//End function, "isScreenfieldDataValid".
    
    
    /**   Attempts to fetch an associative array of [ID, rule, regex, errMsg] from 
     * the "Screenfield_validation" table. Upon success, the assoc array is returned. 
     * Otherwise, false is returned.
     *
     * @param PDO $db -- A database connection. 
     * @return bool/Assoc Array -- Either the above associative array or false.
     */
    public static function getAllScreenfieldValidationsFromDb($db){
        try {
            //Execute the statement:
            $stmt = $db->query("SELECT \"ID\", \"rule\", \"regex\", \"errMsg\" FROM \"Screenfield_validation\" ORDER BY \"rule\"");

            //Fetch the result:
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //Check if the rows exist:
            if(empty($rows)) {
                return json_encode(["status" => "false", "errMsg" => "Error: No rows found in \"Screenfield_validation\"."]);
            }

            //Return associative array of Screenfield Validations:
            return json_encode(["status" => 'true', "allScreenfieldValidations" => $rows]);
            
        } catch (PDOException $e) {
            return json_encode(["status" => "false", "errMsg" => "PDO Error: ".$e->getMessage()]);
        }

        
    }//End function, "getAllScreenfieldValidationsFromDb".
    
    /**   Attempts to save an array of "Screenfield_validation" records
     * (their "ID", "rule", "regex", and "errMsg" values) to the DB. 
     * If successful, a "status" of "true" is returned; Otherwise, a "status" 
     * of "false" is returned with an "errMsg".
     * 
     * Note: This method executes SQL-UPDATE statements for each
     *       row passed in. The loop of executions is within
     *       a transaction, with the possibility of rollback.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param JSON Array $jsonSelectedScreenfieldValidations -- (Described above.)
     * @return JSON Array -- Whether the save succeeded, and a possible "errMsg".
     */
    public static function updateSelectedScreenfieldValidations($db, $jsonSelectedScreenfieldValidations){
        $arySelectedScreenfieldValidations = json_decode($jsonSelectedScreenfieldValidations, true);
        
        try {
            //Begin the transaction:
            $db->beginTransaction();

            foreach($arySelectedScreenfieldValidations as $item){
                $ID = $item[0];
                $rule = $item[1];
                $regex = $item[2];
                $errMsg = $item[3];
                if(empty($ID) || empty($rule) || empty($regex) || empty($errMsg)){
                    continue;
                }
                $strSqlUpdate = "UPDATE \"Screenfield_validation\" ".
                   "SET \"rule\" = :rule, \"regex\" = :regex, \"errMsg\" = :errMsg ".
                   "WHERE \"ID\" = :ID";
                
                $stmt = $db->prepare($strSqlUpdate);
                $stmt->execute([
                    ':ID' => $ID,
                    ':rule' => $rule,
                    ':regex' => $regex,
                    ':errMsg' => $errMsg]);
                $rowsAffected = $stmt->rowCount();

                if($rowsAffected <= 0){
                    //Rollback the transaction in case of an error:
                    $db->rollBack();
                    return json_encode(["status" => "false", "errMsg" => "Error: Screen Validation could not be updated."]);
                }
            }        
            //Commit the transaction:
            $db->commit();
            
        }catch (PDOException $e){
            //Rollback the transaction in case of an error:
            $db->rollBack();
            return json_encode(["status" => "false", "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
        return json_encode(["status" => "true", "errMsg" => ""]);           
    }//End function, "updateSelectedScreenfieldValidations".
    
    /**   Attempts to delete an array of "Screenfield_validation" records
     * (their "ID", "rule", "regex", and "errMsg" values) from the DB.
     * If successful, a "status" of "true" is returned; Otherwise, a "status" 
     * of "false" is returned with an "errMsg".
     *
     * Note: This method executes SQL-DELETE statements for each
     *       row passed in. The loop of executions is within
     *       a transaction, with the possibility of rollback.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param JSON Array $jsonSelectedScreenfieldValidations -- (Described above.)
     * @return JSON Array -- Whether the deletion succeeded, and a possible "errMsg".
     */
    public static function deleteSelectedScreenfieldValidations($db, $jsonSelectedScreenfieldValidations){
        $arySelectedScreenfieldValidations = json_decode($jsonSelectedScreenfieldValidations, true);
        
        try {
            //Begin the transaction:
            $db->beginTransaction();

            foreach ($arySelectedScreenfieldValidations as $item) {
                $ID = $item[0];
                if(empty($ID)){
                    continue;
                }
                $strSqlDelete = "DELETE FROM \"Screenfield_validation\" WHERE \"ID\" = $ID ";
                $stmt = $db->prepare($strSqlDelete);
                $stmt->execute();
                $rowsAffected = $stmt->rowCount();

                if($rowsAffected <= 0){
                    //Rollback the transaction in case of an error:
                    $db->rollBack();
                    return json_encode(["status" => "false", "errMsg" => "Error: Screen Validation could not be deleted."]);            
                }
            }        
            //Commit the transaction:
            $db->commit();
            
        }catch (PDOException $e){
            //Rollback the transaction in case of an error:
            $db->rollBack();
            return json_encode(["status" => "false", "errMsg" => "PDO Error: ".$e->getMessage()]);            
        }
        
        return json_encode(["status" => "true", "errMsg" => ""]);            
    }//End function, "deleteRoles".
    

    /**  Attempts to insert a new Screenfield Validation record 
     * (the "rule", "regex", and "errMsg" values) into the "Screenfield_validation" 
     * table. If successful, a "status" of "true" is returned; Otherwise, a "status" 
     * of "false" is returned with an "errMsg".
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @param string $jsonNewScreenfieldValidation -- The name of a new Role.
     * @return Integer/bool -- Either the primary key, "ID", of the
     *     new Role, or false.
     */
    public static function insertNewScreenfieldValidation($db, $jsonNewScreenfieldValidation){
        try {
            $aryNewScreenfieldValidation = json_decode($jsonNewScreenfieldValidation, true);
            
            if(empty($aryNewScreenfieldValidation['newRule'] ||
               empty($aryNewScreenfieldValidation['newRegex'] ||
               empty($aryNewScreenfieldValidation['newErrMsg'])))){
                return json_encode(["status" => "false", "errMsg" => "Error: Invalid input Screen-field Validation parameters."]);  
            }
            $strSqlInsert = "INSERT INTO \"Screenfield_validation\" (\"rule\", \"regex\", \"errMsg\") ".
                "VALUES (:newRule, :newRegex, :newErrMsg)";
            $stmt = $db->prepare($strSqlInsert);
            $stmt->execute([
                ':newRule' => $aryNewScreenfieldValidation['newRule'],
                ':newRegex' => $aryNewScreenfieldValidation['newRegex'],
                ':newErrMsg' => $aryNewScreenfieldValidation['newErrMsg']
            ]);
            $rowsAffected = $stmt->rowCount();

            if($rowsAffected <= 0){
                return json_encode(["status" => "false", "errMsg" => "Error: New Screen-field Validation could not be saved."]);
            }
            
        }catch (PDOException $e){
            return json_encode(["status" => "false", "errMsg" => "PDO Error: ".$e->getMessage()]);
        }
        
        return json_encode(["status" => "true", "errMsg" => ""]);              
    }//End function, "insertNewScreenfieldValidation".

}//End class, "Screenfield_validation_db".