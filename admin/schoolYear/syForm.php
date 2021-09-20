<?php 
    include_once("../class/Administration.php");
    $admin = new Administration();
    $action = $_GET['action'];
    $quarter_list = array('0' => '-- Select quarter --', '1' => 'First quarter', 
                          '2' => 'Second quarter', '3' => 'Third quarter', '4' => 'Fourth quarter');  
    $quarter_opt = '';   
    $enroll_stat_msg = "No enrollment"; 
    $display = 'd-none';

    if ($action == 'init') {
        $state = 'disabled';
        $button = 'Submit';
        $quarter_opt = "<option value='1'>First Quarter</option>";
    } else if ($action == 'edit') {
        $sy_data = $admin->get_sy();
        $state = '';
        $button = 'Save';
        $display = '';

        $current_qtr = '';
        foreach($quarter_list as $id => $value) {
            $quarter_opt .= "<option value='$id' ". (($id == $current_qtr) ? "selected" : "") .">$value</option>";
        }
    }
?>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="schoolYear.php">School Year</a></li>
            <li class="breadcrumb-item active" aria-current="page">Initialize</li>
        </ol>
    </nav>
    <h3 class="fw-bold">Initialize School Year</h3>
</header>
<!-- HEADER END -->
<!-- SUBJECT TABLE -->
<div class="container mt-4">
    <div class="card h-auto bg-light mx-auto" style='width: 80%;'>
        <p><small class='text-secondary'>Please complete the following: </small></p>
        <form id='school-year-form' action="action.php" method='POST'><!--
            <!-- CURRICULUM CODE -->
            <div class="form-group row">
                <label for="curr-code-input" class="col-lg-3 col-form-label">Curriculum code</label>
                <div class="col-lg-9">
                    <select name="curr-code" id="curr-code-input" class="form-select">
                        <?php 
                            $curriculum_list = $admin->listCurriculum("curriculum");
                            echo "<option selected>-- Select curriculum --</option>";
                            foreach($curriculum_list as $curriculum) {
                                $value = $curriculum->get_cur_code();
                                $name = $curriculum->get_cur_name();
                                echo "<option value='$value'>$value</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
            <!-- CURRICULUM CODE END -->
            <!-- SCHOOL YEAR -->
            <div class="form-group row">
                <label for="" class="col-lg-3 col-form-label">School Year (Start-End)</label>
                <div class="col-lg-9">
                    <div class="d-flex align-items-center">
                        <input type="text" name="start-year" class="form-control number" placeholder="Start">
                        <span class='mb-3 p-2 text-center'><i class="bi bi-dash"></i></span>
                        <input type="text" name="end-year" class="form-control number" placeholder="End">
                    </div>
                </div>
            </div>
            <!-- SCHOOL YEAR END -->
            <!-- GRADE LEVEL -->
            <div class="form-group row">
                <label for="grade-level-input" class="col-lg-3 col-form-label">Grade Level</label>
                <div class="col-lg-9">
                    <select name="grade-level" id="grade-level-input" class="form-select">
                    <?php 
                        $grd_lvl_list = array('0' => '-- Select grade level --', '11' => '11', '12' => '12' );  
                        $grd_opt = '';
                        foreach($grd_lvl_list as $id => $value) {
                            $grd_opt .= "<option value='$id'". ($id == 0 ? "selected" : "") .">$value</option>";
                        }
                        echo $grd_opt;
                    ?>
                    </select>
                </div>
            </div>
            <!-- GRADE LEVEL END -->
            <!-- CURRENT QUARTER -->
            <div class="form-group row <?php echo $display?>">
                <label for="quarter-level-input" class="col-lg-3 col-form-label">Current Quarter</label>
                <div class="col-lg-9">
                    <select name="quarter" id="quarter-level-input" class="form-select" <?php echo $state; ?>>
                        <?php echo $quarter_opt;?>
                    </select>
                </div>
            </div>
            <!-- CURRENT QUARTER END -->
            <!-- ENROLLMENT STATUS -->
            <div class="form-group row">
                <label for="enrollment-status" class="col-lg-3 col-form-label">Enrollment</label>
                <div class="col-lg-9">
                    <div class="mt-1 my-auto">
                        <div class="form-check y-auto ms-2 me-3 d-flex align-items-center">
                            <input id='enrollment-input' name='enrollment' class="form-check-input my-auto me-3" type="checkbox" id="enrollment-btn" title='Start/End enrollment'>
                            <!-- <label id='enrollment-status' class="form-check-label" for="flexSwitchCheckDefault"><?php //echo $enroll_stat_msg; ?></label> -->
                            <label class="form-check-label" for="enrollment-input">Setup enrollment after submission.</label>
<!--                            <label class="form-check-label" for="enrollment-input">Enable enrollment. Accept enrollees after submitting.</label>-->
                        </div>
                    </div>
                </div>
            </div>
            <!-- ENROLLMENT STATUS END -->
            <!-- DECISION -->
            <div class="form-group row mt-5">
                <div class="d-flex justify-content-end">
                    <input type='hidden' name='action' value='initializeSY'>
                    <a href='schoolYear.php' class='btn btn-dark me-2'>Cancel</a>
                    <input type='submit' form='school-year-form' class='btn btn-success' value='<?php echo $button; ?>'>
                </div>
            </div>
            <!-- DECISION END -->
        </form>
    </div>
</div>