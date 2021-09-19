<?php 
require_once("../class/Administration.php"); 
include_once("inc/head.html"); ?>
<title>Login | GEMIS</title>
<link rel="stylesheet" type= "text/css" href="css/loginstyle.css">
</head>
    <body> 
        <div class="box">
            <img src="assets/logoSc.png" class="logo">
            <h1>Welcome!</h1>
            <form>
                <input type="text" name="" class="form-control" placeholder="Enter ID">
                <input type="password" name="" class="form-control" placeholder="Enter Password">
                <input type="submit" name="" class="btn" value="Login">
                <a href="forgotPassword.php">Forgot Password?</a><br>
            </form>
        </div>
    </body>
    <?php include_once ("inc/footer.html"); ?>
</html>