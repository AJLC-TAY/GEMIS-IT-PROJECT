<?php include_once ("../inc/head.html"); ?>
<title>Curriculum | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>


<?php 
    include('../class/Administration.php');
    $admin = new Administration();
    // $program = $admin->getProgram(); // define var
    // $prog_name = $program->get_prog_name();
    // $prog_code = $program->get_prog_code();
    
    $prog_code = 'ABM';
    $prog_name = 'Acountancy and Business Management';
?>

<body>

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
                                        <li class="breadcrumb-item"><a href="programlist.php">Programs</a></li>
                                        <li class="breadcrumb-item active" aria-current="page"><?php echo $prog_name; ?></li>
                                    </ol>
                                </nav>
                                <h2><?php echo "$prog_code"." | "." $prog_name"; ?></h2>
                            </header>

                            <!-- Form -->
                            <form action="action.php" method="POST">
                                <div class="container">
                                    <h4>Information</h4>
                                    <label>Program Code</label>
                                    <input type="hidden" name="current_code" value="<?php echo $prog_code; ?>">
                                    <input type="text" name="code" value="<?php echo $prog_code; ?>" disabled required> <!--call var -->
                                    <label>Program Name</label>
                                    <input type="text" name="name" value="<?php echo $prog_name; ?>" disabled required>
                                    <button id="edit-btn" class="btn btn-secondary">Edit</button>
                                    <input type="hidden" name="action" value="updateProgram">
                                    <input type="submit" id="save-btn" class="btn btn-success" value='Submit'  disabled>
                                </div>
                            </form>
                            <!-- Track table -->
                            <div class="container mt-5">
                                <table id="table" class="table-striped">
                                    <thead class='thead-dark'>
                                        <div class="d-flex justify-content-between mb-3">
                                            <h4>Subjects</h4>
                                            <div>
                                                <button class="btn btn-secondary" title='Archive subject'>Archive</button>
                                                <button id="add-btn" class="btn btn-success add-subject" title='Add new subject'>Add subject</button>
                                            </div>
                                        </div>
        
                                        <tr>
                                            <th data-checkbox="true"></th>
                                            <th scope='col' data-width="100" data-align="right" data-field='sub_code'>Code</th>
                                            <th scope='col' data-width="600" data-sortable="true" data-field="sub_name">Subject Name</th>
                                            <th scope='col' data-width="300" data-align="center" data-field="action">Actions</th>
                                        </tr>
                                    </thead>
                                </table>
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
    <!-- ADD SUBJECT MODAL -->
    <!-- <div class="modal" id="add-subject-modal" tabindex="-1" aria-labelledby="modal addSubject" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Add Subject</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="subject-form" action="">
                        <div class="form-group">
                            <label for="prog-code">Strand Code</label>
                            <input id="prog-code" type="text" name="code" class='form-control' placeholder="Enter unique code here. ex. STEM" required>
                            <p class="unique-error-msg text-danger m-0 invisible"><small>Please provide a unique strand code</small></p>
                            <label for="prog-name">Strand Name</label>
                            <input id="prog-name" type="text" name="name" class='form-control' placeholder="ex. Science, Technology, Engineering, and Math" required>
                            <p class="name-error-msg text-danger m-0 invisible"><small>Please provide the program name</small></p>
                            <label for="prog-curr">Curriculum</label>
                            <input type="text" class='form-control' name="curr" value="<?php echo ($curriculum_code);?>" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="submit-prog" form="prog-form" class="submit btn btn-primary" data-link='addProg.php'>Add</button>
                </div>
            </div>
        </div>
    </div> -->
</body>
<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>
<script type="text/javascript">
    var $table
    var code = 'k12acad'

    function onPostBodyOfTable() {

    }

    $(document).ready(function() {
        /** Display active menu item */
        $('#curr-management a:first').click()
        $('#program').addClass('active-sub')

        var $table = $('#table').bootstrapTable({
            "url": `getAction.php?code=${code}&data=subjects`,
            "method": 'GET',
            // "search": true,
            // "searchSelector": '#search-curr',
            "uniqueId": "code",
            "idField": "code",
            "height": 300,
            // "exportDataType": "All",
            "pagination": true,
            "paginationParts": ["pageInfoShort", "pageSize", "pageList"],
            "pageSize": 10,
            "pageList": "[10, 25, 50, All]",
            // "onPostBody": onPostBodyOfTable
        })

        $('#edit-btn').click(function() {
            $(this).prop("disabled", true)
            $("#save-btn").prop("disabled", false)
            $(this).closest('form').find('input').each(function() {
                $(this).prop('disabled', false)
            })
        })

        // $('#save-btn').click(function() {
        //     $(this).prop("disabled", true)
        //     $("#edit-btn").prop("disabled", false)
        //     $(this).closest('form').find('input').each(function() {
        //         $(this).prop('disabled', true)
        //     })
        // })


    })
</script>

</html>