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
      $remember = (Input::get('remember') === 'on') ? true : false; 
      $login = $user->login(Input::get('username'), Input::get('password'), $remember);
      
      if ($login) {
        Redirect::to('index.php');
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
  <label for="remember">
    <input type="checkbox" name="remember" id="remember"> Remember Me 
  </label><br>
  
  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
  <input type="submit" value="Login">
</form>