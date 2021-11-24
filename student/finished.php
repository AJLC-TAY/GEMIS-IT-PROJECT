<?php
session_start();
if ($_SESSION['enrolled'] != TRUE) {
    header('Location: ../student/enrollment_form.php');
}
include_once("../inc/head.html"); ?>
<title>Enrollment | GEMIS</title>
</head>
<!DOCTYPE html>
<body>
    <!-- SPINNER -->
    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <section id="container">
        <!-- MAIN CONTENT START -->
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class=" ps-3">
                        <div class='d-flex justify-content-center mt-5'>
                            <div class="card h-auto bg-light mx-auto mt-5 p-5 text-center " style='width: 65%;'>
                                    <h1><i class="bi bi-check-circle"></i></h1>
                                    <h4>You have successfully submitted an enrollment request for PCNHS Senior Highschool SY <?php echo $_SESSION['school_year']; ?></h4>
                                    <p>Please submit the physical copy of the following documents to the school to be fully enrolled.</h6>    
                                    <ol class="list-group list-group-numbered">
                                        <li class="list-group-item">PSA Birth Certificate</li>
                                        <li class="list-group-item">Form 137</li>
                                    </ol>
                                <div class='d-flex justify-content-center mt-2'>
                                    <a href='../login.php' class='btn btn-outline-primary me-2 mt-3' data-bs-toggle="modal"><i class="bi bi-arrow-left-circle me-2"></i>Back</a>
                                </div>
                            </div>
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

    <script src="../js/common-custom.js"></script>
    <script>
        $(function() {
            $("#main-spinner-con").hide();
        });
    </script>
</body>

</html>