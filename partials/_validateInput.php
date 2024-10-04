<?php
function validateInput($str){
    $str_validated = htmlspecialchars($str);
    return $str_validated;
}
?>