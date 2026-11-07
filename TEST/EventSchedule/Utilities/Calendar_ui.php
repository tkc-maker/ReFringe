<?php

class Calendar_ui {
    
    /**   Returns an empty 4-day calendar divided into 4-hour time intervals,
     * some of which may not be used in practice. Also specifies a <div> used
     * for a drop-down context menu, that should be hidden/revealed by CSS
     * and JavaScript. The context menu will provide additional options
     * for the selected calendar event.
     * 
     * @return HTML -- Empty calendar and context menu <div>.
     */
    public static function displayEmptyCalendarAndMenu(){
        return '
            <h1>E V E N T S&nbsp;&nbsp;&nbsp;C A L E N D A R&nbsp;&nbsp;&nbsp;( A d m i n )</h1>.
            <table id="tblSchedule">
                <tr>
                    <th>Day</th><th>4am-8am</th><th>8am-noon</th><th>noon-4pm</th>
                    <th>4pm-8pm</th><th>8pm-midnight</th><th>midnight-4am</th>
                </tr>
                <tr>
                    <td class="dayCell">1</td><td class="eventCell"><ul></ul></td><td class="eventCell"><ul></ul></td><td class="eventCell"><ul></ul></td>
                    <td class="eventCell"><ul></ul></td><td class="eventCell"><ul></ul></td><td class="eventCell"><ul></ul></td>
                </tr>
                <tr>
                    <td class="dayCell">2</td><td class="eventCell"><ul></ul></td><td class="eventCell"><ul></ul></td><td class="eventCell"><ul></ul></td>
                    <td class="eventCell"><ul></ul></td><td class="eventCell"><ul></ul></td><td class="eventCell"><ul></ul></td>
                </tr>
                <tr>
                    <td class="dayCell">3</td><td class="eventCell"><ul></ul></td><td class="eventCell"><ul></ul></td><td class="eventCell"><ul></ul></td>
                    <td class="eventCell"><ul></ul></td><td class="eventCell"><ul></ul></td><td class="eventCell"><ul></ul></td>
                </tr>
                <tr>
                    <td class="dayCell">4</td><td class="eventCell"><ul></ul></td><td class="eventCell"><ul></ul></td><td class="eventCell"><ul></ul></td>
                    <td class="eventCell"><ul></ul></td><td class="eventCell"><ul></ul></td><td class="eventCell"><ul></ul></td>
                </tr>
            </table>            
            
            <ul id="customMenu">
                <li>Copy</li>
                <li>Edit General Event</li>
                <li>Edit Calendar Event</li>
                <li>Select Venue</li>
                <li>Delete</li>
            </ul>            
        ';
    }//End function, "function displayEmptyCalendarAndMenu".
    
    /**   Returns a screen for editing a Calendar Event.
     * 
     * @param string $eventTitle
     * @return HTML string -- Fields for editing a Calendar Event.
     */
    public static function displayCalendarEventFields($eventTitle, $day, $start, $end, $tag){
        
        return "
            <h1>E D I T&nbsp;&nbsp;&nbsp;C A L E N D A R&nbsp;&nbsp;&nbsp;E V E N T<br>-- $eventTitle</h1>
            <table>
                <tr>
                    <td class=\"label\">
                    *Day: <select id=\"selDay\">
                            <option value=\"1\" ".($day === '1'?'selected':'').">1</option>
                            <option value=\"2\" ".($day === '2'?'selected':'').">2</option>
                            <option value=\"3\" ".($day === '3'?'selected':'').">3</option>
                            <option value=\"4\" ".($day === '4'?'selected':'').">4</option>
                        </select>
                    </td>
                    <td class=\"label\">*Start: <input type=\"text\" id=\"txtStart\" value=\"$start\"/></td>                
                    <td class=\"label\">*End: <input type=\"text\" id=\"txtEnd\" value=\"$end\"/></td>                
                    <td class=\"label\">Group Tag: <input type=\"text\" id=\"txtTag\" value=\"$tag\"/></td>                
                </tr>
                <tr>
                    <td colspan=\"4\" id=\"errMsg\"></td>
                </tr>
                <tr>
                    <td colspan=4><input type=\"button\" class=\"button\" id=\"btnSaveCalendarEvent\" value=\"Save Calendar Event\"/></td>
                </tr>            
            </table>
        ";
        
    }//End function, "displayCalendarEventFields".
    
}//End class, "Calendar_ui".
