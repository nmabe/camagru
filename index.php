<?php
/**
 * Created by PhpStorm.
 * User: nmabe
 * Date: 10/5/2018
 * Time: 12:47 AM
 */


require_once 'config/database.php';

init();

require_once 'core/init.php';
$user = new User();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Camagru<?php echo (($user->isLoggedIn()) ? ': '.$user->data()->username : ''); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
    <link rel="shortcut icon" href="img/favicon.ico" />
</head>
<body>
<header>
    <a href="index.php" >Camagru</a>
    <h2>Home</h2>
</header>
<section>
        <nav> 
                <div class="vertical-menu">
                    <a href="#" class="active">Home</a>
                    <a href="profile.php">Profile</a>
                    <a href="upload.php" >Upload</a>
                    <a href="gallery.php">Gallery</a>
                    <a href="snap.php">Snap a Pic</a>
                    <a href="update.php">Update Profile</a>
                    <a href="logout.php">Logout</a>
                </div>
        </nav>    
            <article>
                <?php
                if (Session::exists('home'))
                {
                    echo "<p>". Session::flash('home')."<br>";
                }
                
                $user = new User();
                
                
                if ($user->IsLoggedIn())
                {
                ?>
                
                    <p> Hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"> <?php echo escape($user->data()->username) ?></a>!</p>
                    <?php
                    if ($user) {
                    if ($user->hasPermissions('Admin')) {
                    echo "<p>You are an Admin </p>";
                    }
                    }
                
                ?>
                
                    <?php
                }
                else
                {
                    echo '<p>You need to <a href="login.php">login</a> or <a href="register.php">register</a></p>';
                }
                $gallery = new Gallery();
                $photos = $gallery->getAll();
                if (!empty($photos))
                {
                            $i = 0;
                            $max = count($photos);
                            foreach ($photos as $key => $value)
                            {
                                $name = $value['name'];
                                $bigSrc = $value['path'];
                                $comments = $value['comments'];
                                $like = $value['likes'];
                                $likes = 'likes'.$i;
                                $delete = 'delete_'.$i;
                                $send = 'send'.$i;
                                ?>
                                <div class="gallery">
                                        <img id="myImage" src="<?php echo $bigSrc;?>" alt="<?php echo $name;?>" width="300" height="200">
                                    
                                    <div class="desc"><?php echo $name;?></div>
                                    <!-- htmlcommentbox-->
                                    <?php 
                                    if ($user->isLoggedIn())
                                    {
                                        ?>
                                        <div id="commentLa">
                                            <form action="#" method="post">
                                                <p><?php echo $like?> likes
                                                <button name="<?php echo $likes?>">Like</button></p>
                                                <?php if (isset($_POST[$likes]))
                                                {
                                                    if($gallery->like($name))
                                                    {
                                                        Session::flash('success', 'image successfully deleted');
                                                        
                                                    }
                                                }?>
                                                <button name="<?php echo $delete ?>">delete</button><?php if (isset($_POST[$delete]))
                                                {
                                                    if($gallery->delete($name))
                                                    {
                                                        Session::flash('success', 'image successfully deleted');
                                                        
                                                    }
                                                }?>
                                                <input type="text" name="comment" id="comment" placeholder="write a comment..." autocomplete="false">
                                                <input type="hidden" name="token" id="token" value="<?php echo  Token::generate();?>">
                                                <input type="submit" name="<?php echo $send ?>" value="send"><?php
                                                if (isset($_POST[$send]) && isset($_POST['comment']))
                                                {
                                                    if($gallery->comment($name, escape($_POST['comment'])))
                                                    {
                                                        Session::flash('success', 'comment successfully posted');
                                                        
                                                    }
                                                }?>
                                            </form>
                                            <div class="comments">
                                                <p><?php
                                                    foreach ($comments as $comment) {
                                                        foreach ($comment as $commenter => $commented) {
                                                            echo  '<a href="index.php" > ' .$commenter . '</a>'.'<br><p style="font-size:4;">' . $commented . '</p><br><hr><br>';
                                                        }
                                                    }
                                                 ?>
                                                </p>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                    <?php
                                    $i++;
                                }
                            }
                            else{
                                echo '<p>You have no images you need to <a href="upload.php">upload pics</a> or <a href="snap.php">take a snap</a></p>';
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