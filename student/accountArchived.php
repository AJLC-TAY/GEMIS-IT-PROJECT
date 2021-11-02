<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
?>
<title>Account Archived | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>

<body>

    <section id="container">
        <!-- MAIN CONTENT START -->
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12 mt-5">
                    <div class="mt-5 ps-3">
                        <div class='d-flex justify-content-center'>
                            <div class="card h-auto bg-light mx-auto mt-5 p-5 text-center " style='width: 65%;'>
                                <h1><i class="bi bi-exclamation-octagon"></i></h1>
                                <h3>This account has been archived!</h3>
                                <p>If you want to re-activate your account, you may contact any faculty in charge for enrolment. You may only reactivate your account until first quarter.</h6>
                                    <br>
                                    <a href='../login.php' class='btn btn-outline-primary me-2 mt-3' data-bs-toggle="modal"><i class="bi bi-arrow-left-circle me-2"></i>Back</a>
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
    </section>
</body>

</html>