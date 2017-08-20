<?php 
require_once 'core/init.php';

if (Session::exists('home')) {
    echo '<p>' . Session::flash('home') . '</p>';
}

// echo Session::get(Config::get('session/session_name'));

$user = new User(); 

if ($user->isLoggedIn()) {
?>
    <p>Hello <a href="profile.php?user=<?= escape($user->data()->username); ?>"><?= escape($user->data()->username); ?></a>!</p>
    <ul>
        <li><a href="logout.php">Log out</a></li>
        <li><a href="update.php">Update</a></li>
        <li><a href="change_password.php">Change Password</a></li>
    </ul>
<?php
    if ($user->hasPermission('admin')) {
        echo '<p>You are an admin.</p>';
    }
} else {
    echo "<a href='login.php'>login</a> or <a href='register.php'>register</a></p>";
}
