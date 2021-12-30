<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
?>
<title>School Year | GEMIS</title>
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
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <?php
                                if (isset($_GET['action'])) {
                                    include_once("schoolYear/syForm.php");
                                } else if (isset($_GET['id'])) {
                                    include_once("schoolYear/syView.php");
                                } else {
                                    include_once("schoolYear/syList.php");
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <!--MAIN CONTENT END-->
                <!--FOOTER START-->
                <?php include_once("../inc/footer.html"); ?>
                <!--FOOTER END-->
            </section>
        </section>
    </section>
    <!-- END SY MODAL -->
    <div class="modal fade" id="end-sy-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Confirmation</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>End school year <?php echo $_SESSION['school_year']; ?>?</h5>
                    <p class="modal-msg">Ending this school year will automatically promote students with passing grades.</p>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <a href="getAction.php?data=end_school_year" class="btn btn-danger close-btn delete-btn btn-sm">End school year</a>
                </div>
            </div>
        </div>
    </div>
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->
    <!-- ARCHIVE CONFIRMATION MODAL -->
    <div class="modal fade" id="archive-confirmation-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Confirmation</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Archive <span class='backup-name'></span> school year?</h5>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button data-action="archivesy" class="btn btn-danger btn-sm close-btn action" data-bs-dismiss="modal"><i class='bi bi-archive me-2'></i>Archive</button>
                </div>
            </div>
        </div>
    </div>
    <!-- JQUERY FOR BOOTSTRAP TABLE -->
    <script src="../assets/js/bootstrap-table.min.js"></script>
    <script src="../assets/js/bootstrap-table-en-US.min.js"></script>
    <!-- CUSTOM JS -->
    <script src="../js/common-custom.js"></script>
    <script type="module" src="../js/admin/school-year.js"></script>
    <!-- VALIDATION -->
    <script src="../js/validation/jquery.validate.min.js"></script>
    <script src="../js/validation/additional-methods.min.js"></script>
    <script src="../js/validation/validation.js"></script>
</body>

</html>