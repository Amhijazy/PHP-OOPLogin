<?php
require_once 'core/init.php';
echo 'Logging you out';
$user = new User();
$user->logout();
Redirect::to("index.php");