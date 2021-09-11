<?php include_once("../inc/head.html"); ?>
<title>Enrollment | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>

<body>
    <!-- SPINNER START -->
    <div class="spinner-con">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/admin/sidebar.html'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <?php
                            $js = '';
                            if (isset($_GET['page'])) {
                                if ($_GET['page'] === 'enrollees') {
                                    require("enrollment/enrollmentList.php");
                                    $js = "<script type='module' src='../js/admin/enrollment-list.js'></script>";
                                }

                                if ($_GET['page'] === 'setup') {
                                    require("enrollment/setup.php");
                                }

                                if ($_GET['page'] === 'form') {
                                    require("enrollment/stepForm.php");
                                }

                                if ($_GET['page'] === 'report') {
                                    require("enrollment/previewReport.php");
                                    $js = "<script src='../js/admin/enrollment.js'></script>";
                                }
                            } else {
                                require("enrollment/enrollment.php");
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
    <!-- BOOTSTRAP TABLE JS -->
    <script src="../assets/js/bootstrap-table.min.js"></script>
    <script src="../assets/js/bootstrap-table-en-US.min.js"></script>
    <!--CUSTOM JS-->
    <script src="../js/common-custom.js"></script>
    <?php echo $js; ?>
</body>

</html>