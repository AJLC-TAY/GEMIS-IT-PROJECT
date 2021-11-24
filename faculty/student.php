<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
if (!isset($_SESSION['sy_id'])) {
    header("Location: index.php");
}
?>
<title>Award | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
<link href='../css/report.css' rel='stylesheet'>
</head>
<body>
    <!-- SPINNER -->
    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/facultySidebar.php'); ?>
        <!--MAIN CONTENT -->
        <section id="main-content">
            <section class="wrapper ps-4">
                <div class="row">
                    <div class="row ps-3">
                        <?php
                        include_once("../admin/student/studentProfile.php"); 
                        ?>
                    </div>
                </div>
            </section>
            <!-- FOOTER -->
            <?php include_once("../inc/footer.html"); ?>
            <!-- FOOTER END -->
        </section>
    </section>
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->
</body>
<!-- VALIDATION -->


<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>
<script type="text/javascript" src="../js/common-custom.js"></script>
<script type='module' src='../js/admin/student.js'></script>

</html>