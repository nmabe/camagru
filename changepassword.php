<?php
/**
 * Created by PhpStorm.
 * User: nmabe
 * Date: 10/5/2018
 * Time: 12:49 AM
 */

require_once 'core/init.php';

$user = new  User();

?>
<?php

    if (Input::exists())
    {
        if (Token::check(Input::get('token')))
        {
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'old_password' => array(
                    'required' => true,
                    'min' => 6,
                ),
                'new_password' => array(
                    'required' => true,
                    'mix' => 6
                ),
                'new_password_again' => array(
                    'required' => true,
                    'min' => 6,
                    'matches' => 'new_password'
                )
            ));

            if ($validation->passed())
            {
                if ($user->data()->password !== Hash::make(Input::get('old_password'), $user->data()->salt))
                {
                     print("Incorrect old Password !!!<br><br>");
                }
                else {
                    $salt = Hash::salt('!randomize)these&32#characters$.');
                    echo "$salt<br>";

                    $user->update(array(
                        'salt' => $salt,
                        'password' => Hash::make(Input::get('new_password'),$salt)
                    ));

                    Session::flash('home', 'Password was successfully changed');
                    Redirect::to('index.php');
                }
            }
            else
            {
                foreach ($validation->error() as $errors)
                {
                    echo "{$errors}<br>";
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
    <title>Cahnge Password<?php echo (($user->isLoggedIn()) ? ': '.$user->data()->username : ''); ?></title>
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
                    <a href="upload.php">Upload</a>
                    <a href="gallery.php">Gallery</a>
                    <a href="snap.php">Snap a Pic</a>
                    <a href="#" class="active" >Update Profile</a>
                    <a href="logout.php">Logout</a>
                </div>
            </nav>
        <article>
        <?php

if (!$user->isLoggedIn())
{
    Redirect::to('index.php');
}

if ($user->IsLoggedIn())
{
    ?>

    <p> Hello <a href="profile.php"> <?php echo escape($user->data()->username) ?></a>!</p>
    <ul>
        <li>
            <a href="logout.php">Log out</a>
        </li>
    </ul>
    <div>
            <form action="changepassword.php" method="post">
                <div>
                    <label for="old_password"> Old Password
                        <input type="password" name="old_password" id="old_password" autocomplete="false">
                    </label>
                </div>
                <div>
                    <label for="new_password"> New Password
                        <input type="password" name="new_password" id="new_password" autocomplete="false">
                    </label>
                </div>
                <div>
                    <label for="new_password_again"> Confirm New Password
                        <input type="password" name="new_password_again" id="new_password_again" autocomplete="false">
                    </label>
                </div>
                <div>
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                    <input type="submit" value="Change">
                </div>
            </form>
        </div>
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