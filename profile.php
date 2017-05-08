<?php
require_once 'core/init.php';
$user = new User();
if($user->isLoggedIn()){
  $username = $user->data()->name;
  $data = $user->data();
} else {
  Redirect::to('index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo "$username's Profile" ?></title>
</head>
<body>
<?php echo '<pre>',print_r($data),'</pre>'; ?>
</body>
</html>