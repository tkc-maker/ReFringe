<?php

class Search_Proposals_ui{
    
    public static function proposalSearchDisplay(){
       $chkInclude_1 = (isset($_SESSION['SP_Include_1']) ? $_SESSION['SP_Include_1'] : true);
       $Role_1 = (isset($_SESSION['SP_Role_1']) ?  $_SESSION['SP_Role_1'] : '');
       $First_Name_1 = (isset($_SESSION['SP_First_Name_1']) ? $_SESSION['SP_First_Name_1'] : '');
       $Last_Name_1 = (isset($_SESSION['SP_Last_Name_1']) ? $_SESSION['SP_Last_Name_1'] : '');
       $Phone_1 = (isset($_SESSION['SP_Phone_1']) ? $_SESSION['SP_Phone_1'] : '');
       $Email_1 = (isset($_SESSION['SP_Email_1']) ? $_SESSION['SP_Email_1'] : '');
       $Title_1 = (isset($_SESSION['SP_Title_1']) ?  $_SESSION['SP_Title_1'] : '');
       $Question_classes_1 = (isset($_SESSION['SP_Question-classes_1']) ? $_SESSION['SP_Question-classes_1'] : '');
       $questions_answers_1 = (isset($_SESSION['SP_Questions-and-Answers_1']) ? $_SESSION['SP_Questions-and-Answers_1'] : '');

       $chkInclude_2 = (isset($_SESSION['SP_Include_2']) ? $_SESSION['SP_Include_2'] : false);
       $Role_2 = (isset($_SESSION['SP_Role_2']) ?  $_SESSION['SP_Role_2'] : '');
       $First_Name_2 = (isset($_SESSION['SP_First_Name_2']) ? $_SESSION['SP_First_Name_2'] : '');
       $Last_Name_2 = (isset($_SESSION['SP_Last_Name_2']) ? $_SESSION['SP_Last_Name_2'] : '');
       $Phone_2 = (isset($_SESSION['SP_Phone_2']) ? $_SESSION['SP_Phone_2'] : '');
       $Email_2 = (isset($_SESSION['SP_Email_2']) ? $_SESSION['SP_Email_2'] : '');
       $Title_2 = (isset($_SESSION['SP_Title_2']) ?  $_SESSION['SP_Title_2'] : '');
       $Question_classes_2 = (isset($_SESSION['SP_Question-classes_2']) ? $_SESSION['SP_Question-classes_2'] : '');
       $questions_answers_2 = (isset($_SESSION['SP_Questions-and-Answers_2']) ? $_SESSION['SP_Questions-and-Answers_2'] : '');

       $chkInclude_3 = (isset($_SESSION['SP_Include_3']) ? $_SESSION['SP_Include_3'] : false);
       $Role_3 = (isset($_SESSION['SP_Role_3']) ?  $_SESSION['SP_Role_3'] : '');
       $First_Name_3 = (isset($_SESSION['SP_First_Name_3']) ? $_SESSION['SP_First_Name_3'] : '');
       $Last_Name_3 = (isset($_SESSION['SP_Last_Name_3']) ? $_SESSION['SP_Last_Name_3'] : '');
       $Phone_3 = (isset($_SESSION['SP_Phone_3']) ? $_SESSION['SP_Phone_3'] : '');
       $Email_3 = (isset($_SESSION['SP_Email_3']) ? $_SESSION['SP_Email_3'] : '');
       $Title_3 = (isset($_SESSION['SP_Title_3']) ?  $_SESSION['SP_Title_3'] : '');
       $Question_classes_3 = (isset($_SESSION['SP_Question-classes_3']) ? $_SESSION['SP_Question-classes_3'] : '');
       $questions_answers_3 = (isset($_SESSION['SP_Questions-and-Answers_3']) ? $_SESSION['SP_Questions-and-Answers_3'] : '');

       $chkInclude_4 = (isset($_SESSION['SP_Include_4']) ? $_SESSION['SP_Include_4'] : false);
       $Role_4 = (isset($_SESSION['SP_Role_4']) ?  $_SESSION['SP_Role_4'] : '');
       $First_Name_4 = (isset($_SESSION['SP_First_Name_4']) ? $_SESSION['SP_First_Name_4'] : '');
       $Last_Name_4 = (isset($_SESSION['SP_Last_Name_4']) ? $_SESSION['SP_Last_Name_4'] : '');
       $Phone_4 = (isset($_SESSION['SP_Phone_4']) ? $_SESSION['SP_Phone_4'] : '');
       $Email_4 = (isset($_SESSION['SP_Email_4']) ? $_SESSION['SP_Email_4'] : '');
       $Title_4 = (isset($_SESSION['SP_Title_4']) ?  $_SESSION['SP_Title_4'] : '');
       $Question_classes_4 = (isset($_SESSION['SP_Question-classes_4']) ? $_SESSION['SP_Question-classes_4'] : '');
       $questions_answers_4 = (isset($_SESSION['SP_Questions-and-Answers_4']) ? $_SESSION['SP_Questions-and-Answers_4'] : '');
       
       $RefineSearch = (isset($_SESSION['SP_RefineSearch']) ? $_SESSION['SP_RefineSearch'] : '');
        
        echo "
        <table id=\"tblCriteria\">
            <tr>
                <th>&nbsp;</th>
                <th>Include?</th>
                <th>Role</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Title</th>
                <th>Question Classes</th>
                <th>Questions and Answers</th>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type=\"checkbox\" id=\"chkInclude_1\" class=\"searchCriteria\" ".($chkInclude_1? 'checked': '')."/></td>
                <td><input type=\"text\" id=\"txtRole_1\" class=\"searchCriteria\" value=\"$Role_1\"/></td>
                <td><input type=\"text\" id=\"txtFirstName_1\" class=\"searchCriteria\" value=\"$First_Name_1\"/></td>
                <td><input type=\"text\" id=\"txtLastName_1\" class=\"searchCriteria\" value=\"$Last_Name_1\"/></td>
                <td><input type=\"text\" id=\"txtPhone_1\" class=\"searchCriteria\" value=\"$Phone_1\"/></td>
                <td><input type=\"text\" id=\"txtEmail_1\" class=\"searchCriteria\" value=\"$Email_1\"/></td>
                <td><input type=\"text\" id=\"txtTitle_1\" class=\"searchCriteria\" value=\"$Title_1\"/></td>
                <td><input type=\"text\" id=\"txtQuestionClasses_1\" class=\"searchCriteria\" value=\"$Question_classes_1\"/></td>
                <td><input type=\"text\" id=\"txtQuestionsAnswers_1\" class=\"searchCriteria\" value=\"$questions_answers_1\"/></td>                
            </tr>
            <tr>
                <td>Or</td>
                <td><input type=\"checkbox\" id=\"chkInclude_2\" class=\"searchCriteria\" ".($chkInclude_2? 'checked': '')."/></td>
                <td><input type=\"text\" id=\"txtRole_2\" class=\"searchCriteria\" value=\"$Role_2\"/></td>
                <td><input type=\"text\" id=\"txtFirstName_2\" class=\"searchCriteria\" value=\"$First_Name_2\"/></td>
                <td><input type=\"text\" id=\"txtLastName_2\" class=\"searchCriteria\" value=\"$Last_Name_2\"/></td>
                <td><input type=\"text\" id=\"txtPhone_2\" class=\"searchCriteria\" value=\"$Phone_2\"/></td>
                <td><input type=\"text\" id=\"txtEmail_2\" class=\"searchCriteria\" value=\"$Email_2\"/></td>
                <td><input type=\"text\" id=\"txtTitle_2\" class=\"searchCriteria\" value=\"$Title_2\"/></td>
                <td><input type=\"text\" id=\"txtQuestionClasses_2\" class=\"searchCriteria\" value=\"$Question_classes_2\"/></td>
                <td><input type=\"text\" id=\"txtQuestionsAnswers_2\" class=\"searchCriteria\" value=\"$questions_answers_2\"/></td>                
            </tr>
            <tr>
                <td>Or</td>
                <td><input type=\"checkbox\" id=\"chkInclude_3\" class=\"searchCriteria\" ".($chkInclude_3? 'checked': '')."/></td>
                <td><input type=\"text\" id=\"txtRole_3\" class=\"searchCriteria\" value=\"$Role_3\"/></td>
                <td><input type=\"text\" id=\"txtFirstName_3\" class=\"searchCriteria\" value=\"$First_Name_3\"/></td>
                <td><input type=\"text\" id=\"txtLastName_3\" class=\"searchCriteria\" value=\"$Last_Name_3\"/></td>
                <td><input type=\"text\" id=\"txtPhone_3\" class=\"searchCriteria\" value=\"$Phone_3\"/></td>
                <td><input type=\"text\" id=\"txtEmail_3\" class=\"searchCriteria\" value=\"$Email_3\"/></td>
                <td><input type=\"text\" id=\"txtTitle_3\" class=\"searchCriteria\" value=\"$Title_3\"/></td>
                <td><input type=\"text\" id=\"txtQuestionClasses_3\" class=\"searchCriteria\" value=\"$Question_classes_3\"/></td>
                <td><input type=\"text\" id=\"txtQuestionsAnswers_3\" class=\"searchCriteria\" value=\"$questions_answers_3\"/></td>                
            </tr>
            <tr>
                <td>Or</td>
                <td><input type=\"checkbox\" id=\"chkInclude_4\" class=\"searchCriteria\" ".($chkInclude_4? 'checked': '')."/></td>
                <td><input type=\"text\" id=\"txtRole_4\" class=\"searchCriteria\" value=\"$Role_4\"/></td>
                <td><input type=\"text\" id=\"txtFirstName_4\" class=\"searchCriteria\" value=\"$First_Name_4\"/></td>
                <td><input type=\"text\" id=\"txtLastName_4\" class=\"searchCriteria\" value=\"$Last_Name_4\"/></td>
                <td><input type=\"text\" id=\"txtPhone_4\" class=\"searchCriteria\" value=\"$Phone_4\"/></td>
                <td><input type=\"text\" id=\"txtEmail_4\" class=\"searchCriteria\" value=\"$Email_4\"/></td>
                <td><input type=\"text\" id=\"txtTitle_4\" class=\"searchCriteria\" value=\"$Title_4\"/></td>
                <td><input type=\"text\" id=\"txtQuestionClasses_4\" class=\"searchCriteria\" value=\"$Question_classes_4\"/></td>
                <td><input type=\"text\" id=\"txtQuestionsAnswers_4\" class=\"searchCriteria\" value=\"$questions_answers_4\"/></td>                
            </tr>
            <tr>
                <td colspan=\"10\">
                    <input type=\"button\" id=\"btnSearchProposals\" value=\"Search Proposals\"/>
                    <input type=\"button\" id=\"btnShowAllProposals\" value=\"Show All Proposals\"/>
                    <input type=\"button\" id=\"btnClearSearchCriteria\" value=\"Clear Search Criteria\"/>
                </td>
            </tr>
        </table>
        <div id=\"divSearchResults\" class=\"ht-theme-main-dark\"></div>
        <div id=\"buttons\">
            <input type=\"button\" id=\"btnClearVisibleHighlights\" value=\"Clear Visible Highlights\"/>
            <input type=\"button\" id=\"btnClearRefineCriteria\" value=\"Clear Refine Criteria\"/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label class=\"fieldHeading\">Refine Question/Answer Search: </label>
            <input type=\"text\" id=\"txtRefineSearch\" value=\"$RefineSearch\"/>
            <input type=\"button\" id=\"btnRefineSearch\" value=\"Refine and Highlight\"/>
        </div>
        <div id=\"divExportToCsv\">
            <input type=\"button\" id=\"btnClearRowChecks\" value=\"Clear All Row Checks\"/>
            <input type=\"button\" id=\"btnExportToCsv\" value=\"Export Checked Rows to CSV File delimited by | and \\n\"/>
            <input type=\"button\" id=\"btnEditCheckedProposal\" value=\"Edit First Checked Proposal\"/>
        </div>
        ";
    }//End function, "proposalSearchDisplay".
    
    /**   Displays a list of fields for the "Show All Proposals" page. Each field is preceded
     * by a checkbox, to indicate whether that field should be displayed. After each field
     * are buttons for ordering the entire list of Proposals by that field ("Asc", "Desc", and
     * "Orig").
     */
    public static function All_Proposals_Fields_Display(){
       $chkRole = (isset($_SESSION['AP_chkRole']) ? $_SESSION['AP_chkRole'] : false);
       $chkFirst_Name = (isset($_SESSION['AP_chkFirst_Name']) ? $_SESSION['AP_chkFirst_Name'] : false);
       $chkLast_Name = (isset($_SESSION['AP_chkLast_Name']) ? $_SESSION['AP_chkLast_Name'] : false);
       $chkPhone = (isset($_SESSION['AP_chkPhone']) ? $_SESSION['AP_chkPhone'] : false);
       $chkEmail = (isset($_SESSION['AP_chkEmail']) ? $_SESSION['AP_chkEmail'] : false);
       $chkTitle = (isset($_SESSION['AP_chkTitle']) ? $_SESSION['AP_chkTitle'] : true);
       $chkCDT = (isset($_SESSION['AP_chkCDT']) ? $_SESSION['AP_chkCDT'] : false);
       $chkUDT = (isset($_SESSION['AP_chkUDT']) ? $_SESSION['AP_chkUDT'] : false);
       
       echo "
        <h1>S H O W&nbsp;&nbsp;&nbsp;A L L&nbsp;&nbsp;&nbsp;P R O P O S A L S&nbsp;&nbsp;&nbsp;O N&nbsp;&nbsp;&nbsp;R E F R I N G E . O R G&nbsp;&nbsp;&nbsp;( A d m i n )</h1><br>
        <table id=\"tblFields\">
            <tr>
                <th>Show Field</th>
                <th>Sort Order</th>
            </tr>
            <tr class=\"showField_Title\">
                <td><input type=\"checkbox\" id=\"chkTitle\" data-fname=\"Title\" disabled ".($chkTitle? 'checked': '')."/> Title</td>
                <td><input type=\"button\" class=\"button btnAsc\" value=\"Asc\"/>
                    <input type=\"button\" class=\"button btnDesc\" value=\"Desc\"/>
                    <input type=\"button\" class=\"button btnOrig\" value=\"Orig\"/>
                </td>
            </tr>
            <tr class=\"showField_Role\">
                <td><input type=\"checkbox\" id=\"chkRole\" data-fname=\"Role\" ".($chkRole? 'checked': '')."/> Role</td>
                <td><input type=\"button\" class=\"button btnAsc\" value=\"Asc\"/>
                    <input type=\"button\" class=\"button btnDesc\" value=\"Desc\"/>
                    <input type=\"button\" class=\"button btnOrig\" value=\"Orig\"/>
                </td>
            </tr>
            <tr class=\"showField_First_Name\">
                <td><input type=\"checkbox\" id=\"chkFirst_Name\" data-fname=\"First-Name\" ".($chkFirst_Name? 'checked': '')."/> First Name</td>
                <td><input type=\"button\" class=\"button btnAsc\" value=\"Asc\"/>
                    <input type=\"button\" class=\"button btnDesc\" value=\"Desc\"/>
                    <input type=\"button\" class=\"button btnOrig\" value=\"Orig\"/>
                </td>
            </tr>
            <tr class=\"showField_Last_Name\">
                <td><input type=\"checkbox\" id=\"chkLast_Name\" data-fname=\"Last-Name\" ".($chkLast_Name? 'checked': '')."/> Last Name</td>
                <td><input type=\"button\" class=\"button btnAsc\" value=\"Asc\"/>
                    <input type=\"button\" class=\"button btnDesc\" value=\"Desc\"/>
                    <input type=\"button\" class=\"button btnOrig\" value=\"Orig\"/>
                </td>
            </tr>
            <tr class=\"showField_Phone\">
                <td><input type=\"checkbox\" id=\"chkPhone\" data-fname=\"Phone\" ".($chkPhone? 'checked': '')."/> Phone</td>
                <td><input type=\"button\" class=\"button btnAsc\" value=\"Asc\"/>
                    <input type=\"button\" class=\"button btnDesc\" value=\"Desc\"/>
                    <input type=\"button\" class=\"button btnOrig\" value=\"Orig\"/>
                </td>
            </tr>
            <tr class=\"showField_Email\">
                <td><input type=\"checkbox\" id=\"chkEmail\" data-fname=\"Email\" ".($chkEmail? 'checked': '')."/> Email</td>
                <td><input type=\"button\" class=\"button btnAsc\" value=\"Asc\"/>
                    <input type=\"button\" class=\"button btnDesc\" value=\"Desc\"/>
                    <input type=\"button\" class=\"button btnOrig\" value=\"Orig\"/>
                </td>
            </tr>
            <tr class=\"showField_CDT\">
                <td><input type=\"checkbox\" id=\"chkCDT\" data-fname=\"created_date_time\" ".($chkCDT? 'checked': '')."/> CDT</td>
                <td><input type=\"button\" class=\"button btnAsc\" value=\"Asc\"/>
                    <input type=\"button\" class=\"button btnDesc\" value=\"Desc\"/>
                    <input type=\"button\" class=\"button btnOrig\" value=\"Orig\"/>
                </td>
            </tr>
            <tr class=\"showField_UDT\">
                <td><input type=\"checkbox\" id=\"chkUDT\" data-fname=\"updated_date_time\" ".($chkUDT? 'checked': '')."/> UDT</td>
                <td><input type=\"button\" class=\"button btnAsc\" value=\"Asc\"/>
                    <input type=\"button\" class=\"button btnDesc\" value=\"Desc\"/>
                    <input type=\"button\" class=\"button btnOrig\" value=\"Orig\"/>
                </td>
            </tr>
        </table>        
       ";       
    }//END function, "All_Proposals_Fields_Display()".
    
}//End class, "Search_Proposals_ui".