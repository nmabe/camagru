<?php
/**
 * Created by PhpStorm.
 * User: nmabe
 * Date: 10/5/2018
 * Time: 12:50 AM
 */

class Hash
{
    public static function make($string, $salt = '')
    {
        return (hash('sha256', $string.$salt));
    }

    public static function salt($seasoning)
    {
        $seasoning = str_shuffle($seasoning);
        return ($seasoning);
    }

    public static function unique()
    {
        return (self::make(uniqid()));
    }
}