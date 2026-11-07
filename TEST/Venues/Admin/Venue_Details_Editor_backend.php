<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
UserInfo::checkLogin("SuperUser, Organizer");


require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Venues/Utilities/Venue_Details_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Venues/Utilities/Venue_Details_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ScreenFieldValidation/Utilities/Screenfield_validation_db.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'displayVenueDetails':
        $Venues_ID = filter_input(INPUT_POST, 'Venues_ID', FILTER_SANITIZE_NUMBER_INT);

        $db = openDatabaseConnection();
        $jsonVenueDetails = Venue_Details_db::fetchVenueDetailsFromDb($Venues_ID, $db);       
        $aryVenueDetails = json_decode($jsonVenueDetails, true);
        $Venues_ID = $aryVenueDetails['ID'];
        closeDatabaseConnection($db);

        if($aryVenueDetails['status'] === "true"){
            echo Venue_Details_ui::displayVenueDetails($Venues_ID, $jsonVenueDetails);        
        }else{
            echo "<script>alert(".$aryVenueDetails['errMsg'].")</script>";
        }
        break;
        
    case 'saveVenueDetails':
        $jsonVenueDetails = trim(filter_input(INPUT_POST, 'jsonVenueDetails', FILTER_UNSAFE_RAW));      
        
        $db = openDatabaseConnection();
        echo Venue_Details_db::updateVenueDetailsInDb($jsonVenueDetails, $db);
        closeDatabaseConnection($db);
        break;
    
    case 'getAllScreenfieldValidations':
        $db = openDatabaseConnection();
        echo Screenfield_validation_db::getAllScreenfieldValidationsFromDb($db);
        closeDatabaseConnection($db);        
        break;
    
}//End "switch".
