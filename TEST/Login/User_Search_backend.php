<?PHP 
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser");
?>

<?PHP

require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Login/Utilities/Login_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Login/Utilities/Login_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Login/Utilities/User_Search_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Login/Utilities/User_Search_db.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'userSearchDisplay':
        $db = openDatabaseConnection();
        User_Search_ui::userSearchDisplay($db);
        closeDatabaseConnection($db);
        break;

    case 'searchUsersSubmit':
        $jsonSearchUsers = trim(filter_input(INPUT_POST, 'jsonSearchUsers', FILTER_UNSAFE_RAW));
        $db = openDatabaseConnection();
        User_Search_db::searchUsers($jsonSearchUsers, $db);
        closeDatabaseConnection($db);
        break;
    
    case 'deleteUser':
        $Users_ID = filter_input(INPUT_POST, 'Users_ID', FILTER_SANITIZE_NUMBER_INT);
        $db = openDatabaseConnection();
        User_Search_db::deleteUser($Users_ID, $db);
        closeDatabaseConnection($db);
        break;
        
    case 'clearCriteria':
        User_Search_db::clearCriteria();
        break;
    
    default:
        $db = openDatabaseConnection();
        User_Search_ui::userSearchDisplay($db);
        closeDatabaseConnection($db);
        break;
}
