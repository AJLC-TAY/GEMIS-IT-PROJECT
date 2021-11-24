<?php 
require_once("sessionHandling.php");
include_once("../inc/head.html"); 
include_once('../inc/studentSideBar.php');

?>
<title>Student | GEMIS</title>
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
        <!-- MAIN CONTENT START -->
       
                
        </section><section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class=" ps-3">
                        <div class='d-flex justify-content-center'>
                            <div class="card h-auto bg-light mx-auto mt-5 p-5 text-center " style='width: 65%;'>
                            <?php if ($_GET['page'] == 'enrolled'){
                                echo "
                                <h1><i class='bi bi-check-circle'></i></h1>
                                <h4>You are currently enrolled in PCNHS' Senior Highschool SY {$_SESSION['school_year']}</h4>
                                ";
                            } elseif($_GET['page'] == 'failed') {
                                echo "
                                <h4>You can't enroll, you failed <i class='bi bi-emoji-smile'></i></h4> 
                                ";
                            } else {
                                echo "
                                <h4>Congrats! You have finished PCHNS' Senior High Curriculum</i> </h4> 
                                ";
                            }
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
    <!-- FOOTER START -->
    <?php include_once("../inc/footer.html"); ?>
                <!-- FOOTER END -->
    <!-- MAIN CONTENT END -->
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->

    <script src='../assets/js/bootstrap-table.min.js'></script>
    <script src='../assets/js/bootstrap-table-en-US.min.js'></script>
    <script src='../assets/js/bootstrap.bundle.min.js'></script>
    <script src="../js/common-custom.js"></script>
    <script>
        $(function () {
            preload("#enrollment");
            hideSpinner();
        });
    </script>
    
</body>

</html>
