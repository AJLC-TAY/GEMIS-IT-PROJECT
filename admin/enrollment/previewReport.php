<?php
require "../class/Administration.php";
$admin = new Administration();
$tracks = $admin->getEnrollmentReportData();
$school_year = "2021 - 2022";
$signatory = "Alvin John Cutay";
$position = "Student";
$date = strftime('%Y-%m-%d', strtotime(date("F j, Y")));

$signatory_list = $admin->listSignatory();
$track_names = [];
$programs = [];
?>

<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="enrollment.php">Enrollment</a></li>
            <li class="breadcrumb-item active" aria-current="page">Report</li>
        </ol>
    </nav>
    <h3 class="fw-bold">Generate Enrollment Report</h3>
</header>
<!-- HEADER END -->
<!-- <form id='enroll-report-form' method='POST' action='test.php'> -->
<div class="container mt-4">
    <form id='enroll-report-form' method='POST' action='enrollment.php?page=report&type=pdf'>
        <!-- GENERAL REPORT INFO  -->
        <div class="card p-4 bg-white mb-3 w-75">
            <h5>Report Information</h5>
            <div class="row align-items-center mb-3">
                <div class="col-md-3">Report Title</div>
                <div class="col-md-9"><input name='report-title' class='form-control mb-0 me-3' value ='Enrollment Report'></div>
            </div>
            <div class="row align-items-center mb-3">
                <div class="col-md-3">School Year</div>
                <div class="col-md-9"><input name='school-year' class='form-control mb-0 me-3' value ='<?php echo $school_year; ?>'></div>
            </div>
            <div class="row align-items-center mb-3">
                <div class="col-md-3">Date</div>
                <div class="col-md-9"><input name='date' type='date' class='form-control mb-0 me-3' value ='<?php echo $date; ?>'></div>
            </div>
        </div>
        <!-- MAIN REPORT INFO -->
        <div class="card p-4 bg-white mb-3 w-75" >
            <?php

            $accepted_grand_total = [];
            $rejected_grand_total = [];

            foreach($tracks as $track_id => $track_value) {
                $track_names[] = $track_id;

                echo "<table class='table  table-sm mx-auto' style='width: 90%;'>";
                echo "<thead class='t>
                    <tr>
                        <th colspan='5'>
                            <h5>$track_id</h5>
                        
                        </th>
                    </tr>
                    <tr class='text-center'>
                        <th>Strand</th>
                        <th>Accepted</th>
                        <th>Rejected</th>
                    </tr>
                </thead>";
                echo "<tbody>";

                $accepted_count_list = [];
                $rejected_count_list = [];

                echo "<tr>";
                foreach($track_value as $tv_id => $tv_value) {
                    echo "<td>$tv_id</td>";
                    $rejected = $rejected_grand_total[] = $rejected_count_list[] = $tv_value[0] ?: 0;
                    $accepted = $accepted_grand_total[] = $accepted_count_list[] = $tv_value[1] ?: 0;
                    echo "<td class='py-2'><input name='tracks[$track_id][$tv_id][1]' class='form-control form-control-sm mb-0 me-3' value ='$accepted' ></td>";
                    echo "<td class='py-2'><input name='tracks[$track_id][$tv_id][0]' class='form-control form-control-sm mb-0 me-3' value ='$rejected' ></td>";
                    // Accepted sub total column
                    if ($tv_id == array_key_last($track_value)) { 			// if the element is the last key, calculate total
                        echo "</tr>";
                        echo "<tr>"
                            ."<td>Sub total</td>"
                            ."<td class='py-2'><input class='form-control form-control-sm mb-0' value ='".array_sum($accepted_count_list)."' ></td>"
                            ."<td class='py-2'><input class='form-control form-control-sm mb-0' value ='".array_sum($rejected_count_list)."' ></td>"
                        ."</tr>";
                    } else {
                        echo "</tr>";
                    }
                }

                    echo "</tbody>";
                echo "</table>";
                echo "<br>";
            }
            ?>
            <div class="container ">
                <div class="row align-items-center" >

                    <div class="col-2">
                        Total
                    </div>
                    <div class="col-5">
                        <input value="<?php echo array_sum($accepted_grand_total); ?>" type="text" class="form-control form-control-sm mb-0">
                    </div>
                    <div class="col-5">
                        <input value="<?php echo array_sum($rejected_grand_total); ?>" type="text" class="form-control form-control-sm mb-0">
                    </div>

                </div>
            </div>
        </div>
        <!-- SIGNATORY INFO -->

        <div class=" card p-4 bg-white w-75">
            <h5>Signatory</h5>
        
            <div class="d-flex flex-column">
                <input type="hidden" name="signatory" value='<?php echo $signatory; ?>'>
                <input type="hidden" name="position" value='<?php echo $position; ?>'>
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
        </div>

        <div class="d-inline-flex justify-content-end w-75 mt-4 p-0">
            <!-- <div class="col-auto"><a href='./enrollment/enrollmentReport.php' form='enroll-report-form' type='submit' class='form-control btn btn-success mb-0'>Generate document</a></div> -->
            <div class="col-auto p-0"><input form='enroll-report-form' type='submit' class='form-control btn btn-success mb-0' value='Generate document'></div>
        </div>
    </form>
</div>