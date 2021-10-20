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
        <?php include_once('../inc/facultySidebar.php'); ?>
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
                                    $js = "<script type='module' src='../js/faculty/enrollment-list.js'></script>";
                               
                                }

                                if ($_GET['page'] === 'form') {
                                    require("enrollment/enrollmentForm.php");
                                    $js = '<script type="text/javascript" src="../js/faculty/enrollment.js"></script>';
                                }

                                if ($_GET['page'] === 'admission') {
                                    require("enrollment/studentAdmission.php");
                                    $js = "<script type='module' src='../js/faculty/studentAdmission.js'></script>";
                                }
                            } else {
                                require("enrollment/enDashBoard.php");
                                $js = "<script src='../js/faculty/enrollment.js'></script>";
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
    <script src="../assets/js/bootstrap-table-auto-refresh.min.js"></script>
    <!--CUSTOM JS-->
    <script src="../js/common-custom.js"></script>
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
    <?php echo $js; ?>
    <!-- VALIDATION -->
    <script>
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        });
    </script>
</body>

</html>