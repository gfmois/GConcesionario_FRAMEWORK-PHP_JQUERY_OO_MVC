<?php
function strToArray($string, $wordLimit) {
    $stringArray = array();
    
    for ($i = 0; $i < strlen($string); $i++) {
        if (ctype_upper($string[$i]) && $i == 0) {
            $stringArray[0] =  substr($string, $i, strpos($string, $wordLimit));
        } else if (ctype_upper($string[$i]) && $i != 0) {
            $stringArray[1] =  substr($string, $i, 99);
        }
    }

    return $stringArray;
}
?>