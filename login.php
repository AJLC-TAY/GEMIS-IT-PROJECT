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
              <input type="text" name="UName" class="form-control" placeholder="Enter ID">
              <input type="password" name="Password" class="form-control" placeholder="Enter Password">
              <input type="submit" name="loginBtn" class="btn" value="Login">
              <a href="passwordReset/forgotPassword.php">Forgot Password?</a><br>
            </form>
        </div>
    </body>
</html>