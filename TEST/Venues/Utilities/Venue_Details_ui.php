<?php
class Venue_Details_ui{
    
    /**   Displays an editor for a Venue, consisting of a Venue Name, Venue Check,
     * and a column of rows, where read-only questions are above editable TextAreas, 
     * representing answers. Also, a "Venues_ID" is passed in to be carried by the 
     * "Save Venue Details" button. If an empty parameter is passed in, then '' is 
     * returned. Also, to the right of each answer TextArea is a table cell for a 
     * possible error-message.
     * 
     * @param integer $Venues_ID
     * @param JSON $jsonVenueDetails -- Contains above values to be displayed.
     * @return string -- The Venue name, Venue Check, and a column of 
     *      question/answer blocks.
     */
    public static function displayVenueDetails($Venues_ID, $jsonVenueDetails) {    
        if(!empty($Venues_ID) && !empty($jsonVenueDetails)){
            $aryVenueDetails = json_decode($jsonVenueDetails, true);
            $name = $aryVenueDetails['name'];
            $aryQuestionsAnswers = json_decode($aryVenueDetails['questionsAnswers'], true);
            $venueCheck = $aryVenueDetails['venueCheck'];
            $isChecked = filter_var($venueCheck, FILTER_VALIDATE_BOOLEAN);
            $isCheckedDisplay = $isChecked ? "checked=\"checked\"" : "";
            
            $results = "<h1>E D I T&nbsp;&nbsp;&nbsp;V E N U E&nbsp;&nbsp;&nbsp;D E T A I L S&nbsp;&nbsp;&nbsp;( A d m i n )
                &nbsp;&nbsp;&nbsp;<input type=\"button\" id=\"btnBackToAllVenues\" class=\"button\" value=\"Back To All Venues\"/></h1>
                <table id=\"tblVenueDetails\"><tr><td id=\"tdName\">*Venue Name:</td>
                <td><input type=\"text\" id=\"txtName\"/ value=\"$name\">
                <span id=\"nameErrMsg\"></span>
                </td></tr><tr><td id=\"tdVenueCheck\">*Venue Check:</td>
                <td><input type=\"checkbox\" id=\"chkVenueCheck\" class=\"checkbox\" value=\"1\" $isCheckedDisplay/>
                </td></tr></table><br><br>";

            foreach($aryQuestionsAnswers as $row){
                $results .= "<div class=\"question_answer_block\">".
                    "<div class=\"question_row\">".($row['question'])."</div>".
                    "<table class=\"answer_row\"><tr><td>".
                    "<textarea class=\"answer_box\">".($row['answer'])."</textarea></td>".
                    "<td class=\"tdErrorMessage\"></td></tr></table></div>";
            }
            $results .= "<div id=\"divSaveAnswers\">Do Validation?: <input type=\"checkbox\" id=\"chkDoValidation\" checked/><br>".
                    "<input type=\"button\" id=\"btnSaveVenueDetails\" class=\"button\" data-venues_id=\"$Venues_ID\" value=\"Save Venue Detais\"/>".
                    "</div>";
            
            return $results;
        }else{
            return '';
        }
    }
    
}//End class, "Venue_Details_ui".