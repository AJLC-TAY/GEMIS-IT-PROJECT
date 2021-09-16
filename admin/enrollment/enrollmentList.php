<?php
require ("../class/Administration.php");
//$admin = new Administration();
$filters =  (new Administration())->getEnrollFilters();

?>
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
            <button type="button" class="view-archive btn btn-secondary btn-sm"><i class="bi bi-eye me-2"></i>View Archived Enrollees</button>
            <a href="enrollment.php?page=form" id="add-btn" class="btn btn-success btn-sm" title='Enroll a student' target="_blank"><i class="bi bi-plus me-2"></i>Enroll</a>
            <!-- <a href="faculty.php?state=add" id="add-btn" class="btn btn-success add-prog" title='Add new faculty'>ADD FACULTY</a> -->
        </div>
    </div>
</header>

<!-- ENROLLEES TABLE -->
<div class="container mt-1">
    <div class="card w-100 h-auto bg-light">
        <div id="toolbar" class="row justify-content-between mb-3">
            <!-- SEARCH BAR -->
            <div class="row align-content-center me-3">
                <div class="col-8">
                    <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
                </div>
                <!--SCHOOL YEAR FILTER-->
                <div class="col-4">
                    <div class="col-auto me-0 mb-2">
                        <div class="input-group input-group-sm">
                            <label class="input-group-text " for="sy">School Year</label>
                            <select class="filter-item form-select mb-0" id="sy">
                                <option value="*" selected>All</option>
                                <?php
                                foreach($filters['school_year'] as $id => $value) {
                                    echo "<option value='$id' >$value</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
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
                </div>
            </div>
            <div class="row align-content-center">
                <div class="col-auto">
                    <button id="subject-archive-btn" class="btn btn-secondary btn-sm"><i class="bi bi-archive me-2"></i>Archive</button>
                    <button id="export-opt" type="submit" class="btn btn-dark btn-sm" title='Export'><i class="bi bi-box-arrow-up-left me-2"></i>Export</button>
                </div>

                    <div class="col d-inline-flex flex-wrap justify-content-end container">
<!--                        <div class="">-->
                            <!--TRACK FILTER-->

                            <div class="col-auto me-0 mb-2">
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text " for="tracks">Track</label>
                                    <select class="form-select mb-0 filter-item " id="tracks">
                                        <option value="*" selected>All</option>
                                        <?php
                                        foreach($filters['tracks'] as $id => $value) {
                                            echo "<option value='$id' >$value</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!--STRAND FILTER-->

                            <div class="col-auto  mb-2 me-0 ms-3">
                                <div class="input-group input-group-sm">
                                    <label class="input-group-text " for="strands">Strand</label>
                                    <select class="form-select mb-0 filter-item " id="strands">
                                        <option value="*" selected>All</option>
                                        <?php
                                        foreach($filters['programs'] as $id => $value) {
                                            echo "<option value='$id' >$value</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <!--YEAR LEVEL FILTER-->
                            <div class="col-auto  mb-2 me-0 ms-3 ">
                                <div class="input-group input-group-sm ">
                                    <label class="input-group-text " for="year-level">Year Level</label>
                                    <select class="form-select mb-0 filter-item " id="year-level">
                                        <option value="*" selected>All</option>
                                        <?php
                                        foreach($filters['year_level'] as $value) {
                                            echo "<option value='$value' >$value</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!--STATUS FILTER-->
                            <div class="col-auto  mb-2 me-0 ms-3 ">
                                <div class="input-group input-group-sm ">
                                    <label class="input-group-text " for="status">Status</label>
                                    <select class="form-select mb-0 filter-item " id="status">
                                        <option value="*" selected>All</option>
                                        <?php
                                        foreach($filters['status'] as $id => $value) {
                                            echo "<option value='$id' >$value</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
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
                    </div>


            </div>
        </div>

        <table id="table"
               data-search="true"
               data-pagination="true"
               data-auto-refresh="true"
               data-show-auto-refresh="false"
               data-auto-refresh-interval = "10"
               data-search-selector="#search-input"
               data-url="getAction.php?data=enrollees"
               data-side-pagination="server"
               data-unique-id="LRN"
               data-id-field="LRN"
               data-height="450"
               data-maintain-meta-dat="true"
               data-click-to-select="true"
               data-query-params="queryParams"
               data-page-size="25"
               data-page-list="[25, 50, 100, All]"
               data-toggle="#toolbar"
               data-pagination-parts="['pageInfoShort', 'pageSize', 'pageList']"
               class="table-striped table-sm">
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