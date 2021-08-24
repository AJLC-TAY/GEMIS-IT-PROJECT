<?php include_once("../inc/head.html"); ?>
<title>Student List | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</link>
</head>

<body>
    <section id="container">
        <?php include_once('../inc/admin/sidebar.html'); ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper"></section>
            <div class="row">
                <div class="col-lg-11">
                    <div class="row mt ps-3">
                        <!-- HEADER -->
                        <header>
                            <!-- BREADCRUMB -->
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item active"><a href="studentList.php">Student</a></li>
                                </ol>
                            </nav>
                            <div class="d-flex justify-content-between mb-3">
                                    <h3>Students</h3>
                                    <div>
                                        <button type="button" class="view-archive btn btn-secondary"><i class="bi bi-eye me-2"></i>View Archived Students</button>
                                    </div>
                                </div>
                        </header>

                        <!-- Student List -->
                        <div class="container mt-3">
                            <div class="card w-100 h-auto bg-light">
                                <table id="table" class="table-striped">
                                    <thead class='thead-dark'>
                                        <div class="d-flex justify-content-between mb-3">
                                            <!-- SEARCH BAR -->
                                            <span class="flex-grow-1 me-5">
                                                <input id="search-input" type="search" class="form-control" placeholder="Search something here">
                                            </span>
                                            <div>
                                                <button class="btn btn-danger btn-sm" title='Deactivate Faculty'>Deactivate</button>
                                                <button class="btn btn-secondary btn-sm" title='Reset Password'>Reset Password</button>
                                                <button class="btn btn-dark btn-sm" title='Export'><i class="bi bi-box-arrow-up-left me-2"></i>Export</button>
                                            </div>
                                        </div>

                                        <tr>
                                            <th data-checkbox="true"></th>
                                            <th scope='col' data-width="100" data-align="left" data-sortable="true" data-field='lrn'>LRN</th>
                                            <th scope='col' data-width="400" data-align="left" data-sortable="true" data-field='name'>Student Name</th>
                                            <th scope='col' data-width="300" data-align="left" data-sortable="true" data-field='section'>Section</th>
                                            <th scope='col' data-width="200" data-align="center" data-field="action">Actions</th>
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
    <script src="../assets/js/bootstrap-table.min.js"></script>
    <script src="../assets/js/bootstrap-table-en-US.min.js"></script>
    <script src="../js/common-custom.js"></script>
    <script type="module" src="../js/admin/studentlist.js"></script>
</body>
<!-- JQUERY FOR BOOTSTRAP TABLE
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>
<script type="text/javascript"> //javascript to be edited
    var spinner = $('.spinner-con')
    var $table
    var code = 'k12acad'

    function onPostBodyOfTable() {

    }

    $(document).ready(function() {
        spinner.show()
        /** Display active menu item */
        $('#curr-management a:first').click()
        $('#subject').addClass('active-sub')

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

        spinner.fadeOut(500)
    })
</script> -->

</html>