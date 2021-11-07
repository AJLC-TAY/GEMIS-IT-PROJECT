<?php 
require_once("sessionHandling.php");
include_once ("../inc/head.html"); 
    echo (!isset($_GET["action"]) ? "<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>" : "");
?>
    <title>PCNHS GEMIS</title>
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
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <div class="col-lg-12">
                    <div class="row mt ps-3">
                        <?php 
                            if (isset($_GET['page']) && $_GET['page'] === 'schedule') {
                                include("subject/subjectSchedule.php");
                            } else if (isset($_GET['action'])) {
                                include("subject/subjectForm.php");
                            } 
                            else if (isset($_GET['sub_code'])){
                                include("subject/subjectView.php");
                            } 
                            else {
                                include("subject/subjectList.php");
                            }
                        ?>
                    </div>
                </div>
                <!--main content end-->
                <!--footer start-->
                <?php include_once ("../inc/footer.html");?>
                <!--footer end-->
            </section>
        </section>
    </section>

    <!-- Archive modal -->
    <div class="modal fade" id="archive-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Confirmation</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Do you want to archive <span id="modal-identifier"></span>?</h5>
                    <p class="modal-msg"></p>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary archive-btn">Archive</button>
                </div>
            </div>
        </div>
    </div>

    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->
    <?php // echo (isset($_GET["action"]) 
          //       ? "<!-- JQUERY FOR BOOTSTRAP TABLE -->"
          //           ."<script src='../assets/js/bootstrap-table.min.js'></script>"
          //           ."<script src='../assets/js/bootstrap-table-en-US.min.js'></script>"
          //       : "");
    ?>
    
    <!-- JQUERY FOR BOOTSTRAP TABLE -->"
    <script src='../assets/js/bootstrap-table.min.js'></script>
    <script src='../assets/js/bootstrap-table-en-US.min.js'></script>
    <script src="../js/common-custom.js"></script>
    <script type='module' src="../js/admin/subject.js"></script>
    <!-- <script type='text/javascript' src="../js/admin/subject.js"></script> -->
    <!-- <script type="module" src="../js/admin/subjectlist.js"></script> -->
</body>

</html>