<?php
include_once("class/config.php");
include_once("inc/head.html");
$con = (new dbConfig())->connect();
$row_temp = mysqli_query($con, "SELECT can_enroll FROM schoolyear WHERE status = '1';");
if (mysqli_num_rows($row_temp) == 0) {
    $enroll = 0;
} else {
    $sy = mysqli_fetch_row($row_temp);
    $enroll = $sy[0];
}
?>
<title>Login | GEMIS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/loginstyle.css">
</head>

<body>
    <div class="container">
        <div class="login_name_wrapper mb-5">
            <div class="d-flex justify-content-center">Welcome!</div>
        </div>
        <div class="d-flex justify-content-center h-50">
            <div class="user_card">
                <div class="d-flex justify-content-center">
                    <div class="login_logo_container"> <img src="assets/school_logo.jpg" class="login_logo" alt="Logo" style="width: 100%; height: 100%;">  </div>
                </div>
                <?php if (isset($_GET['Empty'])) { ?>
                    <div class="alert-light text-danger text-center py-3"><?php echo $_GET['Empty'] ?></div>
                <?php } ?>

                <?php if (isset($_GET['Invalid'])) { ?>
                    <div class="alert-light text-danger text-center py-3"><?php echo $_GET['Invalid'] ?> </div>
                <?php  } ?>
                <div class="d-flex justify-content-center form_container">
                    <form action="inc/authenticate.php" method="post" style="width: 320px">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg" width="16"  fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                    </svg></span>
                            </div>
                            <input type="text" name="UName" class="form-control input_user" placeholder="Enter User ID" value="30201012">
                        </div>
                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg" width="16"  fill="currentColor" class="bi bi-key-fill" viewBox="0 0 16 16">
                                        <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                                    </svg></span>
                            </div>
                            <input type="password" name="Password" class="form-control input_pass" placeholder="Enter Password" value="FELICIANO12">
                        </div>

                        <div class="d-flex justify-content-center mt-3 login_container">
                            <input type="submit" name="loginBtn" class="btn login_btn" value="Login"></input>
                        </div>
                        <div class="mt-4">
                            <div class="d-flex justify-content-center links">
                                <a href="passwordReset/forgotPassword.php" style="color:darkgrey; font-size:small;">Forgot your password?</a>
                                <br>
                            </div>
                            <div class="mt-5 d-flex justify-content-center ">
                                <?php if ($enroll == 1) { ?>
                                    <!-- <div class="d-flex justify-content-end mt-5"> -->
                                    <small class='text-secondary'>New student? </small>
                                    <small><a href="student/enrollment_form.php" class="link ms-1" style="color: #5dbb63;"> Enroll Now!</a></small>
                                    <!-- <div class="col-auto"> -->
                                    <!-- </div> -->
                                    <!-- </div> -->
                                <?php } ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>