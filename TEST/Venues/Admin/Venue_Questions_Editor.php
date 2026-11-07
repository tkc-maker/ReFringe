<?PHP 
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser");
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Venue Questions Editor</title>

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
                //Open page with main screen and venue questions:
                //-----------------------------------------------
                $.post('/TEST/Venues/Admin/Venue_Questions_Editor_backend.php',
                    { mode: 'displayVenueQuestionsScreen' },
                    function (resp) {
                        try {
                            $('#divPageContent').html(resp); //Update the div content.
                            $('#divPageContent').show(); //Ensure the div is visible.

                            loadVenueQuestions();
                            $('#divPageContent #selected_question_class').html("Venue Questions:");

                        } catch (error) {
                            alert('Error: ' + error.message);
                        }
                    }
                );
                
                //Update a question:
                //------------------
                $('#divPageContent').on('click', '#venue_questions .btnUpdate', function(){ 
                    //Find the closest textarea to the clicked button and get its data-ordinal attribute:
                    var ordinal = $(this).closest('div').find('textarea').data('ordinal');
                    var question = $(this).closest('div').find('textarea').val();
                    if(question.trim() === ''){ 
                        loadVenueQuestions();
                        
                    }else{
                        $.ajax({
                            type: 'POST',
                            url: '/TEST/Venues/Admin/Venue_Questions_Editor_backend.php',
                            data: {
                                mode: 'updateQuestion',
                                ordinal: ordinal,
                                question: question
                            },
                            dataType: 'json',  //Specify JSON data type.
                            success: function(resp) {
                                if(resp.status === "true"){
                                    alert("Venue question updated ok.");
                                    
                                }else{
                                    alert(resp.errMsg);
                                }
                            },
                            error: function(error) {
                                alert('Error: '+error);
                            }
                        });
                    }
                });
                
                //Delete a question:
                //------------------
                $('#divPageContent').on('click', '#venue_questions .btnDelete', function() {
                    if(!confirm('Are you sure you want to delete this question?')){
                        return;
                    }
                    //Find the closest textarea to the clicked button and get its data-ordinal attribute:
                    var ordinal = $(this).closest('div').find('textarea').data('ordinal');
                    $.ajax({
                        type: 'POST',
                        url: '/TEST/Venues/Admin/Venue_Questions_Editor_backend.php',
                        data: {
                            mode: 'deleteQuestion',
                            ordinal: ordinal
                        },
                        dataType: 'json',  //Specify JSON data type.
                        success: function(resp) {
                            if(resp.status === "true"){
                                loadVenueQuestions();
                                
                            }else{
                                alert(resp.errMsg);
                            }
                         },
                        error: function(error) {
                            alert('Error: '+error);
                        }
                    });
                });
                
                //Move a question up in the list:
                //-------------------------------
                $('#divPageContent').on('click', '#venue_questions .btnUp', function(){
                    //Find the closest textarea to the clicked button and get its data-ordinal attribute:
                    var ordinal = $(this).closest('div').find('textarea').data('ordinal');
                    var direction = 'up';
                    moveQuestionUpDown(ordinal, direction);
                });
                
                //Move a question down in the list:
                //---------------------------------
                $('#divPageContent').on('click', '#venue_questions .btnDown', function(){
                    //Find the closest textarea to the clicked button and get its data-ordinal attribute:
                    var ordinal = $(this).closest('div').find('textarea').data('ordinal');
                    var direction = 'down';
                    moveQuestionUpDown(ordinal, direction);
                });
                
                //Add a new question to the list:
                //-------------------------------
                $('#divPageContent').on('click','#btnAddNewQuestion', function(){
                    if($('#divPageContent #newQuestion').val().trim() === ''){
                        $('#divPageContent #newQuestion').val('');    
                        
                    }else{
                        var new_question= {"question": $('#divPageContent #newQuestion').val(), "answer": ""};

                        loadQuestionsAfterUpdate(new_question);
                    }                    
                });
                                
            });    
            
            function loadVenueQuestions(){
                $.ajax({
                    type: 'POST',
                    url: '/TEST/Venues/Admin/Venue_Questions_Editor_backend.php',
                    data: {
                        mode: 'loadVenueQuestions'
                    },
                    dataType: 'json',  //Specify JSON data type
                    success: function(resp) {
                        if(resp.status === "true"){
                            populateVenueQuestions(resp.questionsAnswers);
                            
                        }else{
                            alert(resp.errMsg);
                        }
                    },
                    error: function(error) {
                        alert('Error: '+error);
                    }
                });
            }
            
            function loadQuestionsAfterUpdate(new_question){
                $.ajax({
                    type: 'POST',
                    url: '/TEST/Venues/Admin/Venue_Questions_Editor_backend.php',
                    data:{
                        mode: 'addNewQuestion',
                        new_question: JSON.stringify(new_question)
                    },
                    dataType: 'json',  //Specify JSON data type
                    success: function(resp) {
                        loadVenueQuestions();
                        $('#divPageContent #newQuestion').val('');
                        
                        if(resp.status === "true"){
                            alert("New question added ok.");
                            
                        }else{
                            alert(resp.errMsg);
                        }
                    },
                    error: function(error) {
                        alert('Error: '+error);
                    }
                });
            }
            
            function moveQuestionUpDown(ordinal, direction){
                $.ajax({
                    type: 'POST',
                    url: '/TEST/Venues/Admin/Venue_Questions_Editor_backend.php',
                    data: {
                        mode: 'moveQuestionUpDown',
                        ordinal: ordinal,
                        direction: direction
                    },
                    dataType: 'json',  //Specify JSON data type
                    success: function(resp) {
                        if(resp.status === "true"){
                            populateVenueQuestions(JSON.parse(resp.questionsAnswers));
                        }else{
                            alert(resp.errMsg);
                        }
                    },
                    error: function(error) {
                        alert('Error: Question could not be moved. '+error);
                    }
                });
            }
            
            function populateVenueQuestions(questionsAnswers){
                try{
                    $("#venue_questions").html('');
                    var i = 1;
                    questionsAnswers.forEach(item => {
                        var q_field = '<textarea class="question_box" data-ordinal="'+(i++)+'">'+item.question+'</textarea>';
                        $("#venue_questions").html($("#venue_questions").html()+
                                '<div class="question_row" style="display: flex; align-items: center;">'+q_field+
                                '<input type="button" class="btnUpdate" value="Update"/>'+
                                '<input type="button" class="btnDelete" value="Delete"/>'+
                                '<input type="button" class="btnUp" value="up"/>'+
                                '<input type="button" class="btnDown" value="down"/></div>');
                    });
                }catch(error){
                    alert('Error: '+error);
                }
            }                

        </script>

    </head>
    <body>
        <?php require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Menus/header.php'; ?>
        <div id="divPageContent"></div>   
    </body>
</html>
