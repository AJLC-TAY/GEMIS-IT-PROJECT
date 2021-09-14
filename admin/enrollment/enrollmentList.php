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
        <table id="table" class="table-striped table-sm">
            <thead class='thead-dark'>
            <div class="row justify-content-between mb-3">
                <!-- SEARCH BAR -->
                <span class="flex-grow-1 me-3">
                    <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
                </span>
                <div>
                    <button id = "subject-archive-btn" class="btn btn-secondary btn-sm"><i class="bi bi-archive me-2"></i>Archive</button>
                    <button id="export-opt" type="submit" class="btn btn-dark btn-sm" title='Export'><i class="bi bi-box-arrow-up-left me-2"></i>Export</button>
                </div>
            </div>

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