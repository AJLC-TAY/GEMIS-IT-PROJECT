<?php 
require_once("sessionHandling.php");
include_once("../inc/head.html");
include('../class/Administration.php'); ?>
<title>Program | GEMIS</title>
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
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper ps-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <?php
                            if (isset($_GET['prog_code'])){
                                include_once("program/programView.php"); 
                                $jsFilePath = "../js/admin/program.js";
                            } else {
                                include_once("program/programCards.php"); 
                                $jsFilePath = "../js/admin/program-card.js";
                            }
                            ?>
                        
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
            <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
        </div>
        <!-- TOAST END -->
</body>
<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>
<!-- VALIDATION -->
<script src="../js/validation/jquery.validate.min.js"></script>
<script src="../js/validation/additional-methods.min.js"></script>
<script src="../js/validation/validation.js"></script>
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
<script type="text/javascript" src="../js/common-custom.js"></script>
<script type="module" src="<?php echo $jsFilePath; ?>"></script>
</html>