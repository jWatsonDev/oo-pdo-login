<?php 
require_once 'core/init.php';

if (Session::exists('home')) {
    echo '<p>' . Session::flash('home') . '</p>';
}

// echo Session::get(Config::get('session/session_name'));

$user = new User(); 

if ($user->isLoggedIn()) :
?>
    <p>Hello <a href="#"><?= escape($user->data()->username); ?></a>!</p>
    <ul>
        <li><a href="logout.php">Log out</a></li>
    </ul>
<?php
else :
    echo "<a href='login.php'>login</a> or <a href=''>register</a></p>";
endif;
