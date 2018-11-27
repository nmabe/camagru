<?php
/**
 * Created by PhpStorm.
 * User: nmabe
 * Date: 10/5/2018
 * Time: 12:52 AM
 */

session_start();


$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => 'localhost',
        'database' => 'db_camagru',
        'username' => 'root',
        'password' => 'co65amHr'
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_exp' => 604800
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    )
);

spl_autoload_register(function ($class){
    require_once  'classes/' . $class .'.php';
});

require_once 'functions/sanitize.php';

if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name')))
{
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('user_session', array('hash','=',$hash));

    if ($hashCheck->count()) {
        $user = new User($hashCheck->first()->user_id);
        $user->login();
    }
}