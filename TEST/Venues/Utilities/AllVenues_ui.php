<?php

class AllVenues_ui{
    
    public static function displayAllVenues($db){
        $allVenues = AllVenues_db::getAllVenuesFromDb($db);

        $tblReturn = "<h1>&nbsp;&nbsp;&nbsp;A L L&nbsp;&nbsp;&nbsp;V E N U E S&nbsp;&nbsp;&nbsp;( A d m i n )</h1>
            <table id=\"tblAllVenues\"><tr><td colspan=\"3\" class=\"center\"><input type=\"button\" id=\"btnNewVenue\" class=\"button\" value=\"New Venue?\"/>
            </td></tr>";
        
        $aryAllVenues = json_decode($allVenues, true);

        if($aryAllVenues['status'] === "false"){
            $tblReturn .= "<tr><th colspan=\"3\">".$aryAllVenues['errMsg']."</th></tr></table>";
            return $tblReturn;
            
        }else{
            $tblReturn .= "<tr><th>Venue Name:</th><th>Venue Check:</th><th>Delete?</th></tr>";
        }
        
        foreach($aryAllVenues['allVenues'] as $venue){
            $ID = $venue['ID'];
            $name = !empty($venue['name'])?$venue['name']:('Venue ID: '.$ID);
            $venue_check = $venue['venue_check'];
            $isChecked = filter_var($venue_check, FILTER_VALIDATE_BOOLEAN);
            
            $tblReturn .= "<tr><td><a href=\"\" class=\"aVenue\" data-id=$ID>$name</a></td>
                <td class=\"center\"><input type=\"checkbox\" class=\"checkbox\" value=\"1\" ".($isChecked ? "checked=\"checked\"" : "")." data-id=$ID/></td>
                <td class=\"center\"><input type=\"button\" class=\"button btnDelete\" value=\"Delete\" data-id=\"$ID\"/></td></tr>";
        }
        $tblReturn .= "<tr><td colspan=\"3\" class=\"center\"><input id=\"btnSaveVenueChecks\" type=\"button\" class=\"button\" value=\"Save Venue Checks\"/></td></table>";
        
        return $tblReturn;
    }
    
    
}//End class, "AllVenues_ui".