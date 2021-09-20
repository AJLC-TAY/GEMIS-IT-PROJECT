<?php 
//require_once("class/Administration.php"); 
//require_once("admin/action.php");
include_once("../inc/head.html"); 
?>
<title>New Password | GEMIS</title>
<link rel="stylesheet" type= "text/css" href="../css/loginstyle.css">
</head>
    <body> 
        <div class="box">
            <h1>Enter your new password</h1>
            <form action="../admin/action.php" method="POST">
                <input type="hidden" name='action' value="newPassword">
                <input type="hidden" name='token' value='<?php echo($_GET['token'])?>'>
                <input type="password" class="form-control" name="newPass" placeholder="Input password">
                <input type="password" class="form-control" name="newPassConf" placeholder="Confirm password">
                <input type="submit" name="newPassword" class="btn" value="Save"> 
            </form>
        </div>
    </body>
    <?php include_once ("../inc/footer.html"); ?>
</html>