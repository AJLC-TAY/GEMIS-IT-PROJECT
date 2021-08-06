<?php

function prepareEmptyProgramOptions($programs) {
    $prog_opt = "<div id='app-spec-options' class='row d-none overflow-auto'>"
        ."<label class='col-sm-4'>Program Options</label>"
        ."<div id='program-con' class='col-sm-8'>";
        foreach ($programs as $program) {
            $prog_code = $program->get_prog_code();
            $prog_name = $program->get_prog_name();
            $prog_opt .= "<div class='form-check'>"
                ."<input class='form-check-input' type='radio' name='prog_code[]' id='$prog_code' value='$prog_code' readonly>"
                ."<label class='form-check-label' for='$prog_name'>$prog_code</label>"
                ."</div>";
        }
    $prog_opt .= '</div></div>';
    return $prog_opt;
}

function getSubjectForm($state) {
    $admin = new Administration();
    $content = new stdClass();
    // option lists
    $sub_opt = array('core' => 'Core', 'applied' => 'Applied', 'specialized' => 'Specialized' );        // subject type
    $semesters = array('0' => '-- Select semester --', '1' => '1st Semester','2'=>'2nd Semester' );     // semseter
    $grd_lvl = array('0' => '-- Select grade level --', '11' => '11', '12' => '12' );                   // grade level


     // retrieve subjects for each grade level
    $subjectGrade11 = $admin->listSubjectsGrade11();
    $subjectGrade12 = $admin->listSubjectsGrade12();
    // retrieve programs
    $programs = $admin->listPrograms();

    // default link for Bread crumb 
    $links = "<li class='breadcrumb-item'><a href='subjectlist.php'>Subject</a></li>"
            ."<li class='breadcrumb-item active' aria-current='page'>Add Subject</li>";
    // subject type input is editable by default 
    $sub_type_editable = '';
    
    // prepare program options
    $prog_opt = prepareEmptyProgramOptions($programs);
    $input_sub_with_prog = "";

    if ($state === 'add') {
        $header = '<h3>Add Subject</h3><h6>Please complete the following:</h6>';
        $subject_code = '';
        $subject_name = '';
        // prepare semester options
        $semester_opt = '';
        foreach ($semesters as $id => $value) { 
            $semester_opt .= "<option value='$id'". (($id == '0' ) ? 'selected' : '') .">$value</option>";
        }

        // prepare subject type options
        $sub_type_opt = '';
        foreach ($sub_opt as $id => $value) { 
            $sub_type_opt .= "<option value='$id' ". (($id == "core" ) ? "selected" : "") .">$value</option>"; // Default = Core subject type
        }

        $grade_level_opt = '';
        foreach ($grd_lvl as $id => $value) { 
            $grade_level_opt .= "<option value='$id'". (($id == '0' ) ? 'selected' : '') .">$value</option>";
        }

        $reqRowsGrd11 = '';
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
            </tr>";
        }

        $reqRowsGrd12 = '';
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
            </tr>";
        }

        $button = "<input type='hidden' name='action' value='addSubject'>"
                 ."<input class='btn btn-success form-control' style='width: 150px' type='submit' value='ADD SUBJECT'>";

        if (isset($_GET['prog_code'])) {                // add subject page is accessed from a program page
            // get program data
            $program = $admin->getProgram();
            $prog_name = $program->get_prog_desc();
            $prog_code = $program->get_prog_code();
            
            // update link data
            // $link = "program.php?prog_code=$prog_code";
            $links = "<li class='breadcrumb-item'><a href='programlist.php'>Program</a></li>"
                . "<li class='breadcrumb-item'><a href='program.php?code='$prog_code'>$prog_code</a></li>"
                . "<li class='breadcrumb-item active' aria-current='page'>Add Subject</li>";
            $header = "<h2>$prog_name</h2>"
                . "<h4>Add Subject</h4>";
         
            // overwrite program options
            $prog_opt = '';

            $sub_type_opt = '<option selected>Specialized</option>';
        }
    }

    if ($state === 'edit') {
        // get the subject data
        $subject = $admin->getSubject();
        $subject_code = $subject->get_sub_code();
        $subject_name = $subject->get_sub_name();
        $sub_type = $subject->get_sub_type();
        $prereq = $subject->get_prerequisite();
        $coreq = $subject->get_corequisite();

        $header = "<h3>$subject_name</h3><hr>";
        $input_sub_with_prog = '';

        $links = "<li class='breadcrumb-item'><a href='subjectlist.php'>Subject</a></li>"
                ."<li class='breadcrumb-item active' aria-current='page'>Edit Subject</li>";

        $semester_opt = '';
        foreach ($semesters as $id => $value) { 
            $semester_opt .= "<option value='$id' ". (($id == $subject->get_sub_semester()) ? 'selected' : '') .">$value</option>";
        }

        $grade_level_opt = '';
        foreach ($grd_lvl as $id => $value) { 
            $grade_level_opt .= "<option value='$id' ". (($id == $subject->get_for_grd_level()) ? 'selected' : '') .">$value</option>";
        }

        $sub_type_opt = '';
        foreach ($sub_opt as $id => $value) { 
            $sub_type_opt .= "<option value='$id' ". (($id == $subject->get_sub_type()) ? 'selected' : '') .">$value</option>";
        }

        $reqRowsGrd11 = '';
        foreach ($subjectGrade11 as $subGr11) {
            $sub_code = $subGr11->get_sub_code();
            $sub_name = $subGr11->get_sub_name();
            $sub_type = $subGr11->get_sub_type();

            $reqRowsGrd11 .= ($subject_code == $sub_code) ? "" : "<tr>
                <td scope='col'>$sub_code</td>
                <td scope='col'>$sub_name</td>
                <td scope='col'>$sub_type</td>
                <td scope='col' class='text-center'><input ". (in_array($sub_code, $prereq) ? 'checked' : '') ." class='form-check-input' type='radio' name='radio-$sub_code' value='PRE-$sub_code'></td>
                <td scope='col' class='text-center'><input ". (in_array($sub_code, $coreq) ? 'checked' : '') ." class='form-check-input' type='radio' name='radio-$sub_code' value='CO-$sub_code'></td>
            </tr>";
        }

        $reqRowsGrd12 = '';
        foreach ($subjectGrade12 as $subGr12) {
            $sub_code = $subGr12->get_sub_code();
            $sub_name = $subGr12->get_sub_name();
            $sub_type = $subGr12->get_sub_type();
            $reqRowsGrd12 .= ($subject_code == $sub_code) ? "" :"<tr>
                <td scope='col'>$sub_code</td>
                <td scope='col'>$sub_name</td>
                <td scope='col'>$sub_type</td>
                <td scope='col' class='text-center'><input ". (in_array($sub_code, $prereq) ? 'checked' : '') ." class='form-check-input' type='radio' name='radio-$sub_code' value='PRE-$sub_code'></td>
                <td scope='col' class='text-center'><input ". (in_array($sub_code, $coreq) ? 'checked' : '') ." class='form-check-input' type='radio' name='radio-$sub_code' value='CO-$sub_code'></td>
            </tr>";
        }

        $button = "<input type='hidden' name='action' value='updateSubject'>"
                 ."<input class='btn btn-success form-control' style='width: 150px;' type='submit' value='Save'>";

        if ($sub_type === 'applied' ) {
            $sub_programs = $subject->get_programs();
    
            $prog_opt = "<div id='app-spec-options' class='row overflow-auto'>"
                        ."<label class='col-sm-4'>Program Options</label>"
                        ."<div id='program-con' class='col-sm-8 p-0'>";
            
            foreach ($programs as $program) {
                $prog_code = $program->get_prog_code();
                $prog_name = $program->get_prog_name();
                $sub_program_state = (in_array($prog_code, $sub_programs)) ? " original' checked" : "'";
                $prog_opt .= "<div class='form-check'>
                    <input class='form-check-input $sub_program_state type='checkbox' name='prog_code[]' id='$prog_code' value='$prog_code'>
                    <label class='form-check-label' for='$prog_name'>$prog_code</label>
                </div>";
            }
            $prog_opt .= '</div></div>';
        }

        if (isset($_GET['prog_code'])) {                // edit page is accessed from a program page
            // get program data
            $program = $admin->getProgram();
            $prog_name = $program->get_prog_desc();
            $prog_code = $program->get_prog_code();
            $input_sub_with_prog = "<input type='hidden' name='prog_code' value='$prog_code'>";
    
            // prepare links for bread crumb
            $links = "<li class='breadcrumb-item'><a href='programlist.php'>Program</a></li>"
                    ."<li class='breadcrumb-item'><a href='program.php?code='$prog_code'>$prog_code</a></li>"
                    ."<li class='breadcrumb-item active' aria-current='page'>Edit Subject</li>";
    
            $header = "<h3>$sub_name</h3><hr><h6>$prog_name</h6>";
    
            $prog_opt = "<div id='app-spec-options' class='row overflow-auto'>
                <label class='col-sm-4'>Program Options</label>
                <div id='program-con' class='col-sm-8'>";
                    foreach ($programs as $program) {
                        $prog_code_data = $program->get_prog_code();
                        $prog_name_data = $program->get_prog_name();
                        $prog_opt .= "<div class='form-check'>
                            <input class='form-check-input' type='radio' name='prog_code[]' id='$prog_code_data' value='$prog_code_data' ". (($prog_code == $prog_code_data) ? 'checked': '') .">
                            <label class='form-check-label' for='$prog_name_data'>$prog_code_data</label>
                        </div>";
                    }
            $prog_opt .= '</div></div>';
        }
    
    }

    $content->breadcrumb = "<nav aria-label='breadcrumb'>
                                <ol class='breadcrumb'>
                                    <li class='breadcrumb-item'><a href='index.html'>Home</a></li>$links
                                </ol>
                            </nav>";

    $form = $header ."<form id='add-subject-form' method='POST'>
                        $input_sub_with_prog
                        <div class='row card bg-light w-100 h-auto text-start mx-auto mt-3'>
                            <h5 class='text-start p-0'>SUBJECT DETAILS</h5>
                            <hr class='mt-1'>
                            <div class='row p-0'>
                                <div class='col-7'>
                                    <div class='form-group row'>
                                        <label for='subjectCode1'  class='col-sm-3 col-form-label'>Code</label>
                                        <div class='col-sm-9'>
                                            <input type='text' name = 'code' class='form-control' id='sub-code' value='$subject_code' placeholder='Enter unique subject code'>
                                        </div>
                                        <label for='subjectName1' class='col-sm-3 col-form-label'>Name</label>
                                        <div class='col-sm-9'>
                                            <input type='text' name = 'name' class='form-control' id='sub-name' value='$subject_name' placeholder='Enter subject name'>
                                        </div>
                                        <label for='subjectSemester1' class='col-sm-3 col-form-label'>Semester</label>
                                        <div class='col-sm-9'>
                                            <select name='semester' class='form-select' id='semester'>$semester_opt</select>
                                        </div>
                                        <label for='grade-level' class='col-sm-3 col-form-label'>Grade Level</label>
                                        <div class='col-sm-9'>
                                            <select name='grade-level' class='form-select' id='grade-level'>$grade_level_opt</select>
                                        </div>
                                    </div>
                                </div>
                                <div class='col-5'>
                                    <div class='form-group row'>
                                        <label for='subjectType1' class='col-sm-3 col-form-label'>Type</label>
                                        <div class='col-sm-9'>
                                            <select name='sub-type' class='form-select' id='sub-type'>$sub_type_opt</select>
                                        </div>
                                        $prog_opt
                                    </div>
                                </div>
                            </div>
                            </div>";
                            
                        $form .= "
                                <div class='row card w-100 h-auto bg-light my-4 mx-auto'>
                                    <h5 class='text-start mb-3'>PREREQUISITE | COREQUISITE SUBJECTS (if applicable)</h5>
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
                                                            <span><button data-desc='11' id='clear-table' class='float-right btn btn-dark'>Clear</button></span>
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
                                                                    </tr>
                                                                </thead>
                    
                                                                <tbody>$reqRowsGrd11</tbody>
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
                                                            <span><button data-desc='12' id='clear-table' class='float-right btn btn-dark'>Clear</button></span>
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
                                                                    </tr>
                                                                </thead>
                                                                <tbody>$reqRowsGrd12</tbody>
                                                            </table>
                                                        </div>
                                                    </div>                   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='d-flex flex-row-reverse'>$button</div>
                        </form>";
    $content->main = $form;
    return $content; 
}

function getSubjectViewContent() {
    $admin = new Administration();
    $content = new stdClass();
    $links = "<li class='breadcrumb-item'><a href='subjectlist.php'>Subject</a></li>";

    // get the subject data
    $subject = $admin->getSubject();
    $sub_code = $subject->get_sub_code();
    $sub_name = $subject->get_sub_name();
    $sub_type = $subject->get_sub_type();
    $sub_semester = $subject->get_sub_semester();
    $sub_grd_lvl = $subject->get_for_grd_level();
    $prog_name = '';
    if (isset($_GET['prog_code'])) {
        // get program data
        $program = $admin->getProgram();
        $prog_name = $program->get_prog_desc();
        $prog_code = $program->get_prog_code();
        $links = "<li class='breadcrumb-item'><a href='programlist.php'>Program</a></li>"
            . "<li class='breadcrumb-item'><a href='program.php?code='$prog_code'>$prog_code</a></li>";
    }

    $prereq = $subject->get_prerequisite();
    $coreq = $subject->get_corequisite();
  
    $content->breadcrumb = "<nav aria-label='breadcrumb'>
                                <ol class='breadcrumb'>
                                    <li class='breadcrumb-item'><a href='index.html'>Home</a></li>$links
                                    <li class='breadcrumb-item active' aria-current='page'>View Subject</li>
                                </ol>
                            </nav>";

    $details = "<div class='d-flex justify-content-between'>
        <h3>$sub_name</h3>
        <div class='buttons-con d-flex'>
            <button class='btn m-auto text-danger pt-1 px-1'><i class='bi bi-archive me-1'></i>Archive</button>
            <a href='subject.php?code=$sub_code&state=edit' target='_self' class='btn m-auto text-primary pt-1 px-1'><i class='bi bi-pencil-square me-1'></i>Edit</a>
        </div>
    </div>
    <hr>
    <h6>$prog_name</h6>
    <div id='add-subject-info' class='row justify-content-around mt-3'>
        <div class='col-sm-12 col-lg-6 col-xl-5 shadow-sm p-4 bg-light border rounded-3 text-start mb-4'>
            <h5 class='text-start p-0 fw-bold'>SUBJECT DETAILS</h5>
            <hr class='mt-1'>
            <div class='row p-0'>
                    <div class='row mb-3'>
                        <div class='col-xl-4 fw-bold'>Subject code</div>
                        <div class='col-xl-8'>$sub_code</div>
                    </div>
                    <div class='row mb-3'>
                        <div class='col-xl-4 fw-bold'>Name</div>
                        <div class='col-xl-8'>$sub_name</div>
                    </div>
                    <div class='row mb-3'>
                        <div class='col-xl-4 fw-bold'>Semester</div>
                        <div class='col-xl-8'>$sub_semester</div>
                    </div>
                    <div class='row mb-3'>
                        <div class='col-xl-4 fw-bold'>Grade Level</div>
                        <div class='col-xl-8'>$sub_grd_lvl</div>
                    </div>
                    <div class='row mb-3'>
                        <div class='col-xl-4 fw-bold'>Type</div>
                        <div class='col-xl-8'>$sub_type</div>
                    </div>
                </div>
            </div>
            <div class='col-sm-12 col-lg-5 col-xl-6 shadow-sm p-4 bg-light border rounded-3 mb-4'>  
                <h6 class='col-xl-4 fw-bold'>PROGRAM/S</h6>
                <hr class='mt-1'>
                <div id='program-con' class='d-flex flex-wrap'>";
                    if ($sub_type === 'core') {
                        $details .= "<div class='flex-grow-1><p class='text-center'>This subject is under all of the offered programs/strand.</p></div>";
                    }  else  if ($sub_type === 'specialized') {
                        $associatedProgram = $subject->get_program();
                        $details .= "<a href='program.php?prog_code=$associatedProgram' role='button' class='btn btn-outline-secondary rounded-pill'>$associatedProgram</a>";
                    } else if ($sub_type === 'applied') {
                        $associatedProgram = $subject->get_programs();
                        foreach($associatedProgram as $element) {
                            $details .= "<a href='program.php?prog_code=$element' role='button' class='btn btn-outline-secondary rounded-pill'>$element</a>";
                        }
                    } 
                      
                $countPre = count($prereq);
                $countCo = count($coreq);
                
                $requisite = '';
                if ($countPre) {
                    $requisite = "<div>
                        <h6>Prerequisite <span class='badge rounded-circle bg-secondary'>$countPre</span></h6>
                        <div class='list-group ms-3'>";
                            foreach($prereq as $req) {
                                $requisite .= "<a href='subject.php?code=$req&state=view' class='list-group-item list-group-item-action'>$req</a>";
                            }
                        $requisite .= "</div>
                    </div>";
                } 

                if ($countCo) {
                    $requisite .= "<div class='mt-3'>
                        <h6>Corequisite <span class='badge rounded-circle bg-secondary'>$countCo</span></h6>
                        <div class='list-group ms-3'>";
                            foreach($coreq as $req) {
                                $requisite .= "<a href='subject.php?code=$req&state=view' class='list-group-item list-group-item-action'>$req</a>";
                            }
                        $requisite .= "</div>
                    </div>";
                }

                if (!($countPre && $countCo)) {
                    $requisite = "<p class='text-center'>No subject set</p>";
                }
                $details .= "</div>
                <h6 class='text-start fw-bold mt-4'>PREREQUISITE | COREQUISITE SUBJECTS</h6>
                <hr class='mt-1'>
                <div class='flex-grow-1'>$requisite</div>
            </div>
        </div>";
    $content->main = $details;
    return $content;
}