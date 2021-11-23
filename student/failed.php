
<?php
session_start();

include_once("../inc/head.html"); ?>
<title>Enrollment | GEMIS</title>
</head>
<!DOCTYPE html>
<body>
    <!-- SPINNER -->
    <!-- <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div> -->

    <section id="container">
        <!-- MAIN CONTENT START -->
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class=" ps-3">
                        <div class='d-flex justify-content-center'>
                            <div class="card h-auto bg-light mx-auto mt-5 p-5 text-center " style='width: 65%;'>
                            <h4>You can't enroll, you failed <i class='bi bi-emoji-smile'></i></h4> 
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