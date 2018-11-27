<?php
class Gallery{

    private     $_user = null,
                $_db = null,
                $_passed = false;

    public function __construct($username = null)
    {
        $this->_db = DB::getInstance();
        $this->_user = new User();
    }

    private function passed()
    {
        return ($this->_passed);
    }

    public function get()
    {
        $image =  $this->_db->get('images', array('user_id', '=', $this->_user->data()->id));
        $booth = array();
        if ($image->count())
        {
            $bin_data = $image->first()->img;
            $bin_data = unserialize($bin_data);
            $i = 0;
            foreach ($bin_data as $item)
            {
                $booth[] = array(
                    'index' => $i,
                    'name' => $item['name'],
                    'uploaded' => $item['uploaded'],
                    'path' => $item['path'],
                    'likes' => $item['likes'],
                    'comments' => $item['comments']
                );
                $i++;
            }
            return ($booth);
        }
        return(array());
    }

    public function getAll()
    {
        $image =  $this->_db->query('SELECT * FROM `images`');
        $booth = array();
        if ($image->count())
        {
            $t = 0;
            while ($t < $image->count())
            {
                $bin_data = $image->result()[$t]->img;
                $bin_data = unserialize($bin_data);
                $i = 0;
                foreach ($bin_data as $item)
                {
                    $booth[] = array(
                        'index' => $i,
                        'name' => $item['name'],
                        'uploaded' => $item['uploaded'],
                        'path' => $item['path'],
                        'likes' => $item['likes'],
                        'comments' => $item['comments']
                    );
                    $i++;
                }
                $t++;
            }
            return ($booth);
        }
        return(array());
    }

    public function delete($name)
    {
        $image = $this->_db->get('images', array('user_id', '=',$this->_user->data()->id));
        if($image->count())
        {
            $bin_data = $image->first()->img;
            $bin_data = unserialize($bin_data);
            $max = count($bin_data);
            $i = 0;
            $path = NULL;
            $new_bin = array();
            foreach ($bin_data as $item)
            {
                if($item['name'] !== $name)
                    $new_bin[] = $item;
                else{
                    $path = $item['path'];
                }
            }
            $bin_data = serialize($new_bin);
            if ($path)
            {
                try{
                    $this->_db->update('images', $image->first()->id, array(
                    'img' => $bin_data
                    ));
                    unlink($path);
                    return (true);
                } catch (Exception $e){
                    die($e->getMessage());
                }
            }
        }
        return (false);
    }

    public function comment($name, $comment)
    {
        $this->_passed = false;
        if(!empty($comment))
        {
            $image = $this->_db->get('images', array('user_id', '=',$this->_user->data()->id));
            if($image->count())
            {
                $bin_data = $image->first()->img;
                $bin_data = unserialize($bin_data);
                $new_bin = array();
                foreach ($bin_data as $item)
                {
                    if($item['name'] == $name){
                        $item['comments'][] = array(
                            $this->_user->data()->username => $comment
                        );
                    }
                    $new_bin[] = $item;
                }
                $bin_data = serialize($new_bin);
                try{
                    $this->_db->update('images', $image->first()->id, array(
                        'img' => $bin_data
                    ));
                    $this->_passed = true;
                    if ($this->_user->data()->notify)
                    {
                        $this->commentMail($comment, $this->_user->data()->username, $this->_user->data()->email);
                    }
                    return (true);
                } catch (Exception $e){
                    die($e->getMessage());
                }
            }
        }
        return (false);
    }

    public function like($name)
    {
        $image = $this->_db->get('images', array('user_id', '=',$this->_user->data()->id));
        if($image->count())
        {
            $bin_data = $image->first()->img;
            $bin_data = unserialize($bin_data);
            $new_bin = array();
            foreach ($bin_data as $item)
            {
                if($item['name'] == $name){
                    $item['likes']++;
                }
                $new_bin[] = $item;
            }
            $bin_data = serialize($new_bin);
            try{
                $this->_db->update('images', $image->first()->id, array(
                    'img' => $bin_data
                ));
                    return (true);
            } catch (Exception $e){
                die($e->getMessage());
            }
        }
        return (false);
    }

    public function commentMail($comment, $username, $email)
    {
        $user = new User($username);
        if ($this->passed()) {
            $body = "You've received this email because a comment was sent on your Camagru account.<br>
                Please follow this link to view Camagru online for free:";
            $to = escape($email);
            $subject = "{$username} commented on your Camagru account";
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
                    max-width: min-content;
                    height: 100%;
                }
    </style>
</head>
<body>
    <div class="header">
        <h1>You Have a Comment On Your Camagru Account</h1>
    </div>
    
    <p id="user"> $username </p>
    <p id="user"> Your comment: $comment</p>
    <div class="container">
        <p> $body</p>
        <p><a href="http://127.0.0.1:8100/camagru/gallery.php">View Comment</a></p>
    </div>
    <div class="footer">
       <p>Yours truly,<br>
       nmabe &reg Camagru 2018 &copy;<br>
       <a href="http://127.0.0.1:8100/camagru/index.php">https://www.camagru.com<br></a>
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
}

