<?php include_once("../inc/head.html"); ?>
    <title>Grade Report | GEMIS</title>
    <link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
    <script src="../assets/js/html2pdf.bundle.min.js"></script>
    <style>
        .doc {
            width: 8.5in !important;
            height: auto;
        }
        .template {
            width: 7.5in !important; 
            font-family: Arial !important;
            font-size: 13px !important;
        }
        .template li {
            width: 100%;
            height: 9.98in !important;
            margin: 0.5in !important;
        }


        /*Header*/
        .report-title {
            text-align: center; margin-top: 40px; margin-bottom: 40px;
        }

        .title {
            margin-bottom: 0;
        }

        .sub-title {
            margin-top: 0;
        }
        /*Content*/
        img {
            height: 100px;
            width: 100px;
        }

        td {
            border: 0.5px solid #C5C5C5;
        }

        /*Footer*/
        .signatory-con {
            width: 50%; display: table-column;
        }
    </style>
</head>

<body>
    <!-- SPINNER -->
    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container"></section>
        <?php include_once('../inc/admin/sidebar.php'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="index.php">Student</a></li>
                                        <li class="breadcrumb-item active">Grade Report</li>
                                    </ol>
                                </nav>
                                <div class="d-flex flex-column mb-3">
                                    <h6 class="fw-bold">Grade Report</h6>
                                    <h3>Student Name</h3>
                                    <hr class='m-1'>
                                    <p class='text-secondary'>School Year</p>
                                </div>
                            </header>
                            <?php 
                                include "../class/Administration.php";
                                $admin = new Administration();
                                $stud_id = '1111';  // test
                                $filename = $stud_id.'_grade_report'; // 1111_grade_report
                                $grades = $admin->listGrade();
                                // echo json_encode($grades);

                                function prepareGradeRecordsHTML($grade) {
                                    $row = '';
                                    // echo json_encode($grade);
                                        $row .= "<tr>
                                        <td>{$grade[0]['sub_name']}</td>
                                        <td align='center'>{$grade[0]['grade_1']}</td>
                                        <td align='center'>{$grade[0]['grade_2']}</td>
                                        <td align='center'>{$grade[0]['grade_f']}</td>
                                        </tr>";
                                    return $row;
                                }

                                function renderSemesterGradeTable($semester_desc, $grades) {
                                    echo "
                                        <h6><b>$semester_desc</b></h6>
                                        <table class='table w-100 table-sm'>
                                            <col style='width: 65%;'>
                                            <col style='width: 10%;'>
                                            <col style='width: 10%;'>
                                            <col style='width: 15%;'>
                                            
                                            <thead class='text-center fw-bold'>
                                                <tr>
                                                    <td rowspan='2' valign='middle' align='center'>Subjects</td>
                                                    <td colspan='2' align='center'>Quarter</td>
                                                    <td rowspan='2' valign='middle' align='center'>Semester Final Grade</td>
                                                </tr>
                                                <tr>
                                                    <td align='center'>1</td>
                                                    <td align='center'>2</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class='bg-light'>
                                                    <td colspan='4'>Core Subjects</td>
                                                </tr>".
                                                    prepareGradeRecordsHTML($grades['core'])
                                                ."<tr class='bg-light'>
                                                    <td colspan='4'>Applied Subjects</td>
                                                </tr>".
                                                    prepareGradeRecordsHTML($grades['applied'])
                                                ."<tr class='bg-light'>
                                                    <td colspan='4'>Specialized Subjects</td>
                                                </tr>".
                                                    prepareGradeRecordsHTML($grades['specialized'])
                                                ."<tr class='bg-light fw-bold'>
                                                    <td colspan='3'>General Average for the Semester:</td>
                                                    <td class='bg-white'></td>
                                                </tr>
                                            </tbody>
                                        </table>";

                                }
                                

                                $observed_values_desc = [
                                    "Makadiyos" => [
                                        "Expresses one’s spiritual beliefs while respecting the spirtiual beliefs of others.",
                                        "Shows  adherence to ethical principles by uphoalding truth in all undertakings." 
                                    ],
                                    "Makatao"  =>  [
                                        "In sensitive to individual, social, and cultural differences." ,
                                        "Demonstrates contributions towards solidarity"
                                    ],
                                    "Makakalikasan" => [
                                        "Cares for environment and utilizes resources wisely, judiciously and economically." ,
                                    ],
                                    "Makabansa"  => [
                                        "Demonstrates pride in being a Filipino; exercises the rights and responsibilities of a Filipino citizen.",
                                        "Demonstrate appropriate behavior in carrying out activities in school, community and country." 
                                    ]
                                ];
                                
                                $observed_values = $admin ->listValuesReport();
                                // $observed_values = [
                                //     "1" => [
                                //         'Makadiyos'     => ['AO', 'SO'],
                                //         'Makatao'       => ['NO', 'RO'],
                                //         'Makakalikasan' => ['NO'],
                                //         'Makatao'       => ['NO', 'RO'],
                                //     ],
                                //     "2" => [
                                //         'Makadiyos'     => ['AO', 'SO'],
                                //         'Makatao'       => ['NO', 'RO'],
                                //         'Makakalikasan' => ['NO'],
                                //         'Makatao'       => ['NO', 'RO'],
                                //     ],
                                //     "3" => [
                                //         'Makadiyos'     => ['AO', 'SO'],
                                //         'Makatao'       => ['NO', 'RO'],
                                //         'Makakalikasan' => ['NO'],
                                //         'Makatao'       => ['NO', 'RO'],
                                //     ],
                                //     "4" => [
                                //         'Makadiyos'     => ['AO', 'SO'],
                                //         'Makatao'       => ['NO', 'RO'],
                                //         'Makakalikasan' => ['NO'],
                                //         'Makatao'       => ['NO', 'RO'],
                                //     ]
                                // ];
                            ?>


                            <div class="d-flex-inline">
                                <button onclick='generatePDF(`<?php echo $filename; ?>`)' class="btn btn-sm btn-primary">Download</button>
                            </div>
                            <div class="doc bg-white ms-2 mt-3 p-0 shadow">
                                <ul class="template p-0 w-100">
                                    <li class="p-0 mb-0 mx-auto">
                                        <!-- <div class="row p-0 mx-1">
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
                                        </div> -->
                                        <h5 class="text-center"><b>Report on Learning Progress and Achievement</b></h5>
                                        <?php 
                                        
                                            renderSemesterGradeTable('1st Semester', $grades['1']);
                                            renderSemesterGradeTable('2nd Semester', $grades['2']);
                                        ?>
                                        <br>
                                    </li>
                                    <hr class='m-0'>
                                    <!-- <div class="html2pdf__page-break"><hr class='m-0'></div> -->
                                    <li class="p-0 mb-0 mx-auto">

                                        <h6 class="text-center"><b>Report on Learner's Observed Values</b></h6>
<!-- 
                                        <div class="d-flex justify-content-between">
                                            <p><b>AO</b> - Always Observed</p>
                                            <p><b>SO</b> - Sometimes Observed</p>
                                            <p><b>RO</b> - Rarely Observed</p>
                                            <p><b>NO</b> - Not Observed</p>
                                        </div> -->

                                        <table class="table-bordered table w-100">
                                            <col style='width: 20%;'>
                                            <col style='width: 40%;'>
                                            <col style='width: 10%;'>
                                            <col style='width: 10%;'>
                                            <col style='width: 10%;'>
                                            <col style='width: 10%;'>
                                            
                                            <thead class='text-center fw-bold'>
                                                <tr>
                                                    <td>Core Values</td>
                                                    <td>Behavior Statements</td>
                                                    <td>1</td>
                                                    <td>2</td>
                                                    <td>3</td>
                                                    <td>4</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    // echo json_encode($observed_values);
                                                    // echo "<tr>";
                                                     foreach($observed_values as $id => $values) {//id = MakaDiyos , values = [ 'sfdsdfsdfdsf => 1 => 'AO',2 => 'AO,],
                                                        echo "<td rowspan='". count($values) ."'><b>$id</b></td>";
                                                        foreach($values as $bh_staments => $bh_qtr){ // $bh_staments = sfdsdfsdfdsf
                                                            foreach($bh_qtr as $bh_staments => $marking){
                                                                echo "<td>$bh_staments</td>";
                                                                
                                                                    $first = $marking[0];
                                                                    $second = $marking[1];
                                                                    $third = $marking[2];
                                                                    $fourth = $marking[3];
                                                                    echo "<td>$first</td>";
                                                                    echo "<td>$second</td>";
                                                                    echo "<td>$third</td>";
                                                                    echo "<td>$fourth</td>";
                                                                    echo "</tr>";                                                           
                                                                }
                                                                
                                                            }
                                                       
                                                       echo "</tr>";
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                        <div class="container">
                                            <h6><b>Observed Values</b></h6>
                                            <div class="row g-5">
                                                <div class="col-auto">LEGEND:</div>
                                                <div class="col-auto"><b>Marking</b><p>AO<br>SO<br>RO<br>NO</p></div>
                                                <div class="col-auto">
                                                    <b>Non-numerical Rating</b>
                                                    <p>Always Observed<br>Sometimes Observed<br>Rarely Observed<br>Not Observed</p>
                                                </div>
                                            </div>
                                            <h6><b>Learners Progress and Achievement</b></h6>
                                            <div class="row g-5">
                                                <div class="col-auto">
                                                    <b>Descriptors</b>
                                                    <p>Outstanding<br>Very Satisfactory<br>Satisfactory<br>Fairly Satisfactory<br>Did Not Meet Expectations</p>
                                                </div>
                                                <div class="col-auto">
                                                    <b>Grade Scale</b>
                                                    <p>90 - 100<br>85 - 89<br>80 - 84<br>75 - 79<br>Below 75</p>
                                                </div>
                                                <div class="col-auto">
                                                    <b>Remarks</b>
                                                    <p>Passed<br>Passed<br>Passed<br>Passed<br>Failed</p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                                              
                        </div>
                    </div>
                </div>
            </section>
            <!-- MAIN CONTENT END-->
            <!-- FOOTER -->
            <?php include_once("../inc/footer.html"); ?>
            <!-- FOOTER END -->
        </section>
    </section>
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->

    <!-- BOOTSTRAP TABLE JS -->
    <script src="../assets/js/bootstrap-table.min.js"></script>
    <script src="../assets/js/bootstrap-table-en-US.min.js"></script>
    <!--CUSTOM JS-->
    <script src="../js/common-custom.js"></script>
    <script >

        function generatePDF(filename) {
            // hide horizontal line 
            // $(".doc hr").addClass('d-none');
            const template = document.querySelector(".template");
            var opt = {
                // margin: 0.5,
                margin: 0,
                filename: filename + '.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 4, dpi: 300 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().from(template).set(opt).save();
            // $(".doc hr").removeClass('d-none');
        }
        // window.generatePDF = generatePDF;
        $(function () {
            preload("#faculty");
            hideSpinner();
        });
    </script>

</body>

</html>