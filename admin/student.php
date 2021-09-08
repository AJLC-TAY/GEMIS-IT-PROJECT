<?php include_once("../inc/head.html"); 
      session_start();
?>
<title>Student | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'></link>
</head>

<body>
    <!-- SPINNER START -->
    <div class="spinner-con">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/admin/sidebar.html'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-11">
                        <div class="row mt ps-3">
                        <?php 
                            if (isset($_GET['action'])) {
                                include_once("student/studentForm.php");
                                $jsFilePath = "../js/admin/student.js";
                            } else if (isset($_GET['id'])){
                                include_once("student/studentProfile.php"); 
                                $jsFilePath = "../js/admin/student.js";
                            } else {
                                include_once("student/studentList.php"); 
                                $jsFilePath = "../js/admin/student-list.js";
                            }
                        ?>
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
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->
</body>

<script src='../assets/js/bootstrap-table.min.js'></script>
<script src='../assets/js/bootstrap-table-en-US.min.js'></script>
<script type="text/javascript" src="../js/common-custom.js"></script>
<script type="module" src="<?php echo $jsFilePath; ?>"></script>
</html>