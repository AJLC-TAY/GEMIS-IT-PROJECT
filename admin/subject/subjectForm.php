<?php
require_once ("../class/Administration.php");

function prepareEmptyProgramOptions($programs, $type = NULL) {
    $prog_opt = "<div id='app-spec-options' class='row ". (($type != 'core') ? "" : "d-none" ). " overflow-auto'>"
        ."<label class='col-sm-12'>Program Options</label>"
        ."<div id='program-con' class='col-sm-8'>";
        foreach ($programs as $program) {
            $prog_code = $program->get_prog_code();
            $prog_name = $program->get_prog_name();
            $prog_opt .= "<div class='form-check'>"
                ."<input class='form-check-input' type='radio' name='prog_code[]' id='$prog_code' value='$prog_code' readonly>"
                ."<label class='form-check-label' for='$prog_code'>$prog_code</label>"
                ."</div>";
        }
    $prog_opt .= '</div></div>';
    return $prog_opt;
}

$action = $_GET['action'];
$admin = new Administration();
// option lists
$sub_opt = array('core' => 'Core', 'applied' => 'Applied', 'specialized' => 'Specialized' );   
$semesters = array('1' => 'First','2'=>'Second' );     
$grd_lvl = array('11' => '11', '12' => '12' );                 
// retrieve subjects for each grade level
$subjectGrade11 = $admin->listSubjectsbyLevel(11);
$subjectGrade12 = $admin->listSubjectsbyLevel(12);
// retrieve programs
$programs = $admin->listPrograms('program');

// default link for Bread crumb 
$links = "<li class='breadcrumb-item'><a href='subject.php'>Subject</a></li>"
        ."<li class='breadcrumb-item active' aria-current='page'>Add</li>";

// subject type input is editable by default 
$sub_type_editable = '';

// prepare program options
$prog_opt = "";
$input_sub_with_prog = '';

// subject data
$title = "<h3><b>Add Subject</b></h3><p class='text-secondary'><small>Please complete the following:</small></p>";
$subject_code = '';
$subject_name = '';
$semester_opt = '';
$sub_type_opt = '';
$grade_level_opt = '';
$reqRowsGrd11 = '';
$reqRowsGrd12 = '';
$grade_level_state = '';
$button = '';
$old_program = '';
if ($action === 'add') {
    $prog_opt = prepareEmptyProgramOptions($programs, "core");

    // // prepare semester options
    // foreach ($semesters as $id => $value) {
    //     $semester_opt .= "<option value='$id'". (($id == '0' ) ? 'selected' : '') .">$value</option>";
    // }

    // prepare subject type options
    foreach ($sub_opt as $id => $value) {
        $sub_type_opt .= "<option value='$id' ". (($id == "core" ) ? "selected" : "") .">$value</option>"; // Default = Core subject type
    }

    foreach ($grd_lvl as $id => $value) { 
        $grade_level_opt .= "<option value='$id'". (($id == '0' ) ? 'selected' : '') .">$value</option>";
    }

    foreach ($subjectGrade11 as $subGr11) {
        $sub_code = $subGr11->get_sub_code();
        $sub_name = $subGr11->get_sub_name();
        $sub_type = $subGr11->get_sub_type();
        $reqRowsGrd11 .= "<tr>
            <td scope='col'>$sub_code</td>
            <td scope='col'>$sub_name</td>
            <td scope='col'>$sub_type</td>
            <td scope='col' class='text-center'><input class='form-check-input' type='radio' name='radio-$sub_code' value='PRE-$sub_code'></td>
            <td scope='col' class='text-center'><input class='form-check-input' type='radio' name='radio-$sub_code' value='CO-$sub_code'></td>
            <td scope='col' class='text-center'><button class='spec-clear-btn btn btn-sm rounded-pill btn-outline-danger' title='Clear'><i class='bi bi-dash-circle'></i></button></td>
        </tr>";
    }

    foreach ($subjectGrade12 as $subGr12) {
        $sub_code = $subGr12->get_sub_code();
        $sub_name = $subGr12->get_sub_name();
        $sub_type = $subGr12->get_sub_type();
        $reqRowsGrd12 .= "<tr>
            <td scope='col'>$sub_code</td>
            <td scope='col'>$sub_name</td>
            <td scope='col'>$sub_type</td>
            <td scope='col' class='text-center'><input class='form-check-input' type='radio' name='radio-$sub_code' value='PRE-$sub_code'></td>
            <td scope='col' class='text-center'><input class='form-check-input' type='radio' name='radio-$sub_code' value='CO-$sub_code'></td>
            <td scope='col' class='text-center'><button class='spec-clear-btn btn btn-sm rounded-pill btn-outline-danger' title='Clear'><i class='bi bi-dash-circle'></i></button></td>
        </tr>";
    }

    $button = "<div class='btn-con'>"
                ."<a href='#' role='button' class='submit-and-again-btn form-control btn btn-secondary me-2 w-auto'>Submit & Add another</a>"
                ."<input type='submit' form='add-subject-form' class='submit-btn btn btn-success form-control w-auto' value='Submit' />"
            ."</div>";

    if (isset($_GET['prog_code'])) {                // add subject page is accessed from a program page
        // get program data
        $program = $admin->getProgram();
        $prog_name = $program->get_prog_desc();
        $prog_code = $program->get_prog_code();
        
        // update link data
        $links = "<li class='breadcrumb-item'><a href='program.php'>Program</a></li>"
            . "<li class='breadcrumb-item'><a href='program.php?prog_code=$prog_code'>$prog_code</a></li>"
            . "<li class='breadcrumb-item active' aria-current='page'>Add</li>";
        $header = "<h2>$prog_name</h2>"
            . "<h4>Add Subject</h4>";
        
        // overwrite program options
        $prog_opt = '';

        $sub_type_editable = 'readonly';
        $sub_type_opt = "<option value='specialized' selected>Specialized</option>";
        $input_sub_with_prog = "<input type='hidden' name='prog_code[]' value='$prog_code'>";
        $old_program = "<input type='hidden' name='old_program[]' value='$prog_code'>";
    }
}

if ($action === 'edit') {
    // get the subject data
    $subject = $admin->getSubject();
    $subject_code = $subject->get_sub_code();
    $subject_name = $subject->get_sub_name();
    $subject_type = $subject->get_sub_type();
    $prereq = $subject->get_prerequisite();
    $coreq = $subject->get_corequisite();

    $prog_opt = prepareEmptyProgramOptions($programs, $subject_type);

    $title = "<h3><b>$subject_name</b></h3><hr>";
    $input_sub_with_prog = '';

    $links = "<li class='breadcrumb-item'><a href='subject.php'>Subject</a></li>"
            ."<li class='breadcrumb-item active' aria-current='page'>Edit</li>";

    $sub_type_opt = '';
    foreach ($sub_opt as $id => $value) { 
        $sub_type_opt .= "<option value='$id' ". (($id == $subject->get_sub_type()) ? 'selected' : '') .">$value</option>";
    }

    $reqRowsGrd11 = '';
    foreach ($subjectGrade11 as $subGr11) {
        $sub_code = $subGr11->get_sub_code();
        $sub_name = $subGr11->get_sub_name();
        $sub_type = $subGr11->get_sub_type();

        $reqRowsGrd11 .= ($subject_code == $sub_code) ? "" 
                        : "<tr>
                            <td scope='col'>$sub_code</td>
                            <td scope='col'>$sub_name</td>
                            <td scope='col'>$sub_type</td>
                            <td scope='col' class='text-center'><input ". (in_array($sub_code, $prereq) ? 'checked' : '') ." class='form-check-input' type='radio' name='radio-$sub_code' value='PRE-$sub_code'></td>
                            <td scope='col' class='text-center'><input ". (in_array($sub_code, $coreq) ? 'checked' : '') ." class='form-check-input' type='radio' name='radio-$sub_code' value='CO-$sub_code'></td>
                            <td scope='col' class='text-center'><button class='spec-clear-btn btn btn-sm rounded-pill btn-outline-danger' title='Clear'><i class='bi bi-dash-circle'></i></button></td>
                        </tr>";
    }

    // $grade_level_state = ($subject_grd == 11) ? 'disabled': '';
    $grade_level_state = '';
    $reqRowsGrd12 = '';
    foreach ($subjectGrade12 as $subGr12) {
        $sub_code = $subGr12->get_sub_code();
        $sub_name = $subGr12->get_sub_name();
        $sub_type = $subGr12->get_sub_type();
        $reqRowsGrd12 .= ($subject_code == $sub_code) ? "" 
                        :"<tr>
                            <td scope='col'>$sub_code</td>
                            <td scope='col'>$sub_name</td>
                            <td scope='col'>$sub_type</td>
                            <td scope='col' class='text-center'><input ". (in_array($sub_code, $prereq) ? 'checked' : '') ." class='form-check-input' type='radio' name='radio-$sub_code' value='PRE-$sub_code' $grade_level_state></td>
                            <td scope='col' class='text-center'><input ". (in_array($sub_code, $coreq) ? 'checked' : '') ." class='form-check-input' type='radio' name='radio-$sub_code' value='CO-$sub_code' $grade_level_state></td>
                            <td scope='col' class='text-center'><button class='spec-clear-btn btn btn-sm rounded-pill btn-outline-danger' title='Clear'><i class='bi bi-dash-circle'></i></button></td>
                        </tr>";
    }

    $button = "<input form='add-subject-form' class='btn btn-success form-control' style='width: 120px;' type='submit' value='Save'>";
    $old_program = [];
    if ($subject_type === 'applied' ) {
        $sub_programs = $subject->get_programs();
        foreach ($sub_programs as $sprog) {
            $old_program[] = "<input type='hidden' name='old_program[]' value='$sprog'>";
        }
        $prog_opt = "<input type='hidden' name='old_type' value='applied'><div id='app-spec-options' class='form-group row overflow-auto'>"
                    ."<label class='col-sm-6'>Program Options</label>"
                    ."<div id='program-con' class='col-sm-8'>";
        
        foreach ($programs as $program) {
            $prog_code = $program->get_prog_code();
            $prog_name = $program->get_prog_name();
            $sub_program_state = (in_array($prog_code, $sub_programs)) ? " original' checked" : "'";
            $prog_opt .= "<div class='form-check'>
                <input class='form-check-input $sub_program_state type='checkbox' name='prog_code[]' id='$prog_code' value='$prog_code'>
                <label class='form-check-label' for='$prog_code'>$prog_code</label>
            </div>";
        }
        $prog_opt .= '</div></div>';
    }

    if (isset($_GET['prog_code'])) {                // edit page is accessed from a program page
        // get program data
        $program = $admin->getProgram();
        $prog_name = $program->get_prog_desc();
        $prog_code = $program->get_prog_code();
        $input_sub_with_prog = "<input type='hidden' name='prog_code' value='$prog_code'>"
                ."<input type='hidden' name='old_type' value='specialized'>";


        // prepare links for bread crumb
        $links = "<li class='breadcrumb-item'><a href='program.php'>Program</a></li>"
                ."<li class='breadcrumb-item'><a href='program.php?prog_code=$prog_code'>$prog_code</a></li>"
                ."<li class='breadcrumb-item active' aria-current='page'>Edit</li>";

        $title = "<h3>$subject_name</h3><hr><h6>$prog_name</h6>";

        $prog_opt = "<div id='app-spec-options' class='row overflow-auto'>
            <label class='col-sm-12'>Program Options</label>
            <div id='program-con' class='col-sm-8'>";
                foreach ($programs as $program) {
                    $prog_code_data = $program->get_prog_code();
                    $prog_name_data = $program->get_prog_name();
                    $prog_opt .= "<div class='form-check'>
                        <input class='form-check-input' type='radio' name='prog_code[]' id='$prog_code_data' value='$prog_code_data' ". (($prog_code == $prog_code_data) ? 'checked': '') .">
                        <label class='form-check-label' for='$prog_code_data'>$prog_code_data</label>
                    </div>";
                }
        $prog_opt .= '</div></div>';
        $old_program[] = "<input type='hidden' name='old_program[]' value='$prog_code'>";
    }
    $old_program = implode("", $old_program);
}
?>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label='breadcrumb'>
        <ol class='breadcrumb'>
            <li class='breadcrumb-item'><a href='index.php'>Home</a></li>
            <?php echo $links;?>
        </ol>
    </nav>
    <?php echo $title; ?>
</header>
<!-- HEADER END -->

<div class='CONTAINER'>
    <form id='add-subject-form' method='POST'>
        <?php echo $input_sub_with_prog; ?>
        <input type='hidden' name='action' value='<?php echo $action; ?>Subject'>
        <div class='row card bg-light w-100 h-auto text-start mx-auto mt-3 p-3'>
            <h5 class='text-start p-0 fw-bold'>SUBJECT DETAILS</h5>
            <hr class='mt-1'>
            <div class='container'>
                <div class='form-row row'>
                    <div class='form-group col-lg-4'>
                        <label for='subjectCode1'  class='col-sm-3 col-form-label'>Code</label>
                        <input value='<?php echo $subject_code; ?>' name = 'current_sub_code' type="hidden">
                        <input value='<?php echo $subject_code; ?>' type='text' name = 'code' class='form-control' id='sub-code' placeholder='Enter unique subject code'>
                    </div>
                    <div class='form-group col-lg-3'>
                        <label for='sub-type' class='col-sm-3 col-form-label'>Type</label>
                        <?php echo $old_program; ?>
                        <select name='sub-type' class='form-select' id='sub-type' <?php echo $sub_type_editable?>><?php echo $sub_type_opt; ?></select>
                        <?php echo $prog_opt; ?>
                    </div>
                    <div class='form-group col-lg-5'>
                        <label for='sub-name' class='  col-form-label'>Name</label>
                        <textarea name='name' class='form-control' id='sub-name' maxlength='250' placeholder='Enter subject name (max of 250 characters)'><?php echo $subject_name; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class='row card w-100 h-auto bg-light my-4 mx-auto'>
            <h5 class='text-start mb-3 fw-bold'>PREREQUISITE | CO-REQUISITE SUBJECTS (if applicable)</h5>
            <div class='accordion' id='accordionPanelsStayOpenExample'>
                <div class='accordion-item'>
                    <h2 class='accordion-header' id='panelsStayOpen-headingOne'>
                        <button class='accordion-button' type='button' data-bs-toggle='collapse' data-bs-target='#panelsStayOpen-collapseOne' aria-expanded='true' aria-controls='panelsStayOpen-collapseOne'>
                            Grade 11
                        </button>
                    </h2>
                    <div id='panelsStayOpen-collapseOne' class='accordion-collapse collapse show' aria-labelledby='panelsStayOpen-headingOne'>
                        <div class='accordion-body'>
                            <div id='grade11-table'>
                                <div class='d-flex justify-content-between align-items-center mb-2'>
                                    <h6>Subjects</h6>
                                    <span><button data-desc='11' class='clear-table-btn float-right btn btn-outline-secondary btn-sm'><i class='bi bi-x me-2'></i>Clear</button></span>
                                </div>
                                <div class='requisite-table overflow-auto'>
                                    <table class='table table-bordered table-hover table-striped'>
                                        <thead>
                                            <tr class='text-center'>
                                                <th scope='col'>CODE</th>
                                                <th scope='col'>SUBJECT NAME</th>
                                                <th scope='col'>TYPE</th>
                                                <th scope='col'>PRE</th>
                                                <th scope='col'>CO</th>
                                                <th scope='col'>ACTION</th>
                                            </tr>
                                        </thead>

                                        <tbody><?php echo $reqRowsGrd11; ?></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='accordion-item'>
                    <h2 class='accordion-header' id='panelsStayOpen-headingTwo'>
                        <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#panelsStayOpen-collapseTwo' aria-expanded='false' aria-controls='panelsStayOpen-collapseTwo'>
                            Grade 12
                        </button>
                    </h2>
                    <div id='panelsStayOpen-collapseTwo' class='accordion-collapse collapse' aria-labelledby='panelsStayOpen-headingTwo'>
                        <div class='accordion-body'>
                            <div id='grade12-table'>
                                <div class='d-flex justify-content-between align-items-center mb-2'>
                                    <h6>Subjects</h6>
                                    <span><button data-desc='12' class='clear-table-btn float-right btn btn-outline-secondary btn-sm'><i class='bi bi-x me-2'></i>Clear</button></span>
                                </div>
                            
                                <div class='requisite-table overflow-auto'>
                                    <table class='table table-bordered table-hover table-striped'>
                                        <thead>
                                            <tr class='text-center'>
                                                <th scope='col'>CODE</th>
                                                <th scope='col'>SUBJECT NAME</th>
                                                <th scope='col'>TYPE</th>
                                                <th scope='col'>PRE</th>
                                                <th scope='col'>CO</th>
                                                <th scope='col'>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody><?php echo $reqRowsGrd12; ?></tbody>
                                    </table>
                                </div>
                            </div>                   
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class='d-flex flex-row-reverse'><?php echo $button; ?></div>