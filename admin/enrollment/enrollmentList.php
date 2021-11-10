<?php
require("../class/Administration.php");
//$admin = new Administration();
$filters =  (new Administration())->getEnrollFilters();
$archived_btn = '';
$table_opts = '';
$sy_filter = '';
if ($_SESSION['user_type'] == 'AD') {
    $archived_btn = "<button type='button' class='view-archive btn btn-secondary'><i class='bi bi-eye me-2'></i>View Archived Enrollees</button>";

    $table_opts = "<button id='delete-opt' class='table-opt btn btn-danger btn-sm  my-1' title='Delete'><i class='bi bi-trash me-2'></i>Delete</button>
                <button id='export-opt' class='table-opt btn btn-dark btn-sm  my-1' title='Export'><i class='bi bi-box-arrow-up-left me-2'></i>Export</button>
                <button id='archive-opt' class='table-opt btn btn-secondary btn-sm my-1'><i class='bi bi-archive me-2'></i>Archive</button>";

    $sy_filter = "<li class='col-3 mb-3 me-2'>
                        <div class='input-group input-group-sm'>
                            <label class='input-group-text ' for='sy'>School Year</label>
                            <select class='filter-item form-select mb-0' id='sy'>
                                <option value='*' selected>All</option>";
                                foreach ($filters['school_year'] as $id => $value) {
                                    $sy_filter .= "<option value='$id' >$value</option>";
                                }
                $sy_filter.= "</select>
                        </div>
                    </li>";
                                
    
}
?>
<!DOCTYPE html>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="enrollment.php">Enrollment</a></li>
            <li class="breadcrumb-item active" aria-current="page">Enrollees</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between mb-3">
        <h3 class="fw-bold">Enrollees</h3>
        <div>
            <?php echo $archived_btn; ?>
            <a href="enrollment.php?page=form" id="add-btn" class="btn btn-success" title='Enroll a student' target="_blank"><i class="bi bi-plus me-2"></i>Enroll</a>
        </div>
    </div>
</header>

<!-- ENROLLEES TABLE -->
<div class="container">
    <div class="card w-100 h-auto bg-light">
        <div class="row justify-content-between mb-3">
            <!-- SEARCH BAR -->
            <div class="col-lg-4 row">
                <div class="col-8">
                    <input id="search-input" type="search" class="form-control form-control-sm m-1" placeholder="Search something here">
                </div>
                <div class="col-4">
                    <button class="btn btn-primary btn-sm m-1" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse"><i class="bi bi-funnel me-2"></i>Filter</button>
                </div>
            </div>
            <div class="col-lg-4" >
                <?php echo $table_opts; ?>
            </div>
            <div class="col-lg-4 row">
                <div class="col-auto">
                    <button id="" class="refresh btn btn-outline-primary btn-sm m-1"><i class="bi bi-arrow-repeat me-2"></i>Refresh</button>
                </div>
                <div class="col-auto">
                    <div class="form-check form-switch my-2">
                        <input class="form-check-input auto-refresh-switch" type="checkbox" title="Turn off auto refresh" data-status="on" checked>
                        <span>Auto Refresh</span>
                    </div>
                </div>
            </div>
        </div>
        <!--FILTER-->
        <div class="collapse" id="filterCollapse">
            <div class="card">
                <ul id="" class="row flex-wrap">
                    <?php echo $sy_filter; ?>
                    <!--TRACK FILTER-->
                    <li class="col-4 mb-3 me-2">
                        <div class="input-group input-group-sm">
                            <label class="input-group-text " for="tracks">Track</label>
                            <select class="form-select mb-0 filter-item " id="tracks">
                                <option value="*" selected>All</option>
                                <?php
                                foreach ($filters['tracks'] as $id => $value) {
                                    echo "<option value='$id' >$value</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </li>
                    <!--STRAND FILTER-->
                    <li class="col-4 mb-3 me-2">
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
                    <!--STATUS FILTER-->
                    <li class="col-3 me-2">
                        <div class="input-group input-group-sm ">
                            <label class="input-group-text " for="status">Status</label>
                            <select class="form-select mb-0 filter-item " id="status">
                                <option value="*" selected>All</option>
                                <?php
                                foreach ($filters['status'] as $id => $value) {
                                    echo "<option value='$id' >$value</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </li>
                    <!--AUTO REFRESH SWITCH-->
                    <!--                            <div class="col-auto  mb-2 me-0 ms-3 ">-->
                    <!--                                <div class="input-group input-group-sm ">-->
                    <!--                                    <div class="form-check form-switch">-->
                    <!--                                        <input class="form-check-input auto-refresh" name="auto-refresh" type="checkbox" id="auto-refresh-table" checked>-->
                    <!--                                        <label class="form-check-label" for="auto-refresh-table">Auto Refresh Table</label>-->
                    <!--                                    </div>-->
                    <!--                                </div>-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <div class="d-flex justify-content-end">
                        <button type="button" class="reset-filter-btn btn btn-outline-danger btn-sm me-5"><i class="bi bi-x-circle me-2"></i>Reset All</button>
                    </div>
                </ul>
            </div>
        </div>
        <div class="buttons-toolbar">
            
        </div>
        <table id="table" class="table-striped">
            <thead class='thead-dark'>
                <tr>
                    <th data-checkbox="true"></th>
                    <th scope='col' data-width="90" data-align="center" data-sortable="false" data-field="SY">SY</th>
                    <th scope='col' data-width="100" data-halign="center" data-align="center" data-sortable="false" data-field="LRN">LRN</th>
                    <th scope='col' data-width="235" data-halign="center" data-align="center" data-sortable="true" data-field="name">Name</th>
                    <th scope='col' data-width="85" data-align="center" data-sortable="true" data-field="enroll-date">Enrollment Date</th>
                    <th scope='col' data-width="50" data-align="center" data-sortable="true" data-field="grade-level">Level</th>
                    <th scope='col' data-width="75" data-align="center" data-sortable="true" data-field="curriculum">Curriculum</th>
                    <th scope='col' data-width="75" data-align="center" data-sortable="true" data-field="section">Section</th>
                    <th scope='col' data-width="75" data-align="center" data-sortable="true" data-field="status">Status</th>
                    <th scope='col' data-width="100" data-align="center" data-field="action">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<!-- ENROLLMENT TABLE END-->
<div class="modal fade" id="delete-student-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="delete-form" method="POST">
            <input type="hidden" name="action" value="deleteStudent">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Delete Confirmation</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Delete <span id="student-count"></span> selected student/s?
                    <ul id="student-selected" class="list-group mt-3 overflow-auto" style="max-height: 300px"></ul>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-dark close-btn btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <input type="submit" form="delete-form" class="submit btn btn-success btn-sm" value="Delete"/>
                </div>
            </div>
        </form>
    </div>
</div>