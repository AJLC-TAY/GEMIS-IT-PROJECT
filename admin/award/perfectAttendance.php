<?php
include "../class/Administration.php";
$admin = new Administration();
$data = $admin->getPerfectAttendance();
$school_year = $_SESSION['school_year'];
$filename = "Perfect_Attendance_$school_year";
$date_desc = date("F j, Y");
$signatory_desc = $_POST['signatory'] ?? $_SESSION['User'];
$position_desc = $_POST['position'] ?? ($_SESSION['user_type'] == 'FA' ? "Award Coordinator" : "Administrator");
?>
<!DOCTYPE html>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="awardReport.php">Awards</a></li>
            <li class="breadcrumb-item active">Perfect Attendance Award</li>
        </ol>
    </nav>
    <div class="d-flex flex-column mb-3">
        <h6 class="fw-bold">Preview</h6>
        <h3>Perfect Attendance</h3>
        <hr class='m-1'>
        <p class='text-secondary'><?php echo $school_year; ?></p>
    </div>
</header>

<div class="d-flex-inline">
    <button onclick='generatePDF(`<?php echo $filename; ?>`)' class="btn btn-sm btn-primary">Download</button>
</div>
<div class="doc bg-white ms-2 mt-3 p-0 shadow overflow-auto">
    <ul class="template p-0 w-100">
        <li class="p-0 mb-0 mx-auto">
            <div class="row p-0 mx-1">
                <div class="col-3 p-0">
                    <img src="../assets/deped_logo.png" alt="DEPED Logo" title="DEPED Logo">
                </div>
                <div class="col-6 p-0 text-center">
                    <p>
                        Republic of the Philippines<br>
                        Department of Education<br>
                        Cordillera Administrative Region<br>
                        Baguio City Schools Division<br>
                        PINES CITY NATIONAL HIGH SCHOOL<br>
                        [SENIOR HIGH SCHOOL - Lucban Campus]<br>
                        Magsaysay Ave., Baguio City
                    </p>
                </div>
                <div class="col-3 p-0" style="text-align: right;">
                    <img src="../assets/school_logo.jpg" alt="PCNSH Logo" title="PCNSH Logo">
                </div>
            </div>

            <h6 class="text-center m-0"><b>Perfect Attendance</b></h6>
            <h6 class="text-center m-0"><small>SY <?php echo $school_year; ?></small></h6>
            <?php 
            if (empty($data)) {
                echo "<h4 class='my-5 text-center'>No students have perfect attendance</h4>";
            }
            foreach($data as $grd_level => $val) {
                foreach($val as $prog_code => $prog_data) {
                    echo "<table class='table-bordered table w-100 table-sm text-center my-3'>"
                        ."<thead class='text-center'>
                            <tr>
                                <th colspan='3'>$prog_code $grd_level</th>
                            </tr>
                            <tr>
                                <th>LRN</th>
                                <th>Student Name</th>
                                <th>Sex</th>
                            </tr>
                        </thead>";
                    echo "<tbody>";
                    foreach ($prog_data['students'] as $record) {
                        echo "<tr>
                            <td>{$record['lrn']}</td>
                            <td class='text-start ps-3'>{$record['name']}</td>
                            <td>{$record['sex']}</td>
                        </tr>";
                    }
                    echo "</tbody></table>";
                    
                }
            }
            ?>
            <div class="row">
                <div class="signatory-con col-6">
                    <p class="closing-remark mb-5">Prepared on <span id="date" class="fw-bold"><?php echo $date_desc; ?></span> by: </p>
                    <p class="signatory" style="text-align: center">
                        <span id="signatory"><?php echo $signatory_desc?></span><br>
                        <span id="position"><?php echo $position_desc?></span>
                    </p>
                </div>
            </div>
        </li>
    </ul>
</div>