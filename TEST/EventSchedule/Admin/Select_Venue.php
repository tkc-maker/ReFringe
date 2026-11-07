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
        <link rel="stylesheet" href="/TEST/CSS/handsontable.min.css?v=15.2" />
        <link rel="stylesheet" href="/TEST/CSS/ht-theme-main.min.css?v=15.2" />
        <style>
            #divPageContent{
                width: 100%;
                text-align: left;
            }
            /* undo the dimming on all readOnly cells */
            .htDimmed {
              opacity: 1 !important;
              color: inherit !important;
              background-color: inherit !important;
              cursor: default !important; /* optional: remove the "not-allowed" cursor */
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
        <script src="/TEST/JavaScript/handsontable.full.min.js?v=15.2"></script>
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
                        //Initially, display an empty HOT, and initialize the "hot" variable:
                        displayHandsontable([]);
                        loadHandsontable();
                });                
                
                var hot; //variable for use after this function is called.
                function displayHandsontable(data){
                    const container = $('#divPageContent #divAllCheckedVenues')[0];
                    const QandAcolVal = 3;
                    
                    hot = new Handsontable(container, {
                      data: data,
                      rowHeaders: true,
                      colHeaders: ['Select?','','Venue Name','Questions/Answers'],
                      hiddenColumns: { columns: [1], indicators: false },        
                      autoWrapRow: true,
                      autoWrapCol: true,
                      columnSorting: true, //Enable sorting.
                      filters: true, //Enable filtering.
                      dropdownMenu: false, //Enable/disable a UI dropdown for filtering/sorting.
                      disableVisualSelection: false, //Allow cell selections, which also enable hooks.
                      columns: [
                        {
                            data: 'selected',
                            renderer: radioRenderer,
                            readOnly: true        // we handle clicks in a hook
                        },
                        { data: 'ID',   type: 'numeric', readOnly: true },
                        { data: 'name', type: 'text', readOnly: true },
                        { data: 'questions_answers', type: 'text', readOnly: true, width: 500,
                            renderer: function(instance, td, row, col, prop, value, cellProperties) {
                                // Set new innerHTML
                                const processedValue = `${value}`;

                                //Pass the processed value to HtmlRenderer:
                                Handsontable.renderers.HtmlRenderer.apply(this, [instance, td, row, col, prop, processedValue, cellProperties]);                        

                                td.style.minHeight = '100px'; //Constrain height.
                                td.style.maxHeight = '100px'; //Constrain height.
                                td.style.overflowY = 'auto'; //Enable vertical scrollbars for overflowing content.
                                td.style.textAlign = 'left';
                                td.style.display = 'block'; //Ensure the cell acts as a block element.

                                return td;
                            }
                        }
                      ],
                      licenseKey: 'non-commercial-and-evaluation' //For non-commercial use only.
                    });
                    
                    //Renders a radio-button column:
                    function radioRenderer( hot, td, row, col, prop, value ){
                        td.innerHTML = `<input
                            type="radio"
                            name="ht-radio"
                            class="radioButton"
                            ${value ? 'checked' : ''}
                        />`;

                        return td;
                    }

                    //Responds to clicking a radio-button in traditional fashion:
                    hot.addHook('afterOnCellMouseUp', (event, coords) => {
                        if (coords.col !== 0) return;

                        const clickedRow = coords.row;
                        const totalRows  = hot.countRows();

                        // build a batch of updates: only the clicked row → true, others → false
                        const updates = [];
                        for (let r = 0; r < totalRows; r++) {
                          updates.push([ r, 'selected', r === clickedRow ]);
                        }

                        // apply them in one go
                        hot.setDataAtRowProp(updates);
                        
                        //On clicking a radio-button, color the "Save Venue Selection" button red:
                        $("#divPageContent .btnSaveVenueSelection").css("background-color", "red");
                        
                        // prevent the native editor from ever appearing
                        event.stopImmediatePropagation();
                    });
                    
                };//End function, "displayHandsontable".

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
                   
               /**   Populates the Handsontable with all Venues having the field,
                * "Venues.venue_check" == true.
                */
                function loadHandsontable(){
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
                                    hot.loadData(data);

                                }else{
                                    alert(objResp.errMsg);
                                }    

                            }catch(error){
                                alert(error);
                            }
                    });  
                }

                 /**   Scans the HOT for a row whose radio-button (first column) has been ticked. 
                  * Returns the Venues_ID (second column). If no ticks have been found, 0 is 
                  * returned.
                  * 
                  * @returns integer -- "Venues_ID" of the ticked row.
                  *
                  */
                 function getTickedVenueId(){
                     var tickedRow = 0;

                     // Loop through all rows
                     for (let rowIndex = 0; rowIndex < hot.countRows(); rowIndex++) {
                         const isTicked = hot.getDataAtCell(rowIndex, 0); //Get the value from the radio-button column.

                         if (isTicked === true) { //Check if the radio-button is ticked.
                             var row = hot.getDataAtRow(rowIndex);

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
