<?php
/**
 * Created by PhpStorm.
 * User: nmabe
 * Date: 10/17/2018
 * Time: 12:19 AM
 */

class Page
{
    protected $title = null,
        $head = null,
        $body = null,
        $user = null;

    public function __construct($user = null,$title = null)
    {
        $this->title = "<title>" . $title . "</title>";
        $this->user = new User();
    }

    public function getBody()
    {
        return($this->body);
    }

    public function getHead()
    {
        return ($this->head);
    }

    public function setTitle($title)
    {
        $this->title = "<title>".$title."</title>";
    }

    public function contentCat($content)
    {
        $this->body .= $content;
    }

    public function headCat($head)
    {
        $this->head .= $head;
    }

    public function addScript($script)
    {
        $this->head .= "<script>". $script."</script>";
    }

    public function addScriptUrl($url)
    {
        $this->head .= '<script src="' .$url. '"></script>';
    }

    public function addStyleUrl($style)
    {
        $this->head .= '<link rel="stylesheet" href="' .$style. '">';
    }

    

    public function setPage()
    {
        $pageName = basename($_SERVER['PHP_SELF']);
        $home = "<a class='navbar-item' href='index.php'>Home</a>";
        $gallery = "<a class='navbar-item' href='gallery.php'>Gallery</a>";


        if ($pageName === 'index.php')
            $home = str_replace("navbar-item", "navbar-item is-active", $home);
        else if ($pageName === 'gallery.php')
            $gallery = str_replace("navbar-item", "navbar-item is-active", $gallery);
        $page = <<<HTML
    <!DOCTYPE HTML>
    <html>
        <head>
            <meta charset="utf-8">
            <link rel="stylesheet" href="css/style.css">
            <script rel="script" src="js/common.js"></script>
            <script rel="script" src="js/request.js"></script>
            $this->head
            $this->title
         </head>
         <body>
            <section class="top">
                <div class="top head">
                    <nav class="navbar is-fixed-top">
                        <div class="navbar brand">
                            <a class="navbar-item" href="index.php">
                                <img src="img/logo.png" alt="camagru logo" width="30" height="35">
                            </a> 
                            
                            <div class="navbar-burg burg" id="burg-menu">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                        <div class="navbar-menu">
                            <div class="navbar-start">
HTML;
        $page .= <<<HTML
                                $home
                                $gallery    
                            </div>
                            
                            <div class="navbar-end">
                                <div class="navbar-item">
                                    <div class="field is-group">
                                        
HTML;
        if ($this->user) {
            $page .= <<<HTML
                                
                                        <p class="control">
                                            <a class="button button is-dark" id="login" href="login.php">
                                                <span>Login</span>
                                            </a>
                                        </p>
                                         <p class="control">
                                            <a class="button button is-dark" id="SignUp" href="register.php">
                                                <span>Sign Up</span>
                                            </a>
                                        </p>
HTML;

        } else {
            $page .= <<< HTML
                                        <p class="control">
                                            <a class="button button is-dark" id="photoedit" href="upload.php">
                                                <span>Photo</span>
                                            </a>
                                        </p>
                                         <p class="control">
                                            <a class="button button is-dark" id="profile" href="../profile.php?user={User::data()->username}">
                                                <span>Profile</span>
                                            </a>
                                        </p>
                                        <p class="control">
                                            <a class="button button is-dark" id="Account" href="account.php">
                                                <span>Account</span>
                                            </a>
                                        </p>
                                         <p class="control">
                                            <a class="button button is-dark" id="logout" href="logout.php">
                                                <span>Log out</span>
                                            </a>
                                        </p>
HTML;
        }

        $page .= <<<HTML
                            
                            
                                    </div>
                                 </div>                                
                            </div>
                         </div>
                    </nav>                
                </div>
                <div class="model" id="login-model">
                    <div class="model-background"></div>
                        <div class="model-form">
                            <header class="model-form-head">
                                <p class="model-form-head-title">Login</p>

                            </header>
                            <section class="model-form-body">
                                <form action="connect.php" method="post" id="login-form">
                                    <div>
                                        <label  class="label" for="username">Username</label>
                                        <input type="text" name="username" id="username" autocomplete="false">
                                    </div>
                                    <div>
                                        <label class="label" for="password">Password</label>
                                        <input type="password" name="password" id="password">
                                    </div>
                                    <div>
                                        <label class="label" for="remember">
                                            <input name="remember" type="checkbox" id="remember"> Remember me
                                        </label>
                                    </div>
                                    <br>
                                        <a class="link" href="changepassword.php">Forgot Password ?</a>
                                </form>
                            </section>
                                <footer>
                                  <div>
                                       <input type="hidden" name="token" value="<?php echo Token::generate() ?>">
                                       <input class="button button is-dark" type="submit" form="login-form" name="login">
                                       <input class="button button is-dark" type="button" value="cancel" name="cancel">
                                   </div>
                                </footer>
                         </div>
                    </div>
                </div>
                <div class="model" id="sign-up-model">
                    <div class="model-background"></div>
                    <div class="model-form">
                        <header class="model-form-head">
                            <p class="model-form-head-title">Login</p>
                            <button class="delete login-cancel" aria-label="close"></button>
                        </header>
                        <section class="model-form-body">
                            <form id="signup-form" action="register.php" method="post">
                                <div class="field">
                                    <label class="label" for="username">Enter Username</label>
                                    <input class="input" name="username" type="text" id="username" autocomplete="false">
                                </div>
                                <div class="field">
                                    <label class="label" for="name">Enter Name and Surname</label>
                                    <input class="input" name="name" type="text" id="name" value="" autocomplete="false">
                                </div>
                                <div class="field">
                                    <label class="label" for="email">Enter Email Address</label>
                                    <input name="email" type="email" id="email" value="" autocomplete="false">
                                </div>
                                <div class="field">
                                    <label class="label" for="password">Enter Password</label>
                                    <input class="input" name="password" type="password" id="password">
                                </div>
                                <div class="field">
                                    <label class="label" for="password_again">Confirm Password</label>
                                    <input class="input" name="password_again" type="password" id="password_again" >
                                </div>
                                <div>
                                    <input type="hidden" name="token" id="token" value="<?php echo  Token::generate();?>">
                                    <input  class="button button is-dark" type="submit" value="Create Account">
                                </div>
                            </form>
                        </section>
                        <footer>
                            <div>
                                <input type="hidden" name="token" value="<?php echo Token::generate() ?>">
                                <input class="button button is-dark" type="submit" form="signup-form" name="signup">
                                <input class="button button is-dark" type="button" value="cancel" name="cancel">
                            </div>
                         </footer>
                    </div>
                </div>
                <div id="notification"></div>
                
                $this->body
                
                <div class="bottom">
                    <footer class="bottom-footer">
                        <div class="container">
                            <div class="content text-centered">
                                <p><i class="bar bar-tag"> &nbsp;&copy;&nfr; 2018 Camagru &reg;</i></p>
                                <p class="copy"> Created by <a href="https://www.wethinkcode.com/students/nmabe">nmabe</p>
                            </div>
                        </div>
                    </footer>
                </div>
            </section>
                
HTML;

        return ($page);
     }

        
}
