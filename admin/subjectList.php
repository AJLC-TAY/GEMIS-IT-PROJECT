<?php include_once("../inc/head.html"); ?>
<title>Subject Page | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</link>
</head>

<body>
    <section id="container">
        <?php include_once('../inc/admin/sidebar.html'); ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-10">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item"><a href="subjectlist.php">Subject</a></li>
                                    </ol>
                                </nav>
                                <h2>Subject</h2>
                                <!-- SEARCH BAR -->
                                <input id="search-input" type="search" class="form-control" placeholder="Search something here">
                            </header>
                            <!-- Subject table -->
                            <div class="container mt-5">
                                <table id="table" class="table-striped">
                                    <thead class='thead-dark'>
                                        <div class="d-flex justify-content-between mb-3">
                                            <h4>Subject List</h4>
                                            <div>
                                                <button class="btn btn-secondary" title='Archive strand'>Archive</button>
                                                <a href="subject.php?state=add" id="add-btn" class="btn btn-success add-prog" title='Add new strand'>Add Subject</a>
                                                <button class="btn btn-secondary" title='Archive strand'>Export</button>
                                            </div>
                                        </div>

                                        <tr>
                                            <th data-checkbox="true"></th>
                                            <th scope='col' data-width="100" data-align="right" data-field="sub_code">Code</th>
                                            <th scope='col' data-width="600" data-sortable="true" data-field="sub_name">Subject Name</th>
                                            <th scope='col' data-width="100" data-sortable="true" data-field="sub_type">Type</th>
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
                <?php include_once("../inc/footer.html"); ?>
                <!--footer end-->
            </section>
        </section>
        <!-- ADD SUBJECT MODAL -->
        <div class="modal" id="add-prog-modal" tabindex="-1" aria-labelledby="modal  " aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <h4 class="mb-0">Add Subject</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="prog-form" action="">
                            <div class="form-group">
                                <label for=" ">Subject Code</label>
                                <input id=" " type="text" name="code" class='form-control' placeholder="Enter unique code here. ex. AM1" required>
                                <p class="unique-error-msg text-danger m-0 invisible"><small>Please provide a unique subject code</small></p>
                                <label for=" ">Subject Name</label>
                                <input id=" e" type="text" name="name" class='form-control' placeholder="ex. Entrepreneurship 2" required>
                                <p class="name-error-msg text-danger m-0 invisible"><small>Please provide the subject name</small></p>
                                <label for=" e">Subject Type</label>
                                <input id=" " type="text" name="name" class='form-control' placeholder="ex. ABM 2" required>
                                <p class="name-error-msg text-danger m-0 invisible"><small>Please provide the subject type</small></p>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="submit-prog" form="prog-form" class="submit btn btn-primary" data-link=''>Add</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>
<script type="text/javascript"> //javascript to be edited
    var $table
    var code = 'k12acad'

    function onPostBodyOfTable() {

    }

    $(document).ready(function() {
        /** Display active menu item */
        $('#curr-management a:first').click()
        $('#program').addClass('active-sub')

        var $table = $('#table').bootstrapTable({
            "url": `getAction.php?data=subjects`,
            "method": 'GET',
            "search": true,
            "searchSelector": '#search-input',
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
            $(this).closest('form').find('.form-input').each(function() {
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