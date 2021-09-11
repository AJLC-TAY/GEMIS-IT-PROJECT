<?php require_once("../class/Administration.php");
$admin = new Administration();
?>
<!-- HEADER -->
<header>
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

        </div>
        <div class="bs-stepper-content">
            <div id="test-l-1" class="content">
                <div class="card body w-100 h-auto p-4">
                    <!-- STEP 1 -->
                    <h4 class="fw-bold">Faculty Enrollment Privileges</h4>
                    <div class="d-flex justify-content-between mb-3">
                        <!-- SEARCH BAR -->
                        <span class="flex-grow-1 me-5">
                            <input id="search-input" type="search" class="form-control" placeholder="Search something here">
                        </span>
                        <div>
                            <button class="btn btn-secondary" title=''>Grant Enrollment Access</button>
                        </div>

                    </div>
                    <table id="table" class="table-striped">
                        <thead class='thead-dark'>
                            <tr>
                                <th data-checkbox="true"></th>
                                <th scope='col' data-width="600" data-align="left" data-sortable="true" data-field="sub_name">Faculty Name</th>
                                <th scope='col' data-width="100" data-align="center" data-field="sub_type">Can Enroll</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="row justify-content-end mt-3">
                        <div class="col-auto">
                            <a href="#stepper" class="btn btn-primary" onclick="stepper.next()">Next</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- STEP 1 END -->
            <!-- STEP 2 -->
            <div id="test-l-2" class="content">
                <div class="card w-100 h-auto mt-4 p-4">
                    <h4 class="fw-bold">ENROLLMENT SET UP 2: SECTIONS</h4>
                    <div class="card w-80 h-auto bg-light">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="col-form-label">Section Name</label>
                                <select class="form-select" name="program" id="program-select">
                                    <?php
                                    $programs = $admin->listPrograms('program');
                                    foreach($programs as $program) {
                                        echo "<option value='{$program->get_prog_code()}'>{$program->get_prog_desc()}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class='col-md-5'>
                                 <label for="grade-select" class="col-form-label">Grade Level</label>
                                <select class="form-select" name="grade-level" id="grade-select">
                                    <?php
                                    $grade_level = ["11" => 11, "12" => 12];
                                    foreach($grade_level as $id => $value) {
                                        echo "<option value='$id'>$value</option>";                                                          }
                                    ?>
                                </select>

                            </div>

                            <div class='col-md-3'>
                                <label class="col-form-label">Strand</label>
                                <input class="form-control" name="strand" type="text" value = "" >
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <!-- SEARCH BAR -->
                        <span class="flex-grow-1 me-5">
                            <input id="search-input" type="search" class="form-control" placeholder="Search something here">
                        </span>
                        <div>
                            <button class="btn btn-secondary" title=''>Remove Section</button>
                        </div>

                    </div>
                    <table id="table" class="table-striped">
                        <thead class='thead-dark'>
                            <tr>
                                <th data-checkbox="true"></th>
                                <th scope='col' data-width="500" data-align="center" data-sortable="true" data-field="section-name">Section Name</th>
                                <th scope='col' data-width="300" data-align="center" >Action</th>
                            </tr>
                        </thead>
                    </table>
                        <div class="d-flex justify-content-between flex-row-reverse mt-4">
                            <input type="hidden" name="action" value="enroll">
                            <input class="btn btn-success" form="enrollment-form" type="submit" value="Submit">
                            <a href="#stepper" class="btn btn-primary" onclick="stepper.previous()">Back</a>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <!-- STEPPER END -->
</form>