<?php
require_once "core/init.php";
if(Input::exists()){
   if(Token::check(Input::get('token'))){
    $validate = new Validate();
    $validation = $validate->check($_POST,array(
      'username' => array('required' => true),
      'password' => array('required' => true)
    ));
    if($validation->passed()){
      $user = new User();
      $remember = (Input::get('remember') === 'on') ? true : false;
      $login = $user->login(Input::get('username'),Input::get('password'),$remember);
      if($login){
        Redirect::to('index.php');
      }else{
        echo 'Login failed.';
      }
    } else {
      foreach($validation->errors() as $error){
        echo $error . '<br>';
      }
    }
   }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login</title>
</head>
<body>
  <form action="" method = "post">
    <div>
      <label for="username">Username:</label>
      <input type="text" name="username" id="username" autocomplete="off">
    </div>
    <div>
      <label for="password">Password:</label>
      <input type="password" name="password" id="password" autocomplete="off">
    </div>
    <div>
      <label for="remember"><input type="checkbox" name="remember" id="remember" autocomplete="off">Remember me</label>
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="Login">
  </form>

</body>
</html>