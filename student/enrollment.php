<?php
require_once("../inc/sessionHandling.php");
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
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <?php
                            $bootstrapJS = '<script src="../assets/js/bootstrap-table.min.js"></script>'
                                . '<script src="../assets/js/bootstrap-table-en-US.min.js"></script>'
                                . '<script src="../assets/js/bootstrap-table-auto-refresh.min.js"></script>';

                            require("../admin/enrollment/enrollmentForm.php");
                            $bootstrapJS = '';
                            $js = '<script type="text/javascript" src="../js/admin/enrollment.js"></script>';

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