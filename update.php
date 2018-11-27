<?php
/**
 * Created by PhpStorm.
 * User: nmabe
 * Date: 10/5/2018
 * Time: 12:49 AM
 */

require_once 'core/init.php';

$user = new  User();

if (!$user->isLoggedIn())
{
    Redirect::to('login.php');
}


?>
<?php

if (Input::exists())
{
    if (Token::check(Input::get('token')))
    {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'min' => 2,
                'max' => 20,
                'unique' => 'users'),
            'name' => array(
                'min' => 2,
                'max' => 50),
            'email' => array(
                'min' => 8)
        ));

        if ($validation->passed()) {
            try {

                $notify = ($user->data()->notify == 1) ? 0 : 1;
                if (!empty(Input::get(('name'))) || Input::get('notify') == '1' || !empty(Input::get(('email'))) || !empty(Input::get(('username'))))
                {
                    if (!empty(Input::get('username')))
                    {
                        $user->update(array(
                            'username' => Input::get('username')
                        ));
                    }
                    if (!empty(Input::get('email')))
                    {
                        $user->update(array(
                            'email' => Input::get('email')
                        ));
                    }
                    if (empty(Input::get(('name'))) && Input::get('notify') == '1')
                    {
                        $user->update(array(
                            'notify' => $notify
                        ));
                    }
                    else if (!empty(Input::get(('name'))) && Input::get('notify') !== '1'){
                        $user->update(array(
                            'name' => Input::get('name'),
                        ));
                    }
                    else if (!empty(Input::get(('name'))) && Input::get('notify') === '1')
                    {
                        $user->update(array(
                            'name' => Input::get('name'),
                            'notify' => $notify
                        ));
                    }
                    Session::flash('home', 'Profile updated successfully');
                    Redirect::to('index.php');
                }
            } catch (Exception $e)
            {
                die($e->getMessage());
            }
        }
        else
        {
            foreach ($validation->error() as $value)
                echo "{$value}<br>";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Update Profile<?php echo (($user->isLoggedIn()) ? ': '.$user->data()->username : ''); ?></title>
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
            if ($user->isLoggedIn())
            {
                ?>
                <p> Hello <a href="profile.php"> <?php echo escape($user->data()->username) ?></a>!</p>
    <ul>
        <li>
            <a href="changepassword.php">Change Password</a>
        </li>
        <li>
            <a href="logout.php">Log out</a>
        </li>
    </ul>
                <div>
            <form action="update.php" method="post">
                <div>
                    <label for="name"> Name
                    <input type="text" name="name" id="name" autocomplete="false">
                    </label>
                </div>
                <div>
                    <label for="name"> Username
                    <input type="text" name="username" id="username" autocomplete="false">
                    </label>
                </div>
                <div>
                    <label for="name"> Email
                    <input type="email" name="email" id="email" autocomplete="false">
                    </label>
                </div>
                <div>
                    <label for="name"> Stop recieving Notification Mail
                    <input type="checkbox" name="notify" id="notify" value="1">
                    </label>
                </div>
                <div>
                    <input type="hidden" name="token" id="token" value="<?php echo Token::generate()?>">
                    <input type="submit" value="update">
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