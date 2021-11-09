<?php 
require_once("sessionHandling.php");
include_once("../inc/head.html"); ?>
<title>Section | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>
<!DOCTYPE html>
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
                                $isViewPage = TRUE;
                                $js = "<script type='module' src='../js/admin/section.js'></script>";
                                if (isset($_GET['action']) && $_GET['action'] === 'add') {
                                    include_once("section/sectionForm.php");
                                } else if (isset($_GET["sec_code"])) {
                                    include_once("section/sectionView.php");
                                } else if (isset($_GET['page']) && $_GET['page'] == 'sub_class_form') {
                                    include_once("section/subjectClassForm.php");
                                    $js = "<script type='module' src='../js/admin/subject-class.js'></script>";
                                } else if (isset($_GET['page']) && $_GET['page'] == 'sub_classes') {
                                    include_once("section/subjectClasses.php");
                                    $js = "<script type='module' src='../js/admin/subject-class.js'></script>";
                                } else {
                                    $isViewPage = FALSE;
                                    include_once("section/sectionList.php");
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

    <div class="modal fade" id="sub-class-modal" tabindex="-1" aria-labelledby="modal subClass" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0 fw-bold">Subject</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row border p-3 mb-3">
                            <div class="col-md-7">
                                <dl class="row mb-0">
                                    <dt class="col-4">Section Name</dt>
                                    <dd class="col-8">
                                        <p id="section-name"></p>
                                    </dd>
                                    <dt class="col-4">Program</dt>
                                    <dd class="col-8">
                                        <ul id="program-list" class="list-group list-group-horizontal"></ul>
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-md-5">
                                <dl class="row mb-0">
                                    <dt class="col-4">Grade Level</dt>
                                    <dd class="col-8">
                                        <p class="grd-level"></p>
                                    </dd>
                                    <dt class="col-4">No of Students</dt>
                                    <dd class="col-8">
                                        <p id="stud-no"></p>
                                    </dd>
                                </dl>
                            </div>
                        </div>

                        <form id="subject-class-form" action="action.php" method="post">
                            <input type="hidden" id="selected-section" name="section" value="">
                            <input type="hidden" name="action" value="editSubjectSection">
                            <div class="container">
                                <div class="row">
                                    <div class="row"><p class="px-0 fw-bold">Recommended</p></div>
                                    <div class="row recommended"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-dark close-btn btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <input type="submit" form="subject-class-form" class="submit btn btn-success btn-sm" value="Submit" />
                </div>
            </div>
        </div>
    </div>
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->
</body>
<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>
<script type="text/javascript" src="../js/common-custom.js"></script>
<?php echo $js; ?>

</html>