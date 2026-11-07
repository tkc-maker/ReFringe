<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/EventSchedule/Utilities/Calendar_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/EventSchedule/Utilities/Calendar_ui.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'displayCalendarEventFields':
        $title = trim($_POST['title']);
        $day = filter_input(INPUT_POST, 'day', FILTER_SANITIZE_NUMBER_INT);
        $start = trim($_POST['start']);
        $end = trim($_POST['end']);
        $tag = trim($_POST['tag']);
        echo Calendar_ui::displayCalendarEventFields($title, $day, $start, $end, $tag);
        break;
    
    case 'saveCalendarEvent':
        $Calendar_ID = filter_input(INPUT_POST, 'calendar_id', FILTER_SANITIZE_NUMBER_INT);
        $day = filter_input(INPUT_POST, 'day', FILTER_SANITIZE_NUMBER_INT);
        $start = trim($_POST['start']);
        $end = trim($_POST['end']);
        $tag = trim($_POST['tag']);
        $db = openDatabaseConnection();
        echo Calendar_db::updateCalendarEventWithMainFields($Calendar_ID, $day, $start, $end, $tag, $db);        
        closeDatabaseConnection($db);
        break;
}
