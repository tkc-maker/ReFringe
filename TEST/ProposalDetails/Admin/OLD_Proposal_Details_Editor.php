<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser, Organizer");
?>
<!DOCTYPE html>
<?PHP
    $Proposal_details_ID = $_SESSION['PD_Proposal_details_ID'];
    $Users_ID = $_SESSION['PD_Users_ID'];
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Proposal Details Editor</title>

        <style>
            .fieldHeading{
                color: brown;
                font-size: 16pt;
                margin: 4px;
            }
            .proposerInfo{
                color:blue;
                font-size: 14pt;
            }
            #tblTitle{
                width: 100%;
            }
            #tblTitle .tdErrorMessage{
                width: 170px;
                margin-left: 20px;
            }
            #tdTitle{
                width: 160px;
                color: darkgreen;
                font-size: 18pt;
                margin: 4px;
            }
            #txtTitle{
                width: 600px;
                padding: 2px;
                font-size: 14pt;
                border-color: black;
            }
            input[type="button"]{
                margin: 4px;
                font-size: 12pt; 
                background-color: orange;
            }
            #btnSaveTitleAnwsers{
                position: fixed;
                top: 60%; /* Center vertically on the screen */
                right: 20px; /* Position it 20px from the right edge */
                transform: translateY(-60%); /* Adjust for perfect centering */
            }
            select{
                font-size: 14pt;
                width: 300px;
            }
            .question_row{
                color: darkgreen;
                font-size: 18pt;
                margin: 4px;
            }
            .answer_box{
                width: 600px;
                font-size: 14pt;
                margin: 4px;
            }
            .tdErrorMessage{
                width: 600px;
                font-size: 12pt;
                color: red;
                margin: 4px;
            }
        </style>
        <script src="/TEST/JavaScript/JQuery371.js"></script>
        <script> 
            
            $(function() {
                $.post('/TEST/ProposalDetails/Admin/Proposal_Details_Editor_backend.php', 
                    {
                        mode: 'displayProposalDetails'
                    }, 
                    function(resp) {
                    $('#divPageContent').html(resp);
                });
                
                $('#divPageContent').on('click', '#btnBackToSearchProposals', function(){
                    window.location = '/TEST/ProposalDetails/Admin/Search_Proposals.php';
                });
                
                $('#divPageContent').on('click', '#divAllQuestionClassesDropdown #btnSelectQuestionClass', function(){ 
                    var title = $('#divPageContent #txtTitle').val();
                    var selectedQuestion_classes_ID = $('#divPageContent #divAllQuestionClassesDropdown #dd_question_classes').find('option:selected').val();
                    var selectedQuestion_classes_name = $('#divPageContent #divAllQuestionClassesDropdown #dd_question_classes').find('option:selected').text();
                    $.post('/TEST/ProposalDetails/Admin/Proposal_Details_Editor_backend.php',
                    {
                        mode: 'selectQuestionClass',
                        title: title,
                        selectedQuestion_classes_ID: selectedQuestion_classes_ID,
                        selectedQuestion_classes_name: selectedQuestion_classes_name
                    },
                    function(resp) {
                        $('#divPageContent').html(resp);
                    });
                });
                
                $('#divPageContent').on('click', '#divAllGenresDropdown #btnSelectGenre', function(){ 
                    var title = $('#divPageContent #txtTitle').val();
                    var selectedGenres_ID = $('#divPageContent #divAllGenresDropdown #dd_genres').find('option:selected').val();
                    var selectedGenres_name = $('#divPageContent #divAllGenresDropdown #dd_genres').find('option:selected').text();

                    $.post('/TEST/ProposalDetails/Admin/Proposal_Details_Editor_backend.php',
                    {
                        mode: 'selectGenre',
                        title: title,
                        selectedGenres_ID: selectedGenres_ID,
                        selectedGenres_name: selectedGenres_name
                    },
                    function(resp) {
                        $('#divPageContent').html(resp);
                    });
                });
                
                $('#divPageContent').on('click', '#divSelectedQuestionClassesDropdown #btnClearSelectedQuestionClasses', function(){
                    var title = $('#divPageContent #txtTitle').val();
                    if(confirm('Are you sure you want to clear all of your selected question classes?')){
                        $.post('/TEST/ProposalDetails/Admin/Proposal_Details_Editor_backend.php',
                        {
                            mode: 'clearSelectedQuestionClasses',
                            title: title
                        },
                        function(resp){
                            $('#divPageContent').html(resp);
                            $('#divQuestionsAnswers').html('');
                        });
                    }
                });
                
                $('#divPageContent').on('click', '#divSelectedGenresDropdown #btnClearSelectedGenres', function(){
                    var title = $('#divPageContent #txtTitle').val();
                    if(confirm('Are you sure you want to clear all of your selected genres?')){
                        $.post('/TEST/ProposalDetails/Admin/Proposal_Details_Editor_backend.php',
                        {
                            mode: 'clearSelectedGenres',
                            title: title 
                        },
                        function(resp){
                            $('#divPageContent').html(resp);
                        });
                    }
                });
                
                $('#divPageContent').on('click', '#btnShowSelectedQuestions', function(){
                    var selectedQuestion_classes_ID = $('#divPageContent #divSelectedQuestionClassesDropdown #dd_question_classes').find('option:selected').val();

                    $.post('/TEST/ProposalDetails/Admin/Proposal_Details_Editor_backend.php', 
                        {
                            mode: 'showSelectedQuestions',
                            selectedQuestion_classes_ID: selectedQuestion_classes_ID
                        }, 
                        function(resp) {
                        $('#divQuestionsAnswers').html(resp);
                    });
                });

                $('#divQuestionsAnswers').on('click', '#btnSaveTitleAnwsers', function(){
                    if(!validProposalDetailsEditorFields()){
                        return;
                    }                    
                    var jsonQuestionsAnswers = getJsonQuestionsAnswers();
                    var title = $('#divPageContent #txtTitle').val();
                    
                    $.post('/TEST/ProposalDetails/Admin/Proposal_Details_Editor_backend.php', 
                        {
                            mode: 'saveTitleAnswers',
                            title: title,
                            jsonQuestionsAnswers: jsonQuestionsAnswers
                        }, 
                        function(resp) {
                        $('#divQuestionsAnswers').html(resp);
                    });
                });

            });
            
            function getJsonQuestionsAnswers(){               
                //Create an array to store the values:
                const ary_questions_answers = [];

                //Loop through each Div with the specified class and extract
                //values into the array:
                $('#divQuestionsAnswers .question_answer_block').each(function() {
                    var question = $(this).find('.question_row').text();
                    var answer = $(this).find('.answer_box').val();
                    ary_questions_answers.push({'question': question, 'answer': answer});
                }); 
                //Convert the array to JSON and return it:
                return JSON.stringify(ary_questions_answers);
            }
            
            /**   Loops through all questions/answer blocks, and validates answers,
             * based upon the rule that starts the question, e.g.: "Yes / No",
             * "*Integer", etc. Calls the function below for each question/answer
             * block. If the answer is valid, true is returned. Otherwise, an
             * error message is displayed to the right of the answer, and false
             * is returned.
             * 
             * @returns {Boolean} Whether all "answer" fields contain valid data.
             */
            function validProposalDetailsEditorFields(){
                var validFields = true;
                
                //First check the "*Proposal Title" field:
                //----------------------------------------
                $('#divPageContent .tdErrorMessage').text('');
                if($('#divPageContent #txtTitle').val().trim() === ''){
                    $('#divPageContent .tdErrorMessage').text('Error: Required.');
                    validFields = false;
                }
                
                //Check all answer fields:
                //------------------------
                $('#divQuestionsAnswers .question_answer_block').each(function() {
                    var question = $(this).find('.question_row').text();
                    var rule = question.match(/^[^:]+: /); //Matches any characters until the first ": ". 
                    var answer = $(this).find('.answer_box').val();
                    
                    $(this).find('.tdErrorMessage').text('');
                    if(!checkAgainstRegex(this, question, answer, 
                            'Yes / No', /^(yes|no)$/i, 
                            'Please enter either Yes or No.')){
                        validFields = false;                    
                        
                    }else if(!checkAgainstRegex(this, question, answer, 
                            'True / False', /^(true|false)$/i, 
                            'Please enter either True or False.')){
                        validFields = false;                    
                        
                    }else if(!checkAgainstRegex(this, question, answer, 
                            'Integer', /^\d+$/, 
                            'Please enter an integer.')){
                        validFields = false;

                    }else if(!checkAgainstRegex(this, question, answer, 
                            'Date-Time', /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/, 
                            'Please enter a date-time in the format, yyyy-mm-dd hh:mm.')){
                        validFields = false;
                                                
                    }else if(!checkAgainstRegex(this, question, answer, 
                            'Up to 125 chars', /^.{0,125}$/, 
                            'Please enter up to 125 characters.')){
                        validFields = false;
                        
                    }else if(!checkAgainstRegex(this, question, answer, 
                            'URL', /^(https?:\/\/)?([a-zA-Z0-9-]+\.)+[a-zA -Z]{2,}(:\d+)?(\/[^\s]*)?$/, 
                            'Please enter a valid URL.')){
                        validFields = false;
                        
                    }else if(answer.trim() === '' && question[0] === '*'){
                        $(this).find('.tdErrorMessage').text('Error: Required.');
                        validFields = false;
                    }
                }); 

                if(!validFields){
                    alert('Some of your fields contain errors! Please fix these before continuing.');
                    return false;
                }else{
                    return true;
                }
            }
            
            /**   Validates one screen-field. Called in a loop over all questions/answers
             * by the above function. If validation fails, an error message is displayed to 
             * the right of the answer field, and false is returned. Upon successful validation,
             * true is returned.
             * 
             * @param obj currObj -- Since this function is called in a loop over selectors,
             *      "curObj" is "this".
             * @param {type} question -- Text of current question.
             * @param {type} answer -- Text of current answer.
             * @param {type} ruleCompareText -- The entered text to compare with the 
             *      rule extracted from "question".
             * @param {type} regex -- A regular expression equivalent to the "ruleCompareText".
             * @param {type} errMsg -- An error message to display if the field is not valid.
             * @returns {Boolean} -- Whether the "answer" is valid.
             */
            function checkAgainstRegex(currObj, question, answer, ruleCompareText, regex, errMsg){
                var rule = question.match(/^[^:]+: /); //Matches any characters until the first ": ". 
                var validField = true;
                
                if(rule !== null && rule[0].includes(ruleCompareText)){                    
                    if(answer.trim() === ''){
                        if(question[0] === '*'){
                            $(currObj).find('.tdErrorMessage').text('Error: Required. '+errMsg);
                            validField = false;
                        }
                    }else if(!regex.test(answer)){
                        $(currObj).find('.tdErrorMessage').text('Error: '+errMsg);
                        validField = false;
                    }
                }
                return validField;
            }
        </script>
    </head>
    
    <body>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Menus/header.php'; ?>
<div id="divPageContent"></div>
<br>
<div id="divQuestionsAnswers"></div>
        
    </body>
</html>
