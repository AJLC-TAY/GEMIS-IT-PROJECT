<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
$class = 'ABM11';
$current_month = date("F"); // ex. January
$months = array(
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July ',
    'August',
    'September',
    'October',
    'November',
    'December',
);
$url = "getAction.php?data=attendance&class=$class&month=$current_month";
?>
<title>Attendance | GEMIS</title>
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
        <?php include_once('../inc/facultySidebar.php'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
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
                                        <p class="text-secondary m-0"><medium>First Semester</medium></p>
                                        <h3 class="m-0 fw-bold">12 ABM A</h3>
                                        <h5 class="m-0">Attendance</h5>
                                    </div>
                                </div>
                                <hr class="mt-3 mb-4">
                            </header>
                            <!-- HEADER END -->
                            <!-- ATTENDANCE TABLE -->
                            <div class="container mt-1 ms-0">
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
                                                    foreach ($months as $month) {
                                                        echo "<option value='$month' " . ($month == $current_month ? "selected" : "") . ">$month</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-1">
                                                <input type="text" name="academicDays" class="form-control form-control-sm number" placeholder="Days" title="Academic Days">
                                            </div>

                                            <div class="col-auto">
                                                <button class="link btn btn-secondary btn-sm edit-btn ms-2"><i class="bi bi-pencil-square me-2"></i>Edit</button>
                                                <div class="edit-options" style="display: none;">
                                                    <a href="attendance.php" role="button" class="btn btn-sm btn-outline-dark me-1 ms-2">Cancel</a>
                                                    <input type='submit' class="btn btn-sm btn-success save-btn" value="Save">
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
                                </div>
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