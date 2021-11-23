<?php
include "../class/Administration.php";
$admin = new Administration();
$school_year = $_SESSION['school_year'];
$filename = "Other_awards_$school_year";
$date_desc = date("F j, Y");
$date = strftime('%Y-%m-%d', strtotime(date("F j, Y")));
$signatory_list = $admin->listSignatory();
$url = "getAction.php?data=otherAwards&sub_code={$_POST['sub_code']}&filter={$_POST['filter']}";
$report_title = $_POST['sub_code'];
?>

<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="awardReport.php">Awards</a></li>
            <li class="breadcrumb-item active">Other Awards</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between">
        <span>
            <h3><b>Other Award</b></h3>
            <p class='text-secondary'><?php echo $school_year; ?></p>
        </span>
    </div>
</header>
<div class="container">
    <form id='subject-award-form' class="report-form" method='POST' action='awardReport.php?type=ga'>
        <div class="row">
            <div class="col-lg-8">
                <div class="card bg-white p-3 mb-3" style="min-height: 490px;">
                    <small class="text-secondary mb-3">Unselect students to remove from the list</small>
                    <table id="ae-table" data-toggle="table" data-click-to-select="true" data-url="<?php echo $url; ?>" data-height="465" data-unique-id="id" class="table table-sm">
                        <thead>
<!--                            <th data-checkbox="true"></th>-->
                            <th scope='col' data-width="100" data-sortable="true" data-align="center" data-field='lrn'>LRN</th>
                            <th scope='col' data-width="220" data-sortable="true" data-halign="center" data-align="left" data-field='name'>Name</th>
                            <th scope='col' data-width="80" data-sortable="true" data-align="center" data-field='sex'>Sex</th>
                            <th scope='col' data-width="80" data-sortable="true" data-align="center" data-field='fg'>Grade</th>
                            <th scope='col' data-width="120" data-sortable="true" data-align="center" data-field='program'>Strand</th>
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
                            <input name='report-title' class='form-control form-control-sm mb-0 me-3' value ='AWARD FOR <?php echo $report_title; ?>'>
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
                                <option>Search user</option>
                                <?php
                                foreach($signatory_list as $element) {
                                    // echo "<option ". ($element['id'] == $_SESSION['id'] ? "selected" : "") ." class='signatory' data-name='{$element['name']}' data-position='{$element['position']}' value='{$element['id']}'>{$element['name']} - {$element['position']}</option>";
                                    echo "<option ". ($element->sign_id == 1 ? "selected" : "") ." class='signatory' data-name='{$element->name}' data-position='{$element->position}' value='{$element->sign_id}'>{$element->name} - {$element->position}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="row align-items-center mb-3">
                            <button type="submit" form="subject-award-form" class="btn btn-sm btn-success"><i class="bi bi-file-earmark-text me-2"></i>Generate</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
