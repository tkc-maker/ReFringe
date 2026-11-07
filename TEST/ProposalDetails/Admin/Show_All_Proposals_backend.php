<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
UserInfo::checkLogin("SuperUser, Organizer");


require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/general.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/Search_Proposals_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/Search_Proposals_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/Proposal_Details_db.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'showAllProposalFieldOptions':
        Search_Proposals_ui::All_Proposals_Fields_Display();
        break;
    
    case 'showAllProposalTitles':
        $db = openDatabaseConnection();
        $sqlSelect = Search_Proposals_db::showAllProposalsSQL();
        echo Search_Proposals_db::getProposalDetailsQuestionsFromDb($db, $sqlSelect, $assoc_cols = true);
        closeDatabaseConnection($db);
        break;
    case 'persistCheckboxState':
        $_SESSION['AP_chkRole'] = filter_input(INPUT_POST, 'chkRole', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $_SESSION['AP_chkFirst_Name'] = filter_input(INPUT_POST, 'chkFirst_Name', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $_SESSION['AP_chkLast_Name'] = filter_input(INPUT_POST, 'chkLast_Name', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $_SESSION['AP_chkPhone'] = filter_input(INPUT_POST, 'chkPhone', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $_SESSION['AP_chkEmail'] = filter_input(INPUT_POST, 'chkEmail', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $_SESSION['AP_chkTitle'] = filter_input(INPUT_POST, 'chkTitle', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $_SESSION['AP_chkCDT'] = filter_input(INPUT_POST, 'chkCDT', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $_SESSION['AP_chkUDT'] = filter_input(INPUT_POST, 'chkUDT', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);        
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
        
    case 'deleteProposalDetails':
        $Proposal_details_ID = filter_input(INPUT_POST, 'Proposal_details_ID', FILTER_SANITIZE_NUMBER_INT);
        $db = openDatabaseConnection();
        echo Proposal_Details_db::deleteProposalFromDb($Proposal_details_ID, $db);
        closeDatabaseConnection($db);
        break;
}