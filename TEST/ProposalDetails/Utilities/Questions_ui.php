<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/question_classes_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/question_classes_ui.php';
 
class Questions_ui{
    
    /**   Returns a string of the Questions Editor Screen. Requires the use of 
     * the following methods from "question_classes_db" and "question_classes_ui":
     *      "getAllQuestionClassesFromDb($db)" and 
     *      "displayQuestionClassDropdown($aryQuestion_classes)", respectively.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @return string -- An HTML5 string of the Questions Editor Screen.
     */
    public static function displayQuestionsEditorScreen($db){
        $aryQuestion_classes = question_classes_db::getAllQuestionClassesFromDb($db);
        
        $returnValue = "<h1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E D I T&nbsp;&nbsp;&nbsp;P R O P O S A L&nbsp;&nbsp;&nbsp;".
            "T E M P L A T E&nbsp;&nbsp;&nbsp;Q U E S T I O N S&nbsp;&nbsp;&nbsp;( A d m i n )</h1>".
            "<div id=\"divWarning\">NOTE: You may prefix a question with a rule to constrain values in its answer. ".
            "Use * , as the leftmost character, to indicate a required field. If you add a rule, separate ".
            "it from the question with :: . View and manage all rules from the main menu item, ".
            "\"Edit Screenfield_Validation Table\".</div>".
            "<table><tr><td>".
            "<label class=\"selection\" for=\"question_classes\">Select Main Question Class:</label><br><br>".
            "<span name=\"question_classes\" id=\"question_classes\">".
            question_classes_ui::displayQuestionClassDropdown($aryQuestion_classes).
            "</span><br><br><p id=\"selected_question_class\" class=\"selection\"></p><br></td><td id=\"tdAttach\">".
            "<div><label class=\"selection\">Prepend or Append Question Class:</label><br><br>".
            "<label id=\"lblPrepend\" for=\"radPrepend\">Prepend</label>".
            "<input type=\"radio\" id=\"radPrepend\" name=\"radAttach\" value=\"prepend\" checked>".
            "<label id=\"lblAppend\" for=\"radAppend\">Append</label>".
            "<input type=\"radio\" id=\"radAppend\" name=\"radAttach\" value=\"append\">&nbsp;".
            "<span name=\"question_classes_2\" id=\"question_classes_2\">".
            question_classes_ui::displayQuestionClassDropdown($aryQuestion_classes).
            "</span><br><br><input id=\"btnAttachQuestionClass\" type=\"button\" value=\"Attach Question Class\"/>".
            "<br><br></div></td></tr><br><br></div></td></tr>".
            "</table><br><div id=\"divNewQuestion\"><textarea id=\"newQuestion\"></textarea>".
            "<input id=\"btnAddNewQuestion\" type=\"button\" value=\"Add New Question\"/></div>".
            "<div id=\"selected_questions\"></div>";
        
        return $returnValue;
    }//End function, "displayQuestionsEditorScreen".
    
}//End class, "Questions_ui".