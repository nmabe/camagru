<?php

$username = "Nkush";
$comment = "Hey there lovely picture woor!";
$email = 'nmabe@gmail.com';
$body = "You have a new comment";
$to = $email;
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
                    height: 55%;
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
        <p><a href="gallery.php">View Comment</a></p>
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

echo $page;
?>
