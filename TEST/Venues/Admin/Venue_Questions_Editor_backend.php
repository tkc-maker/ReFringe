<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
UserInfo::checkLogin("SuperUser");


require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Venues/Utilities/Venue_Questions_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Venues/Utilities/Venue_Questions_db.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'displayVenueQuestionsScreen':
        echo Venue_Questions_ui::displayVenueQuestionsEditorScreen();
        break;
    
    case 'loadVenueQuestions':
        $db = openDatabaseConnection();
        $jsonResults = Venue_Questions_db::loadVenueQuestionsFromDb($db);
        $objResults = json_decode($jsonResults, true);
        if($objResults["status"] == "false"){
            echo Venue_Questions_db::insertTemplateVenuesRecordIntoDb($db);
        }else{
            echo $jsonResults;
        }
        closeDatabaseConnection($db);
        break;
    
    case 'updateQuestion':
        $ordinal = filter_input(INPUT_POST, 'ordinal', FILTER_SANITIZE_NUMBER_INT);
        $updatedQuestion = trim(htmlspecialchars($_POST['question'], ENT_QUOTES, 'UTF-8'));
        $db = openDatabaseConnection();
        echo Venue_Questions_db::updateVenueQuestionInDb($db, $ordinal, $updatedQuestion);
        closeDatabaseConnection($db);
        break;
    
    case 'deleteQuestion':
        $ordinal = filter_input(INPUT_POST, 'ordinal', FILTER_SANITIZE_NUMBER_INT);
        $db = openDatabaseConnection();
        echo Venue_Questions_db::deleteVenueQuestionInDb($db, $ordinal);
        closeDatabaseConnection($db);
        break;
    
    case 'addNewQuestion':
        $new_question = trim(filter_input(INPUT_POST, 'new_question', FILTER_UNSAFE_RAW));
        $db = openDatabaseConnection();
        echo Venue_Questions_db::addVenueQuestionInDb($db, $new_question);
        closeDatabaseConnection($db);
        break;
    
    case 'moveQuestionUpDown':
        $ordinal = filter_input(INPUT_POST, 'ordinal', FILTER_SANITIZE_NUMBER_INT) - 1; //The array used is zero-based.
        $direction = trim(htmlspecialchars($_POST['direction'], ENT_QUOTES, 'UTF-8'));
        $db = openDatabaseConnection();
        echo Venue_Questions_db::moveVenueQuestionUpDown($db, $ordinal, $direction);
        closeDatabaseConnection($db);
        break;
        
    default:
        echo Venue_Questions_ui::displayVenueQuestionsEditorScreen();
        break;
  
}