<?php include_once("../inc/head.html"); ?>
<title>Archieved Curriculum Page | GEMIS</title>
<link rel="stylesheet" href="../css/general.css"></link>
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
        <?php include_once ('../inc/admin/sidebar.html'); ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Curriculum</li>
                                        <li class="breadcrumb-item active" aria-current="page">Archived Curriculum</li>
                                    </ol>
                                </nav>
                                <div class="d-flex justify-content-between">
                                    <h2>Archived Curriculum</h2>
                                </div>
                                <!-- SEARCH BAR -->
                                <input id="search-input" type="search" class="form-control" placeholder="Search something here">
                            </header>
                            <!-- No result message -->
                            <div class="msg w-100 d-flex justify-content-center d-none">
                                <p class="m-auto">No results found</p>
                            </div>
                            <div class="cards-con d-flex flex-wrap container mt-4 h-auto" style="min-height: 75vh;">
                
                            </div>
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
</body>
<script type="text/javascript" src="../js/common-custom.js"></script>
<script type="text/javascript" src="../js/admin/archivedCards.js"></script>
<script type="text/javascript" src="../js/admin/archivedCurr.js"></script>
</html>