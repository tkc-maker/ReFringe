<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
UserInfo::checkLogin("SuperUser, Organizer");


require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/general.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/Search_Proposals_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/Search_Proposals_ui.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'displaySearchProposalScreen':
        Search_Proposals_ui::proposalSearchDisplay();
        break;
    
    case 'searchProposalsSubmit':
        $jsonSearchProposals = trim(filter_input(INPUT_POST, 'jsonSearchProposals', FILTER_UNSAFE_RAW));
        $db = openDatabaseConnection();
        $sqlSelect = Search_Proposals_db::buildSearchSQL($jsonSearchProposals);
        echo Search_Proposals_db::getProposalDetailsQuestionsFromDb($db, $sqlSelect);
        closeDatabaseConnection($db);
        break;

    case 'showAllProposalsSubmit':
        $db = openDatabaseConnection();
        $sqlSelect = Search_Proposals_db::showAllProposalsSQL();
        echo Search_Proposals_db::getProposalDetailsQuestionsFromDb($db, $sqlSelect);
        closeDatabaseConnection($db);
        break;        
        
    case 'clearSearchCriteria':
        Search_Proposals_db::clearCriteriaInSession();
        break;
    
    case 'editCheckedProposal':
        $_SESSION['PD_Proposal_details_ID'] = filter_input(INPUT_POST, 'Proposal_details_ID', FILTER_SANITIZE_NUMBER_INT);
        $_SESSION['PD_Users_ID'] = filter_input(INPUT_POST, 'Users_ID', FILTER_SANITIZE_NUMBER_INT);
        $_SESSION['PD_Role'] = trim(htmlspecialchars($_POST['Role'], ENT_QUOTES, 'UTF-8')); 
        $_SESSION['PD_First_Name'] = trim(htmlspecialchars($_POST['First_Name'], ENT_QUOTES, 'UTF-8'));
        $_SESSION['PD_Last_Name'] = trim(htmlspecialchars($_POST['Last_Name'], ENT_QUOTES, 'UTF-8'));
        $_SESSION['PD_Phone'] = trim(htmlspecialchars($_POST['Phone'], ENT_QUOTES, 'UTF-8'));
        $_SESSION['PD_Email'] = trim(htmlspecialchars($_POST['Email'], ENT_QUOTES, 'UTF-8'));
        break;
    
    case 'refineSearch':
        $_SESSION['SP_RefineSearch'] = trim(htmlspecialchars($_POST['refineSearch'], ENT_QUOTES, 'UTF-8'));
        break;
    
    default:        
        Search_Proposals_ui::proposalSearchDisplay();
        break;
}
