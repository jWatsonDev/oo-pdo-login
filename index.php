<?php 
require_once 'core/init.php';

$user = DB::getInstance()->get('users', array('username', '=', 'alex'));

if (!$user->count()) {
    echo "no user";
} else {
    echo "Ok";
}

