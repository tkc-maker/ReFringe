<?php

class Select_Venue_ui {
    /**   Displays a heading for the "Select A Venue" rpage.
     * 
     */
    public static function displayCheckedVenuesHeader($eventTitle){
        return "
        <h1>S E L E C T&nbsp;&nbsp;&nbsp;A&nbsp;&nbsp;&nbsp;V E N U E&nbsp;&nbsp;&nbsp;-- $eventTitle</h1><br>
        <div><input type=\"button\" class=\"button btnSaveVenueSelection\" value=\"Save Venue Selection\"/></div>
        <div id=\"divAllCheckedVenues\" class=\"ht-theme-main-dark\"></div>
        <div><input type=\"button\" class=\"button btnSaveVenueSelection\" value=\"Save Venue Selection\"/></div>
        ";
    }//End function, "displayCheckedVenuesHeader".    
}
