<?php
class general{
    /**   Searches for a string in an indexed associative array,
     * where each element contains the keys, "ID" and "name".
     * Designed for use in building drop-downs from SESSION
     * variables and preventing duplicate options. Returns true 
     * if found; false otherwise.
     * 
     * @param Indexed Assoc Array $array
     * @param String $searchString
     * @return bool
     */
    public static function findNameInArray($array, $searchString) {
        foreach ($array as $element) {
            if (strpos($element['name'], $searchString) !== false) {
                return true;
            }
        }
        return false;
    }

    /**   Sorts the passed-in indexed associative array in ascending order by the "name"
     * key. Designed for use in building drop-downs from SESSION variables.
     * 
     * @param Indexed Assoc Array $array -- This array is passed in by reference.
     */
    public static function sortAscendingBy_name(&$array){
        if(!empty($array)){
            usort($array, function($a, $b) {
                return $a['name'] <=> $b['name'];
            });
        }
    }

}//End class, "general".