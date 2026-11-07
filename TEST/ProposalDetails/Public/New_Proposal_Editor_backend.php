<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
UserInfo::checkLogin("SuperUser, Organizer, Proposer");


require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/mail_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/general.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/Proposal_Details_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/questions_answers_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/questions_answers_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ScreenFieldValidation/Utilities/Screenfield_validation_db.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){

    case 'displayQuestionAnswerBlocks':
        $Proposal_details_ID = filter_input(INPUT_POST, 'Proposal_details_ID', FILTER_SANITIZE_NUMBER_INT);
        $question_classes = trim(filter_input(INPUT_POST, 'question_classes', FILTER_UNSAFE_RAW));
        $ary_question_classes = json_decode($question_classes, true);
        $db = openDatabaseConnection();

        $fetchResults = questions_answers_db::getProposalQuestionsAnswersFromDb(
                    $Proposal_details_ID, $ary_question_classes[0]['ID'], $db);
        
        $aryFetchResults = json_decode($fetchResults, true);
        if($aryFetchResults['status'] === false){
            echo $aryFetchResults['errMsg'];
        }                
        $aryFetchResults['Question_classes_ID'] = $ary_question_classes[0]['ID'];     
        insertAnswerValue('First Name', $_SESSION['first_name'], $aryFetchResults);        
        insertAnswerValue('Last Name', $_SESSION['last_name'], $aryFetchResults);
        insertAnswerValue('Phone', $_SESSION['phone'], $aryFetchResults);
        insertAnswerValue('Email', $_SESSION['email'], $aryFetchResults);
        insertAnswerValue('Zip', $_SESSION['zip'], $aryFetchResults);

        echo questions_answers_ui::displayQuestionsAnswersForNewProposal(json_encode([$aryFetchResults], JSON_NUMERIC_CHECK));
        closeDatabaseConnection($db);
        break;
        
    case 'saveQuestionAnswerBlocks':
        $question_classes = trim(filter_input(INPUT_POST, 'question_classes', FILTER_UNSAFE_RAW)); 
        $jsonQuickEasyNewProposal = trim(filter_input(INPUT_POST, 'jsonQuickEasyNewProposal', FILTER_UNSAFE_RAW));    
        
        $aryQuickEasyNewProposal = json_decode($jsonQuickEasyNewProposal, true);
        $Question_classes_ID = extractAnswerValue('Question_classes_ID', $aryQuickEasyNewProposal);
        $questions_answers = extractAnswerValue('questions_answers', $aryQuickEasyNewProposal);
        
        $firstName = extractAnswerValue('First Name', $aryQuickEasyNewProposal);
        $lastName = extractAnswerValue('Last Name', $aryQuickEasyNewProposal);
        $email = extractAnswerValue('Email field', $aryQuickEasyNewProposal);
        $proposalTitle = extractAnswerValue('Proposal Title', $aryQuickEasyNewProposal);
        
        $db = openDatabaseConnection();
        
        //Get a "Proposal_details_ID" (Notice that the "NoLogin" db method is used):
        $Users_ID = $_SESSION['ID'];
        $results_2 = Proposal_Details_db::insertQuickEasyNoLoginProposalDetails($Users_ID, $proposalTitle, $question_classes, $db);
        if(!is_numeric($results_2)){
            closeDatabaseConnection($db);
            echo $results_2;
            break;
        }
        $Proposal_details_ID = (int)$results_2;        
        //Save the "questions_answers" block (Notice that the "NoLogin" db method is used):
        $results_3 = questions_answers_db::insertQuickEasyNoLoginQuestionsAnswers($Proposal_details_ID, 
                $Question_classes_ID, $questions_answers, $db);
        closeDatabaseConnection($db);
        
        $aryResults_3 = json_decode($results_3, true);
        
        if($aryResults_3['status'] === 'true'){
            //Email a Proposal Confirmation:
            $results_3mail = prepareAndSendConfirmationEmail($firstName, $lastName, $email, $proposalTitle, 
                    $aryQuickEasyNewProposal[0]['questions_answers']);            
            $aryResults_3mail = json_decode($results_3mail, true);
            //Build new "errMsg" (which may be entirely positive):
            $aryResults_3['errMsg'] .= ' ' . $aryResults_3mail['errMsg'];
            $aryResults_3['status'] = $aryResults_3mail['status'];
            $results_3 = json_encode($aryResults_3);
        }
        echo $results_3;
        break;
    
    case 'getAllScreenfieldValidations':
        $db = openDatabaseConnection();
        echo Screenfield_validation_db::getAllScreenfieldValidationsFromDb($db);
        closeDatabaseConnection($db);        
        break;
    
}//End "switch".

/**   Searches the passed-in questions/answers array for the question containing
 * the passed-in partial string. If exactly one match is found, then the
 * corresponding "answer" value is returned. Otherwise, false is returned.
 * 
 * Note: This function can also return the "Question_classes_ID" and all
 *       "questions_answers" for the form.
 * 
 * @param string $part -- The "question" part or keyword.
 * @param assoc. array $aryQuickEasyNewProposal -- The array to search.
 * @return bool/string -- Either the "answer" value of a match, or
 *     the boolean, false, if no match was found or there is more than one
 *     match.
 */
function extractAnswerValue($part, $aryQuickEasyNewProposal){
    if($part === 'Question_classes_ID'){
       return $aryQuickEasyNewProposal[0]['Question_classes_ID'];
       
    }elseif($part === 'questions_answers') {
       return json_encode($aryQuickEasyNewProposal[0]['questions_answers']); 
    }
    
    $matches = array_filter(
        $aryQuickEasyNewProposal[0]['questions_answers'],
        fn($qa) => stripos($qa['question'], $part) !== false
    );

    if (count($matches) === 1) {
        //Point to first element, regardless of key:
        $firstMatch = reset($matches);
        $result = $firstMatch['answer'];
        return $result;
    }    
    return false;
}

/**   Searches the passed-in database fetch results of the Quick Easy template
 * for the "$questionPart". If found, places "$answer" in the 'answer' field.
 * Returns the fetch results by side-effect.
 * 
 * Notice the & in the signature and in the "foreach" statement.
 * 
 * @param String $questionPart
 * @param String $answer
 * @param assoc. array $aryFetchResults
 */
function insertAnswerValue($questionPart, $answer, &$aryFetchResults){
    $aryQuestionsAnswers = json_decode($aryFetchResults['questionsAnswers'], true);
    
    foreach($aryQuestionsAnswers as &$questionAnswer){
        if(str_contains($questionAnswer['question'], $questionPart)){
            $questionAnswer['answer'] = $answer;
            unset($questionAnswer);
            break;
        }
        unset($questionAnswer);
    }
    $aryFetchResults['questionsAnswers'] = json_encode($aryQuestionsAnswers);
}
    
/**   Sends an email with the parameter settings below. 
 * Returns a JSON string indicating the status of the emailing.
 * 
 * @param string $firstName
 * @param string $lastName
 * @param string $email
 * @param string $proposalTitle
 * @param assoc. array $aryQuestionsAnswers -- An array of "question" / "answer" pairs.
 * @return JSON string -- A "status" (either "true" or "false"), 
 *    and an "errMsg" (which may be entirely positive).
 */
function prepareAndSendConfirmationEmail($firstName, $lastName, $email, $proposalTitle, $aryQuestionsAnswers){
    $subject = 'Confirmation of Proposal Submission to ReFringe';
    $body = "<p>Dear $firstName $lastName:</p><p>Your proposal titled, \"$proposalTitle\" has been submitted successfully to ReFringe. ".
            "We will contact you by phone and email soon. Should you have questions or concerns, please respond to this ".
            "email. The proposal idea that you submitted is listed below.</p><p>".
            "Thank You,<br>The ReFringe Management<br>";
    foreach($aryQuestionsAnswers as $questionAnswer){
        $body .= '<br>Q: '.$questionAnswer['question'].'<br>A:&nbsp;&nbsp;&nbsp;&nbsp;'.$questionAnswer['answer'];
    }
    $body .= '</p>';
    $altBody = $body;
    
    return emailMessage($email, $subject, $body, $altBody, true);
}