<?php
require_once("../class/Administration.php");
$admin = new Administration();
include_once("../inc/head.html");

?>
<title>Forgot Password | GEMIS</title>
<link rel="stylesheet" type="text/css" href="../css/loginstyle.css">
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-center mt-5">
            <div class="user_card h-50 mt-5">
                <div class="text-center mt-5">
                    <h4>Forgot your password?</h4>
                    <p class="p-2">Enter your email address and we'll send you a link to reset your password.</p>
                </div>
                <div class="d-flex justify-content-center form_container_fp">
                    <form action="../admin/action.php" method="POST" style="width: 320px">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">@</span>
                            </div>
                            <input type="hidden" name='action' value="forgotPassword">
                            <input type="text" name="email" class="form-control" placeholder="Enter Email">
                        </div>
                        <div class="d-flex justify-content-center mt-3 mb-5 login_container">
                            <input type="submit" name="forgotPassword" class="btn login_btn" value="Recover Password">
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