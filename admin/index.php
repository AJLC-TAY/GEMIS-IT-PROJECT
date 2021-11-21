<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
require_once("../class/Administration.php");
$admin = new Administration();
$user_id = $_SESSION['id'];
$current_quarter = $_SESSION['current_quarter'];

$admin_user = $admin->getProfile('AD');
[$admins, $faculties, $students, $signatories] = $admin->getUserCounts();

$edit = "disabled";
$disable_when_edit = "";
$none_when_edit = "";
$display_when_edit = "d-none";

if (isset($_GET['state']) && $_GET['state'] == 'edit') {
    $edit = '';
    $display_when_edit = "";
    $disable_when_edit = "disabled";
    $none_when_edit = "d-none";
}
?>

<title>Home | GEMIS</title>
</head>
<!DOCTYPE html>

<body>
    <!-- SPINNER -->
    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container-fluid">
        <?php include_once('../inc/adminSidebar.php'); ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper ps-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
                            <header class="mb-4">
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item active">Home</li>
                                    </ol>
                                </nav>
                                <div class="card">
                                    <div class="form-row row">
                                        <div class="form-group col-md-6">
                                            <h2 class="fw-bold mt-3 ms-3">Welcome!</h2>
                                            <ul class="ms-4 list-style p-0">
                                                <li>
                                                    <h4><?php echo $admin_user->name; ?></h4>
                                                </li>
                                                <?php
                                                if (!empty($_SESSION['sy_id'])) {
                                                    echo "<li>School Year {$_SESSION['school_year']}</li>";
                                                    echo " <li>
                                                    <div class='row align-content-center mt-3'>
                                                        <div class='col-4 mt-1'><label for='current-quarter' class='mb-0 mx-auto'>Current Quarter</label></div>
                                                        <div class='col-8' style='width:40%;'><select name='quarter' class='form-select-sm form-select mb-0' id='current-quarter' value='$current_quarter'>";
                                                    foreach ([1, 2, 3, 4] as $qtr) {
                                                        echo "<option value='$qtr' " . ($qtr == $current_quarter ? "selected" : "") . " >" . ($qtr == 1 ? "First" : ($qtr == 2 ? "Second" : ($qtr == 3 ? "Third" : "Fourth"))) . " Quarter </option>";
                                                    }
                                                    echo "</select></div>
                                                        </li>";
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <img src="../assets/admin.png" alt="Display image" style="width: 50%; display: block; margin-left: auto; float:right;">
                                        </div>
                                    </div>
                                </div>
                            </header>

                            <div class="container-fluid mb-3 mt-3">
                                <!-- PEOPLE MANAGEMENT -->
                                <section class="row">
                                    <h5 class="fw-bold">PEOPLE MANAGEMENT</h5>
                                    <div class="col-lg-3">
                                        <div class="card-box bg-default">
                                            <div class="inner">
                                                <h3> <?php echo $admins; ?> </h3>
                                                <p>Admin</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-user-circle" aria-hidden="true"></i>
                                            </div>
                                            <a href="admin.php?id=<?php echo $user_id; ?>" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="card-box bg-forest">
                                            <div class="inner">
                                                <h3> <?php echo $faculties; ?> </h3>
                                                <p>Faculty</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-users" aria-hidden="true"></i>
                                            </div>
                                            <a href="faculty.php" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="card-box bg-green">
                                            <div class="inner">
                                                <h3> <?php echo $students; ?> </h3>
                                                <p> Student </p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                            </div>
                                            <a href="student.php" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="card-box bg-tea">
                                            <div class="inner">
                                                <h3> <?php echo $signatories; ?> </h3>
                                                <p> Signatory </p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </div>
                                            <a href="signatory.php" class="card-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <!-- PEOPLE MANAGEMENT END -->
                            <!-- SCHOOL MANAGEMENT -->
                            <div class="container-fluid">
                                <div class="row">
                                    <h5 class="fw-bold">SCHOOL MANAGEMENT</h5>
                                    <div class="col-lg-6">
                                        <div class="card bg-white rounded shadow-sm mt-2">
                                            <!-- CURRICULUM -->
                                            <section class="mb-2">
                                                <h6 class='mb-0 fw-bold ms-3 mt-2'>CURRICULUM</h6>
                                                <hr class="mt-1 mb-2">
                                                <div class="d-flex flex-wrap">
                                                    <div class='row'>
                                                        <div class="inner">
                                                            <div class="card-btn bg-pastel mx-4 mb-3">
                                                                <div class="inner row">
                                                                    <div class="ms-4 col-sm-8">
                                                                        <h5 class="fw-bold mt-1"><i class="fa fa-calendar-o me-3" aria-hidden="true"></i>SCHOOL YEAR</h5>
                                                                    </div>
                                                                    <div class="mt-1 col-sm-3">
                                                                        <a href="schoolYear.php" class="card-link">View <i class="fa fa-arrow-circle-right"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="inner">
                                                            <div class="card-btn bg-pastel mx-4 mb-3">
                                                                <div class="inner row">
                                                                    <div class="ms-4 col-sm-8">
                                                                        <h5 class="fw-bold mt-1"><i class="fa fa-book me-3" aria-hidden="true"></i>CURRICULUM</h5>
                                                                    </div>
                                                                    <div class="mt-1 col-sm-3">
                                                                        <a href="curriculum.php" class="card-link">View <i class="fa fa-arrow-circle-right"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="inner">
                                                            <div class="card-btn bg-pastel mx-4 mb-3">
                                                                <div class="inner row">
                                                                    <div class="ms-4 col-sm-8">
                                                                        <h5 class="fw-bold mt-1"><i class="fa fa-list-alt  me-3" aria-hidden="true"></i>PROGRAM</h5>
                                                                    </div>
                                                                    <div class="mt-1 col-sm-3">
                                                                        <a href="program.php" class="card-link">View <i class="fa fa-arrow-circle-right"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="inner">
                                                            <div class="card-btn bg-pastel mx-4 mb-3">
                                                                <div class="inner row">
                                                                    <div class="ms-4 col-sm-8">
                                                                        <h5 class="fw-bold mt-1"><i class="fa fa-file-text me-3" aria-hidden="true"></i>SUBJECT</h5>
                                                                    </div>
                                                                    <div class="mt-1 col-sm-3">
                                                                        <a href="subject.php" class="card-link">View <i class="fa fa-arrow-circle-right"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="card bg-pastel shadow-sm mt-2">
                                            <section class="mb-3">
                                                <h6 class='mb-0 fw-bold ms-3 mt-2'>ENROLLMENT</h6>
                                                <hr class="mt-1 mb-3">
                                                <div class="d-flex flex-wrap">
                                                    <div class='row'>
                                                        <div class="inner mb-4">
                                                            <div class="card-btn bg-white mx-4  mt-3" style="height: 81%;">
                                                                <div class="inner row">
                                                                    <div class="ms-4 col-sm-8">
                                                                        <h5 class="fw-bold mt-1"><i class="fa fa-tasks me-3" aria-hidden="true"></i>ENROLLMENT</h5>
                                                                    </div>
                                                                    <div class="mt-1 col-sm-3">
                                                                        <a href="enrollment.php" class="card-link">View <i class="fa fa-arrow-circle-right"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="inner mb-4">
                                                            <div class="card-btn bg-white mx-4 mt-1 mb-1" style="height: 81%;">
                                                                <div class="inner row">
                                                                    <div class="ms-4 col-sm-8">
                                                                        <h5 class="fw-bold mt-1"><i class="fa fa-cog me-3" aria-hidden="true"></i>SET UP ENROLLMENT</h5>
                                                                    </div>
                                                                    <div class="mt-1 col-sm-3">
                                                                        <a href="enrollment.php?page=setup" class="card-link">View <i class="fa fa-arrow-circle-right"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="inner mb-4">
                                                            <div class="card-btn bg-white mx-4 mb-2" style="height: 81%;">
                                                                <div class="inner row">
                                                                    <div class="ms-4 col-sm-8">
                                                                        <h5 class="fw-bold mt-1"><i class="fa fa-list-ul me-3" aria-hidden="true"></i>SECTION</h5>
                                                                    </div>
                                                                    <div class="mt-1 col-sm-3">
                                                                        <a href="section.php" class="card-link redirect-card">View <i class="fa fa-arrow-circle-right"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--footer start-->
            <?php include_once("../inc/footer.html"); ?>
            <!--footer end-->
        </section>
        <!--main content end-->
    </section>
    <div class="modal fade" id="change-quarter-confirmation-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Confirmation</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Transition to <span id="quarter"></span> quarter?</p>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-sm btn-dark close-btn" data-bs-dismiss="modal">Cancel</button>
                    <button id="quarter-submit" data-bs-dismiss="modal" class="btn btn-sm btn-success">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->

</body>
<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script type="text/javascript" src="../js/common-custom.js"></script>
<script type="text/javascript">
    function changeQuarter(quarter) {
        let formData = [{
                name: "action",
                value: "editSY"
            },
            {
                name: "quarter",
                value: quarter
            },
        ];
        $.post("action.php", formData, function(data) {
            $("#change-quarter-confirmation-modal").modal("hide");
            location.reload();
        });
    }

    $(function() {
        preload("#home");
        $(document).on("change", "#current-quarter", function() {
            let value = $(this).find("option:selected").val();
            let modal = $("#change-quarter-confirmation-modal");
            modal.find("#quarter").html((value == 1) ? "First" : (value == 2 ? "Second" : (value == 3 ? "Third" : "Fourth")));
            modal.find("#quarter-submit").attr("onclick", `changeQuarter(${value})`);
            modal.modal("show");
        });
        hideSpinner();
    });
</script>

</html>