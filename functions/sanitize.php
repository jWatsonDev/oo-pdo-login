<?php
// escape function
// ENT_QUOTES option - escape both single/double quotes
// third param - define character encoding
function escape($string) {
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}