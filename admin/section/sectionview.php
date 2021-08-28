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

    $NONE = "d-none";
    $state = "disabled";
    $edit_btn_state = "";
    $display = $NONE;
    $input_display = "";
    $edit_btn_display = "";
    $empty_msg_display = "";
    if (isset($_GET['action']) && $_GET['action'] == 'edit') {
        $state = '';
        $edit_btn_state = "disabled";
        $display = "";
        $edit_btn_display = $NONE;
        $empty_msg_display =  $NONE;
    } 
?>

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
    <div class="d-flex justify-content-between">
        <h2 class="fw-bold"><?php echo $sect_name; ?></h2>
        <span>
            <button type="button" class="btn btn-secondary me-1">Archive Section</button>
            <button class="btn btn-dark" title='Archive strand'><i class="bi bi-box-arrow-up-left me-2"></i>Export</button>
        </span>
    </div>
    <hr class="my-2">
    <h6 class="fw-bold">Section</h6>
</header>
<!-- HEADER END -->
<!-- INFORMATION -->
<div class="container">
    <div class='card'>
        <div class="d-flex justify-content-between">
            <h4>Information</h4>
            <div class="btn-con my-a">
                <button id='edit-btn' class='btn link btn-sm <?php echo $edit_btn_display;?>'><i class="bi bi-pencil-square me-2"></i>Edit</button>
                <div class='edit-opt <?php echo $display; ?>'>
                    <button id="cancel-btn" class="btn btn-dark btn-sm me-1">Cancel</button>
                    <input type="submit" form="section-edit-form" class="btn btn-success btn-sm" value="Save">
                </div>
            </div>
        </div><hr class='mt-2 mb-4'>
        <section class="w-100">
            <form id='section-edit-form' method='POST'>
                <div class="ps-3 row w-100">
                    <div class="col-md-5">
                        <div class="row">
                            <label for="code" class="col-sm-4 text-secondary">Code</label>
                            <div class="col-sm-8">
                                <p id="code"><?php echo $sect_code; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <label for="grd-level" class="col-sm-4 text-secondary">Grade Level</label>
                            <div class="col-sm-8">
                                <p id="grd-level"><?php echo $sect_grd_level; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <label for="no-of-stud" class="col-sm-4 text-secondary">No of Students</label>
                            <div class="col-sm-8">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <p id="no-of-stud" class="m-0"><?php echo $sect_stud_no; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="row">
                            <label for="sect-max-no" class="col-sm-4 text-secondary">Max Student No.</label>
                            <div class="col-sm-8">
                                <input class="form-control" name="max-no" id="sect-max-no" value="<?php echo $sect_max_no; ?>" <?php echo $state; ?> />
                            </div>
                        </div>
                        <div class="row">
                            <label for="adviser" class="col-sm-4 text-secondary">Class Adviser</label>
                            <div class="col-sm-8">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <p id='adviser' class='m-0'>
                                        <?php 
                                            $teacher_id = "";
                                            $adviser_name = "";
                                            if ($sect_adviser) {
                                                $teacher_id = $sect_adviser['teacher_id'];
                                                $adviser_name = "Teacher {$sect_adviser['name']}";
                                                $empty_msg_display = "d-none";
                                            } 
                                            echo "<a class='link' target='_blank' href='faculty.php?id=$teacher_id'>$adviser_name</a>";
                                            echo "<p id='empty-msg' class='m-0 $empty_msg_display'>No adviser set</p>"; 
                                        ?>
                                    </p>
                                    <div class='d-flex-column w-100 mb-2 edit-opt <?php echo $display; ?>'>
                                        <div class='d-flex'>
                                            <div class='flex-grow-1'>
                                                <input value="<?php echo $teacher_id; ?>" type="text" class='form-control m-0' name='adviser' list='adviser-list' placeholder='Type to search ...'>
                                                <datalist id='adviser-list'>
                                                    <?php 
                                                        $faculty_list = $admin->listFaculty();
                                                        foreach($faculty_list as $faculty) {
                                                            $id = $faculty->get_teacher_id();
                                                            $teacher_name = $faculty->get_name();
                                                            echo "<option value='$id'>$id - $teacher_name</option>";
                                                        }
                                                    ?>
                                                </datalist>
                                            </div>
                                            <span class='m-auto edit-opt <?php echo $display; ?>'><button id='adviser-clear-btn' class='btn btn-link text-danger w-auto ms-2 p-1'><i class='bi bi-x-square-fill'></i></button></span>
                                        </div>
                                        <small class='edit-opt ms-1 text-secondary <?php echo $display; ?>'>Clear field to unassign adviser</small>
                                    </div>
                                    <!-- <span class="badge"><button id="adviser-edit-btn" class="btn btn-sm link-green"><i class="bi bi-plus-square me-2"></i>Assign</button></span> -->
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
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="my-auto"><h5 class='m-0'>Student List</h5></span>
        <span><button class="btn btn-success btn-sm">Add Student</button></span>
    </div>
    <div class="card w-100 h-auto bg-light">
        <table id="table" class="table-striped table-sm">
            <thead class='thead-dark'>
                <div class="d-flex justify-content-between mb-3">
                    <!-- SEARCH BAR -->
                    <span class="flex-grow-1 me-2"> 
                        <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
                    </span>
                    <div>
                        <button id="transfer-btn" class="btn btn-secondary btn-sm" title='Transfer student to another section'>Transfer student</button>
                    </div>
                </div>
                <tr>
                    <th data-checkbox="true"></th>
                    <th scope='col' data-width="100" data-align="left" data-field="lrn">LRN</th>
                    <th scope='col' data-width="600" data-align="left" data-sortable="true" data-field="name">Student Name</th>
                    <th scope='col' data-width="300" data-align="center" data-field="action">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<!-- STUDENT TABLE END -->
<!-- MODAL -->
<div class="modal fade" id="transfer-modal" tabindex="-1" aria-labelledby="modal transferStudent" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form id="transfer-form" method="POST">
            <input type="hidden" name="action" vaue="transferStudent">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Transfer Student</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><small class='text-secondary'>Select section/s where students will be trasfered. </small></p>
                    <form id='section-form' method="POST">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <div class="form-row row d-none">
                                    <label for="sect-code" class="col-lg-5 col-form-label">Code</label>
                                    <div class="col-lg-7">
                                        <input value="" type="text" name="code" class="form-control" id="sect-code" placeholder="Enter unique code">
                                    </div>
                                </div>
                                <div class="form-row row d-none">
                                    <label for="program" class="col-lg-5 col-form-label">Program/Strand</label>
                                    <div class="col-lg-7">
                                        <select id="program" class='form-select' name='program'>
                                            <option value="" selected>-- Select --</option>
                                            <?php 
                                                include_once("../class/Administration.php");
                                                $admin = new Administration();
                                                $program_list = $admin->listPrograms("program");
                                                
                                                foreach($program_list as $program) {
                                                    $prog_code = $program->get_prog_code();
                                                    $prog_name = $program->get_prog_desc();
                                                    echo "<option value='$prog_code'>$prog_name</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row row">
                                    <label for="section-name" class="col-lg-4 col-form-label">Section Name</label>
                                    <div class="col-lg-8">
                                        <textarea id='section-name'name="section-name" class='form-control' maxlength="50" placeholder="Enter section name"></textarea>
                                    </div>
                                </div>
                                <div class="form-row row">
                                    <label for="grade-level" class="col-lg-4 col-form-label">Grade Level</label>
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
                                
                            </div>
                            <div class="form-group col-sm-6">
                                <div class="form-row row">
                                    <label for="max-no" class="col-lg-4 col-form-label">Max student no.</label>
                                    <div class="col-lg-8">
                                        <input value="50" type="text" name="max-no" class="form-control number" id="max-no" placeholder="Enter maximum student no.">
                                    </div>
                                </div>
                                <div class="form-row row">
                                    <label for="section-name" class="col-lg-4 col-form-label">Class Adviser (Optional)</label>
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
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="action" id="action" value="addSection" />
                    <button class="close btn btn-dark close-btn" data-bs-dismiss="modal">Close</button>
                    <button class="submit-another btn btn-secondary">Submit and add again</button>
                    <input type="submit" form="section-form" class="submit btn btn-success" value="Submit" />
                </div>
            </div>
        </form>
    </div>
</div>
<!-- MODAL END -->
<script type="text/javascript">
    let isViewPage = <?php echo json_encode($isViewPage); ?>;
    let sectionCode = <?php echo json_encode($sect_code); ?>;
    let adviser = <?php echo json_encode($sect_adviser); ?>;
</script>