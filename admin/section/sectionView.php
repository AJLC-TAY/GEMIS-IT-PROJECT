<?php
require_once("../class/Administration.php");
$admin = new Administration();
$section = $admin->getSection();
$sect_code = $section->get_code();
$sect_name = $section->get_name();
$sect_grd_level = $section->get_grd_level();
$sect_max_no = $section->get_max_stud();
$sect_stud_no = $section->get_stud_no();
$sect_adviser = $section->get_teacher_id();
$school_year = $section->get_sy_desc();
$sy_id = $section->get_sy();

$program_list = $admin->listPrograms("program");

$NONE = "d-none";
$state = "disabled";
$edit_btn_state = "";
$display = $NONE;
$input_display = "";
$none_when_edit = "";
if (isset($_GET['action']) && $_GET['action'] == 'edit') {
    $state = '';
    $edit_btn_state = "disabled";
    $display = "";
    $none_when_edit = $NONE;
}
?>
<script>
    let activeFacultyList = <?php echo json_encode($admin->listFaculty()); ?>;
</script>

<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="section.php">Section</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $sect_name; ?></li>
        </ol>
    </nav>
    <div class="row justify-content-between">
        <div class="col-md-5">
            <h2 class="fw-bold"><?php echo $sect_name; ?></h2>
        </div>
        <div class="col-md-7 d-flex justify-content-lg-end">
            <button data-code='<?php echo $sect_code; ?>' class='btn btn-primary edit-sub-class m-1' title='Subject classes'><i class='bi bi-pencil-square me-2'></i>Subject Class</button>
            <a href="section.php?sec_code=<?php echo $sect_code; ?>&action=export" class="btn btn-outline-dark m-1" title='Export section'><i class="bi bi-box-arrow-up-left me-2"></i>Export</a>
        </div>
    </div>
    <hr class="my-2">
    <h5 class="fw-bold">Section</h5>
</header>
<!-- HEADER END -->
<!-- INFORMATION -->
<div class="container">
    <div class='card'>
        <div class="d-flex justify-content-between">
            <h5>Information</h5>
            <div class="btn-con my-a">
                <button id='edit-btn' data-current-adviser='<?php echo $sect_adviser['teacher_id'];?>' class='btn link btn-sm <?php echo $none_when_edit; ?>'><i class="bi bi-pencil-square me-2"></i>Edit</button>
                <div class='edit-opt <?php echo $display; ?>'>
                    <a href='section.php?sec_code=<?php echo $sect_code; ?>' class="btn btn-secondary btn-sm me-1">Cancel</a>
                    <input type="submit" form="section-edit-form" class="btn btn-success btn-sm" value="Save">
                </div>
            </div>
        </div>
        <hr class='mt-2 mb-4'>
        <section class="w-100">
            <form id='section-edit-form' method='POST'>
                <div class="ps-3 row w-100">
                    <div class="col-md-5">
                        <div class="row">
                            <label for="code" class="col-sm-4 fw-bold">Code</label>
                            <div class="col-sm-8">
                                <p id="code"><?php echo $sect_code; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <label for="grd-level" class="col-sm-4  fw-bold">Grade Level</label>
                            <div class="col-sm-8">
                                <p class="grd-level"><?php echo $sect_grd_level; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <label for="no-of-stud" class="col-sm-4 fw-bold">No of Students</label>
                            <div class="col-sm-8">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <p id="no-of-stud" class="m-0"><?php echo $sect_stud_no; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label for="sy" class="col-sm-4 fw-bold">School Year</label>
                            <div class="col-sm-8">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <p id="sy" class="m-0"><?php echo $school_year; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="container">
                            <div class="row align-content-center">
                                <label for="sect-name" class="col-form-label col-sm-4  fw-bold">Section Name</label>
                                <div class="col-sm-8">
                                    <input class="form-control form-control-sm" name="sect-name" id="sect-name" value="<?php echo $sect_name; ?>" <?php echo $state; ?> />
                                </div>
                            </div>
                            <div class="row align-content-center">
                                <label for="sect-max-no" class="col-form-label col-sm-4   fw-bold">Max Student No.</label>
                                <div class="col-sm-8">
                                    <input class="form-control form-control-sm" name="max-no" id="sect-max-no" value="<?php echo $sect_max_no; ?>" <?php echo $state; ?> />
                                </div>
                            </div>
                            <div class="row align-content-center">
                                <label for="adviser" class="col-form-label col-sm-4   fw-bold">Class Adviser</label>
                                <div class="col-sm-8 py-2">
                                    <?php
                                    $teacher_id = "";
                                    $adviser_name = "";
                                    $none_when_adv_exist = "";
                                    if ($sect_adviser) {
                                        $teacher_id = $sect_adviser['teacher_id'];
                                        $adviser_name = "Teacher {$sect_adviser['name']}";
                                        $none_when_adv_exist = $NONE;
                                    }
                                    echo "<a class='link $none_when_edit ' target='_blank' href='faculty.php?id=$teacher_id'>$adviser_name</a>";
                                    echo "<p id='empty-msg' class='m-0 $none_when_edit $none_when_adv_exist'>No adviser set</p>";
                                    ?>
                                    <div class="row edit-opt <?php echo $display; ?>">
                                        <div class='w-100 mb-2 '>
                                            <select name="adviser" id="adviser-section" class="form-select">
                                                <option value="">-- Select faculty --</option>
                                                <?php
                                                $faculty_list = $admin->listNotAdvisers($teacher_id);
                                                foreach($faculty_list as $e) {
                                                    $faculty_id = $e['teacher_id'];
                                                    $teacher_name = $e['name'];
                                                    echo "<option ". ($teacher_id == $faculty_id ? "selected" : "") ." value='$faculty_id'>T. $teacher_name</option>";
                                                }
                                                ?>
                                            </select>
                                            <button id='adviser-clear-btn' class='btn btn-outline-danger edit-opt <?php echo $display; ?> btn-sm w-auto mt-1'><i class="bi bi-dash-circle me-2"></i> Unassign</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="section" value="<?php echo $sect_code; ?>">
                <input type="hidden" name="action" value="editSection">
            </form>
        </section>
    </div>
</div>
<!-- INFORMATION END -->
<!-- STUDENT LIST TABLE -->
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="my-auto">
            <h5 class='m-0 fw-bold'>Student List</h5>
        </span>
        <span><button id="add-student" data-grade-level="<?php echo $sect_grd_level; ?>"  data-sy-id="<?php echo $sy_id; ?>" class="btn btn-success btn"><i class="bi bi-plus bi-plus-lg me-2"></i>Add Student</button></span>
    </div>
    <div class="card w-100 h-auto bg-light">
        <form action="">
            <div class="d-flex justify-content-between mb-3">
                <!-- SEARCH BAR -->
                <span class="flex-grow-1 me-2">
                    <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
                </span>
                <div>
                    <button id="transfer-btn" class="btn btn-secondary btn-sm" data-section="<?php echo $sect_code; ?>" data-grade-level="<?php echo $sect_grd_level; ?>"  data-sy-id="<?php echo $sy_id; ?>"  title='Transfer student to another section'><i class="bi bi-arrow-left-right me-2"></i>Transfer student</button>
                </div>
            </div>
        </form>
        <table id="table" class="table-striped table-sm">
            <thead class='thead-dark'>
                <tr>
                    <th data-checkbox="true"></th>
                    <th scope='col' data-width="200" data-align="center" data-field="lrn">LRN</th>
                    <th scope='col' data-width="300" data-halign="center" data-align="left" data-sortable="true" data-field="name">Student Name</th>
                    <th scope='col' data-width="400" data-align="center" data-sortable="true" data-field="program">Strand</th>
                    <th scope='col' data-width="100" data-align="center" data-field="action">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<!-- STUDENT TABLE END -->
<!-- ADVISER HISTORY -->
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="my-auto">
            <h6 class='m-0 fw-bold'>Adviser History</h6>
        </span>
    </div>
    <div class="card w-100 h-auto bg-light">
        <form action="">
            <div class="d-flex justify-content-between mb-3">
                <!-- SEARCH BAR -->
                <span class="flex-grow-1 me-3">
                    <input id="search-adviser-input" type="search" class="form-control form-control-sm" placeholder="Search subject here">
                </span>
            </div>
        </form>
        <table data-toggle='table' data-search='true' data-search-selector='#search-adviser-input' data-url="getAction.php?data=sectionAdviserHistory&section_code=<?php echo $sect_code; ?>" id="adviser-table" class="table-striped table-sm">
            <thead class='thead-dark'>
                <tr>
                    <th scope='col' data-width="400" data-align="center" data-sortable="true" data-field="date_assignment">Date time of Assignment</th>
                    <th scope='col' data-width="400" data-halign="center" data-align="left" data-sortable="true" data-field="faculty">Faculty</th>
                    <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="semester">Semester</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<!-- STUDENT TABLE END -->
<!-- MODAL -->
<div class="modal fade" id="transfer-modal" tabindex="-1" aria-labelledby="modal transferStudent" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h4 class="mb-0">Transfer Student</h4>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><small class='text-secondary'>Select section where students will be transferred.</small></p>
                <form id='transfer-form' method="POST">
                    <input type="hidden" name="action" value="transferStudentInSection">
                </form>
                <table id="section-options-table" class="table-striped table-sm">
                    <thead class='thead-dark'>
                        <tr>
                            <th data-radio="true"></th>
                            <th scope='col' data-width="100" data-align="center" data-field="section_code">Section Code</th>
                            <th scope='col' data-width="300" data-halign="center" data-align="left" data-sortable="true" data-field="name">Name</th>
                            <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grade_level">Grade</th>
                            <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="student_no">No of Student</th>
                            <th scope='col' data-width="400" data-align="center" data-sortable="true" data-field="adviser">Adviser</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button class="close btn btn-dark close-btn btn-sm" data-bs-dismiss="modal">Cancel</button>
                <input type="submit" form="transfer-form" class="submit btn btn-success btn-sm" value="Transfer" />
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="add-student-modal" tabindex="-1" aria-labelledby="modal add-student" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h4 class="mb-0">Add Student</h4>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><small class='text-secondary'>Select students who will be added or transferred to this section</small></p>
                <form>
                    <div class="d-flex justify-content-between mb-1"> 
                        <!-- SEARCH BAR -->
                        <span class="flex-grow-1 me-3">
                            <input id="search-student-input" type="search" class="form-control form-control-sm" placeholder="Search students here">
                        </span>
                        <span><input type='reset' class='clear-table-btn btn btn-dark btn-sm shadow-sm' value='Clear'/></span>
                    </div>
                </form>
                <form id="add-student-form" method="post">
                    <input type="hidden" name="action" id="action" value="addStudentInSection" />
                    <input type="hidden" name="section_code" value="<?php echo $sect_code; ?>" />
                </form>
                <table id="student-options-table" class="table-striped table-sm">
                    <thead class='thead-dark'>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th scope='col' data-width="100" data-align="center" data-field="stud_id">SID</th>
                            <th scope='col' data-width="100" data-align="center" data-field="lrn">LRN</th>
                            <th scope='col' data-width="200" data-halign="center" data-align="left" data-sortable="true" data-field="name">Student Name</th>
                            <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grade">Grade</th>
                            <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="program">Strand</th>
                            <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="section_code">Section Code</th>
                            <th scope='col' data-width="200" data-halign="center" data-align="left" data-sortable="true" data-field="section_name">Section Name</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                
            </div>
            <div class="modal-footer">
                <button class="close btn btn-dark close-btn btn-sm" data-bs-dismiss="modal">Cancel</button>
                <input type="submit" form="add-student-form" class="submit btn btn-success btn-sm" value="Add" />
            </div>
        </div>
    </div>
</div>
<!-- MODAL END -->
<script type="text/javascript">
    let isViewPage = <?php echo json_encode($isViewPage); ?>;
    let sectionCode = <?php echo json_encode($sect_code); ?>;
    let adviser = <?php echo json_encode($sect_adviser); ?>;
    let tempData = <?php echo json_encode([$sect_max_no, $teacher_id]); ?>;
    let currentSectName = <?php echo json_encode($sect_name); ?>;
    let currentSectNo = <?php echo json_encode($sect_stud_no); ?>;
    let currentSectLevel = <?php echo json_encode($sect_grd_level); ?>;
</script>