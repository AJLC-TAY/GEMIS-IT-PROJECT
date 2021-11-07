<?php
include "../class/Administration.php";
$admin = new Administration();
$excellence = $_POST['excellence'];
$school_year = $_SESSION['school_year'];
$filename = "Academic_Excellence_$school_year";
$signatory_desc = $_POST['signatory'] ?? $_SESSION['User'];
$position_desc = $_POST['position'] ?? ($_SESSION['user_type'] == 'FA' ? "Award Coordinator" : "Administrator");$date_desc = date("F j, Y");

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
            <small class="fw-bold">Preview</small>
            <h3><b>Academic Excellence Award</b></h3>
            <p class='text-secondary'><?php echo $school_year; ?></p>
        </span>
        <div class="mt-4">
            <button onclick='generatePDF(`<?php echo $filename; ?>`)' class=" btn btn-primary"><i class="bi bi-download me-2"></i>Download</button>
        </div>
    </div>
</header>
<hr class='m-1'>


<div class="d-flex justify-content-center">
    <div class="doc bg-white ms-2 mt-3 p-0 shadow overflow-auto ">
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

                <h6 class="text-center m-0"><b>Academic Excellence Award</b></h6>
                <h6 class="text-center"><b><?php (($_GET['grade'] && $_GET['grade']) == '12') ? "Senior High School Graduates" : "<br>"; ?></b></h6>
                <p class='text-secondary text-center'>SY <?php echo $school_year; ?></p>

                <table class="table-bordered table w-100 table-sm text-center mt-3">
                    <thead class="text-center">
                        <tr>
                            <th>SH School</th>
                            <th>Strand</th>
                            <th>Name of Student</th>
                            <th>Award</th>
                            <th>Sex</th>
                            <th>Gen. Ave.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($excellence as $track => $val) { // K12A => []
                            # get tracks rowspan
                            $track_row_span = 0;
                            foreach ($val as $prog_code => $prog_data) {

                                $track_row_span += count($prog_data['students']);
                            }
                            echo "<tr><td rowspan='$track_row_span'>$track</td>";
                            $first_prog = array_key_first($val);
                            foreach ($val as $prog_code => $prog_data) {
                                if ($prog_code != $first_prog) {
                                    echo "<tr>";
                                }
                                echo "<td rowspan='" . count($prog_data['students']) . "'>$prog_code</td>";
                                $stud_list = $prog_data['students'];
                                foreach ($stud_list as $id => $record) {
                                    $first_rec  = array_key_first($stud_list);
                                    if ($first_rec != $id) {
                                        echo "<tr>";
                                    }
                                    echo "<td class='text-start'>{$record['name']}</td>"
                                        . "<td>{$record['sex']}</td>"
                                        . "<td>{$record['remark']}</td>"
                                        . "<td>{$record['ga']}</td>";
                                    echo "</tr>";
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
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