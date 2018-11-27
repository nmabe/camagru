<?php

require_once 'core/init.php';

if (Session::exists('verify'))
{
    echo Session::flash('verify');
}

if (Input::get('verify'))
{
    if (Token::check(Input::get('token')))
    {
        $username = Input::get('username');
        $user = new User($username);
        if ($user->data())
        {
            $code = Input::get('code');
            $db_code = $user->data()->code;
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'username' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 50 
                ),
                'password' => array(
                    'required' => true,
                    'min' => 6
                ),
                'code' => array(
                    'requied' => true
            )));

            if ($validation && $db_code === $code){
                try{
                    $login = $user->login($username, Input::get('password'));
                    if ($login)
                    {
                        $id = $user->data()->id;
                        DB::getInstance()->update('users', $id, array(
                            'active' => 1
                        ));
                        Session::flash('home', "Your Account Has been Successfully Verified...");
                        Redirect::to('index.php');
                    }else{
                        echo "Login Failed Something went Wrong";
                    }
                }catch (Exception $e){
                    die($e->getMessage());
                }
            }
            else{
                echo "Incorrect Verification number: {$code}<br>";
                foreach ($validation->error() as $errors)
                    echo "{$errors}<br>";
            }
        }
        else{
            echo "User Not found";
        }
    }
}

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Verify Account</title>
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
        <div class="container" style="border: 1px solid black; padding: 5px;">
            <form action="verify.php" method="post">
                <div>
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" autocomplete="false">
                </div>
                <div>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password">
                </div>
                <div>
                    <label for="password">Verification Code</label>
                    <input type="text" name="code" id="code">
                </div>
                <div>
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                    <input type="submit" name="verify" value="verify">
                </div>
            </form>
        </div>
  
        </article>
    </section>
    <footer>
        <p>&copy; 2018 <a href="http://www.wethinkcode.co.za/students?filter=false">@nmabe</a> Camagru.com </p>
    </footer>
</body>
</html>