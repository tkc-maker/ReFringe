<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser, Organizer");
?>
<!DOCTYPE html>
<?php
    $Proposal_details_ID = $_SESSION['PD_Proposal_details_ID'];
    $Users_ID = $_SESSION['PD_Users_ID'];
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
            #divQuestionClasses{
                margin: 5px;
                position: relative;
                width: 600px;
                min-height: 475px;
                max-height: 475px;
                overflow: hidden; 
                border: 1px solid #ccc; /* Optional border for better visibility */
            }
            h1, #divBackToSearch, #divProposer{
                text-align: left;
                margin: 5px;
            }
            #titleErrMsg{
                width: 100px;
                margin: 5px;
            }
            .fieldHeading{
                color: brown;
                font-size: 16pt;
                margin: 4px;
            }
            .proposerInfo{
                color:blue;
                font-size: 14pt;
            }
            #titleErrMsg{
                width: 100px;
                margin: 5px;
            }
            #divTitleHeading, #divTableHeading{
                margin: 5px;
                text-align: left;
                width: 100%;
                color: darkgreen;
                font-size: 18pt;
            }
            #divTitleText{
                margin: 5px;
            }
            #txtTitle{
                width: 600px;
                padding: 2px;
                font-size: 14pt;
                border-color: black;
            }
            .buttonRow{
                margin: 5px;
                text-align: left;
            }
            #txtTitle{
                width: 600px;
                padding: 2px;
                font-size: 14pt;
                border-color: black;
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
            .tdErrorMessage, /* This is for the answers. */
            #titleErrMsg,
            #questionClassesErrMsg,
            #scheduledMsg{
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
              width: 250px;
              z-index: 9999; /* Ensure it's above other content */
            }
        </style>
        <script src="/TEST/JavaScript/handsontable.full.min.js?v=15.2"></script>
        <title>Proposal Details Editor"</title>
        
        <script src="/TEST/JavaScript/JQuery371.js"></script>
        <script src="/TEST/ScreenFieldValidation/Utilities/Javascript_utilities.js"></script>
        
        <script>             
            $(function() {
                $.post('/TEST/ProposalDetails/Admin/Proposal_Editor_backend.php',
                    {
                        mode: 'displayProposalDetails'
                    }, 
                    function(resp){
                        $("#divPageContent").html(resp);
                        
                        //Initially, display an empty HOT, and initialize the "hot..." variables:
                        //-----------------------------------------------------------------------
                        displayHotQuestionClasses([]);                   
                        
                        //Now populate the two Handsontables:
                        //-----------------------------------
                        displayAllQuestionClassesFromDb();                        
                        
                        //Now get all Screenfield Validation records:
                        //-------------------------------------------
                        getAllScreenfieldValidations();
                });
                
                $('#divPageContent').on('click', '#btnBackToSearchProposals', function(){
                    window.location = '/TEST/ProposalDetails/Admin/Search_Proposals.php';
                });
                
                $('#divPageContent').on('click', '#btnBackToShowAllProposals', function(){
                    window.location = '/TEST/ProposalDetails/Admin/Show_All_Proposals.php';
                });
                
                $("#divPageContent").on("click", "#btnHelp", function(){
                    window.open('Help/Proposal_Editor_Admin_Help.html', 'popupWindow', 'width=700, height=500,scrollbars=yes');
                });
                
                const rowHeight = 30; //Approximate row height.
                const extraPadding = 50; //Extra space for UI elements.

                function getDynamicHeight(hot) {
                    const rowCount = hot.countRows();
                    return Math.min(rowHeight * rowCount + extraPadding, 450); //Max height in px.
                }
                
                var hotQuestionClasses; //Variable for use after this function is called.
                function displayHotQuestionClasses(data){
                    const container = document.querySelector('#divPageContent #divQuestionClasses');
                    
                    hotQuestionClasses = new Handsontable(container, {
                      data: data,
                      rowHeaders: true,
                      colHeaders: ['Include?','','Name'],
                      hiddenColumns: { columns: [1], indicators: false },        
                      autoWrapRow: true,
                      autoWrapCol: true,
                      columnSorting: true, //Enable sorting.
                      filters: false,
                      dropdownMenu: false,
                      readOnly: false, //Ensure the table is editable.
                      disableVisualSelection: false, //Allow cell selections, which also enable hooks.
                      columns: [
                        {type: 'checkbox', width: 100, className: "htCenter"}, //Checkbox column.
                        {type: 'text'},
                        {type: 'text', width: 430}
                      ],
                      cells: function (row, col) { 
                          const cellProperties = {};
                          //Dissable the checkbox on the "Quick Easy No Login Proposal" Question Class.
                          if (col === 0 && row === 0) { 
                              cellProperties.readOnly = true;                             
                              cellProperties.renderer = Handsontable.renderers.CheckboxRenderer;
                              cellProperties.renderer = function(instance, td, row, col, prop, value, cellProps){ 
                                  Handsontable.renderers.CheckboxRenderer.apply(this, arguments);
                                  td.style.backgroundColor = '#e0e0e0'; // light gray
                              }; 
                          };
                          return cellProperties;
                      },
                      afterOnCellMouseDown: function(event, coords){
                         if(coords.col === 0 && coords.row !== 0){ //Detect checkbox clicks, except on first row.
                            $("#divPageContent #btnShowQandA").css("background-color", "red");
                         }
                      },
                      licenseKey: 'non-commercial-and-evaluation' //For non-commercial use only.
                    });

                };//End function, "displayHotQuestionClasses".
                
                /**   Refreshes the Question Classes Handsontable from the database.
                 * Should an error occur, it is displayed in an alert and in "divPageContent".
                 */
                function displayAllQuestionClassesFromDb(){
                    $.post('/TEST/ProposalDetails/Admin/Proposal_Editor_backend.php',
                        {
                            mode: 'displayAllQuestionClasses'
                        }, 
                        function(resp){
                            try{
                                if(resp !== false){
                                    var aryData = [];
                                    var data = JSON.parse(resp);
                                    data.forEach(item => {
                                        if (item.ID && item.name) { //Ensure both ID and name exist.
                                            var include = false;
                                            aryData.push([include, item.ID, item.name]);
                                        }
                                    });
                                    hotQuestionClasses.loadData(aryData);
                                    hotQuestionClasses.updateSettings({ height: getDynamicHeight(hotQuestionClasses) });
                         
                                    //Populate Title, and make selections on Hots
                                    //(only if both HOTs have been loaded:
                                    //-------------------------------------------
                                    displayTitleAndSelectionsFromDb();
                               }    

                            }catch(error){
                                alert(error);
                                $('#divPageContent').html(error);
                            }
                    });                     
                }//End function, "displayAllQuestionClassesFromDb".

                /**   Displays the saved title, and selects the saved checkboxes
                 * (actually, the corresponding "ID" values to those saved) 
                 * on the Handsontable. Should errors occur, they first appear
                 * in an alert box, and then on the screen.
                 * 
                 * @returns {undefined}
                 */
                //This would be different from the one POSTed if the Proposal Details
                //are being created anew, and not simply edited:
                var newProposal_details_ID = '';
                function displayTitleAndSelectionsFromDb(){
                    //Only run this function if the HOT contains rows:
                    if(hotQuestionClasses.getData().length === 0){
                        return;
                    }
                    
                    $.post('/TEST/ProposalDetails/Admin/Proposal_Editor_backend.php',
                        {
                            mode: 'displayTitleAndSelections',
                            Proposal_details_ID: <?PHP echo $Proposal_details_ID; ?>,
                            Users_ID: <?PHP echo $Users_ID; ?>
                        }, 
                        function(resp){
                            try{
                                var data = JSON.parse(resp); 
                                var status = data.status;
                                
                                if(status === 'true'){
                                    //Newly created Proposal Details come in as an object,
                                    //but revisited ones come in as JSON strings:
                                    var proposalDetails = '';
                                    if(isJsonString(data.proposalDetails)){
                                        proposalDetails = JSON.parse(data.proposalDetails);                                        
                                    }else if(typeof data.proposalDetails === "object" && data.proposalDetails !== null){
                                        proposalDetails = data.proposalDetails;                                        
                                    }else{
                                       alert("\"data.proposalDetails\" is neither a JSON string or an object.");
                                    }
                                    
                                    newProposal_details_ID = proposalDetails.ID;
                                    var title = unescapeHtml(proposalDetails.title);                                   
                                    var question_classes = JSON.parse(proposalDetails.question_classes);
                                    
                                    //Display "title":
                                    //----------------
                                    $('#divPageContent #txtTitle').val(title);

                                    //Select checkboxes on the "Question Classes" Hot:
                                    //------------------------------------------------
                                    try{
                                        aryData = hotQuestionClasses.getData();
                                        aryData.forEach((item, rowIndex) => {
                                            if (question_classes.some(itemQuestionClasses => parseInt(itemQuestionClasses.ID) === item[1])) { //Check if ID exists in question_classes.
                                                hotQuestionClasses.setDataAtCell(rowIndex, 0, true); //Update checkbox column.
                                            }
                                        });
                                    }catch(e){}
                                
                                    // See function definition below:
                                    displayScheduledCheck(newProposal_details_ID);
                                
                                }else if(status === false){
                                    var errMsg = data.errMsg;
                                    alert(errMsg);
                                }
                                
                            }catch(error){
                                alert(error);
                            }
                    });
                }//End function, "displayTitleAndSelectionsFromDb".
                
                /**   Checks if the current proposal is already a scheduled event.
                 * If it is, then the red word, "Sheduled", is displayed to the 
                 * right of the "Schedule Event" button. Otherwise, nothing is displayed.
                 * If an error has occurred, it is displayed in an alert(...)
                 * 
                 * Note: This function can only be run after a "Proposal_details_ID"
                 *       has been retrieved or created.
                 * 
                 * @param {int} Proposal_details_ID
                 */ 
                 function displayScheduledCheck(Proposal_details_ID){
                    $.post('/TEST/ProposalDetails/Admin/Proposal_Editor_backend.php',
                        {
                            mode: 'displayScheduledCheck',
                            Proposal_details_ID: <?PHP echo $Proposal_details_ID; ?>
                        }, 
                        function(resp){
                            try{
                                var data = JSON.parse(resp); 
                                var status = data.status;

                                if(status === 'true'){
                                    $('#divPageContent #scheduledMsg').text('Scheduled');
                                }else{
                                    $('#divPageContent #scheduledMsg').text('');
                                    if(data.errMsg){
                                       alert(data.errMsg); 
                                    }
                                }
                            }catch(error){
                                alert(error);
                            }
                    });
                }//End function, "displayScheduledCheck".
                
                /**   Utility to convert HTML entities into readable text.
                 * @param {type} text
                 * @returns {jQuery}                 */
                function unescapeHtml(text) {
                  return $('<div>').html(text).text();
                } 
                
                //Change "#btnShowQandA" button to red when the title field is changed:
                $("#divPageContent").on("input", "#txtTitle", function() {
                    $("#divPageContent #btnShowQandA").css("background-color", "red"); // Change button color
                });
                
                //Save Proposal Details and display Question/Answer blocks:
                $("#divPageContent").on("click", "#btnShowQandA", function() {
                    if(!validProposalHeaderFields()){
                        return;
                    }
        
                    $(this).css("background-color", ""); //Reset color after clicking button.
                    
                    //Save Proposal Title, selected Question Classes, and Display Question/Answer blocks:
                    //-----------------------------------------------------------------------------------
                    var title = $('#divPageContent #txtTitle').val();
                    var jsonSelectedQuestionClasses = JSON.stringify(getCheckedRows(hotQuestionClasses));                   
                    
                    $.post('/TEST/ProposalDetails/Admin/Proposal_Editor_backend.php',
                        {
                            mode: 'displayQuestionAnswerBlocks',
                            Proposal_details_ID: newProposal_details_ID,
                            title: title,
                            jsonSelectedQuestionClasses: jsonSelectedQuestionClasses
                        }, 
                        function(resp){
                            try{
                                $('#divQuestionsAnswers').html(resp);
                            }catch(error){
                                alert(error);
                            }
                    });
                });
                
                $("#divPageContent").on("click", "#btnScheduleEvent", function(){
                    $.post('/TEST/ProposalDetails/Admin/Proposal_Editor_backend.php',
                        {
                            mode: 'scheduleEvent',
                            Proposal_details_ID: newProposal_details_ID
                        }, 
                        function(resp){
                            if(resp !== ''){
                                var results = JSON.parse(resp);
                                
                                if(results['status'] === 'true'){
                                    alert('New Event Scheduled ok.');

                                }else if(results['errMsg']){
                                    alert(results['errMsg']);
                                }
                                if(!results['errMsg']){
                                    window.location = '/TEST/EventSchedule/Admin/Calendar.php';
                                }
                            }else{
                                window.location = '/TEST/EventSchedule/Admin/Calendar.php';
                            }
                    });
                });
                
                //Change ".btnSaveAnswers" button to red when the title field is changed:
                $("#divQuestionsAnswers").on("input", ".answer_box", function() {
                    $("#divQuestionsAnswers .btnSaveAnswers").css("background-color", "red");
                });

                $("#divQuestionsAnswers").on("change", ".chkDoValidation", function(){
                    //Get the state of the checkbox that triggered the event:
                    var isChecked = $(this).prop("checked");

                    //Set all checkboxes with that class to the same state:
                    $(".chkDoValidation").prop("checked", isChecked);
                });

                //Reset button color when clicked and saves all Questions
                //and current Answers:
                $("#divQuestionsAnswers").on("click", ".btnSaveAnswers", function() {
                    if($(".chkDoValidation").prop("checked") &&
                       !validProposalAnswerFields()){
                        return;
                    }

                    $('#divQuestionsAnswers .tdErrorMessage').text(''); //Clear all error messages on the page.

                    $(this).css("background-color", ""); //Reset color after clicking button on valid fields.

                    var jsonClassesQuestionsAnswers = getJsonQuestionsAnswers();
                    $.post('/TEST/ProposalDetails/Admin/Proposal_Editor_backend.php',
                        {
                            mode: 'saveQuestionAnswerBlocks',
                            Proposal_details_ID: newProposal_details_ID,
                            jsonClassesQuestionsAnswers: jsonClassesQuestionsAnswers
                        }, 
                        function(resp){ 
                            try{
                                var results = JSON.parse(resp);
                                if(results['status'] === true){
                                    alert('Answers saved OK.');
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

                 /**   Scans the passed-in HOT for rows that have been checked (first column). 
                  * Stores each row found into an array of rows for use by subsequent functions.
                  * 
                  * Note: Each row contains an associative array of 'ID' and 'name', consistent
                  *       with the columns of the HOTs.
                  * 
                  * @param {Handsontable} hot -- A Handsontable instance
                  * @returns {Array of Arrays} Data to be saved or deleted.
                  *
                  */
                 function getCheckedRows(hot) {
                     var checkedRows = [];

                     // Loop through all rows
                     for (let rowIndex = 0; rowIndex < hot.countRows(); rowIndex++) {
                         const isChecked = hot.getDataAtCell(rowIndex, 0); //Get the value from the checkbox column.

                         if (isChecked === true) { //Check if the checkbox is marked.
                             var row = hot.getDataAtRow(rowIndex).slice(1); //Exclude the checkbox column.

                             checkedRows.push({'ID': row[0], 'name': row[1]});
                         }
                     }
                     return checkedRows;
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
                    $.post('/TEST/ProposalDetails/Admin/Proposal_Editor_backend.php',
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

                 /**   Checks that the "*Title" field is filled, and that at least one item
                  * has been checked on the "*Question Classes" HOT.
                  * 
                  * @returns {boolean} Whether all Proposal header fields contain valid data.
                  */
                 function validProposalHeaderFields(){
                     var validFields = true;

                     //Clear all error messages on the header:
                     //---------------------------------------
                     $('#divPageContent #titleErrMsg').text('');

                     //First check the "*Proposal Title" field:
                     //----------------------------------------
                     if($('#divPageContent #txtTitle').val().trim() === ''){
                         $('#divPageContent #titleErrMsg').text('This field is required!');
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
<?php require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Menus/header.php'; ?>
<div id="divPageContent"></div>
<br>
<div id="divQuestionsAnswers"></div>        
    </body>
