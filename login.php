<?php
include_once("class/config.php"); 
include_once("inc/head.html");
$con = (new dbConfig())->connect();
$row_temp = mysqli_query($con, "SELECT can_enroll FROM schoolyear ORDER BY sy_id DESC LIMIT 1;");
$sy = mysqli_fetch_row($row_temp);
$enroll = $sy[0];
?>
<title>Login | GEMIS</title>
<link rel="stylesheet" type= "text/css" href="css/loginstyle.css">
</head>
    <body> 
        <div class="box">
            <img src="assets/logoSc.png" class="logo">
            <h1>Welcome!</h1>

            <?php if(isset($_GET['Empty'])){ ?>
           <div class="alert-light text-danger text-center py-3"><?php echo $_GET['Empty']?></div>  
         <?php } ?>

        <?php if(isset($_GET['Invalid'])){ ?>
           <div class="alert-light text-danger text-center py-3"><?php echo $_GET['Invalid']?> </div>  
        <?php  } ?>
            <form action = "inc/authenticate.php" method="post">
              <input type="text" name="UName" class="form-control" value="30201012" placeholder="Enter ID">
              <input type="password" name="Password" class="form-control" value="FELICIANO12" placeholder="Enter Password">
              <input type="submit" name="loginBtn" class="btn" value="Login">
              <?php if ($enroll == 1) { ?>
                    <!-- <div class="d-flex justify-content-end mt-5"> -->
                        <a href="enrollment.php" class="link">Enroll Now!</a><br>
                        <!-- <div class="col-auto"> -->
                        <!-- </div> -->
                    <!-- </div> -->
              <?php } ?>
              <a href="passwordReset/forgotPassword.php">Forgot Password?</a><br>
            </form>
        </div>
    </body>
</html>