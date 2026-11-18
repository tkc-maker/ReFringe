<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser");
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
                width: 600px;
                overflow: hidden; 
                border: 1px solid #ccc; /* Optional border for better visibility */
            }
            #divWarning{
                color: red;
                font-size: 12pt;
                font-weight: bold;
                margin: 4px;
            }
            .fieldHeading{
                color: brown;
                font-size: 16pt;
                margin: 4px;
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
        </style>
        <script src="/TEST/JavaScript/EditableTable.js?v=1.3"></script>
        <title>Reference Table Editor: "Roles"</title>
        
        <script src="/TEST/JavaScript/JQuery371.js"></script>
        
        <script>             
            $(function() {
                $.post('/TEST/ProposalDetails/Admin/Roles_Editor_backend.php',
                    {
                        mode: 'displayRolesScreen'
                    }, 
                    function(resp){
                        //Initially, display an empty table, and initialize the "table" variable:
                        //-----------------------------------------------------------------------
                        $("#divPageContent").html(resp);
                        displayTable([]);
                        
                        //Now populate the table with Roles:
                        //----------------------------------
                        displayAllRolesFromDb();
                });
                
                                    
                const rowHeight = 30; // Approximate row height
                const extraPadding = 50; // Extra space for UI elements

                function getDynamicHeight() {
                    const rowCount = table.countRows();
                    return Math.min(rowHeight * rowCount + extraPadding, 500); // Max height 500px
                }
                
                var table; //Variable for use after this function is called.
                function displayTable(data){
                    const container = document.querySelector('#divPageContent #divRoles');
                    
                    table = new EditableTable(container, {
                      data: data,
                      rowHeaders: true,
                      colHeaders: ['Include?','','Name'],
                      hiddenColumns: { columns: [1] }, //Hide the "ID" column (its data is kept).
                      width: 350,
                      autoWrapRow: true,
                      autoWrapCol: true,
                      columnSorting: true, //Enable sorting.
                      stretchH: 'all', //Stretch columns for consistent alignment.
                      readOnly: false, //Ensure the table is editable.
                      columns: [
                        {type: 'checkbox', width: 55}, //Checkbox column.
                        {type: 'text'},
                        {type: 'text'}
                      ]
                    });

                    /**   Scans the table for rows that have been checked (first column). 
                     * Stores each row found into an array of rows for use by the
                     * saving or deleting functions.
                     * 
                     * @returns {Array of Arrays} Data to be saved or deleted.
                     *                      */
                    function getCheckedRows() {
                        var checkedRows = [];

                        //Loop through all rows:
                        for (let rowIndex = 0; rowIndex < table.countRows(); rowIndex++) {
                            const isChecked = table.getDataAtCell(rowIndex, 0); //Get the value from the checkbox column.

                            if (isChecked === true) { //Check if the checkbox is marked.
                                checkedRows.push(table.getDataAtRow(rowIndex).slice(1)); //Exclude the checkbox column.
                            }
                        }
                        return checkedRows;
                    }
                    
                    /**   Scans the table for rows that have NOT been checked. 
                     * Stores each row found into an array of rows for use by the
                     * deleting functions.
                     * 
                     * Note: Here the checkbox column is preserved, because
                     *       the data will be used to populate the table.
                     * 
                     * @returns {Array of Arrays} Data to be restored in table.
                     *                      */
                    function getUnCheckedRows() {
                        var unCheckedRows = [];

                        //Loop through all rows:
                        for (let rowIndex = 0; rowIndex < table.countRows(); rowIndex++) {
                            const isChecked = table.getDataAtCell(rowIndex, 0); //Get the value from the checkbox column.

                            if (isChecked === false) { //Check if the checkbox is NOT marked.
                                unCheckedRows.push(table.getDataAtRow(rowIndex)); //Exclude the checkbox column.
                            }
                        }
                        return unCheckedRows;
                    }
                    
                    /**   Sorts rows alphabetically by name (column 2), ignoring
                     * upper/lower case. The array is sorted in place.
                     * 
                     * @param {Array of Arrays} aryData -- Rows of [checkbox, ID, name].
                     * @returns {Array of Arrays} The same array, now sorted.
                     *                      */
                    function sortRowsByName(aryData) {
                        return aryData.sort(function(rowA, rowB){
                            return String(rowA[2] ?? '').localeCompare(String(rowB[2] ?? ''),
                                undefined, { sensitivity: 'base' });
                        });
                    }
                    
                    $('#divPageContent').on('click', '#btnSaveCheckedRows', function(){
                        const checkedRows = getCheckedRows();
                        if (checkedRows.length === 0) {
                            alert('No rows selected for saving.');
                            return;
                        }
                        $.post('/TEST/ProposalDetails/Admin/Roles_Editor_backend.php',
                            {
                                mode: 'saveCheckedRows',
                                jsonCheckedRows: JSON.stringify(checkedRows)
                            }, 
                            function(resp){
                                try{
                                    if(resp){
                                        alert('Checked rows saved ok.');
                                        displayAllRolesFromDb();
                                    }else{
                                        alert('Error: Problem saving checked rows.');
                                    }
                                }catch(error){
                                    alert('Error: Problem saving checked rows. '+error);  
                                }
                        });
                    });
                    
                    $('#divPageContent').on('click', '#btnDeleteCheckedRows', function(){
                        const checkedRows = getCheckedRows();
                        if (checkedRows.length === 0) {
                            alert('No rows selected for deleting.');
                            return;
                        }
                        if(!confirm('Are you sure you want to delete these roles? '+
                           'Doing so may break related records.')){
                            return;
                        }
                        $.post('/TEST/ProposalDetails/Admin/Roles_Editor_backend.php',
                            {
                                mode: 'deleteCheckedRows',
                                jsonCheckedRows: JSON.stringify(checkedRows)
                            }, 
                            function(resp){                                
                                try{
                                    if(resp){
                                        alert('Checked rows deleted ok.');
                                        const aryData = getUnCheckedRows();
                                        table.loadData(aryData);
                                        table.updateSettings({ height: getDynamicHeight() });
                                        
                                    }else{
                                        alert('Error: Problem deleting checked rows.');
                                    }
                                }catch(error){
                                    alert('Error: Problem deleting checked rows. '+error);  
                                }
                        });
                    });
                    
                    $('#divPageContent').on('click', '#btnSaveNewRole', function(){
                        const newRole = $('#txtNewRole').val();
                        
                        $.post('/TEST/ProposalDetails/Admin/Roles_Editor_backend.php',
                            {
                                mode: 'saveNewRole',
                                newRole: newRole
                            }, 
                            function(resp){                                
                                try{
                                    if(resp){
                                        alert('New Role saved ok.');
                                        var aryData = table.getData();
                                        aryData.push([false, resp, newRole]);
                                        sortRowsByName(aryData); //Put the new entry in alphabetical order.
                                        table.loadData(aryData);
                                        table.updateSettings({ height: getDynamicHeight() });
                                        $('#divPageContent #txtNewRole').val('');
                                        
                                    }else{
                                        alert('Problem saving New Role.');
                                    }                                    
                                }catch(error){
                                    alert('Error: Problem saving New Role. '+error);  
                                }
                        });
                    });
                    
                }//End function, "displayTable".
                
                /**   Refreshes the table from the database.
                 * Should an error occur, it is displayed in an
                 * alert and in "divPageContent".
                 */
                function displayAllRolesFromDb(){
                    $.post('/TEST/ProposalDetails/Admin/Roles_Editor_backend.php',
                        {
                            mode: 'displayAllRoles'
                        }, 
                        function(resp){

                            try{
                                if(resp !== false){
                                    var aryData = [];
                                    var data = JSON.parse(resp);
                                    data.forEach(item => {
                                        if (item.ID && item.name) { //Ensure both ID and name exist.
                                            aryData.push([false, item.ID, item.name]); //Push [false, ID, name] into the array.
                                        }
                                    });
                                    table.loadData(aryData);
                                    table.updateSettings({ height: getDynamicHeight() });
                                }    

                            }catch(error){
                                alert(error);
                                $('#divPageContent').text(error);
                            }
                    }); 
                }//End function, "displayAllRolesFromDb".

            });//End "$(function(){...}".
        </script>
     </head>
    <body>
        <?php require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Menus/header.php'; ?>
        <div id="divPageContent"></div>
    </body>
