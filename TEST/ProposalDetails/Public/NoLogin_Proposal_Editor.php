<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
?>
<!DOCTYPE html>
<?php
    //Initial values for everybody:
    $Proposal_details_ID = 0; //Template record.
    $Users_ID = 0; //The "Users_ID" has yet to be determined.
    $question_classes = json_encode([["ID" => 32, "name" => "A Quick Easy No Login Proposal"]]); //This must not be changed!
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/TEST/CSS/handsontable.min.css?v=15.2" />
        <link rel="stylesheet" href="/TEST/CSS/ht-theme-main.min.css?v=15.2" />
        <style>
            #divPageContent{
                position: relative;
                width: 1000px;
                overflow: hidden; 
                border: 1px solid #ccc; /* Optional border for better visibility */
            }
            .question_answer_block{
                width: 1200px;
            }
            #Note{
                color: red;
                font-size: 14pt;
            }
            h1{
                text-align: center;
            }
            table{
                margin-left: 20px;
            }
            table td{
                vertical-align: top;
            }
            table .buttonRow{
                padding: 10px;
                text-align: center;
            }
            .Question_classes_name{
                width: 600px;
                color: brown;
                font-size: 18pt;
                margin: 4px;
            }
            .question_row{
                width: 600px;
                color: darkgreen;
                font-size: 18pt;
                margin: 4px;
            }
            .answer_box{
                width: 600px;
                font-size: 14pt;
                margin: 4px;
            }
            .tdErrorMessage{ /* This is for the answers. */
                width: 600px;
                font-size: 12pt;
                color: red;
                margin: 4px;
                text-align: left;
            }
            input[type="button"]{
                margin: 4px;
                font-size: 12pt; 
                background-color: orange;
            }
            #divButtons{
                margin-top: 15px;
                margin-bottom: 15px;
            }
            .divSaveAnswers {
              text-align: left;
              color: red;
              font-size: 12pt;
              width: 150px;
              z-index: 9999; /* Ensure it's above other content */
            }
            .validation_code {
              position: absolute;
              width: 1px;
              height: 1px;
              overflow: hidden;
              clip: rect(0 0 0 0);
            }

        </style>
        <title>No Login Proposal Entry Form</title>
        
        <script src="/TEST/JavaScript/JQuery371.js"></script>
        <script src="/TEST/ScreenFieldValidation/Utilities/Javascript_utilities.js"></script>
        
        <script>             
            $(function() {
                //Display Question/Answer blocks upon page load:
                $.post('/TEST/ProposalDetails/Public/NoLogin_Proposal_Editor_backend.php',
                    {
                        mode: 'displayQuestionAnswerBlocks',
                        Proposal_details_ID: <?php echo $Proposal_details_ID; ?>,
                        question_classes: JSON.stringify(<?php echo $question_classes; ?>)
                    }, 
                    function(resp){
                        try{
                            $('#divQuestionsAnswers').html(resp);
                        }catch(error){
                            alert(error);
                        }
                                                
                        //Now get all Screenfield Validation records:
                        //-------------------------------------------
                        getAllScreenfieldValidations();                    
                });
                
                
                $("#divQuestionsAnswers").on("click", "#btnHelp", function(){
                    window.open('Help/NoLogin_Proposal_Help.html', 'popupWindow', 'width=700, height=500,scrollbars=yes');
                });
                
                //Change ".btnSaveAnswers" button to red when an answer field is changed:
                $("#divQuestionsAnswers").on("input", ".answer_box", function() {
                    $('#divQuestionsAnswers .btnSaveAnswers').css("background-color", "red");
                });

                //Reset button color when clicked and saves all Questions
                //and current Answers:
                $("#divQuestionsAnswers").on("click", ".btnSaveAnswers", function() {
                    if(!validProposalAnswerFields()){
                        return;
                    }
                           
                    $('#divQuestionsAnswers .tdErrorMessage').text(''); //Clear all error messages on the page.

                    $(this).css("background-color", ""); //Reset color after clicking button on valid fields.

                    var jsonQuickEasyNoLoginProposal = getJsonQuestionsAnswers();
                    $.post('/TEST/ProposalDetails/Public/NoLogin_Proposal_Editor_backend.php',
                        {
                            mode: 'saveQuestionAnswerBlocks',
                            question_classes: JSON.stringify(<?php echo $question_classes; ?>),
                            jsonQuickEasyNoLoginProposal: jsonQuickEasyNoLoginProposal
                        }, 
                        function(resp){
                            var results = '';
                            try{
                                results = JSON.parse(resp);
                            
                                if(results['status'] === 'true'){
                                    alert(results['errMsg'] + ' Remember: Username = Last Name, Password = Email. You will now be redirected to the Login screen.');
                                    window.location.href = "/TEST/Login/Login.php";
                                }else{
                                    alert(results['errMsg']);
                                }
                            }catch(error){
                                alert(error);
                            }
                    });
                });
                
            
                /**   Loops though the Questions/Answers list and builds a JSON array
                 * of the following elements formatted for updating the "Questions" table:
                 * 
                 *    [{“Question_classes_ID”: <id>, 
                 *      “questions_answers”: [{“question”: “<question>”, “answer”: “<answer>”}, . . .]},
                 *     . . .]
                 *
                 * @returns {JSON array} Described above.
                 */
                 function getJsonQuestionsAnswers(){
                     var aryClassesQuestionsAnswers = [];
                     var ary_questions_answers = [];
                     var Question_classes_ID = '';

                     //Loop through each Div with the specified class and extract
                     //values into the array, "ary_questions_answers":
                     var groupQuestion_classes_ID = '';
                     $('#divQuestionsAnswers .question_answer_block').each(function() {
                         var divQuestionRow = $(this).children('.question_row');
                         Question_classes_ID = divQuestionRow.attr('data-Question_classes_ID');

                         //When "Question_classes_ID" changes from the "groupQuestion_classes_ID":
                         if(Question_classes_ID !== groupQuestion_classes_ID){
                             if(groupQuestion_classes_ID !== ''){
                                 aryClassesQuestionsAnswers.push({
                                     'Question_classes_ID': groupQuestion_classes_ID, 'questions_answers': ary_questions_answers});
                                 ary_questions_answers = [];
                             }
                             groupQuestion_classes_ID = Question_classes_ID;
                         }  

                         var question = divQuestionRow.text();
                         var answer = $(this).find('.answer_box').val();
                         ary_questions_answers.push({'question': question, 'answer': answer});

                     });
                     //Build last element of the super array:
                     aryClassesQuestionsAnswers.push({
                         'Question_classes_ID': groupQuestion_classes_ID, 'questions_answers': ary_questions_answers});

                     //Convert the super array to JSON and return it:
                     return JSON.stringify(aryClassesQuestionsAnswers);
                 }

                 /**   Determines whether the passed-in value is a JSON string.
                  * 
                  * @param {object/string} value
                  * @returns {Boolean} -- Whether the passed-in value is a JSON string.
                  */
                 function isJsonString(value){
                     if (typeof value !== "string") return false; // Must be a string

                     try {
                         JSON.parse(value); // Attempt parsing
                         return true; // Valid JSON string
                     } catch (error) {
                         return false; // Invalid JSON
                     }
                 }

                //=======================================================================================
                //                  S c r e e n - F i e l d   V a l i d a t i o n
                //=======================================================================================
                
                /**   Fetches all Screenfield Validation records from the DB.
                 * Upon success, a module-level variable is loaded with an array of records.
                 * Otherwise, an error message is displayed.
                 * 
                 * Note: This function is meant to be run upon page load.
                 */
                var allScreenfieldValidations = [];
                function getAllScreenfieldValidations(){
                    $.post('/TEST/ProposalDetails/Public/NoLogin_Proposal_Editor_backend.php',
                        {
                            mode: 'getAllScreenfieldValidations'
                        },                      
                        function(resp){
                            try{
                                var results = JSON.parse(resp);
                                if(results['status'] === 'true'){
                                    allScreenfieldValidations = results['allScreenfieldValidations'];
                                }else{
                                    alert(results['errMsg']);
                                }
                            }catch(error){
                                alert(error);
                            }
                    });
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
                function validProposalAnswerFields() {
                    // Clear all error messages on the page:
                    $('#divQuestionsAnswers .tdErrorMessage').text('');

                    var validFields = true;
                    $('#divQuestionsAnswers .question_answer_block').each(function() {
                        const question = $(this).find('.question_row').text();
                        const answer = $(this).find('.answer_box').val();
                        var results = Javascript_utilities.isScreenfieldDataValid(question, answer, allScreenfieldValidations);
                        if(results['status'] === 'false'){
                            validFields = false;
                            $(this).find('.tdErrorMessage').text(results['errMsg']);
                        }
                    });

                    if (!validFields) {
                        alert('Some of your fields contain errors! Please fix these before continuing.');
                    }

                    return validFields;
                }// End function, "validProposalAnswerFields".
                
            });//End "$(function(){...});".

        </script>
     </head>
    <body>
<div id="divQuestionsAnswers"></div>        
    </body>
