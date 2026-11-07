<?php
class question_classes_ui{
    /**   Populates an HTML 5 drop-down from the associative array passed in. 
     * If an empty array is passed in, then '' is returned.
     * 
     * @param Assoc Array $aryQuestion_classes -- an associative array of [ID, name] 
     *      from the "Question_classes" table.
     * @return string -- The populated drop-down.
     */
    public static function displayQuestionClassDropdown($aryQuestion_classes){
        if(!empty($aryQuestion_classes)){
            $dropdown = "<select name=\"dd_question_classes\" id=\"dd_question_classes\">";
            foreach ($aryQuestion_classes as $row):
                $dropdown .= "<option value=\"".$row['ID']."\">".htmlspecialchars($row['name'])."</option>";
            endforeach;
            $dropdown .= "</select>";

            return $dropdown;
        }else{
            return '';
        }
    }
    
    /**   Displays a page for the "Question_classes" reference table editor.
     * 
     */
    public static function questionClassesDisplay(){
       $newQuestionClass = (isset($_SESSION['QC_NewQuestionClass']) ? $_SESSION['QC_NewQuestionClass'] : '');
       
        echo "
        <h1>&nbsp;&nbsp;&nbsp;E D I T&nbsp;&nbsp;&nbsp;R E F E R E N C E&nbsp;&nbsp;&nbsp;T A B L E ,<br>
        &nbsp;&nbsp;&nbsp;Q u e s t i o n _ c l a s s e s&nbsp;&nbsp;&nbsp;( A d m i n )</h1><br>
        <div id=\"divQuestionClasses\" class=\"ht-theme-main-dark\"></div>
        <div id=\"divButtons\">
            <input type=\"button\" id=\"btnSaveCheckedRows\" value=\"Save Checked Rows\"/>
            <input type=\"button\" id=\"btnDeleteCheckedRows\" value=\"Delete Checked Rows\"/><br>
            <label class=\"fieldHeading\">Add New Question Class: </label>
            <input type=\"text\" id=\"txtNewQuestionClass\" value=\"$newQuestionClass\"/>
            <input type=\"button\" id=\"btnSaveNewQuestionClass\" value=\"Save New Question Class\"/>
        </div>
        ";
    }//End function, "questionClassesDisplay".
    
}