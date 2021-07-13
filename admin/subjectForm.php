<?php

function getSubjectPageContent($state) {
    $admin = new Administration();
    
    if ($state === 'add') {
        $content = new stdClass();
        if (isset($_GET['code'])) {
            $program = $admin->getProgram();
            $prog_name = $program->get_prog_desc();
            $prog_code = $program->get_prog_code();
            $subjects = [];
            $content->breadcrumb = '<nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                            <li class="breadcrumb-item"><a href="programlist.php">Program</a></li>
                                            <li class="breadcrumb-item"><a href="program.php?code='.$prog_code.'">'.$prog_name.'</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
                                        </ol>
                                    </nav>'; 

            $form = '<h2>'.$prog_code.' | '.$prog_name.'</h2>
                              <h4>Add Subject</h4>
                                <form id="add-subject-form" class="container" method="POST">
                                    <input type="hidden" name="prog_code" value="'.$prog_code.'">
                                    <div class="row mt">
                                        <div class="col-10">
                                            <div class="form-group row">
                                                <label for="subjectCode1"  class="col-sm-2 col-form-label">Subject code</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name = "code" class="form-control" id="sub-code" value="">
                                                </div>
                                                <label for="subjectName1" class="col-sm-2 col-form-label">Subject name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name = "name" class="form-control" id="sub-name">
                                                </div>
                                                <label for="subjectType1" class="col-sm-2 col-form-label">Subject type</label>
                                                <div class="col-sm-10">
                                                    <select name="sub-type" class="form-select" id="sub-type" disabled>
                                                        <option value="core">Core</option>
                                                        <option value="applied">Applied</option>
                                                        <option value="specialized" selected>Specialized</option>
                                                    </select>
                                                </div>
                                                <label for="subjectSemester1" class="col-sm-2 col-form-label">Subject Semester</label>
                                                <div class="col-sm-10">
                                                    <select name="semester" class="form-select" id="semester">
                                                        <option value="0" selected>-- Select semester --</option>
                                                        <option value="1">1st Semester</option>
                                                        <option value="2">2nd Semester</option>
                                                    </select>
                                                </div>
                                                <label for="grade-level" class="col-sm-2 col-form-label">Grade Level</label>
                                                <div class="col-sm-10">
                                                    <select name="gradeLevel" class="form-select" id="grade-level">
                                                            <option selected>-- Select grade level --</option>
                                                            <option value="11">11</option>
                                                            <option value="12">12</option>
                                                        </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input id="req-btn" class="btn btn-outline-secondary " data-bs-toggle="collapse" data-bs-target="#req-table-con" type="button" value="Prerequisite | Corequisite Subjects">
                                
                                    <div id="req-table-con" class="collapse">
                                    <div class="col-12">
                                        <div class="requisites d-flex flex-column justify-content-between">
                                            <div class="card">
                                                <div class="card-header">Prerequisite</div>
                                                <div class="requisite card-body">
                                                    <div class="input-group mb-3">
                                                        <div class="search-con">
                                                            <input id="pre" type="search" class="form-control" placeholder="Search subject here">
                                                            <ul id="pre-results" class="dropdown-menu" aria-labelledby="dropdownMenuButton"></ul>
                                                        </div>   
                                                    
                                                    </div>
                                                    <ul id="prerequisite" class="added-sub list-group">
                                                        <li id="pre-msg" class="no-subject-msg list-group-item border border-1 d-flex justify-content-center align-items-center">No subjects yet.</li>';
                                                        foreach ($subjects as $sub) {
                                                            $form .= '<li class="subject list-group-item d-flex justify-content-between align-items-center"><option value="">'.$sub.'</option>
                                                            <button type="btn" class="btn btn-danger radius-50"><img src=""></button></li>';
                                                        }
                                                    $form .= '
                                                    </ul>
                                                </div>
                                            </div>

            
                                            <div class="card">
                                                <div class="card-header">Corequisite</div>
                                                <div class="requisite card-body">
                                                    <div class="input-group mb-3">
                                                        <div class="search-con">
                                                            <input id="co" type="search" class="form-control" placeholder="Search subject here">
                                                            <ul id="co-results" class="dropdown-menu" aria-labelledby="dropdownMenuButton"></ul>
                                                        </div>   
                                                    </div>
                                                    <ul id="corequisite" class="added-sub list-group">
                                                    <li id="co-msg" class="no-subject-msg border border-1 d-flex justify-content-center align-items-center">No subjects yet.</li>';
                                                        foreach ($subjects as $sub) {
                                                            $form .= '<li class="subject list-group-item d-flex justify-content-between align-items-center"><option value="">'.$sub.'</option>
                                                            <button type="btn" class="btn btn-danger btn-sm"><img src=""></button></li>';
                                                        }
                                                    $form .='
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    <input type="hidden" name="action" value="addSubject">
                                    <input class="btn btn-success form-control" type="submit" value="Add Subject">
                                </form>';
            $content->form = $form;
            return $content; 
        }

        $programs = $admin->listPrograms();
        $content->breadcrumb = '<nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                        <li class="breadcrumb-item"><a href="subjectlist.php">Subject</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
                                    </ol>
                                </nav>'; 

        $form= '<h1>Add Subject</h1>
                            <form id="add-subject-form" class="container" method="POST">
                                <div class="row mt">
                                    <div class="col-8">
                                        <div class="form-group row">
                                            <label for="subjectCode1" class="col-sm-2 col-form-label">Subject code</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="code" class="form-control" id="sub-code" value="">
                                            </div>
                                            <label for="subjectName1" class="col-sm-2 col-form-label">Subject name</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="name" class="form-control" id="sub-name">
                                            </div>
                                            <label for="subjectType1" class="col-sm-2 col-form-label">Subject type</label>
                                            <div class="col-sm-10">
                                                <select name="sub-type" class="form-select" id="sub-type">
                                                    <option value="core" selected>Core</option>
                                                    <option value="applied">Applied</option>
                                                    <option value="specialized">Specialized</option>
                                                </select>
                                            </div>
                                            <label for="subjectSemester1" class="col-sm-2 col-form-label">Subject Semester</label>
                                            <div class="col-sm-10">
                                                <select name="semester" class="form-select" id="semester">
                                                    <option value="0" selected>-- Select semester --</option>
                                                    <option value="1">1st Semester</option>
                                                    <option value="2">2nd Semester</option>
                                                </select>
                                            </div>
                                            <label for="grade-level" class="col-sm-2 col-form-label">Grade Level</label>
                                            <div class="col-sm-10">
                                                <select name="grade-level" class="form-select" id="grade-level">
                                                        <option selected>-- Select grade level --</option>
                                                        <option value="11">11</option>
                                                        <option value="12">12</option>
                                                    </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="app-spec-options" class="col-4">
                                        <label>Options for Applied or Specialized Subjects</label>';

                                    foreach ($programs as $program) {
                                        $prog_code = $program->get_prog_code();
                                        $prog_name = $program->get_prog_name();
                                        $form .= '
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="prog_code" id="'.$prog_code.'" value="'.$prog_code.'" checked disabled>
                                            <label class="form-check-label" for="'.$prog_name.'">'.$prog_code.'</label>
                                        </div>';
                                    }
                                       $form .='
                                    </div>
                                </div>
                                
                                <input id="req-btn" class="btn btn-outline-secondary " data-bs-toggle="collapse" data-bs-target="#req-table-con" type="button" value="Prerequisite | Corequisite Subjects">
                                
                                <div id="req-table-con" class="collapse">
                                    <h3 class="pt-3">Grade 11</h3>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th scope="col">CODE</th>
                                                <th scope="col">SUBJECT NAME</th>
                                                <th scope="col">TYPE</th>
                                                <th scope="col">PRE</th>
                                                <th scope="col">CO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="text-center">
                                                <th scope="row">OCC</th>
                                                <td>Oral Communication in Context</td>
                                                <td>CORE</td>
                                                <td><input class="form-check-input" type="radio" name="Radios" id="radios5" value="pre"></td>
                                                <td><input class="form-check-input" type="radio" name="Radios" id="radios6" value="co"></td>
                                            </tr>

                                            <tr class="text-center">
                                                <th scope="row">MATH</th>
                                                <td>BASIC MATHEMATICS</td>
                                                <td>CORE</td>
                                                <td><input class="form-check-input" type="radio" name="Radios" id="radios5" value="pre"></td>
                                                <td><input class="form-check-input" type="radio" name="Radios" id="radios6" value="co"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <input type="hidden" name="action" value="addSubject">
                                <input class="btn btn-success form-control" type="submit" value="Add Subject">
                            </form>';
        $content->form = $form;
        return $content;
    }

    if ($state === 'edit') {
        if (isset($_GET['program'])) {

        } 
    }
}
?>