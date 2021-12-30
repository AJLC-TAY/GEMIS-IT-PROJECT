<?php
$user = '';
switch ($_SESSION['user_type']) {
    case 'AD':
        include_once("../class/Administration.php");
        $user = new Administration();
        break;
    case 'FA':
        include_once("../class/Faculty.php");
        $user = new FacultyModule();
        break;
}
$program_list = $user->listPrograms("program");
$filters =  $user->getEnrollFilters();
?>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="section.php">Sections</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add</li>
        </ol>
    </nav>
    <div class="mb-2 d-flex justify-content-between">
        <span>
            <h3 class="fw-bold">Section Form</h3>
            <p><small class='text-secondary'>Please complete the following: </small></p>
        </span>
    </div>
</header>
<!-- HEADER END -->
<form id="section-form-page" method="POST" action="action.php">
    <input type="hidden" name="action" value="addSection">

    <div class="container card w-100 ms-0 bg-white mb-3">
        <div class="row">
            <div class="form-group col-sm-6">
                <div class="form-row row d-none">
                    <label for="sect-code" class="col-lg-5 col-form-label">Code</label>
                    <div class="col-lg-7">
                        <input value="" type="text" name="code" class="form-control" id="sect-code" placeholder="Enter unique code">
                    </div>
                </div>
                <div class="form-row row">
                    <label for="section-name" class="col-lg-4 col-form-label">Section Name</label>
                    <div class="col-lg-8">
                        <input id='section-name' name="section-name" class='form-control' maxlength="250" placeholder="Enter section name">
                    </div>
                </div>
                <div class="form-row row">
                    <label for="grade-level" class="col-lg-4 col-form-label">Grade Level</label>
                    <div class="col-lg-8">
                        <select id="grade-level" class='form-select' name='grade-level'>
                            <?php
                            $grade_level_list = ["11" => "11", "12" => "12"];
                            foreach ($grade_level_list as $id => $value) {
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
                    <label for="adviser" class="col-lg-4 col-form-label">Class Adviser (Optional)</label>
                    <div class="col-lg-8">
                        <select name="adviser" id="adviser" class="form-select">
                            <option value="*">-- Select faculty --</option>
                            <?php
                            $faculty_list = $user->listNotAdvisers();
                            foreach ($faculty_list as $faculty) {
                                echo "<option value='{$faculty['teacher_id']}'>T. {$faculty['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="container">
            <div id="">
                <div class="d-flex justify-content-between mb-3">
                    <!-- SEARCH BAR -->
                    <span class="flex-grow-1 me-3">
                        <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
                    </span>
                    <div>
                        <div class="input-group input-group-sm mb-3">
                            <label class="input-group-text " for="tracks">Strand</label>
                            <select id="program" class='form-select mb-0 filter-form' name='program'>
                                <option value="*" selected>All</option>
                                <?php
                                foreach ($program_list as $program) {
                                    $prog_code = $program->get_prog_code();
                                    $prog_name = $program->get_prog_desc();
                                    echo "<option value='$prog_code'>$prog_name</option>";
                                }
                                ?>
                            </select>
                            <label class="input-group-text " for="tracks">Grade Level</label>
                            <select id="grade-level-filter" class='form-select mb-0 filter-form' name='grade-lvl-filter'>
                            <option value="*" selected>All</option>
                            <?php
                            $grade_level_list = ["11" => "11", "12" => "12"];
                            foreach ($grade_level_list as $id => $value) {
                                echo "<option value='$id'>$value</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <table id="section-enrollees-table" data-toolbar="#toolbar" class="table-striped">
                <thead class='thead-dark'>
                    <tr>
                        <th data-checkbox="true"></th>
                        <th scope='col' data-width="100" data-halign="center" data-align="center" data-sortable="false" data-field="LRN">LRN</th>
                        <th scope='col' data-width="235" data-halign="center" data-align="center" data-sortable="true" data-field="name">Name</th>
                        <th scope='col' data-width="50" data-align="center" data-sortable="true" data-field="grade">Level</th>
                        <th scope='col' data-width="75" data-align="center" data-sortable="true" data-field="strand">Strand</th>
                        <th scope='col' data-width="75" data-align="center" data-sortable="true" data-field="section">Section</th>
                        <th scope='col' data-width="100" data-align="center" data-field="action">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="ms-0 d-flex justify-content-end">
        <input type="submit" class="btn btn-outline-secondary me-1" value="Submit & Create another" disabled />
        <input type="submit" class="btn btn-success" value="Create" />
    </div>
</form>
<script type="text/javascript">
    let isViewPage = false;
</script>