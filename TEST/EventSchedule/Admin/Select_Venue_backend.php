<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/EventSchedule/Utilities/Calendar_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/EventSchedule/Utilities/Select_Venue_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/EventSchedule/Utilities/Select_Venue_ui.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'displayCheckedVenuesHeader':
        $title = trim($_POST['title']);
        echo Select_Venue_ui::displayCheckedVenuesHeader($title);
        break;
    
    case 'fetchAllCheckedVenues':
        $Venues_ID = filter_input(INPUT_POST, 'Venues_ID', FILTER_SANITIZE_NUMBER_INT);
        $db = openDatabaseConnection();
        echo Select_Venue_db::fetchAllCheckedVenuesFromDb($Venues_ID, $db);
        closeDatabaseConnection($db);
        break;
    
    case 'saveVenueSelection':
        $Calendar_ID = filter_input(INPUT_POST, 'Calendar_ID', FILTER_SANITIZE_NUMBER_INT);
        $Venues_ID = filter_input(INPUT_POST, 'Venues_ID', FILTER_SANITIZE_NUMBER_INT);
        $db = openDatabaseConnection();
        $jsonResults = Calendar_db::updateCalendarEventWithVenuesId($Calendar_ID, $Venues_ID, $db);
        
        echo $jsonResults;
        closeDatabaseConnection($db);
        break;
        
    default:
        echo Select_Venue_ui::displayCheckedVenuesHeader();
        break;
}