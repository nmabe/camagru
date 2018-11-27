<?php
/**
 * Created by PhpStorm.
 * User: nmabe
 * Date: 10/5/2018
 * Time: 12:48 AM
 */

require_once 'core/init.php';

$user = new User();

if (isset($_POST['upload'])){
    if (Input::exists()) {
        if (Token::check(Input::get('token'))){
            $img = $_POST['img'];
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $save = new Save($user->data()->username);
            $file = $user->data()->username.'-'.date('YmdHis').'.png';
            $saved = $save->snapLoad($file, $data);
            if ($saved)
            {
                $file = '';
            }
            else{
                foreach ($save->error() as $error) 
                    echo "{$error}<br>";
                }
            }
        }
        else{
            echo "{$file} failed to Save<br>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Snap <?php echo (($user->isLoggedIn()) ? ':'.$user->data()->username : ''); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css"  href="css/style.css" />
    <link rel="shortcut icon" href="img/favicon.ico" />
</head>
<body >
    <header>
        <a href="index.php" >Camagru</a>
        <h2>Snap a pic</h2>
    </header>
    <section>
        <nav>
        <div class="vertical-menu">
                    <a href="index.php">Home</a>
                    <a href="profile.php">Profile</a>
                    <a href="upload.php" >Upload</a>
                    <a href="gallery.php">Gallery</a>
                    <a href="#" class="active">Snap a Pic</a>
                    <a href="update.php">Update Profile</a>
                    <a href="logout.php">Logout</a>
                </div>
        </nav>
        <article>
            <?php 
                if ($user->isLoggedIn())
                {                    
                    $gallery = new Gallery($user->data()->username);
                    if ($user && $gallery)
                    ?>
                <div class="top-container">
                    <div class="stickers">
                        <button onclick="sticker1(1)" ><img src="img/stickers/sticker1.png" id="sticker1" alt="glasses" ></button>
                        <button onclick="sticker1(2)"><img src="img/stickers/sticker2.png" id="sticker2" alt="glasses"></button>
                        <button onclick="sticker1(3)"><img src="img/stickers/sticker3.png" id="sticker3" alt="glasses" ></button>
                        <button onclick="sticker1(4)"><img src="img/stickers/sticker4.png" id="sticker4" alt="glasses" ></button>
                        <script>
                        function sticker1(id)
                        {
                            var img = new Image();
                            if (id == 1)
                                img.src = 'img/stickers/sticker1.png';
                            else if (id == 2)
                                img.src = 'img/stickers/sticker2.png';
                            else if (id == 3)
                                img.src = 'img/stickers/sticker3.png';
                            else if (id == 4)
                                img.src = 'img/stickers/sticker4.png';
                            cnv = document.getElementById('stickercnvs');
                            contx = cnv.getContext('2d');
                            contx.drawImage(img,0,0,cnv.width + 2,cnv.height);
                            cnv.style.visibility = 'visible';
                        }
                        </script>
                    </div>
                    <video id="video"></video>
                    <canvas  style="background-color: #f1f1f1;" id="stickercnvs"></canvas>
                    <button id="photo-button" class="btn btn-dark">Snap</button>
                <select id="photo-filter">
                    <option value="none"> Select Filter</option>
                    <option value="grayscale(100%)"> Grayscale</option>
                    <option value="Sepia(100%)"> Sepia</option>
                    <option value="invert(100%)"> Invert</option>
                    <option value="hue-rotate(90deg)"> Hue</option>
                    <option value="blur(10px)"> Blur</option>
                    <option value="contrast(200%)"> Constrast</option>
                </select>
                <button id="clear-button" class="btn btn-light"> Clear </button>
                    <canvas id="canvas" width="200" height="200"></canvas>
                    <form method="post" action="" onsubmit="prepareImg();">
                        <input id="inp_img" name="img" type="hidden" value="">
                        <input type="hidden" name="token" id="token" value="<?php echo Token::generate(); ?>">
                        <input id="bt_upload" name="upload" class="btn btn-dark" type="submit" value="Upload">
                    </form>
                </div>
                <div class="side-container">
                    <div id="photos">
                        <?php 
                            $url = 'img/'. $user->data()->username.'/'.'snaps/';
                            $images = glob($url."*.png");
                            $images = array_reverse($images);
                            foreach($images as $image)
                            {
                                echo '<img src="'.$image.'">';
                            }
                        ?>
                    </div>
                </div>
                <script src="js/main.js"></script>
            <?php
                }
                else
                {
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