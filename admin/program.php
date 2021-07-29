<?php include_once("../inc/head.html"); ?>
<title>Program | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>

<?php
include('../class/Administration.php');
$admin = new Administration();
$program = $admin->getProgram();
$prog_name = $program->get_prog_desc();
$prog_code = $program->get_prog_code();
$prog_curr_code = $program->get_curr_code();
$state = "disabled";
$edit_btn_state = "";

if (isset($_GET['state']) && $_GET['state'] == 'edit') {
    $state = "";
    $edit_btn_state = "disabled";
}
?>

<body>
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
                    <div class="col-lg-9">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
                            <header>
                                <!-- BREADCRUMB -->
                                <nav class="mb-4" aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="programlist.php">Programs</a></li>
                                        <li class="breadcrumb-item active" aria-current="page"><?php echo $prog_name; ?></li>
                                    </ol>
                                </nav>
                                <h2><?php //echo $prog_name;?></h2>
                                <h6><?php //echo $prog_curr_code;?></h6>
                                <!-- <hr> -->
                                <h2><?php echo $prog_name;?></h2>
                                <h6><?php echo $prog_curr_code;?></h6>
                            </header>

                            <!-- Form -->
                            <div class="container mt-4">
                                <div class="card w-100 h-auto">
                                    <h5 class="text-start fw-bold">Information</h5><hr class="mt-1 mb-4">
                                    <form action="action.php" method="POST">
                                        <div class="form-group row">
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Program Code</label>
                                            <div class="col-xl-10 col-lg-9">
                                                <input type="hidden" name="current_code" value="<?php echo $prog_code; ?>">
                                                <?php echo "<input class='form-input form-control' type='text' name='code' value='$prog_code' $state required>"; ?>
                                            </div>
                                            <label class="col-xl-2 col-lg-3 col-form-label text-start">Description</label>
                                            <div class='col-xl-10 col-lg-9'>
                                                <!-- <input name="name" value="<?php //echo $prog_name; ?>" disabled> -->
                                                <?php echo "<textarea class='form-input form-control' name='name' $state>" . $prog_name . "</textarea>"; ?>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end col-sm-12">
                                            <?php echo "<button id='edit-btn' class='btn btn-secondary btn-sm' $edit_btn_state>Edit</button>"; ?>
                                            <input type="hidden" name="action" value="updateProgram">
                                            <?php echo "<input type='submit' id='save-btn' class='btn btn-success btn-sm' value='Save' $state>"; ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Track table -->
                            <div class="container mt-5">
                                <div class="card w-100 h-auto">
                                    <table id="table" class="table-striped">
                                        <thead class='thead-dark'>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="fw-bold">Subjects</h5>
                                                <a href="subject.php?state=add&prog_code=<?php echo $prog_code;?>" id="add-btn" class="btn btn-success add-subject" title='Add new subject'>Add subject</a>
                                            </div><hr class="mt-1 mb-4">
                                            <div class="d-flex flex-row-reverse mb-3">
                                            <!-- <div class="d-flex mb-3"> -->
                                                <button class="btn btn-secondary" title='Archive subject'>Archive</button>
                                            </div>
                                            
                                            <tr>
                                                <th data-checkbox="true"></th>
                                                <th scope='col' data-width="100" data-align="right" data-field='sub_code'>Code</th>
                                                <th scope='col' data-width="600" data-sortable="true" data-field="sub_name">Subject Name</th>
                                                <th scope='col' data-width="100" data-sortable="true" data-field="sub_type">Subject Type</th>
                                                <th scope='col' data-width="300" data-align="center" data-field="action">Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
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
</body>
<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>
<script type="text/javascript">
    var code = <?php echo json_encode($prog_code);?>;
    var spinner = $('.spinner-con')
    $(document).ready(function() {
        spinner.show()
        /** Display active menu item */
        $('#curr-management a:first').click()
        $('#program').addClass('active-sub')

        var $table = $('#table').bootstrapTable({
            "url": `getAction.php?prog_code=${code}&data=subjects`,
            "method": 'GET',
            // "search": true,
            // "searchSelector": '#search-curr',
            "uniqueId": "code",
            "idField": "code",
            "height": 450,
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
            $(this).closest('form').find('.form-input').each(function() {
                $(this).prop('disabled', false)
            })
        })
        
        spinner.fadeOut(500)
    })
</script>

</html>