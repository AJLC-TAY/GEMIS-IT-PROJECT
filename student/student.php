<?php 
// require_once("sessionHandling.php");

include_once("../inc/head.html"); 
include_once('../inc/studentSideBar.php');
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
        <!-- MAIN CONTENT START -->
        <?php  ?>
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                        <?php 
                            if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                include_once("../admin/student/studentForm.php");
                                $jsFilePath = "<script type='text/javascript' src='../js/admin/student.js'></script>";
                            } else if (isset($_GET['action']) && $_GET['action'] == 'transfer'){
                                include_once("../admin/student/studentTransfer.php"); 
                                $jsFilePath = "<script type='module' src='../js/admin/transfer-student.js'></script>";
                            }else {
                                include_once("../admin/student/studentProfile.php"); 
                                $jsFilePath = "<script type='text/javascript' src='../js/admin/student.js'></script>";
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
    <script type="text/javascript" src="../js/common-custom.js"></script>
    <?php echo $jsFilePath; ?>;
    
</body>

</html>
