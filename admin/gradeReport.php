<?php include_once("../inc/head.html"); ?>
    <title>Section | GEMIS</title>
    <link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
    <script src="../assets/js/html2pdf.bundle.min.js"></script>
    <style>
        .doc {
            width: 8.5in !important;
            height: 11in !important;
            border: 1px solid black;
            padding: 0.4in; /** Margin */
        }
        .template {
            /*width: 7.3in !important;*/
            /*height: 10in !important;*/
            width: 7.45in !important; /** 8.5-1 = 7.5  */
            height: 9.5in !important;
            font-family: Arial !important;
            font-size: 13px !important;
        }
        .template li {
            width: 100%;
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
    <section id="container">
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
                                        <li class="breadcrumb-item active">Faculty</li>
                                    </ol>
                                </nav>
                                <div class="d-flex justify-content-between mb-3">
                                    <h3 class="fw-bold">Faculty Members</h3>
                                    <div>
                                        <button type="button" class="view-archive btn btn-secondary"><i class="bi bi-eye me-2"></i>View Archived Faculty</button>
                                        <a href="faculty.php?action=add" id="add-btn" class="btn btn-success" title='Add new faculty'><i class="bi bi-plus me-2"></i>Add Faculty</a>
                                        <!-- <a href="faculty.php?state=add" id="add-btn" class="btn btn-success add-prog" title='Add new faculty'>ADD FACULTY</a> -->
                                    </div>
                                </div>
                            </header>
                            <?php 
                                include "../class/Administration.php";
                                $admin = new Administration();
                                $grades = $admin->getGrade();
                             
                                // print_r($grades);
                            ?>

                            <button onclick='generatePDF()' class="btn btn-sm btn-primary">Export to PDF</button>
                            <div class="doc bg-white ms-2">
                                <ul class="template p-0">
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

                                        <table class="table">
                                            <thead class='text-center'>
                                                <tr>
                                                    <td>Subjects</td>
                                                    <td colspan='2'>Quarter</td>
                                                    <td>Semester Final Grade</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>1</td>
                                                    <td>2</td>
                                                    <td></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    foreach($grades as $grade) {
                                                        echo "<tr>
                                                            <td>{$grade['sub_name']}</td>
                                                            <td>{$grade['grade_1']}</td>
                                                            <td>{$grade['grade_2']}</td>
                                                            <td>{$grade['grade_f']}</td>
                                                        </tr>";
                                                    }
                                                ?>
                                            </tbody>
                                        </table>

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

        function generatePDF() {
            const template = document.querySelector(".template");
            var opt = {
                margin: 0.5,
                filename: 'grade.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 4, dpi: 300 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().from(template).set(opt).save();
        }
        // window.generatePDF = generatePDF;
        $(function () {
            preload("#faculty");
            hideSpinner();
        });
    </script>

</body>

</html>