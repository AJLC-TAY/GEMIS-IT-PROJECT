<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
include_once("../class/Faculty.php");
$faculty = new FacultyModule();
$data = $faculty->getAttendanceDays();
$current_month = $data['current'];
$months = $data['months'];
$class_data =  $faculty->getAdvisoryClass();
$class = '';
$class_name = '';
if (!empty($class_data)) {
    $class = $class_data['section_code'];
    $class_name = $class_data['section_name'];
}
$url = "getAction.php?data=class_attendance&class=$class&month=$current_month";
?>
<title>Attendance | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
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
    <section id="container">
        <?php include_once('../inc/facultySidebar.php'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper ps-4">
                <div class="row">
                    <div class="row mt ps-3">
                        <!-- HEADER -->
                        <header id="main-header">
                            <!-- BREADCRUMB -->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item active">Attendance</li>
                                </ol>
                            </nav>
                            <div class="d-flex">
                                <div class="col-auto">
                                    <p class="text-secondary m-0">
                                        <medium>Current:
                                            <?php
                                            $sem = '';
                                            switch ($_SESSION['current_semester']) {
                                                case '1':
                                                    $sem = "First ";
                                                    break;
                                                case '2':
                                                    $sem = "Second ";
                                                    break;
                                                case '3':
                                                    $sem = "Third ";
                                                    break;
                                                case '4':
                                                    $sem = "Fourth ";
                                                    break;
                                            }
                                            echo $sem . " Semester";
                                            ?>
                                        </medium>
                                    </p>
                                    <?php

                                    ?>
                                    <h3><b><?php echo $class_name; ?></b> Attendance</h3>
                                </div>
                            </div>
                        </header>
                        <!-- HEADER END -->
                        <!-- ATTENDANCE TABLE -->
                        <div class="container mt-2 ms-0">
                            <div class="card w-100 h-auto bg-light">
                                <form id="attendance-form" action="action.php" method="POST">
                                    <input type="hidden" name="action" value="changeAttendance">
                                    <div class="d-flex justify-content-between mb-3">
                                        <!-- SEARCH BAR -->
                                        <span class="flex-grow-1 me-3">
                                            <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
                                        </span>
                                        <div class="col-2 me-2">
                                            <select name="month" class="form-select form-select-sm">
                                                <?php
                                                foreach ($months as $month => $days) {
                                                    echo "<option value='$month' " . ($month == $current_month ? "selected" : "") . ">{$days[0]} ({$days[1]})</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <!-- <div class="col-1">
                                            <input type="text" name="academicDays" class="form-control form-control-sm number" placeholder="Days" title="Academic Days">
                                        </div> -->

                                        <div class="col-auto">
                                            <button class="btn btn-secondary btn-sm edit-btn ms-2"><i class="bi bi-pencil-square me-2"></i>Edit</button>
                                            <div class="edit-options" style="display: none;">
                                                <a href="attendance.php" role="button" class="btn btn-sm btn-outline-dark me-1 ms-2">Cancel</a>
                                                <!-- <input type='submit' class="btn btn-sm btn-success save-btn" value="Save">
                                                <input type='submit' class="btn btn-sm btn-success submit-btn" value="Submit"> -->
                                                <button class='save-btn btn btn-sm btn-success'>Save</button>
                                                <button class='submit-btn btn btn-sm btn-success'>Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                    <table id="table" data-url="<?php echo $url; ?>" class="table-striped table-sm">
                                        <thead class='thead-dark'>
                                            <tr>
                                                <th scope='col' data-width="100" data-align="center" data-field="stud_id" title="Student ID">SID</th>
                                                <th scope='col' data-width="200" data-halign="center" data-align="left" data-sortable="true" data-field="name">Name</th>
                                                <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="present_e">Present</th>
                                                <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="absent_e">Absent</th>
                                                <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="tardy_e">Tardy</th>
                                                <th scope='col' data-width="100" data-align="center" data-field="action">Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </form>
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
    <!-- Confirmation Modal -->
    <div id="" class="modal fade attendancerect-confirmation" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0"><span id='stmt'></span><span id='label'></span></h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <p id="modal-msg"></p>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn close-btn btn-success" id="confirm">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Confirmation Modal END -->

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
    <script>
        let currentClass = <?php echo json_encode($class); ?>;
    </script>
    <script type="module" src="../js/faculty/attendance.js"></script>

</body>

</html>