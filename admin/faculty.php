<?php include_once("../inc/head.html"); 
      require_once("../class/Administration.php");
      require_once("facultyform.php");
      session_start();
      $content = getFacultyFormContent();
?>
<title>Add Faculty | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</link>
</head>

<body>
    <!-- SPINNER -->
    <div class="spinner-con">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <section id="container">
        <?php include_once('../inc/admin/sidebar.html'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper"></section>
            <div class="row">
                <div class="col-lg-10">
                    <div class="row mt ps-3">
                        <!-- HEADER -->
                        <header>
                            <!-- BREADCRUMB -->
                            <nav aria-label='breadcrumb'>
                                <ol class='breadcrumb'>
                                    <li class='breadcrumb-item'><a href='index.php'>Home</a></li>
                                    <li class='breadcrumb-item'><a href='facultyList.php'>Faculty</a></li>
                                    <li class='breadcrumb-item active'><?php echo $content->state; ?> Faculty</li>
                                </ol>
                            </nav>
                            <h2><?php echo $content->state; ?> Faculty</h2>
                        </header>
                        <!-- MAIN CONTENT -->
                        <?php echo $content->main;?>
                    </div>
                </div>
            </div>
            <!--main content end-->
            <!--footer start-->
            <?php include_once("../inc/footer.html"); ?>
            <!--footer end-->
        </section>
    </section>
        <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div class="position-absolute" style="bottom: 20px; right: 25px;">
            <div class="toast warning-toast bg-danger text-white" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body"></div>
            </div>

            <div class="toast add-toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body">
                </div>
            </div>
        </div>
    </div>

</body>
<script type="text/javascript" src="../js/common-custom.js"></script>
<script type="text/javascript">
    let subjects = <?php echo json_encode($content->subjects); ?>;
</script>
<script type="text/javascript" src="../js/admin/facultyForm.js"></script>

</html>
