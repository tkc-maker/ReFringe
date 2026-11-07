<?php

class MyProposals_ui{
    
    public static function displayMyProposals($Users_ID, $db){
        $myProposals = MyProposals_db::getMyProposalsFromDb($Users_ID, $db);

        $tblReturn = "<h1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;M Y&nbsp;&nbsp;&nbsp;P R O P O S A L S&nbsp;&nbsp;&nbsp;( P u b l i c )</h1><br><br>
            <table id=\"tblMyProposals\"><tr><td colspan=\"2\"><input type=\"button\" id=\"btnNewProposal\" class=\"button\" value=\"New Proposal?\"/>
            </td></tr>";
        
        if($myProposals === false){
            $tblReturn .= "</table>";
            return $tblReturn;
        }
        $aryMyProposals = json_decode($myProposals, true);

        foreach($aryMyProposals as $proposal){
            $ID = $proposal['ID'];
            $title = !empty($proposal['title'])?$proposal['title']:('Proposal ID: '.$ID);
            
            $tblReturn .= "<tr><td><a href=\"\" class=\"aMyProposal\" data-id=\"$ID\">$title</a></td>
                <td><input type=\"button\" class=\"button btnDelete\" value=\"Delete\" data-id=\"$ID\"/></td></tr>";
        }
        $tblReturn .= "</table>";
        
        return $tblReturn;
    }
    
    
}//End class, "MyProposals_ui".