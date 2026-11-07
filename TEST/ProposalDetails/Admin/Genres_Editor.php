<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser");
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
                position: relative;
                width: 600px;
                overflow: hidden; 
                border: 1px solid #ccc; /* Optional border for better visibility */
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
        <script src="/TEST/JavaScript/handsontable.full.min.js?v=15.2"></script>
        <title>Reference Table Editor: "Genres"</title>
        
        <script src="/TEST/JavaScript/JQuery371.js"></script>
        
        <script>             
            $(function() {
                $.post('/TEST/ProposalDetails/Admin/Genres_Editor_backend.php',
                    {
                        mode: 'displayGenresScreen'
                    }, 
                    function(resp){
                        //Initially, display an empty HOT, and initialize the "hot" variable:
                        //-------------------------------------------------------------------
                        $("#divPageContent").html(resp)
                        displayHandsontable([]);
                        
                        //Now populate the HOT with Genres:
                        //---------------------------------
                        displayAllGenresFromDb();                       
//                        $.post('/TEST/ProposalDetails/Admin/Genres_Editor_backend.php',
//                            {
//                                mode: 'displayAllGenres'
//                            }, 
//                            function(resp){
//                                
//                                try{
//                                    if(resp !== false){
//                                        var aryData = [];
//                                        var data = JSON.parse(resp);
//                                        data.forEach(item => {
//                                            if (item.ID && item.name) { //Ensure both ID and name exist.
//                                                aryData.push([false, item.ID, item.name]); //Push [false, ID, name] into the array.
//                                            }
//                                        });
//                                        hot.loadData(aryData);
//                                    }    
//
//                                }catch(error){
//                                    alert(error);
//                                    $('#divPageContent').html(error);
//                                }
//                        });                
                });
                                                   
                const rowHeight = 30; // Approximate row height
                const extraPadding = 50; // Extra space for UI elements

                function getDynamicHeight() {
                    const rowCount = hot.countRows();
                    return Math.min(rowHeight * rowCount + extraPadding, 500); // Max height 500px
                }                
                
                var hot; //Variable for use after this function is called.
                function displayHandsontable(data){
                    const container = document.querySelector('#divPageContent #divGenres');
                    
                    hot = new Handsontable(container, {
                      data: data,
                      rowHeaders: true,
                      colHeaders: ['Include?','','Name'],
                      hiddenColumns: { columns: [1], indicators: false },        
                      width: 450,
                      autoWrapRow: true,
                      autoWrapCol: true,
                      columnSorting: true, //Enable sorting.
                      filters: false,
                      dropdownMenu: false,
                      stretchH: 'all', //Stretch columns for consistent alignment.
                      readOnly: false, //Ensure the table is editable.
                      disableVisualSelection: false, //Allow cell selections, which also enable hooks.
                      columns: [
                        {type: 'checkbox', width: 55}, //Checkbox column.
                        {type: 'text'},
                        {type: 'text'}
                      ],
                      licenseKey: 'non-commercial-and-evaluation' //For non-commercial use only.
                    });

                    /**   Scans the HOT for rows that have been checked (first column). 
                     * Stores each row found into an array of rows for use by the
                     * saving or deleting functions.
                     * 
                     * @returns {Array of Arrays} Data to be saved or deleted.
                     *                      */
                    function getCheckedRows() {
                        var checkedRows = [];

                        //Loop through all rows:
                        for (let rowIndex = 0; rowIndex < hot.countRows(); rowIndex++) {
                            const isChecked = hot.getDataAtCell(rowIndex, 0); //Get the value from the checkbox column.

                            if (isChecked === true) { //Check if the checkbox is marked.
                                checkedRows.push(hot.getDataAtRow(rowIndex).slice(1)); //Exclude the checkbox column.
                            }
                        }
                        return checkedRows;
                    }
                    
                    /**   Scans the HOT for rows that have NOT been checked. 
                     * Stores each row found into an array of rows for use by the
                     * deleting functions.
                     * 
                     * Note: Here the checkbox column is preserved, because
                     *       the data will be used to populate the HOT.
                     * 
                     * @returns {Array of Arrays} Data to be restored in HOT.
                     *                      */
                    function getUnCheckedRows() {
                        var unCheckedRows = [];

                        //Loop through all rows:
                        for (let rowIndex = 0; rowIndex < hot.countRows(); rowIndex++) {
                            const isChecked = hot.getDataAtCell(rowIndex, 0); //Get the value from the checkbox column.

                            if (isChecked === false) { //Check if the checkbox is NOT marked.
                                unCheckedRows.push(hot.getDataAtRow(rowIndex)); //Exclude the checkbox column.
                            }
                        }
                        return unCheckedRows;
                    }
                    
                    $('#divPageContent').on('click', '#btnSaveCheckedRows', function(){
                        const checkedRows = getCheckedRows();
                        if (checkedRows.length === 0) {
                            alert('No rows selected for saving.');
                            return;
                        }
                        $.post('/TEST/ProposalDetails/Admin/Genres_Editor_backend.php',
                            {
                                mode: 'saveCheckedRows',
                                jsonCheckedRows: JSON.stringify(checkedRows)
                            }, 
                            function(resp){
                                try{
                                    if(resp){
                                        alert('Checked rows saved ok.');
                                        displayAllGenresFromDb();
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
                        if(!confirm('Are you sure you want to delete these genres? '+
                           'Doing so may break related records.')){
                            return;
                        }
                        $.post('/TEST/ProposalDetails/Admin/Genres_Editor_backend.php',
                            {
                                mode: 'deleteCheckedRows',
                                jsonCheckedRows: JSON.stringify(checkedRows)
                            }, 
                            function(resp){                                
                                try{
                                    if(resp){
                                        alert('Checked rows deleted ok.');
                                        const aryData = getUnCheckedRows();
                                        hot.loadData(aryData);
                                        hot.updateSettings({ height: getDynamicHeight() });
                                        
                                    }else{
                                        alert('Error: Problem deleting checked rows.');
                                    }
                                }catch(error){
                                    alert('Error: Problem deleting checked rows. '+error);  
                                }
                        });
                    });
                    
                    $('#divPageContent').on('click', '#btnSaveNewGenre', function(){
                        const newGenre = $('#txtNewGenre').val();
                        
                        $.post('/TEST/ProposalDetails/Admin/Genres_Editor_backend.php',
                            {
                                mode: 'saveNewGenre',
                                newGenre: newGenre
                            }, 
                            function(resp){                                
                                try{
                                    if(resp){
                                        alert('New Genre saved ok.');
                                        var aryData = hot.getData();
                                        aryData.push([false, resp, newGenre]);
                                        hot.loadData(aryData);
                                        hot.updateSettings({ height: getDynamicHeight() });
                                        $('#divPageContent #txtNewGenre').val('');
                                        
                                    }else{
                                        alert('Problem saving New Genre.');
                                    }                                    
                                }catch(error){
                                    alert('Error: Problem saving New Genre. '+error);  
                                }
                        });
                    });
                    
                }//End function, "displayHandsontable".
                
                /**   Refreshes the Handsontable from the database.
                 * Should an error occur, it is displayed in an
                 * alert and in "divPageContent".
                 */
                function displayAllGenresFromDb(){
                    $.post('/TEST/ProposalDetails/Admin/Genres_Editor_backend.php',
                        {
                            mode: 'displayAllGenres'
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
                                    hot.loadData(aryData);
                                    hot.updateSettings({ height: getDynamicHeight() });
                                }    

                            }catch(error){
                                alert(error);
                                $('#divPageContent').html(error);
                            }
                    }); 
                }//End function, "displayAllGenresFromDb".
                
            });//End "$(function(){...}".
        </script>
     </head>
    <body>
        <?php require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Menus/header.php'; ?>
        <div id="divPageContent"></div>
    </body>
