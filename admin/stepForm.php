<?php include_once("../inc/head.html"); ?>
<title>Step Sample | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
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
                        <div class="bs-stepper">
                            <div class="bs-stepper-header">
                                <div class="step" data-target="#test-l-1">
                                    <button type="button" class="btn step-trigger">
                                        <span class="bs-stepper-circle">1</span>
                                        <span class="bs-stepper-label">First step</span>
                                    </button>
                                </div>
                                <div class="line"></div>
                                <div class="step" data-target="#test-l-2">
                                    <button type="button" class="btn step-trigger">
                                        <span class="bs-stepper-circle">2</span>
                                        <span class="bs-stepper-label">Second step</span>
                                    </button>
                                </div>
                                <div class="line"></div>
                                <div class="step" data-target="#test-l-3">
                                    <button type="button" class="btn step-trigger">
                                        <span class="bs-stepper-circle">3</span>
                                        <span class="bs-stepper-label">Third step</span>
                                    </button>
                                </div>
                            </div>
                            <div class="bs-stepper-content">
                                <div id="test-l-1" class="content">
                                    <p class="text-center">test 1</p>
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-primary" onclick="stepper.next()">Next</button>
                                    </div>
                                </div>
                                <div id="test-l-2" class="content">
                                    <p class="text-center">test 2</p>
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                        <button class="btn btn-primary" onclick="stepper.next()">Next</button>
                                    </div>
                                </div>
                                <div id="test-l-3" class="content">
                                    <p class="text-center">test 3</p>
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                        <button class="btn btn-primary">Submit</button>
                                    </div>
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
</section>
</body>

<!-- BOOTSTRAP TABLE JS -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>
<!--CUSTOM JS-->
<script src="../js/common-custom.js"></script>
<script>
    preload("#faculty")

    var stepper = new Stepper(document.querySelector('.bs-stepper'))
    $(function() {


        hideSpinner()
    })
</script>


</html>