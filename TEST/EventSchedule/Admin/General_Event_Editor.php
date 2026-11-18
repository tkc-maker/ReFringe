<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';

    $title = trim($_GET['title']);
    $Proposal_details_ID = (int) $_GET['proposal_details_id'];
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/TEST/CSS/EditableTable.css?v=1.2" />
        <style>
            #divPageContent{
                position: relative;
                width: 1150px;
                overflow: hidden; 
                border: 1px solid #ccc; /* Optional border for better visibility */
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
            #divPreferredTimes{
                position: relative;
                width: 800px;
                min-height: 150px;
                max-height: 150px;
                overflow: hidden; 
            }
            #divMembers, #divResources{
                position: relative;
                width: 520px;
                min-height: 150px;
                max-height: 150px;
                overflow-y: hidden;
                margin-right: 20px;
            }
            table{
                margin-left: 20px;
            }
            table td{
                vertical-align: top;
            }
            #tblGeneral{
                margin-left: 0px;
                width: 1000px;
            }
            #tblGeneral #durationErrMsg,
            #tblGeneral #maxOccurencesErrMsg,
            #preferredTimesErrMsg{
                color: red;
                width: 100px;
                margin-left: 20px;
            }
            #tdGeneralContainer{
                margin-left: 0px;
                text-align: left;
            }
            table .buttonRow{
                padding: 10px;
                text-align: center;
            }
            #tdTitle, 
            #tdDuration, 
            #tdMaxOccurences, 
            .hotHeading{
                width: 270px;
                color: darkgreen;
                font-size: 18pt;
            }
            #txtDuration,
            #txtMaxOccurences,
            #txtNewMember,
            #txtNewResource{
                width: 300px;
                padding: 2px;
                font-size: 12pt;
                border-color: black;
            }
            .errMsg{
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
        </style>
        <script src="/TEST/JavaScript/EditableTable.js?v=1.3"></script>
        <title>Event Details Editor"</title>
        
        <script src="/TEST/JavaScript/JQuery371.js"></script>
        <script src="/TEST/ScreenFieldValidation/Utilities/Javascript_utilities.js"></script>
        
        <script>             
            $(function() {
                $.post('/TEST/EventSchedule/Admin/General_Event_Editor_backend.php',
                    {
                        mode: 'displayEventDetailsScreen',
                        proposal_details_id: <?php echo $Proposal_details_ID; ?>,
                        title: <?php echo json_encode($title); ?>
                    }, 
                    function(resp){
                        $("#divPageContent").html(resp);
                        
                        //Initially, display an empty table, and initialize the "table..." variables:
                        //---------------------------------------------------------------------------
                        displayTablePreferredTimes([], {});
                        displayTableExistingMembers([]);
                        displayTableExistingResources([]);
                         
                        //Now populate event fields and the three tables:
                        //-----------------------------------------------
                        displayMainSelectionsFromDb();
                        
                        //Now get all Screenfield Validation records:
                        //-------------------------------------------
//                        getAllScreenfieldValidations();
                });
                
                $('#divPageContent').on('click', '#btnBackToProposalEditor', function(){
                    window.location = '/TEST/ProposalDetails/Admin/Proposal_Editor.php';
                });
                
                $("#divPageContent").on("input", "#txtDuration", function() {
                    //Change "Save Event" button color to red:
                    $("#divPageContent .btnSaveGeneralEvent").css("background-color", "red");
                });
                $("#divPageContent").on("input", "#txtMaxOccurences", function() {
                    //Change "Save Event" button color to red:
                    $("#divPageContent .btnSaveGeneralEvent").css("background-color", "red");                  
                });
                
                $("#divPageContent").on("input", "#txtNewMember", function() {
                    //Change "Add New Member" button color to red:
                    $("#divPageContent #btnAddNewMember").css("background-color", "red");
                });
                $("#divPageContent").on("click", "#chkNewMemberShareable", function() {
                    //Change "Add New Member" button color to red:
                    $("#divPageContent #btnAddNewMember").css("background-color", "red");
                });
                
                $("#divPageContent").on("input", "#txtNewResource", function() {
                    //Change "Add New Resource" button color to red:
                    $("#divPageContent #btnAddNewResource").css("background-color", "red");
                });
                $("#divPageContent").on("click", "#chkNewResourceShareable", function() {
                    //Change "Add New Resource" button color to red:
                    $("#divPageContent #btnAddNewResource").css("background-color", "red");
                });
                
                $("#divPageContent").on("click", "#btnAddNewMember", function() {
                    //Change "Add New Member" button color back to normal:
                    $(this).css("background-color", "orange");

                    const jsonMember = JSON.stringify({
                        home_Proposal_details_ID: <?php echo $Proposal_details_ID; ?>,
                        name: $("#divPageContent #txtNewMember").val(),
                        isShareable: $("#divPageContent #chkNewMemberShareable").is(":checked")
                    });

                    $.post('/TEST/EventSchedule/Admin/General_Event_Editor_backend.php',
                    {
                        mode: 'insertNewMember',
                        jsonMember: jsonMember
                    },
                    function(resp){
                        const aryResp = JSON.parse(resp);
                        
                        if(aryResp.status === 'true'){
                            alert('New Member saved ok.');
                            displayAllMembersWithCurrentSelections();  
                            //Clear fields:
                            $("#divPageContent #txtNewMember").val('');
                            $("#divPageContent #chkNewMemberShareable").prop("checked", false);
                            
                        }else{
                            alert(aryResp.errMsg);
                        }
                    });
                });
                
                $("#divPageContent").on("click", "#btnAddNewResource", function() {
                    //Change "Add New Resource" button color back to normal:
                    $(this).css("background-color", "orange");

                    const jsonResource = JSON.stringify({
                        home_Proposal_details_ID: <?php echo $Proposal_details_ID; ?>,
                        name: $("#divPageContent #txtNewResource").val(),
                        isShareable: $("#divPageContent #chkNewResourceShareable").is(":checked")
                    });

                    $.post('/TEST/EventSchedule/Admin/General_Event_Editor_backend.php',
                    {
                        mode: 'insertNewResource',
                        jsonResource: jsonResource
                    },
                    function(resp){
                        const aryResp = JSON.parse(resp);

                        if(aryResp.status === 'true'){
                            alert('New Resource saved ok.');
                            displayAllResourcesWithCurrentSelections();
                            //Clear fields:
                            $("#divPageContent #txtNewResource").val('');
                            $("#divPageContent #chkNewResourceShareable").prop("checked", false);
                            
                        }else{
                            alert(aryResp.errMsg);
                        }
                    });
                });
                
                $("#divPageContent").on("click", ".btnSaveGeneralEvent", function() {
                    if(!validEventEditorFields()){
                        return;
                    }               
                    
                    var duration = $('#txtDuration').val();
                    var maxOccurences = $('#txtMaxOccurences').val();
                    var preferredTimes = tablePreferredTimes.getSourceData();
                    var Proposal_details_ID = <?php echo $Proposal_details_ID; ?>;
                    
                    const jsonEvent = JSON.stringify({
                        duration: duration,
                        maxOccurences: maxOccurences,
                        preferredTimes: preferredTimes,
                        Proposal_details_ID: Proposal_details_ID
                    });
                    
                    var returnMessage = {"msg": ""};
                    
                    $.post('/TEST/EventSchedule/Admin/General_Event_Editor_backend.php',
                    {
                        mode: 'updateEventSelections',
                        jsonEvent: jsonEvent
                    },
                    function(resp){
                        const objResp = JSON.parse(resp);
                        
                        if(objResp.status === 'true'){
                            returnMessage.msg += "\nDuration, Max Occurences, and Preferred Times saved ok.";
                        }else{
                            returnMessage.msg += "\n"+objResp.errMsg;
                        }
                        
                        //Save Member selections (then save Resource selections):
                        updateMemberSelections(returnMessage);
                    });                    
                });

                
                const rowHeight = 30; //Approximate row height.
                const extraPadding = 50; //Extra space for UI elements.

                function getDynamicHeight(table) {
                    const rowCount = table.countRows();
                    return Math.min(rowHeight * rowCount + extraPadding, 450); //Max height in px.
                }
                
                var tablePreferredTimes; //Variable for use after this function is called.
                function displayTablePreferredTimes(data, columns){
                    const container = document.querySelector('#divPageContent #divPreferredTimes');
                    
                    tablePreferredTimes = new EditableTable(container, {
                      data: data,
                      columns: columns,
                      rowHeaders: false,
                      width: 800,
                      height: 150, //The container's fixed height: the table scrolls within it.
                      columnSorting: false, //Disable sorting.
                      readOnly: false, //Ensure the table is editable.
                      afterOnCellMouseDown: function(event, coords){
                         if(coords.col > 0){
                            //Change "Save Event" button color to red: 
                            $("#divPageContent .btnSaveGeneralEvent").css("background-color", "red");
                         }
                      }
                    });
                };//End function, "displayTablePreferredTimes".
                
                var tableExistingMembers; //Variable for use after this function is called.
                function displayTableExistingMembers(data){
                    const container = document.querySelector('#divPageContent #divMembers');
                    
                    tableExistingMembers = new EditableTable(container, {
                      data: data,
                      colHeaders: ['Select?','','Home Event Title', 'Name'],
                      columns: [{data: '', type: 'checkbox' }, // column 0 
                                { data: null }, // column 1 (hidden) 
                                { data: '' }, // column 2 
                                { data: '' } // column 3 
                               ],
                      hiddenColumns: { columns: [1] }, //Hide the "ID" column (its data is kept).
                      rowHeaders: true,
                      width: 520,
                      height: 150, //The container's fixed height: the table scrolls within it.
                      autoWrapRow: true,
                      autoWrapCol: true,
                      columnSorting: true,
                      readOnly: false, //Ensure the table is editable.
                      afterOnCellMouseDown: function(event, coords){
                         if(coords.col === 0){
                            //Change "Save Event" button color to red:
                            $("#divPageContent .btnSaveGeneralEvent").css("background-color", "red");
                         }
                      }
                    });
                                        
                };//End function, "displayTableExistingMembers".
                
                var tableExistingResources; //Variable for use after this function is called.
                function displayTableExistingResources(data){
                    const container = document.querySelector('#divPageContent #divResources');
                    
                    tableExistingResources = new EditableTable(container, {
                      data: data,
                      colHeaders: ['Select?','','Home Event Title', 'Name'],
                      columns: [{data: '', type: 'checkbox' }, // column 0 
                                { data: null }, // column 1 (hidden) 
                                { data: '' }, // column 2 
                                { data: '' } // column 3 
                               ],
                      hiddenColumns: { columns: [1] }, //Hide the "ID" column (its data is kept).
                      rowHeaders: true,
                      width: 520,
                      height: 150, //The container's fixed height: the table scrolls within it.
                      autoWrapRow: true,
                      autoWrapCol: true,
                      columnSorting: true,
                      readOnly: false, //Ensure the table is editable.
                      afterOnCellMouseDown: function(event, coords){
                         if(coords.col === 0){
                            //Change "Save Event" button color to red:
                            $("#divPageContent .btnSaveGeneralEvent").css("background-color", "red");
                         }
                      }
                    });
                };//End function, "displayTableExistingResources".
                
                /**   Refreshes the Preferred Times table from the database.
                 * Should an error occur, it is displayed in an alert and in "divPageContent".
                 */
                function displayMainSelectionsFromDb(){
                    $.post('/TEST/EventSchedule/Admin/General_Event_Editor_backend.php',
                        {
                            mode: 'displayMainSelections',
                            Proposal_details_ID: <?php echo $Proposal_details_ID; ?>
                        }, 
                        function(resp){
                            try{
                                var objResp = JSON.parse(resp);

                                if(objResp.status !== 'false'){
                                    //Load "Title", "Duration", and "Max Occurences" fields:
                                    //------------------------------------------------------
                                    $('#divPageContent #spTitle').text(objResp.title);                                    
                                    $('#divPageContent #txtDuration').val(objResp.event.duration);
                                    $('#divPageContent #txtMaxOccurences').val(objResp.event.maxOccurences);
                                    
                                    //Load "Preferred Times" table:
                                    //-----------------------------
                                    var data = JSON.parse(objResp.event.preferredTimes);

                                    const columns = Object.keys(data[0]).map((key, index) => {
                                      return {
                                        data: key,
                                        type: index === 0 ? 'text' : 'checkbox',
                                        width: index === 0 ? undefined : 120,
                                        className: index === 0 ? undefined : 'et-center',
                                        title: key.charAt(0).toUpperCase() + key.slice(1)
                                      };
                                    });    
                                    
                                    tablePreferredTimes.updateSettings({
                                      data: data,
                                      columns: columns,
                                      colHeaders: columns.map(c => c.title)
                                    });                         
                                    
                                    //Populate table with all Members and current selections:
                                    //-------------------------------------------------------
                                    displayAllMembersWithCurrentSelections();
                                    
                                    //Populate table with all Resources and current selections:
                                    //---------------------------------------------------------
                                    displayAllResourcesWithCurrentSelections();
                                    
                               }else{
                                   alert(objResp.errMsg);
                               }

                            }catch(error){
                                alert(error);
                            }
                    });         
                    
                }//End function, "displayMainSelectionsFromDb".

               /**   Display all Members in a table, showing their home Proposal titles.
                * Also, show current selections in checkbox column.
                */
                function displayAllMembersWithCurrentSelections(){
                    $.post('/TEST/EventSchedule/Admin/General_Event_Editor_backend.php',
                        {
                            mode: 'displayAllMembers'
                        }, 
                        function(resp){
                            try{
                                var objResp = JSON.parse(resp);
                                
                                if(objResp.status !== 'false'){
                                    var data = objResp.aryMembers;
                                    const columns = Object.keys(data[0]).map((key, index) => {
                                      return {
                                        data: key,
                                        type: index === 0 ? 'checkbox': 'text',
                                        width: index === 0 ? 100: 
                                               index === 1 ? undefined:
                                               index === 2 ? 200: 150, 
                                        className: index === 0 ? 'et-center': undefined //(Text wraps by default.)
                                      };
                                    });    

                                    tableExistingMembers.updateSettings({
                                      data: data,
                                      columns: columns
                                    });   
                                    
                                    //Show currently selected Members:
                                    showSelectedMembers();
                                    
                                }else{
                                    alert(objResp.errMsg);
                                }

                            }catch(error){
                                alert(error);
                            }
                        });
                }//End function, "displayAllMembers".

               /**   Show the currently selected Members
                * in the table.
                */
                function showSelectedMembers(){
                    $.post('/TEST/EventSchedule/Admin/General_Event_Editor_backend.php',
                        {
                            mode: 'showSelectedMembers'
                        }, 
                        function(resp){
                            try{
                                var objResp = JSON.parse(resp);
                                
                                if(objResp.status === 'true'){
                                    var arySelectedIDs = objResp.arySelectedIDs;
                                    
                                    arySelectedIDs.forEach(function(idValue) {
                                        searchForIdAndCheckItInTable(tableExistingMembers, idValue);
                                    });                                

                                }else{
                                    alert(objResp.errMsg);
                                }

                            }catch(error){
                                alert(error);
                            }
                        });                    
                }//End function, "showSelectedMembers()".

               /**   Display all Resources in a table, showing their home Proposal titles.
                * Also, show current selections in checkbox column.
                */
                function displayAllResourcesWithCurrentSelections(){
                    $.post('/TEST/EventSchedule/Admin/General_Event_Editor_backend.php',
                        {
                            mode: 'displayAllResources'
                        }, 
                        function(resp){
                            try{
                                var objResp = JSON.parse(resp);
                                
                                if(objResp.status !== 'false'){
                                    var data = objResp.aryResources;
                                    const columns = Object.keys(data[0]).map((key, index) => {
                                      return {
                                        data: key,
                                        type: index === 0 ? 'checkbox': 'text',
                                        width: index === 0 ? 100: 
                                               index === 1 ? undefined:
                                               index === 2 ? 200: 150, 
                                        className: index === 0 ? 'et-center': undefined //(Text wraps by default.)
                                      };
                                    });    

                                    tableExistingResources.updateSettings({
                                      data: data,
                                      columns: columns
                                    });      
                                    
                                    //Show currently selected Resources:
                                    showSelectedResources();
                                    
                                }else{
                                    alert(objResp.errMsg);
                                }

                            }catch(error){
                                alert(error);
                            }
                        });
                }//End function, "displayAllResources".
                
               /**   Show the currently selected Members
                * in the table.
                */
                function showSelectedResources(){
                    $.post('/TEST/EventSchedule/Admin/General_Event_Editor_backend.php',
                        {
                            mode: 'showSelectedResources'
                        }, 
                        function(resp){
                            try{
                                var objResp = JSON.parse(resp);
                                
                                if(objResp.status === 'true'){
                                    var arySelectedIDs = objResp.arySelectedIDs;
                                    
                                    arySelectedIDs.forEach(function(idValue) {
                                        searchForIdAndCheckItInTable(tableExistingResources, idValue);
                                    });                                

                                }else{
                                    alert(objResp.errMsg);
                                }

                            }catch(error){
                                alert(error);
                            }
                        });                    
                }//End function, "showSelectedResources()".
                
                /**    Updates the database with Member selections for the current Event.
                 * Upon return from the AJAX call, a parallel method "updateResourceSelections"
                 * is called. Either a message of success, or one containing an error message is
                 * returned.
                 *
                 * @returns -- A message by side-effect indicating the success or failure 
                 *     of the update: "returnMessage.msg".
                 */
                function updateMemberSelections(returnMessage){
                    const arySelectedMemberIDs = JSON.stringify(getCheckedIdsInTable(tableExistingMembers));
                    
                    $.post('/TEST/EventSchedule/Admin/General_Event_Editor_backend.php',
                    {
                        mode: 'updateMemberSelections',
                        jsonSelectedMemberIDs: arySelectedMemberIDs
                    },
                    function(resp){
                        const aryResp = JSON.parse(resp);
                        
                        if(aryResp.status === 'true'){
                            returnMessage.msg += "\nSelected Members saved ok.";
                            
                        }else{
                            returnMessage.msg += "\n"+aryResp.errMsg;
                        }
                                                
                        //Save Resource selections:
                        updateResourceSelections(returnMessage);
                    });
                }//End function, "updateMemberSelections".
                
                /**    Updates the database with Resource selections for the current Event.
                 * Either a message of success, or one containing an error message is
                 * returned. Also displays an alert containing the message.
                 *
                 * @returns -- A message by side-effect indicating the success or failure 
                 *     of the update: "returnMessage.msg".
                 */
                function updateResourceSelections(returnMessage){
                    const arySelectedResourceIDs = JSON.stringify(getCheckedIdsInTable(tableExistingResources));
                    
                    $.post('/TEST/EventSchedule/Admin/General_Event_Editor_backend.php',
                    {
                        mode: 'updateResourceSelections',
                        jsonSelectedResourceIDs: arySelectedResourceIDs
                    },
                    function(resp){
                        const aryResp = JSON.parse(resp);
                        
                        if(aryResp.status === 'true'){
                            returnMessage.msg += "\nSelected Resources saved ok.";
                            //Change "Save Event" button color back to normal:
                            $("#divPageContent .btnSaveGeneralEvent").css("background-color", "orange");
                            //Refresh the entire Calendar from the DB:        
                            window.parent.postMessage('refreshDivs', '*');
                            
                        }else{
                            returnMessage.msg += "\n"+aryResp.errMsg;
                        }
                        
                        //Display save results in an alert:
                        alert(returnMessage.msg);
                    });
                }//End function, "updateResourceSelections".
                
                /**   Javascript utility to search for an "ID" value and check
                 * its row in a table, marking it as selected.
                 */
                function searchForIdAndCheckItInTable(table, searchValue) {
                    const sourceData = table.getSourceData(); // Array of objects
                    const updatedData = sourceData.map(row => {
                        const newRow = { ...row }; // Shallow copy

                        // Adjust property names to match your dataset
                        if(
                          newRow.ID && // Assuming 'ID' is the key holding the search value
                          newRow.ID.toString() === searchValue.toString()
                        ){
                          newRow.bool = true; // Assuming 'selected' is your checkbox column
                        }

                        return newRow;
                    });

                  table.loadData(updatedData);
                }
                
                /**   Returns an array of ID values from those rows that have been
                 * checked in the passed-in Members or Resources table.
                 * 
                 * @param {EditableTable} table -- Either the Members or Resources 
                 *    table.
                 * @returns {Array} -- A single-subscripted array of ID values
                 *    that have been checked.
                 */
                function getCheckedIdsInTable(table){
                    const sourceData = table.getSourceData();
                    
                    var aryCheckedIds = [];
                    sourceData.forEach(function(row){
                        if(row.bool === true){
                            aryCheckedIds.push(row.ID);
                        }
                    });

                    return aryCheckedIds;                                      
                }
                
                /**   Checks that the main Event fields are filled with valid data".
                 * Should an error be detected, it is spelled out in red to the right
                 * of the field.
                 * 
                 * @returns {boolean} Whether main Event fields contain valid data.
                 */
                function validEventEditorFields(){
                    var validFields = true;
                    
                    //Get values to validate:
                    var duration = $('#txtDuration').val();
                    var maxOccurences = $('#txtMaxOccurences').val();
                    var checkedPreferredTimes = false;
                    tablePreferredTimes.getData().forEach(function(aryRow){
                        aryRow.forEach(function(col){
                            if(col === true){
                                checkedPreferredTimes = true;
                            }
                        });
                    });

                    //Clear all error message fields:
                    $('#durationErrMsg').text('');
                    $('#maxOccurencesErrMsg').text('');
                    $('#preferredTimesErrMsg').text('');
                    
                    //Test and report on any errors:
                    var parsed = parseInt(duration, 10);
                    var isInt = !isNaN(parsed) && parsed.toString() === duration;
                    if(duration === 0 || !isInt){
                        $('#durationErrMsg').text('A Duration is required and must be an integer.');
                        validFields = false;
                    }
                    
                    parsed = parseInt(maxOccurences, 10);
                    isInt = !isNaN(parsed) && parsed.toString() === maxOccurences;
                    if(maxOccurences === 0 || !isInt){
                        $('#maxOccurencesErrMsg').text('Max Occurences is required and must be an integer.');
                        validFields = false;
                    }
                    
                    if(!checkedPreferredTimes){
                        $('#preferredTimesErrMsg').text('At least one time interval must be checked.'); 
                        validFields = false;
                    }
                    if(!validFields){
                        alert('Some of your fields contain errors! Please fix these before continuing.'); 
                    }
                    
                    return validFields;
                }//End function, "validEventEditorFields".
                
            });//End "$(function(){...});".
        </script>
     </head>
    <body>
<div id="divPageContent"></div>
    </body>
