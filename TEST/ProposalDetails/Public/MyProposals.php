<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser, Organizer, Proposer");
?>
<!DOCTYPE html>
<?PHP
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Proposals</title>

        <style>
            #divPageContent #tblMyProposals td{
                vertical-align: middle;
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
                var Users_ID = <?PHP echo $_SESSION['ID'] ?>;
                $.post('/TEST/ProposalDetails/Public/MyProposals_backend.php', 
                    {
                        mode: 'displayMyProposals',
                        Users_ID: Users_ID
                    }, 
                    function(resp) {
                        $('#divPageContent').html(resp);
                });
                
                $('#divPageContent').on('click', '#tblMyProposals .aMyProposal', function(event){
                    event.preventDefault();
                    
                    var Proposal_details_ID = $(this).data('id');
                    createSubmitHiddenForm(Proposal_details_ID)
                });
                
                $('#divPageContent').on('click', '#tblMyProposals #btnNewProposal', function(){
                    var Proposal_details_ID = 0;
                    createSubmitHiddenForm(Proposal_details_ID);
                });
                
                $('#divPageContent').on('click', '#tblMyProposals .btnDelete', function(){
                    var Proposal_details_ID = $(this).data('id');
                    if(confirm('Are you sure you want to delete this "Proposal_details" record?')){
                        $.post('/TEST/ProposalDetails/Public/MyProposals_backend.php', 
                            {
                                mode: 'deleteProposalDetails',
                                Users_ID: Users_ID,
                                Proposal_details_ID: Proposal_details_ID
                            }, 
                            function(resp) {
                                $('#divPageContent').html(resp);
                        });
                    }
                });
            });
            
           /**   Redirects to the "Proposal Editor" screen, passing the
            * "Proposal_details_ID" and the current "Users_ID", which is 
            * stored in SESSION. 
            * 
            * NOTE: If "Proposal_details_ID" is 0 or null, a new "empty" 
            *       "Proposal Details Editor" will be created.
            * 
            * @param {Integer} Proposal_details_ID
            * @returns -- Redirects to the "Proposal Details Editor" screen.
            */
            function createSubmitHiddenForm(Proposal_details_ID){
                    var action = '/TEST/ProposalDetails/Public/Proposal_Editor.php';
                    if(Proposal_details_ID === 0){
                        action = '/TEST/ProposalDetails/Public/New_Proposal_Editor.php';
                    }
                    //Create a hidden form to send the variable via POST:
                    const form = $('<form>', {
                        action: action,
                        method: 'POST',
                        style: 'display: none;' //Hide the form.
                    });
                    
                    //Add two variables to the form:
                    //------------------------------
                    const input_1 = $('<input>', {
                        type: 'hidden',
                        name: 'Proposal_details_ID', // Variable name
                        value: Proposal_details_ID // Variable value
                    });
                    
                    var Users_ID = <?PHP echo $_SESSION['ID'] ?>;
                    const input_2 = $('<input>', {
                        type: 'hidden',
                        name: 'Users_ID', // Variable name
                        value: Users_ID // Variable value
                    });

                    form.append(input_1);
                    form.append(input_2);
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
