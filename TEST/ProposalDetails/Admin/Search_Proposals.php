<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser, Organizer");
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/TEST/CSS/handsontable.min.css?v=15.2" />
        <link rel="stylesheet" href="/TEST/CSS/ht-theme-main.min.css?v=15.2" />
        <style>
            #tblCriteria{
                width: 100%;
                margin-left: 10px;
            }
            #tblCriteria td{
                text-align: center;
            }
            #divSearchResults{
                position: relative;
                width: 100%;
                height: 600px;
                overflow: hidden; 
                border: 1px solid #ccc; /* Optional border for better visibility */
            }
            .yellowit {
                background-color: yellow;
                color: black;
            }
            .fieldHeading{
                color: brown;
                font-size: 16pt;
                margin: 4px;
            }
            #txtRefineSearch{
                width: 300px;
            }
            input[type="button"]{
                margin: 4px;
                font-size: 12pt; 
                background-color: orange;
            }
        </style>
        <script src="/TEST/JavaScript/handsontable.full.min.js?v=15.2"></script>
        <title>Proposal Search Loaded from View</title>
        
        <script src="/TEST/JavaScript/JQuery371.js"></script>
        
        <script>             
            $(function() {
                //Initially, display an empty HOT, and initialize the "hot" variable:
                $.post('/TEST/ProposalDetails/Admin/Search_Proposals_backend.php',
                    {
                        mode: 'displaySearchProposalScreen'
                    }, 
                    function(resp){
                        $("#divPageContent").html(resp)
                        $("#divPageContent #divSearchResults").html('');
                        displayHandsontable([]);
                });
                
                $('#divPageContent').on('click', '#btnSearchProposals', function(){
                    submitSearchProposals();
                });
                
                $('#divPageContent').on('click', '#btnShowAllProposals', function(){
                    $.post('/TEST/ProposalDetails/Admin/Search_Proposals_backend.php', 
                    {
                        mode: 'showAllProposalsSubmit'
                    }, 
                    function(resp){
                        try{
                            var results = JSON.parse(resp);
                            if(results["status"] === "true"){
                                var data = results["results"];
                                hot.loadData(data);
                                
                            }else{
                                $('#divPageContent #divSearchResults').html(results["errMsg"]);
                            }    

                        }catch(error){
                            alert(error);
                            $('#divPageContent #divSearchResults').html(error);
                        }
                    });                
                });
                
                $('#divPageContent').on('click', '#btnClearSearchCriteria', function(){
                    $.post('/TEST/ProposalDetails/Admin/Search_Proposals_backend.php', 
                    {
                        mode: 'clearSearchCriteria'
                    }, 
                    function(){
                        try{
                            var first = true;
                            $('#divPageContent #tblCriteria .searchCriteria').each(function(){
                                if($(this).prop("checked") === true){
                                    if(first){
                                        $(this).prop("checked", true);
                                        first = false;
                                    }else{
                                        $(this).prop("checked", false);
                                    }
                                }else{
                                    $(this).val('');
                                }
                            });
                            hot.loadData([]);
                            
                        }catch(error){
                            alert(error);
                            $('#divPageContent #divSearchResults').html(error);
                        }
                    });
                });
                
                var hot; //variable for use after this function is called.
                function displayHandsontable(data){
                    const container = document.querySelector('#divPageContent #divSearchResults');
                    const QandAcolVal = 10;
                    
                    hot = new Handsontable(container, {
                      data: data,
                      rowHeaders: true,
                      colHeaders: ['Include?','','Role','First Name','Last Name','Phone','Email','','Title','Question Classes','Questions and Answers', 'CDT', 'UDT'],
                      hiddenColumns: { columns: [1, 7], indicators: false },        
                      height: 600, //Explicit height to avoid layout splitting.
                      autoWrapRow: true,
                      autoWrapCol: true,
                      columnSorting: true, //Enable sorting.
                      filters: true, //Enable filtering.
                      dropdownMenu: false, //Enable/disable a UI dropdown for filtering/sorting.
                      stretchH: 'all', //Stretch columns for consistent alignment.
                      readOnly: false, //Ensure the table is editable.
                      disableVisualSelection: false, //Allow cell selections, which also enable hooks.
                      columns: [
                        {type: 'checkbox'}, //Checkbox column.
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text'},
                        {type: 'text',
                            renderer: function(instance, td, row, col, prop, value, cellProperties) {
                               Handsontable.renderers.HtmlRenderer.apply(this, arguments); // Use HTML renderer for Title.
                            }
                        },
                        {type: 'text',
                            renderer: function(instance, td, row, col, prop, value, cellProperties) {
                                Handsontable.renderers.HtmlRenderer.apply(this, arguments); // Use HTML renderer for Question Classes.
                            }                            
                        },
                        {type: 'text', 
                            renderer: function(instance, td, row, col, prop, value, cellProperties) {
                                // Set new innerHTML
                                const processedValue = `${value}`;

                                //Pass the processed value to HtmlRenderer:
                                Handsontable.renderers.HtmlRenderer.apply(this, [instance, td, row, col, prop, processedValue, cellProperties]);                        

                                td.style.minHeight = '100px'; //Constrain height.
                                td.style.maxHeight = '100px'; //Constrain height.
                                td.style.overflowY = 'auto'; //Enable vertical scrollbars for overflowing content.
                                td.style.display = 'block'; //Ensure the cell acts as a block element.

                                if (col === 10 && CurrentSearchText && value) {
                                    const regex = new RegExp(`(${CurrentSearchText})`, 'gi');
                                    const safeValue = Handsontable.helper.stringify(value);

                                    //Replace matching substring with highlighted span:
                                    td.innerHTML = safeValue.replace(regex, '<span class="yellowit">$1</span>');
                                }
                                return td;
                            }
                        },
                        {type: 'text',
                            renderer: (instance, td, row, col, prop, value) => {
                                td.textContent = value.replace(/\.\d+$/, '');
                            }
                        },
                        {type: 'text',
                            renderer: (instance, td, row, col, prop, value) => {
                                td.textContent = value.replace(/\.\d+$/, '');
                            }
                        }
                      ],
                        cells: function (row, col) {
                          const cellProperties = {};
                          if (col === 10) {
                            cellProperties.width = 600; // acts like max width
                          }
                          return cellProperties;
                        },
                        licenseKey: 'non-commercial-and-evaluation' //For non-commercial use only.
                    });
                    
                    //=================================================================================================
                    //==== Buttons at Bottom of Screen  ====
                    //=================================================================================================

                    $('#divPageContent').on('click', '#btnClearVisibleHighlights', function(){
                        CurrentSearchText = null;//This stops the renderer from painting highlights.
                        hot.render();
                    });
                    
                    $('#divPageContent').on('click', '#btnClearRefineCriteria', function(){
                        const tempSearchText = '';
                        $.post('/TEST/ProposalDetails/Admin/Search_Proposals_backend.php', 
                        {
                            mode: 'refineSearch',
                            refineSearch: tempSearchText
                        }, 
                        function(){
                            try{
                                $('#divPageContent #txtRefineSearch').val(tempSearchText);
                                hot.render();
                            }catch(error){
                                alert(error);
                            }
                        });
                    });
                    
                    // Store search text globally:
                    let CurrentSearchText = null;

                    $('#divPageContent').on('click', '#btnRefineSearch', function(){
                        CurrentSearchText = $('#txtRefineSearch').val();
                        
                        $.post('/TEST/ProposalDetails/Admin/Search_Proposals_backend.php', 
                        {
                            mode: 'refineSearch',
                            refineSearch: CurrentSearchText
                        }, 
                        function(){
                            try{
                                //Ensures proper table recalculation:
                                hot.refreshDimensions();
                                //Activates the renderer for col-10 that highlights matches
                                //of "CurrentSearchText":
                                hot.render();
                                //Hammer the DOM three times to find matches:
                                requestAnimationFrame(() => {
                                    setTimeout(() => {
                                        scrollToHighlights();
                                    }, 500); // 500 ms = 0.5 seconds
                                    setTimeout(() => {
                                        scrollToHighlights();
                                    }, 1000); // 500 ms = 0.5 seconds
                                    setTimeout(() => {
                                        scrollToHighlights();
                                    }, 1500); // 500 ms = 0.5 seconds
                                });
                                
                            }catch(error){
                                alert(error);
                            }
                        });
                    });
                    
                    /**   Called by the above refine-search handler. Highlights matchea
                     * and scrolls cell contents to the first highlights in each cell.
                     */
                    function scrollToHighlights(){
                         const colIndex = 10;
                        for(let row = 0; row < hot.countRows(); row++) {
                            hot.scrollViewportTo(row, colIndex); //This must be done for `.getCell` to work.
                            var td = hot.getCell(row, colIndex);
                            if(td){
                                td.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                var highlight = td.querySelector('.yellowit');
                                if(highlight){
                                    highlight.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                }
                            }
                        }                                                  
                    }
                    
                    //=================================================================================================
                    //==== Export Checked Rows without HTML ====
                    //=================================================================================================

                    /**   Scans the HOT for rows that have been checked (first column). 
                     * Stores each row found into an array of rows for use by the
                     * exporting functions.
                     * 
                     * @returns {Array of Arrays} Data to be exported into a CSV file.
                     *                      */
                    function getCheckedRows() {
                        var checkedRows = [];

                        // Loop through all rows
                        for (let rowIndex = 0; rowIndex < hot.countRows(); rowIndex++) {
                            const isChecked = hot.getDataAtCell(rowIndex, 0); //Get the value from the checkbox column.

                            if (isChecked === true) { //Check if the checkbox is marked.
                                checkedRows.push(hot.getDataAtRow(rowIndex).slice(1)); //Exclude the checkbox column.
                            }
                        }

                        return checkedRows;
                    }

                    /**   Converts the input array of strings into CSV data, using "|"
                     * as the delimiter between values and "\n" as the delimiter
                     * between rows.
                     * 
                     * Note: This function also removes all HTML tags from the "Roles"
                     *       and "Questions/Answers" columns, and replaces "<br" with ",".
                     * 
                     * @param {array} data
                     * @returns {CSV data} -- Where each value is concatenated to the previous
                     *     value by a "|", and rows are concatenated by "\n".
                     */
                    function convertToCSV(data) {
                        var dataConverted =  data.map(row => row.map(value => `"${value}"`).join('|')).join('\n');
                        
                        //Replace "<br>" tags with ",":
                        dataConverted = dataConverted.replace(/<br\s*\/?>/gi, ',');
    
                        //Remove all other HTML tags:
                        dataConverted = dataConverted.replace(/<\/?[^>]+(>|$)/g, '');

                        return dataConverted;

                    }

                    $('#divPageContent').on('click', '#btnClearRowChecks', function() {
                        const checkedRows = getCheckedRows();
                        if (checkedRows.length === 0) {
                            alert('No rows have been checked.');
                            return;
                        }
                        // Loop through all rows, and uncheck them:
                        for (let rowIndex = 0; rowIndex < hot.countRows(); rowIndex++) {
                            hot.setDataAtCell(rowIndex, 0, false);
                        }                       
                    });

                    /**   Calls functions "getCheckedRows()" and "convertToCSV(...)",
                     * and exports the the data as a CSV file with the name, 
                     * "export.csv", to the computer's downloads.
                     * 
                     * Note: Fields are delimited by "|" and rows by "\n".
                     * 
                     */
                    $('#divPageContent').on('click', '#btnExportToCsv', function() {
                        const checkedRows = getCheckedRows();
                        if (checkedRows.length === 0) {
                            alert('No rows selected for export.');
                            return;
                        }

                        const csvContent = convertToCSV(checkedRows);

                        // Create a Blob and download it
                        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                        const link = document.createElement('a');
                        link.href = URL.createObjectURL(blob);
                        link.download = 'export.csv';
                        link.click();
                        alert("Look in your downloads folder for a file like \"export.csv\". If you "+
                              "have more than one, its name will be suffixed with a unique number.");
                    });
                    
                    //=================================================================================================
                    //==== Edit First Checked Row ====
                    //=================================================================================================
                    
                    /**   Scans the HOT for rows that have been checked (first column). 
                     * Stores the first row found for use by the Proposal Editor.
                     * 
                     * @returns {Array} Data for the Proposal Editor.
                     *
                     */
                    function getFirstCheckedRow() {
                        var checkedRow = [];

                        // Loop through all rows
                        for (let rowIndex = 0; rowIndex < hot.countRows(); rowIndex++) {
                            const isChecked = hot.getDataAtCell(rowIndex, 0); //Get the value from the checkbox column.

                            if (isChecked === true) { //Check if the checkbox is marked.
                                checkedRow = hot.getDataAtRow(rowIndex).slice(1); //Exclude the checkbox column.
                                break; //Stop once the first checked row is found.
                            }
                        }
                        return checkedRow; // Return the first checked row
                    }
                    
                    $('#divPageContent').on('click', '#btnEditCheckedProposal', function() {
                        const checkedRow = getFirstCheckedRow();

                        if (checkedRow.length === 0) {
                            alert('No row selected for editing.');
                            return;
                        }
                        
                        $.post('/TEST/ProposalDetails/Admin/Search_Proposals_backend.php', 
                        {
                            mode: 'editCheckedProposal',
                            Users_ID: checkedRow[0],
                            Proposal_details_ID: checkedRow[6],
                            Role: checkedRow[1],
                            First_Name: checkedRow[2],
                            Last_Name: checkedRow[3],
                            Phone: checkedRow[4],
                            Email: checkedRow[5]
                        }, 
                        function(resp){
                            try{
                                window.location = '/TEST/ProposalDetails/Admin/Proposal_Editor.php';
                                
                            }catch(error){
                                alert(error);
                            }
                        });                                        
                    });
                };//End function, "displayHandsontable".
                
                //=================================================================================================
                function submitSearchProposals(){
                    var jsonSearchProposals = JSON.stringify({
                        Include_1: $('#divPageContent #tblCriteria #chkInclude_1').prop('checked'),
                        Role_1: $('#divPageContent #tblCriteria #txtRole_1').val().trim(),
                        'First-Name_1': $('#divPageContent #tblCriteria #txtFirstName_1').val().trim(),
                        'Last-Name_1': $('#divPageContent #tblCriteria #txtLastName_1').val().trim(),
                        Phone_1: $('#divPageContent #tblCriteria #txtPhone_1').val().trim(),
                        Email_1: $('#divPageContent #tblCriteria #txtEmail_1').val().trim(),
                        Title_1: $('#divPageContent #tblCriteria #txtTitle_1').val().trim(),
                        'Question-classes_1': $('#divPageContent #tblCriteria #txtQuestionClasses_1').val().trim(),
                        'Questions-and-Answers_1': $('#divPageContent #tblCriteria #txtQuestionsAnswers_1').val().trim(),

                        Include_2: $('#divPageContent #tblCriteria #chkInclude_2').prop('checked'),
                        Role_2: $('#divPageContent #tblCriteria #txtRole_2').val().trim(),
                        'First-Name_2': $('#divPageContent #tblCriteria #txtFirstName_2').val().trim(),
                        'Last-Name_2': $('#divPageContent #tblCriteria #txtLastName_2').val().trim(),
                        Phone_2: $('#divPageContent #tblCriteria #txtPhone_2').val().trim(),
                        Email_2: $('#divPageContent #tblCriteria #txtEmail_2').val().trim(),
                        Title_2: $('#divPageContent #tblCriteria #txtTitle_2').val().trim(),
                        'Question-classes_2': $('#divPageContent #tblCriteria #txtQuestionClasses_2').val().trim(),
                        'Questions-and-Answers_2': $('#divPageContent #tblCriteria #txtQuestionsAnswers_2').val().trim(),

                        Include_3: $('#divPageContent #tblCriteria #chkInclude_3').prop('checked'),
                        Role_3: $('#divPageContent #tblCriteria #txtRole_3').val().trim(),
                        'First-Name_3': $('#divPageContent #tblCriteria #txtFirstName_3').val().trim(),
                        'Last-Name_3': $('#divPageContent #tblCriteria #txtLastName_3').val().trim(),
                        Phone_3: $('#divPageContent #tblCriteria #txtPhone_3').val().trim(),
                        Email_3: $('#divPageContent #tblCriteria #txtEmail_3').val().trim(),
                        Title_3: $('#divPageContent #tblCriteria #txtTitle_3').val().trim(),
                        'Question-classes_3': $('#divPageContent #tblCriteria #txtQuestionClasses_3').val().trim(),
                        'Questions-and-Answers_3': $('#divPageContent #tblCriteria #txtQuestionsAnswers_3').val().trim(),

                        Include_4: $('#divPageContent #tblCriteria #chkInclude_4').prop('checked'),
                        Role_4: $('#divPageContent #tblCriteria #txtRole_4').val().trim(),
                        'First-Name_4': $('#divPageContent #tblCriteria #txtFirstName_4').val().trim(),
                        'Last-Name_4': $('#divPageContent #tblCriteria #txtLastName_4').val().trim(),
                        Phone_4: $('#divPageContent #tblCriteria #txtPhone_4').val().trim(),
                        Email_4: $('#divPageContent #tblCriteria #txtEmail_4').val().trim(),
                        Title_4: $('#divPageContent #tblCriteria #txtTitle_4').val().trim(),
                        'Question-classes_4': $('#divPageContent #tblCriteria #txtQuestionClasses_4').val().trim(),
                        'Questions-and-Answers_4': $('#divPageContent #tblCriteria #txtQuestionsAnswers_4').val().trim()
                    });
                    
                    $.post('/TEST/ProposalDetails/Admin/Search_Proposals_backend.php', 
                    {
                        mode: 'searchProposalsSubmit',
                        jsonSearchProposals: jsonSearchProposals
                    }, 
                    function(resp){
                        try{
                            var results = JSON.parse(resp);
                            if(results["status"] === "true"){
                                var data = results["results"];
                                hot.loadData(data);
                                
                            }else{
                                alert(results["errMsg"]);
                            }
                        }catch(error){
                            alert(error);
                        }
                    });                
                }//End function, "submitSearchProposals".
                
            });//End "$(function(){...}".
        </script>
     </head>
    <body>
        <?php require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Menus/header.php'; ?>
        <div id="divPageContent"></div>
    </body>
