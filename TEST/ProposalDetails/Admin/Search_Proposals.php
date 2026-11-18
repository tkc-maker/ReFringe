<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser, Organizer");
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/TEST/CSS/EditableTable.css?v=1.2" />
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
            .qaScrollBox{
                position: relative; /* So highlights can be located within the box. */
                height: 100px; /* Constrain height. */
                overflow-y: auto; /* Vertical scrollbar for overflowing content. */
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
        <script src="/TEST/JavaScript/EditableTable.js?v=1.3"></script>
        <title>Proposal Search Loaded from View</title>
        
        <script src="/TEST/JavaScript/JQuery371.js"></script>
        
        <script>             
            $(function() {
                //Initially, display an empty table, and initialize the "table" variable:
                $.post('/TEST/ProposalDetails/Admin/Search_Proposals_backend.php',
                    {
                        mode: 'displaySearchProposalScreen'
                    }, 
                    function(resp){
                        $("#divPageContent").html(resp)
                        $("#divPageContent #divSearchResults").html('');
                        displayTable([]);
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
                                table.loadData(data);
                                
                            }else{
                                $('#divPageContent #divSearchResults').text(results["errMsg"]);
                            }    

                        }catch(error){
                            alert(error);
                            $('#divPageContent #divSearchResults').text(error);
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
                            table.loadData([]);
                            
                        }catch(error){
                            alert(error);
                            $('#divPageContent #divSearchResults').text(error);
                        }
                    });
                });
                
                var table; //variable for use after this function is called.
                function displayTable(data){
                    const container = document.querySelector('#divPageContent #divSearchResults');
                    const QandAcolVal = 10;
                    
                    // Store search text globally (used by the "Questions and Answers" renderer):
                    let CurrentSearchText = null;
                    
                    /**   Renders a date-time without its fractional seconds,
                     * e.g. "2025-03-01 12:34:56.123456" is shown as "2025-03-01 12:34:56".
                     */
                    function dateTimeRenderer(instance, td, row, col, prop, value) {
                        td.textContent = (value === null || value === undefined) ? '' : String(value).replace(/\.\d+$/, '');
                    }
                    
                    table = new EditableTable(container, {
                      data: data,
                      rowHeaders: true,
                      colHeaders: ['Include?','','Role','First Name','Last Name','Phone','Email','','Title','Question Classes','Questions and Answers', 'CDT', 'UDT'],
                      hiddenColumns: { columns: [1, 7] }, //Hide the two ID columns (their data is kept).
                      height: 600, //Explicit height; the table scrolls within it.
                      autoWrapRow: true,
                      autoWrapCol: true,
                      columnSorting: true, //Enable sorting.
                      stretchH: 'all', //Stretch columns to fill any spare width.
                      readOnly: false, //Ensure the table is editable.
                      columns: [
                        {type: 'checkbox', width: 75}, //Checkbox column.
                        {type: 'text'}, //(Hidden.)
                        {type: 'text', width: 90}, //Role.
                        {type: 'text', width: 100}, //First Name.
                        {type: 'text', width: 100}, //Last Name.
                        {type: 'text', width: 110}, //Phone.
                        {type: 'text', width: 170}, //Email.
                        {type: 'text'}, //(Hidden.)
                        {type: 'html', width: 170}, //Title: HTML, so that e.g. "&amp;" shows as "&".
                        {type: 'html', width: 150}, //Question Classes.
                        {type: 'html', width: 600, //Questions and Answers, in a scrolling box.
                            renderer: function(instance, td, row, col, prop, value) {
                                const box = document.createElement('div');
                                box.className = 'qaScrollBox';
                                EditableTable.setSafeHtml(box, value); //Keeps only basic formatting tags.
                                
                                if (col === QandAcolVal && CurrentSearchText) {
                                    //Highlight matches of the "Refine" search text:
                                    EditableTable.highlightText(box, CurrentSearchText, 'yellowit');
                                }
                                td.appendChild(box);
                            }
                        },
                        {type: 'text', width: 100, renderer: dateTimeRenderer}, //CDT.
                        {type: 'text', width: 100, renderer: dateTimeRenderer}  //UDT.
                      ]
                    });
                    
                    //=================================================================================================
                    //==== Buttons at Bottom of Screen  ====
                    //=================================================================================================

                    $('#divPageContent').on('click', '#btnClearVisibleHighlights', function(){
                        CurrentSearchText = null;//This stops the renderer from painting highlights.
                        table.render();
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
                                table.render();
                            }catch(error){
                                alert(error);
                            }
                        });
                    });
                    
                    $('#divPageContent').on('click', '#btnRefineSearch', function(){
                        CurrentSearchText = $('#txtRefineSearch').val();
                        
                        $.post('/TEST/ProposalDetails/Admin/Search_Proposals_backend.php', 
                        {
                            mode: 'refineSearch',
                            refineSearch: CurrentSearchText
                        }, 
                        function(){
                            try{
                                //Redraw, so that the "Questions and Answers" renderer
                                //highlights matches of "CurrentSearchText":
                                table.render();
                                scrollToHighlights();
                                
                            }catch(error){
                                alert(error);
                            }
                        });
                    });
                    
                    /**   Called by the above refine-search handler. Scrolls each
                     * "Questions and Answers" box to its first highlight, and then
                     * scrolls the table to the first row containing a highlight.
                     */
                    function scrollToHighlights(){
                        const colIndex = 10;
                        let firstHighlightedRow = null;
                        
                        for(let row = 0; row < table.countRows(); row++) {
                            const td = table.getCell(row, colIndex);
                            const box = td ? td.querySelector('.qaScrollBox') : null;
                            const highlight = box ? box.querySelector('.yellowit') : null;
                            if(highlight){
                                //Centre the first highlight within the cell's scrolling box:
                                box.scrollTop = highlight.offsetTop - (box.clientHeight - highlight.offsetHeight) / 2;
                                if(firstHighlightedRow === null){
                                    firstHighlightedRow = row;
                                }
                            }
                        }
                        if(firstHighlightedRow !== null){
                            table.scrollViewportTo(firstHighlightedRow, colIndex);
                        }
                    }
                    
                    //=================================================================================================
                    //==== Export Checked Rows without HTML ====
                    //=================================================================================================

                    /**   Scans the table for rows that have been checked (first column). 
                     * Stores each row found into an array of rows for use by the
                     * exporting functions.
                     * 
                     * @returns {Array of Arrays} Data to be exported into a CSV file.
                     *                      */
                    function getCheckedRows() {
                        var checkedRows = [];

                        // Loop through all rows
                        for (let rowIndex = 0; rowIndex < table.countRows(); rowIndex++) {
                            const isChecked = table.getDataAtCell(rowIndex, 0); //Get the value from the checkbox column.

                            if (isChecked === true) { //Check if the checkbox is marked.
                                checkedRows.push(table.getDataAtRow(rowIndex).slice(1)); //Exclude the checkbox column.
                            }
                        }

                        return checkedRows;
                    }

                    /**   Converts one value into a quoted CSV field that a spreadsheet
                     * will show as plain text, never run as a formula.
                     * 
                     * Steps, in this order:
                     *   1. Replace "<br>" tags with "," and remove all other HTML tags.
                     *   2. If the text starts (after any spaces) with a character that
                     *      makes spreadsheets treat it as a formula ("=", "+", "-", "@",
                     *      their full-width forms, tab, or a line break), put "'" in
                     *      front of it, so that it is shown as text.
                     *      (Done after step 1, so that e.g. "<b>=...</b>" is caught.)
                     *   3. Double any '"' characters, and wrap the field in '"', so that
                     *      no value can end its own field and start another.
                     * 
                     * @param {*} value
                     * @returns {string} -- The quoted CSV field.
                     */
                    function toCsvField(value) {
                        var text = String(value);
                        
                        //Replace "<br>" tags with ",":
                        text = text.replace(/<br\s*\/?>/gi, ',');
    
                        //Remove all other HTML tags:
                        text = text.replace(/<\/?[^>]+(>|$)/g, '');
                        
                        //Stop spreadsheets from treating the value as a formula:
                        if(/^\s*[=+\-@\uFF1D\uFF0B\uFF0D\uFF20]/.test(text) || /^[\t\r\n]/.test(text)){
                            text = "'" + text;
                        }
                        
                        return '"' + text.replace(/"/g, '""') + '"';
                    }

                    /**   Converts the input array of strings into CSV data, using "|"
                     * as the delimiter between values and "\n" as the delimiter
                     * between rows.
                     * 
                     * Note: Each value is cleaned by "toCsvField" (above): HTML tags are
                     *       removed ("<br>" becomes ","), and values that a spreadsheet
                     *       would run as formulas are made into plain text.
                     * 
                     * @param {array} data
                     * @returns {CSV data} -- Where each value is concatenated to the previous
                     *     value by a "|", and rows are concatenated by "\n".
                     */
                    function convertToCSV(data) {
                        return data.map(row => row.map(toCsvField).join('|')).join('\n');
                    }

                    $('#divPageContent').on('click', '#btnClearRowChecks', function() {
                        const checkedRows = getCheckedRows();
                        if (checkedRows.length === 0) {
                            alert('No rows have been checked.');
                            return;
                        }
                        // Loop through all rows, and uncheck them:
                        for (let rowIndex = 0; rowIndex < table.countRows(); rowIndex++) {
                            table.setDataAtCell(rowIndex, 0, false);
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
                    
                    /**   Scans the table for rows that have been checked (first column). 
                     * Stores the first row found for use by the Proposal Editor.
                     * 
                     * @returns {Array} Data for the Proposal Editor.
                     *
                     */
                    function getFirstCheckedRow() {
                        var checkedRow = [];

                        // Loop through all rows
                        for (let rowIndex = 0; rowIndex < table.countRows(); rowIndex++) {
                            const isChecked = table.getDataAtCell(rowIndex, 0); //Get the value from the checkbox column.

                            if (isChecked === true) { //Check if the checkbox is marked.
                                checkedRow = table.getDataAtRow(rowIndex).slice(1); //Exclude the checkbox column.
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
                };//End function, "displayTable".
                
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
                                table.loadData(data);
                                
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
