<?PHP 
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser, Organizer, Proposer, Viewer");
?>
<!DOCTYPE html>

<?PHP

require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Utilities/General_db_utilities.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Login/Utilities/Login_db.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Login/Utilities/Login_Change_ui.php';
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Login/Utilities/Login_Change_db.php';

$mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));

switch($mode){
    case 'loginChangeDisplay':
        Login_Change_ui::loginChangeDisplay();
        break;
    
    case 'loginChangeSubmit':
        $usernameCurrent = trim(htmlspecialchars($_POST['usernameCurrent'], ENT_QUOTES, 'UTF-8'));
        $_SESSION['usernameCurrent'] = $usernameCurrent;
        $passwordCurrent = trim(htmlspecialchars($_POST['passwordCurrent'], ENT_QUOTES, 'UTF-8'));
        $_SESSION['passwordCurrent'] = $passwordCurrent;
        $usernameNew = trim(htmlspecialchars($_POST['usernameNew'], ENT_QUOTES, 'UTF-8'));
        $_SESSION['usernameNew'] = $usernameNew;
        $passwordNew = trim(htmlspecialchars($_POST['passwordNew'], ENT_QUOTES, 'UTF-8'));
        $_SESSION['passwordNew'] = $passwordNew;
        $_SESSION['rePasswordNew'] = $passwordNew;
        
        $db = openDatabaseConnection();
        Login_Change_db::loginChangeSubmit($usernameCurrent, $passwordCurrent, $usernameNew, $passwordNew, $db);
        closeDatabaseConnection($db);
        break;
    
    case 'editUserDisplay':
        $db = openDatabaseConnection();
        Login_Change_ui::editUserDisplay($db);
        closeDatabaseConnection($db);
        break;
    
    case 'editUserSubmit':
        $jsonEditUser = trim(filter_input(INPUT_POST, 'jsonEditUser', FILTER_UNSAFE_RAW));
        $db = openDatabaseConnection();
        Login_Change_db::editUserSubmit($jsonEditUser, $db);
        closeDatabaseConnection($db);
        break;
        
    default:
        Login_Change_ui::loginChangeDisplay();
        
//        quick_and_dirty_password_update(2, 'thecat'); //Current: (Samoht, ReTnEpRaC), (David, PaSsMe).
        break;
}


