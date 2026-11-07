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
                width: 1100px;
                overflow: hidden; 
                border: 1px solid #ccc; /* Optional border for better visibility */
            }
            #divWarning{
                color: red;
                font-size: 12pt;
                font-weight: bold;
                padding: 10px;
            }
            .tableHeading{
                padding-top: 20px;
                padding-bottom: 10px;
                color: green;
                font-size: 16pt;
                text-align: center;
            } 
            .fieldHeading{
                width: 35%;
                color: brown;
                font-size: 16pt;
                padding: 4px;
                text-align: right;
            }
            .textField{
                width: 65%;
                padding: 4px;
                text-align: left;
            }
            .textField input{
                width: 400px;
            }
            .newValidationSubmit{
                padding-top: 10px;                
            }
            input[type="button"]{
                margin: 4px;
                font-size: 12pt; 
                background-color: orange;
            }
            #divButtons{
                width: 100%;
                text-align: center;
                margin-bottom: 15px;
            }
            #divButtons table{
                width: 100%;
            }
        </style>
        <script src="/TEST/JavaScript/handsontable.full.min.js?v=15.2"></script>
        <title>Reference Table Editor: "Screenfield_validation"</title>
        
        <script src="/TEST/JavaScript/JQuery371.js"></script>
        
        <script>             
            $(function() {

                $.post('/TEST/ScreenFieldValidation/Admin/Screenfield_validation_Editor_backend.php',
                    {
                        mode: 'displayScreenfieldValidationScreen'
                    }, 
                    function(resp){
                        //Initially, display an empty HOT, and initialize the "hot" variable:
                        //-------------------------------------------------------------------
                        $("#divPageContent").html(resp);
                        displayHandsontable([]);
                        
                        //Now populate the HOT with Screenfield Validations:
                        //--------------------------------------------------
                        displayAllScreenfieldValidationsFromDb();
                });
                
                                    
                const rowHeight = 30; // Approximate row height
                const extraPadding = 50; // Extra space for UI elements

                function getDynamicHeight() {
                    const rowCount = hot.countRows();
                    return Math.min(rowHeight * rowCount + extraPadding, 500); // Max height 500px
                }
                
                var hot; //Variable for use after this function is called.
                function displayHandsontable(data){
                    const container = document.querySelector('#divPageContent #divScreenfieldValidation');
                    
                    hot = new Handsontable(container, {
                      data: data,
                      rowHeaders: true,
                      colHeaders: ['Include?','','Rule', 'Regex', 'Error Message'],
                      hiddenColumns: { columns: [1], indicators: false },        
                      width: 1100,
                      autoWrapRow: true,
                      autoWrapCol: true,
                      columnSorting: true, //Enable sorting.
                      filters: false,
                      dropdownMenu: false,
                      readOnly: false, //Ensure the table is editable.
                      disableVisualSelection: false, //Allow cell selections, which also enable hooks.
                      columns: [
                        {type: 'checkbox', width: 100, className: "htCenter"}, //Checkbox column.
                        {type: 'text'},
                        {type: 'text', width: 200},
                        {type: 'text', width: 300},
                        {type: 'text', width: 435}
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
                        $.post('/TEST/ScreenFieldValidation/Admin/Screenfield_validation_Editor_backend.php',
                            {
                                mode: 'saveCheckedRows',
                                jsonCheckedRows: JSON.stringify(checkedRows)
                            }, 
                            function(resp){
                                try{
                                    var aryResp = JSON.parse(resp);
                                    
                                    if(aryResp['status'] === 'true'){
                                        alert('Checked rows saved ok.');
                                        displayAllScreenfieldValidationsFromDb();
                                        
                                    }else{
                                        alert(aryResp['errMsg']);
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
                        if(!confirm('Are you sure you want to delete these screenfield validations? '+
                           'Doing so may allow bad data to be entered.')){
                            return;
                        }
                        $.post('/TEST/ScreenFieldValidation/Admin/Screenfield_validation_Editor_backend.php',
                            {
                                mode: 'deleteCheckedRows',
                                jsonCheckedRows: JSON.stringify(checkedRows)
                            }, 
                            function(resp){                                
                                try{
                                    var aryResp = JSON.parse(resp);
                                    
                                    if(aryResp['status'] === 'true'){
                                        alert('Checked rows deleted ok.');
                                        displayAllScreenfieldValidationsFromDb();
                                        
                                    }else{
                                        alert(aryResp['errMsg']);
                                    }
                                }catch(error){
                                    alert('Error: Problem deleting checked rows. '+error);  
                                }
                        });
                    });
                    
                    $('#divPageContent').on('click', '#btnSaveNewScreenfieldValidation', function(){
                        const jsonNewScreenfieldValidation = JSON.stringify({
                              'newRule': $('#divPageContent #txtNewRule').val(),
                              'newRegex': $('#divPageContent #txtNewRegex').val(),
                              'newErrMsg': $('#divPageContent #txtNewErrMsg').val()
                        });

                        $.post('/TEST/ScreenFieldValidation/Admin/Screenfield_validation_Editor_backend.php',
                            {
                                mode: 'saveNewScreenfieldValidation',
                                jsonNewScreenfieldValidation: jsonNewScreenfieldValidation
                            }, 
                            function(resp){ 
                                try{
                                    var aryResp = JSON.parse(resp);
                                    
                                    if(aryResp['status'] === 'true'){
                                        alert('New Screenfield Validation saved ok.');
                                        displayAllScreenfieldValidationsFromDb();
                                        $('#divPageContent #txtNewRule').val('');
                                        $('#divPageContent #txtNewRegex').val('');
                                        $('#divPageContent #txtNewErrMsg').val('');
                                        
                                    }else{
                                        alert(aryResp['errMsg']);
                                    }                                    
                                }catch(error){
                                    alert('Error: Problem saving New Screenfield Validation. '+error);  
                                }
                        });
                    });
                    
                }//End function, "displayHandsontable".
                
                /**   Refreshes the Handsontable from the database.
                 * Should an error occur, it is displayed in an
                 * alert and in "divPageContent".
                 */
                function displayAllScreenfieldValidationsFromDb(){
                    $.post('/TEST/ScreenFieldValidation/Admin/Screenfield_validation_Editor_backend.php',
                        {
                            mode: 'displayAllScreenfieldValidations'
                        }, 
                        function(resp){
                            var aryResp = JSON.parse(resp);
                            
                            try{
                                if(aryResp['status'] === 'true'){
                                    var aryData = [];
                                    var data = aryResp['allScreenfieldValidations'];
                                    data.forEach(item => {
                                        if (item.ID && item.rule && item.regex && item.errMsg) { //Ensure that all fields exist.
                                            aryData.push([false, item.ID, item.rule, item.regex, item.errMsg]); //Push [false, <other fields>] into the array.
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
                }//End function, "displayAllScreenfieldValidationsFromDb".

            });//End "$(function(){...}".
        </script>
     </head>
    <body>
        <?php require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Menus/header.php'; ?>
        <div id="divPageContent"></div>
    </body>
