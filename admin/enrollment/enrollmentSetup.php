<?php require_once("../class/Administration.php");
$admin = new Administration();
?>
<!-- HEADER -->
<header id="main-header">
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="enrollment.php">Enrollment</a></li>
            <li class="breadcrumb-item active">Setup</li>
        </ol>
    </nav>
    <div class="container px-4 text-center">
        <h2>Enrollment Setup</h2>
        <h5>SY <?php echo $_SESSION['school_year']; ?></h5>
    </div>
</header>

<form id="enrollment-setup" class="needs-validation " enctype="multipart/form-data" action="action.php" method="POST" novalidate>
    <div id="stepper" class="bs-stepper">
        <div id="header" class="bs-stepper-header w-75 mx-auto">
            <div class="step mx-5" data-target="#test-l-1">
                <button type="button" class="btn step-trigger">
                    <span class="bs-stepper-label">Step</span>
                    <span class="bs-stepper-circle">1</span>
                </button>
            </div>
            <div class="line"></div>
            <div class="step mx-5" data-target="#test-l-2">
                <button type="button" class="btn step-trigger">
                    <span class="bs-stepper-label">Step</span>
                    <span class="bs-stepper-circle">2</span>
                </button>
            </div>
            <!-- <div class="line"></div>
            <div class="step mx-5" data-target="#test-l-3">
                <button type="button" class="btn step-trigger">
                    <span class="bs-stepper-label">Step</span>
                    <span class="bs-stepper-circle">3</span>
                </button>
            </div> -->
        </div>
        <div class="bs-stepper-content">
            <div id="test-l-1" class="content">
                <div class="card body w-100 h-auto p-4">
                    <!-- STEP 1 -->
                    <div class="d-flex justify-content-between">
                        <h4 class="fw-bold">FACULTY ENROLLMENT PRIVILEGES</h4>
                        <hr class='mt-4'>
                        <div class="dropdown">
                            <button class="btn btn-outline-success btn-sm" type="button" id="faculty-filter" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-filter me-2"></i>Filter</button>
                            <ul class="dropdown-menu" aria-labelledby="faculty-filter">
                                <li><a role='button' data-value='*' class="filter-item dropdown-item active">All</a></li>
                                <li><a role='button' data-value='1' class="filter-item dropdown-item">Can enroll</a></li>
                                <li><a role='button' data-value='0' class="filter-item dropdown-item">Cannot enroll</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="flex-grow-1 me-3">
                            <input id="faculty-search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
                        </span>
                        <div>
                            <input id="f-table-clear-btn" type="reset" class=" mb-0 me-1 btn btn-outline-dark btn-sm" value="Clear" />

                            <button id="rm-enroll-priv" class="enroll-priv-btn btn btn-secondary btn-sm me-1" title=''>Remove enrollment access</button>
                            <button id="enroll-priv" class="enroll-priv-btn btn btn-primary btn-sm" title=''>Grant enrollment access</button>
                        </div>
                    </div>
                    <table id="faculty-table" class="table-striped">
                        <thead class='thead-dark'>
                            <tr>
                                <th data-checkbox="true"></th>
                                <th scope='col' data-width="800" data-halign="center" data-align="left" data-sortable="true" data-field="name">Faculty Name</th>
                                <th scope='col' data-width="200" data-align="center" data-field="can-enroll">Can Enroll</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="row justify-content-end mt-3">
                    <div class="col-auto">
                        <a id='to-step-2' role="button" class="stepper-btn btn btn-success next" >Next</a>
                    </div>
                </div>
            </div>
            <!-- STEP 1 END -->
            <!-- STEP 2 -->
          
        <!--STEP 2 END-->
        <!--STEP 3-->
        <div id="test-l-2" class="content">
            <div class="card body w-100 h-auto p-4">
                <!-- STEP 1 -->
                <h4 class="fw-bold">Enable enrollment</h4>
                <div class="row mt-3">
                    <div class="col-lg-6 mb-3">
                        <h6>Summary list of Faculties who can enroll <span id='faculty-count' class="badge bg-secondary rounded-pill"></span></h6>
                        <ul id="faculty-list" class="list-group overflow-auto" style="max-height: 350px;">

                        </ul>
                    </div>
                    <!-- <div class="col-lg-4 mb-3">
                        <h6>List of Sections <span id='section-count' class="badge bg-secondary rounded-pill"></span></h6>
                        <ul id="section-list" class="list-group h-75 overflow-auto">

                        </ul>
                    </div> -->
                    <div class="col-lg-6 mb-3">
                        <h6>Enrollment status</h6>
                        <div class="container p-4 border border-2">
                            <div class='form-check form-switch my-auto'>
                                <?php
                                $enroll_opt = "Enrollment is " . ($_SESSION['enroll_status']  ? "on-going" : "disabled");
                                echo "<input " . ($_SESSION['enroll_status'] ? "checked" : "") . " name='enrollment' data-id='{$_SESSION['sy_id']}' class='form-check-input' "
                                    . "type='checkbox' title='Turn " . ($_SESSION['enroll_status'] ? "off" : "on") . " enrollment'>"
                                    . "<span class='status'>$enroll_opt</span>";
                                ?>
                            </div>
                            <p class="mt-3 text-secondary"><small>Enabling / Disabling enrollment will automatically start or stop accepting enrollees.</small></p>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row justify-content-end mt-3">
                    <div class="col-auto">
                        <a class="stepper-btn btn btn-secondary me-1 previous">Back</a>
                        <a href="enrollment.php" class="stepper-btn btn btn-success">Go to Enrollment Dashboard</a>
                    </div>
                </div>
        </div>
        <!--STEP 3 END-->
    </div>
    </div>
    <!-- STEPPER END -->
</form>