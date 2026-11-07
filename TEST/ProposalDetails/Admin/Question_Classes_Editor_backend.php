<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
UserInfo::checkLogin("SuperUser");


require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/general.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/question_classes_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/question_classes_ui.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'displayQuestionClassesScreen':
        question_classes_ui::questionClassesDisplay();
        break;
        
    case 'displayAllQuestionClasses':
        $db = openDatabaseConnection();
        $aryQuestionClasses = question_classes_db::getAllQuestionClassesFromDb($db);
        if($aryQuestionClasses !== false){
            echo json_encode($aryQuestionClasses, true);
        }else{
            echo [];
        }
        closeDatabaseConnection($db);
        break;
        
    case 'saveCheckedRows':
        $jsonCheckedRows = trim(filter_input(INPUT_POST, 'jsonCheckedRows', FILTER_UNSAFE_RAW));
        $db = openDatabaseConnection();
        echo question_classes_db::updateSelectedQuestionClasses($db, $jsonCheckedRows);
        closeDatabaseConnection($db);
        break;
        
    case 'deleteCheckedRows':
        $jsonCheckedRows = trim(filter_input(INPUT_POST, 'jsonCheckedRows', FILTER_UNSAFE_RAW));
        $db = openDatabaseConnection();
        echo question_classes_db::deleteSelectedQuestionClasses($db, $jsonCheckedRows);
        closeDatabaseConnection($db);
        break;
    
    case 'saveNewQuestionClass':
        $newQuestionClass = trim(htmlspecialchars($_POST['newQuestionClass'], ENT_QUOTES, 'UTF-8'));
        $db = openDatabaseConnection();
        echo question_classes_db::insertNewQuestionClass($db, $newQuestionClass);
        closeDatabaseConnection($db);
        break;
        
    default:
        question_classes_ui::questionClassesDisplay();
        break;

    
}