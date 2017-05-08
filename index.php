<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1>The login app</h1>
<?php
require_once 'core/init.php';

if(Session::exists('home')){
    echo Session::flash('home');
}
$user = new User();
if($user->isLoggedIn()){
?>
<p>Hello<a href="profile.php">
<?php echo escape($user->data()->username); ?>
</a></p>
<ul>
    <li><a href="logout.php">Log out</a></li>
    <li><a href="update.php">Update details</a></li>
    <li><a href="changepassword.php">Change password</a></li>
</ul>
<?php
if($user->hasPermission('admin')){
    echo '<p>You are an admin</p>';
}
} else {
    echo '<p>You need to <a href="login.php">login </a>or <a href="register.php">register</a>.</p>';
}

?>
    
</body>
</html>