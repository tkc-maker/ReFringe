<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
UserInfo::checkLogin("SuperUser, Organizer, Proposer");


require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/general.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/MyProposals_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/MyProposals_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/Proposal_Details_db.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'displayMyProposals':
        $Users_ID = filter_input(INPUT_POST, 'Users_ID', FILTER_SANITIZE_NUMBER_INT);
        $db = openDatabaseConnection();
        echo MyProposals_ui::displayMyProposals($Users_ID, $db);
        closeDatabaseConnection($db);
        break;
    
    
    case 'deleteProposalDetails':
        $Users_ID = filter_input(INPUT_POST, 'Users_ID', FILTER_SANITIZE_NUMBER_INT);
        $Proposal_details_ID = filter_input(INPUT_POST, 'Proposal_details_ID', FILTER_SANITIZE_NUMBER_INT);
        $db = openDatabaseConnection();
        Proposal_Details_db::deleteProposalFromDb($Proposal_details_ID, $db);
        echo MyProposals_ui::displayMyProposals($Users_ID, $db);
        closeDatabaseConnection($db);
        break;
        
    default:
        
        break;
}
