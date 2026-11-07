<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
UserInfo::checkLogin("SuperUser, Organizer");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/EventSchedule/Utilities/Calendar_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/EventSchedule/Utilities/Calendar_db.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'displayEmptyCalendar':
        echo Calendar_ui::displayEmptyCalendarAndMenu();
        break;
    
    case 'fetchCalendarEvents':
        $db = openDatabaseConnection();
        echo Calendar_db::fetchAllCalendarEventsFromDb($db);
        closeDatabaseConnection($db);
        break;
    
    case 'fetchGeneralEvents':
        $db = openDatabaseConnection();
        echo Calendar_db::fetchAllGeneralEventsFromDb($db);
        closeDatabaseConnection($db);
        break;
    
    case 'copyCalendarEvent':
        $Proposal_details_ID = filter_input(INPUT_POST, 'proposal_details_id', FILTER_SANITIZE_NUMBER_INT);
        $Venues_ID = filter_input(INPUT_POST, 'venues_id', FILTER_SANITIZE_NUMBER_INT);
        $day = filter_input(INPUT_POST, 'day', FILTER_SANITIZE_NUMBER_INT);
        $start = trim($_POST['start']);
        $end = trim($_POST['end']);
        $tag = trim($_POST['tag']);
        
        $db = openDatabaseConnection();
        echo Calendar_db::insertCalendarEvent($Proposal_details_ID, $Venues_ID, $day, $start, $end, $tag, $db);
        closeDatabaseConnection($db);
        break;
    
    case 'moveCalendarEvent':
        $Calendar_ID = filter_input(INPUT_POST, 'calendar_id', FILTER_SANITIZE_NUMBER_INT);
        $day = filter_input(INPUT_POST, 'day', FILTER_SANITIZE_NUMBER_INT);
        $start = trim($_POST['start']);
        $end = trim($_POST['end']);
        $tag = trim($_POST['tag']);
        
        $db = openDatabaseConnection();
        echo Calendar_db::updateCalendarEventWithMainFields($Calendar_ID, $day, $start, $end, $tag, $db);
        closeDatabaseConnection($db);
        break;
    
    case 'deleteCalendarEvent':
        $Calendar_ID = filter_input(INPUT_POST, 'calendar_id', FILTER_SANITIZE_NUMBER_INT);
        
        $db = openDatabaseConnection();
        echo Calendar_db::deleteCalendarEvent($Calendar_ID, $db);
        closeDatabaseConnection($db);
        break;
        
}//End switch.
