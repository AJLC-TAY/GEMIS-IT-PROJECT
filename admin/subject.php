<?php 
require_once("sessionHandling.php");
include_once ("../inc/head.html"); 
echo (!isset($_GET["action"]) ? "<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>" : "");
?>
<title>PCNHS GEMIS</title>
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
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <div class="col-lg-12">
                    <div class="row mt ps-3">
                        <?php 
                        if (isset($_GET['page']) && $_GET['page'] === 'schedule') {
                            include("subject/subjectSchedule.php");
                        } else if (isset($_GET['action'])) {
                            include("subject/subjectForm.php");
                        } else if (isset($_GET['sub_code'])) {
                            include("subject/subjectView.php");
                        } else {
                            include("subject/subjectList.php");
                        }
                        ?>
                    </div>
                </div>
                <!--main content end-->
                <!--footer start-->
                <?php include_once ("../inc/footer.html");?>
                <!--footer end-->
            </section>
        </section>
    </section>
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->
    <!-- VALIDATION -->
    <script src="../js/validation/jquery.validate.min.js"></script>
    <script src="../js/validation/additional-methods.min.js"></script>
    <!-- JQUERY FOR BOOTSTRAP TABLE -->
    <script src='../assets/js/bootstrap-table.min.js'></script>
    <script src='../assets/js/bootstrap-table-en-US.min.js'></script>
    <script src="../js/common-custom.js"></script>
    <script type='module' src="../js/admin/subject.js"></script>
</body>

</html>