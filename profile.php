<?php
/**
 * Created by PhpStorm.
 * User: nmabe
 * Date: 10/5/2018
 * Time: 12:48 AM
 */

require_once 'core/init.php';

$user = new User();


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Profile<?php echo (($user->isLoggedIn()) ? ': '.$user->data()->username : ''); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
    <link rel="shortcut icon" href="img/favicon.ico" />
</head>
<body>
    <header>
        <a href="index.php" >Camagru</a>
        <h2>Profile</h2>
    </header>
    <section>
        <nav>
            <div class="vertical-menu">
                <a href="index.php">Home</a>
                <a href="#" class="active">Profile</a>
                <a href="upload.php" >Upload</a>
                <a href="gallery.php">Gallery</a>
                <a href="snap.php">Snap a Pic</a>
                <a href="update.php">Update Profile</a>
                <a href="logout.php">Logout</a>
            </div>
        </nav>        
        <article>
            <?php 
                if ($user->isLoggedIn())
                {
                    ?>
            <h1> <?php echo $user->data()->name ?></h1>
            <p><ul>
                <li>Username: <?php echo $user->data()->username ?></li>
                <li>Name: <?php echo $user->data()->name ?></li>
                <li>Gender: <?php echo ($user->data()->username == 1) ? "Male" : "Female"; ?></li>
                <li>Email: <?php echo $user->data()->email ?></li>
                <li>Joined: <?php echo $user->data()->joined ?></li>
                <li>Activated: <?php echo ($user->data()->active == 1) ? "Yes" : "No"; ?></li>
            </ul></p>
            <p>
                Standing on the River Thames, London has been a major settlement for two millennia, its history going back to its founding by the Romans, who named it Londinium.
            </p>
            <?php
            }
                else{
                    echo '<p>You need to <a href="login.php">login</a> or <a href="register.php">register</a></p>';
                    echo '<center><img src="img/kats.jpg"></center>';
                }
            ?>
        </article>
    </section>
    <footer>
        <p>&copy; 2018 <a href="http://www.wethinkcode.co.za/students?filter=false">@nmabe</a> Camagru.com </p>
    </footer>
</body>
</html>