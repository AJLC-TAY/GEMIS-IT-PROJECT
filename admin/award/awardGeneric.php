<?php
include "../class/Administration.php";
$admin = new Administration();
$data = $admin->getAwardDataFromSubject();
$school_year = $_SESSION['school_year'];
$filename = "Academic_Excellence_$school_year";
$date_desc = date("F j, Y");
$signatory_desc = $_POST['signatory'] ?? $_SESSION['User'];
$position_desc = $_POST['position'] ?? ($_SESSION['user_type'] == 'FA' ? "Award Coordinator" : "Administrator");
?>

<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="awardReport.php">Awards</a></li>
            <li class="breadcrumb-item active">Award for Research</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between">
        <span>
            <small class="fw-bold">Preview</small>
            <h3><b>Award for Research</b></h3>
            <p class='text-secondary'><?php echo $school_year; ?></p>
        </span>
        <div class="mt-4">
            <button onclick='generatePDF(`<?php echo $filename; ?>`)' class=" btn btn-primary"><i class="bi bi-download me-2"></i>Download</button>
        </div>
    </div>
</header>
<hr class='m-1'>

<div class="d-flex justify-content-center">
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

                <h6 class="text-center m-0"><b>AWARD FOR RESEARCH</b></h6>
                <h6 class="text-center m-0"><small>SY <?php echo $school_year; ?></small></h6>

                <?php
                foreach ($data as $grd_level => $val) {
                    foreach ($val as $prog_code => $prog_data) {
                        echo "<table class='table-bordered table w-100 table-sm text-center my-3'>"
                            . "<thead class='text-center'>
                            <tr>
                                <th colspan='3'>$prog_code $grd_level</th>
                            </tr>
                            <tr>
                                <th>Student Name</th>
                                <th>Sex</th>
                                <th>Grade</th>
                            </tr>
                        </thead>";
                        echo "<tbody>";
                        foreach ($prog_data['students'] as $record) {
                            echo "<tr>
                            <td class='text-start ps-3'>{$record['name']}</td>
                            <td>{$record['sex']}</td>
                            <td class='fw-bold'>{$record['fg']}</td>
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
                            <span id="signatory"><?php echo $signatory_desc ?></span><br>
                            <span id="position"><?php echo $position_desc ?></span>
                        </p>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>