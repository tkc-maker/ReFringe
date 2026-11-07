<?php
class Search_Proposals_db{
    
    /**   Dynamically builds an SQL-SELECT statement from the search criteria passed in.
     * If nothing is passed in, then '' is returned.
     * 
     * @param JSON $jsonSearchProposals -- Contains search criteria from the Proposals "Search screen".
     * @return string -- An SQL-SELECT statement.
     */
    public static function buildSearchSQL($jsonSearchProposals){
        $arySearchProposals = json_decode($jsonSearchProposals, true);

        $_SESSION['SP_Include_1'] = $arySearchProposals['Include_1'];
        $_SESSION['SP_Role_1'] = $arySearchProposals['Role_1'];
        $_SESSION['SP_First_Name_1'] = $arySearchProposals['First-Name_1'];
        $_SESSION['SP_Last_Name_1'] = $arySearchProposals['Last-Name_1'];
        $_SESSION['SP_Phone_1'] = $arySearchProposals['Phone_1'];
        $_SESSION['SP_Email_1'] = $arySearchProposals['Email_1'];
        $_SESSION['SP_Title_1'] = $arySearchProposals['Title_1'];
        $_SESSION['SP_Question-classes_1'] = $arySearchProposals['Question-classes_1'];
        $_SESSION['SP_Questions-and-Answers_1'] = $arySearchProposals['Questions-and-Answers_1'];

        $_SESSION['SP_Include_2'] = $arySearchProposals['Include_2'];
        $_SESSION['SP_Role_2'] = $arySearchProposals['Role_2'];
        $_SESSION['SP_First_Name_2'] = $arySearchProposals['First-Name_2'];
        $_SESSION['SP_Last_Name_2'] = $arySearchProposals['Last-Name_2'];
        $_SESSION['SP_Phone_2'] = $arySearchProposals['Phone_2'];
        $_SESSION['SP_Email_2'] = $arySearchProposals['Email_2'];
        $_SESSION['SP_Title_2'] = $arySearchProposals['Title_2'];
        $_SESSION['SP_Question-classes_2'] = $arySearchProposals['Question-classes_2'];
        $_SESSION['SP_Questions-and-Answers_2'] = $arySearchProposals['Questions-and-Answers_2'];

        $_SESSION['SP_Include_3'] = $arySearchProposals['Include_3'];
        $_SESSION['SP_Role_3'] = $arySearchProposals['Role_3'];
        $_SESSION['SP_First_Name_3'] = $arySearchProposals['First-Name_3'];
        $_SESSION['SP_Last_Name_3'] = $arySearchProposals['Last-Name_3'];
        $_SESSION['SP_Phone_3'] = $arySearchProposals['Phone_3'];
        $_SESSION['SP_Email_3'] = $arySearchProposals['Email_3'];
        $_SESSION['SP_Title_3'] = $arySearchProposals['Title_3'];
        $_SESSION['SP_Question-classes_3'] = $arySearchProposals['Question-classes_3'];
        $_SESSION['SP_Questions-and-Answers_3'] = $arySearchProposals['Questions-and-Answers_3'];

        $_SESSION['SP_Include_4'] = $arySearchProposals['Include_4'];
        $_SESSION['SP_Role_4'] = $arySearchProposals['Role_4'];
        $_SESSION['SP_First_Name_4'] = $arySearchProposals['First-Name_4'];
        $_SESSION['SP_Last_Name_4'] = $arySearchProposals['Last-Name_4'];
        $_SESSION['SP_Phone_4'] = $arySearchProposals['Phone_4'];
        $_SESSION['SP_Email_4'] = $arySearchProposals['Email_4'];
        $_SESSION['SP_Title_4'] = $arySearchProposals['Title_4'];
        $_SESSION['SP_Question-classes_4'] = $arySearchProposals['Question-classes_4'];
        $_SESSION['SP_Questions-and-Answers_4'] = $arySearchProposals['Questions-and-Answers_4'];

        $aryCriteria_1 = [];
        $aryCriteria_2 = [];
        $aryCriteria_3 = [];
        $aryCriteria_4 = [];
        foreach ($arySearchProposals as $key => $value) {
            if(empty($value) || str_contains($key, 'Include')){
                continue;
            }
            $segment = '';
            $fieldname = substr($key, 0, -2);
            if(str_contains($key, 'Question-classes') ||
               str_contains($key, 'Questions-and-Answers')){
                $segment = "\"$fieldname\"::text ILIKE '%$value%'";
            }else{
                $segment = "\"$fieldname\" ILIKE '%$value%'";
            }

            if(str_ends_with($key, '_1')){
                $aryCriteria_1[] = $segment;

            }elseif(str_ends_with($key, '_2')){
                $aryCriteria_2[] = $segment;

            }elseif(str_ends_with($key, '_3')){
                $aryCriteria_3[] = $segment;

            }elseif(str_ends_with($key, '_4')){
                $aryCriteria_4[] = $segment;
            }
        }

        $strWHERE_1 = '';
        if($arySearchProposals['Include_1']){
            foreach($aryCriteria_1 as $criterion){
                if($strWHERE_1 !== ''){
                    $strWHERE_1 .= ' AND ';
                }
                $strWHERE_1 .= $criterion;
            }
        }
        $strWHERE_2 = '';
        if($arySearchProposals['Include_2']){
            foreach($aryCriteria_2 as $criterion){
                if($strWHERE_2 !== ''){
                    $strWHERE_2 .= ' AND ';
                }
                $strWHERE_2 .= $criterion;
            }
        }
        $strWHERE_3 = '';
        if($arySearchProposals['Include_3']){
            foreach($aryCriteria_3 as $criterion){
                if($strWHERE_3 !== ''){
                    $strWHERE_3 .= ' AND ';
                }
                $strWHERE_3 .= $criterion;
            }
        }
        $strWHERE_4 = '';
        if($arySearchProposals['Include_4']){
            foreach($aryCriteria_4 as $criterion){
                if($strWHERE_4 !== ''){
                    $strWHERE_4 .= ' AND ';
                }
                $strWHERE_4 .= $criterion;
            }
        }

        if(empty($strWHERE_1) && empty($strWHERE_2) && empty($strWHERE_3) && empty($strWHERE_4)){
            return '';
        }

        $strWHERE_clause = '';
        if($strWHERE_1 !== ''){
           $strWHERE_clause .= " ($strWHERE_1) OR "; 
        }
        if($strWHERE_2 !== ''){
           $strWHERE_clause .= " ($strWHERE_2) OR "; 
        }
        if($strWHERE_3 !== ''){
           $strWHERE_clause .= " ($strWHERE_3) OR "; 
        }
        if($strWHERE_4 !== ''){
           $strWHERE_clause .= " ($strWHERE_4) OR "; 
        }
        $strWHERE_clause = substr($strWHERE_clause, 0, -3);

        $strSQL_SELECT = "SELECT * FROM \"proposal_questions_view_2\"
            WHERE $strWHERE_clause
            ORDER BY \"Role\", \"Last-Name\", \"First-Name\";
        ";

        return $strSQL_SELECT;
    }//End function, "buildSearchSQL".
    
    /**   Responds to clicking the "Show All Proposals" button
     * by generating an appropriate SQL statement over a view.
     * 
     * @return string -- An SQL-SELECT statement.
     */
    public static function showAllProposalsSQL(){
        $strSQL_SELECT = "SELECT * FROM \"proposal_questions_view_2\"
            ORDER BY \"Title\";
        ";
        
        return $strSQL_SELECT;
    }
    
    /**   Attempts to fetch an associative array of rows from the "proposal_questions_view_2",
     * consistent with the SQL-SELECT statement passed in as a string. The result is
     * primarily for populating a HandsonTable. Upon success, the assoc array is returned. 
     * Otherwise, false is returned.
     *
     * @param PDO $db -- A database connection. 
     * @param string $sqlSelect -- An SQL-SELECT ORDER BY string against the "proposal_questions_view_2".
     * @return bool/Assoc Array -- Either an array of arrays (for rows and columns) or false.
     */
    public static function getProposalDetailsQuestionsFromDb($db, $sqlSelect, $assoc_cols=false){
        try {
            if(empty($sqlSelect)){
                return json_encode(["status" => "false", "errMsg" => "Error: No SQL statement provided."]);
            }
            
            //Execute the statement:
            $stmt = $db->query($sqlSelect);

            //Fetch the result:
            if($assoc_cols){
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }else{
                $rows = $stmt->fetchAll(PDO::FETCH_NUM);
            }

            //Check if the rows exist:
            if($rows === false) {
                return json_encode(["status" => "false", "errMsg" => "Error: No rows found in \"proposal_questions_view_2\"."]);
            }
            $aryRows = [];
            foreach ($rows as $row){
                array_unshift($row, true);
                                
                if($assoc_cols === false){
                    //Format the "Question Classes" column:
                    //-------------------------------------
                    $jsonRow_9 = $row[9];                      
                    $aryRow_9 = json_decode($jsonRow_9, true);
                    $aryNewRow_9 = [];
                    foreach($aryRow_9 as $item){
                        $aryNewRow_9[] = '--'.$item['name'].'<br>';
                    }
                    $row[9] = implode('', $aryNewRow_9);

                    //Format the "Questions/Answers" column:
                    //--------------------------------------
                    $jsonRow_10 = $row[10];
                    $aryRow_10 = json_decode($jsonRow_10, true);
                    $aryNewRow_10 = [];
                    foreach($aryRow_10 as $item_1){
                        $aryNewRow_10[] .= '<b><u><i>'.$item_1['Question-class'].'</i></u></b><br>';
                        foreach($item_1['questions_answers'] as $item_2){
                            $aryNewRow_10[] .= '<b>Q:</b> '.$item_2['question'].'<br><b>A:</b> '.$item_2['answer'].'<br>';
                        }
                    }
                    $row[10] = implode('', $aryNewRow_10);
                }
                $aryRows[] = $row;
            }
            //Return associative array of rows of fields:
            return json_encode(["status" => "true", "results" => $aryRows]);

        } catch (PDOException $e) {
            return json_encode(["status" => "false", "errMsg" => "PDOError: " . $e->getMessage()]);
        }
    }//End function, "getProposalDetailsQuestionsFromDb".
    
   /**   Clears all search criteria in SESSION.
    * 
    */
   public static function clearCriteriaInSession(){
       $_SESSION['SP_Include_1'] = true;
       $_SESSION['SP_Role_1'] = '';
       $_SESSION['SP_First_Name_1'] = '';
       $_SESSION['SP_Last_Name_1'] = '';
       $_SESSION['SP_Phone_1'] = '';
       $_SESSION['SP_Email_1'] = '';
       $_SESSION['SP_Title_1'] = '';
       $_SESSION['SP_Question-classes_1'] = '';
       $_SESSION['SP_Questions-and-Answers_1'] = '';

       $_SESSION['SP_Include_2'] = false;
       $_SESSION['SP_Role_2'] = '';
       $_SESSION['SP_First_Name_2'] = '';
       $_SESSION['SP_Last_Name_2'] = '';
       $_SESSION['SP_Phone_2'] = '';
       $_SESSION['SP_Email_2'] = '';
       $_SESSION['SP_Title_2'] = '';
       $_SESSION['SP_Question-classes_2'] = '';
       $_SESSION['SP_Questions-and-Answers_2'] = '';

       $_SESSION['SP_Include_3'] = false;
       $_SESSION['SP_Role_3'] = '';
       $_SESSION['SP_First_Name_3'] = '';
       $_SESSION['SP_Last_Name_3'] = '';
       $_SESSION['SP_Phone_3'] = '';
       $_SESSION['SP_Email_3'] = '';
       $_SESSION['SP_Title_3'] = '';
       $_SESSION['SP_Question-classes_3'] = '';
       $_SESSION['SP_Questions-and-Answers_3'] = '';

       $_SESSION['SP_Include_4'] = false;
       $_SESSION['SP_Role_4'] = '';
       $_SESSION['SP_First_Name_4'] = '';
       $_SESSION['SP_Last_Name_4'] = '';
       $_SESSION['SP_Phone_4'] = '';
       $_SESSION['SP_Email_4'] = '';
       $_SESSION['SP_Title_4'] = '';
       $_SESSION['SP_Question-classes_4'] = '';
       $_SESSION['SP_Questions-and-Answers_4'] = '';
   }//End function, "clearCriteria".

}//End class, "Proposals_Search_db".