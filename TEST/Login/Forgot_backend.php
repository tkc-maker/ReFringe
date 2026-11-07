<?PHP
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Login/Utilities/Login_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Login/Utilities/Login_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/mail_utilities.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'forgotUsernameDisplay':
        login_ui::forgotUsernameDisplay();
        break;
    
    case 'forgotUsernameSubmit':
        $first_name = trim(htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8'));
        $last_name = trim(htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8'));
        $password = trim(htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8'));
        $db = openDatabaseConnection();
        Login_db::forgotUsernameSubmit($first_name, $last_name, $password, $db);
        closeDatabaseConnection($db);
        break;    
    
    case 'forgotPasswordDisplay':
        login_ui::forgotPasswordDisplay();
        break;
    
    case 'forgotPasswordSubmit':
        $first_name = trim(htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8'));
        $last_name = trim(htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8'));
        $username = trim(htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'));
        $db = openDatabaseConnection();
        Login_db::forgotPasswordSubmit($first_name, $last_name, $username, $db);
        closeDatabaseConnection($db);
        break;
}
