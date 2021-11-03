<?php
require_once("../class/Administration.php");
$admin = new Administration();
include_once("../inc/head.html");

?>
<title>New Password | GEMIS</title>
<link rel="stylesheet" type="text/css" href="../css/loginstyle.css">
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-center mt-5">
            <div class="user_card h-50 mt-5">
                <div class="text-center mt-5">
                    <h4>Enter your new password:</h4>
                </div>
                <div class="d-flex justify-content-center form_container">
                    <form action="../admin/action.php" method="POST" style="width: 320px">
                        <div class="input-group">
                            <input type="hidden" name='action' value="newPassword">
                            <input type="hidden" name='token' value='<?php echo ($_GET['token']) ?>'>
                            <input type="password" class="form-control" name="newPass" placeholder="Input password">
                        </div>
                        <small>Re-enter your new password: </small>
                            <br>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="newPassConf" placeholder="Confirm password">
                        </div>
                        <div class="d-flex justify-content-center mt-3 mb-5 login_container">
                            <input type="submit" name="newPassword" class="btn login_btn" value="Save">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--CUSTOM JS-->
        <script src="../js/common-custom.js"></script>
        <script type="module" src="../js/admin/admin.js"></script>
    </div>
</body>


</html>