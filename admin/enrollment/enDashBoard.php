<?php
$enroll_status = "";
$deactivate_modal = "";
$enroll_setup = "";
$enroll_report = "";
if ($_SESSION['user_type'] == "AD") {
    $enroll_report = "<a href='enrollment.php?page=generateReport' class='btn btn-secondary button col me-3 mt-1'><i class='bi bi-file-earmark-text-fill fa-3x'></i><br>Generate Report</a>";
    $enroll_status = "<div class='toggle d-flex flex-row-reverse align-items-center'>
            <label class='switch ms-3'>
                <input name='enrollment' type='checkbox' " . $_SESSION['enroll_status'] == 0 ? '' : 'checked' . ">
                <span class='slider round'></span>
            </label>
            Accept enrollees
        </div>";
    $deactivate_modal = "<div id='deactivate-modal' class='modal fade' tabindex='-1' aria-labelledby='modal' aria-hidden='true'>
                            <div class='modal-dialog modal-dialog-centered'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <div class='modal-title'>
                                            <h4 class='mb-0'>Confirmation</h4>
                                        </div>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body'>
                                        Deactivate <span id='question'></span><br>
                                        <small>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </small>
                                    </div>
                                    <div class='modal-footer'>
                                        <form id='deactivate-form' action='action.php'>
                                            <input type='hidden' name='action' value='deactivate'/>
                                            <button class='close btn btn-secondary close-btn' data-bs-dismiss='modal'>Cancel</button>
                                            <input type='submit' form='deactivate-form' class='submit btn btn-danger' value='Deactivate'>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>";
    $enroll_setup = "<a href='enrollment.php?page=setup' class='btn btn-secondary button col me-3 mt-1'><i class='bi bi-gear-wide-connected fa-3x'></i><br>Enrollment Setup</a>";
}
?>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Enrollment</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between mb-3">
        <h3 class="fw-bold">Enrollment</h3>
        <?php if ($_SESSION['user_type'] == 'AD') { ?>
        <div class='d-flex'>
            <div class='form-check form-switch'>
                <input name='enrollment' type='checkbox' class='form-check-input mt-2 me-3' <?php echo $_SESSION['enroll_status'] == 0 ? '' : 'checked' ; ?>>
                <label for='auto-refresh' class='form-check-label'><h5 class="mb-0">Accept Enrollees</h5></label>
            </div>
        </div>
        <?php } ?>
    </div>
</header>

<section class="border border-2 p-2 pt-3 mb-3">
    <div class="container">
        <div class="row justify-content-end">
             <div class="col-auto">
                <button class="btn btn-sm btn-primary" onclick="refresh();"><i class="bi bi-arrow-clockwise me-2"></i>Refresh</button>
             </div>
             <div class="col-auto pt-2">
                <div class="form-check form-switch">
                    <input id="auto-refresh" type="checkbox" class="form-check-input refresh-switch" checked>
                    <label for="auto-refresh" class="form-check-label">Auto-Refresh Counts</label>
                </div>
             </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card-box bg-warning">
                    <div class="inner">
                        <h4>Pending</h4>
                        <h2 id="pending"></h2>
                    </div>
                    <div class="en-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-box bg-default">
                    <div class="inner">
                        <h4>Enrolled</h4>
                        <h2 id='enrolled'></h2>
                    </div>
                    <div class="en-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </div>

                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-box bg-danger">
                    <div class="inner">
                        <h4>Rejected</h4>
                        <h2 id="rejected"></h2>
                        <div class="en-icon">
                            <i class="bi bi-clipboard-x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- QUICK ACTIONS -->
<div class="card shadow-sm mt-1">
    <h5 class='mb-0 fw-bold ms-3 mt-2'>QUICK ACTIONS</h5>
    <hr class="mt-1 mb-3">
    <div class="col-lg-12 ms-2">
        <div class="row">
            <a href='enrollment.php?page=enrollees' class="btn btn-secondary button col me-3 mt-1"> <i class="bi bi-person-lines-fill fa-3x"></i><br>View Enrollment List</a>
            <?php
            echo $enroll_report;
            echo $enroll_setup;
            ?>
            <a href='enrollment.php?page=form' class="btn btn-secondary button col me-3 mt-1"><i class="bi bi-ui-radios fa-3x"><br></i>Enrollment Form</a>
        </div>
    </div>
</div>
<!-- MODAL -->
<?php echo $deactivate_modal; ?>
<!-- MODAL END -->