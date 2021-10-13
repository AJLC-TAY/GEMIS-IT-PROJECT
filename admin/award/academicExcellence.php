<?php
include "../class/Administration.php";
$is_graduating = false;
if (isset($_GET['graduating']) && in_array(strtolower($_GET['graduating']), array('true', 'false'))) {
    $is_graduating = $_GET['graduating'];
}
$grd = ($is_graduating === 'true' ? "12" : "11");
$admin = new Administration();
$query = "SELECT report_id, stud_id, CONCAT(last_name,', ',first_name,' ',middle_name,' ', COALESCE(ext_name,'')) AS name, sex, "
        ."curr_code AS curriculum, prog_code AS program, general_average, CASE WHEN (general_average >= 90 AND general_average <= 94) THEN 'with' "
        ."WHEN (general_average >= 95 AND general_average <= 97) THEN 'high' WHEN (general_average >= 98 AND general_average <=100) "
        ."THEN 'highest' END AS remark FROM gradereport JOIN student USING (stud_id) LEFT JOIN enrollment e USING (stud_id) WHERE general_average >= 90 "
        ."AND enrolled_in = '$grd' AND e.sy_id = '9' "
        ."ORDER BY program DESC, general_average DESC;";
$result = $admin->query($query);
$excellence = [];
while ($row = mysqli_fetch_assoc($result)) {
    $excellence[$row['curriculum']][$row['program']]['students'][] = ['id' => $row['stud_id'], 'name' => $row['name'], 'ga' => $row['general_average'], 'sex' => ucwords($row['sex']), 'remark' => ucwords($row['remark'].' Honors')];
}

foreach($excellence as $curr => $prog_rec) {
    $total_count = [];
    foreach($prog_rec as $prog => $prog_list) {
        $excellence[$curr][$prog]['size'] = $total_count[] = count($prog_list['students']);
    }
}
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
            <li class="breadcrumb-item active">Academic Excellence</li>
        </ol>
    </nav>
    <div class="d-flex flex-column mb-3">
        <h6 class="fw-bold">Preview</h6>
        <h3>Academic Excellence Awards</h3>
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

            <h6 class="text-center m-0"><b>Academic Excellence Award</b></h6>
            <h6 class="text-center"><b>Senior High School Graduates</b></h6>
            <!-- 
                <div class="d-flex justify-content-between">
                    <p><b>AO</b> - Always Observed</p>
                    <p><b>SO</b> - Sometimes Observed</p>
                    <p><b>RO</b> - Rarely Observed</p>
                    <p><b>NO</b> - Not Observed</p>
                </div> -->

            <table class="table-bordered table w-100 table-sm text-center">
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
                            $track_row_span += $prog_data['size'];
                        }
                        echo "<tr><td rowspan='$track_row_span'>$track</td>";
                        $first_prog = array_key_first($val);
                        foreach ($val as $prog_code => $prog_data) {
                            if ($prog_code != $first_prog) {
                                echo "<tr>";
                            }
                            echo "<td rowspan='". $prog_data['size'] ."'>$prog_code</td>";
                            $stud_list = $prog_data['students'];
                            foreach($stud_list as $record) {
                                $first_rec  = $record[0]['id'];
                                if ($first_rec != $record['stud_id']) {
                                    echo "<tr>";
                                }
                                echo "<td class='text-start'>{$record['name']}</td>"
                                    ."<td>{$record['sex']}</td>"
                                    ."<td>{$record['remark']}</td>"
                                    ."<td>{$record['ga']}</td>";
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
                        <span id="signatory"><?php echo $signatory_desc?></span><br>
                        <span id="position"><?php echo $position_desc?></span>
                    </p>
                </div>
            </div>
        </li>
    </ul>
   
</div>