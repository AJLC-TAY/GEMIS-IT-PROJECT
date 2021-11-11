<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
?>
<title>Grade Report | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
<script src="../assets/js/html2pdf.bundle.min.js"></script>
<link href='../css/report.css' rel='stylesheet'>
<style>

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
    <section id="container">
        <?php include_once('../inc/adminSidebar.php'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <?php include_once ("../admin/award/gradeReport.php"); ?>
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
        $(function() {
            preload("#students");
            hideSpinner();
        });
    </script>

</body>

</html>