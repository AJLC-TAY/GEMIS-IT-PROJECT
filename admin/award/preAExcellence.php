<?php
include "../class/Administration.php";
$admin = new Administration();
//$excellence = $admin->getExcellenceAwardData($_SESSION['sy_id'], $_POST['grade'], $_POST['Highest-honor'], $_POST['High-honor'], $_POST['With-honor']);
$school_year = $_SESSION['school_year'];
$filename = "Academic_Excellence_$school_year";
$date_desc = date("F j, Y");
$date = strftime('%Y-%m-%d', strtotime(date("F j, Y")));
$highest = [$_POST['Highest-honor-min'], $_POST['Highest-honor-max']];
$high =  [$_POST['High-honor-min'], $_POST['High-honor-max']];
$with =  [$_POST['With-honor-min'], $_POST['With-honor-max']];
$grade = $_POST['grade'];
$signatory_list = $admin->listSignatory();
$url = "getAction.php?data=academicExcellence&sy_id={$_SESSION['sy_id']}&semester={$_POST['semester']}&grade=$grade".
        "&highest_min={$highest[0]}&highest_max={$highest[1]}&high_min={$high[0]}&high_max={$high[1]}&with_min={$with[0]}&with_max={$with[1]}";
?>

<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="awardReport.php">Awards</a></li>
            <li class="breadcrumb-item active">Academic Excellence</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between">
        <span>
            <h3><b>Academic Excellence Award</b></h3>
            <p class='text-secondary'><?php echo $school_year; ?></p>
        </span>
    </div>
</header>
<div class="container">
    <form id='academic-excellence-form' class="report-form" method='POST' action='awardReport.php?type=ae&grade=<?php echo $grade; ?>'>
        <div class="row">
            <div class="col-lg-8">
                <div class="card bg-white p-3 mb-3" style="min-height: 490px;">
                    <div class="d-flex justify-content-between align-content-center">
                        <span>
                            <h6 class="mb-0"><b>Grade <?php echo $grade; ?></b> Student List</h6>
                        </span>
                        <span></span>
                    </div>
                    <small class="text-secondary mb-3">Unselect students to remove from the list</small>
                    <table id="ae-table" data-toggle="table" data-click-to-select="true" data-url="<?php echo $url; ?>" data-height="465" data-unique-id="id" class="table table-sm">
                        <thead>
<!--                            <th data-checkbox="true"></th>-->
                            <th scope='col' data-width="220" data-sortable="true" data-halign="center" data-align="left" data-field='name'>Name</th>
                            <th scope='col' data-width="80" data-sortable="true" data-align="center" data-field='sex'>Sex</th>
                            <th scope='col' data-width="120" data-sortable="true" data-align="center" data-field='program'>Strand</th>
                            <th scope='col' data-width="100" data-sortable="true" data-align="center" data-field='ga'>General Average</th>
                            <th scope='col' data-width="80" data-sortable="true" data-align="center" data-field='remark'>Remark</th>
                            <th scope='col' data-width="100" data-align="center" data-field='action'>Action</th>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card bg-white p-3 mb-3">
                    <h6>Report Information</h6>
                    <div class="container">
                        <!-- GENERAL REPORT INFO  -->
                        <div class="row align-items-center mt-3 mb-3">
                            <label class="form-label px-0">Report Title</label>
                            <input name='report-title' class='form-control form-control-sm mb-0 me-3' value ='Academic Excellence Report'>
                        </div>
                        <div class="row align-items-center mb-3">
                            <label class="form-label px-0">School Year</label>
                            <input name='school-year' class='form-control form-control-sm mb-0 me-3' value ='<?php echo $school_year; ?>'>
                        </div>
                        <div class="row align-items-center mb-3">
                            <label class="form-label px-0">Date</label>
                            <input name='date' type='date' class='form-control form-control-sm mb-0 me-3' value ='<?php echo $date; ?>'>
                        </div>
                        <div class="row align-items-center mb-3">
                            <label class="form-label px-0">Signatory</label>
                            <input type="hidden" name="signatory">
                            <input type="hidden" name="position">
                            <select class="select2 px-0 form-select form-select-sm" id="id-no-select" required>
                                <option selected>Search user</option>
                                <?php
                                foreach($signatory_list as $element) {
                                    // echo "<option ". ($element['id'] == $_SESSION['id'] ? "selected" : "") ." class='signatory' data-name='{$element['name']}' data-position='{$element['position']}' value='{$element['id']}'>{$element['name']} - {$element['position']}</option>";
                                    echo "<option ". ($element->sign_id == $_SESSION['id']  ? "selected" : "") ." class='signatory' data-name='{$element->name}' data-position='{$element->position}' value='{$element->sign_id}'>{$element->name} - {$element->position}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="row align-items-center mb-3">
                            <button type="submit" form="academic-excellence-form" class="btn btn-sm btn-success"><i class="bi bi-file-earmark-text me-2"></i>Generate</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
