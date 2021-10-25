<?php
session_start();
$_SESSION['id'] = rand();
$_SESSION['user_type'] = 'ST';
include_once("../class/Student.php"); 
$user = new StudentModule();
$row_temp = $user->query("SELECT start_year, end_year, can_enroll FROM schoolyear ORDER BY sy_id DESC LIMIT 1;");
$sy = mysqli_fetch_row($row_temp);
$_SESSION['school_year'] = $sy_desc = $sy[0]." - ".$sy[1];
$enroll = $sy[2];
include_once("../inc/head.html"); 
?>

<title>Enrollment | GEMIS</title>
</head>
    <body> 
        <!-- SPINNER -->
        <div id="main-spinner-con" class="spinner-con">
            <div id="main-spinner-border" class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <!-- SPINNER END -->
        <?php if ($enroll == 0) { ?>
        <h1>Sorry, enrollment has ended.</h1>
        <?php } else { ?>
            <div class="container w-75">
                <?php require_once("../admin/enrollment/enrollmentForm.php"); ?>
            </div>
        <?php }  ?>
        <?php include_once("../inc/footer.html"); ?>
        <script src="../js/common-custom.js"></script>
        <script src="../js/admin/enrollment.js"></script>
    </body>
</html>