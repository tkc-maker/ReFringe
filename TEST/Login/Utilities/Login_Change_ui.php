<?php
class Login_Change_ui{
    /**   Displays a username/password change screen.
     * 
     */
    public static function loginChangeDisplay(){
        $usernameCurrent = (isset($_SESSION['usernameCurrent']) ? $_SESSION['usernameCurrent'] : '');
        $passwordCurrent = (isset($_SESSION['passwordCurrent']) ? $_SESSION['passwordCurrent'] : '');
        $usernameNew = (isset($_SESSION['usernameNew']) ? $_SESSION['usernameNew'] : '');
        $passwordNew = (isset($_SESSION['passwordNew']) ? $_SESSION['passwordNew'] : '');
        $rePasswordNew = (isset($_SESSION['rePasswordNew']) ? $_SESSION['rePasswordNew'] : '');

        echo "
            <div id=\"divLoginChange\">
            <h1>C H A N G E&nbsp;&nbsp;&nbsp;L O G I N&nbsp;&nbsp;&nbsp;T O&nbsp;&nbsp;&nbsp;R E F R I N G E . O R G<br>O R&nbsp;&nbsp;&nbsp;E D I T&nbsp;&nbsp;&nbsp;C U R R E N T&nbsp;&nbsp;&nbsp;U S E R</h1>
            <br>
            <table id=\"tblLoginChange\">
                <tr><td class=\"label\">Current Username:</td><td><input type=\"text\" id=\"inUsernameCurrent\" class=\"inputBox\"
                value=\"$usernameCurrent\" pattern=\"^[A-Za-z0-9_\-\*\&\$\@]{6,10}$\" required></input></td><td class=\"tdInstructions\">*</td></tr>
                <tr><td class=\"label\">Current Password:</td><td><input type=\"password\" id=\"inPasswordCurrent\" class=\"inputBox\"
                value=\"$passwordCurrent\" pattern=\"^[A-Za-z0-9_\-\*\&\$\@]{6,10}$\" required></input></td><td class=\"tdInstructions\">*</td></tr>
                <tr><td class=\"label\">New Usernname:</td><td><input type=\"text\" id=\"inUsernameNew\" class=\"inputBox\"
                value=\"$usernameNew\" pattern=\"^[A-Za-z0-9_\-\*\&\$\@]{6,10}$\" required></input></td><td class=\"tdInstructions\">*</td></tr>
                <tr><td class=\"label\">New Password:</td><td><input type=\"password\" id=\"inPasswordNew\" class=\"inputBox\"
                value=\"$passwordNew\" pattern=\"^[A-Za-z0-9_\-\*\&\$\@]{6,10}$\" required></input></td><td class=\"tdInstructions\">*</td></tr>
                <tr><td class=\"label\">Re-enter New Password:</td><td><input type=\"password\" id=\"inRePasswordNew\" class=\"inputBox\"
                value=\"$rePasswordNew\" pattern=\"^[A-Za-z0-9_\-\*\&\$\@]{6,10}$\" required></input></td><td class=\"tdInstructions\">*</td></tr>
                <tr><td id=\"tdSubmitLoginChange\" colspan=\"3\"><input type=\"button\" id=\"btnSubmitLoginChange\" class=\"button\" value=\"Submit Login Change\"/>
                &nbsp;&nbsp;<input type=\"button\" id=\"btnEditUser\" class=\"button\" value=\"Edit User?\"/></td></tr>
            </table>
            </div>
        ";
    }

    /**   Displays the "Edit User screen". A database connection is necessary
     * to populate and display the Roles drop-down.
     * 
     * @param PDO $db -- A database connection.
     */
    public static function editUserDisplay($db){
        $selRoles_ID = (isset($_SESSION['Roles_ID']) ? $_SESSION['Roles_ID'] : UserInfo::$Roles_ID);
        $firstName = (isset($_SESSION['first_name']) ? $_SESSION['first_name'] : UserInfo::$first_name);
        $lastName = (isset($_SESSION['last_name']) ? $_SESSION['last_name'] : UserInfo::$last_name);
        $street = (isset($_SESSION['street']) ? $_SESSION['street'] : UserInfo::$street);
        $city = (isset($_SESSION['city']) ? $_SESSION['city'] : UserInfo::$city);
        $state = (isset($_SESSION['state']) ? $_SESSION['state'] : UserInfo::$state);
        $zip = (isset($_SESSION['zip']) ? $_SESSION['zip'] : UserInfo::$zip);
        $phone = (isset($_SESSION['phone']) ? $_SESSION['phone'] : UserInfo::$phone);
        $email = (isset($_SESSION['email']) ? $_SESSION['email'] : UserInfo::$email);
        $notes = (isset($_SESSION['notes']) ? $_SESSION['notes'] : UserInfo::$email);

        $dropdown = Login_db::populateSelRoles($selRoles_ID, $db);
        echo "
            <div id=\"divEditUser\">
            <table id=\"tblOuter\"><tr><td colspan=\"2\" id=\"tdHeading\">
            <h1>E D I T&nbsp;&nbsp;&nbsp;U S E R&nbsp;&nbsp;&nbsp;O N&nbsp;&nbsp;&nbsp;R E F R I N G E . O R G</h1><br>
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
                <tr><td id=\"tdSubmitEditUser\" colspan=\"3\">
                <input type=\"button\" id=\"btnSubmitEditUser\"  class=\"button\" value=\"Submit Edit User\"/>
                </td></tr>
            </table>
            </td><td>
            <p class=\"label\">Notes:</p>
            <textarea id=\"txtNotes\">$notes</textarea>
            </td></tr></table>
            </div>
        ";
    }
}//End class, "Login_Change_ui".