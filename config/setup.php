<?php
/**
 * Created by PhpStorm.
 * User: nmabe
 * Date: 10/18/2018
 * Time: 8:38 AM
 */

require_once  'database.php';

function createDatabase()
{
    try{
        $pdo = new PDO(database('mysql/dsn'),database('mysql/username'), database('mysql/password'));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($pdo) {
            $sql = "CREATE DATABASE IF NOT EXISTS `db_camagru` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci";
            $created = $pdo->query($sql);
            if ($created) {
                $pdo->query("USE ".database('mysql/database'));
                unset($pdo);
                return (true);
            }
            else
            {
                foreach ($pdo->errorInfo() as $key => $value)
                    echo "{$key} => {$value}<br>";
                unset($pdo);
                return (false);
            }
        }
    }catch(PDOException $e)
    {
        throw new pdoException($e->getMessage(), (int)$e->getCode());
    }
}

function createTableUsers()
{
    try{
        $pdo = new PDO(database('mysql/dsn'),database('mysql/username'), database('mysql/password'));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($pdo) {
            $sql = "CREATE TABLE IF NOT EXISTS`db_camagru`.`users` ( 
                        `id` INT NOT NULL AUTO_INCREMENT ,
                        `username` VARCHAR(20) NOT NULL ,
                        `name` VARCHAR(50) NOT NULL ,
                        `gender` tinyint(1) ,
                        `email` VARCHAR(32) NOT NULL , 
                        `password` VARCHAR(64) NOT NULL ,
                        `salt` VARCHAR(32) NOT NULL ,
                        `code` INT NOT NULL , 
                        `joined` DATETIME NOT NULL ,
                        `notify` INT NOT NULL,
                        `active` INT NOT NULL , 
                        `group` INT NOT NULL ,
                        PRIMARY KEY (`id`)) 
                        CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB";
            $created = $pdo->query($sql);
            if ($created) {
                unset($pdo);
                return (true);
            }
        }
    }catch(PDOException $e)
    {
        throw new pdoException($e->getMessage(), (int)$e->getCode());
    }
}

function createTableGroup()
{
    try{
        $pdo = new PDO(database('mysql/dsn'),database('mysql/username'), database('mysql/password'));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($pdo) {
            $sql = "CREATE TABLE IF NOT EXISTS `db_camagru`.`group` ( 
                        `id` INT PRIMARY KEY AUTO_INCREMENT NOT NULL, 
                        `name` VARCHAR(20) NOT NULL,
                        `permission` TEXT NOT NULL) 
                        CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB";
            $created = $pdo->query($sql,PDO::ERRMODE_EXCEPTION);
            if ($created) {
                $sql = "INSERT INTO `db_camagru`.`group` (
                        `id`,`name`,`permission`)
                        VALUES (
                        NULL, 'Administrator', '{\"Admin:\" 1}'
                        )";
                $pdo->query($sql);
                unset($pdo);
                return (true);
            }
        }    
    }catch(PDOException $e)
    {
        throw new pdoException($e->getMessage(), (int)$e->getCode());
    }
}

function createTableUsersSessions()
{
    try{
        $pdo = new PDO(database('mysql/dsn'),database('mysql/username'), database('mysql/password'));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($pdo) {
            $sql = "CREATE TABLE IF NOT EXISTS`db_camagru`.`user_session` ( 
                        `id` INT NOT NULL AUTO_INCREMENT , 
                        `user_id` INT NOT NULL , `hash` VARCHAR(50) NOT NULL ,
                        PRIMARY KEY (`id`))
                        CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB";
            $created = $pdo->query($sql,PDO::ERRMODE_EXCEPTION);
            if ($created) {
                unset($pdo);
                return (true);
            }
        }    
    }catch(PDOException $e)
    {
        throw new pdoException($e->getMessage(), (int)$e->getCode());
    }
}

function createTableImages()
{
    try {
        $pdo = new PDO(database('mysql/dsn'),database('mysql/username'), database('mysql/password'));
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($pdo) {
            $sql = "CREATE TABLE IF NOT EXISTS `db_camagru`.`images` ( 
                        `id` INT PRIMARY KEY AUTO_INCREMENT NOT NULL, 
                        `img` TEXT NOT NULL,
                        `user_id` INT NOT NULL) 
                        CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB";
            $created = $pdo->query($sql,PDO::ERRMODE_EXCEPTION);
            if ($created) {
                return (true);
            }   
        }
    }catch(PDOException $e)
    {
        throw new pdoException($e->getMessage(), (int)$e->getCode());
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/style.css" />
    <link rel="shortcut icon" href="img/favicon.ico" />
</head>
<body style="background-image:url('../img/bg/background-2719572_960_720.jpg')">
<header>
    <a href="../index.php" >Camagru</a>
    <h2>Gallery</h2>
</header>
<section>
    <nav>
        <div class="vertical-menu">
        </div>
    </nav>
    <article>
        <?php
        try {

            if (createDatabase())
                echo '<p style="color: green;">Database Created Successfully<br></p>';
            else
                echo '<p style="color: red;">Error Creating Database</p>';

            if (createTableUsers()){
                echo '<p style="color: green;">Error Table Users Created Successfully<br></p>';
            }
            else{
                echo '<p style="color: red;">Error Creating Table Users</p>';
            }

            if (createTableUsersSessions()){
                echo '<p style="color: green;">Table user_sessions Created Successfully<br></p>';
            }
            else{
                echo '<p style="color: red;">Error Creating Table user_sessions<br></p>';
            }

            if (createTableGroup()){
                echo '<p style="color: green;">Table group Created Successfully<br></p>';
            }
            else{
                echo '<p style="color: red;">Error Creating Table Group<br></p>';
            }

            if (createTableImages()){
                echo '<p style="color: green;">Table images successfully created<br></p>';
            }
            else{
                echo '<p style="color: red;">Error Creating Table images<br></p>';
            }

            $pdo = new PDO(database('mysql/dsn'),database('mysql/username'), database('mysql/password'));
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $salt = salt("saltingsasfarassaltsgo!@#$%^&*()");
            $password = make('notadmin',$salt);
            $t_stamp = date('Y-m-d H:i:s');

            try {
                if ($pdo) {
                    $sql = "INSERT INTO `db_camagru`.`users` (
          `id`, `username`, `name`, `gender`, `email`, `password`, `salt`, `code`, `joined`, `notify`,`active`, `group`) 
          VALUES (NULL, 'banele', 'Banele Mabe', '1', 'banelemabe@gmail.com', '$password', 
          '$salt', '3987', '$t_stamp', '1','1', '2');";

                    $created = $pdo->query($sql, PDO::ERRMODE_EXCEPTION);
                    if (!$created) {
                        echo '<p style="color: red;">Error Creating Admin User<br></p>';
                    }
                    else{
                        echo '<p style="color: green;">Admin account successfully created<br></p>';
                    }
                }
            }catch (PDOException $e)
            {
                throw new pdoException($e->getMessage(), (int)$e->getCode());
            }


            try {
                if ($pdo) {
                    $sql = "INSERT INTO   `db_camagru`.`users` (
          `id`, `username`, `name`, `gender`, `email`, `password`, `salt`, `code`, `joined`, `notify`,`active`, `group`) 
          VALUES (NULL, 'Nkush', 'Nkush Gesterism', '1', 'gesterism@camagru.com', '$password', 
          '$salt', '3987', '$t_stamp', '1','1', '2');";

                    $created = $pdo->query($sql, PDO::ERRMODE_EXCEPTION);
                    if (!$created) {
                        echo '<p style="color: red;">Error Creating Admin User<br></p>';
                    }
                    else{
                        echo '<p style="color: green;">Admin account successfully created<br></p>';
                    }
                }
            }catch (PDOException $e)
            {
                throw new pdoException($e->getMessage(), (int)$e->getCode());
            }
        } catch (Exception $e)
        {
            die($e->getMessage());
        }

        ?>

    </article>
</section>
<footer>
    <p>&copy; 2018 <a href="http://www.wethinkcode.co.za/students?filter=false">@nmabe</a> Camagru.com </p>
</footer>
</body>
</html>