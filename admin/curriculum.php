<?php
include_once("../inc/head.html");
include_once('../class/Administration.php');

$admin = new Administration();
$curriculum = $admin->getCurriculum();
$curr_name = $curriculum->get_cur_name();
$curr_code = $curriculum->get_cur_code();
$curr_desc = $curriculum->get_cur_desc();

$edit = "disabled";
$disable_when_edit = "";
$none_when_edit = "";
$display_when_edit = "d-none";

if (isset($_GET['state']) && $_GET['state'] == 'edit') {
    $edit = '';
    $display_when_edit = "";
    $disable_when_edit = "disabled";
    $none_when_edit = "d-none";
}

?>

<title>Curriculum | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>

<body>
    <!-- SPINNER -->
    <div class="spinner-con">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/admin/sidebar.php'); ?>
        <!--MAIN CONTENT -->
        <section id="main-content">
            <section class="wrapper ps-4">
                <div class="row">
                    <div class="col-lg-11">
                        <div class="row ps-3">
                            <!-- HEADER -->
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="curriculumList.php">Curriculum</a></li>
                                        <li class="breadcrumb-item active" aria-current="page"><?php echo $curr_name; ?></li>
                                    </ol>
                                </nav>
                                <h2 class="fw-bold"><?php echo $curr_name; ?></h2>
                                <hr class="my-2">
                                <h6 class="fw-bold">Curriculum</h6>
                            </header>
                            <!-- HEADER END -->
                            <!-- FORM -->
                            <div class="currcard container">
                                <div class='card'>
                                    <div class="d-flex justify-content-between">
                                        <h4>Information</h4>
                                        <div class="btn-con my-a">
                                            <input type="hidden" name="action" value="updateCurriculum">
                                            <button id='edit-btn' class='btn link btn-sm <?php echo $none_when_edit; ?>'><i class="bi bi-pencil-square me-2"></i>Edit</button>
                                            <div class="decide-con <?php echo $display_when_edit; ?>">
                                                <a id="cancel-btn" href='curriculum.php?code=<?php echo $curr_code; ?>' class="btn btn-dark btn-sm me-1">Cancel</a>
                                                <!-- <button id="cancel-btn" class="btn btn-dark btn-sm me-1 <?php echo $display_when_edit; ?>">Cancel</button> -->
                                                <input type="submit" form="curriculum-form" class="btn btn-success btn-sm" value="Save">
                                            </div>
                                        </div>
                                    </div>
                                    <hr class='mt-2 mb-4'>
                                    <section class="w-100">
                                        <div class="ps-3 row w-100">
                                            <form id='curriculum-form' action="action.php" method="POST">
                                                <div class="form-group row">
                                                    <div class="col-sm-3"><label class="my-auto">Code</label></div>
                                                    <div class="col-sm-9">
                                                        <input type="hidden" name="action" value="updateCurriculum">
                                                        <input type="hidden" name="current_code" value="<?php echo $curr_code; ?>">
                                                        <?php echo "<input class='form-control form-input ' type='text' name='code' value='$curr_code' $edit required>"; ?>
                                                    </div>
                                                    <label class="col-sm-3">Name</label>
                                                    <div class="col-sm-9">
                                                        <?php echo "<input class='form-input form-control ' type='text' name='name' value='$curr_name' $edit required>"; ?>
                                                    </div>
                                                    <label class="col-sm-3">Description</label>
                                                    <div class="col-sm-9">
                                                        <?php echo "<textarea  class='form-input form-control ' name='curriculum-desc' $edit>" . $curr_desc . "</textarea>"; ?>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </section>
                                </div>
                            </div>
                            <!-- FORM END -->
                            <!-- STRAND TABLE -->
                            <div class="container mt-5">
                                <div class="card w-100 h-auto">
                                    <table id="table" class="table-striped table-sm">
                                        <thead class='thead-dark track-table'>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="fw-bold">STRAND LIST</h5>
                                                <a href="#add-modal" id="add-btn" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add-modal"><i class="bi bi-plus me-2"></i>Add strand</a>
                                            </div>
                                            <hr class="mt-1 mb-4">
                                            <div class="d-flex flex-row-reverse mb-3">
                                                <!-- <div class="d-flex mb-3"> -->
                                                <button id="track-archive-btn" class="btn btn-secondary btn-sm"><i class="bi bi-archive me-2"></i>Archive</button>
                                            </div>
                                            <tr>
                                                <th data-checkbox="true"></th>
                                                <th scope='col' data-width="100" data-align="center" data-field='prog_code'>Code</th>
                                                <th scope='col' data-width="600" data-align="center" data-sortable="true" data-field="prog_desc">Program/Strand Description</th>
                                                <th scope='col' data-width="300" data-align="center" data-field="action">Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- STRAND TABLE END -->
                        </div>
                    </div>
                </div>
            </section>
            <!-- FOOTER -->
            <?php include_once("../inc/footer.html"); ?>
            <!-- FOOTER END -->
        </section>
        <!-- MAIN CONTENT END-->
        <!-- ARCHIVE CONFIRMATION MODAL -->
        <div class="modal" id="track-archive-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
            <div class="modal-dialog">
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
                        <button class="btn btn-primary close-btn archive-btn">Archive</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- ARCHIVE CONFIRMATION MODAL END -->
        <!-- ADD MODAL -->
        <div class="modal" id="add-modal" tabindex="-1" aria-labelledby="modal addProgram" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <h4 class="mb-0">Add Strand/Program</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="prog-form" action="">
                            <div class="form-group">
                                <label for="prog-code">Strand Code</label>
                                <input id="prog-code" type="text" name="code" class='form-control' placeholder="Enter unique code here. ex. STEM" required>
                                <p class="unique-error-msg text-danger m-0 invisible"><small>Please provide a unique strand code</small></p>
                                <label for="prog-name">Strand Name</label>
                                <input id="prog-name" type="text" name="desc" class='form-control' placeholder="ex. Science, Technology, Engineering, and Math" required>
                                <p class="name-error-msg text-danger m-0 invisible"><small>Please provide the program name</small></p>
                                <label for="prog-curr">Curriculum</label>
                                <input type="text" class='form-control' name="curr-code" value="<?php echo ($curr_code); ?>" readonly>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="submit-prog" form="prog-form" class="submit btn btn-primary" data-link='addProg.php'>Add</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ADD MODAL END -->
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->
</body>
<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>

<script type="text/javascript">
    var code = <?php echo json_encode($curr_code); ?>;
</script>
<script type="text/javascript" src="../js/common-custom.js"></script>
<script type="module" src="../js/admin/curriculum.js"></script>
</html>