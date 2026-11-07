<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    
    $title = trim(htmlspecialchars($_GET['title'], ENT_QUOTES, 'UTF-8'));
    $Calendar_ID = filter_input(INPUT_GET, 'calendar_id', FILTER_SANITIZE_NUMBER_INT);
    $day = filter_input(INPUT_GET, 'day', FILTER_SANITIZE_NUMBER_INT);
    $start = trim(htmlspecialchars($_GET['start'], ENT_QUOTES, 'UTF-8'));
    $end = trim(htmlspecialchars($_GET['end'], ENT_QUOTES, 'UTF-8'));
    $tag = trim(htmlspecialchars($_GET['tag'], ENT_QUOTES, 'UTF-8'));
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            #divPageContent{
                width: 100%;
                text-align: left;
            }
            h1{
                display: flex;
                align-items: center;     /* vertical centering */
                margin-left: 20px;
            }
            table{
                width: 1000px;
                padding: 5px;
                margin: 10px;
            }
            table td{
                padding: 5px;
            }
            .label{
                color: darkgreen;
                font-size: 18pt;
                margin: 4px;
            }
            select, input{
                width: 100px;
                font-size: 12pt;
            }
            #errMsg{
                color: red;
            }
            #txtTag{
                width: 200px;
            }
            .button{
                margin: 4px;
                font-size: 12pt; 
                background-color: orange;
                width:fit-content;
            }
        </style>
        <script src="/TEST/JavaScript/JQuery371.js"></script>
        <script src="/TEST/ScreenFieldValidation/Utilities/Javascript_utilities.js"></script>
        <title>Edit Calendar Event</title>
        <script>
            $(function() {
                $.post('/TEST/EventSchedule/Admin/Calendar_Event_Editor_backend.php',
                    {
                        mode: 'displayCalendarEventFields',
                        title: '<?php echo $title; ?>',
                        day: '<?php echo $day; ?>',
                        start: '<?php echo $start; ?>',
                        end: '<?php echo $end; ?>',
                        tag: '<?php echo $tag; ?>'
                    }, 
                    function(resp){
                        $('#divPageContent').html(resp);
                });  
                
                //Change "#btnSaveCalendarEvent" button to red when the "Day" field is changed:
                $("#divPageContent").on("change", "#selDay", function() {
                    $("#divPageContent #btnSaveCalendarEvent").css("background-color", "red"); // Change button color
                });
                //Change "#btnSaveCalendarEvent" button to red when the "Start" field is changed:
                $("#divPageContent").on("input", "#txtStart", function() {
                    $("#divPageContent #btnSaveCalendarEvent").css("background-color", "red"); // Change button color
                });
                //Change "#btnSaveCalendarEvent" button to red when the "End" field is changed:
                $("#divPageContent").on("input", "#txtEnd", function() {
                    $("#divPageContent #btnSaveCalendarEvent").css("background-color", "red"); // Change button color
                });
                //Change "#btnSaveCalendarEvent" button to red when the "Group Tag" field is changed:
                $("#divPageContent").on("input", "#txtTag", function() {
                    $("#divPageContent #btnSaveCalendarEvent").css("background-color", "red"); // Change button color
                });

                $("#divPageContent").on("click", "#btnSaveCalendarEvent", function() {
                    if(!validCalendarEventFields()){
                        return;
                    }

                    $.post('/TEST/EventSchedule/Admin/Calendar_Event_Editor_backend.php',
                        {
                            mode: 'saveCalendarEvent',
                            calendar_id: '<?php echo $Calendar_ID; ?>',
                            day: $('#divPageContent #selDay').val(),
                            start: $('#divPageContent #txtStart').val(),
                            end: $('#divPageContent #txtEnd').val(),
                            tag: $('#divPageContent #txtTag').val()
                        }, 
                        function(resp){
                            try{
                                var results = JSON.parse(resp);

                                if(results.status === 'true'){
                                    //Upon a successful save, revert the button's color back to orange:
                                    $("#divPageContent .btnSaveCalendarEvent").css("background-color", "orange");
                                    alert("Calendar Event saved ok.");
                                    
                                    window.parent.postMessage('refreshDivs', '*');

                                }else{
                                    alert(results.errMsg);
                                }    

                            }catch(error){
                                alert(error);
                            }
                    });  
                    
                });    
                
                //=======================================================================================
                //                  S c r e e n - F i e l d   V a l i d a t i o n
                //=======================================================================================

                /**   Checks that the Calendar Event fields are filled with valid data".
                 * Should an error be detected, it is spelled out in red beneath the fields.
                 * 
                 * @returns {boolean} Whether Calendar Event fields contain valid data.
                 */
                function validCalendarEventFields(){
                    var validFields = true;
                    
                    //Get values to validate:
                    const timeStart = $('#divPageContent #txtStart').val().trim();
                    const timeEnd = $('#divPageContent #txtEnd').val().trim();

                    //Clear the error message field:
                    $('#divPageContent #errMsg').html('');
                    
                    const objStart = testTime(timeStart, "The \"Start\" time is required, and must be of the form, HH:mm.");
                    const objEnd = testTime(timeEnd, "The \"End\" time is required, and must be of the form, HH:mm.");

                    if(objStart.status === "false" && objEnd.status === "false"){
                        $('#divPageContent #errMsg').html(objStart.errMsg+'<br>'+objEnd.errMsg);
                        validFields = false;
                    }else if(objStart.status === "false"){
                        $('#divPageContent #errMsg').html(objStart.errMsg);
                        validFields = false;
                    }else if(objEnd.status === "false"){
                        $('#divPageContent #errMsg').html(objEnd.errMsg);
                        validFields = false;
                    }
                    
                    return validFields;
                }//End function, "validEventEditorFields".
                
                /**   Determines whether the passed in value is a valid time (a string
                 * of the form "HH:ss"). An object will be returned of the following form:
                 * 
                 * { status: <a boolean string indicating the validity of the input>
                 *   errMsg: <an error message if the input is invalid; otherwise ""> }
                 * 
                 * @param {string} strTime -- A string of the form, "HH:ss".
                 * @param {string} errMsg -- An error message, should the input 
                 *    not be a valid time.
                 * @returns {object} Described above.
                 */
                function testTime(strTime, errMsg){
                    //Test and report on any errors:
                    const regex = Javascript_utilities.createRegex('/^([01][0-9]|2[0-3]):[0-5][0-9]$/');
                    if(!regex.test(strTime)){
                        return{
                            status: "false",
                            errMsg: errMsg
                        };
                    }
                    return{
                        status: "true",
                        errMsg: ""
                    };                    
                }//End function, "testTime".
            });
        </script>
     </head>
    <body>
        <div id="divPageContent"></div>
    </body>
