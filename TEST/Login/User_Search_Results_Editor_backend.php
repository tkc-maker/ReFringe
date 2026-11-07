<?PHP 
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser");
?>
<!DOCTYPE html>

<?PHP

require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Login/Utilities/Login_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Login/Utilities/Login_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Login/Utilities/User_Search_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Login/Utilities/User_Search_db.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){    
    case 'editUserDisplay':
        $varUsers_ID = filter_input(INPUT_POST, 'varUsers_ID', FILTER_SANITIZE_NUMBER_INT);
        $db = openDatabaseConnection();
        User_Search_db::getSessionFromDb($varUsers_ID, $db);
        User_Search_ui::editUserDisplay($db);
        closeDatabaseConnection($db);
        break;
    
    case 'editUserSubmit':
        $jsonEditUser = trim(filter_input(INPUT_POST, 'jsonEditUser', FILTER_UNSAFE_RAW));
        $db = openDatabaseConnection();
        User_Search_db::editUserSubmit($jsonEditUser, $db);
        closeDatabaseConnection($db);
        break;
        
    default:
        $db = openDatabaseConnection();
        User_Search_ui::editUserDisplay($db);
        closeDatabaseConnection($db);
        break;
}
