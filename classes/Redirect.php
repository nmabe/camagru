<?php
/**
 * Created by PhpStorm.
 * User: nmabe
 * Date: 10/5/2018
 * Time: 12:51 AM
 */

class Redirect
{
    public static function to($location = NULL)
    {
        if ($location)
        {
            if (is_numeric($location))
            {
                switch ($location)
                {
                    case 404:
                        header('HTTP/1.0 404 Not Found');
                        include 'includes/errors/404.php';
                        exit();
                    break;
                }
            }
            header('Location:' . $location);
            exit();
        }
    }
}