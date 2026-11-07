<?php

?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/TEST/CSS/handsontable.min.css" />
        <link rel="stylesheet" href="/TEST/CSS/ht-theme-main.min.css" />
        <script src="/TEST/JavaScript/handsontable.full.min.js"></script>
        <title>Handsontable Loaded from View Test</title>
        <style>
            #divSearchResults{
                width: 100%;
                height: 150px;
                overflow-y: auto; /* Enable vertical scrolling */
                border: 1px solid #ccc; /* Optional border for better visibility */
            }
        </style>
        
        <script src="/TEST/JavaScript/JQuery371.js"></script>
        
        <script>             
            $(function() {
                //Display search results:
                $.ajax({
                    type: 'POST',
                    url: '/TEST/ProposalDetails/Admin/Handsontable_Loaded_From_View_backend.php',
                    data: {
                        mode: 'getHandsontableData',
                        order: '"Last-Name", "First-Name"'
                    }, 
                    dataType: 'json',  //Specify JSON data type
                    success: function(resp) {
                        try{
                            var data = JSON.parse(resp);
                            displayHandsontable(data);
 
                        }catch(error){
                            alert('Error-1: '+error);
                            $('#divSearchResults').html('');
                        }
                    },
                    error: function(resp) {
                        alert('Error-2: '+JSON.stringify(resp));
                        $('#divSearchResults').html('');
                    }
                });
                                          
                function displayHandsontable(data){
                    const container = document.querySelector('#divSearchResults');
                    const hot = new Handsontable(container, {
                      data: data,
                      rowHeaders: true,
                      colHeaders: ['Include?','','Role','First Name','Last Name','Phone','Email','','Title','Genres','','Question Class','Questions/Answers'],
                      hiddenColumns: { columns: [1, 7, 10], indicators: false },        
                      height: 'fixed',
                      autoWrapRow: true,
                      autoWrapCol: true,
                      columnSorting: true, // Enable sorting
                      filters: true, // Enable filtering
                      dropdownMenu: true, // Enable a UI dropdown for filtering/sorting
                      columns: [
                        {type: 'checkbox'}, //Checkbox column.
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text',
                            renderer: function(instance, td, row, col, prop, value, cellProperties) {
                               Handsontable.renderers.HtmlRenderer.apply(this, arguments); // Use HTML renderer for Genres.
                            }
                        },
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text',
                            renderer: function(instance, td, row, col, prop, value, cellProperties) {
                                Handsontable.renderers.HtmlRenderer.apply(this, arguments); // Use HTML renderer for Questions/Answers.
                            }
                        }
                      ],
                      licenseKey: 'non-commercial-and-evaluation' //For non-commercial use only.
                    });
                    
                    // Hook into Handsontable's afterFilter event
                    hot.addHook('afterFilter', () => {
                        const filtersPlugin = hot.getPlugin('filters');
                        const conditions = filtersPlugin.conditions;
 //                       const matchingCellDetails = findMatchingCell(hot, conditions);

//                        if (matchingCellDetails) {
                            const row = 0;
                            const col = 11;
                            const match = conditions.args[0];     alert(match);
                            // Scroll to the cell containing the matching text
                            hot.scrollViewportTo(row, col);
                            hot.render(); // Ensure cell is in viewport

                            // Highlight and scroll to the matching line
                            scrollToMatchingLine(row, col, match);
//                        }
                    });     
                    
                    function findMatchingCell(hotInstance, conditions) {
                        const data = hotInstance.getData();
                        const descriptionColumnIndex = 11; //Assuming the "Description" column is column 11.

                        for (let rowIndex = 0; rowIndex < data.length; rowIndex++) {
                            const cellValue = data[rowIndex][descriptionColumnIndex];

                            if (
                                cellValue &&
                                conditions[descriptionColumnIndex]?.length > 0 &&
                                conditions[descriptionColumnIndex].some(condition => condition.name === 'contains' && condition.args[0])
                            ) {   alert("We're HEEEERE!");
                                const condition = conditions[descriptionColumnIndex].find(c => c.name === 'contains');
                                if (condition && cellValue.includes(condition.args[0])) {
                                    return { row: rowIndex, col: descriptionColumnIndex, match: condition.args[0] };
                                }
                            }
                        }
                        return null;
                    }
                    
                    function scrollToMatchingLine(row, col, searchText) {
                        const cellElement = hot.getCell(row, col);

                        if (cellElement) {
                            const regex = new RegExp(`(${searchText})`, 'gi');
                            cellElement.innerHTML = cellElement.innerText.replace(regex, '<span class="highlight">$1</span>');

                            // Create a scrollable container for the cell (if not already styled)
                            cellElement.style.overflowY = 'auto';
                            cellElement.style.maxHeight = '100px'; // Adjust to desired cell height

                            // Scroll to the matching line
                            const highlightElement = cellElement.querySelector('.highlight');
                            if (highlightElement) {
                                highlightElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        }
                    }                    
                    
                }//End function, "displayHandsontable(...)".     
                
            });//End "$(function(){...}".
        </script>
     </head>
    <body>
         <div id="divSearchResults" class="ht-theme-main-dark"></div>
    </body>
