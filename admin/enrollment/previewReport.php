<?php 
require "../class/Administration.php";
$admin = new Administration();
$tracks = $admin->getEnrollmentReportData();

$school_year = "2021-2022";
$signatory = "Alvin John Cutay";
$position = "Student";
$date = strftime('%Y-%m-%d', strtotime(date("F j, Y")));

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
    <h3 class="fw-bold">Report Preview</h3>
</header>
<!-- HEADER END -->
<!-- <form id='enroll-report-form' method='POST' action='test.php'> -->
<form id='enroll-report-form' method='POST' action='./enrollment/enrollmentReport.php'>
    <!-- GENERAL REPORT INFO  -->
    <div class="container card p-4 bg-white mb-3" style="width: 80% !important;">
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
    <div class="container card p-4 bg-white  mb-3" style="width: 80% !important;">
        <?php 

        $accepted_grand_total = [];
        $rejected_grand_total = [];
     
        foreach($tracks as $track_id => $track_value) {
            $track_names[] = $track_id;
            // echo "<div class='row'><h6>Track ID: $track_id</h6></div>";
            // echo "<div class='row align-items-center text-center'>"
            //     ."<div class='col-md-2'>Strand</div>"
            //     ."<div class='col-md-5'>Accepted</div>"
            //     ."<div class='col-md-5'>Rejected</div>"
            // ."</div>";

            echo "<table class='table table-striped table-sm mx-auto' style='width: 90%;'>";
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
                $is_last_element = $tv_id == array_key_last($track_value);
                
                echo "<td>$tv_id
                   
                </td>";
                $rejected = $rejected_grand_total[] = $rejected_count_list[] = $tv_value[0];
                $accepted = $accepted_grand_total[] = $accepted_count_list[] = $tv_value[1];
                
                echo "<td><input name='tracks[$track_id][$tv_id][1]' class='form-control mb-0 me-3' value ='$accepted' ></td>";
                echo "<td><input name='tracks[$track_id][$tv_id][0]' class='form-control mb-0 me-3' value ='$rejected' ></td>";
                // Accepted sub total column
                if ($is_last_element) { 			// if the element is the last key, calculate total
                    echo "</tr>";
                    echo "<tr>"
                        ."<td>Sub total</td>"
                        ."<td><input class='form-control mb-0' value ='".array_sum($accepted_count_list)."' ></td>"
                        ."<td><input class='form-control mb-0' value ='".array_sum($rejected_count_list)."' ></td>"
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
        <div class="container">
            <div class="row align-items-center" >
           
                <div class="col-2">
                    Total
                </div>
                <div class="col-5">
                    <input value="<?php echo array_sum($accepted_grand_total); ?>" type="text" class="form-control mb-0">
                </div>
                <div class="col-5">
                    <input value="<?php echo array_sum($rejected_grand_total); ?>" type="text" class="form-control mb-0">
                </div>
        
            </div>
        </div>
    </div>
    <!-- SIGNATORY INFO -->
    <div class="container card p-4 bg-white" style="width: 80% !important;">
        <div class='row'>
            <div class="col-md-2">Signatory</div>
            <div class="col-md-5 d-flex flex-column"><label>Name</label><input type='text' class='form-control' name='signatory' value='<?php echo $signatory; ?>'></div>
            <div class="col-md-5 d-flex flex-column"><label>Position</label><input typt='text' class='form-control' name='position' value='<?php echo $position; ?>'></div>
        </div>
        <div class="row justify-content-end">
            <!-- <div class="col-auto"><a href='./enrollment/enrollmentReport.php' form='enroll-report-form' type='submit' class='form-control btn btn-success mb-0'>Generate document</a></div> -->
            <div class="col-auto"><input form='enroll-report-form' type='submit' class='form-control btn btn-success mb-0' value='Generate document'></div>
        </div>
    </div>
</form>