<?php

class Screenfield_validation_ui{
    
    /**   Displays a page for the "Screenfield_validation" reference table editor.
     * 
     */
    public static function screenfieldValidationDisplay(){
       $newRule = (isset($_SESSION['V_newRule']) ? $_SESSION['V_newRule'] : '');
       $newRegex = (isset($_SESSION['V_newRegex']) ? $_SESSION['V_newRegex'] : '');
       $newErrMsg = (isset($_SESSION['V_newErrMsg']) ? $_SESSION['V_newErrMsg'] : '');
       
        echo "
        <h1>&nbsp;&nbsp;&nbsp;E D I T&nbsp;&nbsp;&nbsp;R E F E R E N C E&nbsp;&nbsp;&nbsp;T A B L E ,<br>
        &nbsp;&nbsp;&nbsp;S c r e e n f i e l d _ v a l i d a t i o n&nbsp;&nbsp;&nbsp;( A d m i n )</h1><br>
        <div id=\"divWarning\">WARNING: Changing the screenfield validation fields or deleting them may allow 
        for bad data to be entered. If this happens, review and edit your rules carefully, paying extra attention 
        to the entries for \"Rule\", \"Regex\", and making sure that the \"Error Message\" is appropriate.</div><br>
        <div id=\"divScreenfieldValidation\" class=\"ht-theme-main-dark\"></div>
        <div id=\"divButtons\">
            <table><tr><td colspan=\"2\"><input type=\"button\" id=\"btnSaveCheckedRows\" value=\"Save Checked Rows\"/>
            <input type=\"button\" id=\"btnDeleteCheckedRows\" value=\"Delete Checked Rows\"/></td></tr>
            <tr><td colspan=\"2\" class=\"tableHeading\">Add New Screen-Field Validation:</td></tr>
            <tr><td class=\"fieldHeading\">Rule:</td><td class=\"textField\"><input type=\"text\" id=\"txtNewRule\" value=\"$newRule\"/></td></tr>
            <tr><td class=\"fieldHeading\">Regex:</td><td class=\"textField\"><input type=\"text\" id=\"txtNewRegex\" value=\"$newRegex\"/></td></tr>
            <tr><td class=\"fieldHeading\">Error Message:</td><td class=\"textField\"><input type=\"text\" id=\"txtNewErrMsg\" value=\"$newErrMsg\"/></td></tr>
            <tr><td class=\"newValidationSubmit\" colspan=\"2\"><input type=\"button\" id=\"btnSaveNewScreenfieldValidation\" value=\"Save New Screen-Field Validation\"/></td></tr></table>
        </div>
        ";
    }//End function, "screenFieldValidationDisplay".
    
}//End class, "Screenfield_validation_ui".