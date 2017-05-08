<?php
// This whole file is to load in all my data on top of files

session_start();

$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'db' => 'login_app'
    ),
    'remember' => array(
        'cookie_name' => 'arken',
        'cookie_expiry' => 60*60*24
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    )
);

// this built in function handles the require class file
// for us when we say $db = new DB()
// It goeas ahead and requires the file for us

spl_autoload_register(function($class){
    require_once 'classes/' . $class . '.php';
});

// On the other hand, functions cannot be required like class
//files, so we gotta require it manually

require_once 'functions/sanitize.php';

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('users_session',array('hash','=',$hash));
    if($hashCheck->count()){
        $user = new User($hashCheck->first()->user_id);
        $user->login();
    }
}

?>