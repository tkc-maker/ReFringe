<?php
class Proposal_Details_ui{
    
    public static function displayProposalDetailsScreen(){
        return "<h1>E D I T&nbsp;&nbsp;&nbsp;P R O P O S A L&nbsp;&nbsp;&nbsp;D E T A I L S&nbsp;&nbsp;&nbsp;( P u b l i c )</h1><br>".
            "<input type=\"button\" id=\"btnHelp\" value=\"Help\"/>".
            "<div id=\"divTitleHeading\">*Proposal Title: <span id=\"titleErrMsg\"></span></div>".
            "<div id=\"divTitleText\"><input type=\"text\" id=\"txtTitle\"/></div><br>".
            "<details id=\"detQuestionClasses\">".
            "<summary><span id=\"divTableHeading\">Click to toggle Additional Question Classes:</span></summary>".
            "<div id=\"divQuestionClasses\"></div>".
            "<div class=\"buttonRow\"><input type=\"button\" id=\"btnShowQandA\" value=\"Show Selected Questions/Answers\"/></div>".
            "</details>";
        }
    
    public static function displayProposalDetailsScreen_Admin(){
        //Proposer Info:
        $proposerName = '<span class="proposerInfo">'.$_SESSION['PD_First_Name'].' '.$_SESSION['PD_Last_Name'].'</span>';
        $proposerRole = '<span class="proposerInfo">'.$_SESSION['PD_Role'].'</span>';
        $proposerPhone = '<span class="proposerInfo">'.$_SESSION['PD_Phone'].'</span>';
        $proposerEmail = '<span class="proposerInfo">'.$_SESSION['PD_Email'].'</span>';
        
        return "<h1>E D I T&nbsp;&nbsp;&nbsp;P R O P O S A L&nbsp;&nbsp;&nbsp;D E T A I L S&nbsp;&nbsp;&nbsp;( A d m i n )</h1>".
            "<div id=\"divBackToSearch\"><input type=\"button\" id=\"btnBackToSearchProposals\" value=\"Back to Search Proposals\"/>".
            "<input type=\"button\" id=\"btnBackToShowAllProposals\" value=\"Back to Show All Proposals\"/>".
            "<input type=\"button\" id=\"btnHelp\" value=\"Help\"/></div>".
            "<div id=\"divProposer\"><span class=\"fieldHeading\">Proposer:</span> $proposerName". 
            "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class=\"fieldHeading\">Role:</span> $proposerRole". 
            "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class=\"fieldHeading\">Phone:</span> $proposerPhone". 
            "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class=\"fieldHeading\">Email:</span> $proposerEmail".
            "</div><br>".            
            "<div id=\"divTitleHeading\">*Proposal Title: <span id=\"titleErrMsg\"></span></div>".
            "<div id=\"divTitleText\"><input type=\"text\" id=\"txtTitle\"/></div><br>".
            "<div id=\"divTableHeading\">*Question Classes:</div>".
            "<div id=\"divQuestionClasses\"></div>".
            "<div class=\"buttonRow\"><input type=\"button\" id=\"btnShowQandA\" value=\"Show Selected Questions/Answers\"/>".
            "<input type=\"button\" id=\"btnScheduleEvent\" value=\"Schedule Event\"><span id=\"scheduledMsg\"></span></div>";
    }
    
    /**   Displays editable information from the "Proposal_details" table for the current
     * user logged on. Designed for the public interface.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @return string -- The HTML necessary to display the form.
     */
    public static function OLD_displayProposalDetails($db){
        $allQuestionClassesDropdown = self::displayQuestionClassDropdown($db);
        $allGrenresDropdown = self::displayGenreDropdown($db);
        $PD_record = Proposal_Details_db::getProposalDetailsFromDb($_SESSION['PD_Proposal_details_ID'], $_SESSION['PD_Users_ID'], $db);

        if(empty($PD_record)){
            return;
            
        }else{
            $PD_object = json_decode($PD_record, true);
        
            $_SESSION['PD_Proposal_details_ID'] = $PD_object['ID'];
            $_SESSION['PD_title'] = $PD_object['title'];
            $title = $_SESSION['PD_title'];

            $_SESSION['PD_selectedQuestionClasses'] = json_decode($PD_object['question_classes'], true);
            $arySelectedQuestionClasses = $_SESSION['PD_selectedQuestionClasses'];
            $selectedQuestionClassDropdown = self::displaySelectedQuestionClassDropdown($arySelectedQuestionClasses);
 
            $_SESSION['PD_selectedGenres'] = json_decode($PD_object['genres'], true);
            $arySelectedGenres = $_SESSION['PD_selectedGenres'];
            $selectedGenreDropdown = self::displaySelectedGenreDropdown($arySelectedGenres);
        }
        return "
            <h1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E D I T&nbsp;&nbsp;&nbsp;P R O P O S A L&nbsp;&nbsp;&nbsp;D E T A I L S&nbsp;&nbsp;&nbsp;( P u b l i c )</h1><br><br>
            <table><tr><td>
            <div id=\"divAllQuestionClassesDropdown\">$allQuestionClassesDropdown</div>
            </td><td>
            <div id=\"divAllGenresDropdown\">$allGrenresDropdown</div>
            </td></tr><tr><td colspan=\"2\"><br><br>
            <table id=\"tblTitle\"><tr><td id=\"tdTitle\"> *Proposal Title:
            </td><td><input type=\"text\" id=\"txtTitle\" value=\"$title\"/>
            </td><td class=\"tdErrorMessage\"></td></tr></table>
            </td></tr><tr><td>
            <div id=\"divSelectedQuestionClassesDropdown\">$selectedQuestionClassDropdown</div>
            </td><td>
            <div id=\"divSelectedGenresDropdown\">$selectedGenreDropdown</div>
            </td></tr><tr><td colspan=\"2\">".(!empty($_SESSION['PD_selectedQuestionClasses'])?
            "<input type=\"button\" id=\"btnShowSelectedQuestions\" value=\"Show Selected Questions\"/>": "").
            "</td></tr></table>
        ";
    }
    
    /**   Displays editable information from the "Proposal_details" table for the 
     * user in SESSION['PD_Users_ID']. Designed for the admin interface.
     * 
     * @param PDO $db -- A connection to the PostgreSQL database.
     * @return string -- The HTML necessary to display the form.
     */
    public static function OLD_displayProposalDetails_Admin($db){
        $allQuestionClassesDropdown = self::displayQuestionClassDropdown($db);
        $allGrenresDropdown = self::displayGenreDropdown($db);
        $PD_record = Proposal_Details_db::getProposalDetailsFromDb($_SESSION['PD_Proposal_details_ID'], $_SESSION['PD_Users_ID'], $db);

        if(empty($PD_record)){
            return;
            
        }else{
            $PD_object = json_decode($PD_record, true);
        
            $_SESSION['PD_Proposal_details_ID'] = $PD_object['ID'];
            $_SESSION['PD_title'] = $PD_object['title'];
            $title = $_SESSION['PD_title'];

            $_SESSION['PD_selectedQuestionClasses'] = json_decode($PD_object['question_classes'], true);
            $arySelectedQuestionClasses = $_SESSION['PD_selectedQuestionClasses'];
            $selectedQuestionClassDropdown = self::displaySelectedQuestionClassDropdown($arySelectedQuestionClasses);
 
            $_SESSION['PD_selectedGenres'] = json_decode($PD_object['genres'], true);
            $arySelectedGenres = $_SESSION['PD_selectedGenres'];
            $selectedGenreDropdown = self::displaySelectedGenreDropdown($arySelectedGenres);
            
            //Proposer Info:
            $proposerName = '<span class="proposerInfo">'.$_SESSION['PD_First_Name'].' '.$_SESSION['PD_Last_Name'].'</span>';
            $proposerRole = '<span class="proposerInfo">'.$_SESSION['PD_Role'].'</span>';
            $proposerPhone = '<span class="proposerInfo">'.$_SESSION['PD_Phone'].'</span>';
            $proposerEmail = '<span class="proposerInfo">'.$_SESSION['PD_Email'].'</span>';
        }
        return "
            <h1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E D I T&nbsp;&nbsp;&nbsp;P R O P O S A L&nbsp;&nbsp;&nbsp;D E T A I L S&nbsp;&nbsp;&nbsp;( A d m i n )
            &nbsp;&nbsp;&nbsp;&nbsp;<input type=\"button\" id=\"btnBackToSearchProposals\" value=\"Back to Search Proposals\"/>
            </h1><br>
            <div id=\"divProposer\"><span class=\"fieldHeading\">Proposer:</span> $proposerName 
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class=\"fieldHeading\">Role:</span> $proposerRole 
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class=\"fieldHeading\">Phone:</span> $proposerPhone 
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class=\"fieldHeading\">Email:</span> $proposerEmail</div>
            <br>            
            <table><tr><td>
            <div id=\"divAllQuestionClassesDropdown\">$allQuestionClassesDropdown</div>
            </td><td>
            <div id=\"divAllGenresDropdown\">$allGrenresDropdown</div>
            </td></tr><tr><td colspan=\"2\"><br><br>
            <table id=\"tblTitle\"><tr><td id=\"tdTitle\"> *Proposal Title:
            </td><td><input type=\"text\" id=\"txtTitle\" value=\"$title\"/>
            </td><td class=\"tdErrorMessage\"></td></tr></table>
            </td></tr><tr><td>
            <div id=\"divSelectedQuestionClassesDropdown\">$selectedQuestionClassDropdown</div>
            </td><td>
            <div id=\"divSelectedGenresDropdown\">$selectedGenreDropdown</div>
            </td></tr><tr><td colspan=\"2\">".(!empty($_SESSION['PD_selectedQuestionClasses'])?
            "<input type=\"button\" id=\"btnShowSelectedQuestions\" value=\"Show Selected Questions\"/>": "").
            "</td></tr></table>
        ";
    }
    
    public static function OLD_displayQuestionClassDropdown($db){
        $rows = question_classes_db::getAllQuestionClassesFromDb($db);
        if(!empty($rows)){
            general::sortAscendingBy_name($rows);
            $dropdown = question_classes_ui::displayQuestionClassDropdown($rows);
            $dropdownWithSelectButton = "<table><tr><td class=\"fieldHeading\">All Question Classes:</td</tr>
                <tr><td>$dropdown</td>
                <td><input type=\"button\" id=\"btnSelectQuestionClass\" value=\"Select Question Class\"/></td>
                </tr></table>";
            return $dropdownWithSelectButton;
        }
    }
    
    public static function OLD_displaySelectedQuestionClassDropdown($arySelectedQuestionClasses){
        if(!empty($arySelectedQuestionClasses)){
            general::sortAscendingBy_name($arySelectedQuestionClasses);
            $dropdown = question_classes_ui::displayQuestionClassDropdown($arySelectedQuestionClasses);
            $dropdownWithClearButton = "<table><tr><td class=\"fieldHeading\">Selected Question Classes:</td</tr>
                <tr><td>$dropdown</td>
                <td><input type=\"button\" id=\"btnClearSelectedQuestionClasses\" value=\"Clear\"/></td>
                </tr></table>";
            return $dropdownWithClearButton;
        }else{
            return '';
        }
    }

    public static function OLD_displayGenreDropdown($db){
        $rows = genres_db::getAllGenresFromDb($db);
        if(!empty($rows)){
            general::sortAscendingBy_name($rows);
            $dropdown = genres_ui::displayGenreDropdown($rows);
            $dropdownWithSelectButton = "<table><tr><td class=\"fieldHeading\">All Genres:</td</tr>
                <tr><td>$dropdown</td>
                <td><input type=\"button\" id=\"btnSelectGenre\" value=\"Select Genre\"/></td>
                </tr></table>";
            return $dropdownWithSelectButton;
        }
    }
    
    public static function OLD_displaySelectedGenreDropdown($arySelectedGenres){
        if(!empty($arySelectedGenres)){
            general::sortAscendingBy_name($arySelectedGenres);
            $dropdown = genres_ui::displayGenreDropdown($arySelectedGenres);
            $dropdownWithClearButton = "<table><tr><td class=\"fieldHeading\">Selected Genres:</td</tr>
                <tr><td>$dropdown</td>
                <td><input type=\"button\" id=\"btnClearSelectedGenres\" value=\"Clear\"/></td>
                </tr></table>";
            return $dropdownWithClearButton;
        }else{
            return '';
        }

    }
}
