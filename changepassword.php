<?php
require_once 'core/init.php';
$user = new User();
if(!$user->isLoggedIn()){
  Redirect::to('index.php');
} else {
  if(Input::exists()){
    if(Token::check(Input::get('token'))){
      $validate = new Validate();
      $validation = $validate->check($_POST,array(
        'oldPassword' => array( 'required' => true,
                                'min' => 6,
                                'max' => 20),
        'newPassword' => array( 'required' => true,
                                'min' => 6,
                                'max' => 20),
        'confNewPassword' => array( 'required' => true,
                                    'matches' => 'newPassword')
      ));
      if($validation->passed()){
        if(Hash::make(Input::get('oldPassword'),$user->data()->salt) === $user->data()->password){
          try{
          $hash = Hash::make(Input::get('newPassword'),$user->data()->salt);
          $user->update(array(
          'password' => $hash
          ));
          Session::flash('home','Your details have been updated');
          Redirect::to('index.php');
          } catch (Exception $e){
            die($e->getMessage());
          }
        } else {
          echo 'Wrong password.';
        }
      } else {
        foreach($validation->errors() as $error){
          echo $error . '<br>';
        }
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
  <title>Change password</title>
</head>
<body>
  <form action="" method = "post">
    <div>
      <label for="oldPassword">Old password:</label>
      <input type="password" name="oldPassword" id="oldPassword" >
    </div>
    <div>
      <label for="newPassword">New password:</label>
      <input type="password" name="newPassword" id="newPassword" >
    </div>
    <div>
      <label for="confNewPassword">Confirm new passsword:</label>
      <input type="password" name="confNewPassword" id="confNewPassword" >
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="update">
  </form>
</body>
</html>