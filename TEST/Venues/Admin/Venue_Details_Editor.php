<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser, Organizer");
?>
<!DOCTYPE html>
<?php
    $Venues_ID = filter_input(INPUT_POST, 'Venues_ID', FILTER_SANITIZE_NUMBER_INT);
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
                border: 1px solid #ccc;  /* Optional border for better visibility */
            }
            h1{
                text-align: center;
                display: flex;
                align-items: center;     /* vertical centering */
                margin-left: 70px;
            }
            table{
                margin-left: 20px;
            }
            table td{
                vertical-align: top;
            }
            #tblVenueDetails{
                margin-left: 0px;
                width: 100%;
            }
            #tblVenueDetails td{
                padding-top: 30px;
            }
            #tblVenueDetails #nameErrMsg{
                width: 100px;
                margin-left: 20px;
            }
            #tdName, #tdVenueCheck{
                width: 160px;
                color: brown;
                font-size: 18pt;
            }
            #txtName{
                width: 600px;
                padding: 2px;
                font-size: 14pt;
                border-color: black;
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
            .tdErrorMessage, /* This is for the answers. */
            #nameErrMsg,
            #venueCheckErrMsg{
                width: 600px;
                font-size: 12pt;
                color: red;
                margin: 4px;
                text-align: left;
            }
            .button{
                margin: 4px;
                font-size: 12pt; 
                background-color: orange;
            }
            #divSaveAnswers {
              text-align: center;
              color: red;
              font-size: 12pt;
              position: fixed;
              bottom: 20px;   /* Distance from bottom */
              right: 20px;    /* Distance from right */
              width: 150px;
              z-index: 9999; /* Ensure it's above other content */
            }
        </style>
        <script src="/TEST/JavaScript/handsontable.full.min.js?v=15.2"></script>
        <title>Venue Details Editor"</title>
        
        <script src="/TEST/JavaScript/JQuery371.js"></script>
        <script src="/TEST/ScreenFieldValidation/Utilities/Javascript_utilities.js"></script>
        
        <script>             
            $(function() {
                $.post('/TEST/Venues/Admin/Venue_Details_Editor_backend.php',
                    {
                        mode: 'displayVenueDetails',
                        Venues_ID: <?php echo $Venues_ID; ?>
                    }, 
                    function(resp){
                        $("#divPageContent").html(resp);
                         
                        //Now get all Screenfield Validation records:
                        //-------------------------------------------
                        getAllScreenfieldValidations();
                });
                
                $("#divPageContent").on("click", "#btnBackToAllVenues", function() {
                    window.location = "AllVenues.php";
                });    
                
                //Change "#btnSaveVenueDetails" button to red when the "Name" or "Venue Check" field is changed:
                $("#divPageContent").on("input", "#txtName", function() {
                    $("#divPageContent #btnSaveVenueDetails").css("background-color", "red");
                });
                $('#divPageContent').on("input", "#chkVenueCheck", function(){
                    $("#divPageContent #btnSaveVenueDetails").css("background-color", "red");
                });                
                //Change "#btnSaveVenueDetails" button to red when an "answer" field is changed:
                $("#divPageContent").on("input", ".answer_box", function() {
                    $("#divPageContent #btnSaveVenueDetails").css("background-color", "red");
                });
                
                //Save Venue Details:
                $("#divPageContent").on("click", "#btnSaveVenueDetails", function() {
                    if($("#chkDoValidation").prop("checked") && 
                       (!validVenueHeaderFields() || !validVenueAnswerFields())){
                        return;
                    }
                    
                    $('#divPageContent .tdErrorMessage').text(''); //Clear all error messages on the page.
        
                    $(this).css("background-color", ""); //Reset color after clicking button on valid fields.
                    
                    //Save Venue Name, Venue Check, and Venue Answers:
                    //------------------------------------------------
                    var Venues_ID = $('#divPageContent #btnSaveVenueDetails').data("venues_id"); //Note: Must not use the POSTed value here.
                    var name = $('#divPageContent #txtName').val();
                    var venueCheck = $('#divPageContent #chkVenueCheck').is(':checked'); 
                    var jsonVenueQuestionsAnswers = getJsonQuestionsAnswers();
                    var objVenueDetails = {
                        Venues_ID: Venues_ID,
                        name: name,
                        venueCheck: venueCheck,
                        questionsAnswers: jsonVenueQuestionsAnswers
                    };
                    var jsonVenueDetails = JSON.stringify(objVenueDetails);
                   
                    $.post('/TEST/Venues/Admin/Venue_Details_Editor_backend.php',
                        {
                            mode: 'saveVenueDetails',
                            jsonVenueDetails: jsonVenueDetails
                        }, 
                        function(resp){
                            var aryResp = JSON.parse(resp);
                            try{
                                if(aryResp.status === 'true'){
                                    alert('Venue Details saved ok.');
                                    
                                }else{
                                    alert(aryResp.errMsg);
                                }
                             }catch(error){
                                alert(error);
                            }
                    });
                });
            
                /**   Loops though the Questions/Answers list and builds a JSON array
                 * of the following elements formatted for updating the "Questions" table:
                 * 
                 *    [{“question”: “<question>”, “answer”: “<answer>”}, . . .]
                 *
                 * @returns {JSON array} Described above.
                 */
                 function getJsonQuestionsAnswers(){
                     var aryVenueQuestionsAnswers = [];

                     //Loop through each Div with the specified class and extract
                     //values into the array, "ary_questions_answers":
                     $('#divPageContent .question_answer_block').each(function() {
                         var divQuestionRow = $(this).children('.question_row');
                         var question = divQuestionRow.text();
                         var answer = $(this).find('.answer_box').val();
                         aryVenueQuestionsAnswers.push({'question': question, 'answer': answer});
                     });

                     //Convert the super array to JSON and return it:
                     return JSON.stringify(aryVenueQuestionsAnswers);
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
                    $.post('/TEST/Venues/Admin/Venue_Details_Editor_backend.php',
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

                 /**   Checks that the "*Name" field is filled.
                  * 
                  * @returns {boolean} Whether all Venue header fields contain valid data.
                  */
                 function validVenueHeaderFields(){
                     var validFields = true;

                     //Clear all error messages on the header:
                     //---------------------------------------
                     $('#divPageContent #nameErrMsg').text('');

                     //Check the "*Name" field:
                     //----------------------------------------
                     if($('#divPageContent #txtName').val().trim() === ''){
                         $('#divPageContent #nameErrMsg').text('This field is required!');
                         validFields = false;
                     }

                     return validFields;
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
                function validVenueAnswerFields() {
                    // Clear all error messages on the page:
                    $('#divPageContent .tdErrorMessage').text('');

                    var validFields = true;
                    $('#divPageContent .question_answer_block').each(function() {
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
<?php require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Menus/header.php'; ?>
<div id="divPageContent"></div>
</body>
