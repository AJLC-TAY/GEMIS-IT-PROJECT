<?php 
include_once("../inc/head.html"); 
session_start();

$page_path = "faculty/facultyList.php";
$js_file_path = "../js/admin/faculty-list.js";
$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $page_path = "faculty/facultyForm.php";
    $js_file_path = "../js/admin/faculty-form.js";
} else if (isset($_GET['id'])) {
    $action = "profile";
    $page_path = "faculty/facultyProfile.php";
    $js_file_path = "../js/admin/faculty.js";
    // $jsFilePath = "../js/admin/faculty-temp.js";
}
?>
<title>Faculty | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet' />
</head>

<body>
    <!-- SPINNER START -->
    <div class="spinner-con">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>x
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/admin/sidebar.php'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-11">
                        <div class="row mt ps-3">
                        <?php include($page_path); ?>
                        </div>
                    </div>
                </div>
                <!-- FOOTER START -->
                <?php include_once("../inc/footer.html"); ?>
                <!-- FOOTER END -->
        </section>
    </section>
    <!-- MAIN CONTENT END -->
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0; right: 0;">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->
    <!--ADD SUBJECT CLASS MODAL-->
    <div id="add-sc-modal" class="modal fade">
        <div class="modal-dialog modal-xl modal-dialog-centered ">
            <div class="modal-content" style="height: auto;">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Add subject class</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class='text-secondary'><small>Check subject class/es to be assigned or switched to the faculty.</small></p>
                    <div class="d-flex justify-content-between mb-1">
                        <!-- SEARCH BAR -->
                        <div class="search-con w-100 d-flex mb-3">
                            <form>
                                <div class="flex-grow-1 d-flex me-3">
                                    <input id="search-sc-input" type="search" class="form-control form-control-sm mb-0 me-2" placeholder="Search subject here">
                                    <input type="reset" class='clear-table-btn btn btn-dark btn-sm shadow-sm' value="Clear">
                                </div>
                            </form>
                            <div class="dropdown">
                                <button class="btn btn-sm shadow" type="button" id="sc-filter" data-bs-toggle="dropdown" aria-expanded="false">
                                    Filter
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="sc-filter">
                                    <li><a href="#" id="all-btn" class="dropdown-item">All</a></li>
                                    <li><a href="#" id="available-btn" class="dropdown-item">Available</a></li>
                                    <li><a href="#" id="unavailable-btn" class="dropdown-item">For switching</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <table id="sc-table" class="table-sm">
                        <thead class='thead-dark'>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th scope='col' data-width="200" data-align="center" data-field="sub_class_code">SC Code</th>
                            <th scope='col' data-width="200" data-halign="center" data-align="left" data-sortable="true" data-field="section_name">Section Name</th>
                            <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="section_code">Section Code</th>
                            <th scope='col' data-width="300" data-halign="center"  data-align="left" data-sortable="true" data-field="sub_name">Subject Name</th>
                            <th scope='col' data-width="100" data-align="center" data-sortable="true" data-field="teacher_id">Teacher</th>
                            <th scope='col' data-width="200" data-align="center" data-sortable="true" data-field="for_grd_level">Grade Level</th>
                            <th scope='col' data-width="100" data-align="center" data-field="statusImg">Status</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <form id="sc-form" method="POST" action="action.php" data-form="<?php echo $action; ?>">
                        <input type="hidden" name="teacher_id" value="" />
                        <input type="hidden" name="action" value="assignSubClasses">
                        <button id='cancel-as-btn' class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Cancel</button>
                        <input type="submit" form="sc-form" id='assigned-sc-btn' class="submit btn btn-success" value="Assign">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--ADD SUBJECT CLASS MODAL END-->
</body>

<!--BOOTSTRAP TABLE JS-->
<script src='../assets/js/bootstrap-table.min.js'></script>
<script src='../assets/js/bootstrap-table-en-US.min.js'></script>

<!--CUSTOM JS-->
<script src="../js/common-custom.js"></script>
<script type="module" src="<?php echo $js_file_path; ?>"></script>
</html>
