<?php
require_once 'core/init.php';

$user = new User();


if (Input::exists())
{
    if (Token::check(Input::get('token')))
    {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array('required' => true),
            'password' => array('required' => true)
        ));

        if ($validation->passed()){
            $user = new User();

            $remember = (Input::get('remember')) ? true : false;
            $login = $user->login(Input::get('username'), Input::get('password'), $remember);
            if ($login) {
                Redirect::to('index.php');
            }
            else {
                echo  "Login Failed<br>";
            }
        }
        else{
            foreach ($validation->error() as $value)
            {
                echo "{$value} <br>";
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
    <title>Profile<?php echo (($user->isLoggedIn()) ? ': '.$user->data()->username : ''); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
    <link rel="shortcut icon" href="img/favicon.ico" />
</head>
<body>
<header>
    <a href="index.php" >Camagru</a>
    <h2>Login</h2>
</header>
    <section>
        <nav>
            <div class="vertical-menu">
            </div>
        </nav>        
        <article>
            <?php 
                if ($user->IsLoggedIn())
                {
                    ?>
                
                    <p> Hello <a href="profile.php"> <?php echo escape($user->data()->username) ?></a>!</p>
                    <ul>
                        <li>
                            <a href="logout.php">Log out</a>
                        </li>
                    </ul>
                    <?php
                }
                else
                {
                    echo '<p>Need Account ?  <a href="register.php">register</a></p>';
                    ?>
            <form action="login.php" method="post">
                <div>
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" autocomplete="false">
                </div>
                <div>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password">
                </div>
                <div>
                    <label for="remember">
                        <input name="remember" type="checkbox" id="remember"> Remember me
                    </label>
                </div>
                <div>
                    <input type="hidden" name="token" value="<?php echo Token::generate() ?>">
                    <input type="submit" value="login">
                </div>
            </form>
            <?php
            }
            ?>
        </article>
    </section>
    <footer>
        <p>&copy; 2018 <a href="http://www.wethinkcode.co.za/students?filter=false">@nmabe</a> Camagru.com </p>
    </footer>
</body>
</html>
