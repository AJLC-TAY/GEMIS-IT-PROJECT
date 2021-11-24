<?php
require_once("sessionHandling.php");
include_once("../inc/head.html");
require_once("../class/Faculty.php");
$faculty = new FacultyModule();
$id = $_SESSION['id'];
$sub_classes = $faculty->get_handled_sub_classes($id);

?>

<title>Home | GEMIS</title>
</head>

<body>
    <!-- SPINNER -->
    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container-fluid">
        <?php include_once('../inc/facultySidebar.php'); ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper ps-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
                            <header class="mb-4">
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item active">Home</li>
                                    </ol>
                                </nav>
                                <div class="card">
                                    <div class="form-row row">
                                        <div class="form-group col-md-6">
                                            <h2 class="fw-bold mt-3 ms-3">Welcome!</h2>
                                            <ul class="ms-4 list-style p-0">
                                                <li>
                                                    <h4><?php echo $_SESSION['User']; ?></h4>
                                                </li>
                                                <li>School Year: <?php echo $_SESSION['school_year']; ?></li>
                                            </ul>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <img src="../assets/faculty.png" alt="Display image" style="width: 40%; display: block; margin-left: auto; float:right;">
                                        </div>
                                    </div>
                                </div>
                            </header>
                               
                            <!-- CLASSES -->
                            <div class="container mb-3">
                                <div class="row">
                                    <h5 class="fw-bold">ASSIGNED CLASSES</h5>
                                    
                                        
                                                <?php 
                                                if (count($sub_classes) != 0) {
                                                    //    $sub_class_opn .= "<optgroup label='Subject Class'>";
                                                        foreach ($sub_classes as $sub_class) {
                                                            echo "<div class='col-lg-4'>
                                                            <div class='card-box bg-default'>
                                                            <div class='inner'
                                                            <h3>{$sub_class->get_section_name()}</h3>
                                                            <h4>{$sub_class->get_sub_name()}</h4>
                                                            </div>
                                                            </div>
                                                            </div>";
                                                           
                                                        }
                                                    //    $sub_class_opn .= "</optgroup>";
                                                    }
                                                ?>
                                                
                                          
                                    
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
            <!--footer start-->
            <?php include_once("../inc/footer.html"); ?>
            <!--footer end-->
        </section>
        <!--main content end-->
    </section>
    <script src="../js/common-custom.js"></script>
    <script>
        $(function() {
            preload("#home");
            hideSpinner();
        });
    </script>
</body>

</html>