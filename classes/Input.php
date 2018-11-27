<?php
/**
 * Created by PhpStorm.
 * User: nmabe
 * Date: 10/5/2018
 * Time: 12:50 AM
 */

class Input{
    public static function exists($type = 'post'){
        switch ($type)
        {
            case  'post':
                return (!empty($_POST) ? true : false);
            break;
            case  'get':
                return (!empty($_GET) ? true : false);
            break;
            default :
                return (false);
            break;
        }
    }

    public static function get($item){
        if (isset($_POST[$item])) {
            return ($_POST[$item]);}
        elseif (isset($_GET[$item])) {
            return ($_GET[$item]);
        }
        return('');
    }
}