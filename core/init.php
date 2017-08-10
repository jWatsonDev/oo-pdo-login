<?php
session_start();

// global array of various config settings
$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'db' => 'oo-pdo-login'
    ),
    // cookies expiration - how long do want users' credentials to be remembered?
    'remember' => array(
        'cookie_name' => 'hash', 
        'cookie_expiry' => 604800
    ), 
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token' 
    )
);

// must auto load this in - quick and efficient way of loading in
// allows us to pass in function that runs every time it's accesses
// instead of using require_once
// https://stackoverflow.com/questions/7651509/what-is-autoloading-how-do-you-use-spl-autoload-autoload-and-spl-autoload-re
spl_autoload_register(function($class) { // anonomous function
    require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';