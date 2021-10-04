<?php
require_once ("../class/Administration.php");
$admin = new Administration();
$faculty_options = $admin->listSubClassFacultyOptions();
?>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Subject Classes</li>
        </ol>
    </nav>
<!--    <div class="d-flex justify-content-between">-->
        <h2 class="fw-bold">Subject Classes</h2>
<!--        <span>-->
<!--            <button type="button" class="btn btn-secondary me-1"><i class="bi bi-archive me-2"></i>Archive Section</button>-->
<!--            <button class="btn btn-primary" title='Archive strand'><i class="bi bi-box-arrow-up-left me-2"></i>Export</button>-->
<!--        </span>-->
<!--    </div>-->
<!--    <hr class="my-2">-->
<!--    <h6 class="fw-bold">Section</h6>-->
</header>
<!-- HEADER END -->
<!-- FACULTY TABLE -->
<div class="container mt-1">
    <div class="card w-100 h-auto bg-light">
        <div class="d-flex justify-content-between mb-3">
            <!-- SEARCH BAR -->
            <span class="flex-grow-1 me-3">
                <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
            </span>
            <div>
                <button id="multiple-assign-opt" class="table-opt btn btn-primary btn-sm" title='Assign multiple subject class to a faculty'>Multiple Assign</button>
            </div>
        </div>
        <table id="table" class="table-striped table-sm">
            <thead class='thead-dark'>
                <tr>
                    <th data-checkbox="true"></th>
                    <th scope='col' data-width="80" data-align="center" data-field="sub_class_code">Sub Class Code</th>
                    <th scope='col' data-width="80" data-align="center"  data-field="section_code">Section Code</th>
                    <th scope='col' data-width="140" data-halign="center" data-align="left" data-sortable="true" data-field="section_name">Section Name</th>
                    <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="sub_name">Sub Name</th>
                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_level">Grade Level</th>
                    <th scope='col' data-width="200" data-halign="center" data-align="left" data-sortable="true" data-field="name">Faculty</th>
                    <th scope='col' data-width="100" data-align="center" data-field="action">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- MODAL -->
<div id="assign-modal" class="modal fade" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h4 class="mb-0">Assign Faculty</h4>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="sub-class-multi-form" action="action.php" method="POST">
                    <input type="hidden" name="action" value="assignSubClasses"/>
                    <p id="instruction" class="text-secondary"></p>
                    <select name="teacher_id" id="faculty-select">
                    </select>
                </form>
            </div>
            <div class="modal-footer">
                <button class="close btn btn-secondary close-btn btn-sm" data-bs-dismiss="modal">Cancel</button>
                <input type="submit" form="sub-class-multi-form" class="submit btn btn-success btn-sm" value="Assign">
            </div>
        </div>
    </div>
</div>
<!--MODAL END-->
<!-- TOAST -->
<div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
    <div id="toast-con" class="position-fixed d-flex flex-column-reverse " style="z-index: 99999; min-height: 50px; bottom: 20px; right: 25px;"></div>
</div>
<!-- TOAST END -->
<script>
    let facultyOptions = <?php echo json_encode($faculty_options); ?>;
</script>