<?php
function escape($string)
{
    return (htmlentities($string, ENT_QUOTES, 'UTF-8'));
}


/**
 * Created by PhpStorm.
 * User: nmabe
 * Date: 10/5/2018
 * Time: 12:53 AM
 */