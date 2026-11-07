<?PHP 
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser");
?>
<!DOCTYPE html>

<html>
    <head>
        <title>ReFringe.org Edit User Search Result Page</title>
<?php
    $varUsers_ID = filter_input(INPUT_GET, 'varUsers_ID', FILTER_SANITIZE_NUMBER_INT);
?>
        <style>
            h1{
                font-size: 20pt;
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
                text-align: right;
            }
            #resetPassword{
                color: red;
                font-size: 14pt;
            }
            #pLabel{
                text-align: left;
            }
            .selRoles{
                font-size: 11pt;
                width: 200px;
            }
            .inputBox{
                width: 400px;
            }
            #tblEditUser{
                width: 600px;
                padding: 5px;
                margin: 0 auto;
            }
            table tr{
                height: 50px;
            }
            .tdInstructions{
                color: red;
                text-align: left;
            }
            #txtNotes{
                height: 200px;
                width: 400px;
            }
            #tdSelRoles{
                text-align: left;
            }
            #tdSubmitEditUser{
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
                $.post('/TEST/Login/User_Search_Results_Editor_backend.php', 
                    {
                        mode: 'editUserDisplay',
                        varUsers_ID: <?php echo $varUsers_ID; ?>
                    }, 
                    function(resp){
                        try{
                            $('#divPageContent').html(resp);

                        }catch(error){
                            alert(resp);
                        }
                    }
                );
                
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
                       inUsername: $('#divPageContent #inUsername').val(),
                       inPassword: $('#divPageContent #inPassword').val(),
                       txtNotes: $('#divPageContent #txtNotes').val()
                    }); 

                    $.post('/TEST/Login/User_Search_Results_Editor_backend.php', 
                    {
                        mode: 'editUserSubmit',
                        jsonEditUser: jsonEditUser
                    }, 
                    function(resp){
                        try{
                            $('#divPageContent').html(resp);

                        }catch(error){
                            alert(resp);
                        }
                    });
                });
                
                $('#divPageContent').on('click', '#btnCancel', function(){
                    window.location = '/TEST/Login/User_Search.php';
                });
            });

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
                if(!$('#divPageContent  #inUsername')[0].checkValidity()){
                    message += '--Username is invalid. Please use characters out of the pool: \
upper and lower case letters, digits 0-9, special characters, _, -, *, &, $, @. And make it at \
least 6 characters, but no more than 10.\n';
                }
                if(!$('#divPageContent  #inPassword')[0].checkValidity()){
                    message += '--Password is invalid. Please use characters out of the pool: \
upper and lower case letters, digits 0-9, special characters, _, -, *, &, $, @. And make it at \
least 6 characters, but no more than 10.\n';
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