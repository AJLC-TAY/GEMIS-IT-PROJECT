<?php include_once ("../inc/head.html"); ?>
    <title>PCNHS GEMIS</title>
</head>

<?php 
    $content = null;
    // $isAddPageUnderProgram = FALSE;
    include_once ('../class/Administration.php');
    include_once ('subjectForm.php');
    
    if (isset($_GET['state'])) {
        // $isAddPageUnderProgram = isset($_GET['code']) ? TRUE : FALSE;
        // $content = getSubjectPageContent($_GET['state']);
        $state = $_GET['state'];
        if ($state !== 'view') {
            $content = getSubjectForm($state);
        } else {
            $content = getSubjectViewContent();
        }
    } else {
        return;
    }

    session_start();
?>
<body>
    <!-- SPINNER -->
    <div class="spinner-con">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once ('../inc/admin/sidebar.html'); ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <div class="col-lg-9">
                    <div class="row mt ps-3">
                        <?php echo $content->breadcrumb; ?>
                        <div class="row">
                            <?php echo $content->main; ?>
                        </div>
                    </div>
                </div>
                <!--main content end-->
                <!--footer start-->
                <?php include_once ("../inc/footer.html");?>
                <!--footer end-->
            </section>
        </section>
    </section>
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px;">
        <div class="position-absolute bottom-0 end-0">
            <div class="toast-container">
                <div class="toast warning-toast bg-danger text-white mt-5"  role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-body"></div>
                </div>
    
                <div class="toast success-toast bg-success text-white mt-5" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-body"></div>
                </div>
    
                <div class="toast normal-toast mt-5" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-body"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- END TOAST -->

</body>

<script type='text/javascript' src="../js/common-custom.js"></script>
<script type='text/javascript' src="../js/admin/subject.js"></script>
</html>