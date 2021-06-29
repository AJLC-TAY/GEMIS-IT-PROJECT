<?php include_once("../inc/head.html"); ?>
<title>Program Page | GEMIS</title>
<link rel="stylesheet" href="../css/general.css"></link>
</head>

<body>
    <?php include('../class/Administration.php'); 
    $admin = new Administration();

    require_once('../class/Dataclasses.php');
    ?>

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
                                        <li class="breadcrumb-item active" aria-current="page">Programs</li>
                                    </ol>
                                </nav>
                                <h2>Programs</h2>
                                <!-- SEARCH BAR -->
                                <input id="search-input" type="search" class="form-control" placeholder="Search something here">
                            </header>
                            <!-- SPINNER -->
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <!-- No result message -->
                            <div class="msg w-100 d-flex justify-content-center d-none">
                                <p class="m-auto">No results found</p>
                            </div>
                            <div class="curriculum-con d-flex flex-wrap container">
                                <?php //$programList = $admin->listProgram();

                                $programList = [new Program('GAS', 'k12acad', 'General Academic Strand'), 
                                                new Program('HUMMS', 'k12acad', 'Hummanities and Social Sciences'),
                                                new Program('TVL-EPAS', 'k12acad', 'Electronics'),
                                                new Program('TVL-COOKERY', 'k12acad', 'Cookery')];
                                foreach ($programList as  $prog) {
                                    $code = $prog->get_prog_code();
                                    $curr_code = $prog->get_curr_code();
                                    $desc = $prog->get_prog_name();
                                    
                                    echo "<div data-id='" .  $code . "' class='card shadow-sm p-0'>
                                            <div class='card-body'>
                                                <div class='dropdown'>
                                                    <button type='button' class='kebab btn btn-link rounded-circle' data-bs-toggle='dropdown'></button>
                                                    <ul class='dropdown-menu'>
                                                        <li><a class='dropdown-item' href='program.php?id=" .   $code . "'>Edit</a></li>
                                                        <li><button data-name='" .  $desc ."' class='archive-btn dropdown-item'>Archive</button></li>
                                                        <li><button class='delete dropdown-item' id='" . $code . "'>Delete</button></li>
                                                    </ul>
                                                </div>
                                                <h4>". $desc ." </h4>
                                                <p> ". $curr_code ." | ". $code ."</p>
                                            </div>
                                            <div class='modal-footer p-0'>
                                                <a role='button' class='btn' href='program.php?code=" .  $code . "'>View</a>
                                            </div>
                                        </div>";
                                }

                                    echo "<div class='btn add-program card shadow-sm'>
                                        <div class='card-body'>
                                            Add Program
                                        </div>
                                    </div>";
                                ?>
                            </div>
                            <button type="button" class="view-archive btn btn-link">View Archived Programs</button>
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

     <!-- MODAL -->
     <div class="modal" id="add-prog-modal" tabindex="-1" aria-labelledby="modal addProgram" aria-hidden="true">
        <div class="modal-dialog">
            <form id="program-form" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <h4 class="mb-0">Add Program</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Please complete the following:</h6>
                        <div class="form-group">
                            <label for="prog-code">Code</label>
                            <input id="prog-code" type="text" name="code" class='form-control' placeholder="Enter unique code here. ex. K12A" required>
                            <p class="unique-error-msg text-danger m-0 invisible"><small>Please provide a unique curriculum code</small></p>
                            <label for="prog-name">Name</label>
                            <input id="prog-name" type="text" name="name" class='form-control' placeholder="ex. K12 Academic" required>
                            <p class="name-error-msg text-danger m-0 invisible"><small>Please provide a curriculum name</small></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" id="action" value="addProgram"/>
                        <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                        <input type="submit" form="program-form" class="submit btn btn-primary" value="Add"/>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="archive-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Confirmation</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Do you want to archive <span id="modal-identifier"></span>?</h5>
                    <p class="modal-msg"></p>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary close-btn">Archive</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="view-arch-prog-modal" tabindex="-1" aria-labelledby="modal viewArchivedProgram" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Archived Programs</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="overflow-auto" style="height: 50vh;">

                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Junior High School
                                <button class="btn btn-link">Unarchive</button>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Junior High School
                                <button class="btn btn-link">Unarchive</button>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Junior High School
                                <button class="btn btn-link">Unarchive</button>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Junior High School
                                <button class="btn btn-link">Unarchive</button>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Junior High School
                                <button class="btn btn-link">Unarchive</button>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Junior High School
                                <button class="btn btn-link">Unarchive</button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- Scripts -->
</html>