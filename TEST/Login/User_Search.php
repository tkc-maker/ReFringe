<?PHP 
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser");
?>
<!DOCTYPE html>

<html>
    <head>
        <title>Search Users Page</title>
        <style>
            #tblSearchUsers{
                border-collapse: separate;
                border-spacing: 10px;
            }
            #tblOuter #tdHeading{
                text-align: center;
            }
            .tdOR{
                color: blue;
            }
            .label{
                color: darkgreen;
                font-size: 16pt;
                margin: 4px;
                text-align: right;
            }
            .checkCol{
                text-align: center;
            }
            .selRoles{
                font-size: 11pt;
                width: 200px;
            }
            .inputBox{
                width: 200px;
            }
            .textArea{
                width: 200px;
                height: 50px;
            }
            .button{
                margin: 4px;
                font-size: 12pt; 
                background-color: orange;
            }
            #tdSubmitSearchUsers{
                text-align: center;
            }
            #tblSearchResults{
                margin-left: 10px;                
            }
            .tdResult{
                text-align: right;
            }
            a{
                color: blue;
            }
            a:visited{
                color: blue;
            }
        </style>
        
        <script src="/TEST/JavaScript/JQuery371.js"></script>
        
        <script>
            $(function(){
                $.post('/TEST/Login/User_Search_backend.php', {mode: 'userSearchDisplay'}, function(resp) {
                    $('#divPageContent').html(resp);
                });
                
                $('#divPageContent').on('click', '#btnSubmitSearchUsers', function(){
                    submitSearchUsers();
                });
                
                $('#divPageContent').on('click', '#btnClearCriteria', function(){
                    $.post('/TEST/Login/User_Search_backend.php', 
                    {
                        mode: 'clearCriteria'
                    }, 
                    function(resp){
                        try{
                            window.location = '/TEST/Login/User_Search.php';

                        }catch(error){
                            alert(error);
                            $('#divSearchResults').html('');
                        }
                    });
                });
                
                $('#divSearchResults').on('click', '.btnDelete', function(){
                    if(!confirm('Are you sure you want to delete this user from the database? '+
                            'WARNING: If you delete, then all records in other tables related to this user will also be deleted.')){
                        return;
                    }
                    var Users_ID = $(this).data('id');
                    
                    $.post('/TEST/Login/User_Search_backend.php', 
                    {
                        mode: 'deleteUser',
                        Users_ID: Users_ID
                    }, 
                    function(resp){
                        try{
                            alert(resp);
                            submitSearchUsers();

                        }catch(error){
                            alert(error);
                            $('#divSearchResults').html('');
                        }
                    });                    
                });
                
            });   

            function submitSearchUsers(){
                var jsonSearchUsers = JSON.stringify({
                    Include_1: $('#divPageContent #chkInclude_1').prop('checked'),
                    Roles_ID_1: $('#divPageContent #tdSelRoles_1 .selRoles').find('option:selected').val(),
                    first_name_1: $('#divPageContent #inFirstName_1').val().trim(),
                    last_name_1: $('#divPageContent #inLastName_1').val().trim(),
                    street_1: $('#divPageContent #inStreet_1').val().trim(),
                    city_1: $('#divPageContent #inCity_1').val().trim(),
                    state_1: $('#divPageContent #inState_1').val().trim(),
                    zip_1: $('#divPageContent #inZip_1').val().trim(),
                    phone_1: $('#divPageContent #inPhone_1').val().trim(),
                    email_1: $('#divPageContent #inEmail_1').val().trim(),
                    username_1: $('#divPageContent #inUsername_1').val().trim(),
                    notes_1: $('#divPageContent #txtNotes_1').val().trim(),

                    Include_2: $('#divPageContent #chkInclude_2').prop('checked'),
                    Roles_ID_2: $('#divPageContent #tdSelRoles_2 .selRoles').find('option:selected').val(),
                    first_name_2: $('#divPageContent #inFirstName_2').val().trim(),
                    last_name_2: $('#divPageContent #inLastName_2').val().trim(),
                    street_2: $('#divPageContent #inStreet_2').val().trim(),
                    city_2: $('#divPageContent #inCity_2').val().trim(),
                    state_2: $('#divPageContent #inState_2').val().trim(),
                    zip_2: $('#divPageContent #inZip_2').val().trim(),
                    phone_2: $('#divPageContent #inPhone_2').val().trim(),
                    email_2: $('#divPageContent #inEmail_2').val().trim(),
                    username_2: $('#divPageContent #inUsername_2').val().trim(),
                    notes_2: $('#divPageContent #txtNotes_2').val().trim(),

                    Include_3: $('#divPageContent #chkInclude_3').prop('checked'),
                    Roles_ID_3: $('#divPageContent #tdSelRoles_3 .selRoles').find('option:selected').val(),
                    first_name_3: $('#divPageContent #inFirstName_3').val().trim(),
                    last_name_3: $('#divPageContent #inLastName_3').val().trim(),
                    street_3: $('#divPageContent #inStreet_3').val().trim(),
                    city_3: $('#divPageContent #inCity_3').val().trim(),
                    state_3: $('#divPageContent #inState_3').val().trim(),
                    zip_3: $('#divPageContent #inZip_3').val().trim(),
                    phone_3: $('#divPageContent #inPhone_3').val().trim(),
                    email_3: $('#divPageContent #inEmail_3').val().trim(),
                    username_3: $('#divPageContent #inUsername_3').val().trim(),
                    notes_3: $('#divPageContent #txtNotes_3').val().trim(),

                    Include_4: $('#divPageContent #chkInclude_4').prop('checked'),
                    Roles_ID_4: $('#divPageContent #tdSelRoles_4 .selRoles').find('option:selected').val(),
                    first_name_4: $('#divPageContent #inFirstName_4').val().trim(),
                    last_name_4: $('#divPageContent #inLastName_4').val().trim(),
                    street_4: $('#divPageContent #inStreet_4').val().trim(),
                    city_4: $('#divPageContent #inCity_4').val().trim(),
                    state_4: $('#divPageContent #inState_4').val().trim(),
                    zip_4: $('#divPageContent #inZip_4').val().trim(),
                    phone_4: $('#divPageContent #inPhone_4').val().trim(),
                    email_4: $('#divPageContent #inEmail_4').val().trim(),
                    username_4: $('#divPageContent #inUsername_4').val().trim(),
                    notes_4: $('#divPageContent #txtNotes_4').val().trim()
                });

                $.post('/TEST/Login/User_Search_backend.php', 
                {
                    mode: 'searchUsersSubmit',
                    jsonSearchUsers: jsonSearchUsers
                }, 
                function(resp){
                    try{
                        $('#divSearchResults').html(resp);

                    }catch(error){
                        alert(resp);
                        $('#divSearchResults').html('');
                    }
                });                
            }
        </script>
    </head>
    <body>
        <?php require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Menus/header.php'; ?>
        <div id="divPageContent"></div>
        <div id="divSearchResults"></div>
    </body>
