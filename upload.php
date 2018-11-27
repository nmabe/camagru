<?php
    require_once 'core/init.php';

    $user = new User();
    

    if (isset($_POST['upload']))
    {
        if ($user->isLoggedIn())
        {
            $save = new Save($user->data()->username);

            if (Input::exists())
            {
                if(Token::check(Input::get('token')))
                {
                    if (!empty($_FILES['isthombe']['tmp_name']))
                    {
                        $saved = $save->upload();
                        if ($saved)
                        {
                            echo "Image Saved Successfully<br>";
                        }
                        else{
                                foreach ($save->error() as $error) 
                                    echo "{$error}<br>";
                        }
                    }
                    else{
                        echo "Please Faka Isthombe Lapho!!!<br>";
                    }
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Upload<?php echo (($user->isLoggedIn()) ? ': '.$user->data()->username : ''); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
    <link href="https://fonts.googleapis.com/css?family=Bungee+Outline" rel="stylesheet">
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
                    <a href="profile.php">Profile</a>
                    <a href="#" class="active">Upload</a>
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
        <form enctype="multipart/form-data" action="upload.php" method="post">
            <input type="hidden" name="MAX_FILE_SIZE" value="50000"/>
            upload Image: <input type="file" name="isthombe"/>
            <input type="hidden" name="token" value="<?php echo Token::generate();?>"/>
            <input type="submit" name="upload" value="Submit Image"/>
        </form>
        <?php
            }
            else{
                echo '<p>You need to <a href="login.php">login</a> or <a href="register.php">register</a></p>';
                echo '<center><img src="img/kats.jpg"></center>';
                          }?>
        </article>
    </section>
   <footer>
        <p>&copy; 2018 <a href="http://www.wethinkcode.co.za/students?filter=false">@nmabe</a> Camagru.com </p>
    </footer>
</body>
</html>