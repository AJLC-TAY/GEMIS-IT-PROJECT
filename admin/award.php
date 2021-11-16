<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
if (!isset($_SESSION['sy_id'])) {
    header("Location: index.php");
}
?>
<title>Award | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
<link href='../css/report.css' rel='stylesheet'>
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
        <?php include_once('../inc/adminSidebar.php'); ?>
        <!--MAIN CONTENT -->
        <section id="main-content">
            <section class="wrapper ps-4">
                <div class="row">
                    <div class="row ps-3">
                        <?php
                        if (isset($_GET['type']))
                        {
                            switch ($_GET['type']) {
                                case "ae":
                                    require_once ("award/preAExcellence.php");
                                    break;
                                case "ca":
                                    require_once ("award/conduct.php");
                                    break;
                                case "pa":
                                    require_once ("award/perfectAttendance.php");
                                    break;
                                case "re":
                                case "im":
                                    require_once ("award/awardGeneric.php");
                                    break;
                            }
                        } else {
                            require_once ("award/parameters.php");
                        }
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
<script type="module" src="../js/admin/award.js"></script>

</html>