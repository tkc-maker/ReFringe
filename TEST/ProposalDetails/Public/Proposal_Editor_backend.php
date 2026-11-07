<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
UserInfo::checkLogin("SuperUser, Organizer, Proposer");


require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/general.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/Proposal_Details_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/Proposal_Details_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/question_classes_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/question_classes_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/questions_answers_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/questions_answers_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ScreenFieldValidation/Utilities/Screenfield_validation_db.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'displayProposalDetails':
        echo Proposal_Details_ui::displayProposalDetailsScreen();        
        break;
    
    case 'displayAllQuestionClasses':
        $db = openDatabaseConnection();
        echo json_encode(Question_classes_db::getAllQuestionClassesFromDb($db));
        closeDatabaseConnection($db);
        break;
    
    case 'displayTitleAndSelections':
        $Proposal_details_ID = filter_input(INPUT_POST, 'Proposal_details_ID', FILTER_SANITIZE_NUMBER_INT);
        $Users_ID = filter_input(INPUT_POST, 'Users_ID', FILTER_SANITIZE_NUMBER_INT);
        $db = openDatabaseConnection();
        $results = Proposal_Details_db::getProposalDetailsFromDb($Proposal_details_ID, $Users_ID, $db);
        
        echo $results;
        closeDatabaseConnection($db);
        break;
    
    case 'displayQuestionAnswerBlocks':
        $Proposal_details_ID = filter_input(INPUT_POST, 'Proposal_details_ID', FILTER_SANITIZE_NUMBER_INT);
        $title = trim(htmlspecialchars($_POST['title'].'', ENT_QUOTES, 'UTF-8'));
        $jsonSelectedQuestionClasses = trim(filter_input(INPUT_POST, 'jsonSelectedQuestionClasses', FILTER_UNSAFE_RAW).'');

        $db = openDatabaseConnection();
        $jsonSaveResults = Proposal_Details_db::updateProposalDetailsInDb($Proposal_details_ID, $title, $jsonSelectedQuestionClasses, $db);
        $saveResults = json_decode($jsonSaveResults, true);
        //We aren't going to return anything if this fails, because the "UDT" field is minor and non-mission-critical:
        Proposal_Details_db::updateProposalDetailsDateTimeInDb($Proposal_details_ID, $db);
        
        if($saveResults['status'] === false){
            echo $saveResults['errMsg'];
            
        }else{
            $jsonFetchResults = questions_answers_db::fetchProposalQuestionsAnswersForAllQuestionClassesFromDb(
                    $Proposal_details_ID, $jsonSelectedQuestionClasses, $db);
            $fetchResults = json_decode($jsonFetchResults, true);
            
            
            $jsonAllSelectedQuestionsAnswers = json_encode($fetchResults['aryQuestionsAnswers']);
            echo questions_answers_ui::displaySelectedQuestionsAnswersForSelectedQuestionClasses($jsonAllSelectedQuestionsAnswers);
            if($fetchResults['status'] === false){
                echo $fetchResults['errMsg'];
            }                
        }
        closeDatabaseConnection($db);
        break;
        
    case 'displayQuestionAnswerBlocksWithoutSavingAnything':
        $Proposal_details_ID = filter_input(INPUT_POST, 'Proposal_details_ID', FILTER_SANITIZE_NUMBER_INT);
        $jsonSelectedQuestionClasses = trim(filter_input(INPUT_POST, 'jsonSelectedQuestionClasses', FILTER_UNSAFE_RAW).'');
        
        $db = openDatabaseConnection();
        $jsonFetchResults = questions_answers_db::fetchProposalQuestionsAnswersForAllQuestionClassesFromDb(
                $Proposal_details_ID, $jsonSelectedQuestionClasses, $db);
        $fetchResults = json_decode($jsonFetchResults, true);           
        $jsonAllSelectedQuestionsAnswers = json_encode($fetchResults['aryQuestionsAnswers']);
        echo questions_answers_ui::displaySelectedQuestionsAnswersForSelectedQuestionClasses($jsonAllSelectedQuestionsAnswers);
        if($fetchResults['status'] === false){
            echo $fetchResults['errMsg'];
        }                
        closeDatabaseConnection($db);
        break;
    
    case 'saveQuestionAnswerBlocks':
        $Proposal_details_ID = filter_input(INPUT_POST, 'Proposal_details_ID', FILTER_SANITIZE_NUMBER_INT);
        $jsonClassesQuestionsAnswers = trim(filter_input(INPUT_POST, 'jsonClassesQuestionsAnswers', FILTER_UNSAFE_RAW));      
        
        $db = openDatabaseConnection();
        echo questions_answers_db::updateProposalAnswersForAllQuestionClassesInDb(
                $Proposal_details_ID, $jsonClassesQuestionsAnswers, $db);
        //We aren't going to return anything if this fails, because the "UDT" field is minor and non-mission-critical:
        Proposal_Details_db::updateProposalDetailsDateTimeInDb($Proposal_details_ID, $db);
        closeDatabaseConnection($db);
        break;
    
    case 'getAllScreenfieldValidations':
        $db = openDatabaseConnection();
        echo Screenfield_validation_db::getAllScreenfieldValidationsFromDb($db);
        closeDatabaseConnection($db);        
        break;
    
}//End "switch".
