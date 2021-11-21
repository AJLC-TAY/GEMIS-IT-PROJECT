<?php 
require_once("sessionHandling.php");
include_once("../inc/head.html"); ?>
<title>Award Document | GEMIS</title>
<script src="../assets/js/html2pdf.bundle.min.js"></script>
<link rel="stylesheet" href="../css/report.css">
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
                        if (isset($_GET['type'])) {
                            switch ($_GET['type']) {
                                case 'ae': # ae for academic excellence
                                    require_once("award/academicExcellence.php");
                                    break;
                                case 'pa': # pa for perfect attendance
                                    require_once("award/perfectAttendance.php");
                                    break;
                                case 'ca': # ca for conduct award
                                    require_once("award/conduct.php");
                                    break;
                                case "ga":
                                    require_once ("award/awardGeneric.php");
                                    break;
                            }
                        } else {
                            require_once("award/parameters.php");

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
    <script>
        // function generatePDF(filename) {
        //     // hide horizontal line
        //     // $(".doc hr").addClass('d-none');
        //     const template = document.querySelector(".template");
        //     var opt = {
        //         margin: [0.5, 0, 0.5, 0],
        //         // margin: 0,
        //         filename: filename + '.pdf',
        //         image: {
        //             type: 'jpeg',
        //             quality: 0.98
        //         },
        //         html2canvas: {
        //             scale: 4,
        //             dpi: 300
        //         },
        //         jsPDF: {
        //             unit: 'in',
        //             format: 'letter',
        //             orientation: 'portrait'
        //         }
        //     };
        //     html2pdf().from(template).set(opt).save();
        //     // $(".doc hr").removeClass('d-none');
        // }
        // window.generatePDF = generatePDF;
        $(function() {
            preload("#awards");
            hideSpinner();
        });
    </script>

</body>

</html>