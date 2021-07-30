<?php

function prepareEmptyProgramOptions($programs) {
    $prog_opt = "<div id='app-spec-options' class='col-4'>"
        ."<label>Options for Applied or Specialized Subjects</label>";
        foreach ($programs as $program) {
            $prog_code = $program->get_prog_code();
            $prog_name = $program->get_prog_name();
            $prog_opt .= "<div class='form-check'>"
                ."<input class='form-check-input' type='radio' name='prog_code[]' id='$prog_code' value='$prog_code' readonly>"
                ."<label class='form-check-label' for='$prog_name'>$prog_code</label></div>";
        }
    $prog_opt .= '</div>';
    return $prog_opt;
}

function getSubjectPageContent($state) {
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

    $link = 'subjectlist.php';
    // default link for Bread crumb 
    $links = "<li class='breadcrumb-item'><a href='subjectlist.php'>Subject</a></li>";
    // subject type input is editable by default 
    $sub_type_readonly = '';
    
    // prepare program options
    $prog_opt = prepareEmptyProgramOptions($programs);

    if ($state === 'add') {
        $header = '<h1>Add Subject</h1>';

        // prepare subject type options
        $sub_type_opt = '';
        foreach ($sub_opt as $id => $value) { 
            $sub_type_opt .= '<option value="'. $id .'" '. (($id == "core" ) ? "selected" : "") .'>'. $value .'</option>';
        }

        if (isset($_GET['prog_code'])) {                // add subject page is accessed from a program page
            // get program data
            $program = $admin->getProgram();
            $prog_name = $program->get_prog_desc();
            $prog_code = $program->get_prog_code();
            
            // update link data
            $link = "program.php?prog_code=$prog_code";
            $links = "<li class='breadcrumb-item'><a href='programlist.php'>Program</a></li>"
                . "<li class='breadcrumb-item'><a href='program.php?code='$prog_code'>$prog_code</a></li>";
            $header = "<h2>$prog_name</h2>"
                . "<h4>Add Subject</h4>";

            // overwrite program options
            $prog_opt = '';

            $sub_type_opt = '<option selected>Specialized</option>';
            $sub_type_readonly = 'disabled';

            // change subject type options
            // foreach ($sub_opt as $id => $value) { 
            //     $sub_type_opt .= '<option value="'. $id .'" '. (($id == "specialized" ) ? "selected" : "") .'>'. $value .'</option>';
            // }
            
        }

        $content->breadcrumb = "<nav aria-label='breadcrumb'>
                                <ol class='breadcrumb'>
                                    <li class='breadcrumb-item'><a href='index.html'>Home</a></li>$links
                                    <li class='breadcrumb-item active' aria-current='page'>Add Subject</li>
                                </ol>
                            </nav>";

        $form = $header. "<form id='add-subject-form' class='container' method='POST'>
                                <div class='row mt'>
                                    <div class='col-8'>
                                        <div class='form-group row'>
                                            <label for='subjectCode1' class='col-sm-2 col-form-label'>Subject code</label>
                                            <div class='col-sm-10'>
                                                <input type='text' name='code' class='form-control' id='sub-code' value=''>
                                            </div>
                                            <label for='subjectName1' class='col-sm-2 col-form-label'>Subject name</label>
                                            <div class='col-sm-10'>
                                                <input type='text' name='name' class='form-control' id='sub-name'>
                                            </div>
                                            <label for='subjectType1' class='col-sm-2 col-form-label'>Subject type</label>
                                            <div class='col-sm-10'>
                                                <select name='sub-type' class='form-select' id='sub-type' $sub_type_readonly>";
                                                $form .= $sub_type_opt ."</select>
                                            </div>
                                            <label for='subjectSemester1' class='col-sm-2 col-form-label'>Subject Semester</label>
                                            <div class='col-sm-10'>
                                                <select name='semester' class='form-select' id='semester'>";
                                                    foreach ($semesters as $id => $value) { 
                                                        $form .= "<option value='$id'". (($id == '0' ) ? 'selected' : '') ."'>$value</option>";
                                                    }
                                            $form .= "</select></div>
                                            <label for='grade-level' class='col-sm-2 col-form-label'>Grade Level</label>
                                            <div class='col-sm-10'>
                                                <select name='grade-level' class='form-select' id='grade-level'>";
                                                    foreach ($grd_lvl as $id => $value) { 
                                                        $form .= "<option value='$id'". (($id == '0' ) ? 'selected' : '') ."'>$value</option>";
                                                    }
                                            $form .= " </select></div>
                                        </div>
                                    </div>";
                                    
                                $form .= $prog_opt ."</div>
                                
                                <div class='col-4 mb-5'>
                                    <input id='req-btn' class='btn btn-outline-secondary ' data-bs-toggle='collapse' data-bs-target='#req-table-con' type='button' value='Prerequisite | Corequisite Subjects'>
                                </div>
                                
                                <div id='req-table-con' class='collapse'>
                                    <div id='grade11-table'>
                                        <div class='col-11'>
                                            <h3 class='pt-3'>Grade 11</h3>
                                        </div>
            
                                        <div class='col-1'>
                                            <input class='btn btn-outline-secondary' type='button' value='Clear'>
                                        </div>
                                        
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

                                            <tbody>";
                                                foreach ($subjectGrade11 as $subGr11) {
                                                    $sub_code = $subGr11->get_sub_code();
                                                    $sub_name = $subGr11->get_sub_name();
                                                    $sub_type = $subGr11->get_sub_type();
                                                    $form .= "<tr class='text-center'>
                                                        <td scope='col'>$sub_code</td>
                                                        <td scope='col'>$sub_name</td>
                                                        <td scope='col'>$sub_type</td>
                                                        <td scope='col'><input class='form-check-input' type='radio' name='radio-$sub_code' value='pre-$sub_code'></td>
                                                        <td scope='col'><input class='form-check-input' type='radio' name='radio-$sub_code' value='co-$sub_code'></td>
                                                    </tr>";
                                                }
                                            $form .= "</tbody>
                                            </table>
                                        </div>
    
                                        <div id='grade12-table' >
                                            <div class='col-11'>
                                                    <h3 class='pt-3'>Grade 12</h3>
                                                </div>
                    
                                                <div class='col-1'>
                                                    <input class='btn btn-outline-secondary' type='button' value='Clear'>
                                                </div>
                                                
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
                                                <tbody>";
                                                    foreach ($subjectGrade12 as $subGr12) {
                                                        $sub_code = $subGr12->get_sub_code();
                                                        $sub_name = $subGr12->get_sub_name();
                                                        $sub_type = $subGr12->get_sub_type();
                                                        $form .= "<tr class='text-center'>
                                                            <td scope='col'>$sub_code</td>
                                                            <td scope='col'>$sub_name</td>
                                                            <td scope='col'>$sub_type</td>
                                                            <td scope='col'><input class='form-check-input' type='radio' name='radio-$sub_code' value='pre-$sub_code'></td>
                                                            <td scope='col'><input class='form-check-input' type='radio' name='radio-$sub_code' value='co-$sub_code'></td>
                                                        </tr>";
                                                    }
                                                $form .= "</tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <input type='hidden' name='action' value='addSubject'>
                                <input class='btn btn-success form-control' type='submit' value='Add Subject'>
                            </form>";
        $content->form = $form;
        return $content;
    }

    // change editable state for the input fields
    $edit_btn_editable = 'disabled';
    $disabled = '';
    if ($state === 'view') {
        $disabled = 'disabled';
        $sub_type_readonly = 'disabled';
        $edit_btn_editable = '';
    }

    if ($state === 'edit' || $state === 'view') {
        // get the subject data
        $subject = $admin->getSubject();
        $sub_name = $subject->get_sub_name();
        $sub_type = $subject->get_sub_type();

        $header = "<h3>$sub_name</h3><hr>";
        $input_sub_with_prog = '';

        if ($sub_type === 'applied' ) {
            $sub_programs = $subject->get_programs();

            $prog_opt = "<div id='app-spec-options' class='col-4'>
                <label>Programs</label>";
        
            foreach ($programs as $program) {
                $prog_code = $program->get_prog_code();
                $prog_name = $program->get_prog_name();
                $sub_program_state = (in_array($prog_code, $sub_programs)) ? " original' checked readonly" : "'";

                $prog_opt .= "<div class='form-check'>
                    <input class='form-check-input $sub_program_state type='checkbox' name='prog_code[]' id='$prog_code' value='$prog_code'>
                    <label class='form-check-label' for='$prog_name'>$prog_code</label>
                </div>";
            }
            $prog_opt .= '</div>';
        }

        if (isset($_GET['prog_code'])) {                // view/edit page is accessed from a program page
            // get program data
            $program = $admin->getProgram();
            $prog_name = $program->get_prog_desc();
            $prog_code = $program->get_prog_code();
            $input_sub_with_prog = "<input type='hidden' name='prog_code' value='$prog_code'>";

            // prepare links for bread crumb
            $link = "program.php?prog_code=". $prog_code;
            $links = "<li class='breadcrumb-item'><a href='programlist.php'>Program</a></li>
                      <li class='breadcrumb-item'><a href='program.php?code='$prog_code'>$prog_code</a></li>";

            $header = "<h3>$sub_name</h3><hr><h6>$prog_name</h6>";

            $prog_opt = "<div id='app-spec-options' class='col-4'>
                <label>Programs</label>";
            foreach ($programs as $program) {
                $prog_code_data = $program->get_prog_code();
                $prog_name_data = $program->get_prog_name();
                $prog_opt .= "<div class='form-check'>
                    <input class='form-check-input' type='radio' name='prog_code[]' id='$prog_code_data' value='$prog_code_data' ". (($prog_code == $prog_code_data) ? 'checked': '') ." readonly>
                    <label class='form-check-label' for='$prog_name_data'>$prog_code_data</label>
                </div>";
            }
            $prog_opt .= '</div>';
        }
        $prereq = $subject->get_prerequisite();
        $coreq = $subject->get_corequisite();

        $content->breadcrumb = "<nav aria-label='breadcrumb'>
                                    <ol class='breadcrumb'>
                                        <li class='breadcrumb-item'><a href='index.html'>Home</a></li>$links
                                        <li class='breadcrumb-item active' aria-current='page'>Edit Subject</li>
                                    </ol>
                                </nav>";

        $form = $header ."<form id='add-subject-form' class='container' method='POST'>
                            $input_sub_with_prog
                            <div class='row mt'>
                                <div class='col-8'>
                                    <div class='form-group row'>
                                        <label for='subjectCode1'  class='col-sm-2 col-form-label'>Subject code</label>
                                        <div class='col-sm-10'>
                                            <input type='text' name = 'code' class='form-control' id='sub-code' value='". $subject->get_sub_code() ."' $disabled>
                                        </div>
                                        <label for='subjectName1' class='col-sm-2 col-form-label'>Subject name</label>
                                        <div class='col-sm-10'>
                                            <input type='text' name = 'name' class='form-control' id='sub-name' value='$sub_name' $disabled>
                                        </div>
                                        <label for='subjectType1' class='col-sm-2 col-form-label'>Subject type</label>
                                        <div class='col-sm-10'>
                                            <select name='sub-type' class='form-select' id='sub-type' $sub_type_readonly>";
                                                foreach ($sub_opt as $id => $value) { 
                                                    $form .= "<option value='$id' ". (($id == $subject->get_sub_type()) ? 'selected' : '') .">$value</option>";
                                                }
                                            $form .= "</select>
                                        </div>
                                        <label for='subjectSemester1' class='col-sm-2 col-form-label'>Subject Semester</label>
                                        <div class='col-sm-10'>
                                            <select name='semester' class='form-select' id='semester' $disabled>";
                                            foreach ($semesters as $id => $value) { 
                                                $form .= "<option value='$id' ". (($id == $subject->get_sub_semester()) ? 'selected' : '') .">$value</option>";
                                            }
                                            $form .= "</select>
                                        </div>
                                        <label for='grade-level' class='col-sm-2 col-form-label'>Grade Level</label>
                                        <div class='col-sm-10'>
                                            <select name='grade-level' class='form-select' id='grade-level' $disabled>";
                                            foreach ($grd_lvl as $id => $value) { 
                                                $form .= "<option value='$id' ". (($id == $subject->get_for_grd_level()) ? 'selected' : '') .">$value</option>";
                                            }
                                            $form .= "</select>
                                        </div>
                                    </div>
                                </div>";
                                
                                $form .= $prog_opt ."</div>
                                    <input id='req-btn' class='btn btn-outline-secondary ' data-bs-toggle='collapse' data-bs-target='#req-table-con' type='button' value='Prerequisite | Corequisite Subjects'>
                                
                                    <div id='req-table-con' class='collapse'>
                                        <div id='grade11-table'>
                                            <div class='col-11'>
                                                <h3 class='pt-3'>Grade 11</h3>
                                            </div>
                
                                            <div class='col-1'>
                                                <input class='btn btn-outline-secondary' type='button' value='Clear'>
                                            </div>
                                            
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
    
                                                <tbody>";
                                                    foreach ($subjectGrade11 as $subGr11) {
                                                        $sub_code = $subGr11->get_sub_code();
                                                        $sub_name = $subGr11->get_sub_name();
                                                        $sub_type = $subGr11->get_sub_type();
                                                        $form .= "<tr class='text-center'>
                                                            <td scope='col'>$sub_code</td>
                                                            <td scope='col'>$sub_name</td>
                                                            <td scope='col'>$sub_type</td>
                                                            <td scope='col'><input ". (in_array($sub_code, $prereq) ? 'checked' : '') ." class='form-check-input' type='radio' name='radio-$sub_code' value='pre-$sub_code' $disabled></td>
                                                            <td scope='col'><input ". (in_array($sub_code, $coreq) ? 'checked' : '') ." class='form-check-input' type='radio' name='radio-$sub_code' value='co-$sub_code' $disabled></td>
                                                        </tr>";
                                                    }
                                                    $form .="
                                                </tbody>
                                            </table>
                                        </div>
    
                                        <div id='grade12-table' >
                                            <div class='col-11'>
                                                    <h3 class='pt-3'>Grade 12</h3>
                                                </div>
                    
                                                <div class='col-1'>
                                                    <input class='btn btn-outline-secondary' type='button' value='Clear'>
                                                </div>
                                                
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
                                                    <tbody>";
                                                        foreach ($subjectGrade12 as $subGr12) {
                                                            $sub_code = $subGr12->get_sub_code();
                                                            $sub_name = $subGr12->get_sub_name();
                                                            $sub_type = $subGr12->get_sub_type();
                                                            $form .= "<tr class='text-center'>
                                                                <td scope='col'>$sub_code</td>
                                                                <td scope='col'>$sub_name</td>
                                                                <td scope='col'>$sub_type</td>
                                                                <td scope='col'><input ". (in_array($sub_code, $prereq) ? 'checked' : '') ." class='form-check-input' type='radio' name='radio-$sub_code' value='pre-$sub_code' $disabled></td>
                                                                <td scope='col'><input ". (in_array($sub_code, $coreq) ? 'checked' : '') ." class='form-check-input' type='radio' name='radio-$sub_code' value='co-$sub_code' $disabled></td>
                                                            </tr>";
                                                        }
                                                        $form .="
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <input type='hidden' name='action' value='updateSubject'>
                                    <a href='' role='button' class='btn btn-secondary cancel-btn $disabled d-none'>Cancel</a>
                                    <input class='btn btn-success form-control' type='submit' value='Save' $disabled>
                                    <button data-link='$link' id='edit-btn' class='btn btn-secondary' $edit_btn_editable>Edit</button>
                                </form>";
        $content->form = $form;
        return $content; 
    }
}
?>