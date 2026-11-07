<?php
 
class Venue_Questions_ui{
    
    /**   Returns a string of the Venue Questions Editor Screen.
     * 
     * @return string -- An HTML5 string of the Venue Questions Editor Screen.
     */
    public static function displayVenueQuestionsEditorScreen(){
        $returnValue = "<h1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E D I T&nbsp;&nbsp;&nbsp;V E N U E&nbsp;&nbsp;&nbsp;".
            "T E M P L A T E&nbsp;&nbsp;&nbsp;Q U E S T I O N S&nbsp;&nbsp;&nbsp;( A d m i n )</h1>".
            "<div id=\"divWarning\">NOTE: You may prefix a question with a rule to constrain values in its answer. ".
            "Use * , as the leftmost character, to indicate a required field. If you add a rule, separate ".
            "it from the question with :: . View and manage all rules from the main menu item, ".
            "\"Edit Screenfield_Validation Table\".</div>".
            "<br><div id=\"divNewQuestion\"><textarea id=\"newQuestion\"></textarea>".
            "<input id=\"btnAddNewQuestion\" type=\"button\" value=\"Add New Question\"/></div>".
            "<div id=\"venue_questions\"></div>";
        
        return $returnValue;
    }//End function, "displayVenueQuestionsEditorScreen".
    
}//End class, "Questions_ui".