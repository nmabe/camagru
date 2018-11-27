<?php
/**
 * Created by PhpStorm.
 * User: nmabe
 * Date: 10/5/2018
 * Time: 12:50 AM
 */

class Cookie
{
    public static function exists($name)
    {
        return (isset($_COOKIE[$name]) ? true : false);
    }

    public static function get($name)
    {
        return ($_COOKIE[$name]);
    }

    public static function delete($name)
    {
        self::put($name, '', time() - 1);
    }

    public static function put($name, $value, $exp = null)
    {
        setcookie($name, $value, time() + $exp, '/');
    }
}