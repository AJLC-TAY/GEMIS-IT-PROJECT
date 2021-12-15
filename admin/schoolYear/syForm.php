<?php
require_once("../class/Administration.php");
$admin = new Administration();
$init_data = $admin->getInitSYData();
$track_program = $init_data['track_program'];
$subjects = $init_data['subjects'];
?>
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
    <input type="hidden" name="action" value="initializeSY">
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
        <div class="form-group row mb-3">
            <label for="default-days" class="col-lg-5 col-form-label">Default academic days per month</label>
            <div class="col-lg-7">
                <input placeholder="Enter default days (ex. 4 or 20)" id="default-days" type="number" name="default-days" class="form-control form-control-sm number">
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
    <div class="row justify-content-lg-end mt-4">
        <div class="col-auto px-1">
            <a role="button" href="schoolYear.php" class="w-100 btn my-1 btn-dark">Cancel</a>
        </div>
        <div class="col-auto px-1">
            <input type="submit" name="initAndSwitch" class="btn btn-secondary my-1" value="Initialize & Switch">
        </div>
        <div class="col-auto px-1">
            <input type="submit" name="initialize" class="btn btn-success my-1" value="Initialize">
        </div>
    </div>
</form>