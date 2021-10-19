<?php 
require_once("../inc/sessionHandling.php");
include_once("../inc/head.html"); ?>
<title>Section | GEMIS</title>
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
        <?php include_once('../inc/admin/sidebar.php'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <?php 
                                $isViewPage = TRUE;
                                $js = "<script type='module' src='../js/admin/section.js'></script>";
                                if (isset($_GET["sec_code"])) {
                                    include_once("section/sectionView.php");
                                } else if (isset($_GET['page']) && $_GET['page'] == 'sub_classes') {
                                    include_once("section/subjectClasses.php");
                                    $js = "<script type='module' src='../js/admin/subject-class.js'></script>";
                                } else {
                                    $isViewPage = FALSE;
                                    include_once("section/sectionList.php");
                                }
                            ?>

                        </div>
                    </div>
                </div>
            </section>
            <!-- MAIN CONTENT END-->
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
<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>
<script type="text/javascript" src="../js/common-custom.js"></script>
<?php echo $js; ?>

</html>