<?php 
require_once("class/Administration.php"); 
$admin = new Administration();
include_once("inc/head.html"); 

?>
<title>Forgot Password | GEMIS</title>
<link rel="stylesheet" type= "text/css" href="css/loginstyle.css">
</head>
    <body> 
        <div class="box">
            <h1>Forgot your password?</h1>
            <form action="forgotPassword.php" method="POST">
                <input type="text" name="email" class="form-control" placeholder="Enter Email">
                <input type="submit" name="forgotPassword" class="btn" value="Recover Password">
            </form>
        </div>
        <!--CUSTOM JS-->
        <script src="../js/common-custom.js"></script>
        <script type="module" src="../js/admin/admin.js"></script>
    </body>
    <?php include_once ("inc/footer.html"); ?>
</html>