<?php
require_once("sessionHandling.php");
include_once("../inc/head.html"); ?>
<title>Award Document | GEMIS</title>
<!-- <link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'> -->
<script src="../assets/js/html2pdf.bundle.min.js"></script>
<style>
    .doc {
        width: 8.5in !important;
        height: auto;
    }

    .template {
        width: 7.5in !important;
        font-family: Arial !important;
        font-size: 13px !important;
    }

    .template li {
        width: 100%;
        min-height: 9.98in;
        height: auto;
        /* height: 9.98in !important; */
        margin: 0.5in !important;
    }

    * {
        box-sizing: border-box;
    }

    /*Header*/
    .report-title {
        text-align: center;
        margin-top: 40px;
        margin-bottom: 40px;
    }

    .title {
        margin-bottom: 0;
    }

    .sub-title {
        margin-top: 0;
    }

    /*Content*/
    img {
        height: 100px;
        width: 100px;
    }

    td {
        border: 0.5px solid #C5C5C5;
    }

    .fsize {
        font-size: 15px;
    }

    .fsizes {
        font-size: 12px;
    }

    .perLine {
        line-height: 45px;
    }

    .parentLine {
        display: flex;
    }

    .subLine {
        min-width: 150px;
        line-height: 45px;
    }

    .subLine2 {
        min-width: 170px;
    }

    .subLine3 {
        min-width: 350px;
    }

    .subLine4 {
        min-width: 250px;
    }

    .ind {
        text-indent: 10px;
    }

    .par {
        text-indent: 50px;
        text-align: justify;
        font-size: 15px;
        word-wrap: break-word;
    }

    .right {
        float: right;
    }

    /*Footer*/
    .signatory-con {
        width: 50%;
        display: table-column;
    }
</style>
</head>

<body>
    <!-- SPINNER -->
    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container"></section>
    <?php include_once('../inc/facultySidebar.php'); ?>
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
                                    require_once("../admin/award/academicExcellence.php");
                                    break;
                                case 'sp': # sp for specific
                                    require_once("../admin/award/awardGeneric.php");
                                    break;
                                case 'pa': # pa for perfect attendance
                                    require_once("../admin/award/perfectAttendance.php");
                                    break;
                                case 'ca': # pa for conduct award
                                    require_once("../admin/award/conduct.php");
                                    break;
                                case 'la': # la for loyalty award
                                    require_once("../admin/award/loyalty.php");
                                    break;
                            }
                        } else {
                            require_once("../admin/award/parameters.php");
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
        function generatePDF(filename) {
            // hide horizontal line 
            // $(".doc hr").addClass('d-none');
            const template = document.querySelector(".template");
            var opt = {
                margin: [0.5, 0, 0.5, 0],
                // margin: 0,
                filename: filename + '.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 4,
                    dpi: 300
                },
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'portrait'
                }
            };
            html2pdf().from(template).set(opt).save();
            // $(".doc hr").removeClass('d-none');
        }
        // window.generatePDF = generatePDF;
        $(function() {
            preload("#faculty");
            hideSpinner();
        });
    </script>

</body>

</html>