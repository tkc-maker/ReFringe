<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
UserInfo::checkLogin("SuperUser, Organizer, Proposer");


require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/general.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/Proposal_Details_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/Proposal_Details_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/question_classes_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/question_classes_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/genres_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/genres_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/questions_answers_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/questions_answers_ui.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'displayProposalDetails':
        $_SESSION['PD_Proposal_details_ID'] = filter_input(INPUT_POST, 'Proposal_details_ID', FILTER_SANITIZE_NUMBER_INT);
        $_SESSION['PD_Users_ID'] = filter_input(INPUT_POST, 'Users_ID', FILTER_SANITIZE_NUMBER_INT);
        
        $db = openDatabaseConnection();
        echo Proposal_Details_ui::displayProposalDetails($db);
        closeDatabaseConnection($db);
        break;
    
    case 'selectQuestionClass':
        $selectedQuestion_classes_ID = filter_input(INPUT_POST, 'selectedQuestion_classes_ID', FILTER_SANITIZE_NUMBER_INT);
        $selectedQuestion_classes_name = trim(htmlspecialchars($_POST['selectedQuestion_classes_name'], ENT_QUOTES, 'UTF-8'));
        $title = trim(htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8'));
        
        $db = openDatabaseConnection();
        echo submitQuestionClassDropdown_db_ui($title, $selectedQuestion_classes_ID, $selectedQuestion_classes_name, $db);            
        closeDatabaseConnection($db);
        break;
    
    case 'clearSelectedQuestionClasses':
        $title = trim(htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8'));
        $db = openDatabaseConnection();
        if(Proposal_Details_db::clearSelectedQuestionClassesFromDb($_SESSION['PD_Proposal_details_ID'], $title, $db)){
            unset($_SESSION['PD_selectedQuestionClasses']);
            echo Proposal_Details_ui::displayProposalDetails($db);
        }
        closeDatabaseConnection($db);
        break;
    
    case 'selectGenre':
        $selectedGenres_ID = filter_input(INPUT_POST, 'selectedGenres_ID', FILTER_SANITIZE_NUMBER_INT);
        $selectedGenres_name = trim(htmlspecialchars($_POST['selectedGenres_name'], ENT_QUOTES, 'UTF-8'));
        $title = trim(htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8'));
        
        $db = openDatabaseConnection();
        echo submitGenreDropdown_db_ui($title, $selectedGenres_ID, $selectedGenres_name, $db);
        closeDatabaseConnection($db);
        break;
    
    case 'clearSelectedGenres':
        $db = openDatabaseConnection();
        $title = trim(htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8'));
        if(Proposal_Details_db::clearSelectedGenresFromDb($_SESSION['PD_Proposal_details_ID'], $title, $db)){
            unset($_SESSION['PD_selectedGenres']);
            echo Proposal_Details_ui::displayProposalDetails($db);
        }
        closeDatabaseConnection($db);
        break;

    case 'showSelectedQuestions':
        $selectedQuestion_classes_ID = filter_input(INPUT_POST, 'selectedQuestion_classes_ID', FILTER_SANITIZE_NUMBER_INT);
        $_SESSION['PD_selectedQuestion_classes_ID'] = $selectedQuestion_classes_ID;
        $Proposal_details_ID = $_SESSION['PD_Proposal_details_ID'];

        $db = openDatabaseConnection();
        $jsonQuestionsAnswers = questions_answers_db::getProposalQuestionsAnswersFromDb($Proposal_details_ID, $selectedQuestion_classes_ID, $db);
        $aryQuestionsAnswers = json_decode($jsonQuestionsAnswers, true);
        if($aryQuestionsAnswers !== false){
            echo questions_answers_ui::displaySelectedQuestionsAnswers($aryQuestionsAnswers);
        }
        closeDatabaseConnection($db);
        break;
    
    case 'saveTitleAnswers':
        $Proposal_details_ID = $_SESSION['PD_Proposal_details_ID'];
        $Question_classes_ID = $_SESSION['PD_selectedQuestion_classes_ID'];
        $title = trim(htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8'));
        $jsonQuestionsAnswers = trim(filter_input(INPUT_POST, 'jsonQuestionsAnswers', FILTER_UNSAFE_RAW));

        $db = openDatabaseConnection();
        $results = questions_answers_db::updateProposalAnswersInDb($Proposal_details_ID, $Question_classes_ID, 
                $title, $jsonQuestionsAnswers, $db);
        echo $results;
        closeDatabaseConnection($db);
        break;
        
    default:
        //Clear dropdown SESSION variables on initial page load:
        unset($_SESSION['PD_selectedQuestionClasses']);        
        unset($_SESSION['PD_selectedGenres']);        
        break;
}


function submitQuestionClassDropdown_db_ui($title, $selectedQuestion_classes_ID, $selectedQuestion_classes_name, $db){
   if(empty($_SESSION['PD_selectedQuestionClasses']) ||
       general::findNameInArray($_SESSION['PD_selectedQuestionClasses'], $selectedQuestion_classes_name) === false){
        //Append the selected question class to an array in SESSION:
        $selectedQuestion = ['ID' => $selectedQuestion_classes_ID, 'name' => $selectedQuestion_classes_name];
        $_SESSION['PD_selectedQuestionClasses'][] = $selectedQuestion;
        //Update the Question-Classes in the db:
        Proposal_Details_db::updateSelectedQuestionClassesInDb($_SESSION['PD_Proposal_details_ID'], $title,
            $_SESSION['PD_selectedQuestionClasses'], $db);
    }
    //Reload the screen from the db:
    return Proposal_Details_ui::displayProposalDetails($db);
}

function submitGenreDropdown_db_ui($title, $selectedGenres_ID, $selectedGenres_name, $db){
    if(empty($_SESSION['PD_selectedGenres']) ||
       general::findNameInArray($_SESSION['PD_selectedGenres'], $selectedGenres_name) === false){
        //Append the selected genre to an array in SESSION:
        $selectedGenre = ['ID' => $selectedGenres_ID, 'name' => $selectedGenres_name];
        $_SESSION['PD_selectedGenres'][] = $selectedGenre;
        //Update the Question-Classes in the db:
        Proposal_Details_db::updateSelectedGenresInDb($_SESSION['PD_Proposal_details_ID'], $title,
            $_SESSION['PD_selectedGenres'], $db);
    }
    //Reload the screen from the db:
    return Proposal_Details_ui::displayProposalDetails($db);
}