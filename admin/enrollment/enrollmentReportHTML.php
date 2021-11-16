<?php 
include("../inc/head.html"); 

$tracks = $_POST['tracks'];
$school_year = $_POST['school-year'];
$title_desc = $_POST['report-title'];
$signatory_desc = $_POST['signatory'];
$position_desc = $_POST['position'];
$date_desc = date('F j, Y', strtotime($_POST['date']));
$file_name = str_replace(' - ', '_', $school_year). '_enrollment_report';

?>
    <script src="../assets/js/html2pdf.bundle.min.js"></script>
    <link rel="stylesheet" href="../css/report.css">
</head>
<body>
<!-- HEADER -->
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="enrollment.php?page=enrollees">Enrollment List</a></li>
            <li class="breadcrumb-item active" aria-current="page">Preview Report</li>
        </ol>
    </nav>
    <h2 class="fw-bold">Enrollment Report preview</h2>
    <h6><?php echo $school_year; ?></h6>
    <hr class="my-2">
    <div class="row mb-4">
        <div class="col-auto">
            <button class="btn btn-sm btn-primary" onclick="generatePDF(`<?php echo $file_name; ?>`)" id="export">Download</button>
        </div>
    </div>
</header>
<!-- HEADER END -->
<div class="doc bg-white ms-2 p-0 shadow overflow-auto">
    <ul class="template p-0">
        <li class="p-0 mb-0 mx-auto">
            <!-- DOCUMENT HEADER -->
            <div class="row p-0 mx-1">
                <div class="col-3 p-0">
                    <img src="../assets/deped_logo.png" alt="">
                </div>
                <div class="col-6 p-0 text-center">
                    <p>
                        Republic of the Philippines<br>
                        Department of Education<br>
                        Cordillera Administrative Region<br>
                        Baguio City Schools Division<br>
                        PINES CITY NATIONAL HIGH SCHOOL SENIOR HIGH<br>
                        Lucban Campus
                   </p>
                </div>
                <div class="col-3 p-0" style="text-align: right;">
                    <img src="../assets/school_logo.jpg" alt="">
                </div>
            </div>
            <div class="report-title">
                <h5 class="title"><?php echo $title_desc; ?></h5>
                <p class="sub-title">SY <?php echo $school_year ?></p>
            </div>
            <!-- DOCUMENT HEADER END -->
            <div class="content mb-5">
                <h6>Statistics</h6>
                <div class="table-con">
                    <table class="table table-sm">
                        <thead>
                        <tr class="table-dark text-center">
                            <td>Track</td>
                            <td>Strand</td>
                            <td colspan="2">Accepted</td>
                            <td colspan="2">Rejected</td>
                        </tr>
                        </thead>
                        <tbody>
                            <?php 

                            foreach($tracks as $track_id => $track_value) {
                                echo "<tr>";
                                echo "<td class='text-center' valign='middle' rowspan='". count($track_value) ."'>$track_id</td>";

                                $accepted_count_list = [];
                                $rejected_count_list = [];
                                foreach($track_value as $strand_id => $strand_count) {
                        
		                            $is_last_element = $strand_id == array_key_last($track_value);
                                    
                                    $rejected = $rejected_count_list[] = $strand_count[0];
                                    $accepted = $accepted_count_list[] = $strand_count[1] ?: 0;

                                    # Strand
                                    echo "<td>$strand_id</td>";
                                    # Accepted Count
                                    echo "<td class='text-end'>". $accepted ."</td>";
                                    if ($is_last_element) {
                                        $accepted_grand_total[] = $total_accepted = array_sum($accepted_count_list);
                                        echo "<td class='text-end'>$total_accepted</td>";
                                    } else {
                                        echo "<td></td>";
                                    }

                                    # Rejected count
                                    echo "<td class='text-end'>$rejected</td>";
                                    if ($is_last_element) {
                                        $rejected_grand_total[] = $total_rejected = array_sum($rejected_count_list);
                                        echo "<td class='text-end'>$total_rejected</td>";
                
                                    } else {
                                        echo "<td></td>";
                                    }
                                    echo "</tr>";
                                
                        
                                }
                            }

                            echo "<tr class='table-secondary'>"
                                    ."<td class='text-end' colspan='3'>Total</td>"
                                    ."<td class='text-end'>". array_sum($accepted_grand_total) ."</td>"
                                    ."<td></td>"
                                    ."<td class='text-end'>". array_sum($rejected_grand_total) ."</td>"
                                ."</tr>";
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="footer row">
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