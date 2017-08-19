<?php

require_once 'core/init.php';
$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
}

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'password_current' => array(
                'required' => true, 
                'min' => 6
            ), 
            'password_new' => array(
                'required' => true, 
                'min' => 6
            ), 
            'password_new_again' => array(
                'required' => true, 
                'min' => 6, 
                'matches' => 'password_new'
            )
        ));
        if ($validation->passed()) {
            if (Hash::make(Input::get('password_current'), $user->data()->salt) !== $user->data()->password) {
                echo 'current pw is wrong';
            } else {
                $salt = Hash::salt(32);
                $user->update(array(
                    'password' => Hash::make(Input::get('password_new'), $salt), 
                    'salt' => $salt
                ));
                Session::flash('home', 'Password updated.');
                Redirect::to('index.php');
            }
            
        } else {
            foreach ($validation->errors() as $error) {
                echo $error . '<br>';
            }
        }
    }
}
?>

<form action="" method="post">
    
    Current PW: <input type="text" name="password_current"><br>
    Old PW: <input type="text" name="password_new"><br>
    Old PW Again: <input type="text" name="password_new_again"><br>
    <input type="submit" value="change pw">
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
</form>