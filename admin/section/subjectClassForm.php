<?php
require_once("../class/Administration.php");
$admin = new Administration();
$faculty_options = $admin->listSubClassFacultyOptions();
$sections = $admin->listSection($_SESSION['sy_id']);
?>
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="../section.php?page=sub_classes">Subject Class</a></li>
            <li class="breadcrumb-item active">Add</a></li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between mb-3">
        <h3 class="fw-bold">Create subject class</h3>
    </div>
</header>
<div class="container w-75">
    <div class="card">
        <!-- SECTION LIST -->
        <div id="toolbar" class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold">NAME LIST</h5>
        </div>
        <hr class="mt-1 mb-4">
        <div class="d-flex flex-row-reverse mb-3">
            <button id="track-archive-btn" class="btn btn-secondary btn-sm"><i class="bi bi-archive me-2"></i>Archive</button>
        </div>
        <table id="table" data-toolbar="#toolbar" class="table-striped table-sm">
            <thead class='thead-dark track-table'>
                <tr>
                    <th data-checkbox="true"></th>
                    <th scope='col' data-width="100" data-align="center" data-field='prog_code'>Code</th>
                    <th scope='col' data-width="600" data-align="center" data-sortable="true" data-field="prog_desc">Program/Strand Description</th>
                    <th scope='col' data-width="300" data-align="center" data-field="action">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<script>
    let facultyOptions = <?php echo json_encode($faculty_options); ?>;
</script>
