<?php
require_once("../class/Administration.php");
$admin = new Administration();
$init_data = $admin->getInitSYData();
$track_program = $init_data['track_program'];
$subjects = $init_data['subjects'];
// $tracks = ['ACAD' => ["ABM" => "ABM desc", "HumSS" => "HumSS desc"], 'TVL' => ["BP" => "Bread & Pastry", "Elec" => "Electronics"]];
// $subjects = ["core" => ["BMath" => "Business Math", "Research" => "Research 01"], "spap" => ["Test" => "Test 01", "Test02" => "Test 02"]];
?>
<!DOCTYPE html>
<!-- HEADER -->
<header id="main-header">
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="schoolYear.php">School Year</a></li>
            <li class="breadcrumb-item active">Form</li>
        </ol>
    </nav>
    <div class="container px-4 text-center">
        <h2>Initialize School Year</h2>
    </div>
</header>
<!-- HEADER END -->
<form id="school-year-form" class="needs-validation" action="action.php" method="POST" novalidate>
    <div id="school-year-stepper" class="bs-stepper">
        <div id="header" class="bs-stepper-header w-50 mx-auto">
            <div class="step mx-mb-5" data-target="#step-1">
                <button type="button" class="btn step-trigger">
                    <span class="bs-stepper-label">Part</span>
                    <span class="bs-stepper-circle">1</span>
                </button>
            </div>
            <div class="line"></div>
            <div class="step mx-mb-5" data-target="#step-2">
                <button type="button" class="btn step-trigger">
                    <span class="bs-stepper-label">Part</span>
                    <span class="bs-stepper-circle">2</span>
                </button>
            </div>
            <div class="line"></div>
            <div class="step mx-mb-5" data-target="#step-3">
                <button type="button" class="btn step-trigger">
                    <span class="bs-stepper-label">Part</span>
                    <span class="bs-stepper-circle">3</span>
                </button>
            </div>
        </div>
        <div class="bs-stepper-content">
            <div id="step-1" class="content">
                <div class="card body w-100 h-auto p-4">
                    <!-- STEP 1 -->
                    <h4 class="fw-bold">Set School Year</h4>
                    <div class="form-group row mb-3">
                        <label for="" class="col-lg-5 col-form-label">School Year (Start-End)</label>
                        <div class="col-lg-7">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" name="start-year" class="form-control form-control-sm number w-100" placeholder="Start">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="end-year" class="form-control form-control-sm number w-100" placeholder="End">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="" class="col-lg-5 col-form-label">Academic Month (Start-End)</label>
                        <div class="col-lg-7">
                            <div class="row">
                                <?php 
                                    $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                    $months_opt = '';
                                    foreach($months as $id => $month) {
                                        $months_opt .= "<option value='$id'>$month</option>";
                                    }
                                ?>
                                <div class="col-md-6">
                                    <select name="start-month" id="start-month" class='form-select-sm form-select'>
                                        <option selected value="" disabled>Start Month</option>
                                        <?php echo $months_opt; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select name="end-month" id="end-month" class='form-select-sm form-select'>
                                        <option selected value="" disabled>End Month</option>
                                        <?php echo $months_opt; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- TRACKS -->
                    <p class="text-secondary p-0"><small><i class='bi bi-info-circle me-2'></i>Uncheck what not to include in this school year</small></p>
                    <div class="form-group row row-cols-1 row-cols-lg-2 g-3">
                        <div class="col">
                            <div class="container">
                                <label class="col-form-label col-auto fw-bold">Tracks</label>
                        
                                <div class="row border overflow-auto" style="height: 250px;">
                                    <ul id="track-list" class="list-group p-0">
                                        <?php 
                                        foreach($track_program as $id => $value) {
                                            $track_name = $value['track_name'];
                                            echo "<label class='list-group-item'><input name='track[name][]' class='form-check-input me-2 track-checkbox' type='checkbox' value='$id' checked>$track_name Track</label>";
                                        }
                                        ?>
                                
                                    </ul>
                                </div>
                                <div class="row mt-3 justify-content-between">
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input id="track-checkbox-all" type="checkbox" class="checkbox-all form-check-input" data-target-list="#track-list" checked>
                                            <label for="track-checkbox-all" class="form-check-label">Check | Uncheck All</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       </div>
                        <div class="col">
                            <div class="container">
                                <label class="col-form-label col-auto fw-bold">Strand</label>
                                <div class="row border overflow-auto" style="height: 250px;">
                                    <ul id="strand-list" class="list-group p-0">
                                       <?php 
                                        foreach($track_program as $id => $value) {
                                            $track_name = $value['track_name'];
                                            echo "<label data-track='$id' class='track-label list-group-item text-secondary'>$track_name</label>";
                                            $program = $value['programs'];
                                            foreach($program as $strand_id => $strand_value) {
                                                echo "<label class='list-group-item'><input name='track[$id][$strand_id]' class='form-check-input me-2 track-checkbox' data-track-id='$id' type='checkbox' checked>$strand_value</label>";
                                            }
                                        }
                                       ?>
                                    </ul>
                                </div>
                                <div class="row mt-3 justify-content-between">
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input id="strand-checkbox" type="checkbox" class="checkbox-all form-check-input" data-target-list="#strand-list" checked>
                                            <label for="strand-checkbox" class="form-check-label">Check | Uncheck All</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="schoolYear.php" class="btn me-1 btn-dark">Cancel</a>
                    <button role="button" id="sy-part-1" class="btn btn-primary next">Next</button>
                </div>
            </div>
            <!-- STEP 1 END -->
            <!-- STEP 2 -->
            <div id="step-2" class="content">
                <div class="card w-100 h-auto mt-4 p-4">
                    <h4 class="fw-bold">Subject Checklist</h4>
                    <p class="text-secondary p-0"><small><i class='bi bi-info-circle me-2'></i>Uncheck what not to include in this school year</small></p>
                    <!-- SUBJECT -->
                    <div class="form-group row row-cols-1 row-cols-lg-2 g-3">
                        <div class="col">
                            <div class="container">
                                <div class="row">
                                    <label class="col-form-label col-auto fw-bold px-0">Core Subject</label>
                                </div>
                                <div class="row">
                                    <form>
                                        <div class="d-flex px-0">
                                            <input id="search-core-subjects" type="text" class="form-control form-control-sm flex-grow-1 mb-0 me-3" placeholder="Search subject here ...">
                                            <input type="reset" data-target="#core" class="clear-btn form-control mb-0 me-1 w-auto btn-dark form-control-sm" value="Clear"/>
                                        </div>
                                    </form>
                                </div>
                                <div class="row border position-relative mt-3 overflow-auto" style="height: 350px;">
                                    <!-- SUB SPINNER -->
                                    <div id="core-spinner" class="sub-spinner bg-white position-absolute start-0 end-0 bottom-0 top-0" style="z-index: 3; display: none; ">
                                        <div class="spinner-border m-auto" style="position: absolute; top: 0; right: 0; bottom: 0; left:0;" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                    <!-- NO RESULTS MESSAGE -->
                                    <div class="d-flex justify-content-center" style="position: absolute; top: 0; right: 0; bottom: 0; left:0; z-index: 2;">
                                        <p id="core-empty-msg" class="no-result-msg my-auto" style="display: none;">No results found</p>
                                    </div>
                                    <ul id="core-list" class="list-group p-0">
                                        <?php
                                        if (!empty($subjects['core'])) {
                                            foreach($subjects['core'] as $id => $value) {
                                                echo "<label class='list-group-item'><input name='subjects[core][]' class='form-check-input me-2 track-checkbox' type='checkbox' checked value='$id'>$value</label>";
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <div class="row mt-3 justify-content-between">
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input id="core-checkbox" type="checkbox" class="checkbox-all form-check-input" data-target-list="#core-list" checked>
                                            <label for="core-checkbox" class="form-check-label">Check | Uncheck All</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="container">
                                <div class="row">
                                    <label class="col-form-label col-auto fw-bold px-0">Specialized | Applied Subject</label>
                                </div>
                                <form>
                                    <div class="row">
                                        <div class="d-flex px-0">
                                            <input id="search-spap-subjects" type="text" class="form-control form-control-sm flex-grow-1 mb-0 me-3" placeholder="Search subject here ...">
                                            <input type="reset" data-target="#spap" class="clear-btn form-control mb-0 me-1 w-auto btn-dark form-control-sm" value="Clear"/>
                                        </div>
                                    </div>
                                </form>
            
                                <div class="row border position-relative mt-3 overflow-auto" style="height: 350px;">
                                    <!-- SUB SPINNER -->
                                    <div id="spap-spinner" class="sub-spinner bg-white position-absolute start-0 end-0 bottom-0 top-0" style="z-index: 3; display: none; ">
                                        <div class="spinner-border m-auto" style="position: absolute; top: 0; right: 0; bottom: 0; left:0;" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                    <!-- NO RESULTS MESSAGE -->
                                    <div class="d-flex justify-content-center" style="position: absolute; top: 0; right: 0; bottom: 0; left:0; z-index: 2;">
                                        <p id="spap-empty-msg" class="no-result-msg my-auto" style="display: none;">No results found</p>
                                    </div>
                                    <ul id="spap-list" class="list-group p-0">
                                        <?php
                                        if (!empty($subjects['specialized'])) {
                                            foreach ($subjects['specialized'] as $id => $value) {
                                                echo "<label class='list-group-item'><input name='subjects[spap][]' class='form-check-input me-2 track-checkbox' type='checkbox' checked value='$id'>$value</label>";

                                            }
                                        }

                                        if (!empty($subjects['applied'])) {
                                            foreach ($subjects['applied'] as $id => $value) {
                                                echo "<label class='list-group-item'><input name='subjects[spap][]' class='form-check-input me-2 track-checkbox' type='checkbox' checked value='$id'>$value</label>";

                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <div class="row mt-3 justify-content-between">
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input id="spap-checkbox" type="checkbox" class="checkbox-all form-check-input" data-target-list="#spap-list" checked>
                                            <label for="spap-checkbox" class="form-check-label">Check | Uncheck All</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-row-reverse mt-4">
                    <div class="col-auto">
                         <a id="sy-part-2" class="btn btn-primary next">Next</a>
                    </div>
                    <div class="col-auto me-1">
                        <a class="btn btn-dark previous-sy">Back</a>
                    </div>
                </div>
            </div>
            <!-- STEP 2 END -->
            <!-- STEP 3 -->
            <div id="step-3" class="content">
                <div class="card w-100 h-auto mt-4 p-4">
                    <h4 class="fw-bold">Subject Schedule</h4>
                    <?php if (empty($_SESSION['sy_id'])) { ?>
                        <div style="min-height: 180px;" class="text-center d-flex align-items-center justify-content-center" >
                            <p>There is no set subject schedule yet. Please enter the subject schedule after initializing this school year.</p>
                        </div>
                    <?php }  else { ?>
                        <p class="text-secondary"><small><i class="bi bi-info-circle me-1"></i> Please select one of the following: </small></p>
                        <ul class="list-group">
                            <label class="list-group-item">
                                <input id="copy" name="schedule" type="radio" class="form-check-input" value="copy" required>
                                <b>(Recommended)</b> Copy subject schedule from SY <?php echo $_SESSION['school_year']; ?>
                            </label>
                            <label class="list-group-item">
                                <input id="copy" name="schedule" type="radio" class="form-check-input" value="reset" required>
                                 Have an empty subject schedule for this school year that is about to be created
                            </label>
                        </ul>

                    <?php }  ?>

                </div>
                <div class="d-flex flex-row-reverse mt-4">
                    <?php if (empty($_SESSION['sy_id'])) { ?>
                    <div class="col-auto me-1">
                        <input type="hidden" name="action" value="initAndSchedule">
                        <input type="submit" class="form-control btn btn-success" name="initAndSchedule" value="Initialize & Set Subject Schedule">
                    </div>
                    <?php }  else { ?>
                    <div class="col-auto me-1">
                        <input type="hidden" name="action" value="initializeSY">
                        <input type="submit" class="form-control btn btn-success" disabled name="initialize" value="Initialize">
                    </div>
                    <div class="col-auto me-1">
                        <input type="submit" class="form-control btn btn-secondary" disabled name="initAndSwitch" value="Initialize & Switch">
                    </div>
                    <div class="col-auto me-1">
                        <input type="hidden" name="action" value="initAndSchedule">
                        <input type="submit" class="form-control btn btn-secondary" disabled name="initAndSchedule" value="Initialize & Set Subject Schedule">
                    </div>
                    <?php } ?>
                    <div class="col-auto me-1">
                        <a href="#" class="btn btn-dark previous-sy">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- STEPPER END -->
</form>
