<?php
    require $_SERVER['DOCUMENT_ROOT'] . '/TEST/StaticClasses/UserInfo.php';
    UserInfo::checkLogin("SuperUser, Organizer");
?>
<!DOCTYPE html>
<?php
//    $Proposal_details_ID = $_SESSION['PD_Proposal_details_ID'];
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/TEST/CSS/handsontable.min.css?v=15.2" />
        <link rel="stylesheet" href="/TEST/CSS/ht-theme-main.min.css?v=15.2" />
        <style>
            #tblSchedule th{
                font-family: Arial;
                font-size: 14pt;
                color: brown;
            }
            #tblSchedule, #tblSchedule th, #tblSchedule td {
              border: 2px solid blue;
              border-collapse: collapse;
            }
            #tblSchedule .dayCell{
                width: 75px;
                text-align: center;
            }
            #tblSchedule .eventCell{
                width: 300px;
                height: 100px;
            }
            #tblSchedule ul li{
                font-family: Arial;
                font-size: 12pt;
                padding-bottom: 5px;
            }
            #customMenu{
                position:absolute; 
                display:none; 
                background:#eee; 
                border:1px solid #ccc;
                list-style-type: none;
                font-family: Arial;
                padding: 0;
                padding-bottom: 10px;
            }
            #customMenu li{
                padding-top: 10px;
                padding-left: 10px;
                padding-right: 10px;
            }
            #divBottomContent{
                width: 100%; 
                height: 1000px; 
                border: 1px solid #ccc;
            }
            #divBottomContent iframe{
                 width: 100%;
                 height: 100%; 
                 border: none;
            }
            input[type="button"]{
                margin: 4px;
                font-size: 12pt; 
                background-color: orange;
            }
        </style>
        <script src="/TEST/JavaScript/handsontable.full.min.js?v=15.2"></script>
        <title>Event Details Editor"</title>
        
        <script src="/TEST/JavaScript/JQuery371.js"></script>
        <script src="/TEST/ScreenFieldValidation/Utilities/Javascript_utilities.js"></script>
        
        <script>             
            $(function() {
                window.addEventListener('message', function(event) { 
                    if (event.data === 'refreshDivs') { 
                        window.location = '/TEST/EventSchedule/Admin/Calendar.php'; 
                    } 
                });                

                //An assoc. array to hold all general event data, like preferred times,
                //member IDs, resource IDs, etc:
                var aryGeneralEvents;

                $.post('/TEST/EventSchedule/Admin/Calendar_backend.php',
                {
                    mode: 'displayEmptyCalendar'
                }, 
                function(resp_1){
                    $('#divTopContent').html(resp_1);

                    $.post('/TEST/EventSchedule/Admin/Calendar_backend.php',
                        {
                            mode: 'fetchCalendarEvents'
                        }, 
                        function(resp_2){
                            const aryResp_2 = JSON.parse(resp_2);

                            if(aryResp_2.status === 'false'){
                                alert(aryResp_2.errMsg);

                            }else{
                                var eventPlacements = aryResp_2.allCalendarEvents;
                                
                                $.post('/TEST/EventSchedule/Admin/Calendar_backend.php',
                                    {
                                        mode: 'fetchGeneralEvents'
                                    }, 
                                    function(resp_3){
                                        
                                        const objResp_3 = deepJsonParse(resp_3);
                                            

                                        if(objResp_3.status === 'false'){
                                            alert(objResp_3.errMsg);
                                            
                                        }else{
                                            aryGeneralEvents = objResp_3.allGeneralEvents;
                                            placeAllEvents(eventPlacements);
                                        }
                                });
                            }
                    });
                });
                
                function placeAllEvents(eventPlacements){
                    //From each "eventPlacements" element, extract enough of the event's main data
                    //to place the event on the HTML table:
                    //----------------------------------------------------------------------------
                    eventPlacements.forEach(placement => {
                        const Calendar_ID =  placement['Calendar_ID'];
                        const Proposal_details_ID = placement['Proposal_details_ID'];
                        const Venues_ID =  placement['Venues_ID'];
                        const row = placement['day'];
                        const start = placement['start'];
                        const end = placement['end'];
                        const title = placement['title'];
                        const tag = placement['tag'];
                        
                        var col;
                        if(start >= '04:00' && start < '08:00'){
                            col = 1;
                        }else if(start >= '08:00' && start < '12:00'){
                            col = 2;
                        }else if(start >= '12:00' && start < '16:00'){
                            col = 3;
                        }else if(start >= '16:00' && start < '20:00'){
                            col = 4;
                        }else if(start >= '20:00' && start < '24:00'){
                            col = 5;
                        }else if(start >= '00:00' && start < '04:00'){
                            col = 6;
                        }
                        //Place the event on the HTML table:
                        addListItemToCell(row, col, Calendar_ID, Proposal_details_ID, Venues_ID, start, end, title, tag);
                    });
                    //Color events red (for conflicts) or green (no conflicts):
                    //---------------------------------------------------------
                    showAllConflictsInTable();
                }//End function, "placeAllEvents".
                
                /**   Loops through all time-interval cells in the "tblSchedule"
                 * table, and colors event list items red (for conflicts) or
                 * green (no conflicts).
                 */
                function showAllConflictsInTable(){
                    $('#tblSchedule td li').css('color', 'black');
                    for(var row = 1; row < 5; row++){
                        for(var col = 1; col < 7; col++){
                            showMemberResourceConflicts(row, col);
                        }
                    }
                }//End function, "showAllConflictsInTable".
                
                /**    Colors table cell list items either red (has member and/pr resource
                 * conflicts) or green (has no member/resource conflicts). Conflicts are
                 * only checked if the time-intervals of the events overlap.
                 * 
                 * @param {integer} rowIndex -- Table cell row index.
                 * @param {integer} colIndex -- Table cell column index.
                 */
                function showMemberResourceConflicts(rowIndex, colIndex){
                    const $cell = $('#tblSchedule')
                        .find('tr')
                        .eq(rowIndex)
                        .find('td')
                        .eq(colIndex);

                    const $items = $cell.find('li');

                    $items.each(function(index_a, item_a) {
                        const id_a = $(item_a).data('proposal_details_id');
                        const event_a = aryGeneralEvents.find(item => item.Proposal_details_ID === id_a);
                        const time_a = {'day': rowIndex, 'start': $(item_a).data('start'), 'end': $(item_a).data('end')};

                        let hasConflict = false;

                        $items.each(function(index_b, item_b) {
                            if (index_b === index_a) return true; // skip self

                            const id_b = $(item_b).data('proposal_details_id');
                            const event_b = aryGeneralEvents.find(item => item.Proposal_details_ID === id_b);
                            const time_b = {'day': rowIndex, 'start': $(item_b).data('start'), 'end': $(item_b).data('end')};

                            //Only check for conflicts if time intervals overlap between the events:
                            if(doIntervalsOverlap(time_a, time_b)){
                                //If the "Proposal_details_ID" values of both events are the same,
                                //then mark a conflict:
                                if(id_a === id_b){
                                    hasConflict = true;
                                    return false; //Early exit from inner loop.
                                }
                                
                                //Extract any members in common between the events:
                                const intersectMembers = findArrayIntersection(
                                    event_a?.members,
                                    event_b?.members
                                );
                                //Extract any resources in common between the events:
                                const intersectResources = findArrayIntersection(
                                    event_a?.resources,
                                    event_b?.resources
                                );
                                //If there are members or resources in common, report a conflict:
                                if (intersectMembers.length > 0 || intersectResources.length > 0) {
                                    hasConflict = true;
                                    return false; //Early exit from inner loop.
                                }
                            }
                        });

                        //Apply color based on conflict check:
                        $(item_a).css('color', hasConflict ? 'red' : 'green');
                    });
                }
 
                /**   Given table-cell coordinates, adds the passed-in "itemText" to that cell as a list item.
                 * To the newly-created <li> element, adds an "id", using "Calendar_ID", and four data attributes: 
                 * "data-Proposal_details_ID", "data-Venues_ID", "data-start", and "data-end", from passed-in values.
                 * 
                 * @param {integer} rowIndex
                 * @param {integer} colIndex
                 * @param {integer} Calendar_ID -- Primary key value in the "sched_Calendar" table.
                 * @param {integer} Proposal_details_ID -- Identifies details associated with the event.
                 * @param {integer} Venues_ID -- Identifies the Venue selection for the event.
                 * @param {string} start -- 24-hour clock time, e.g.: "15:30" for 3:30 p.m.
                 * @param {string} end -- 24-hour clock time, e.g.: "15:30" for 3:30 p.m.
                 * @param {string} itemText
                 * @param {string} tag
                 */
                function addListItemToCell(rowIndex, colIndex, Calendar_ID, Proposal_details_ID, Venues_ID, start, end, itemText, tag) {
                    //Locate the desired cell:
                    const $cell = $('#tblSchedule')
                        .find('tr')
                        .eq(rowIndex)
                        .find('td')
                        .eq(colIndex);

                    //Ensure it contains a <ul>:
                    let $ul = $cell.find('ul');
                    if ($ul.length === 0) {
                        $ul = $('<ul></ul>').appendTo($cell);
                    }

                    //Add "tag" to the title if "tag" is not empty:
                    tag = tag.trim();
                    if(tag !== ''){
                        itemText += '<br> <i><b>Tag: '+tag+'</b></i>';
                    }
                    
                    //Chop off seconds:
                    start = start.trim();
                    if(start.length > 5){
                        start = start.slice(0, 5);
                    }
                    
                    //Chop off seconds:
                    end = end.trim();
                    if(end.length > 5){
                        end = end.slice(0, 5);
                    }
                    
                    //Create and append the new <li>:
                    const $li = $('<li draggable="true" id="'+Calendar_ID+'" data-Proposal_details_ID="'+Proposal_details_ID+
                         '" data-Venues_ID="'+Venues_ID+'" data-day="'+rowIndex+'" data-start="'+start+'" data-end="'+end+
                         '" data-tag="'+tag+'"></li>').html(itemText);
                    $ul.append($li);
                }
                
                /**   Compares two 24 hr clock time intervals, formatted consistently.
                 * If they overlap, then true is returned; otherwise, false is returned.
                 * 
                 * Note: The input parameters must be formatted as follows:
                 *       {"day": n, "start": "nn:nn", "end": "nn:nn"}, where n is any integer,
                 *       and the "start" and "end" times are in the 24 hr clock, e.g.:
                 *       "00:15", "03:45", "13:05", etc., and the day will most likely be
                 *       an integer from 1 to 4.
                 *       
                 * Example: doIntervalsOverlap({'day': 4, 'start': '09:45', 'end': '13:45'}, 
                 *          {'day': 4, 'start': '09:00', 'end': '10:45'});      
                 * 
                 * @param {Object} interval_1
                 * @param {Object} interval_2
                 * @returns {Boolean} -- Whether the input intervals overlap.
                 */
                function doIntervalsOverlap(interval_1, interval_2){
                    if(interval_1.day === interval_2.day &&
                       (interval_1.end < interval_2.start ||
                        interval_1.start > interval_2.end)){
                       return false;
                       
                    }else{
                       return true; 
                    }                   
                }//End function, "doIntervalsOverlap".

                /**   Compares two arrays of integers. If there are common items,
                 * they are returned in an array. Otherwise, an empty array is returned.
                 * 
                 * Example: findArrayIntersection([1, 4, 56, 23, 48], [23, 9, 81, 56, 48]);
                 * 
                 * @param {Array} array_1 -- A single-subscripted array of integers.
                 * @param {Array} array_2 -- A single-subscripted array of integers.
                 * @returns {Array} -- Either an array of the common elements, 
                 *      or an empty array.
                 */
                function findArrayIntersection(array_1, array_2){
                    var intersectedItems = [];
                    
                    if (Array.isArray(array_1) && array_1.length > 0 &&
                        Array.isArray(array_2) && array_2.length > 0){                   
                        const referenceSet = new Set(array_2);

                        intersectedItems = array_1.filter(item => referenceSet.has(item)); 
                        
                        if(intersectedItems.length === 1 && intersectedItems[0] === null){
                            intersectedItems = [];
                        }
                    }
                    return intersectedItems;
                }//End function, "findArrayIntersection".
                
//=================================================================================================
//===           D R A G   &   D R O P :
//=================================================================================================

                //Handle drag start:
                $('#divTopContent').on('dragstart', 'li', function(e) {
                    e.originalEvent.dataTransfer.setData('text/plain', $(this).attr('id'));
                  
                    highlightAvailabilities($(this).data('proposal_details_id'));
                });

                //Allow drop on <td>:
                $('#divTopContent').on('dragover', 'td', function(e) {
                    e.preventDefault(); //Necessary to allow drop.
                });

                //Handle drop:
                $('#divTopContent').on('drop', 'td', function(e) {
                    e.preventDefault();
                    const itemId = e.originalEvent.dataTransfer.getData('text/plain');
                    const draggedItem = $('#' + itemId);
                    adjustEventDayTime(draggedItem, this);
                    
                    moveCalendarEvent(draggedItem);
                    
                    clearAvailabilityHighlights();
                });
                
//=================================================================================================
//===           D R A G   &   D R O P   A C T I O N S :
//=================================================================================================
                
                /**   Using the passed-in "Proposal_details_ID", looks up the corresponding
                 * "eventDefinitions" element and its "preferredTimes" member. Then highlights 
                 * in yellow the corresponding "tblSchedule" cells that have been marked "true" 
                 * in the "availabilities" member.
                 * 
                 * @param {integer} Proposal_details_ID
                 */
                function highlightAvailabilities(Proposal_details_ID){
                    var availabilities = [];
                    aryGeneralEvents.forEach(item => {                    
                        if(item.Proposal_details_ID === Proposal_details_ID){
                            availabilities = item.preferredTimes;
                        }
                    });

                    for(var i = 0; i <= 5; i++){
                        const objDay = availabilities[i];                           
                        if (!objDay) continue;
                        
                        const timeSlots = Object.keys(availabilities[i]).filter(key => key !== "Day");

                        // The HTML table is not 0-based:
                        var j = 1;
                        timeSlots.forEach(slot => {
                            if(objDay[slot] === true){                                
                                const $cell = $('#tblSchedule')
                                    .find('tr')
                                    .eq(i + 1)
                                    .find('td')
                                    .eq(j);

                                $cell.css('background-color', 'yellow');
                            }
                            j++;
                        });
                    }
                }//End function, "highlightAvailabilities".
                
                /**   Clears all highlighting in "tblSchedule".
                 * 
                 */
                function clearAvailabilityHighlights(){
                    $('#tblSchedule .eventCell').css('background-color', 'white');
                }//End function, "clearAvailabilityHighlights".
                
                /**   After dragging a list item into another cell, adjusts its "day",
                 * "start" and "end" times in the DOM.
                 * 
                 * @param {jQuery obj} movedListItem -- List item just dragged to a new cell.
                 * @param {DOM elem} currentCell -- Cell that list item was dragged into.
                 */
                function adjustEventDayTime(movedListItem, currentCell){
                    const movedStart = $(movedListItem).data('start');
                    const movedEnd = $(movedListItem).data('end');

                    const currentCol = $(currentCell).index();
                    const currentRow = $(currentCell).closest('tr').index();
                    
                    //Compute "newStart":
                    var newStart = '';
                    switch(currentCol){
                        case 1:
                            newStart = '04:00';
                            break;
                        case 2:
                            newStart = '08:00';
                            break;
                        case 3:
                            newStart = '12:00';
                            break;
                        case 4:
                            newStart = '16:00';
                            break;
                        case 5:
                            newStart = '20:00';
                            break;
                        case 6:
                            newStart = '00:00';
                            break;
                    }
                    
                    //Compute "newEnd":
                    var duration = getMinutesDuration(movedStart, movedEnd);
                    const newEnd = addMinutesToTime(newStart, duration);
                    
                    //Set new "start"/"end" attributes.
                    //(Note: ".attr" is used to persist to the DOM):
                    $(movedListItem).data('day', currentRow);
                    $(movedListItem).data('start', newStart);
                    $(movedListItem).data('end', newEnd);
                    $(movedListItem).attr('data-day', currentRow);
                    $(movedListItem).attr('data-start', newStart);
                    $(movedListItem).attr('data-end', newEnd);
                }//End function, "adjustEventTime".

                /**   Computes the number of minutes between the two time-strings
                 * input. (We assume that "strStart" <= "strEnd".)
                 * 
                 * @param {String} strStart -- "HH:mm"
                 * @param {String} strEnd -- "HH:mm"
                 * @returns {int} -- The number of seconds between the above.
                 */
                function getMinutesDuration(strStart, strEnd){
                    const [strStartHours, strStartMinutes] = strStart.split(':');
                    const timeStart = new Date();                    
                    timeStart.setHours(parseInt(strStartHours, 10));                    
                    timeStart.setMinutes(parseInt(strStartMinutes, 10));
                    
                    const [strEndHours, strEndMinutes] = strEnd.split(':');
                    const timeEnd = new Date();                    
                    timeEnd.setHours(parseInt(strEndHours, 10));
                    timeEnd.setMinutes(parseInt(strEndMinutes, 10));

                    var difference = timeEnd - timeStart;
                    if(difference < 0){
                        difference = 24 * 60 * 60 * 1000;
                    }
                    var diffMinutes = Math.floor(difference / 60000);

                    return diffMinutes;
                }
                
                /**   Converts the passed-in 24 hr time string into a new one
                 * after adding the passed-in number of minutes to the old.
                 * 
                 * @param {string} timeStr -- Old 24hr time string.
                 * @param {integer} minutesToAdd -- A number of minutes.
                 * @returns {String} -- New 24hr time string.
                 */
                function addMinutesToTime(timeStr, minutesToAdd) {
                    const [hourStr, minuteStr] = timeStr.split(':');
                    const date = new Date();

                    date.setHours(parseInt(hourStr, 10));
                    date.setMinutes(parseInt(minuteStr, 10) + minutesToAdd);

                    const newHour = date.getHours().toString().padStart(2, '0');
                    const newMinute = date.getMinutes().toString().padStart(2, '0');

                    return `${newHour}:${newMinute}`;
                }
                
                /**   Attempts to move the passed-in "targetLi" (by adjusting its data),
                 * persist the "targetLi" to the database, and then display it on the 
                 * Calendar. Upon success, the displayed Calendar is updated and refreshed 
                 * from the db. Upon failure, an error alert is displayed.
                 * 
                 * @param {jQuery Li} targetLi
                 */
                function moveCalendarEvent(targetLi){
                    const Calendar_ID = targetLi.attr('id');
                    const day = targetLi.data('day');
                    const start = targetLi.data('start');
                    const end = targetLi.data('end');
                    const tag = targetLi.data('tag');
                    
                    $.post('/TEST/EventSchedule/Admin/Calendar_backend.php',
                        {
                            mode: 'moveCalendarEvent',
                            calendar_id: Calendar_ID,
                            day: day,
                            start: start,
                            end: end,
                            tag: tag
                        }, 
                        function(resp){
                            const objResp = JSON.parse(resp);
                            
                            if(objResp.status === 'true'){
                                //The move has only been made in the db,
                                //so the calendar must be reloaded from the db:
                                window.postMessage('refreshDivs', '*');
                                
                            }else{
                                alert(objResp.errMsg);
                            }
                    });
                }//End function, "moveCalendarEvent(...)".  
                
//=================================================================================================
//==== C O N T E X T   M E N U :
//=================================================================================================
                
                $('#divTopContent').on('click', '.eventCell li', function(){
                    $('#divTopContent .eventCell li').css('background-color', 'white');
                    $(this).css('background-color', 'lightblue');
                });
 
                $('#divTopContent').on('contextmenu', '.eventCell li', function(e) {
                    e.preventDefault(); //Prevent default browser menu.
                    
                    //Highlight the current event li upon double-click:
                    $('#divTopContent .eventCell li').css('background-color', 'white');
                    $(this).css('background-color', 'lightblue');

                    const $menu = $('#divTopContent #customMenu');
                    $menu.css({
                        top: e.pageY + 'px',
                        left: e.pageX + 'px',
                        display: 'block'
                    });
                    $('#divTopContent #customMenu li').css('background-color', '#eee');

                    //Store reference to the clicked cell if needed:
                    $menu.data('targetLi', this);
                }); 
                
                $(document).on('click', function(e) {
                    $('#divTopContent #customMenu').hide();
                    //If the click target is NOT inside an .eventCell li:
                    if ($(e.target).closest('.eventCell li', 'td').length === 0 &&
                        $(e.target).closest('#customMenu li').length === 0) {
                        $('#divTopContent .eventCell li').css('background-color', 'white');
                    }
                });  
                
                $('#divTopContent').on('mouseover', '#customMenu li', function(){
                    $('#divTopContent #customMenu li').css('background-color', '#eee');
                    $(this).css('background-color', 'lightblue');
                });
                
                $('#divTopContent').on('click', '#customMenu li', function(e){
                    const targetLi = $('#divTopContent #customMenu').data('targetLi');
                    const cell = $(targetLi).closest('td');
                    const colIndex = $(cell).index(); //Index within its row.
                    const rowIndex = $(cell).closest('tr').index(); //Index within the table.   
                    
                    switch($(this).text()){
                        case 'Copy':
                            //Make copy of the target Li, persist the copy and display:
                            copyCalendarEvent($(targetLi));
                            break;
                            
                        case 'Edit Calendar Event':
                            var title = $(targetLi).text();
                            var Calendar_ID = $(targetLi).attr('id');
                            var day = $(targetLi).data('day');
                            var start = $(targetLi).data('start');
                            var end = $(targetLi).data('end');
                            var tag = $(targetLi).data('tag');
                            $('#divBottomContent iframe').attr('src', 'Calendar_Event_Editor.php?title='+title+
                                    '&calendar_id='+Calendar_ID+'&day='+day+'&start='+start+'&end='+end+'&tag='+tag);
                            $(targetLi).css('background-color', 'lightblue');
                            break;
                            
                        case 'Edit General Event': 
                            var title = encodeURIComponent($(targetLi).text());
                            var Proposal_details_ID = encodeURIComponent($(targetLi).data('proposal_details_id'));
                            $('#divBottomContent iframe').attr('src', 'General_Event_Editor.php?title='+title+
                                    '&proposal_details_id='+Proposal_details_ID);
                            $(targetLi).css('background-color', 'lightblue');
                            break;
                            
                        case 'Select Venue':
                            var title = $(targetLi).text();
                            var Calendar_ID = $(targetLi).attr('id');
                            var Venues_ID = $(targetLi).data('venues_id');
                            $('#divBottomContent iframe').attr('src', 'Select_Venue.php?title='+title+
                                    '&calendar_id='+Calendar_ID+'&venues_id='+Venues_ID);
                            $(targetLi).css('background-color', 'lightblue');
                            break;

                        case 'Delete':
                            deleteCalendarEvent($(targetLi));
                            break;

                    }
                });
                
                /**   Attempts to make a copy of the passed-in "targetLi",
                 * persist the copy to the database, and then display it
                 * on the Calendar. Upon success, the displayed Calendar
                 * is updated. Upon failure an error alert is displayed.
                 * 
                 * @param {jQuery Li} targetLi
                 */
                function copyCalendarEvent(targetLi){
                    const copiedLi = targetLi.clone(true);
                    const Proposal_details_ID = copiedLi.data('proposal_details_id');
                    const Venues_ID = copiedLi.data('venues_id');
                    const day = copiedLi.data('day');
                    const start = copiedLi.data('start');
                    const end = copiedLi.data('end');
                    const tag = copiedLi.data('tag');
                    
                    $.post('/TEST/EventSchedule/Admin/Calendar_backend.php',
                        {
                            mode: 'copyCalendarEvent',
                            proposal_details_id: Proposal_details_ID,
                            venues_id: Venues_ID,
                            day: day,
                            start: start,
                            end: end,
                            tag: tag
                        }, 
                        function(resp){
                            const objResp = JSON.parse(resp);
                            
                            if(objResp.status === 'true'){
                                copiedLi.attr('id', objResp.calendar_id);                                
                                $(targetLi).parent().append($(copiedLi)); 
                                
                                //Indicate member and resouce conflicts:
                                const col = $(targetLi).parent().parent().index();
                                const row = $(targetLi).parent().parent().parent().index();
                                showMemberResourceConflicts(row, col);                            
                                
                            }else{
                                alert(objResp.errMsg);
                            }
                    });
                }//End function, "copyCalendarEvent(...)".  

                /**   Attempts to delete the passed-in "targetLi" from
                 * the database and the displayed Calendar. Upon success, 
                 * the Calendar. Upon failure an error alert is displayed.
                 * 
                 * @param {jQuery Li} targetLi
                 */
                function deleteCalendarEvent(targetLi){
                    const Calendar_ID = targetLi.attr('id');
                    
                    if(!confirm('Do you really want to delete this event from the Calendar?')){
                        return;
                    }
                    
                    $.post('/TEST/EventSchedule/Admin/Calendar_backend.php',
                        {
                            mode: 'deleteCalendarEvent',
                            calendar_id: Calendar_ID
                        }, 
                        function(resp){
                            const objResp = JSON.parse(resp);
                            
                            if(objResp.status === 'true'){
                                const currentCell = $(targetLi).parent().parent();
                                $(targetLi).remove();
                                
                                //Indicate member and resouce conflicts:
                                const col = currentCell.index();
                                const row = currentCell.parent().index();
                                showMemberResourceConflicts(row, col); 
                                                               
                            }else{
                                alert(objResp.errMsg);
                            }
                    });
                }//End function, "deleteCalendarEvent(...)".  
                
                function deepJsonParse(value) {
                    // Try to parse strings that contain JSON
                    if (typeof value === "string") {
                      try {
                        const parsed = JSON.parse(value);
                        return deepJsonParse(parsed);
                      } catch (e) {
                        return value; // not JSON, return as-is
                      }
                    }

                    // Recurse into arrays
                    if (Array.isArray(value)) {
                      return value.map(v => deepJsonParse(v));
                    }

                    // Recurse into objects
                    if (value !== null && typeof value === "object") {
                      const result = {};
                      for (const [key, val] of Object.entries(value)) {
                        result[key] = deepJsonParse(val);
                      }
                      return result;
                    }

                    // Primitive (number, boolean, null)
                    return value;
                }


            });//End "$(function(){...});".
        </script>
     </head>
    <body>
<?php require $_SERVER['DOCUMENT_ROOT'] . '/TEST/Menus/header.php'; ?>
        <div id="divTopContent"></div>
        <div id="divBottomContent">
            <iframe src=""></iframe>
        </div>

    </body>
