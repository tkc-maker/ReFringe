<?php
class roles_ui{
    /**   Populates an HTML 5 drop-down from the associative array passed in. 
     * If an empty array is passed in, then '' is returned.
     * 
     * @param Assoc Array $aryRoles -- an associative array of [ID, name] 
     *      from the "Roles" table.
     * @return string -- The populated drop-down.
     */
    public static function displayRoleDropdown($aryRoles){
        if(!empty($aryRoles)){
            $dropdown = "<select name=\"dd_roles\" id=\"dd_roles\">";
            foreach ($aryRoles as $row):
                $dropdown .= "<option value=\"".$row['ID']."\">".htmlspecialchars($row['name'])."</option>";
            endforeach;
            $dropdown .= "</select>";

            return $dropdown;
        }else{
            return '';
        }
    }//End function, "displayRoleDropdown".
    
    /**   Displays a page for the "Roles" reference table editor.
     * 
     */
    public static function rolesDisplay(){
       $newRole = (isset($_SESSION['R_NewRole']) ? $_SESSION['R_NewRole'] : '');
       
        echo "
        <h1>&nbsp;&nbsp;&nbsp;E D I T&nbsp;&nbsp;&nbsp;R E F E R E N C E&nbsp;&nbsp;&nbsp;T A B L E ,<br>
        &nbsp;&nbsp;&nbsp;R o l e s&nbsp;&nbsp;&nbsp;( A d m i n )</h1><br>
        <div id=\"divWarning\">WARNING: Changing the names of roles or deleting them may lock you out of screens, as if you don't have
        permissions to access them. If this happens, consult a PHP programmer to fix the problem.</div><br>
        <div id=\"divRoles\" class=\"ht-theme-main-dark\"></div>
        <div id=\"divButtons\">
            <input type=\"button\" id=\"btnSaveCheckedRows\" value=\"Save Checked Rows\"/>
            <input type=\"button\" id=\"btnDeleteCheckedRows\" value=\"Delete Checked Rows\"/><br>
            <label class=\"fieldHeading\">Add New Role: </label>
            <input type=\"text\" id=\"txtNewRole\" value=\"$newRole\"/>
            <input type=\"button\" id=\"btnSaveNewRole\" value=\"Save New Role\"/>
        </div>
        ";
    }//End function, "rolesDisplay".
    
}