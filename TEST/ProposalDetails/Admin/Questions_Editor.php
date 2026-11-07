<?PHP 
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser");
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Questions Editor</title>

        <style>
            #divWarning{
                color: red;
                font-size: 12pt;
                font-weight: bold;
                margin: 4px;
                width: 800px;
            }
            .selection {
                color: brown;
                font-size: 18pt;
                margin: 4px;
            }
            #selected_question_class, #lblPrepend, #lblAppend{
                color: blue;
                font-size: 14pt;
            }
            #radPrepend, #radAppend{
                margin: 8px;
            }
            #question_classes #dd_question_classes, 
            #question_classes_2 #dd_question_classes{
                font-size: 12pt;
                width: 200px;
            }
            #tdAttach{
                text-align: center;
            }
            #divNewQuestion{
                margin: 5px;
                display: flex;
                align-items: center; /* Vertically align items */
            }
            #newQuestion{
                width: 600px;
                font-size: 14pt;
                margin-right: 8px;
            }
            #btnAddNewQuestion, #btnAttachQuestionClass{
                font-size: 12pt; 
                background-color: orange;
            }
            #question_row{
                display: flex;
                align-items: center;
            }
            .question_box{
                width: 600px;
                font-size: 14pt;
                margin: 4px;
            }
            .btnUpdate, .btnDelete, .btnUp, .btnDown{
                margin: 4px;
                font-size: 12pt; 
                background-color: orange;
            }
            table{
                width: 800px;
            }
            table td{
                vertical-align: middle;
            }

        </style>
        <script src="/TEST/JavaScript/JQuery371.js"></script>
        <script> 
            
            $(function() {
                //Open page with main screen and question classes:
                //------------------------------------------------
                $.post('/TEST/ProposalDetails/Admin/Questions_Editor_backend.php',
                    { mode: 'displayQuestionsScreen' },
                    function (resp) {
                        try {
                            $('#divPageContent').html(resp); //Update the div content.
                            $('#divPageContent').show(); //Ensure the div is visible.
                            
                            //Preselect top dropdown item and display:
                            var selectedQuestion_class = $("#divPageContent #question_classes").find('option:selected').text().trim();
                            var selectedQuestion_classes_ID = $("#divPageContent #question_classes").find('option:selected').val();

                            loadQuestions(selectedQuestion_classes_ID);
                            $('#divPageContent #selected_question_class').html(selectedQuestion_class + " Questions:");

                        } catch (error) {
                            alert('Error: ' + error.message);
                        }
                    }
                );
                
                //Display user's selection:
                //-------------------------
                $('#divPageContent').on('change', '#question_classes', function() {
                    var selectedQuestion_class = $(this).find('option:selected').text();
                    var selectedQuestion_classes_ID = $(this).find('option:selected').val();
                    $('#divPageContent #selected_question_class').html(selectedQuestion_class + " Questions:");
                    
                    loadQuestions(selectedQuestion_classes_ID);
                });
                
                //Update a question:
                //------------------
                $('#divPageContent').on('click', '#selected_questions .btnUpdate', function(){ 
                    var selectedQuestion_classes_ID = $('#divPageContent #question_classes').find('option:selected').val();
                    //Find the closest textarea to the clicked button and get its data-ordinal attribute:
                    var ordinal = $(this).closest('div').find('textarea').data('ordinal');
                    var question = $(this).closest('div').find('textarea').val();
                    if(question.trim() === ''){ 
                        loadQuestions(selectedQuestion_classes_ID);
                        
                    }else{
                        $.ajax({
                            type: 'POST',
                            url: '/TEST/ProposalDetails/Admin/Questions_Editor_backend.php',
                            data: {
                                mode: 'updateQuestion',
                                Question_classes_ID: selectedQuestion_classes_ID, 
                                ordinal: ordinal,
                                question: question
                            },
                            dataType: 'json',  //Specify JSON data type.
                            success: function(resp) {
                                alert(resp);
                            },
                            error: function(resp) {
                                alert(resp);
                            }
                        });
                    }
                });
                
                //Delete a question:
                //------------------
                $('#divPageContent').on('click', '#selected_questions .btnDelete', function() {
                    if(!confirm('Are you sure you want to delete this question?')){
                        return;
                    }
                    var selectedQuestion_classes_ID = $('#divPageContent #question_classes').find('option:selected').val();
                    //Find the closest textarea to the clicked button and get its data-ordinal attribute:
                    var ordinal = $(this).closest('div').find('textarea').data('ordinal');
                    $.ajax({
                        type: 'POST',
                        url: '/TEST/ProposalDetails/Admin/Questions_Editor_backend.php',
                        data: {
                            mode: 'deleteQuestion',
                            Question_classes_ID: selectedQuestion_classes_ID, 
                            ordinal: ordinal
                        },
                        dataType: 'json',  //Specify JSON data type.
                        success: function(resp) {
                           alert(resp);
                           loadQuestions(selectedQuestion_classes_ID);
                         },
                        error: function(resp) {
                            alert(resp);
                        }
                    });
                });
                
                //Move a question up in the list:
                //-------------------------------
                $('#divPageContent').on('click', '#selected_questions .btnUp', function(){
                    var varQuestion_classes_ID = $('#divPageContent #question_classes').find('option:selected').val();
                    //Find the closest textarea to the clicked button and get its data-ordinal attribute:
                    var ordinal = $(this).closest('div').find('textarea').data('ordinal');
                    var direction = 'up';
                    moveQuestionUpDown(varQuestion_classes_ID, ordinal, direction);
                });
                
                //Move a question down in the list:
                //---------------------------------
                $('#divPageContent').on('click', '#selected_questions .btnDown', function(){
                    var varQuestion_classes_ID = $('#divPageContent #question_classes').find('option:selected').val();
                    //Find the closest textarea to the clicked button and get its data-ordinal attribute:
                    var ordinal = $(this).closest('div').find('textarea').data('ordinal');
                    var direction = 'down';
                    moveQuestionUpDown(varQuestion_classes_ID, ordinal, direction);
                });
                
                //Add a new question to the list:
                //-------------------------------
                $('#divPageContent').on('click','#btnAddNewQuestion', function(){
                    var selectedQuestion_classes_ID = $('#divPageContent #question_classes').find('option:selected').val();

                    if($('#divPageContent #newQuestion').val().trim() === ''){
                        $('#divPageContent #newQuestion').val('');    
                        
                    }else{
                        var new_question= {"question": $('#divPageContent #newQuestion').val(), "answer": ""};

                        loadQuestionsAfterUpdate(selectedQuestion_classes_ID, new_question);
                    }                    
                });
                
                //Attaching questions from another class to the current list:
                //-----------------------------------------------------------
                $('#divPageContent').on('click', '#btnAttachQuestionClass',function(){
                    if(!confirm('Are you sure you want to attach all questions from the new question class to the main one?')){
                        exit();
                    }
                    var mainQuestion_classes_ID = $('#divPageContent #question_classes').find('option:selected').val();
                    var attachQuestion_classes_ID = $('#divPageContent #question_classes_2').find('option:selected').val();
                    var attachDirection = $('#divPageContent input[name="radAttach"]:checked').val();
                    
                    $.ajax({
                        type: 'POST',
                        url: '/TEST/ProposalDetails/Admin/Questions_Editor_backend.php',
                        data: {
                            mode: 'attachQuestionClass',
                            mainQuestion_classes_ID: mainQuestion_classes_ID,
                            attachQuestion_classes_ID: attachQuestion_classes_ID,
                            attachDirection: attachDirection
                        },
                        dataType: 'json',  //Specify JSON data type
                        success: function(resp) {
                            populateSelectedQuestions(resp);
                        },
                        error: function(resp) {
                            alert('Error: '+resp);
                        }
                    });
                });
                
            });    
            
            function loadQuestions(selectedQuestion_classes_ID){
                $.ajax({
                    type: 'POST',
                    url: '/TEST/ProposalDetails/Admin/Questions_Editor_backend.php',
                    data: {
                        mode: 'loadSelectedQuestions',
                        Question_classes_ID: selectedQuestion_classes_ID
                    },
                    dataType: 'json',  //Specify JSON data type
                    success: function(resp) {
                        populateSelectedQuestions(resp);
                    },
                    error: function(resp) {
                        alert('Error: '+resp);
                    }
                });
            }
            
            function loadQuestionsAfterUpdate(selectedQuestion_classes_ID, new_question){
                $.ajax({
                    type: 'POST',
                    url: '/TEST/ProposalDetails/Admin/Questions_Editor_backend.php',
                    data:{
                        mode: 'addNewQuestion',
                        Question_classes_ID: selectedQuestion_classes_ID, 
                        new_question: JSON.stringify(new_question)
                    },
                    dataType: 'json',  //Specify JSON data type
                    success: function(resp) {
                        loadQuestions(selectedQuestion_classes_ID);
                        $('#divPageContent #newQuestion').val('');
                        
                        alert(resp);
                    },
                    error: function(resp) {
                        alert('Error: '+resp);
                    }
                });
            }
            
            function moveQuestionUpDown(varQuestion_classes_ID, ordinal, direction){
                $.ajax({
                    type: 'POST',
                    url: '/TEST/ProposalDetails/Admin/Questions_Editor_backend.php',
                    data: {
                        mode: 'moveQuestionUpDown',
                        Question_classes_ID: varQuestion_classes_ID,
                        ordinal: ordinal,
                        direction: direction
                    },
                    dataType: 'json',  //Specify JSON data type
                    success: function(resp) {
                        populateSelectedQuestions(resp);
                    },
                    error: function(resp) {
                        alert('Error: Question could not be moved. '+resp);
                    }
                });
            }
            
            function populateSelectedQuestions(resp){
                try{
                    $("#selected_questions").html('');
                    var i = 1;
                    resp.forEach(item => {
                        var q_field = '<textarea class="question_box" data-ordinal="'+(i++)+'">'+item.question+'</textarea>';
                        $("#selected_questions").html($("#selected_questions").html()+
                                '<div class="question_row" style="display: flex; align-items: center;">'+q_field+
                                '<input type="button" class="btnUpdate" value="Update"/>'+
                                '<input type="button" class="btnDelete" value="Delete"/>'+
                                '<input type="button" class="btnUp" value="up"/>'+
                                '<input type="button" class="btnDown" value="down"/></div>');
                    });
                }catch(error){
                    alert('Error: '+resp);
                }
            }                

        </script>

    </head>
    <body>
        <?php require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Menus/header.php'; ?>
        <div id="divPageContent"></div>   
    </body>
</html>
