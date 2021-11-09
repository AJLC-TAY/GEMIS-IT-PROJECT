<?php
require("../class/Administration.php");
//$admin = new Administration();
$filters =  (new Administration())->getEnrollFilters();

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
            <button type="button" class="view-archive btn btn-secondary"><i class="bi bi-eye me-2"></i>View Archived Enrollees</button>
            <a href="enrollment.php?page=form" id="add-btn" class="btn btn-success" title='Enroll a student' target="_blank"><i class="bi bi-plus me-2"></i>Enroll</a>
            <!-- <a href="faculty.php?state=add" id="add-btn" class="btn btn-success add-prog" title='Add new faculty'>ADD FACULTY</a> -->
        </div>
    </div>
</header>

<!-- ENROLLEES TABLE -->
<div class="container">
    <div class="card w-100 h-auto bg-light">
        <div class="d-flex justify-content-between mb-2">
            <!-- SEARCH BAR -->
            <span class="flex-grow-1 me-3">
                <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
            </span>
            <div>
                <button class="btn btn-primary btn-sm me-1" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse"><i class="bi bi-funnel me-2"></i>Filter</button>
                <button id="export-opt" type="submit" class="btn btn-dark btn-sm me-1" title='Export'><i class="bi bi-box-arrow-up-left me-2"></i>Export</button>
                <button id="track-archive-btn" class="btn btn-secondary btn-sm me-5"><i class="bi bi-archive me-2"></i>Archive</button>
                <button id="" class="refresh btn btn-outline-primary btn-sm me-3"><i class="bi bi-arrow-repeat me-2"></i>Refresh</button>
            </div>
            <div class="form-check form-switch my-1">
                <input class="form-check-input auto-refresh-switch" type="checkbox" title="Turn off auto refresh" data-status="on" checked>
                <span>Auto Refresh</span>
            </div>
        </div>
        <!--FILTER-->
        <div class="collapse" id="filterCollapse">
            <div class="card">
                <ul id="" class="row flex-wrap">
                    <li class="col-3 mb-3 me-2">
                        <div class="input-group input-group-sm">
                            <label class="input-group-text " for="sy">School Year</label>
                            <select class="filter-item form-select mb-0" id="sy">
                                <option value="*" selected>All</option>
                                <?php
                                foreach ($filters['school_year'] as $id => $value) {
                                    echo "<option value='$id' >$value</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </li>
                    <!--                    <div class="dropdown">-->
                    <!--                        <button class="btn btn-sm shadow" type="button" id="section-filter" data-bs-toggle="dropdown" aria-expanded="false">-->
                    <!--                            School Year-->
                    <!--                        </button>-->
                    <!--                        <ul class="dropdown-menu" aria-labelledby="section-filter">-->
                    <!--                            <li><a role="button" href="#" id="all-section-btn" class="dropdown-item">All</a></li>-->
                    <!--                            <li><hr class="dropdown-divider"></li>-->
                    <!--                            <li><a role="button" id="no-adv-btn" class="dropdown-item active">Current</a></li>-->


                    <!--                            <li><a role="button" id="with-adv-btn" class="dropdown-item">With Adviser</a></li>-->
                    <!--                            <li><hr class="dropdown-divider"></li>-->
                    <!--                        </ul>-->
                    <!--                    </div>-->
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
                    <th scope='col' data-width="100" data-align="center" data-sortable="false" data-field="SY">SY</th>
                    <th scope='col' data-width="100" data-halign="center" data-align="center" data-sortable="false" data-field="LRN">LRN</th>
                    <th scope='col' data-width="300" data-halign="center" data-align="center" data-sortable="true" data-field="name">Name</th>
                    <th scope='col' data-width="85" data-align="center" data-sortable="true" data-field="enroll-date">Enrollment Date</th>
                    <th scope='col' data-width="50" data-align="center" data-sortable="true" data-field="grade-level">Level</th>
                    <th scope='col' data-width="75" data-align="center" data-sortable="true" data-field="curriculum">Curriculum</th>
                    <th scope='col' data-width="75" data-align="center" data-sortable="true" data-field="status">Status</th>
                    <th scope='col' data-width="100" data-align="center" data-field="action">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- ENROLLMENT TABLE END-->