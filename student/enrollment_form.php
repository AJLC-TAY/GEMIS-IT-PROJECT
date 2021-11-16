<?php
session_start();
$_SESSION['id'] = rand();
$_SESSION['user_type'] = 'ST';
include_once("../class/Student.php");
$user = new StudentModule();
$row_temp = $user->query("SELECT sy_id, start_year, end_year, can_enroll FROM schoolyear WHERE status = '1';");
$sy = mysqli_fetch_row($row_temp);
$_SESSION['sy_id'] = $sy[0];
$_SESSION['school_year'] = $sy_desc = $sy[1] . " - " . $sy[2];
$enroll = $sy[3];
include_once("../inc/head.html");
?>

<title>Enrollment | GEMIS</title>
</head>
<!DOCTYPE html>
<body>
    <!-- SPINNER -->
    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <?php if ($enroll == 0) { ?>
        <div class="container" style="margin-top: 18%;">
            <div class="card h-auto bg-light mx-auto mt-4 p-4" style='width: 70%;'>
                <h1 class="text-center">Sorry, enrollment has ended.</h1>
            </div>
        </div>
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