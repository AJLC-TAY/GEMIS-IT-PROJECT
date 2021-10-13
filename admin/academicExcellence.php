<?php
include_once("../inc/head.html");
include_once("../class/Administration.php");
$admin = new Administration();
// $excellence = $admin -> get
$query = "SELECT report_id, stud_id, CONCAT(last_name,', ',first_name,' ',middle_name,' ', COALESCE(ext_name,'')) AS name, sex, "
        ."curr_code AS curriculum, prog_code AS program, general_average, CASE WHEN (general_average >= 90 AND general_average <= 94) THEN 'with' "
        ."WHEN (general_average >= 95 AND general_average <= 97) THEN 'high' WHEN (general_average >= 98 AND general_average <=100) "
        ."THEN 'highest' END AS remark FROM gradereport JOIN student USING (stud_id) LEFT JOIN enrollment USING (stud_id) WHERE general_average >= 90 ORDER BY program DESC, general_average DESC;";
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

// return $excellence; 
// echo json_encode($excellence);

?>

<table class="table table-striped text-center">
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

