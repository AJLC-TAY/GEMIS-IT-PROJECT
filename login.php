<?php
session_start();
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
<link rel="stylesheet" type="text/css" href="css/loginstyle.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="login_name_wrapper mb-5">
            <div class="d-flex justify-content-center">Welcome!</div>
        </div>
        <div class="d-flex justify-content-center h-50">
            <div class="user_card">
                <div class="d-flex justify-content-center">
                    <div class="login_logo_container"> <img src="assets/school_logo.jpg" class="login_logo" alt="Logo" style="width: 100%; height: 100%;"> </div>
                </div>
                <div class="d-flex justify-content-center form_container">
                    <form id="login-form" action="inc/authenticate.php" method="post" style="width: 320px">
                        <div class="form-group mb-3">
                            <label for="userID" class="form-label text-secondary"><small>UID</small></label>
                            <input id="userID" type="text" name="UName" class="form-control input_user" placeholder="Enter User ID" value="<?php echo $_GET['uid'] ?? ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label text-secondary"><small>Password</small></label>
                            <input id="password" type="password" name="password" class="form-control input_pass" placeholder="Enter Password" value="">
                            <p class="text-danger text-center"><?php echo $_GET['error'] ?? ''; ?> </p>
                        </div>
                        <div class="form-check d-flex justify-content-end">
                            <input class="form-check-input me-1" type="checkbox" onclick="myFunction()">
                            <label class="form-check-label" for="flexCheckDefault"><small>Show Password</small>
                            </label>
                        </div>
                        <div class="d-flex justify-content-center mt-3 login_container">
                            <input type="submit" name="loginBtn" class="btn login_btn" value="Login">
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
    <?php include_once("inc/footer.html"); ?>


    <script src="../js/validation/jquery.validate.min.js"></script>
    <script src="../js/validation/additional-methods.min.js"></script>
    <script>
        $(function() {
            $("#login-form").validate({
                rules: {
                    UName: {
                        required: true
                    },
                    password: {
                        required: true,
                        minlength: 8,
                    }
                },
                messages: {
                    UName: {
                        required: "<p>Please provide your User ID</p>",
                    },
                    password: {
                        required: "<p>Please provide your password</p>",
                        minlength: "<p>Please choose a password with at least 8 characters</p>"
                    }

                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
    <!-- PEEK PW -->
    <script>
        function myFunction() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
</body>

</html>