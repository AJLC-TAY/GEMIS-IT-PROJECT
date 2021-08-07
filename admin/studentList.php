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
                <div class="col-lg-10">
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
                            <h2> Student</h2>
                            <input id="search-input" type="search" class="form-control" placeholder="Search something here">
                        </header>

                        <!-- Student List -->
                        <div class="container mt-2">
                            <table id="table" class="table-striped">
                                <thead class='thead-dark'>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        
                                        <div>
                                            <button class="btn btn-secondary" title='Reset Password'>Reset Password</button>
                                            <button class="btn btn-secondary" title='Archive Student'>Archive</button>
                                            <button id="add-btn" class="btn btn-success add-prog" title='Export Student'>Export</button>
                                            <button class="btn btn-danger" title='Deactivate Student'>Deactivate</button>
                                        </div>
                                    </div>

                                    <tr>
                                        <th data-checkbox="true"></th>
                                        <th scope='col' data-width="100" data-align="right" data-field='lrn'>LRN</th>
                                        <th scope='col' data-width="600" data-sortable="true" data-field='stud_name'>Student Name</th>
                                        <th scope='col' data-width="100" data-align="right" data-field='stud_section'>Section</th>
                                        <th scope='col' data-width="300" data-align="center" data-field='actions'>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class='text-center'>
                                        <td scope="col"><input type="checkbox" /></td>
                                        <td scope='col'></td>
                                        <td scope='col'></td>
                                        <td scope='col'></td>
                                    </tr>
                                </tbody>
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
</body>
<!-- JQUERY FOR BOOTSTRAP TABLE -->
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
</script>

</html>