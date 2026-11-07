<?PHP 
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser, Organizer, Proposer, Viewer");
?>
<!DOCTYPE html>

<html>
    <head>
        <title>Main Menus Page</title>
        <style>
            .menuHeading{
                color: blue;
            }
            .welcome{
                color: darkgreen;
                font-size: 18pt;
                margin: 4px;
            }
            a{
                color: red;
                font-size: 12pt;
            }
            a:visited{
                color: red;
            }
            .button{
                margin: 4px;
                font-size: 12pt; 
                background-color: orange;
            }
        </style>
        
        <script src="/TEST/JavaScript/JQuery371.js"></script>
        
        <script>
            $(function(){
                
                var mode = '';

                $.post('/TEST/Menus/Main_Menus_backend.php', {mode: mode}, function(resp) {
                    $('#divPageContent').html(resp);
                });
                
                $('#divPageContent').on('click', '.logout', function(event){
                    event.preventDefault();
                    window.location.href = "/TEST/Login/Login.php";
                });                
                
                $('#divPageContent').on('click', '#btnEditQuestion_classes', function(){
                    window.location.href = "/TEST/ProposalDetails/Admin/Question_Classes_Editor.php";
                });   
                
                $('#divPageContent').on('click', '#btnEditRoles', function(){
                    window.location.href = "/TEST/ProposalDetails/Admin/Roles_Editor.php";
                });   
                
                $('#divPageContent').on('click', '#btnEditGenres', function(){
                    window.location.href = "/TEST/ProposalDetails/Admin/Genres_Editor.php";
                });   
                
                $('#divPageContent').on('click', '#btnEditScreenfieldValidations', function(){
                    window.location.href = "/TEST/ScreenFieldValidation/Admin/Screenfield_validation_Editor.php";
                });   
                
                $('#divPageContent').on('click', '#btnMyProposals', function(){
                    window.location.href = "/TEST/ProposalDetails/Public/MyProposals.php";
                });   
                
                $('#divPageContent').on('click', '#btnSearchProposals', function(){
                    window.location.href = "/TEST/ProposalDetails/Admin/Search_Proposals.php";
                });                
                
                $('#divPageContent').on('click', '#btnShowAllProposals', function(){
                    window.location.href = "/TEST/ProposalDetails/Admin/Show_All_Proposals.php";
                });                

                $('#divPageContent').on('click', '#btnEventsCalendar', function(){
                    window.location.href = "/TEST/EventSchedule/Admin/Calendar.php";
                });                
                
                $('#divPageContent').on('click', '#btnChangeLoginOrUserInfo', function(){
                    window.location.href = "/TEST/Login/Login_Change.php";
                });

                $('#divPageContent').on('click', '#btnSearchUsers', function(){
                    window.location.href = "/TEST/Login/User_Search.php";
                });
                
                $('#divPageContent').on('click', '#btnEditProposalTemplateQuestions', function(){
                    window.location.href = "/TEST/ProposalDetails/Admin/Questions_Editor.php";
                });
                
                $('#divPageContent').on('click', '#btnEditVenueTemplateQuestions', function(){
                    window.location.href = "/TEST/Venues/Admin/Venue_Questions_Editor.php";
                });
                
                $('#divPageContent').on('click', '#btnEditVenues', function(){
                    window.location.href = "/TEST/Venues/Admin/AllVenues.php";
                });
                
                $('#divPageContent').on('click', '#btnGoToBbs', function(){
                    window.open("/TEST/forum", "_blank");
                });
                
            });            
        </script>
    </head>
    <body>
        <div id="divPageContent"></div>
    </body>
