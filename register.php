<?php
/**
 * Created by PhpStorm.
 * User: nmabe
 * Date: 10/5/2018
 * Time: 12:49 AM
 */

require_once 'core/init.php';

if (Input::exists()) {
    if (Token::check(Input::get('token'))){
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users'
            ),
            'name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50,
            ),
            'gender' => array(
                'required' => true,
                'gender' => array('Male','Female') 
            ),
            'email' => array(
                'required' => true,
                'min' => 8
            ),
            'password' => array(
                'required' => true,
                'min' => 6,
                'strong' => true
            ),
            'password_again' => array(
                'required' => true,
                'match' => 'password'
            ),
        ));


        if ($validation->passed()) {
            $user = new User();
            $salt = Hash::salt("saltingsasfarassaltsgo!@#$%^&*()");
            $code = rand(12345, 56789);
            $fields = array(
                'username' => Input::get('username'),
                'gender' => (Input::get('gender') === 'Male') ? 1 : 0,
                'password' => Hash::make(Input::get('password'), $salt),
                'salt' => $salt,
                'email' => Input::get('email'),
                'name' => Input::get('name'),
                'joined' => escape(date('Y-m-d H:i:s')),
                'notify' => 1,
                'group' => 1,
                'code' => $code,
                'active' => 0
            );
            try{
                $user->create($fields);
                try{
                    $sent = $validate->Verifymail($code, Input::get('username'), Input::get('email'));
                    if ($sent)
                    {
                        Session::flash('verify', "Account Registered Refer to your email for confimation ...");
                        Redirect::to('verify.php');
                    }
                    else
                    {
                        echo "emial not sent lets see why<br>";
                    }
                }catch(Exception $e){
                    die($e->getMessage());
                }
            }catch (Exception $e){
                die($e->getMessage());
            }

        } else {
            foreach ($validation->error() as $error) {
                echo $error . "<br>";
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
    <title>Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
    <link rel="shortcut icon" href="img/favicon.ico" />
</head>
<body style="background-image:url('img/bg/background-2719572_960_720.jpg')">
<header>
    <a href="index.php" >Camagru</a>
    <h2>Gallery</h2>
</header>
    <section>
            <nav>
                <div class="vertical-menu">
                </div>
            </nav>
            <article>
                <form action="#" method="post">
                    <div class="field">
                        <label for="username">Enter Username</label>
                        <input name="username" type="text" id="username" autocomplete="false">
                    </div>
                    <div class="field">
                        <label for="name">Enter Name and Surname</label>
                        <input name="name" type="text" id="name" value="" autocomplete="false">
                    </div>
                    <div class="field">
                        <label for="name">Gender</label>
                        <select name="gender" id="gender" required="true">
                            <option selected="selected">select your gender</option>
                            <option>Female</option>
                            <option>Male</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="email">Enter Email Address</label>
                        <input name="email" type="email" id="email" value="" autocomplete="false">
                    </div>
                    <div class="field">
                        <label for="password">Enter Password</label>
                        <input name="password" type="password" id="password">
                    </div>
                    <div class="field">
                        <label for="password_again">Confirm Password</label>
                        <input name="password_again" type="password" id="password_again" >
                    </div>
                    <div>
                        <input type="hidden" name="token" id="token" value="<?php echo  Token::generate();?>">
                        <input type="submit" value="Create Account">
                    </div>
                </form>
        </article>
        </section>
    <footer>
        <p>&copy; 2018 <a href="http://www.wethinkcode.co.za/students?filter=false">@nmabe</a> Camagru.com </p>
    </footer>
</body>
</html>