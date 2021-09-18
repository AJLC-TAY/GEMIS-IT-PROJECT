<?php include("../inc/head.html"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <style>
        .doc {
            width: 8.5in !important;
            height: 11in !important;
            border: 1px solid black;
            padding: 0.4in;
        }
        .template {
            /*width: 7.3in !important;*/
            /*height: 10in !important;*/
            width: 7.45in !important;
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
            width: 100px;"
        }
        /*Footer*/
        .signatory-con {
            width: 50%; display: table-column;
        }
    </style>
</head>
<body>

<!-- SPINNER -->
<div id="main-spinner-con" class="spinner-con d-none">
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
                                    <li class="breadcrumb-item"><a href="enrollment.php?page=enrollees">Enrollment List</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Preview Report</li>
                                </ol>
                            </nav>
                            <h2 class="fw-bold">Enrollment Report preview</h2>
                            <hr class="my-2">
                            <div class="row justify-content-end mb-4">
                                <div class="col-auto">
                                    <button class="btn btn-sm btn-primary" onclick="generatePDF()" id="export">Export PDF</button>
                                </div>
                            </div>
                        </header>
                        <!-- HEADER END -->
                        <div class="doc bg-white ">
                            <ul class="template p-0">
                                <li class="p-0 mb-0 mx-auto">
                                    <div class="row p-0 mx-1">
                                        <div class="col-3 p-0">
                                            <img src="../assets/deped_logo.png" alt="">
                                        </div>
                                        <div class="col-6 p-0 text-center">
                                            Republic of the Philippines<br>
                                            Department of Education<br>
                                            Cordillera Administrative Region<br>
                                            Baguio City Schools Division<br>
                                            PINES CITY NATIONAL HIGH SCHOOL SENIOR HIGH<br>
                                            Lucban Campus
                                        </div>
                                        <div class="col-3 p-0" style="text-align: right;">
                                            <img src="../assets/school_logo.jpg" alt="">
                                        </div>
                                    </div>
                                    <div class="report-title">
                                        <h5 class="title">Enrollment Report</h5>
                                        <p class="sub-title">SY 2021-2022</p>
                                    </div>
                                    <div class="content mb-5">
                                        <h6>Statistics</h6>
                                        <div class="table-con">
                                            <table class="table">
                                                <thead>
                                                <tr class="table-dark text-center">
                                                    <td>Track</td>
                                                    <td>Strand</td>
                                                    <td colspan="2">Accepted</td>
                                                    <td colspan="2">Rejected</td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="center" rowspan="2">Academic</td>
                                                    <td>ABM</td>
                                                    <td class="right">10</td>
                                                    <td></td>
                                                    <td class="right">10</td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>HumSS</td>
                                                    <td class="right">10</td>
                                                    <td class="right">20</td>
                                                    <td class="right">10</td>
                                                    <td class="right">20</td>
                                                </tr>
                                                <tr>
                                                    <td class="center" rowspan="2">TVL</td>
                                                    <td>Electronics</td>
                                                    <td class="right">10</td>
                                                    <td></td>
                                                    <td class="right">10</td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Bread & Pastry</td>
                                                    <td class="right">10</td>
                                                    <td class="right">20</td>
                                                    <td class="right">10</td>
                                                    <td class="right">20</td>
                                                </tr>
                                                <tr class="table-secondary">
                                                    <td class="right" colspan="3">Total</td>
                                                    <td class="right">40</td>
                                                    <td></td>
                                                    <td class="right">40</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="footer row">
                                        <div class="signatory-con col-6">
                                            <p class="closing-remark mb-5">Prepared on <span id="date" class="fw-bold">September 17, 2021</span> by: </p>
                                            <p class="signatory" style="text-align: center">
                                                <span id="signatory">Alvin John Cutay</span><br>
                                                <span id="position">Student</span>
                                            </p>
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
<script>
    function generatePDF() {
        const template = document.querySelector(".template");
        var opt = {
            margin: 0.5,
            filename: 'myfile.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 4, dpi: 300 },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        };
        html2pdf().from(template).set(opt).save();

    }
</script>
</body>
</html>