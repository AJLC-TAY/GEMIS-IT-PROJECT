<?php
require_once("../inc/sessionHandling.php");
include_once("../inc/head.html");

include("../class/Administration.php");
$admin = new Administration();
$result = $admin->query("SELECT CASE WHEN award_code = 'ae1_highestHonors' THEN 'Highest' 
                        WHEN award_code = 'ae1_highHonors' THEN 'High'
                        WHEN award_code = 'ae1_withHonors' THEN 'With' END AS info, 
                        min_gwa AS min, max_gwa AS max
                        FROM academicexcellence;");
$param = [];
while ($row = mysqli_fetch_assoc($result)) {
    $param[$row['info']] = ['min' => $row['min'], 'max' => $row['max']];
}
?>
<title>Award | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
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
        <!--MAIN CONTENT -->
        <section id="main-content">
            <section class="wrapper ps-4">
                <div class="row">
                    <div class="row ps-3">
                        <header>
                            <!-- BREADCRUMB -->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item active">Award</a></li>
                                </ol>
                            </nav>
                            <div class="d-flex justify-content-between mb-3">
                                <h3 class="fw-bold">Award Parameters</h3>
                            </div>
                        </header>
                        <div class="container w-75">
                            <div class="card row mb-4">
                                <h4>Academic Excellence</h4>
                                <hr>
                                <form id="acad-parameter-form" action="action.php" method="post">
                                    <input type="hidden" name="editAcadParameters">
                                    <div class="container">
                                        <div class="form-row row align-content-center mb-3 text-center fw-bold">
                                            <label class="col-form-label col-4">Description</label>
                                            <div class="col-8 pt-2">Range (Min - Max)</div>
                                        </div>
                                        <?php
                                        foreach ($param as $info => $range) {
                                            echo "<div class='form-row row align-content-center'>
                                                    <label class='col-form-label col-md-4'>$info Honor</label>";
                                            foreach ($range as $val) {
                                                echo "<div class='col-4'>
                                                                <input value='$val' name='$info-honor[]' type='text' class='form-control form-control-sm number text-end' placeholder='Enter Value'>
                                                            </div>";
                                            }
                                            echo "</div>";
                                        }
                                        ?>
                                    </div>
                                    <!-- <input type="submit" form="acad-parameter-form" class="form-control form-control-sm btn-success" value="Save"> -->
                                </form>
                            </div>
                            <div class="card row mb-4">
                                <h4>Conduct Award</h4>
                                <hr>
                                <div class="container">
                                    <p>They must have obtained a rating of at least 75% “Always Observed” (AO) at the end of the school year (with at least 21 out of 28 AO rating in the report card). </p>

                                </div>
                            </div>
                            <div class="card row mb-4">
                                <h4>Specific Discipline</h4>
                                <div class="container">
                                    <div class='form-row row justify-content-around align-content-center mb-2'>
                                        <label class='col-form-label col-md-6'></label>
                                        <div class='col-md-4  text-center text-secondary'>Minimum Grade</div>
                                    </div>
                                    <div class="row">
                                        <?php 
                                        $spec_award = $admin->getSpecificDiscParamters();
                                        foreach($spec_award as $code => $param) {
                                            echo "<div class='col-md-6 row justify-content-around align-content-center'>
                                                    <label class='col-form-label col-md-6'>{$param['desc']}</label>
                                                    <div class='col-md-6'>
                                                        <input value='{$param['grd']}' name='$code' type='number' class='form-control form-control-sm number text-center' placeholder='Enter Value'>
                                                    </div>
                                                </div>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card row mb-4">
                                <h4>Other Awards </h4>
                                <hr>
                                <div class="container">
                                    <div class='form-row row align-content-center justify-content-end text-center mt-1'>
                                        <div class="col-6">
                                            <b>Awards for</b>
                                        </div>
                                        <div class="col-6">
                                            <p class='fw-bold'>Minimum Grade</p>
                                        </div>
                                    </div>
                                    <div class='form-row row align-content-center'>
                                        <label class='col-form-label col-md-6'>Research</label>
                                        <div class="col-md-6">
                                            <input value='90' name='award-for-research' type='text' class='form-control form-control-sm number text-end' placeholder='Enter Value'>
                                        </div>
                                    </div>
                                    <div class='form-row row align-content-center'>
                                        <label class='col-form-label col-md-6'>Outstanding Performance in Specific Disciplines</label>
                                        <div class="col-md-6">
                                            <input value='90' name='outstanding' type='text' class='form-control form-control-sm number text-end' placeholder='Enter Value'>
                                        </div>
                                    </div>
                                    <div class='form-row row align-content-center'>
                                        <label class='col-form-label col-md-6'>Work Immersion Senior High</label>
                                        <div class="col-md-6">
                                            <input value='90' name='immersion' type='text' class='form-control form-control-sm number text-end' placeholder='Enter Value'>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <?php
                        // if (isset($_GET['award_code'])){
                        //     include_once("award/awardView.php");
                        //     $jsFilePath = "../js/admin/award.js";
                        // } else {
                        //     include_once("award/awardCards.php");
                        //     $jsFilePath = "../js/admin/award.js";
                        // }
                        ?>
                    </div>
                </div>
            </section>
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
</body>
<!-- VALIDATION -->
<script>
    var forms = document.querySelectorAll('.needs-validation');

    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false);
    });
</script>

<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>
<script type="text/javascript" src="../js/common-custom.js"></script>
<!-- <script type="module" src="<?php // echo $jsFilePath; 
                                ?>"></script> -->
<script>
    $(function() {
        preload("#curriculum");
        hideSpinner();
    });
</script>

</html>