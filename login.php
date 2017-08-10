<?php
require_once 'core/init.php';
if (Input::exists()) {
  if (Token::check(Input::get('token'))) {
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
      'username' => array('required' => true), 
      'password' => array('required' => true)
    ));
    
    if ($validation->passed()) {
      // log user in
      $user = new User();
      $login = $user->login(Input::get('username'), Input::get('password'));
      
      if ($login) {
        echo 'success';
      } else {
        echo 'sorry, there was a problem loggin in';
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
  <input type="text" name="username" autocomplete="off"><br>
  <input type="text" name="password" autocomplete="off"><br>
  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
  <input type="submit" value="Login">
</form>