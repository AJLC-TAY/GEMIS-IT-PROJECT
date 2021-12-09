<?php
require_once('../class/Administration.php');
$admin = new Administration();
$program = $admin->getProgram();
$prog_name = $program->get_prog_desc();
$prog_code = $program->get_prog_code();
$prog_curr_code = $program->get_curr_code();
$state = "disabled";
$edit_btn_state = "";
$display = "d-none";
$edit_btn_display = '';
if (isset($_GET['state']) && $_GET['state'] == 'edit') {
    $state = '';
    $edit_btn_state = "disabled";
    $display = "";
    $edit_btn_display = "d-none";
}
?>

<!DOCTYPE html>
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="program.php">Programs</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $prog_name; ?></li>
        </ol>
    </nav>
    <h2><?php //echo $prog_name;
        ?></h2>
    <h5><?php //echo $prog_curr_code;
        ?></h5>
    <!-- <hr> -->
    <h3><b><?php echo $prog_name; ?></b></h3>
    <h5><?php echo $prog_curr_code; ?></h5>
</header>

<!-- Form -->
<div class="container mt-4">
    <div class="card w-100 h-auto">
        <div class="d-flex justify-content-between">
            <h4>Information</h4>
            <div class="btn-con my-a">
                <button id='edit-btn' class='btn link btn-sm <?php echo $edit_btn_display; ?>'><i class="bi bi-pencil-square me-2"></i>Edit</button>
                <div class="decide-con <?php echo $display; ?>">
                    <a id="cancel-btn" href='program.php?prog_code=<?php echo $prog_code; ?>' class="btn btn-dark btn-sm me-1">Cancel</a>
                    <!--                                                <button id="cancel-btn" class="btn btn-dark btn-sm me-1">Cancel</button>-->
                    <input type="submit" form='program-view-form' class="btn btn-success btn-sm" value="Save">
                </div>
            </div>
        </div>
        <hr class='mt-2 mb-4'>
        <form id='program-view-form' class="needs-validation" method="POST" novalidate>
            <input type="hidden" name="action" value="updateProgram">
            <div class="form-group row">
                <label class="col-xl-2 col-lg-3 col-form-label text-start">Program Code</label>
                <div class="col-xl-10 col-lg-9">
                    <input type="hidden" name="current_code" value="<?php echo $prog_code; ?>">
                    <?php echo "<input class='form-input form-control mb-1' type='text' name='prog-code' value='$prog_code' $state required> 
                                                <div class='invalid-feedback'>
                                                    Please enter current code
                                                </div>"; ?>
                </div>
                <label class="col-xl-2 col-lg-3 col-form-label text-start">Description</label>
                <div class='col-xl-10 col-lg-9'>
                    <!-- <input name="name" value="<?php //echo $prog_name; 
                                                    ?>" disabled> -->
                    <?php echo "<textarea class='form-input form-control' name='name' $state>" . $prog_name . "</textarea>"; ?>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Subject table -->
<div class="container mt-5">
    <div class="card w-100 h-auto">
        <table id="table" class="table-striped table-sm">
            <thead class='thead-dark track-table'>
                <div class="row justify-content-between align-items-center mb-3">
                    <div class="col-auto">
                        <h5 class="fw-bold">SUBJECTS</h5>
                    </div>
                    <div class="d-flex col-auto">
                        <div class="col-auto">
                            <a href="subject.php?page=schedule&prog_code=<?php echo $prog_code; ?>" role="button" class="btn btn-secondary m-1"><i class="bi bi-calendar4-week me-2"></i> Schedule</a>
                        </div>
                        <div class="col-auto">
                            <a href="subject.php?prog_code=<?php echo $prog_code; ?>&action=add" id="add-btn" class="btn btn-success m-1"><i class="bi bi-plus-lg me-2"></i>Add subject</a>
                        </div>
                    </div>
                </div>
                <hr class="mt-1 mb-4">
                <div class="d-flex flex-row-reverse mb-3">
                    <!-- <div class="d-flex mb-3"> -->
                    <!-- <button id="subject-archive-btn" class="btn btn-secondary btn-sm"><i class="bi bi-archive me-2"></i>Archive</button> -->
                </div>
                <tr>
                    <th data-checkbox="true"></th>
                    <th scope='col' data-width="100" data-align="center" data-field='sub_code'>Code</th>
                    <th scope='col' data-width="600" data-halign="center" data-align="left" data-sortable="true" data-field="sub_name">Subject Name</th>
                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="sub_type">Subject Type</th>
                    <th scope='col' data-width="300" data-align="center" data-field="action">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Archive subject confirmation -->
<div class="modal fade" id="subject-archive-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
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
                <button class="btn btn-primary close-btn archive-btn">Archive</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var code = <?php echo json_encode($prog_code); ?>;
</script>