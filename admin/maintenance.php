<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
?>
<title>Maintenance | GEMIS</title>
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
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Maintenance</li>
                                    </ol>
                                </nav>
                                <div class="justify-content-between row">
                                    <div class="col-md-4 mt-2">
                                        <h3 class="fw-bold">Maintenance</h3>
                                    </div>
                                    <!-- <div class="col-auto">
                                        <button type="button" class="btn btn-success mt-1" data-bs-toggle="modal" data-bs-target="#add-modal"><i class="bi bi-plus-lg me-2"></i>Add Curriculum</button>
                                    </div> -->
                                </div>
                            </header>
                            <div class="content">
                                <div class="card py-3 mb-3">
                                    <h5>Backup Data</h5>
                                    <p>This will export all the data that is currently recorded in the database. This will only make a copy of all records (curriculum, school personnel, and student records of any school year), which can be restored or deleted. </p>
                                    <div class="d-flex justify-content-end">
                                        <button data-bs-toggle="modal" data-bs-target="#backup-confirmation-modal" class="btn btn-success">Backup</button>
                                    </div>
                                </div>
                                <div class="card py-3 mb-3">
                                    <div class="container">
                                        <h5>Backup</h5>
                                        <div class="d-flex justify-content-between mb-3">
                                            <!-- SEARCH BAR -->
                                            <span class="flex-grow-1 me-3">
                                                <input id="search-input" type="search" class="form-control" placeholder="Search something here">
                                            </span>
                                        </div>
                                        <div class="row overflow-auto">
                                            <table id="table" data-url="getAction.php?data=backupFiles" data-height="500" data-search-selector="#search-input" data-toggle="table" class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th scope='col' data-width="500" data-halign="center" data-align="left" data-field="name">School Year</th>
                                                        <th scope='col' data-width="300" data-align="center" data-field="date">Date created</th>
                                                        <th scope='col' data-width="200" data-align="center" data-field="action">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>                                   
                                        </div>
                                    </div>
                                </div>
                                <div class="card py-3">
                                    <h5>Reset System</h5>
                                    <p>Reseting the system will delete all data and backup stored, which includes the admin and faculty members, student records, and curriculum data.</p>
                                    <div class="d-flex justify-content-end">
                                        <button data-bs-toggle="modal" data-bs-target="#reset-confirmation-modal" class="btn btn-danger">Reset</button>
                                    </div>
                                </div>
                            </div>
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
    <!-- BACKUP CONFIRMATION MODAL -->
    <div class="modal fade" id="backup-confirmation-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Confirmation</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Export a back up file?</h5>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button data-action="backupData" class="btn btn-success btn-sm close-btn action">Backup Now</button>
                </div>
            </div>
        </div>
    </div>
    <!-- DELETE CONFIRMATION MODAL -->
    <div class="modal fade" id="delete-confirmation-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Confirmation</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Delete this backup file?</h5>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-danger btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button data-action="delete" class="btn btn-secondary btn-sm close-btn action">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!-- RESTORE CONFIRMATION MODAL -->
    <div class="modal fade" id="restore-confirmation-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Confirmation</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Restore this backup file?</h5>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button data-action="restore" class="btn btn-success btn-sm close-btn action">Restore</button>
                </div>
            </div>
        </div>
    </div>
    <!-- RESET CONFIRMATION MODAL -->
    <div class="modal fade" id="reset-confirmation-modal" tabindex="-1" aria-labelledby="modal deleteAccount" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Confirmation</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reset-system-form" method="POST">
                        <input type="hidden" name="action" value="reset-system">
                        <p class="text-secondary"><small>Enter your password to confirm system reset</small></p>
                        <div class="container">
                            <div class="form-group row mb-3">
                                <label for="current">Current password</label>
                                <input id="current" type="password" name="current" class='form-control' placeholder="Password">
                            </div>
                            <div class="form-group row">
                                <label for="current">Re-enter password</label>
                                <input id="re-enter-pass" type="password" name="re-enter-pass" class='form-control' placeholder="Password">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-danger close-btn btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <input type="submit" name="reset-system" form="reset-system-form" class="btn btn-secondary btn-sm" value="Reset">
                </div>
            </div>
        </div>
</div>
</body>

<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>
<script type="text/javascript" src="../js/common-custom.js"></script>
<script>
    let uid = <?php echo json_encode($_SESSION['user_id']); ?>;
    function showBackUpName(name) {
        $(".backup-name").html(name);
        $(".action").attr("data-name", name);
    }

    $(document).on("click", ".action", function() {
        $.get(`getAction.php?data=action-maintenance&action=${$(this).attr("data-action")}&name=${$(this).attr("data-name")}`, function(data) {
            data = JSON.parse(data);
            $("[id*='confirmation-modal']").modal('hide');
            $("#table").bootstrapTable("refresh");
            showToast(data.status, data.message);
        });
    });
    $(function () {
        preload("#maintenance");
        $("#main-spinner-con").hide();

    });
    
</script>


<!-- VALIDATION -->
<script src="../js/validation/jquery.validate.min.js"></script>
<script src="../js/validation/additional-methods.min.js"></script>
<script src="../js/validation/validation.js"></script>

</html>