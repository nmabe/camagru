<?php
/**
 * Created by PhpStorm.
 * User: nmabe
 * Date: 10/5/2018
 * Time: 12:52 AM
 */

class Validate
{
    private $_passed = false,
        $_errors = array(),
        $_db = null,
        $_page;

    public function __construct()
    {
        $this->_db = DB::getInstance();
    }

    public function check($source, $items = array())
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rules_val) {
                $value = trim($source[$item]);
                $item = escape($item);
                if ($rule === "required" && empty($value)) {
                    $this->addError("{$item} is Required");
                } elseif (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $rules_val)
                                $this->addError("{$item} Must Be a Minimum of {$rules_val} Characters");
                            break;
                        case 'max':
                            if (strlen($value) > $rules_val)
                                $this->addError("{$item} Must Be a Maximum of {$rules_val} Characters");
                            break;
                        case 'match':
                            if ($value != $source[$rules_val]) {
                                $this->addError("{$rules_val} Must Match {$item}");
                            }
                            break;
                        case 'unique':
                            $check = $this->_db->get($rules_val, array($item, '=', $value));
                            if ($check->count())
                                $this->addError("{$item} Already Exists");
                            break;
                        case 'gender':
                            $ruler = $rules['gender'];
                            if ($ruler[0] !== $value && $ruler[1] !== $value)
                                $this->addError("{$item} Should be Male or Female");
                            break;
                        case 'strong':
                            if((!preg_match("#[0-9]+#", $value)) || (!preg_match("#[a-zA-Z]+#", $value)))
                                $this->addError("{$item} should Include atleast one Uppercase and one numbe");
                            break;
                    }
                }
            }
        }
        if (empty($this->_errors)) {
            $this->_passed = true;
        }
        return ($this);
    }

    private function addError($error)
    {
        $this->_errors[] = $error;
    }


    public function Verifymail($code, $username, $email)
    {
        $user = new User($username);
        if ($this->passed()) {
            $body = "You've received this email because your email address was used for registering/updating your Camagru account.<br>
                Please follow this link to confirm your decision and start using Camagru online for free:";
            $to = escape($email);
            $subject = "Activate your Camagru account";
            $from = "nmabe@student.wethinkcode.co.za";
            $page = <<<HTML
    <head>
        <style>
            .header{
                        background-color: black;
                        color: white;
                        padding: 5px;
                        text-align: center;
                    }

                    #user{
                        font-size: 21px;
                        text-align: left;
                        color: #ee66ee;
                    }

                    .container {
                        width: 350px;
                        padding: 5px;
                        float: left;
                    }

                    .footer {
                        background-color: black;
                        color: white;
                        clear: both;
                        text-align: -moz-left;
                        padding-bottom: 5px;
                    }

                    body{
                        border: black outset 4px;
                        border-bottom-left-radius: 45px;
                        border-top-color: #ee66ee;
                        border-right-color: #ee66ee;
                        height: 10%;
                        max-width: min-content;
                    }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>Verify Your Camagru Account</h1>
        </div>
        
        <p id="user"> $username </p>
        <p id="user"> Your special verification Code: $code</p>
        <div class="container">
            <p> $body</p>
            <p><a href="http://127.0.0.1:8100/camagru/verify.php?username={$username}&code={$code}.">Confirm Account</a></p>
        </div>
        <div class="footer">
           <p>Yours truly,<br>
           nmabe &reg Camagru 2018 &copy;<br>
           <a href="index.php">https://www.camagru.com<br></a>
           <a href="nmabe@student.wethinkcode.co.za">contact: infor@camagru.com<br></a>
           Share with care</p>
        </div>
    </body>
HTML;
            $headers = "From:" . $from. "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $mailed = mail($to, $subject, $page, $headers);
            if ($mailed)
                return (true);
            else
                return (false);
        }
        return (false);
    }

    public function error()
    {
        return ($this->_errors);
    }

    public function passed()
    {
        return ($this->_passed);
    }

    
}