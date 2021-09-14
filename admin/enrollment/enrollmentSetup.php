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
        <h5>SY <?php echo $_SESSION['sy_desc']; ?></h5>
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
            <div class="line"></div>
            <div class="step mx-5" data-target="#test-l-3">
                <button type="button" class="btn step-trigger">
                    <span class="bs-stepper-label">Step</span>
                    <span class="bs-stepper-circle">3</span>
                </button>
            </div>
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
                                <li><a data-value='*' class="filter-item dropdown-item active">All</a></li>
                                <li><a data-value='1' class="filter-item dropdown-item">Can enroll</a></li>
                                <li><a data-value='0' class="filter-item dropdown-item">Cannot enroll</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="flex-grow-1 me-3">
                            <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
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
                                <th scope='col' data-width="600" data-halign="center" data-align="left" data-sortable="true" data-field="name">Faculty Name</th>
                                <th scope='col' data-width="100" data-align="center" data-field="can-enroll">Can Enroll</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="row justify-content-end mt-3">
                    <div class="col-auto">
                        <a href="#" id='to-step-2' class="stepper-btn btn btn-success" onclick="stepper.next()">Next</a>
                    </div>
                </div>
            </div>
            <!-- STEP 1 END -->
            <!-- STEP 2 -->
            <div id="test-l-2" class="content">
                <div class="row card bg-light w-100 h-auto text-start mx-auto mt-3">
                    <h4 class='text-start p-0 fw-bold'>SET SECTIONS</h4>
                    <div class='row p-0'>
                        <div class="h-auto">
                            <form id='section-form' class="p-3 border border-2 mb-3" method="POST">
                                <div class="container mb-2">
                                    <div class="row p-0">
                                        <h5 class="fw-bold mt-2">Add Section</h5>
                                        <div class="form-row row">
                                            <div class='form-group col-md-6'>
                                                <label for="sect-code" class="col-lg-4 col-form-label fw-bold">Code</label>
                                                <input value="" type="text" name="code" class="form-control" id="sect-code" placeholder="Enter unique code">
                                            </div>
                                            <div class='form-group col-md-6'>
                                                <label for="section-name" class="col-lg-4 col-form-label fw-bold">Name</label>
                                                <input id='section-name' name="section-name" class='form-control' maxlength="50" placeholder="Enter section name" required>
                                            </div>
                                            
                                        </div>
                                        <div class="form-row row">
                                            <div class='form-group col-md-3'>
                                                <label for="max-no" class="col-lg-4  col-form-label fw-bold">Max</label>
                                                <input value="50" type="text" name="max-no" class="form-control number" id="max-no" placeholder="Enter maximum student no.">
                                            </div>
                                            <div class='form-group col-md-3'>
                                                <label for="grade-level" class="col-lg-4 col-form-label fw-bold">Level</label>
                                                <select id="grade-level" class='form-select' name='grade-level'>
                                                    <?php
                                                    $grade_level_list = ["11" => "11", "12" => "12"];
                                                    foreach ($grade_level_list as $id => $value) {
                                                        echo "<option value='$id'>$value</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class='form-group col-md-6'>
                                                <label for="section-name" class="col-lg-4 col-form-label fw-bold">Adviser (Opt.)</label>
                                                <input class='form-control' name='adviser' list='adviser-list' placeholder='Type to search ...'>
                                                <datalist id='adviser-list'>
                                                    <?php
                                                    $faculty_list = $admin->listFaculty();
                                                    foreach ($faculty_list as $faculty) {
                                                        $teacher_id = $faculty->get_teacher_id();
                                                        $teacher_name = $faculty->get_name();
                                                        echo "<option value='$teacher_id'>$teacher_id - $teacher_name</option>";
                                                    }
                                                    ?>
                                                </datalist>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-end">
                                        <div class="col-auto p-0">
                                            <input type="hidden" name="action" id="action" value="addSection" />
                                            <input type="submit" form="section-form" class="submit btn btn-success" value="Add Section" />
                                        </div>
                                    </div>
                                    <hr class='mt-4'>
                                </div>
                            </form>
                        </div>

                        <!--SECTION TABLE-->
                        <div class="h-auto">
                            <div class="container">
                                <h5 class="fw-bold">Current Sections</h5>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="flex-grow-1 me-3">
                                        <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
                                    </span>
                                    <div>
                                        <input id="s-table-clear-btn" type="reset" class="mb-0 me-1 btn btn-outline-dark btn-sm" value="Clear">
                                        <button id="remove-section-btn" class="btn btn-sm btn-danger">Delete Section</button>
                                    </div>
                                </div>

                                <div class="row">
                                    <table id="section-table" class="table-striped table-sm">
                                        <thead class='thead-dark'>
                                            <tr>
                                                <th data-checkbox="true"></th>
                                                <th scope='col' data-width="200" data-halign="center" data-align="left" data-sortable="true" data-field="code">Section Code</th>
                                                <th scope='col' data-width="400" data-halign="center" data-align="left" data-sortable="true" data-field="name">Section Name</th>
                                                <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="grd_level">Grade Level</th>
                                                <th scope='col' data-width="200" data-align="center" data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-row-reverse mt-4">
                    <input type="hidden" name="action" value="enroll">
                    <a href="#" id="to-step-3" class="stepper-btn btn btn-success" onclick="stepper.next()">Next</a>
                    <a href="#" class="stepper-btn btn btn-secondary me-1" onclick="stepper.previous()">Back</a>
                </div>
            </div>
        </div>
        <!--STEP 2 END-->
        <!--STEP 3-->
        <div id="test-l-3" class="content">
            <div class="card body w-100 h-auto p-4">
                <!-- STEP 1 -->
                <h4 class="fw-bold">Enable enrollment</h4>
                <div class="row mt-3">
                    <div class="col-lg-4 mb-3">
                        <h6>List of Faculties who can enroll <span id='faculty-count' class="badge bg-secondary rounded-pill"></span></h6>
                        <ul id="faculty-list" class="list-group h-75 overflow-auto">

                        </ul>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <h6>List of Sections <span id='section-count' class="badge bg-secondary rounded-pill"></span></h6>
                        <ul id="section-list" class="list-group h-75 overflow-auto">

                        </ul>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <h6>Enrollment status</h6>
                        <div class="container p-4 border border-2">
                            <div class='form-check form-switch my-auto'>
                                <?php
                                $enroll_opt = "Enrollment is " . ($_SESSION['enrollment']  ? "on-going" : "disabled");
                                echo "<input " . ($_SESSION['enrollment'] ? "checked" : "") . " name='enrollment' data-id='{$_SESSION['sy_id']}' class='form-check-input' "
                                    . "type='checkbox' title='Turn " . ($_SESSION['enrollment'] ? "off" : "on") . " enrollment'>"
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
                        <a href="#" class="stepper-btn btn btn-secondary me-1" onclick="stepper.previous()">Back</a>
                        <a href="enrollment.php" class="stepper-btn btn btn-success">Go to Enrollment Dashboard</a>
                    </div>
                </div>
        </div>
        <!--STEP 3 END-->
    </div>
    </div>
    <!-- STEPPER END -->
</form>