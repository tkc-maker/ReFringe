<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
UserInfo::checkLogin("SuperUser");


require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/general.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ScreenFieldValidation/Utilities/Screenfield_validation_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ScreenFieldValidation/Utilities/Screenfield_validation_db.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'displayScreenfieldValidationScreen':
        Screenfield_validation_ui::screenfieldValidationDisplay();
        break;
        
    case 'displayAllScreenfieldValidations':
        $db = openDatabaseConnection();
        echo Screenfield_validation_db::getAllScreenfieldValidationsFromDb($db);
        closeDatabaseConnection($db);
        break;
        
    case 'saveCheckedRows':
        $jsonCheckedRows = trim(filter_input(INPUT_POST, 'jsonCheckedRows', FILTER_UNSAFE_RAW));
        $db = openDatabaseConnection();
        echo Screenfield_validation_db::updateSelectedScreenfieldValidations($db, $jsonCheckedRows);
        closeDatabaseConnection($db);
        break;
        
    case 'deleteCheckedRows':
        $jsonCheckedRows = trim(filter_input(INPUT_POST, 'jsonCheckedRows', FILTER_UNSAFE_RAW));
        $db = openDatabaseConnection();
        echo Screenfield_validation_db::deleteSelectedScreenfieldValidations($db, $jsonCheckedRows);
        closeDatabaseConnection($db);
        break;
    
    case 'saveNewScreenfieldValidation':
        $jsonNewScreenfieldValidation = trim(filter_input(INPUT_POST, 'jsonNewScreenfieldValidation', FILTER_UNSAFE_RAW));
        $db = openDatabaseConnection();
        echo Screenfield_validation_db::insertNewScreenfieldValidation($db, $jsonNewScreenfieldValidation);
        closeDatabaseConnection($db);
        break;
        
    default:
        Screenfield_validation_ui::screenfieldValidationDisplay();
        break;
}