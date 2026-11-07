<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser, Organizer");
?>
<!DOCTYPE html>
<?PHP
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Show All Proposals</title>

        <style>
            table{
                border-collapse: collapse;
                margin-left: 10px;
            }
            table td, table th{
                text-align: left;
                padding: 5px;
            }
            th{
                color: maroon;
                font-weight: bold;
            }
            #divSearchResults table td:nth-child(1), /* Delete button */
            #divSearchResults table th:nth-child(1) {
              width: 50px;
            }

            #divSearchResults table td:nth-child(2), /* Title */
            #divSearchResults table th:nth-child(2) {
              width: 200px;
            }
            #divSearchResults table td:nth-child(7), /* Email */
            #divSearchResults table th:nth-child(7) {
              width: 100px;
            }
            #divSearchResults table tbody tr:nth-child(even) { /* Alternate row colors */
                background-color: bisque;
            }
            a{
                color: blue;
            }
            a:visited{
                color: blue;
            }
            .button{
                margin: 4px;
                font-size: 12pt; 
                background-color: orange;
            }
        </style>
        <script src="/TEST/JavaScript/JQuery371.js"></script>
        <script> 
            
            $(function() {
                var aryOrigAllProposals = [];
                var aryCurrAllProposals = [];
                
                //Initially, display Proposal field options:
                $.post('/TEST/ProposalDetails/Admin/Show_All_Proposals_backend.php',
                    {
                        mode: 'showAllProposalFieldOptions'
                    }, 
                    function(resp){
                        $("#divPageContent").html(resp);
                        showAllProposalTitles();
                });
            
                /**   First clears "#divSearchResults", and then populates it with All Proposals,
                 * constrained to the passed-in fields list.
                 * 
                 * @param {Assoc. Array} aryData -- All Proposals. Each row is an object
                 *    of all possible fields in the PostgreSQL view.
                 * @param {Array} aryFields -- An array of field-names to show for All Proposals.
                 */
                function renderResults(aryData, aryFields){
                    const $container = $('#divSearchResults');
                    $container.empty();

                    var tblFieldList = '<br><table>';
                    tblFieldList += '<tr><th>&nbsp;</th>';
                    for(var field of aryFields){
                        if(field === 'created_date_time'){
                           field = 'CDT';
                        }
                        if(field === 'updated_date_time'){
                           field = 'UDT';
                        }
                        tblFieldList += '<th>'+field+'</th>';
                    }
                    tblFieldList += '</tr>';
                    for(var row of aryData){                          
                        tblFieldList += '<tr><td style="width: 50px;"><input class="button btnDelete" type="button" data-id="'+row['Proposal-details-ID']+'" value="Delete"></td>';
                        for(var field of aryFields){
                            if(field === 'Title'){
                                row[field] = '<a href class="aShowAllProposals" data-id="'+row['Proposal-details-ID']+'">'+row[field]+'</a>';
                            }
                            if(field === 'created_date_time' || field === 'updated_date_time'){
                                row[field] = row[field].toString().split('.')[0]; 
                            }
                            tblFieldList += '<td>'+row[field]+'</td>';
                        };
                        tblFieldList += '</tr>';
                    };
                    tblFieldList += '</table>';
                    $container.html(tblFieldList);
                }
                
                /**   Responds to checking/unchecking a checkbox to the left of a field.
                 * Either that field is included in the All Proposals results, or it
                 * is not.
                 */
                $('#divPageContent').on('change', '#tblFields input[type="checkbox"]', function(resp){
                    var aryFields = $('#tblFields input[type="checkbox"]:checked').map(function() {
                        return $(this).data('fname');
                    }).get();
                    renderResults(aryCurrAllProposals, aryFields);
                    
                    //Now, persist check-box state:
                    $.post('/TEST/ProposalDetails/Admin/Show_All_Proposals_backend.php', 
                        {
                            mode: 'persistCheckboxState',
                            chkRole: $('#divPageContent #chkRole').prop('checked'),
                            chkFirst_Name: $('#divPageContent #chkFirst_Name').prop('checked'),
                            chkLast_Name: $('#divPageContent #chkLast_Name').prop('checked'),
                            chkPhone: $('#divPageContent #chkPhone').prop('checked'),
                            chkEmail: $('#divPageContent #chkEmail').prop('checked'),
                            chkTitle: $('#divPageContent #chkTitle').prop('checked'),
                            chkCDT: $('#divPageContent #chkCDT').prop('checked'),
                            chkUDT: $('#divPageContent #chkUDT').prop('checked')
                        }, 
                        function(){}
                    );

                });
                
                /**   Upon clicking a field's "Asc" button, sorts 
                 * the data rows in ascending order by that field.
                 */
                $('#divPageContent').on('click', '#tblFields .btnAsc', function(){
                    if($(this).parent().siblings().find('input[type="checkbox"]:checked').length === 0){
                        return;               
                    }
                    colorSelectedOrderingButton($(this));
                    const sortField = $(this).parent().siblings().children('input[type="checkbox"]').data('fname');
                    aryCurrAllProposals.sort((a, b) => String(a[sortField]).localeCompare(String(b[sortField])));
                    
                    var aryFields = $('#tblFields input[type="checkbox"]:checked').map(function() {
                        return $(this).data('fname');
                    }).get();
                    renderResults(aryCurrAllProposals, aryFields);
                });

                /**   Upon clicking a field's "Desc" button, sorts 
                 * the data rows in descending order by that field.
                 */
                $('#divPageContent').on('click', '#tblFields .btnDesc', function(){
                    if($(this).parent().siblings().find('input[type="checkbox"]:checked').length === 0){
                        return;               
                    }
                    colorSelectedOrderingButton($(this));
                    const sortField = $(this).parent().siblings().children('input[type="checkbox"]').data('fname');
                    aryCurrAllProposals.sort((a, b) => String(b[sortField]).localeCompare(String(a[sortField])));
                    
                    var aryFields = $('#tblFields input[type="checkbox"]:checked').map(function() {
                        return $(this).data('fname');
                    }).get();
                    renderResults(aryCurrAllProposals, aryFields);
                });
                
                /**   Upon clicking a field's "Orig" button, reverts 
                 * the data rows to their original order.
                 */
                $('#divPageContent').on('click', '#tblFields .btnOrig', function(){
                    if($(this).parent().siblings().find('input[type="checkbox"]:checked').length === 0){
                        return;               
                    }
                    colorSelectedOrderingButton($(this));
                    var aryFields = $('#tblFields input[type="checkbox"]:checked').map(function() {
                        return $(this).data('fname');
                    }).get();
                    renderResults(aryOrigAllProposals, aryFields);
                });
                
                function colorSelectedOrderingButton(selector){
                    $('#divPageContent #tblFields .button').css('background-color', 'orange');
                    selector.css('background-color', 'lightblue');
                }
                
                $('#divSearchResults').on('click', 'table .aShowAllProposals', function(event){
                    event.preventDefault();
                    
                    const Proposal_details_ID = $(this).data('id');
                    const pd_record = aryOrigAllProposals.find(pd => pd['Proposal-details-ID'] === Proposal_details_ID);
                    $.post('/TEST/ProposalDetails/Admin/Show_All_Proposals_backend.php', 
                        {
                            mode: 'editCheckedProposal',
                            Users_ID: pd_record['Users-ID'],
                            Proposal_details_ID: Proposal_details_ID,
                            Role: pd_record['Role'],
                            First_Name: pd_record['First-Name'],
                            Last_Name: pd_record['Last-Name'],
                            Phone: pd_record['Phone'],
                            Email: pd_record['Email']
                        }, 
                        function(){
                            try{
                                window.location = '/TEST/ProposalDetails/Admin/Proposal_Editor.php';
                                
                            }catch(error){
                                alert(error);
                            }
                        });                                        
                });
                
                $('#divSearchResults').on('click', 'table .btnDelete', function(){
                    $(this).css('background-color', 'red');
                    setTimeout(() => {
                        const confirmed = confirm('Are you sure you want to delete this "Proposal_details" record?');
                        $(this).css('background-color', 'orange');

                        var Proposal_details_ID = $(this).data('id');
                        if(confirmed){
                            $.post('/TEST/ProposalDetails/Admin/Show_All_Proposals_backend.php', 
                                {
                                    mode: 'deleteProposalDetails',
                                    Proposal_details_ID: Proposal_details_ID
                                }, 
                                function(resp){
                                    var aryResp = JSON.parse(resp);
                                    if(aryResp['status'] === true){
                                        showAllProposalTitles();
                                    }else{
                                        alert(aryResp['errMsg']);
                                    }
                                });
                        }
                    }, 10); // 10ms is enough to flush the paint queue
                });
                
                function showAllProposalTitles(){
                    //After fields display, load and display all Proposal Titles:
                    $.post('/TEST/ProposalDetails/Admin/Show_All_Proposals_backend.php',
                        {
                            mode: 'showAllProposalTitles'
                        }, 
                        function(resp){
                            try{
                                var results = JSON.parse(resp);
                                if(results["status"] === "true"){
                                    aryOrigAllProposals = structuredClone(results["results"]);
                                    aryCurrAllProposals = structuredClone(results["results"]);
                                    var aryFields = $('#tblFields input[type="checkbox"]:checked').map(function() {
                                        return $(this).data('fname');
                                    }).get();
                                    colorSelectedOrderingButton($('#divPageContent #tblFields .showField_Title .btnAsc'));
                                    renderResults(aryCurrAllProposals, aryFields);

                                }else{
                                    $('#divSearchResults').html(results["errMsg"]);
                                }    

                            }catch(error){
                                alert(error);
                                $('#divSearchResults').html(error);
                            }
                    });
                }

            });
        </script>
    </head>
    
    <body>        
        <?php require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Menus/header.php'; ?>
        <div id="divPageContent"></div>
        <div id="divSearchResults"></div>
    </body>
</html>