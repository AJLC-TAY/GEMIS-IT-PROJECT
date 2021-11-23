<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
?>
<title>Curriculum | GEMIS</title>
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
        <?php include_once('../inc/adminSidebar.php'); ?>
        <!--MAIN CONTENT -->
        <section id="main-content">
            <section class="wrapper ps-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <?php
                            if (isset($_GET['code'])) {
                                include_once("curriculum/curriculumView.php");
                                $jsFilePath = "../js/admin/curriculum.js";
                            } else {
                                include_once("curriculum/curriculumCards.php");
                                $jsFilePath = "../js/admin/curriculum-card.js";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </section>
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

<!-- VALIDATION -->
<script src="../js/validation/jquery.validate.min.js"></script>
<script src="../js/validation/additional-methods.min.js"></script>
<script src="../js/validation/validation.js"></script>

<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>
<script type="text/javascript" src="../js/common-custom.js"></script>
<script type="module" src="<?php echo $jsFilePath; ?>"></script>

</html>