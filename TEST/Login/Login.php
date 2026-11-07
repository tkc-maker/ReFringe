<?PHP 
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    $username = urldecode(trim(filter_input(INPUT_GET, 'u', FILTER_UNSAFE_RAW).''));
    $password = urldecode(trim(filter_input(INPUT_GET, 'p', FILTER_UNSAFE_RAW).''));
?>
<!DOCTYPE html>

<html>
    <head>
        <title>ReFringe.org Login Page</title>
        <style>
            #divImg{
                background-image: url('/TEST/Images/word_salad.png');
                background-size: 80% 900px; /* Makes the background image cover the entire body */
                background-repeat: no-repeat; /* Prevents the background image from repeating */
                background-position: center center; /* Centers the background image */
                
                width: 100%;
                height: 600px;                
            }
            .random-word{
                position: absolute;
                font-size: 40px;
                font-weight: bold;
                color: red;
                background-color: #ffffff;
            }
            #divEnterProposalNow{
                width: 700px;
                margin: 0 auto;
            }
            #tblEnterProposalNow{
                width: 600px;
                padding: 5px;
                margin: 0 auto;
            }
            #tblEnterProposalNow .label{
                text-align: right;
            }
            #tblEnterProposalNow #tdButtons{
                text-align: center;
            }
            #pPreface{
                text-align: center;
                color: red;
                font-size: 14pt;
            }
            #divLogin{
                width: 600px;
                margin: 0 auto;
            }
            #divNewUser{
                width: 1000px;
                margin: 0 auto;

            }
            #divForgotUsername, #divForgotPassword{
                width: 750px;
                margin: 0 auto;
            }
            #pHeading{
                color: blue;
                font-size: 19pt;
            }
            #pNotes{
                color: blue;
                font-size: 14pt;
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
            #txtNotes{
                height: 200px;
                width: 400px;
            }
            #tblLogin{
                width: 400px;
                padding: 5px;
                margin: 0 auto;
            }
            #tblOuter{
                width: 1000px;
            }
            #tblOuter #tdHeading{
                text-align: center;
            }
            #tblNewUser{
                width: 600px;
            }
            #tblForgotUsername, #tblForgotPassword{
                width: 560px;
                padding: 5px;
                margin: 0 auto;
            }
            table tr{
                height: 50px;
            }
            #tblLogin td{
                margin: 2px;
                text-align: center;
            }
            #tblNewUser .label,
            #tblForgotUsername .label{
                margin: 4px;
                text-align: right;
            }
            #tblNewUser .tdInstructions,
            #tblForgotUsername .tdInstructions{
                color: red;
                text-align: left;
            }
            #tblNewUser #tdSelRoles{
                text-align: left;
            }
            #tblNewUser #tdSubmitNewUser,
            #tblForgotUsername #tdSubmitInfo{
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
                const words = ['reacclimate', 'reach', 'reacquaint', 'react', 'read', 'ready', 'reaffirm', 'realign', 'reFringe',
                    'realize', 'reallocate', 'reanimate', 'reappraise', 'rearrange', 'rearticulate', 'reason', 'reassemble', 'ReFringe',
                    'reassert', 'reassess', 'reassure', 'reawaken', 'rebalance', 'rebirth', 'rebound', 'rebuild', 'recalibrate', 'reFringe !!',
                    'recall', 'recapture', 'receive', 'recent', 'recharge', 'reciprocate', 'recite', 'reckon', 'reclaim', 'ReFringe !!!',
                    'reclassify', 'recline', 'recognize', 'recommend', 'recommit', 'recompile', 'recompose', 'reconceive', '* reFringe *',
                    'reconceptualize', 'reconcile', 'reconfigure', 'reconnect', 'reconsider', 'reconstitute', 'reconstruct', '** ReFringe **',
                    'recontextualize', 'reconvene', 'record', 'recover', 'recreate', 'recultivate', 'recycle', 'redeem', '-- reFringe --',
                    'redefine', 'redesign', 'redesignate', 'redevelop', 'redirect', 'rediscover', 'redistribute', 'redo', '--- ReFringe ---',
                    'reduce', 'reemerge', 'reemphasize', 'reenable', 'reenact', 'reenergize', 'reenforce', 'reengage', 'reFringe ?',
                    'reenter', 'reenvisage', 'reestablish', 'reevaluate', 'reexamine', 'refashion', 'refer', 'refill', 'ReFringe ??',
                    'refine', 'refit', 'reflect', 'reform', 'refract', 'refrain', 'refresh', 'refuge', 'refugee', 'refurbish', '> reFringe <',
                    'refute', 'regain', 'regale', 'regard', 'regenerate', 'region', 'regroup', 'regularize', 'rehabilitate', '>>> ReFringe <<<',
                    'rehearse', 'reignite', 'reimagine', 'reinforce', 'reintegrate', 'reintroduce', 'reinvent', 'reinvigorate', '& reFringe', 
                    'reiterate', 'reject', 'rejoice', 'rejuvenate', 'rekindle', 'relabel', 'relate', 'relationships', 'relaunch', '&& ReFringe', 
                    'relearn', 'release', 'relevant', 'reliable', 'relieve', 'relight', 'relive', 'remain', 'remake', 'remark', '(refringe)', 
                    'remedy', 'remember', 'remind', 'reminisce', 'remodel', 'remold', 'renaissance', 'render', 'rendezvous', '((reFringe))', 
                    'renegotiate', 'renew', 'renovate', 'reopen', 'reorganize', 'repair', 'repeat', 'replace', 'replenish', '(((ReFringe)))', 
                    'reply', 'report', 'represent', 'reprint', 'reprise', 'reproduce', 'repurpose', 'request', 'rescue', '5 x reFringe', 
                    'research', 'resemble', 'reserve', 'reset', 'reshape', 'reside', 'resilient', 'resist', 'resolve', '10 x ReFringe', 
                    'resonate', 'resort', 'resourcefulness', 'resources', 'respect', 'respite', 'respond', 'restart', 'restore', 'info@reFringe.org', 
                    'restructure', 'result', 'resume', 'resurgence', 'resurrect', 'retain', 'retell', 'rethink', 'retrace', '{reFringe}', 
                    'retrieve', 'return', 'reunify', 'reunite', 'revamp', 'reveal', 'revel', 'revere', 'reverse', 'review', '{{{ReFringe}}}', 
                    'revise', 'revisit', 'revitalize', 'revive', 'revolve', 'reward', 'rewrite', 'reFRINGE'];
                const $myDiv = $('#divImg');

                function getRandomWord() {
                    const randomIndex = Math.floor(Math.random() * words.length);
                    return words[randomIndex];
                }

                function setRandomWordSize(){
                    const randomSize = (Math.floor(Math.random() * 10) * 5) + 40;
                    $('.random-word').css('font-size', randomSize + 'px');
                }
                
                function setRandomWordColor(){
                    const randomColor = Math.floor(Math.random() * 10);
                    const colors = ['burlywood', 'coral', 'darkorange', 'darkturquoise', 'deeppink', 
                        'dodgerblue', 'lawngreen', 'orchid', 'firebrick', 'gold'];
                    $('.random-word').css('color', colors[randomColor]);
                }

                function getRandomPosition(max) {
                    return Math.floor(Math.random() * max);
                }

                function placeRandomWord() {
                    const word = getRandomWord();
                    const $span = $('<span>').text(word).addClass('random-word');

                    $('#divImg .random-word').text("");
                    $myDiv.append($span);
                    
                    setRandomWordSize();
                    setRandomWordColor();
                    
                    const maxWidth = $myDiv.width() - $span.width();
                    const maxHeight = $myDiv.height() - $span.height();

                    const xPos = getRandomPosition(maxWidth);
                    const yPos = getRandomPosition(maxHeight) + 250;

                    $span.css({ left: xPos + 'px', top: yPos + 'px' });
                }

                //Place a random word every 2 seconds (2000 milliseconds):
                var intervalId = setInterval(placeRandomWord, 2000);

//==========================================================================================================

                $.post('/TEST/Login/Login_backend.php', {mode: 'loginDisplay'}, function(resp) {
                    $('#divPageContent').html(resp);
                    $('#inUsername').val("<?php echo $username; ?>");
                    $('#inPassword').val("<?php echo $password; ?>");
                });
                
                $('#divPageContent').on('click', '#btnQuickEasyNoLoginProposal', function(){
                    if(!confirm("Continue ONLY if you haven't created a user profile on \"Refringe.org\". "+
                                "Otherwise, please log in to view your Proposal or enter a new one.")){
                        return;
                    }
                    window.location.href = "/TEST/ProposalDetails/Public/NoLogin_Proposal_Editor.php";
                });
                
                $('#divPageContent').on('click', '#btnBackToHomePage', function(){
                    window.location.href = "https://refringe.org";
                });
                
                $('#divPageContent').on('click', '#btnNewUser', function(){
                    $.post('/TEST/Login/Login_backend.php', {mode: 'newUserDisplay'}, function(resp){
clearInterval(intervalId);                            
$('.random-word').hide();
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

                $('#divPageContent').on('click', '#btnSubmitLogin', function(){
                    $.post('/TEST/Login/Login_backend.php', 
                        {
                         mode: 'loginSubmit',
                         username: $('#divPageContent #inUsername').val(),
                         password: $('#divPageContent #inPassword').val()
                        }, 
                        function(resp){
                            try{
                                $('#divPageContent').html(resp);

                            }catch(error){
                                $('#divPageContent').html(resp);
                            }
                        }
                    );
                });

                $('#divPageContent').on('click', '#btnSubmitNewUser', function(){
                    if(!validateScreenFields()){
                        return;
                    }
                    var jsonNewUser = JSON.stringify({
                       selRoles: $('#divPageContent .selRoles').find('option:selected').val(),
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
                    $.post('/TEST/Login/Login_backend.php', 
                        {
                         mode: 'newUserSubmit',
                         jsonNewUser: jsonNewUser
                        },
                        function(resp){
                            try{
                                $('#divPageContent').html(resp);
intervalId = setInterval(placeRandomWord, 2000);                                
$('.random-word').show();

                            }catch(error){
                                alert(JSON.stringify(resp));
                                $('#divPageContent').html('');
                            }
                        }
                    );
                });

                $('#divPageContent').on('click', ' #btnBackToLogin, #btnCancelNewUser', function(){
                    $.post('/TEST/Login/Login_backend.php', {mode: 'loginDisplay'}, function(resp) {
                        $('#divPageContent').html(resp);
intervalId = setInterval(placeRandomWord, 2000);                                
$('.random-word').show();
                    });
                });
                
                $('#divPageContent').on('click', '#lnkForgotUsername', function(event){
                    event.preventDefault();
                    $.post('/TEST/Login/Forgot_backend.php', {mode: 'forgotUsernameDisplay'}, function(resp) {
                        $('#divPageContent').html(resp);
                    });
                });
                
                $('#divPageContent').on('click', '#btnSubmitUsernameInfo', function(){
                    $.post('/TEST/Login/Forgot_backend.php', 
                           {mode: 'forgotUsernameSubmit',
                            first_name: $('#divPageContent #divForgotUsername #inFirstName').val(),
                            last_name: $('#divPageContent #divForgotUsername #inLastName').val(),
                            password: $('#divPageContent #divForgotUsername #inPassword').val()
                           }, function(resp) {
                        $('#divPageContent').html(resp);
                    });
                });

                $('#divPageContent').on('click', '#lnkForgotPassword', function(event){
                    event.preventDefault();
                    $.post('/TEST/Login/Forgot_backend.php', {mode: 'forgotPasswordDisplay'}, function(resp) {
                        $('#divPageContent').html(resp);
                    });
                });
                
                $('#divPageContent').on('click', '#btnSubmitPasswordInfo', function(){
                    $.post('/TEST/Login/Forgot_backend.php', 
                           {mode: 'forgotPasswordSubmit',
                            first_name: $('#divPageContent #divForgotPassword #inFirstName').val(),
                            last_name: $('#divPageContent #divForgotPassword #inLastName').val(),
                            username: $('#divPageContent #divForgotPassword #inUsername').val()
                           }, function(resp) {
                        $('#divPageContent').html(resp);
                    });
                });
            });   
            
            function validateScreenFields(){
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
                if(!$('#divPageContent #inPassword')[0].checkValidity()){
                    message += '--Password is invalid. Please use characters out of the pool: \
upper and lower case letters, digits 0-9, special characters, _, -, *, &, $, @. And make it at \
least 6 characters, but no more than 10.\n';
                }
                if($('#divPageContent #reinPassword').val() !== $('#divPageContent #inPassword').val()){
                    message += '--The repeat-password must equal the password.\n';
                }

                message = message.trim();
                if(message !== ''){
                    alert('Some of your fields contain errors:\n\n'+message);
                    return false;
                }else{
                    return true;
                }
            }
            
            function validateScreenFields_ProposalNow(){
                var message = '';
                if($('#divPageContent #inFirstName').val().trim() === ''){
                    message += '--First Name is required.\n';
                }
                if($('#divPageContent #inLastName').val().trim() === ''){
                    message += '--Last Name is required.\n';
                }
                if(!$('#divPageContent #inPhone')[0].checkValidity()){
                    message += '--Phone is invalid and is required.\n    Please use ###-###-####.\n';
                }
                if(!$('#divPageContent #inEmail')[0].checkValidity()){
                    message += '--Email is invalid and is required.\n';
                }

                message = message.trim();
                if(message !== ''){
                    alert('Some of your fields contain errors:\n\n'+message);
                    return false;
                }else{
                    return true;
                }
            }
            
           /**   Redirects to the "Proposal Editor" screen, passing the
            * "Proposal_details_ID" and the current "Users_ID", which is stored
            * in SESSION. 
            * 
            * NOTE: If "Proposal_details_ID" is 0 or null, a new "empty" 
            *       "Proposal Details Editor" will be created.
            * 
            * @param {Integer} Users_ID
            * @param {Integer} Proposal_details_ID
            * @returns -- Redirects to the "Proposal Details Editor" screen.
            */
            function createSubmitHiddenForm(Users_ID, Proposal_details_ID){
                    //Create a hidden form to send the variable via POST:
                    const form = $('<form>', {
                        action: '/TEST/ProposalDetails/Public/Proposal_Editor.php',
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
        <div id="divPageContent"></div>
        <div id="divImg"></div>
    </body>
</html>        