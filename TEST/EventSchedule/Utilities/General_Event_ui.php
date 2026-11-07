<?php

class General_Event_ui {
    /**   Displays a screen for viewing/editing an individual event. Contains holders for
     * three Handsontables. Also, has a button to return to the Proposal Editor.
     * 
     * Note: This UI function is designed for the "Proposer" role.
     * 
     * @return string -- Described above.
     */
    public static function displayEventDetailsScreen(){
        return "<h1>E D I T&nbsp;&nbsp;&nbsp;E V E N T&nbsp;&nbsp;&nbsp;D E T A I L S&nbsp;&nbsp;&nbsp;( P u b l i c )".
            "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"button\" id=\"btnBackToProposalEditor\" value=\"Back to Proposal Editor\"/>".
            "</h1><br>".
            "<table><tr><td id=\"tdGeneralContainer\" colspan=\"2\">".
            "<table id=\"tblGeneral\"><tr><td id=\"tdTitle\">Event Title:</td>".
            "<td><span id=\"spTitle\"/></span>".
            "<span id=\"titleErrMsg\" class=\"errMsg\"></span></td></tr>".
            "<tr><td id=\"tdDuration\">*Integer:: Duration (mins):</td>".
            "<td><input type=\"text\" id=\"txtDuration\"/>".
            "<span id=\"durationErrMsg\" class=\"errMsg\"></span></td></tr>".
            "<tr><td id=\"tdMaxOccurences\">*Integer:: Max Occurences:</td>".
            "<td><input type=\"text\" id=\"txtMaxOccurences\"/>".
            "<span id=\"maxOccurencesErrMsg\" class=\"errMsg\"></span></td></tr>".
            "</table><br></td></tr>".
            "<tr><td class=\"hotHeading\" colspan=\"2\">*Days and Times Available (check all that apply): <span id=\"preferredTimesErrMsg\" class=\"errMsg\"></span></td></tr>".
            "<tr><td colspan=\"2\"><div id=\"divPreferredTimes\" class=\"ht-theme-main-dark\"></div></td></tr>".
            "<tr><td class=\"fieldHeading\"><br>New Member: <input type=\"text\" id=\"txtNewMember\"/>".
            "<br>Shareable? <input type=\"checkbox\" id=\"chkNewMemberShareable\"/>".
            "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"button\" id=\"btnAddNewMember\" value=\"Add New Member\"/></td>".    
            "<td class=\"fieldHeading\"><br>New Resource: <input type=\"text\" id=\"txtNewResource\"/>".
            "<br>Shareable? <input type=\"checkbox\" id=\"chkNewResourceShareable\"/>".
            "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"button\" id=\"btnAddNewResource\" value=\"Add New Resource\"/></td></tr>".
            "<tr><td class=\"hotHeading\">Existing Members:</td><td class=\"hotHeading\">Existing Resources:</td></tr>".
            "<tr><td><div id=\"divMembers\" class=\"ht-theme-main-dark\"></div><br></td><td><div id=\"divResources\" class=\"ht-theme-main-dark\"></div><br></td></tr>".
            "</table><input type=\"button\" id=\"btnSaveEvent\" value=\"Save Event Details\"/>";
    }//End function, "displayEventDetailsScreen".
    
    /**   Displays a screen for viewing/editing an individual event. Contains holders for
     * three Handsontables. Also, has a button to return to the Proposal Editor.
     * 
     * Note: This UI function is designed for the "Organizer" and "SuperUser" roles.
     *       Beneath the heading, Proposer identification fields are shown.
     * 
     * @param assoc. array $aryProposerInfo -- Various fields identifying the 
     *      current Proposer.
     * @return string -- Described above.
     */
    public static function displayEventDetailsScreen_Admin($aryProposerInfo, $title){
        //Proposer Info:
        $proposerName = '<span class="proposerInfo">'.$aryProposerInfo['first_name'].' '.$aryProposerInfo['last_name'].'</span>';
        $proposerRole = '<span class="proposerInfo">'.$aryProposerInfo['role'].'</span>';
        $proposerPhone = '<span class="proposerInfo">'.$aryProposerInfo['phone'].'</span>';
        $proposerEmail = '<span class="proposerInfo">'.$aryProposerInfo['email'].'</span>';

        return "<h1>E D I T&nbsp;&nbsp;&nbsp;G E N E R A L&nbsp;&nbsp;&nbsp;E V E N T".
            "<br>--$title</h1><br>".
            "<div id=\"divProposer\"><span class=\"fieldHeading\">Proposer:</span> $proposerName". 
            "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class=\"fieldHeading\">Role:</span> $proposerRole". 
            "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class=\"fieldHeading\">Phone:</span> $proposerPhone". 
            "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class=\"fieldHeading\">Email:</span> $proposerEmail".
            "</div><br>".                   
            "<table>".
            "<tr><td colspan=\"2\"><input type=\"button\" class=\"btnSaveGeneralEvent\" value=\"Save General Event\"/></td></tr>".
            "<tr><td id=\"tdGeneralContainer\" colspan=\"2\">".
            "<table id=\"tblGeneral\"><tr><td id=\"tdDuration\">*Integer:: Duration (mins):</td>".
            "<td><input type=\"text\" id=\"txtDuration\"/>".
            "<span id=\"durationErrMsg\" class=\"errMsg\"></span></td></tr>".
            "<tr><td id=\"tdMaxOccurences\">*Integer:: Max Occurences:</td>".
            "<td><input type=\"text\" id=\"txtMaxOccurences\"/>".
            "<span id=\"maxOccurencesErrMsg\" class=\"errMsg\"></span></td></tr>".
            "</table><br></td></tr>".
            "<tr><td class=\"hotHeading\" colspan=\"2\">*Days and Times Available (check all that apply): <span id=\"preferredTimesErrMsg\" class=\"errMsg\"></span></td></tr>".
            "<tr><td colspan=\"2\"><div id=\"divPreferredTimes\" class=\"ht-theme-main-dark\"></div></td></tr>".
            "<tr><td class=\"fieldHeading\"><br>New Member: <input type=\"text\" id=\"txtNewMember\"/>".
            "<br>Shareable? <input type=\"checkbox\" id=\"chkNewMemberShareable\"/>".
            "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"button\" id=\"btnAddNewMember\" value=\"Add New Member\"/></td>".    
            "<td class=\"fieldHeading\"><br>New Resource: <input type=\"text\" id=\"txtNewResource\"/>".
            "<br>Shareable? <input type=\"checkbox\" id=\"chkNewResourceShareable\"/>".
            "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"button\" id=\"btnAddNewResource\" value=\"Add New Resource\"/></td></tr>".
            "<tr><td class=\"hotHeading\">Existing Members:</td><td class=\"hotHeading\">Existing Resources:</td></tr>".
            "<tr><td><div id=\"divMembers\" class=\"ht-theme-main-dark\"></div></td><td><div id=\"divResources\" class=\"ht-theme-main-dark\"></div></td></tr>".
            "<tr><td colspan=\"2\"><br><input type=\"button\" class=\"btnSaveGeneralEvent\" value=\"Save General Event\"/></td></tr>".
            "</table>";
    }//End function, "displayEventDetailsScreen_Admin".
    
}//End class, "Event_ui".
