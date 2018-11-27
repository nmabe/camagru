<?php
/**
 * Created by PhpStorm.
 * User: nmabe
 * Date: 10/30/2018
 * Time: 5:23 AM
 */

function  database($path = NULL)
{
    $_DATABASE['config'] = array(
        'mysql' => array(
            'dsn' => 'mysql:host=localhost',
            'host' => 'localhost',
            'database' => 'db_camagru',
            'username' => 'root',
            'password' => 'co65amHr'
        )
    );

    if ($path) {
        $config = $_DATABASE['config'];
        $path = explode('/', $path);
        foreach ($path as $bit) {
            if (isset($config[$bit]))
                $config = $config[$bit];

        }
        return ($config);
    }
    return (FALSE);
}
function make($string, $salt = '')
{
    return (hash('sha256', $string.$salt));
}

function salt($seasoning)
{
    $seasoning = str_shuffle($seasoning);
    return ($seasoning);
}

function init(){

    $pdo = new PDO(database('mysql/dsn'),database('mysql/username'), database('mysql/password'));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($pdo) {
        $sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'db_camagru'";
        $result = $pdo->query($sql)->fetchAll();
        if (empty($result))
        {
            header('Location: config/setup.php');
        }
    }
}

?>