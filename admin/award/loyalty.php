<?php
include "../class/Administration.php";
$admin = new Administration();
$data = $admin->getConductAward();
$school_year = $_SESSION['school_year'];
$filename = "Conduct_Awards_$school_year";
$date_desc = date("F j, Y");
$signatory_desc = $_POST['signatory'] ?? $_SESSION['User'];
$position_desc = $_POST['position'] ?? ($_SESSION['user_type'] == 'FA' ? "Award Coordinator" : "Administrator");
$header = "<div class='row p-0 mx-1'>
                <div class='col-3 p-0'>
                    <img src='../assets/deped_logo.png' alt='DEPED Logo' title='DEPED Logo'>
                </div>
                <div class='col-6 p-0 text-center'>
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
                <div class='col-3 p-0' style='text-align: right;'>
                    <img src='../assets/school_logo.jpg' alt='PCNSH Logo' title='PCNSH Logo'>
                </div>
            </div>";
?>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="awardReport.php">Awards</a></li>
            <li class="breadcrumb-item active">Conduct Awards</li>
        </ol>
    </nav>
    <div class="d-flex flex-column mb-3">
        <h6 class="fw-bold">Preview</h6>
        <h3>Conduct AWards</h3>
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
            <?php 
            foreach($data as $grd_level => $val) {
                echo $header; 
                echo "<h6 class='text-center m-0'><b>Conduct Awards for Grade $grd_level</b></h6>
                    <h6 class='text-center mt-0 mb-3'><small>SY $school_year</small></h6>
                    <p>Awardees must have consistently and dutifully carried out the core values of the Department as 
                    indicated in the report card. They must have obtained a rating of at least 75% <b><i>“Always Observed”</i></b> 
                    <b>(AO)</b> at the end of the school year (with at least 21 out of 28 <b>AO</b> rating in the report card). 
                    They also must have not been sanctioned with offenses punishable by suspension or higher sanction 
                    within the school year according to the Department’s service manual and child protection policies.</p>";
                if (empty($val)) {
                    echo "<h4 class='my-5 text-center'>No students </h4>";
                }
                foreach($val as $section_code => $section_data) {
                    echo "<p>Section: {$section_data['section_name']}</p>";
                    echo "<table class='table-bordered table w-100 table-sm text-center my-3'>"
                        ."<col style='width: 20%;'>"
                        ."<col style='width: 70%;'>"
                        ."<col style='width: 10%;'>"
                        ."<thead class='text-center'>
                            <tr>
                                <th>LRN</th>
                                <th>Student Name</th>
                                <th>Sex</th>
                            </tr>
                        </thead>";
                    echo "<tbody>";
                    foreach ($section_data['students'] as $record) {
                        echo "<tr>
                            <td>{$record['lrn']}</td>
                            <td class='text-start ps-3'>{$record['name']}</td>
                            <td>{$record['sex']}</td>
                        </tr>";
                    }
                    echo "</tbody></table>";
                    
                }
                echo "<div class='row'>
                        <div class='signatory-con col-6'>
                            <p class='closing-remark mb-5'>Prepared on <span id='date' class='fw-bold'>$date_desc</span> by: </p>
                            <p class='signatory' style='text-align: center'>
                                <span id='signatory'>$signatory_desc</span><br>
                                <span id='position'>$position_desc</span>
                            </p>
                        </div>
                    </div>";
                // if (count($data) != 1) {
                //     echo "<div class='html2pdf__page-break'></div>";
                // } 
            }
            ?>
        </li>
    </ul>
</div>