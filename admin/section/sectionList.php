<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sections</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between mb-3">
        <h3 class="fw-bold">Section</h3>
        <button id="add-btn" class="btn btn-success" title='Add section'>Add Section</button>
    </div>
</header>
<!-- HEADER END -->
<!-- SCHOOL YEAR TABLE -->
<div class="container mt-1">
    <div class="card w-100 h-auto bg-light">
        <table id="table" class="table-striped table-sm">
            <thead class='thead-dark'>
                <div class="d-flex justify-content-between mb-3">
                    <!-- SEARCH BAR -->
                    <span class="flex-grow-1 me-3 ">
                        <input id="search-input" type="search" class="form-control m-0" placeholder="Search something here">
                    </span>
                    <div class="button-con my-auto">
                        <button class="btn btn-danger btn-sm">Archive</button>
                        <button class="btn btn-dark btn-sm">Export</button>
                    </div>
                </div>
                <tr>
                    <th data-checkbox="true"></th>
                    <th scope='col' data-width="150" data-align="center" data-field="code">Section Code</th>
                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="name">Section Name</th>
                    <th scope='col' data-width="50" data-align="center" data-sortable="true" data-field="sy">SY ID</th>
                    <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="grd_level">Grade Level</th>
                    <th scope='col' data-width="100" data-align="center" data-field="max_stud">Maximum Students</th>
                    <th scope='col' data-width="100" data-align="center" data-field="stud_no">Student Number</th>
                    <th scope='col' data-width="150" data-align="center" data-field="teacher_id">Adviser (ID)</th>
                    <th scope='col' data-width="250" data-align="center" data-field="action">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<!-- MODAL  -->
<div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="modal addCurriculum" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form id="section-form" method="POST">
            <input type="hidden" name="action" vaue="editSection">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Add New Section</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><small class='text-secondary'>Please complete the following: </small></p>
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
    let isViewPage = false;
</script>