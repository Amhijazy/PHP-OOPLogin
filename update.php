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
        'name' => array('required' => true,
                        'min' => 2,
                        'max' => 50)
      ));
      if($validation->passed()){
       try{
        $user->update(array(
          'name' => Input::get('name')
        ));
        Session::flash('home','Your details have been updated');
        Redirect::to('index.php');
       } catch (Exception $e){
        die($e->getMessage());
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
  <title>Update</title>
</head>
<body>
  <form action="" method = "post">
    <div>
      <label for="name">Name:</label>
      <input type="text" name="name" id="name" value="<?php echo escape($user->data()->name); ?>">
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="update">
  </form>
</body>
</html>