<?php include_once ("../inc/head.html"); ?>
<title>Curriculum | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>


<?php $curriculum = 'K12 Academic';
      $curriculum_code = 'K12ACAD';
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
                                        <li class="breadcrumb-item"><a href="curriculumlist.php">Curriculum</a></li>
                                        <li class="breadcrumb-item active" aria-current="page"><?php echo $curriculum; ?></li>
                                    </ol>
                                </nav>
                                <h2><?php echo $curriculum; ?> Curriculum</h2>
                            </header>

                            <!-- Form -->
                            <form>
                                <div class="container">
                                    <h4>Information</h4>
                                    <label>Curriculum Code</label>
                                    <input type="text" name="code" disabled required>
                                    <label>Curriculum Name</label>
                                    <input type="text" name="name" disabled required>
                                    <label>Description</label>
                                    <input name="desc" disabled></input>
                                    <button id="edit-btn" class="btn btn-secondary">Edit</button>
                                    <button id="save-btn" class="btn btn-success" disabled>Save</button>
                                </div>
                            </form>
                            <!-- Track table -->
                            <div class="container mt-5">
                                <table id="table" class="table-striped">
                                    <thead class='thead-dark'>
                                        <div class="d-flex justify-content-between mb-3">
                                            <h4>Strand List</h4>
                                            <div>
                                                <button class="btn btn-secondary" title='Archive strand'>Archive</button>
                                                <button id="add-btn" class="btn btn-success add-prog" title='Add new strand'>Add strand</button>
                                            </div>
                                        </div>
        
                                        <tr>
                                            <th data-checkbox="true"></th>
                                            <th scope='col' data-width="100" data-align="right" data-field='prog_code'>Code</th>
                                            <th scope='col' data-width="600" data-sortable="true" data-field="prog_name">Program/Strand Name</th>
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
    <!-- ADD PROGRAM MODAL -->
    <div class="modal" id="add-prog-modal" tabindex="-1" aria-labelledby="modal addProgram" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Add Strand/Program</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="prog-form" action="">
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
    </div>
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
        $('#curriculum').addClass('active-sub')

        var $table = $('#table').bootstrapTable({
            "url": `getAction.php?code=${code}&data=program`,
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

        $('#save-btn').click(function() {
            $(this).prop("disabled", true)
            $("#edit-btn").prop("disabled", false)
            $(this).closest('form').find('input').each(function() {
                $(this).prop('disabled', true)
            })
        })


    })

    $('.add-prog').click(() => $('#add-prog-modal').modal('toggle'))
    /*** Add new program information through AJAX */
    $('#prog-form').submit(function(event) {
        event.preventDefault()
        var progFormData = $(this).serializeArray()
        var progCode = progFormData[0].value.trim()
        var progName = progFormData[1].value.trim()
        var progCurr = progFormData[2].value.trim()
        progFormData.push({
            'name': 'add_prog'
        })
        var hideUniqueErrorMsg = () => $('.unique-error-msg').removeClass('invisible')

        if (progCode.length == 0) hideUniqueErrorMsg()

        if (progName.length == 0) $('.name-error-msg').removeClass('invisible')
        else {
            $.post('../src/addProg.php', progFormData, function(data) {
                // success
            }).fail(function() {
                hideUniqueErrorMsg()
            })
        }
    })

    /*** Reset program form and hide error messages */
    $(".close").click(() => {
        $('#prog-form').trigger('reset')
        $("[class*='error-msg']").addClass('invisible')
    })
</script>

</html>