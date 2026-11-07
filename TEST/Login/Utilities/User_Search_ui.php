<?php
class User_Search_ui{
    /**   Displays the "User Search form". A database connection is necessary
     * to populate the Roles drop-downs.
     * 
     * @param PDO $db -- A database connection.
     */
    public static function userSearchDisplay($db){
        $chkInclude_1 = (isset($_SESSION['SU_Include_1']) ? $_SESSION['SU_Include_1'] : true);
        $selRoles_ID_1 = (isset($_SESSION['SU_Roles_ID_1']) ? $_SESSION['SU_Roles_ID_1'] : '');
        $firstName_1 = (isset($_SESSION['SU_first_name_1']) ? $_SESSION['SU_first_name_1'] : '');
        $lastName_1 = (isset($_SESSION['SU_last_name_1']) ? $_SESSION['SU_last_name_1'] : '');
        $street_1 = (isset($_SESSION['SU_street_1']) ? $_SESSION['SU_street_1'] : '');
        $city_1 = (isset($_SESSION['SU_city_1']) ? $_SESSION['SU_city_1'] : '');
        $state_1 = (isset($_SESSION['SU_state_1']) ? $_SESSION['SU_state_1'] : '');
        $zip_1 = (isset($_SESSION['SU_zip_1']) ? $_SESSION['SU_zip_1'] : '');
        $phone_1 = (isset($_SESSION['SU_phone_1']) ? $_SESSION['SU_phone_1'] : '');
        $email_1 = (isset($_SESSION['SU_email_1']) ? $_SESSION['SU_email_1'] : '');
        $username_1 = (isset($_SESSION['SU_username_1']) ? $_SESSION['SU_username_1'] : '');
        $notes_1 = (isset($_SESSION['SU_notes_1']) ? $_SESSION['SU_notes_1'] : '');

        $chkInclude_2 = (isset($_SESSION['SU_Include_2']) ? $_SESSION['SU_Include_2'] : false);
        $selRoles_ID_2 = (isset($_SESSION['SU_Roles_ID_2']) ? $_SESSION['SU_Roles_ID_2'] : '');
        $firstName_2 = (isset($_SESSION['SU_first_name_2']) ? $_SESSION['SU_first_name_2'] : '');
        $lastName_2 = (isset($_SESSION['SU_last_name_2']) ? $_SESSION['SU_last_name_2'] : '');
        $street_2 = (isset($_SESSION['SU_street_2']) ? $_SESSION['SU_street_2'] : '');
        $city_2 = (isset($_SESSION['SU_city_2']) ? $_SESSION['SU_city_2'] : '');
        $state_2 = (isset($_SESSION['SU_state_2']) ? $_SESSION['SU_state_2'] : '');
        $zip_2 = (isset($_SESSION['SU_zip_2']) ? $_SESSION['SU_zip_2'] : '');
        $phone_2 = (isset($_SESSION['SU_phone_2']) ? $_SESSION['SU_phone_2'] : '');
        $email_2 = (isset($_SESSION['SU_email_2']) ? $_SESSION['SU_email_2'] : '');
        $username_2 = (isset($_SESSION['SU_username_2']) ? $_SESSION['SU_username_2'] : '');
        $notes_2 = (isset($_SESSION['SU_notes_2']) ? $_SESSION['SU_notes_2'] : '');

        $chkInclude_3 = (isset($_SESSION['SU_Include_3']) ? $_SESSION['SU_Include_3'] : false);
        $selRoles_ID_3 = (isset($_SESSION['SU_Roles_ID_3']) ? $_SESSION['SU_Roles_ID_3'] : '');
        $firstName_3 = (isset($_SESSION['SU_first_name_3']) ? $_SESSION['SU_first_name_3'] : '');
        $lastName_3 = (isset($_SESSION['SU_last_name_3']) ? $_SESSION['SU_last_name_3'] : '');
        $street_3 = (isset($_SESSION['SU_street_3']) ? $_SESSION['SU_street_3'] : '');
        $city_3 = (isset($_SESSION['SU_city_3']) ? $_SESSION['SU_city_3'] : '');
        $state_3 = (isset($_SESSION['SU_state_3']) ? $_SESSION['SU_state_3'] : '');
        $zip_3 = (isset($_SESSION['SU_zip_3']) ? $_SESSION['SU_zip_3'] : '');
        $phone_3 = (isset($_SESSION['SU_phone_3']) ? $_SESSION['SU_phone_3'] : '');
        $email_3 = (isset($_SESSION['SU_email_3']) ? $_SESSION['SU_email_3'] : '');
        $username_3 = (isset($_SESSION['SU_username_3']) ? $_SESSION['SU_username_3'] : '');
        $notes_3 = (isset($_SESSION['SU_notes_3']) ? $_SESSION['SU_notes_3'] : '');

        $chkInclude_4 = (isset($_SESSION['SU_Include_4']) ? $_SESSION['SU_Include_4'] : false);
        $selRoles_ID_4 = (isset($_SESSION['SU_Roles_ID_4']) ? $_SESSION['SU_Roles_ID_4'] : '');
        $firstName_4 = (isset($_SESSION['SU_first_name_4']) ? $_SESSION['SU_first_name_4'] : '');
        $lastName_4 = (isset($_SESSION['SU_last_name_4']) ? $_SESSION['SU_last_name_4'] : '');
        $street_4 = (isset($_SESSION['SU_street_4']) ? $_SESSION['SU_street_4'] : '');
        $city_4 = (isset($_SESSION['SU_city_4']) ? $_SESSION['SU_city_4'] : '');
        $state_4 = (isset($_SESSION['SU_state_4']) ? $_SESSION['SU_state_4'] : '');
        $zip_4 = (isset($_SESSION['SU_zip_4']) ? $_SESSION['SU_zip_4'] : '');
        $phone_4 = (isset($_SESSION['SU_phone_4']) ? $_SESSION['SU_phone_4'] : '');
        $email_4 = (isset($_SESSION['SU_email_4']) ? $_SESSION['SU_email_4'] : '');
        $username_4 = (isset($_SESSION['SU_username_4']) ? $_SESSION['SU_username_4'] : '');
        $notes_4 = (isset($_SESSION['SU_notes_4']) ? $_SESSION['SU_notes_4'] : '');

        $db = openDatabaseConnection();
        $dropdown_1 = Login_db::populateSelRoles($selRoles_ID_1, $db);
        $dropdown_2 = Login_db::populateSelRoles($selRoles_ID_2, $db);
        $dropdown_3 = Login_db::populateSelRoles($selRoles_ID_3, $db);
        $dropdown_4 = Login_db::populateSelRoles($selRoles_ID_4, $db);
        closeDatabaseConnection($db);

        echo "<div id=\"divSearchUsers\">
            <table id=\"tblOuter\"><tr><td colspan=\"4\" id=\"tdHeading\">
            <h1>S E A R C H&nbsp;&nbsp;&nbsp;U S E R S&nbsp;&nbsp;&nbsp;O N&nbsp;&nbsp;&nbsp;R E F R I N G E . O R G</h1><br>
            </td></tr><tr><td>
            <table id=\"tblSearchUsers\">
                <tr><td class=\"label\">Include?</td>
                <td class=\"checkCol\"><input type=\"checkbox\" id=\"chkInclude_1\" ".($chkInclude_1? 'checked': '')."></input></td>
                <td class=\"checkCol\"><input type=\"checkbox\" id=\"chkInclude_2\" ".($chkInclude_2? 'checked': '')."></input></td>
                <td class=\"checkCol\"><input type=\"checkbox\" id=\"chkInclude_3\" ".($chkInclude_3? 'checked': '')."></input></td>
                <td class=\"checkCol\"><input type=\"checkbox\" id=\"chkInclude_4\" ".($chkInclude_4? 'checked': '')."></input></td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class=\"tdOR\">OR</td>
                <td class=\"tdOR\">OR</td>
                <td class=\"tdOR\">OR</td>
                </tr>
                <tr><td class=\"label\">Role:</td>
                <td id=\"tdSelRoles_1\">$dropdown_1</td>
                <td id=\"tdSelRoles_2\">$dropdown_2</td>
                <td id=\"tdSelRoles_3\">$dropdown_3</td>
                <td id=\"tdSelRoles_4\">$dropdown_4</td>
                <tr><td class=\"label\">First Name:</td>
                <td><input type=\"text\" id=\"inFirstName_1\" class=\"inputBox\" value=\"$firstName_1\"></input></td>
                <td><input type=\"text\" id=\"inFirstName_2\" class=\"inputBox\" value=\"$firstName_2\"></input></td>
                <td><input type=\"text\" id=\"inFirstName_3\" class=\"inputBox\" value=\"$firstName_3\"></input></td>
                <td><input type=\"text\" id=\"inFirstName_4\" class=\"inputBox\" value=\"$firstName_4\"></input></td>
                </tr>
                <tr><td class=\"label\">Last Name:</td>
                <td><input type=\"text\" id=\"inLastName_1\" class=\"inputBox\" value=\"$lastName_1\"></input></td>
                <td><input type=\"text\" id=\"inLastName_2\" class=\"inputBox\" value=\"$lastName_2\"></input></td>
                <td><input type=\"text\" id=\"inLastName_3\" class=\"inputBox\" value=\"$lastName_3\"></input></td>
                <td><input type=\"text\" id=\"inLastName_4\" class=\"inputBox\" value=\"$lastName_4\"></input></td>
                </tr>
                <tr><td class=\"label\">Street:</td>
                <td><input type=\"text\" id=\"inStreet_1\" class=\"inputBox\" value=\"$street_1\"></input></td>
                <td><input type=\"text\" id=\"inStreet_2\" class=\"inputBox\" value=\"$street_2\"></input></td>
                <td><input type=\"text\" id=\"inStreet_3\" class=\"inputBox\" value=\"$street_3\"></input></td>
                <td><input type=\"text\" id=\"inStreet_4\" class=\"inputBox\" value=\"$street_4\"></input></td>
                </tr>
                <tr><td class=\"label\">City:</td>
                <td><input type=\"text\" id=\"inCity_1\" class=\"inputBox\" value=\"$city_1\"></input></td>
                <td><input type=\"text\" id=\"inCity_2\" class=\"inputBox\" value=\"$city_2\"></input></td>
                <td><input type=\"text\" id=\"inCity_3\" class=\"inputBox\" value=\"$city_3\"></input></td>
                <td><input type=\"text\" id=\"inCity_4\" class=\"inputBox\" value=\"$city_4\"></input></td>
                </tr>
                <tr><td class=\"label\">State:</td>
                <td><input type=\"text\" id=\"inState_1\" class=\"inputBox\" value=\"$state_1\"></input></td>
                <td><input type=\"text\" id=\"inState_2\" class=\"inputBox\" value=\"$state_2\"></input></td>
                <td><input type=\"text\" id=\"inState_3\" class=\"inputBox\" value=\"$state_3\"></input></td>
                <td><input type=\"text\" id=\"inState_4\" class=\"inputBox\" value=\"$state_4\"></input></td>
                </tr>
                <tr><td class=\"label\">Zip:</td>
                <td><input type=\"text\" id=\"inZip_1\" class=\"inputBox\" value=\"$zip_1\"></input></td></td>
                <td><input type=\"text\" id=\"inZip_2\" class=\"inputBox\" value=\"$zip_2\"></input></td></td>
                <td><input type=\"text\" id=\"inZip_3\" class=\"inputBox\" value=\"$zip_3\"></input></td></td>
                <td><input type=\"text\" id=\"inZip_4\" class=\"inputBox\" value=\"$zip_4\"></input></td></td>
                </tr>
                <tr><td class=\"label\">Phone:</td>
                <td><input type=\"tel\" id=\"inPhone_1\" class=\"inputBox\" value=\"$phone_1\" pattern=\"[0-9]{3}-[0-9]{3}-[0-9]{4}\"></input></td>
                <td><input type=\"tel\" id=\"inPhone_2\" class=\"inputBox\" value=\"$phone_2\" pattern=\"[0-9]{3}-[0-9]{3}-[0-9]{4}\"></input></td>
                <td><input type=\"tel\" id=\"inPhone_3\" class=\"inputBox\" value=\"$phone_3\" pattern=\"[0-9]{3}-[0-9]{3}-[0-9]{4}\"></input></td>
                <td><input type=\"tel\" id=\"inPhone_4\" class=\"inputBox\" value=\"$phone_4\" pattern=\"[0-9]{3}-[0-9]{3}-[0-9]{4}\"></input></td>
                </tr>
                <tr><td class=\"label\">Email:</td>
                <td><input type=\"email\" id=\"inEmail_1\" class=\"inputBox\" value=\"$email_1\"></input></td>
                <td><input type=\"email\" id=\"inEmail_2\" class=\"inputBox\" value=\"$email_2\"></input></td>
                <td><input type=\"email\" id=\"inEmail_3\" class=\"inputBox\" value=\"$email_3\"></input></td>
                <td><input type=\"email\" id=\"inEmail_4\" class=\"inputBox\" value=\"$email_4\"></input></td>
                </tr>
                <tr><td class=\"label\">Username:</td>
                <td><input type=\"text\" id=\"inUsername_1\" class=\"inputBox\" value=\"$username_1\" pattern=\"^[A-Za-z0-9_\-\*\&\$\@]{6,10}$\"></input></td>
                <td><input type=\"text\" id=\"inUsername_2\" class=\"inputBox\" value=\"$username_2\" pattern=\"^[A-Za-z0-9_\-\*\&\$\@]{6,10}$\"></input></td>
                <td><input type=\"text\" id=\"inUsername_3\" class=\"inputBox\" value=\"$username_3\" pattern=\"^[A-Za-z0-9_\-\*\&\$\@]{6,10}$\"></input></td>
                <td><input type=\"text\" id=\"inUsername_4\" class=\"inputBox\" value=\"$username_4\" pattern=\"^[A-Za-z0-9_\-\*\&\$\@]{6,10}$\"></input></td>
                </tr>
                <tr><td class=\"label\">Notes:</td>
                <td><textarea id=\"txtNotes_1\" class=\"textArea\">$notes_1</textarea></td>
                <td><textarea id=\"txtNotes_2\" class=\"textArea\">$notes_2</textarea></td>
                <td><textarea id=\"txtNotes_3\" class=\"textArea\">$notes_3</textarea></td>
                <td><textarea id=\"txtNotes_4\" class=\"textArea\">$notes_4</textarea></td>
                </tr>
            </table>
            </td></tr>
            <tr><td id=\"tdSubmitSearchUsers\">
            <input type=\"button\" id=\"btnSubmitSearchUsers\" class=\"button\" value=\"Submit Search\"/>
            &nbsp;&nbsp;&nbsp;<input type=\"button\" id=\"btnClearCriteria\" class=\"button\" value=\"Clear Criteria\"/>
            </td></tr>
            </table>
            </div>
        ";    
    }

    /**   Displays the "Edit User form" for the user search results.
     * The database connection is necessary to populate and display
     * the Roles drop-down.
     * 
     * @param PDO $db -- A database connection.
     */
    public static function editUserDisplay($db){
        $Roles_ID = (isset($_SESSION['SR_Roles_ID']) ? $_SESSION['SR_Roles_ID'] : '');
        $firstName = (isset($_SESSION['SR_first_name']) ? $_SESSION['SR_first_name'] : '');
        $lastName = (isset($_SESSION['SR_last_name']) ? $_SESSION['SR_last_name'] : '');
        $street = (isset($_SESSION['SR_street']) ? $_SESSION['SR_street'] : '');
        $city = (isset($_SESSION['SR_city']) ? $_SESSION['SR_city'] : '');
        $state = (isset($_SESSION['SR_state']) ? $_SESSION['SR_state'] : '');
        $zip = (isset($_SESSION['SR_zip']) ? $_SESSION['SR_zip'] : '');
        $phone = (isset($_SESSION['SR_phone']) ? $_SESSION['SR_phone'] : '');
        $email = (isset($_SESSION['SR_email']) ? $_SESSION['SR_email'] : '');
        $username = (isset($_SESSION['SR_username']) ? $_SESSION['SR_username'] : '');
        $password = (isset($_SESSION['SR_password']) ? $_SESSION['SR_password'] : '');
        $notes = (isset($_SESSION['SR_notes']) ? $_SESSION['SR_notes'] : '');

        $dropdown = Login_db::populateSelRoles($Roles_ID, $db);
        echo "
            <div id=\"divEditUser\">
            <table id=\"tblOuter\"><tr><td colspan=\"2\" id=\"tdHeading\">
            <h1>E D I T&nbsp;&nbsp;&nbsp;U S E R&nbsp;&nbsp;&nbsp;S E A R C H&nbsp;&nbsp;&nbsp;R E S U L T S&nbsp;&nbsp;&nbsp;O N&nbsp;&nbsp;&nbsp;R E F R I N G E . O R G</h1><br>
            </td></tr><tr><td>
            <br>
            <table id=\"tblEditUser\">
                <tr><td class=\"label\">Role:</td><td id=\"tdSelRoles\">$dropdown
                </td><td class=\"tdInstructions\">*</td></tr>
                <tr><td class=\"label\">First Name:</td><td><input type=\"text\" id=\"inFirstName\" class=\"inputBox\"
                value=\"$firstName\" required></input></td>
                <td class=\"tdInstructions\">*</td></tr>
                <tr><td class=\"label\">Last Name:</td><td><input type=\"text\" id=\"inLastName\" class=\"inputBox\"
                value=\"$lastName\" required></input></td>
                <td class=\"tdInstructions\">*</td></tr>
                <tr><td class=\"label\">Street:</td><td><input type=\"text\" id=\"inStreet\" class=\"inputBox\"
                value=\"$street\"></input></td>
                <td class=\"tdInstructions\">&nbsp;</td></tr>
                <tr><td class=\"label\">City:</td><td><input type=\"text\" id=\"inCity\" class=\"inputBox\"
                value=\"$city\"></input></td>
                <td class=\"tdInstructions\">&nbsp;</td></tr>
                <tr><td class=\"label\">State:</td><td><input type=\"text\" id=\"inState\" class=\"inputBox\"
                value=\"$state\"></input></td>
                <td class=\"tdInstructions\">&nbsp;</td></tr>
                <tr><td class=\"label\">Zip:</td><td><input type=\"text\" id=\"inZip\" class=\"inputBox\"
                value=\"$zip\"></input></td></td>
                <td class=\"tdInstructions\">&nbsp;</td></tr>
                <tr><td class=\"label\">Phone:</td><td><input type=\"tel\" id=\"inPhone\" class=\"inputBox\"
                value=\"$phone\" pattern=\"[0-9]{3}-[0-9]{3}-[0-9]{4}\" required></input></td>
                <td class=\"tdInstructions\">*</td></tr>
                <tr><td class=\"label\">Email:</td><td><input type=\"email\" id=\"inEmail\" class=\"inputBox\"
                value=\"$email\" required></input></td>
                <td class=\"tdInstructions\">*</td></tr>
                <tr><td class=\"label\">Username:</td><td><input type=\"text\" id=\"inUsername\" class=\"inputBox\"
                value=\"$username\" pattern=\"^[A-Za-z0-9_\-\*\&\$\@]{6,10}$\" required></input></td>
                <td class=\"tdInstructions\">*</td></tr>
                <tr><td id=\"resetPassword\" class=\"label\">CHANGE Password:</td><td><input type=\"password\" id=\"inPassword\" 
                class=\"inputBox\" value=\"$password\" pattern=\"^[A-Za-z0-9_\-\*\&\$\@]{6,10}$\"></input></td>
                <td class=\"tdInstructions\">&nbsp;</td></tr>
                <tr><td id=\"tdSubmitEditUser\" colspan=\"3\">
                <input type=\"button\" id=\"btnSubmitEditUser\"  class=\"button\" value=\"Submit Edit User\"/>
                &nbsp;&nbsp;&nbsp;<input type=\"button\" id=\"btnCancel\"  class=\"button\" value=\"Back to User Search\"/>
                </td></tr>
            </table>
            </td><td>
            <p id=\"pLabel\" class=\"label\">Notes:</p>
            <textarea id=\"txtNotes\">$notes</textarea>
            </td></tr></table>
            </div>
        ";
    }
}//End class, "User_Search_ui".