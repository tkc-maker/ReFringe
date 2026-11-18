<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
//    UserInfo::checkLogin("SuperUser, Organizer");
    
    $title = trim(htmlspecialchars($_GET['title'], ENT_QUOTES, 'UTF-8'));
    $Calendar_ID = (int) $_GET['calendar_id'];
    $Venues_ID = (int) $_GET['venues_id'];
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/TEST/CSS/EditableTable.css?v=1.2" />
        <style>
            #divPageContent{
                width: 100%;
                text-align: left;
            }
            /* undo the dimming on all readOnly cells */
            #divAllCheckedVenues td.et-readonly {
              color: inherit;
            }
            .qaScrollBox{
                height: 100px; /* Constrain height. */
                overflow-y: auto; /* Vertical scrollbar for overflowing content. */
                text-align: left;
            }
            h1{
                display: flex;
                align-items: center;     /* vertical centering */
                margin-left: 20px;
            }
            .button{
                margin: 4px;
                font-size: 12pt; 
                background-color: orange;
            }
        </style>
        <script src="/TEST/JavaScript/EditableTable.js?v=1.3"></script>
        <title>Select a Checked Venue</title>
        
        <script src="/TEST/JavaScript/JQuery371.js"></script>
        
        <script>             
            $(function() {
                $.post('/TEST/EventSchedule/Admin/Select_Venue_backend.php',
                    {
                        mode: 'displayCheckedVenuesHeader',
                        title: '<?php echo $title; ?>'
                        
                    }, 
                    function(resp){
                        $('#divPageContent').html(resp);
                        //Initially, display an empty table, and initialize the "table" variable:
                        displayTable([]);
                        loadTable();
                });                
                
                var table; //variable for use after this function is called.
                function displayTable(data){
                    const container = $('#divPageContent #divAllCheckedVenues')[0];
                    const QandAcolVal = 3;
                    
                    table = new EditableTable(container, {
                      data: data,
                      rowHeaders: true,
                      colHeaders: ['Select?','','Venue Name','Questions/Answers'],
                      hiddenColumns: { columns: [1] }, //Hide the "ID" column (its data is kept).
                      width: 'fit-content', //Just wide enough for the visible columns.
                      autoWrapRow: true,
                      autoWrapCol: true,
                      columnSorting: true, //Enable sorting.
                      columns: [
                        {
                            data: 'selected',
                            renderer: radioRenderer,
                            readOnly: true        // we handle clicks in a hook
                        },
                        { data: 'ID', readOnly: true },
                        { data: 'name', type: 'text', readOnly: true, width: 200 },
                        { data: 'questions_answers', type: 'html', readOnly: true, width: 500,
                            renderer: function(instance, td, row, col, prop, value) {
                                //Show the Questions/Answers HTML (basic formatting only)
                                //in a scrolling box:
                                const box = document.createElement('div');
                                box.className = 'qaScrollBox';
                                EditableTable.setSafeHtml(box, value);
                                td.appendChild(box);
                            }
                        }
                      ]
                    });
                    
                    //Renders a radio-button column:
                    function radioRenderer(instance, td, row, col, prop, value){
                        const radio = document.createElement('input');
                        radio.type = 'radio';
                        radio.name = 'venueRadio';
                        radio.className = 'radioButton';
                        radio.checked = Boolean(value);
                        td.appendChild(radio);
                    }

                    //Responds to clicking a radio-button in traditional fashion:
                    table.addHook('afterOnCellMouseUp', (event, coords) => {
                        if (coords.col !== 0) return;

                        const clickedRow = coords.row;
                        const totalRows  = table.countRows();

                        // build a batch of updates: only the clicked row → true, others → false
                        const updates = [];
                        for (let r = 0; r < totalRows; r++) {
                          updates.push([ r, 'selected', r === clickedRow ]);
                        }

                        // apply them in one go
                        table.setDataAtRowProp(updates);
                        
                        //On clicking a radio-button, color the "Save Venue Selection" button red:
                        $("#divPageContent .btnSaveVenueSelection").css("background-color", "red");
                    });
                    
                };//End function, "displayTable".

                $("#divPageContent").on("click", ".btnSaveVenueSelection", function() {
                    var Venues_ID = getTickedVenueId();

                    if(Venues_ID === 0){
                        alert('You must select a Venue.');
                        return;
                    }
                    
                    $.post('/TEST/EventSchedule/Admin/Select_Venue_backend.php',
                        {
                            mode: 'saveVenueSelection',
                            Calendar_ID: <?php echo $Calendar_ID; ?>,
                            Venues_ID: Venues_ID
                        }, 
                        function(resp){
                            var objResp = JSON.parse(resp);
                            try{
                                if(objResp.status === 'true'){
                                    //Upon a successful save, revert the button's color back to orange:
                                    $("#divPageContent .btnSaveVenueSelection").css("background-color", "orange");
                                    alert("Venue selection saved ok.");
                                    window.parent.postMessage('refreshDivs', '*');

                                }else{
                                    alert(objResp.errMsg);
                                }    

                            }catch(error){
                                alert(error);
                            }
                    });  
                });    
                   
               /**   Populates the table with all Venues having the field,
                * "Venues.venue_check" == true.
                */
                function loadTable(){
                    $.post('/TEST/EventSchedule/Admin/Select_Venue_backend.php',
                        {
                            mode: 'fetchAllCheckedVenues',
                            Venues_ID: <?php echo $Venues_ID; ?>
                        }, 
                        function(resp){
                            var objResp = JSON.parse(resp);
                            try{
                                if(objResp.status !== 'false'){
                                    var data = objResp.allCheckedVenues;
                                    table.loadData(data);

                                }else{
                                    alert(objResp.errMsg);
                                }    

                            }catch(error){
                                alert(error);
                            }
                    });  
                }

                 /**   Scans the table for a row whose radio-button (first column) has been ticked. 
                  * Returns the Venues_ID (second column). If no ticks have been found, 0 is 
                  * returned.
                  * 
                  * @returns integer -- "Venues_ID" of the ticked row.
                  *
                  */
                 function getTickedVenueId(){
                     var tickedRow = 0;

                     // Loop through all rows
                     for (let rowIndex = 0; rowIndex < table.countRows(); rowIndex++) {
                         const isTicked = table.getDataAtCell(rowIndex, 0); //Get the value from the radio-button column.

                         if (isTicked === true) { //Check if the radio-button is ticked.
                             var row = table.getDataAtRow(rowIndex);

                             tickedRow = row[1];
                             break;
                         }
                     }
                     return tickedRow;
                 }
                
            });//End "$(function(){...}".
        </script>
     </head>
    <body>
        <div id="divPageContent"></div>
    </body>
