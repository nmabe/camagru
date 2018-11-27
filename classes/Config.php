<?php
/**
 * Created by PhpStorm.
 * User: nmabe
 * Date: 10/5/2018
 * Time: 12:50 AM
 */

class Config
{
    public static function  get($path = NULL)
    {
        if ($path) {
            $config = $GLOBALS['config'];
            $path = explode('/', $path);
            foreach ($path as $bit) {
                if (isset($config[$bit]))
                    $config = $config[$bit];
            }
            return ($config);
        }
        return (FALSE);
    }
}