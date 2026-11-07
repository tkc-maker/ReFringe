<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';


require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/mail_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/general.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Login/Utilities/Login_db.php';
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
        $aryFetchResults['Question_classes_name'] = $ary_question_classes[0]['name'];

        echo questions_answers_ui::displayQuestionsAnswersForNoLoginProposal(json_encode([$aryFetchResults], JSON_NUMERIC_CHECK));
        closeDatabaseConnection($db);
        break;
        
    case 'saveQuestionAnswerBlocks':
        $question_classes = trim(filter_input(INPUT_POST, 'question_classes', FILTER_UNSAFE_RAW)); 
        $jsonQuickEasyNoLoginProposal = trim(filter_input(INPUT_POST, 'jsonQuickEasyNoLoginProposal', FILTER_UNSAFE_RAW));    
        
        $aryQuickEasyNoLoginProposal = json_decode($jsonQuickEasyNoLoginProposal, true);
        $Question_classes_ID = extractAnswerValue('Question_classes_ID', $aryQuickEasyNoLoginProposal);
        $questions_answers = extractAnswerValue('questions_answers', $aryQuickEasyNoLoginProposal);
        $firstName = extractAnswerValue('First Name', $aryQuickEasyNoLoginProposal);
        $lastName = extractAnswerValue('Last Name', $aryQuickEasyNoLoginProposal);
        $email = extractAnswerValue('Email field', $aryQuickEasyNoLoginProposal);
        $phone = extractAnswerValue('Phone Number field', $aryQuickEasyNoLoginProposal);
        $proposalTitle = extractAnswerValue('Proposal Title', $aryQuickEasyNoLoginProposal);
        
        $db = openDatabaseConnection();
        
        //Get a "Users_ID":
        $results_1 = Login_db::enterNoLoginUserInfoSubmit($firstName, $lastName, $email, $phone, $db);
error_log('Login: '.$results_1);        
        if(!is_numeric($results_1)){
            closeDatabaseConnection($db);
            echo $results_1;
            break;
        }
        $Users_ID = (int)$results_1;
        //Get a "Proposal_details_ID":
        $results_2 = Proposal_Details_db::insertQuickEasyNoLoginProposalDetails($Users_ID, $proposalTitle, $question_classes, $db);
error_log('Proposal: '.$results_2);        
        if(!is_numeric($results_2)){
            closeDatabaseConnection($db);
            echo $results_2;
            break;
        }
        $Proposal_details_ID = (int)$results_2;        
        //Save the "questions_answers" block:
        $results_3 = questions_answers_db::insertQuickEasyNoLoginQuestionsAnswers($Proposal_details_ID, 
                $Question_classes_ID, $questions_answers, $db);
error_log('Q&A: '.$results_1);        
        closeDatabaseConnection($db);
        
        $aryResults_3 = json_decode($results_3, true);
        
        if($aryResults_3['status'] === 'true'){
            //Email a Proposal Confirmation:
            $results_3mail = prepareAndSendConfirmationEmail($firstName, $lastName, $email, $proposalTitle, 
                    $aryQuickEasyNoLoginProposal[0]['questions_answers']);            
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
 * @param assoc. array $aryQuickEasyNoLoginProposal -- The array to search.
 * @return bool/string -- Either the "answer" value of a match, or
 *     the boolean, false, if no match was found or there is more than one
 *     match.
 */
function extractAnswerValue($part, $aryQuickEasyNoLoginProposal){
    if($part === 'Question_classes_ID'){
       return $aryQuickEasyNoLoginProposal[0]['Question_classes_ID'];
       
    }elseif($part === 'questions_answers') {
       return json_encode($aryQuickEasyNoLoginProposal[0]['questions_answers']); 
    }
    
    $matches = array_filter(
        $aryQuickEasyNoLoginProposal[0]['questions_answers'],
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
            "email. The proposal idea that you submitted is listed below.</p><p>Also, try logging into our system. ".
            "Remember that your username is the \"Last Name\" you entered. And your password is your \"Email\" address, as you entered it. ".
            "After logging in, you will see a menu where you can edit your submission, ".
            "and change your password. (We strongly suggest that you change it to something more secure soon. ".
            "But, for now, you can login with this prepopulated form: ".
            "<a href=\"https://refringe.org/TEST/Login/Login.php?u=".urlencode($lastName)."&p=".
            urlencode($email)."\">ReFringe.org</a>.)</p><p>".
           
            "Thank You,<br>The ReFringe Management<br>";
    foreach($aryQuestionsAnswers as $questionAnswer){
        $body .= '<br>Q: '.$questionAnswer['question'].'<br>A:&nbsp;&nbsp;&nbsp;&nbsp;'.$questionAnswer['answer'];
    }
    $body .= '</p>';
    $altBody = $body;
    
    return emailMessage($email, $subject, $body, $altBody, true);
}