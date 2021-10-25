<?php
require_once("sessionHandling.php");
include_once("../inc/head.html"); ?>
<title>Enrollment | GEMIS</title>
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
                            $js = '';
                            $bootstrapJS = '<script src="../assets/js/bootstrap-table.min.js"></script>'
                                          .'<script src="../assets/js/bootstrap-table-en-US.min.js"></script>'
                                          .'<script src="../assets/js/bootstrap-table-auto-refresh.min.js"></script>';
                            if (isset($_GET['page'])) {
                                if ($_GET['page'] === 'enrollees') {
                                    require("enrollment/enrollmentList.php");
                                    $js = "<script type='module' src='../js/admin/enrollment-list.js'></script>";
                                }

                                if ($_GET['page'] === 'setup') {
                                    require("enrollment/enrollmentSetup.php");
                                    $js = "<script type='module' src='../js/admin/enroll-setup.js'></script>";
                                }

                                if ($_GET['page'] === 'form') {
                                    require("enrollment/enrollmentForm.php");
                                    $bootstrapJS = '';
                                    $js = '<script type="text/javascript" src="../js/admin/enrollment.js"></script>';
                                }

                                if ($_GET['page'] === 'report' && $_GET['type']  === 'pdf') {
                                    require("enrollment/enrollmentReportHTML.php");            
                                    $bootstrapJS = '';
                                    $js = "<script src='../js/admin/enrollment.js'></script>";
                                }
                                
                                if ($_GET['page'] === 'generateReport') {
                                    require("enrollment/previewReport.php");
                                    $bootstrapJS = '';
                                    $js = "<script src='../js/admin/enrollment.js'></script>";
                                }

                                if ($_GET['page'] === 'credential') {
                                    require("enrollment/enrollmentCredentials.php");
                                    $bootstrapJS = '';
                                    $js = "<script src='../js/admin/enrollment.js'></script>";
                                }
                            } else {
                                require("enrollment/enDashBoard.php");
                                $js = "<script type='module' src='../js/admin/enrollment-dash.js'></script>";
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
    <?php echo $bootstrapJS; ?>
    <!--CUSTOM JS-->
    <script src="../js/common-custom.js"></script>
    <!-- VALIDATION -->
    <script src="../js/validation/jquery.validate.min.js"></script>
    <script src="../js/validation/additional-methods.min.js"></script>
    <script src="../js/validation/validation.js"></script>
    <script>
        /** MOVED TO enrollment.js */
        // var forms = document.querySelectorAll('.needs-validation');
        // Array.prototype.slice.call(forms).forEach(function(form) {
        //     form.addEventListener('submit', function(event) {
        //         if (!form.checkValidity()) {
        //             event.preventDefault()
        //             event.stopPropagation();
        //         }

        //         form.classList.add('was-validated');
        //     }, false);
        // });
    </script>
    <?php echo $js; ?>
</body>

</html>