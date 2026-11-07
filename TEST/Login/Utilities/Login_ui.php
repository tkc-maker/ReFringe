<?php
class Login_ui{
    /**   Displays a login screen.
     * 
     */
    public static function loginDisplay(){
        echo "
            <div id=\"divLogin\">
            <h1>L O G I N&nbsp;&nbsp;&nbsp;T O&nbsp;&nbsp;&nbsp;R E F R I N G E . O R G</h1>
            <table id=\"tblLogin\">
                <tr><td colspan=\"2\">
                <input type=\"button\" id=\"btnQuickEasyNoLoginProposal\" class=\"button\" value=\"Quick Easy No Login Proposal Entry\"/>
                <input type=\"button\" id=\"btnBackToHomePage\" class=\"button\" value=\"Back to Home Page\"/>
                </td></tr>
                <tr><td class=\"label\">Username:</td><td><input type=\"text\" id=\"inUsername\" class=\"inputBox\"></input></td></tr>
                <tr><td class=\"label\">Password:</td><td><input type=\"password\" id=\"inPassword\" class=\"inputBox\"></input></td></tr>
                <tr><td colspan=\"2\"><input type=\"button\" id=\"btnSubmitLogin\" class=\"button\" value=\"Submit Login\"/>
                &nbsp;&nbsp;<input type=\"button\" id=\"btnNewUser\" class=\"button\" value=\"New User?\"/></td></tr>
                <tr><td colspan=\"2\"><a id=\"lnkForgotUsername\" href=\"\">Forgot Username?</a>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id=\"lnkForgotPassword\" href=\"\">Forgot Password?</a></td></tr>
            </table>
            </div>
        ";
    }

    /**   Displays the "New User Entry form". A database connection is passed in so
     * that the Roles drop-down can be populated.
     * 
     * @param PDO $db -- A database connection.
     */
    public static function newUserDisplay($db){
        $selRoles_ID = 2; //Preselect "Proposer" for the disabled dropdown.
        $firstName = (isset($_SESSION['NU_first_name']) ? $_SESSION['NU_first_name'] : '');
        $lastName = (isset($_SESSION['NU_last_name']) ? $_SESSION['NU_last_name'] : '');
        $street = (isset($_SESSION['NU_street']) ? $_SESSION['NU_street'] : '');
        $city = (isset($_SESSION['NU_city']) ? $_SESSION['NU_city'] : '');
        $state = (isset($_SESSION['NU_state']) ? $_SESSION['NU_state'] : '');
        $zip = (isset($_SESSION['NU_zip']) ? $_SESSION['NU_zip'] : '');
        $phone = (isset($_SESSION['NU_phone']) ? $_SESSION['NU_phone'] : '');
        $email = (isset($_SESSION['NU_email']) ? $_SESSION['NU_email'] : '');
        $username = (isset($_SESSION['NU_username']) ? $_SESSION['NU_username'] : '');
        $password = (isset($_SESSION['NU_password']) ? $_SESSION['NU_password'] : '');
        $rePassword = (isset($_SESSION['NU_rePassword']) ? $_SESSION['NU_rePassword'] : '');
        $notes = (isset($_SESSION['NU_notes']) ? $_SESSION['NU_notes'] : '');

        $dropdown = Login_db::populateSelRoles($selRoles_ID, $db);
        echo "
            <div id=\"divNewUser\">
            <table id=\"tblOuter\"><tr><td colspan=\"2\" id=\"tdHeading\">
            <h1>N E W&nbsp;&nbsp;&nbsp;U S E R&nbsp;&nbsp;&nbsp;O N&nbsp;&nbsp;&nbsp;R E F R I N G E . O R G</h1><br>
            </td></tr><tr><td>
            <table id=\"tblNewUser\">
                <tr><td class=\"label\">Role:</td><td id=\"tdSelRoles\">$dropdown</td>
                <td class=\"tdInstructions\">*</td></tr>
                <tr><td class=\"label\">First Name:</td><td><input type=\"text\" id=\"inFirstName\" class=\"inputBox\"
                value=\"$firstName\"></input></td>
                <td class=\"tdInstructions\">*</td></tr>
                <tr><td class=\"label\">Last Name:</td><td><input type=\"text\" id=\"inLastName\" class=\"inputBox\"
                value=\"$lastName\"></input></td>
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
                <tr><td class=\"label\">Phone:</td><td><input type=\"text\" id=\"inPhone\" class=\"inputBox\"
                value=\"$phone\" pattern=\"[0-9]{3}-[0-9]{3}-[0-9]{4}\" required></input></td>
                <td class=\"tdInstructions\">*</td></tr>
                <tr><td class=\"label\">Email:</td><td><input type=\"email\" id=\"inEmail\" class=\"inputBox\"
                value=\"$email\" required></input></td>
                <td class=\"tdInstructions\">*</td></tr>
                <tr><td class=\"label\">Username:</td><td><input type=\"text\" id=\"inUsername\" class=\"inputBox\"
                value=\"$username\" pattern=\"^[A-Za-z0-9_\-\*\&\$\@]{6,10}$\" required></input></td>
                <td class=\"tdInstructions\">*</td></tr>
                <tr><td class=\"label\">Password:</td><td><input type=\"password\" id=\"inPassword\" class=\"inputBox\"
                value=\"$password\" pattern=\"^[A-Za-z0-9_\-\*\&\$\@]{6,10}$\" required></input></td>
                <td class=\"tdInstructions\">*</td></tr>
                <tr><td class=\"label\">Repeat Password:</td><td><input type=\"password\" id=\"reinPassword\" class=\"inputBox\"
                value=\"$rePassword\" required></input></td>
                <td class=\"tdInstructions\">*</td></tr>
                <tr><td id=\"tdSubmitNewUser\" colspan=\"3\"><input type=\"button\" id=\"btnSubmitNewUser\"  class=\"button\" value=\"Submit New User\"/>
                &nbsp;&nbsp;<input type=\"button\" id=\"btnCancelNewUser\" class=\"button\" value=\"Back to Login\"/></td></tr>
            </table>
            </td><td>
    <p id=\"pNotes\">We’d love to know how you respond to ReFringe.org. Would you
     like to help us organize events, propose events yourself, or simply attend our
     events and view the site? Perhaps you’d like to do it all! Whatever you choose
     to do, please let us know, and leave us a clear way to contact you:</p>
            <textarea id=\"txtNotes\">$notes</textarea>
            </td></tr></table>
            </div>
        ";
    }


    /**   Displays the "Forgot Username" form.
     * 
     */
    public static function forgotUsernameDisplay(){
        echo "
            <div id=\"divForgotUsername\">
            <p id=\"pHeading\">Please enter the following to retrieve your username from ReFringe.org:</p>
            <table id=\"tblForgotUsername\">
                <tr><td class=\"label\">First Name:</td><td><input type=\"text\" id=\"inFirstName\" class=\"inputBox\"></input></td>
                <td class=\"tdInstructions\">*</td></tr>
                <tr><td class=\"label\">Last Name:</td><td><input type=\"text\" id=\"inLastName\" class=\"inputBox\"></input></td>
                <td class=\"tdInstructions\">*</td></tr>
                <tr><td class=\"label\">Password:</td><td><input type=\"password\" id=\"inPassword\" class=\"inputBox\"></input></td>
                <td class=\"tdInstructions\">*</td></tr>
                <tr><td id=\"tdSubmitInfo\" colspan=\"2\"><input type=\"button\" id=\"btnSubmitUsernameInfo\" class=\"button\" value=\"Submit Info\"/></td></tr>
            </table>
            </div>
        ";
    }

    /**   Displays the "Forgot Password" form.
     * 
     */
    public static function forgotPasswordDisplay(){
        echo "
            <div id=\"divForgotPassword\">
            <p id=\"pHeading\">Please enter the following to reset your password on ReFringe.org:</p>
            <table id=\"tblForgotUsername\">
                <tr><td class=\"label\">First Name:</td><td><input type=\"text\" id=\"inFirstName\" class=\"inputBox\"></input></td>
                <td class=\"tdInstructions\">*</td></tr>
                <tr><td class=\"label\">Last Name:</td><td><input type=\"text\" id=\"inLastName\" class=\"inputBox\"></input></td>
                <td class=\"tdInstructions\">*</td></tr>
                <tr><td class=\"label\">Username:</td><td><input type=\"username\" id=\"inUsername\" class=\"inputBox\"></input></td>
                <td class=\"tdInstructions\">*</td></tr>
                <tr><td id=\"tdSubmitInfo\" colspan=\"2\"><input type=\"button\" id=\"btnSubmitPasswordInfo\" class=\"button\" value=\"Submit Info\"/></td></tr>
            </table>
            </div>
        ";
    }

}//End class, "Login_ui".