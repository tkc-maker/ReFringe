<?php
class Proposal_Questions_Search_db{
    /**   Attempts to fetch an associative array of rows from the "proposal_questions_view_2",
     * primarily for populating a HandsonTable. Upon success, the assoc array is returned. 
     * Otherwise, false is returned.
     *
     * @param PDO $db -- A database connection. 
     * @param string $order -- An SQL-SELECT ORDER BY string of field-names, comma-delimited.
     *      Note: Field names should be surrounded by \", e.g.: "\"Last-Name\", \"First-Name\"".
     * @return bool/Assoc Array -- Either an array of arrays (for rows and columns) or false.
     */
    public static function getAllProposalQuestionsResultsFromDb($db, $order){
        try {
            $orderBy = '';
            if(!empty($order)){
                $orderBy = "ORDER BY $order";
            }
            //Execute the statement:
            $stmt = $db->query("SELECT * FROM \"proposal_questions_view_2\" $orderBy");

            //Fetch the result:
            $rows = $stmt->fetchAll(PDO::FETCH_NUM);

            //Check if the rows exist:
            if($rows === false) {
                echo "No rows found in \"proposal_questions_view_2\".";
                return false;
            }
            $aryRows = [];
            foreach ($rows as $row){
                array_unshift($row, true);
                
                //Format the "Genres" column:
                //---------------------------
                $aryRow_9 = json_decode($row[9], true);
                $aryNewRow_9 = [];
                foreach($aryRow_9 as $item){
                    $aryNewRow_9[] = $item['name'].'<br>';
                }
                $row[9] = implode('', $aryNewRow_9);
                
                //Format the "Question Classes" column:
                //-------------------------------------
                $aryRow_10 = json_decode($row[10], true);
                $aryNewRow_10 = [];
                foreach($aryRow_10 as $item){
                    $aryNewRow_10[] = $item['name'].'<br>';
                }
                $row[10] = implode('', $aryNewRow_10);

                //Format the "Questions/Answers" column:
                //--------------------------------------
                $aryRow_11 = json_decode($row[11], true);
                $aryNewRow_11 = [];
                foreach($aryRow_11 as $item_1){
                    $aryNewRow_11[] = '<b>'.$item_1['Question-class'].'</b><br>';
                    foreach($item['questions_answers'] as $item_2){
                        $aryNewRow_11[] = '<b>Q:</b> '.$item_2['question'].'<br><b>A:</b> '.$item_2['answer'].'<br>';
                    }
                }
                $row[11] = implode('', $aryNewRow_11);
                
                $aryRows[] = $row;
            }
            //Return associative array of rows of fields:
            return json_encode($aryRows);

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}