<?php
class genres_ui{
    /**   Populates an HTML 5 drop-down from the associative array passed in. 
     * If an empty array is passed in, then '' is returned.
     * 
     * @param Assoc Array $aryGenres -- an associative array of [ID, name] 
     *      from the "Genres" table.
     * @return string -- The populated drop-down.
     */
    public static function displayGenreDropdown($aryGenres){
        if(!empty($aryGenres)){
            $dropdown = "<select name=\"dd_genres\" id=\"dd_genres\">";
            foreach ($aryGenres as $row):
                $dropdown .= "<option value=\"".$row['ID']."\">".htmlspecialchars($row['name'])."</option>";
            endforeach;
            $dropdown .= "</select>";

            return $dropdown;
        }else{
            return '';
        }
    }//End function, "displayGenreDropdown".
    
    /**   Displays a page for the "Genres" reference table editor.
     * 
     */
    public static function genresDisplay(){
       $newGenre = (isset($_SESSION['G_NewGenre']) ? $_SESSION['G_NewGenre'] : '');
       
        echo "
        <h1>&nbsp;&nbsp;&nbsp;E D I T&nbsp;&nbsp;&nbsp;R E F E R E N C E&nbsp;&nbsp;&nbsp;T A B L E ,<br>
        &nbsp;&nbsp;&nbsp;G e n r e s&nbsp;&nbsp;&nbsp;( A d m i n )</h1><br>
        <div id=\"divGenres\" class=\"ht-theme-main-dark\"></div>
        <div id=\"divButtons\">
            <input type=\"button\" id=\"btnSaveCheckedRows\" value=\"Save Checked Rows\"/>
            <input type=\"button\" id=\"btnDeleteCheckedRows\" value=\"Delete Checked Rows\"/><br>
            <label class=\"fieldHeading\">Add New Genre: </label>
            <input type=\"text\" id=\"txtNewGenre\" value=\"$newGenre\"/>
            <input type=\"button\" id=\"btnSaveNewGenre\" value=\"Save New Genre\"/>
        </div>
        ";
    }//End function, "GenresDisplay".    
}