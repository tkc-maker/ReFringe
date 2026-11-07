<?PHP 
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
?>

<?PHP

require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Login/Utilities/Login_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Login/Utilities/Login_ui.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'loginDisplay':
        UserInfo::clearUserInfo();
        Login_ui::loginDisplay();
        break;
        
    case 'loginSubmit':
        $username = trim(htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'));
        $password = trim(htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8'));
        $db = openDatabaseConnection();
        Login_db::loginSubmit($username, $password, $db);
        closeDatabaseConnection($db);
        break;
    
    case 'newUserDisplay':
        $db = openDatabaseConnection();
        Login_ui::newUserDisplay($db);
        closeDatabaseConnection($db);
        break;
    
    case 'newUserSubmit':
        $jsonNewUser = trim(filter_input(INPUT_POST, 'jsonNewUser', FILTER_UNSAFE_RAW));
        $db = openDatabaseConnection();
        Login_db::newUserSubmit($jsonNewUser, $db);
        closeDatabaseConnection($db);
        break;

    default:
        UserInfo::clearUserInfo();
        Login_ui::loginDisplay();
        break;
}

       