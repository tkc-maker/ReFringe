<?PHP 
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser, Organizer, Proposer, Viewer");
?>
<!DOCTYPE html>

<html>
    <head>
        <title>ReFringe.org Login / User Change Page</title>
        <style>
            #divLoginChange{
                width: 780px;
                margin: 0 auto;
            }
            h1{
                text-align: center;
                margin: 0 auto;
            }
            #divEditUser{
                width: 1000px;
                margin: 0 auto;
            }
            .label{
                color: darkgreen;
                font-size: 18pt;
                margin: 4px;
            }
            .selRoles{
                font-size: 11pt;
                width: 200px;
            }
            .inputBox{
                width: 400px;
            }
            #tblLoginChange{
                width: 690px;
                padding: 5px;
                margin: 0 auto;
            }
            #tblEditUser{
                width: 560px;
                padding: 5px;
                margin: 0 auto;
            }
            table tr{
                height: 50px;
            }
            #tblLoginChange .label,
            #tblEditUser .label{
                margin: 4px;
                text-align: right;
            }
            .tdInstructions{
                color: red;
                text-align: left;
            }
            #txtNotes{
                height: 200px;
                width: 400px;
            }
            #tblEditUser #tdSelRoles{
                text-align: left;
            }
            #tblLoginChange #tdSubmitLoginChange,
            #tblEditUser #tdSubmitEditUser{
                text-align: center;
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
                $.post('/TEST/Login/Login_Change_backend.php', {mode: 'loginChangeDisplay'}, function(resp) {
                    $('#divPageContent').html(resp);
                });

                $('#divPageContent').on('click', '#btnSubmitLoginChange', function(){
                    if(!validateLoginChangeScreenFields()){
                        return;
                    }
                    $.post('/TEST/Login/Login_Change_backend.php', 
                        {
                            mode: 'loginChangeSubmit',
                            usernameCurrent: $('#divPageContent #inUsernameCurrent').val(),
                            passwordCurrent: $('#divPageContent #inPasswordCurrent').val(),
                            usernameNew: $('#divPageContent #inUsernameNew').val(),
                            passwordNew: $('#divPageContent #inPasswordNew').val()
                        }, 
                        function(resp){
                            try{
                                $('#divPageContent').html(resp);

                            }catch(error){
                                alert(JSON.stringify(resp));
                                $('#divPageContent').html('');
                            }
                        }
                    );
                });
                
                $('#divPageContent').on('click', '#btnEditUser', function(){
                    $.post('/TEST/Login/Login_Change_backend.php', {mode: 'editUserDisplay'}, function(resp){
                        try{
                            $('#divPageContent').html(resp);
                            //Disable the Roles dropdown (but show the preselected Role):
                            $('#divPageContent .selRoles').prop('disabled', true);

                        }catch(error){
                            alert(JSON.stringify(resp));
                            $('#divPageContent').html('');
                        }
                    });
                });
                
                $('#divPageContent').on('click', '#btnSubmitEditUser', function(){
                    if(!validateEditUserScreenFields()){
                        return;
                    }
                    var jsonEditUser = JSON.stringify({
                       selRoles_ID: $('#divPageContent .selRoles').find('option:selected').val(),
                       inFirstName: $('#divPageContent #inFirstName').val(),
                       inLastName: $('#divPageContent #inLastName').val(),
                       inStreet: $('#divPageContent #inStreet').val(),
                       inCity: $('#divPageContent #inCity').val(),
                       inState: $('#divPageContent #inState').val(),
                       inZip: $('#divPageContent #inZip').val(),
                       inPhone: $('#divPageContent #inPhone').val(),
                       inEmail: $('#divPageContent #inEmail').val(),
                       txtNotes: $('#divPageContent #txtNotes').val()
                    }); 

                    $.post('/TEST/Login/Login_Change_backend.php', 
                    {
                        mode: 'editUserSubmit',
                        jsonEditUser: jsonEditUser
                    }, 
                    function(resp){
                        try{
                            $('#divPageContent').html(resp);

                        }catch(error){
                            alert(JSON.stringify(resp));
                            $('#divPageContent').html('');
                        }
                    });
                });

            });
            
            function validateLoginChangeScreenFields(){
                var message = '';
                if(!$('#divPageContent  #inUsernameCurrent')[0].checkValidity()){
                    message += '--Current Username is invalid. Please use characters out of the pool: \
upper and lower case letters, digits 0-9, special characters, _, -, *, &, $, @. And make it at \
least 6 characters, but no more than 10.\n';
                }
                if(!$('#divPageContent  #inUsernameNew')[0].checkValidity()){
                    message += '--New Username is invalid. Please use characters out of the pool: \
upper and lower case letters, digits 0-9, special characters, _, -, *, &, $, @. And make it at \
least 6 characters, but no more than 10.\n';
                }
                if(!$('#divPageContent #inPasswordNew')[0].checkValidity()){
                    message += '--New Password is invalid. Please use characters out of the pool: \
upper and lower case letters, digits 0-9, special characters, _, -, *, &, $, @. And make it at \
least 6 characters, but no more than 10.\n';
                }
                if($('#divPageContent #inRePasswordNew').val() !== $('#divPageContent #inPasswordNew').val()){
                    message += '--The repeat-password must equal the new password.\n';
                }

                message = message.trim();
                if(message !== ''){
                    alert('Some of your fields contain errors:\n\n'+message);
                    return false;
                }else{
                    return true;
                }
            }

            function validateEditUserScreenFields(){
                var message = '';
                if($('#divPageContent #inFirstName').val().trim() === ''){
                    message += '--First Name is required.\n';
                }
                if($('#divPageContent #inLastName').val().trim() === ''){
                    message += '--Last Name is required.\n';
                }
                if(!$('#divPageContent #inPhone')[0].checkValidity()){
                    message += '--Phone is invalid. Please use ###-###-####.\n';
                }
                if(!$('#divPageContent #inEmail')[0].checkValidity()){
                    message += '--Email is invalid.\n';
                }

                message = message.trim();
                if(message !== ''){
                    alert('Some of your fields contain errors:\n\n'+message);
                    return false;
                }else{
                    return true;
                }
            }

        </script>
    </head>
    <body>
        <?php require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Menus/header.php'; ?>
        <div id="divPageContent"></div>
    </body>
</html>