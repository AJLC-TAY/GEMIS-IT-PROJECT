<?php include_once("../inc/head.html"); ?>
<title>Enrollment | GEMIS</title>
<link rel="stylesheet" href="../css/general.css">
</link>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" />
</head>


<body>
    <?php include('../class/Administration.php');
    $admin = new Administration();
    ?>
    <!-- SPINNER -->
    <div class="spinner-con">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->

    <section id="container">
        <?php include_once('../inc/admin/sidebar.html'); ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper ps-4">
                <div class="row">
                    <div class="col-lg-11">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Enrollment</li>
                                    </ol>
                                </nav>
                                <div class="d-flex justify-content-between">
                                    <h3 class="fw-bold">Enrollment</h3>
                                
                                </div>
                            </header>
                            <!-- No result message -->
                            
                            <div class="cards-con d-flex flex-wrap container mt-4 h-auto" style="min-height: 75vh;">

                            </div>
                        </div>
                    </div>
                </div>
                <!--main content end-->
                <!--footer start-->
                <?php include_once("../inc/footer.html"); ?>
                <!--footer end-->
            </section>
        </section>
    </section>

    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->

</body>
<script type="text/javascript" src="../js/common-custom.js"></script>
<script type="text/javascript" src="../js/admin/cardPage.js"></script>
<script type="text/javascript" src="../js/admin/"></script>

</html>