<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
UserInfo::checkLogin("SuperUser, Organizer");


require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Venues/Utilities/AllVenues_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Venues/Utilities/AllVenues_db.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'displayAllVenues':
        $db = openDatabaseConnection();
        echo AllVenues_ui::displayAllVenues($db);
        closeDatabaseConnection($db);
        break;
    
    
    case 'deleteVenue':
        $Venues_ID = filter_input(INPUT_POST, 'Venues_ID', FILTER_SANITIZE_NUMBER_INT);
        $db = openDatabaseConnection();
        $jsonResults = AllVenues_db::deleteVenueFromDb($Venues_ID, $db);
        $aryResults = json_decode($jsonResults, true);
        
        if($aryResults['status'] === "true"){
            echo AllVenues_ui::displayAllVenues($db);
            
        }else{
            echo $jsonResults;
        }
        
        closeDatabaseConnection($db);
        break;
        
    case 'saveVenueChecks':
        $jsonVenueChecks = trim(filter_input(INPUT_POST, 'jsonVenueChecks', FILTER_UNSAFE_RAW));
        $aryVenueChecks = json_decode($jsonVenueChecks, true);

        $db = openDatabaseConnection();
        $strErrMsg = '';
        $strStatus = 'true';
        foreach($aryVenueChecks as $venueCheckItem) {
            $Venues_ID = $venueCheckItem['Venues_ID'];
            $isChecked = $venueCheckItem['isChecked'] === true? 1: 0;
            $jsonResults = AllVenues_db::saveVenueChecksInDb($Venues_ID, $isChecked, $db);
            $aryResults = json_decode($jsonResults, true);
            
            if($aryResults['status'] === 'false'){
                $strErrMsg .= "\n".$aryResults['errMsg'];
                $strStatus = 'false';
            }
        }
        echo json_encode(["status" => $strStatus, "errMsg" => $strErrMsg]);
         
        closeDatabaseConnection($db);
        break;
        
    default:
        $db = openDatabaseConnection();
        echo AllVenues_ui::displayAllVenues($db);
        closeDatabaseConnection($db);
        break;
}
