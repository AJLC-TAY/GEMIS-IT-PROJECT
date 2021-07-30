<?php include_once("../inc/head.html"); ?>
<title>Faculty Members List | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</link>
</head>

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
                                        <li class="breadcrumb-item"><a href="facultyList.php">Faculty</a></li>
                                    </ol>
                                </nav>
                                <h2>Faculty Members</h2>
                                <!-- SEARCH BAR -->
                                <input id="search-input" type="search" class="form-control" placeholder="Search something here">

                            </header>
                            <!-- Subject table -->
                            <div class="container mt-5">
                                <table id="table" class="table-striped">
                                    <thead class='thead-dark'>
                                        <div class="d-flex justify-content-between mb-3">
                                            <h4>Faculty Members</h4>
                                            <div>
                                                <a href="" id="add-btn" class="btn btn-success">Add Faculty Member</a>
                                                <button class="btn btn-secondary" title='Deactivate Faculty'>Deactivate</button>
                                                <button class="btn btn-secondary" title='Reset Password'>Reset Password</button>
                                                <button class="btn btn-secondary" title='Export'>Export</button>
                                            </div>
                                        </div>

                                        <tr>
                                            <th data-checkbox="true"></th>
                                            <th scope='col' data-width="500" data-align="left" data-sortable="true" data-field="">Name</th>
                                            <th scope='col' data-width="600" data-align="center" data-field="">Department</th>
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
    </section>
       <!-- TOAST -->
       <div aria-live="polite" aria-atomic="true" class="position-relative" style="min-height: 200px;">
        <div class="position-absolute" style="bottom: 20px; right: 25px;">
            <div class="toast warning-toast bg-danger text-white" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body"></div>
            </div>

            <div class="toast add-toast bg-success text-white" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body">
                    Faculty successfully added
                </div>
            </div>
        </div>
    </div>
    <!-- TOAST END -->
</body>

<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script src="../assets/js/bootstrap-table.min.js"></script>
<script src="../assets/js/bootstrap-table-en-US.min.js"></script>
<script type="text/javascript"> //javascript to be edited
    var spinner = $('.spinner-con')
    var $table
    var code = ''

    function onPostBodyOfTable() {

    }

    $(document).ready(function() {
        spinner.show()
        /** Display active menu item */
        $('# a:first').click()
        $('# ').addClass('')

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

        spinner.fadeOut(500)
    })
</script>