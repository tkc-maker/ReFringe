<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
UserInfo::checkLogin("SuperUser");


require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/general.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/roles_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/roles_ui.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'displayRolesScreen':
        roles_ui::rolesDisplay();
        break;
        
    case 'displayAllRoles':
        $db = openDatabaseConnection();
        $aryRoles = roles_db::getAllRolesFromDb($db);
        if($aryRoles !== false){
            echo json_encode($aryRoles, true);
        }else{
            echo [];
        }
        closeDatabaseConnection($db);
        break;
        
    case 'saveCheckedRows':
        $jsonCheckedRows = trim(filter_input(INPUT_POST, 'jsonCheckedRows', FILTER_UNSAFE_RAW));
        $db = openDatabaseConnection();
        echo roles_db::updateSelectedRoles($db, $jsonCheckedRows);
        closeDatabaseConnection($db);
        break;
        
    case 'deleteCheckedRows':
        $jsonCheckedRows = trim(filter_input(INPUT_POST, 'jsonCheckedRows', FILTER_UNSAFE_RAW));
        $db = openDatabaseConnection();
        echo roles_db::deleteSelectedRoles($db, $jsonCheckedRows);
        closeDatabaseConnection($db);
        break;
    
    case 'saveNewRole':
        $newRole = trim(htmlspecialchars($_POST['newRole'], ENT_QUOTES, 'UTF-8'));
        $db = openDatabaseConnection();
        echo roles_db::insertNewRole($db, $newRole);
        closeDatabaseConnection($db);
        break;
        
    default:
        roles_ui::rolesDisplay();
        break;
}