<?php 
require_once("sessionHandling.php");
include_once("../inc/head.html"); 
?>
<title>Student | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
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
        <?php include_once('../inc/adminSidebar.php'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                        <?php 
                            $jsFilePath = "<script type='module' src='../js/admin/student.js'></script>";
                            if (isset($_GET['action']) && $_GET['action'] == 'assesTransferee') {
                                include_once("student/transfereeForm.php");
                                $jsFilePath = "<script src='../js/admin/transferee.js'></script>";
                            } else if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                include_once("student/studentForm.php");
                            } else if (isset($_GET['action']) && $_GET['action'] == 'export') {
                                include_once("student/export.php");
                            } else if (isset($_GET['action']) && $_GET['action'] == 'transfer'){
                                include_once("student/studentTransfer.php"); 
                                $jsFilePath = "<script type='module' src='../js/admin/transfer-student.js'></script>";
                            } else if (isset($_GET['id'])){
                                include_once("student/studentProfile.php"); 
                            } else {
                                include_once("student/studentList.php"); 
                                $jsFilePath = "<script type='module' src='../js/admin/student-list.js'></script>";
                            }
                        ?>
                        </div>
                    </div>
                </div>
                <!-- FOOTER START -->
                <?php include_once("../inc/footer.html"); ?>
                <!-- FOOTER END -->
        </section>
    </section>
    <!-- MAIN CONTENT END -->
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->
    <script src='../assets/js/bootstrap-table.min.js'></script>
    <script src='../assets/js/bootstrap-table-en-US.min.js'></script>
    <script src='../assets/js/bootstrap.bundle.min.js'></script>

    <!-- VALIDATION -->
    <script src="../js/validation/jquery.validate.min.js"></script>
    <script src="../js/validation/additional-methods.min.js"></script>
    <script src="../js/validation/validation.js"></script>

    <script type="text/javascript" src="../js/common-custom.js"></script>
    <?php echo $jsFilePath; ?>;
</body>

</html>
