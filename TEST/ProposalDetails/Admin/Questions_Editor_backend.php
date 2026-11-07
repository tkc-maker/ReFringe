<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
UserInfo::checkLogin("SuperUser");


require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/general.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/Questions_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/Questions_db.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'displayQuestionsScreen':
        $db = openDatabaseConnection();
        echo Questions_ui::displayQuestionsEditorScreen($db);
        closeDatabaseConnection($db);
        break;
    
    case 'loadSelectedQuestions':
        $selectedQuestion_classes_ID = filter_input(INPUT_POST, 'Question_classes_ID', FILTER_SANITIZE_NUMBER_INT);
        $db = openDatabaseConnection();
        echo Questions_db::loadSelectedQuestionsFromDb($db, $selectedQuestion_classes_ID);
        closeDatabaseConnection($db);
        break;
    
    case 'updateQuestion':
        $selectedQuestion_classes_ID = filter_input(INPUT_POST, 'Question_classes_ID', FILTER_SANITIZE_NUMBER_INT);
        $ordinal = filter_input(INPUT_POST, 'ordinal', FILTER_SANITIZE_NUMBER_INT);
        $updatedQuestion = trim(htmlspecialchars($_POST['question'], ENT_QUOTES, 'UTF-8'));
        $db = openDatabaseConnection();
        echo Questions_db::updateQuestionInDb($db, $ordinal, $selectedQuestion_classes_ID, $updatedQuestion);
        closeDatabaseConnection($db);
        break;
    
    case 'deleteQuestion':
        $selectedQuestion_classes_ID = filter_input(INPUT_POST, 'Question_classes_ID', FILTER_SANITIZE_NUMBER_INT);
        $ordinal = filter_input(INPUT_POST, 'ordinal', FILTER_SANITIZE_NUMBER_INT);
        $db = openDatabaseConnection();
        echo Questions_db::deleteQuestionInDb($db, $ordinal, $selectedQuestion_classes_ID);
        closeDatabaseConnection($db);
        break;
    
    case 'addNewQuestion':
        $selectedQuestion_classes_ID = filter_input(INPUT_POST, 'Question_classes_ID', FILTER_SANITIZE_NUMBER_INT);
        $new_question = trim(filter_input(INPUT_POST, 'new_question', FILTER_UNSAFE_RAW));
        $db = openDatabaseConnection();
        echo Questions_db::addQuestionInDb($db, $selectedQuestion_classes_ID, $new_question);
        closeDatabaseConnection($db);
        break;
    
    case 'moveQuestionUpDown':
        $selectedQuestion_classes_ID = filter_input(INPUT_POST, 'Question_classes_ID', FILTER_SANITIZE_NUMBER_INT);
        $ordinal = filter_input(INPUT_POST, 'ordinal', FILTER_SANITIZE_NUMBER_INT) - 1; //The array used is zero-based.
        $direction = trim(htmlspecialchars($_POST['direction'], ENT_QUOTES, 'UTF-8'));
        $db = openDatabaseConnection();
        echo Questions_db::moveQuestionUpDown($db, $selectedQuestion_classes_ID, $ordinal, $direction);
        closeDatabaseConnection($db);
        break;
    
    case 'attachQuestionClass':
        $mainQuestion_classes_ID = filter_input(INPUT_POST, 'mainQuestion_classes_ID', FILTER_SANITIZE_NUMBER_INT);
        $attachQuestion_classes_ID = filter_input(INPUT_POST, 'attachQuestion_classes_ID', FILTER_SANITIZE_NUMBER_INT);
        $attachDirection = trim(htmlspecialchars($_POST['attachDirection'], ENT_QUOTES, 'UTF-8'));
        $db = openDatabaseConnection();
        echo Questions_db::attachQuestionClass($db, $mainQuestion_classes_ID, $attachQuestion_classes_ID, $attachDirection);
        closeDatabaseConnection($db);
        break;
        
    default:
        $db = openDatabaseConnection();
        echo Questions_ui::displayQuestionsEditorScreen($db);
        closeDatabaseConnection($db);
        break;

    
}