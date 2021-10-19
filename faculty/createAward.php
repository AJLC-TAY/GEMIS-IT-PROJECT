<?php
session_start();
require_once("sessionHandling.php");
include_once("../inc/head.html");
include_once("../class/Faculty.php");
$filters =  (new FacultyModule())->getEnrollFilters();
?>

<title>Create Award | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet' />
</head>

<body>
    <!-- SPINNER -->
    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/facultySidebar.php'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">

                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item active">Create Award</a></li>
                                    </ol>
                                </nav>
                            </header>
                            <div class="d-flex justify-content-between">
                                <h3 class="fw-bold">Create Award</h3>
                            </div>

                            <div class="container mt-2">
                                <div class="card w-100 h-auto p-3">
                                    <h5>Award Details</h5>
                                    <hr class='mt-2 mb-4'>
                                    <form id='program-view-form' class="needs-validation" method="POST" novalidate>
                                        <input type="hidden" name="action" value="updateProgram">
                                        <div class="form-group row">
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Description</label>
                                            <div class="col-xl-10 col-lg-9">
                                                <input type="hidden" name="current_code" value="">
                                                <input class='form-input form-control' type='text' value=''>
                                            </div>
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Award Type</label>
                                            <div class='col-xl-10 col-lg-9'>
                                                <input class='form-input form-control'></input>
                                            </div>
                                        </div>
                                    </form>
                                    <hr>
                                    <div class="">
                                        <h6><b>ADD STUDENTS</b></h6>
                                        <table id="table" class="table-striped table-sm">
                                            <thead class='thead-dark'>
                                                <div class="d-flex justify-content-between">
                                                    <!-- SEARCH BAR -->
                                                    <span class="flex-grow-1 me-3">
                                                        <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search here...">
                                                    </span>
                                                    <div>
                                                        <button class="btn btn-outline-primary btn-sm me-1" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse"><i class="bi bi-funnel me-2"></i>Filter</button>
                                                    </div>
                                                </div>
                                                <!-- FILTERS -->
                                                <div class="collapse mb-2" id="filterCollapse">
                                                    <div class="card">
                                                        <ul id="" class="row flex-wrap">
                                                            <!--STRAND FILTER-->
                                                            <li class="col-md-4 mb-3 me-2">
                                                                <div class="input-group input-group-sm">
                                                                    <label class="input-group-text " for="strands">Strand</label>
                                                                    <select class="form-select mb-0 filter-item " id="strands">
                                                                        <option value="*" selected>All</option>
                                                                        <?php
                                                                        foreach ($filters['programs'] as $id => $value) {
                                                                            echo "<option value='$id' >$value</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                            <!--YEAR LEVEL FILTER-->
                                                            <li class="col-md-3 me-2">
                                                                <div class="input-group input-group-sm ">
                                                                    <label class="input-group-text " for="year-level">Year Level</label>
                                                                    <select class="form-select mb-0 filter-item " id="year-level">
                                                                        <option value="*" selected>All</option>
                                                                        <?php
                                                                        foreach ($filters['year_level'] as $value) {
                                                                            echo "<option value='$value' >$value</option>";
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                            <!--SECTION FILTER-->
                                                            <li class="col-md-4 me-2">
                                                                <div class="input-group input-group-sm ">
                                                                    <label class="input-group-text " for="section">Section</label>
                                                                    <select class="form-select mb-0 filter-item " id="section">
                                                                        <option value="*" selected>All</option>
                                                                        <?php 
                                                                            foreach ($filters['section'] as $id => $value) {
                                                                                echo "<option value='$id' >$value</option>";
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                            <div class="d-flex justify-content-end me-5">
                                                                <button type="button" class="reset-filter-btn btn btn-outline-danger btn-sm me-5"><i class="bi bi-x-circle me-2"></i>Reset All</button>
                                                            </div>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <!-- TABLE -->
                                                <tr>
                                                    <th data-checkbox="true"></th>
                                                    <!-- <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="lrn">LRN</th> -->
                                                    <th scope='col' data-width="450" data-align="left" data-sortable="true" data-field="name">Student Name</th>
                                                    <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="grd">Yr Level</th>
                                                    <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="prog_code">Strand</th>
                                                    <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="section_name">Section</th>
                                                    <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="action">Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <hr>                                        
                                    <div Class='mt-3'>
                                        <h6><b>SELECTED STUDENTS</b></h6>
                                        <table id="student-selection" class="table-striped table-sm">
                                            <thead class='thead-dark'>
                                                <!-- TABLE -->
                                                <tr>
                                                    <th data-checkbox="true"></th>
                                                    <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="lrn">LRN</th>
                                                    <th scope='col' data-width="450" data-align="left" data-sortable="true" data-field="name">Student Name</th>
                                                    <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="grd">Yr Level</th>
                                                    <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="prog_code">Strand</th>
                                                    <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="section_name">Section</th>
                                                    <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="action">Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-end">
                                        <button id='role-cancel-btn' class='btn btn-outline-secondary me-1'>Cancel</button>
                                        <button id='role-save-btn' class='btn  btn-success'>Save</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- FOOTER START -->
                        <?php include_once("../inc/footer.html"); ?>
                        <!-- FOOTER END -->
                    </div>
            </section>
        </section>
    </section>
    <!-- MAIN CONTENT END -->
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->



    <!--BOOTSTRAP TABLE JS-->
    <script src='../assets/js/bootstrap-table.min.js'></script>
    <script src='../assets/js/bootstrap-table-en-US.min.js'></script>
    <!--CUSTOM JS-->
    <script src="../js/common-custom.js"></script>
    <script type='module' src='../js/admin/award.js'></script>
</body>

</html>