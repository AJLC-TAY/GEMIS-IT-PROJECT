<!DOCTYPE html>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Enrollment</li>
        </ol>
    </nav>
    <h3 class="fw-bold">Enrollment</h3>
</header>

<section class="row">
    <div class="col-lg-4">
        <div class="card-box bg-warning">
            <div class="inner">
                <h4>Pending: </h4>
                <!-- <h2> <?php echo $pending; ?> </h2> -->
            </div>
            <div class="en-icon">
                <i class="bi bi-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-box bg-default">
            <div class="inner">
                <h4>Enrolled: </h4>
                <!-- <h2> <?php echo $enrolled; ?> </h2> -->
            </div>
            <div class="en-icon">
                <i class="bi bi-clipboard-check"></i>
            </div>

        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-box bg-danger">
            <div class="inner">
                <h4>Rejected: </h4>
                <!-- <h2> <?php echo $rejected; ?> </h2> -->
            </div>
            <div class="en-icon">
                <i class="bi bi-clipboard-x"></i>
            </div>
        </div>
    </div>
</section>
<!-- QUICK ACTIONS -->
<div class="card shadow-sm mt-1">
    <h5 class='mb-0 fw-bold ms-3 mt-2'>QUICK ACTIONS</h5>
    <hr class="mt-1 mb-3">
    <div class="col-lg-12 ms-2 d-flex justify-content-center">
        <div class="row w-75">
            <a href='enrollment.php?page=enrollees' class="btn btn-secondary button col me-3"> <i class="bi bi-person-lines-fill fa-3x"></i><br>View Enrollment List</a>
            <!-- <a href='enrollment.php?page=generateReport' class="btn btn-secondary button col me-3"><i class="bi bi-file-earmark-text-fill fa-5x"></i><br>Generate Report</a>
            <a href='enrollment.php?page=setup' class="btn btn-secondary button col me-3"><i class="bi bi-gear-wide-connected fa-5x"></i><br>Enrollment Setup</a> -->
            <a href='enrollment.php?page=form' class="btn btn-secondary button col me-3"><i class="bi bi-ui-radios fa-3x"><br></i>Enroll Student</a>
            <a href='enrollment.php?page=admission' class="btn btn-secondary button col me-3"><i class="bi bi-person-plus-fill fa-3x"><br></i>Student Admission</a>
        </div>
    </div>
</div>