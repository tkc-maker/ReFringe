<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
UserInfo::checkLogin("SuperUser");

require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/general.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/genres_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/ProposalDetails/Utilities/genres_ui.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'displayGenresScreen':
        genres_ui::genresDisplay();
        break;
        
    case 'displayAllGenres':
        $db = openDatabaseConnection();
        $aryGenres = genres_db::getAllGenresFromDb($db);
        if($aryGenres !== false){
            echo json_encode($aryGenres, true);
        }else{
            echo [];
        }
        closeDatabaseConnection($db);
        break;
        
    case 'saveCheckedRows':
        $jsonCheckedRows = trim(filter_input(INPUT_POST, 'jsonCheckedRows', FILTER_UNSAFE_RAW));
        $db = openDatabaseConnection();
        echo genres_db::updateSelectedGenres($db, $jsonCheckedRows);
        closeDatabaseConnection($db);
        break;
        
    case 'deleteCheckedRows':
        $jsonCheckedRows = trim(filter_input(INPUT_POST, 'jsonCheckedRows', FILTER_UNSAFE_RAW));
        $db = openDatabaseConnection();
        echo genres_db::deleteSelectedGenres($db, $jsonCheckedRows);
        closeDatabaseConnection($db);
        break;
    
    case 'saveNewGenre':
        $newGenre = trim(htmlspecialchars($_POST['newGenre'], ENT_QUOTES, 'UTF-8'));
        $db = openDatabaseConnection();
        echo genres_db::insertNewGenre($db, $newGenre);
        closeDatabaseConnection($db);
        break;
        
    default:
        genres_ui::genresDisplay();
        break;
}