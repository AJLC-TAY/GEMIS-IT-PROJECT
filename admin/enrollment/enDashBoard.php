<?php session_start(); ?>
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
        <form id="enroll-form" action="action.php" method="post">
            <input type="hidden" name="action" value="editEnrollStatus">
            <!-- <div class="form-check form-switch">
                <input id="auto-refresh" type="checkbox" class="form-check-input" checked>
                <label for="auto-refresh" class="form-check-label">Auto-Refresh Counts</label>
            </div> -->
            <div class="toggle d-flex flex-row-reverse align-items-center">
                <label class="switch ms-3">
                    <input name="enrollment" type="checkbox" <?php echo $_SESSION['enroll_status'] == 0 ? "" : "checked"; ?>>
                    <span class="slider round"></span>
                </label>
                Accept enrollees
            </div>
        </form>
    </div>
</header>
<section class="row">
    <div class="d-inline-flex align-items-center">
        <!-- <div class="col-auto "> -->
            <button class="me-3 btn btn-sm btn-primary" onclick="refresh();">Refresh</button>
        <!-- </div> -->
        <!-- <div class="col-auto"> -->
            <div class="form-check form-switch">
                <input id="auto-refresh" type="checkbox" class="form-check-input refresh-switch" checked>
                <label for="auto-refresh" class="form-check-label">Auto-Refresh Counts</label>
            </div>
        <!-- </div>     -->
    </div>
    <div class="col-lg-4">
        <div class="card-box bg-warning">
            <div class="inner">
                <h4>Pending</h4>
                 <h2 id="pending"></h2>
            </div>
            
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-box bg-default">
            <div class="inner">
                <h4>Enrolled</h4>
                 <h2 id='enrolled'></h2>
            </div>
            
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-box bg-danger">
            <div class="inner">
                <h4>Rejected</h4>
                <h2 id="rejected"></h2>
            </div>
        </div>
    </div>
</section>
<!-- QUICK ACTIONS -->
<div class="card h-auto">
    <h4>QUICK ACTIONS</h4>
    <div class="col-lg-12">                       
        <div class="row mt ps-3">
            <a href='enrollment.php?page=enrollees' class="btn btn-secondary button col me-3"> <i class="bi bi-person-lines-fill fa-5x"></i><br>View Enrollment List</a>
            <a href='enrollment.php?page=generateReport' class="btn btn-secondary button col me-3"><i class="bi bi-file-earmark-text-fill fa-5x"></i><br>Generate Report</a>
            <a href='enrollment.php?page=setup' class="btn btn-secondary button col me-3"><i class="bi bi-gear-wide-connected fa-5x"></i><br>Enrollment Setup</a>
            <a href='enrollment.php?page=form' class="btn btn-secondary button col me-3"><i class="bi bi-ui-radios fa-5x"><br></i>Enrollment Form</a>      
        </div>
    </div>
</div>
<!-- MODAL -->
<div id="deactivate-modal" class="modal fade" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h4 class="mb-0">Confirmation</h4>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Deactivate <span id="question"></span><br>
                <small>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </small>
            </div>
            <div class="modal-footer">
                <form id="deactivate-form" action="action.php">
                    <input type="hidden" name="action" value="deactivate"/>
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                    <input type="submit" form="deactivate-form" class="submit btn btn-danger" value="Deactivate">
                </form>
            </div>
        </div>
    </div>
</div>
<!-- MODAL END -->