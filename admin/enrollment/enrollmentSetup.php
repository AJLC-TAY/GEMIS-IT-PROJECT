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

<form id="enrollment-setup" enctype="multipart/form-data" action="action.php" method="POST">
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
                    <h4 class="fw-bold">Faculty Enrollment Privileges</h4>
                    <div class="row justify-content-between mb-3">
                        <div class="col-md-7 d-flex">
                            <form>
                                <input id="faculty-search-input" type="search" class="form-control mb-0 me-2" placeholder="Search something here ...">
                                <div class="d-flex">
                                    <input id="f-table-clear-btn" type="reset" class="form-control mb-0 me-1 btn-dark" value="Clear"/>
                                    <div class="dropdown">
                                        <button class="btn shadow" type="button" id="faculty-filter" data-bs-toggle="dropdown" aria-expanded="false">
                                            Filter
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="faculty-filter">
                                            <li><a data-value='*' class="filter-item dropdown-item active">All</a></li>
                                            <li><a data-value='1' class="filter-item dropdown-item">Can enroll</a></li>
                                            <li><a data-value='0' class="filter-item dropdown-item">Cannot enroll</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-5 d-flex justify-content-end">
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
                    <div class="row justify-content-end mt-3">
                        <div class="col-auto">
                            <a href="#" id='to-step-2' class="stepper-btn btn btn-primary" onclick="stepper.next()">Next</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- STEP 1 END -->
            <!-- STEP 2 -->
            <div id="test-l-2" class="content">
                <div class="card w-100 h-auto mt-4 p-4">
                    <h4 class="fw-bold">Set Sections</h4>
                    <div class="container px-0 mt-2">
                        <div class="row">
                            <div class="h-auto col-lg-4">
                                <form id='section-form' class="p-3 border border-2 mb-3" method="POST">
                                    <div class="container mb-2">
                                        <div class="row"><h5 class="p-0">Add Section</h5></div>
                                        <div class="form-row row">
                                            <label for="sect-code" class="col-lg-4 col-form-label">Code</label>
                                            <div class="col-lg-8">
                                                <input value="" type="text" name="code" class="form-control" id="sect-code" placeholder="Enter unique code">
                                            </div>
                                        </div>
                                        <div class="form-row row">
                                            <label for="section-name" class="col-lg-4 col-form-label">Name</label>
                                            <div class="col-lg-8">
                                                <input id='section-name'name="section-name" class='form-control' maxlength="50" placeholder="Enter section name" required>
                                            </div>
                                        </div>
                                        <div class="form-row row">
                                            <label for="grade-level" class="col-lg-4 col-form-label">Level</label>
                                            <div class="col-lg-8">
                                                <select id="grade-level" class='form-select' name='grade-level'>
                                                    <?php
                                                    $grade_level_list = ["11" => "11", "12" => "12"];
                                                    foreach($grade_level_list as $id => $value) {
                                                        echo "<option value='$id'>$value</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="form-row row">
                                            <label for="max-no" class="col-lg-4  col-form-label">Max</label>
                                            <div class="col-lg-8">
                                                <input value="50" type="text" name="max-no" class="form-control number" id="max-no" placeholder="Enter maximum student no.">
                                            </div>
                                        </div>
                                        <div class="form-row row">
                                            <label for="section-name" class="col-lg-4 col-form-label">Adviser (Opt.)</label>
                                            <div class="col-lg-8">
                                                <input class='form-control' name='adviser' list='adviser-list' placeholder='Type to search ...'>
                                                <datalist id='adviser-list'>
                                                    <?php
                                                    $faculty_list = $admin->listFaculty();
                                                    foreach($faculty_list as $faculty) {
                                                        $teacher_id = $faculty->get_teacher_id();
                                                        $teacher_name = $faculty->get_name();
                                                        echo "<option value='$teacher_id'>$teacher_id - $teacher_name</option>";
                                                    }
                                                    ?>
                                                </datalist>
                                            </div>
                                        </div>
                                        <div class="row justify-content-end">
                                            <div class="col-auto p-0">
                                                <input type="hidden" name="action" id="action" value="addSection" />
                                                <input type="submit" form="section-form" class="submit btn btn-success btn-sm" value="Add Section" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!--SECTION TABLE-->
                            <div class="h-auto col-lg-8">
                                <div class="container">
                                    <h5>Current Sections</h5>
                                    <form>
                                        <div class="row align-content-center">
                                            <div class="col-10"><input id="section-search-input" class='form-control mb-0' type="search" placeholder="Search section here ..."></div>
                                            <div class="col-2"><input id="s-table-clear-btn" type="reset" class="form-control mb-0 me-1 btn-dark" value="Clear"/></div>
                                        </div>
                                    </form>
                                    <div class="my-3 row">
                                        <div class="col-auto"><button id="remove-section-btn" class="btn btn-sm btn-danger">Delete Section</button></div>
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
                            <a href="#" id="to-step-3" class="stepper-btn btn btn-primary" onclick="stepper.next()">Next</a>
                            <a href="#" class="stepper-btn btn btn-primary me-1" onclick="stepper.previous()">Back</a>
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
                                    $enroll_opt = "Enrollment is ".($_SESSION['enrollment']  ? "on-going" : "disabled");
                                    echo "<input ". ($_SESSION['enrollment'] ? "checked" : ""). " name='enrollment' data-id='{$_SESSION['sy_id']}' class='form-check-input' "
                                            ."type='checkbox' title='Turn ". ($_SESSION['enrollment'] ? "off" : "on")." enrollment'>"
                                            ."<span class='status'>$enroll_opt</span>";
                                    ?>
                                </div>
                                <p class="mt-3 text-secondary"><small>Enabling / Disabling enrollment will automatically start or stop accepting enrollees.</small></p>
                            </div>

                        </div>
                    </div>
                    <div class="row justify-content-end mt-3">
                        <div class="col-auto">
                            <a href="#" class="stepper-btn btn btn-primary me-1" onclick="stepper.previous()">Back</a>
                            <a href="enrollment.php" class="stepper-btn btn btn-primary" >Go to Enrollment Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--STEP 3 END-->
        </div>
    </div>
    <!-- STEPPER END -->
</form>