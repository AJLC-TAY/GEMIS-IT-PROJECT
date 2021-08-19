<?php include_once("../inc/head.html"); ?>
<title>Curriculum | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>


<?php
include('../class/Administration.php');
$admin = new Administration();
$curriculum = $admin->getCurriculum('curriculum'); // define var
$curr_name = $curriculum->get_cur_name();
$curr_code = $curriculum->get_cur_code();
$curr_desc = $curriculum->get_cur_desc();

$state = (isset($_GET['state']) && $_GET['state'] == 'edit') ? "" : "disabled";
$edit_btn_state = ($state == "disabled") ? "" : "disabled";
?>

<body>
    <!-- SPINNER -->
    <div class="spinner-con">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/admin/sidebar.html'); ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper"></section>
            <div class="row">
                <div class="col-lg-10">
                    <div class="row mt ps-3">
                        <!-- HEADER -->
                        <header>
                            <!-- BREADCRUMB -->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item"><a href="curriculumlist.php">Curriculum</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $curr_name; ?></li>
                                </ol>
                            </nav>
                            <h2><?php echo $curr_name; ?> Curriculum</h2>
                        </header>

                        <!-- Form -->
                        <form action="action.php" method="POST">
                            <div class="container">
                                <h4>Information</h4>
                                <div class="col-12 currcard">
                                    <form>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Curriculum Code</label>
                                            <div class="col-sm-8">
                                                <input type="hidden" name="current_code" value="<?php echo $curr_code; ?>">
                                                <?php echo "<input class='form-control form-input' type='text' name='code' value='$curr_code' $state required>"; ?>
                                            </div>
                                            <label class="col-sm-3 col-form-label">Curriculum name</label>
                                            <div class="col-sm-8">
                                                <!-- <input type="text" name="name" value="<?php echo $curr_name; ?>" disabled required> -->
                                                <?php echo "<input class='form-input form-control' type='text' name='name' value='$curr_name' $state required>"; ?>
                                            </div>
                                            <label class="col-sm-3 col-form-label">Description</label>
                                            <div class="col-sm-8">
                                                <!-- <input name="curriculum-desc" value="<?php echo $curr_desc; ?>" disabled> -->
                                                <?php echo "<textarea  class='form-input form-control' name='curriculum-desc' $state>" . $curr_desc . "</textarea>"; ?>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end col-sm-11">
                                            <input type="hidden" name="action" value="updateCurriculum">
                                            <button id='edit-btn' class='btn btn-secondary btn-sm'><i class="bi bi-pencil-square me-2"></i>EDIT</button>
                                            <button id="cancel-btn" class="btn btn-secondary btn-sm d-none me-2">CANCEL</button>
                                            <input type="submit" class="btn btn-success btn-sm d-none" value="SAVE">
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </form>
                        <!-- Track table -->
                        <div class="container mt-5">
                            <table id="table" class="table-striped">
                                <thead class='thead-dark'>
                                    <div class="d-flex justify-content-between mb-3">
                                        <h4>Strand List</h4>
                                        <div>
                                            <button class="btn btn-secondary" title='Archive strand'>Archive</button>
                                            <button id="add-btn" class="btn btn-success add-prog" title='Add new strand'>Add strand</button>
                                        </div>
                                    </div>

                                    <tr>
                                        <th data-checkbox="true"></th>
                                        <th scope='col' data-width="100" data-align="right" data-field='prog_code'>Code</th>
                                        <th scope='col' data-width="600" data-sortable="true" data-field="prog_desc">Program/Strand Description</th>
                                        <th scope='col' data-width="300" data-align="center" data-field="action">Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--main content end-->
            <!--footer start-->
            <?php include_once("../inc/footer.html"); ?>
            <!--footer end-->
        </section>
    </section>
    <!-- ADD PROGRAM MODAL -->
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
</body>
<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>
<script type="text/javascript">
    var code = <?php echo json_encode($curr_code);?>;
</script>
<script type="text/javascript" src="../js/common-custom.js"></script>
<script type="module" src="../js/admin/curriculum.js"></script>

</html>