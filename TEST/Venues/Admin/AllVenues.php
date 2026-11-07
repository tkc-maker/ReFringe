<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser, Organizer");
?>
<!DOCTYPE html>
<?PHP
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Proposals</title>

        <style>
            /* Stripe even rows with a light gray: */
            table tbody tr:nth-child(even) {
              background-color: #f9f9f9;
            }

            /* Explicitly set odd rows to white: */
            table tbody tr:nth-child(odd) {
              background-color: #ffffff;
            } 
            #divPageContent th{
                color: brown;
                padding-top: 10px;
            }
            #divPageContent #tblAllVenues td{
                vertical-align: middle;
                width: 150px;
            }
            #divPageContent #tblAllVenues .center{
                text-align: center;
            }
            a{
                color: blue;
            }
            a:visited{
                color: blue;
            }
            .button{
                margin: 4px;
                font-size: 12pt; 
                background-color: orange;
            }
        </style>
        <script src="/TEST/JavaScript/JQuery371.js"></script>
        <script> 
            
            $(function() {
                $.post('/TEST/Venues/Admin/AllVenues_backend.php', 
                    {
                        mode: 'displayAllVenues'
                    }, 
                    function(resp) {
                        $('#divPageContent').html(resp);
                });
                
                $('#divPageContent').on('click', '#tblAllVenues .aVenue', function(event){
                    event.preventDefault();
                    
                    var Venues_ID = $(this).data('id');
                    createSubmitHiddenForm(Venues_ID);
                });
                
                $('#divPageContent').on('click', '#tblAllVenues #btnNewVenue', function(){
                    var Venues_ID = 0;
                    createSubmitHiddenForm(Venues_ID);
                });
                
                $('#divPageContent').on('click', '#tblAllVenues .btnDelete', function(){
                    var Venues_ID = $(this).data('id');
                    if(confirm('Are you sure you want to delete this "Venues" record?')){
                        $.post('/TEST/Venues/Admin/AllVenues_backend.php', 
                            {
                                mode: 'deleteVenue',
                                Venues_ID: Venues_ID
                            }, 
                            function(resp) {
                                $('#divPageContent').html(resp);
                        });
                    }
                });
                
                //Change "#btnSaveVenueChecks" button to red when a checkbox is changed:
                $("#divPageContent").on("input", ".checkbox", function() {
                    $("#divPageContent #btnSaveVenueChecks").css("background-color", "red");
                });
                
                $("#divPageContent").on("click", "#btnSaveVenueChecks", function() {
                    //Change "#btnSaveVenueChecks" button back to orange when clicked:
                    $(this).css("background-color", "");
                    
                    var aryVenueChecks = [];
                    $("#divPageContent .checkbox").each(function(){
                        var Venues_ID = parseInt($(this).data('id'), 10);
                        var isChecked = $(this).is(':checked');   // true or false
                        aryVenueChecks.push({Venues_ID: Venues_ID, isChecked: isChecked});
                    });
                    
                    var jsonVenueChecks = JSON.stringify(aryVenueChecks);
                    $.post('/TEST/Venues/Admin/AllVenues_backend.php', 
                        {
                            mode: 'saveVenueChecks',
                            jsonVenueChecks: jsonVenueChecks
                        }, 
                        function(resp){
                            var aryResp = JSON.parse(resp);

                            if(aryResp.status === "true"){
                                alert('Venue Checks saved ok.');
                            }else{
                                alert(aryResp.errMsg);
                            }
                    });

                });

            });
            
           /**   Redirects to the "Venue Editor" screen, passing the
            * "Venues_ID". 
            * 
            * NOTE: If "Venues_ID" is 0 or null, a new "empty" 
            *       "Venue Editor" will be created.
            * 
            * @param {Integer} Venues_ID
            * @returns -- Redirects to the "Venue Editor" screen.
            */
            function createSubmitHiddenForm(Venues_ID){
                    //Create a hidden form to send the variable via POST:
                    const form = $('<form>', {
                        action: '/TEST/Venues/Admin/Venue_Details_Editor.php',
                        method: 'POST',
                        style: 'display: none;' //Hide the form.
                    });
                    
                    //Add two variables to the form:
                    //------------------------------
                    const input_1 = $('<input>', {
                        type: 'hidden',
                        name: 'Venues_ID', // Variable name
                        value: Venues_ID // Variable value
                    });

                    form.append(input_1);
                    $('body').append(form);

                    //Submit the form:
                    form.submit();
            }
        </script>
    </head>
    
    <body>        
        <?php require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Menus/header.php'; ?>
        <div id="divPageContent"></div>
    </body>
