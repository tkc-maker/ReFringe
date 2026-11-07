<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/EventSchedule/Utilities/General_Event_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/EventSchedule/Utilities/General_Event_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/EventSchedule/Utilities/Members_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/EventSchedule/Utilities/Resources_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ScreenFieldValidation/Utilities/Screenfield_validation_db.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'displayEventDetailsScreen':
        $Proposal_details_ID = filter_input(INPUT_POST, 'proposal_details_id', FILTER_SANITIZE_NUMBER_INT);
        $title = trim(($_POST['title']));
        $db = openDatabaseConnection();        
        $jsonResults = General_Event_db::fetchProposerInfo($Proposal_details_ID, $db);
        $aryResults = json_decode($jsonResults, true);
        if($aryResults['status'] === 'true'){
            echo General_Event_ui::displayEventDetailsScreen_Admin($aryResults['aryProposerInfo'], $title);
        }else{
            echo '<script>alert("'.$aryResults['errMsg'].'")</script>';
        }
        break;
    
    case 'displayMainSelections':
        $Proposal_details_ID = filter_input(INPUT_POST, 'Proposal_details_ID', FILTER_SANITIZE_NUMBER_INT);
        $db = openDatabaseConnection();
        $mainResults = General_Event_db::getEventFromDb($Proposal_details_ID, $db);
        $aryMainResults = json_decode($mainResults, true);

        if($aryMainResults['status'] === 'true'){
            $_SESSION['E_Events_ID'] = $aryMainResults['event']['ID'];
            $mainResults = json_encode($aryMainResults);
        }
        echo $mainResults;
        closeDatabaseConnection($db);
        break;
        
    case 'displayAllMembers':
        $db = openDatabaseConnection();
        echo Members_db::fetchAllMembers($db);
        closeDatabaseConnection($db);
        break;
    
    case 'showSelectedMembers':
        $db = openDatabaseConnection();
        echo Members_db::fetchAllSelectedMemberIDs($_SESSION['E_Events_ID'], $db);
        closeDatabaseConnection($db);
        break;
    
    case 'displayAllResources':
        $db = openDatabaseConnection();
        echo Resources_db::fetchAllResources($db);
        closeDatabaseConnection($db);
        break;
    
    case 'showSelectedResources':
        $db = openDatabaseConnection();
        echo Resources_db::fetchAllSelectedResourceIDs($_SESSION['E_Events_ID'], $db);
        closeDatabaseConnection($db);
        break;    
    
    case 'insertNewMember':
        $jsonMember = trim(filter_input(INPUT_POST, 'jsonMember', FILTER_UNSAFE_RAW));
        $aryMember = json_decode($jsonMember, true);
        $aryMember['Events_ID'] = $_SESSION['E_Events_ID'];
        $jsonMember = json_encode($aryMember);
        
        $db = openDatabaseConnection();
        echo Members_db::insertMemberInDb($jsonMember, $db);
        closeDatabaseConnection($db);
        break;
    
    case 'insertNewResource':
        $jsonResource = trim(filter_input(INPUT_POST, 'jsonResource', FILTER_UNSAFE_RAW));              
        $aryResource = json_decode($jsonResource, true);
        $aryResource['Events_ID'] = $_SESSION['E_Events_ID'];
        $jsonResource = json_encode($aryResource);
        
        $db = openDatabaseConnection();
        echo Resources_db::insertResourceInDb($jsonResource, $db);
        closeDatabaseConnection($db);
        break;
    
    case 'updateEventSelections':
        $jsonEvent = trim(filter_input(INPUT_POST, 'jsonEvent', FILTER_UNSAFE_RAW));  

        $db = openDatabaseConnection();
        echo General_Event_db::updateEventInDb($jsonEvent, $db);
        closeDatabaseConnection($db);
        break;
    
    case 'updateMemberSelections':
        $jsonSelectedMemberIDs = trim(filter_input(INPUT_POST, 'jsonSelectedMemberIDs', FILTER_UNSAFE_RAW));              
        $db = openDatabaseConnection();
        echo Members_db::updateMemberSelectionsInDb($jsonSelectedMemberIDs, $_SESSION['E_Events_ID'], $db);
        closeDatabaseConnection($db);
        break;
    
    case 'updateResourceSelections':
        $jsonSelectedResourceIDs = trim(filter_input(INPUT_POST, 'jsonSelectedResourceIDs', FILTER_UNSAFE_RAW));              
        $db = openDatabaseConnection();
        echo Resources_db::updateResourceSelectionsInDb($jsonSelectedResourceIDs, $_SESSION['E_Events_ID'], $db);
        closeDatabaseConnection($db);
        break;
    
}//End switch.