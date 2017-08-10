<?php
class Config {
    public static function get($path = null) {
        if ($path) { // if $path is not null 
            $config = $GLOBALS['config']; // Create $config variable that stores the config array in init.php
            
            // Take the passed $path parameter which is string separated by / and create an array from it
            // explode - take a character that we split string by and create an array
            $path = explode('/', $path);
            
            foreach($path as $bit) {
                // Check if the $config variable matches value from $path array
                if (isset($config[$bit])) {
                     // Set the $config variable to the asked config array value, basically remove 
                     // whole $config array and only leave the array part THAT matches the $path array value.
                    $config = $config[$bit];
                } 
            }
            return $config; 
        }
        return false; 
    }
}