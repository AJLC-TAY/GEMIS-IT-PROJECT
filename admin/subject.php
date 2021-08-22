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
                <div class="col-lg-11">
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

    <!-- Archive modal -->
    <div class="modal" id="archive-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Confirmation</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Do you want to archive <span id="modal-identifier"></span>?</h5>
                    <p class="modal-msg"></p>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary archive-btn">Archive</button>
                </div>
            </div>
        </div>
    </div>
    
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
    <script type='text/javascript' src="../js/common-custom.js"></script>
    <script type='text/javascript' src="../js/admin/subject.js"></script>
</body>
</html>