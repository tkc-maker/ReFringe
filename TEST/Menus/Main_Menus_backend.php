<?PHP 
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser, Organizer, Proposer, Viewer");
    
    $mode = trim(htmlspecialchars($_POST['mode'], ENT_QUOTES, 'UTF-8'));
    switch($mode){
        case 'menusDisplay':
            menusDisplay();
            break;
        
        default:
            menusDisplay();
            break;
    }
    
    function menusDisplay(){
        
        //Must place these + email into Session:
        $role = UserInfo::$Roles_name;
        $user = UserInfo::$first_name.' '.UserInfo::$last_name;
                
        switch ($role) {
            case 'SuperUser':
                menuSuperUser($user);
                break;
            case 'Organizer':
                menuOrganizer($user);
                break;
            case 'Proposer':
                menuProposer($user);
                echo '';
                break;
            case 'Viewer':
                menuViewer($user);
                break;
            default:
                menuViewer($user);
                break;
        }
    }
    
    function menuSuperUser($user){
        echo "
            <div id=\"divMenuSuperUser\">
                <h1 id=\"pMenuOrganizer\" class=\"menuHeading\">ReFringe.org SuperUser Menu
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href=\"\" class=\"logout\">Logout?</a></h1>
                <p class=\"welcome\">Welcome, $user</p>
                <p><input type=\"button\" id=\"btnEditQuestion_classes\" class=\"button\" 
                value=\"Edit Question_classes Table\"/></p>
                <p><input type=\"button\" id=\"btnEditRoles\" class=\"button\" 
                value=\"Edit Roles Table\"/></p>
                <p><input type=\"button\" id=\"btnEditGenres\" class=\"button\" 
                value=\"Edit Genres Table\"/></p>
                <p><input type=\"button\" id=\"btnEditScreenfieldValidations\" class=\"button\"
                value=\"Edit Screenfield_validation Table\"/></p>
                <p><input type=\"button\" id=\"btnSearchUsers\" class=\"button\" 
                value=\"Search Users\"/></p>
                <p><input type=\"button\" id=\"btnEditProposalTemplateQuestions\" class=\"button\"
                value=\"Edit Proposal Template Questions\"/></p>
                <p><input type=\"button\" id=\"btnEditVenueTemplateQuestions\" class=\"button\" 
                value=\"Edit Venue Template Questions\"/></p>
                <p><input type=\"button\" id=\"btnEditVenues\" class=\"button\" 
                value=\"Edit Venues\"/><br>
                --------------------------------------------------------------------------</p>
                <p><input type=\"button\" id=\"btnMyProposals\" class=\"button\" 
                value=\"My Proposals\"/></p>
                <p><input type=\"button\" id=\"btnSearchProposals\" class=\"button\" 
                value=\"Search Proposals\"/></p>
                <p><input type=\"button\" id=\"btnShowAllProposals\" class=\"button\" 
                value=\"Show All Proposals\"/></p>
                <p><input type=\"button\" id=\"btnEventsCalendar\" class=\"button\" 
                value=\"Events Calendar\"/></p>
                <p><input type=\"button\" id=\"btnChangeLoginOrUserInfo\" class=\"button\" 
                value=\"Change Username / Password or User Info\"/></p>
                <p><input type=\"button\" id=\"btnGoToBbs\" class=\"button\" 
                value=\"Go To BBS\"/></p>
            </div>
        ";
    }
    
    function menuOrganizer($user){
        echo "
            <div id=\"divMenuOrganizer\">
                <h1 id=\"pMenuOrganizer\" class=\"menuHeading\">ReFringe.org Organizer Menu
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href=\"\" class=\"logout\">Logout?</a></h1>
                <p class=\"welcome\">Welcome, $user</p>
                <p><input type=\"button\" id=\"btnMyProposals\" class=\"button\" 
                value=\"My Proposals\"/></p>
                <p><input type=\"button\" id=\"btnSearchProposals\" class=\"button\" 
                value=\"Search Proposals\"/></p>
                <p><input type=\"button\" id=\"btnShowAllProposals\" class=\"button\" 
                value=\"Show All Proposals\"/></p>
                <p><input type=\"button\" id=\"btnEventsCalendar\" class=\"button\" 
                value=\"Events Calendar\"/></p>
                <p><input type=\"button\" id=\"btnEditVenues\" class=\"button\" 
                value=\"Edit Venues\"/><br>
                <p><input type=\"button\" id=\"btnChangeLoginOrUserInfo\" class=\"button\" 
                value=\"Change Username / Password or User Info\"/></p>
                <p><input type=\"button\" id=\"btnGoToBbs\" class=\"button\" 
                value=\"Go To BBS\"/></p>
            </div>
        ";
    }
    
    function menuProposer($user){
        echo "
            <div id=\"divMenuProposer\">
                <h1 id=\"pMenuProposer\" class=\"menuHeading\">ReFringe.org Proposer Menu
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href=\"\" class=\"logout\">Logout?</a></h1>
                <p class=\"welcome\">Welcome, $user</p>
                <p><input type=\"button\" id=\"btnMyProposals\" class=\"button\" 
                value=\"My Proposals\"/></p>
                <p><input type=\"button\" id=\"btnChangeLoginOrUserInfo\" class=\"button\" 
                value=\"Change Username / Password or User Info\"/></p>
                <p><input type=\"button\" id=\"btnGoToBbs\" class=\"button\" 
                value=\"Go To BBS\"/></p>
            </div>
        ";
    }
    
    function menuViewer($user){
        echo "
            <div id=\"divMenuViewer\">
                <h1 id=\"pMenuViewer\" class=\"menuHeading\">ReFringe.org Viewer Menu
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href=\"\" class=\"logout\">Logout?</a></h1>
                <p class=\"welcome\">Welcome, $user</p>
                <p><input type=\"button\" id=\"btnChangeLoginOrUserInfo\" class=\"button\" 
                value=\"Change Username / Password or User Info\"/></p>
            </div>
        ";
    }