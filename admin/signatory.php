<?php
session_start();
include_once("../inc/head.html");
require_once("../class/Administration.php");
$admin = new Administration();

$administrators = $admin->listAdministrators();
$faculty = $admin->listFaculty();


?>
<title>Admin | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</head>

<body>
    <!-- SPINNER -->
    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/admin/sidebar.php'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item">Signatory</li>
                                    </ol>
                                </nav>
                                <h3 class="fw-bold">Signatory List</h3>
                            </header>
                            <button class="btn btn-sm btn-success" onclick="renderData()" data-bs-toggle="modal" data-bs-target="#modal-form">Add Signatory</button>
                            <!-- HEADER END -->
                            <div class="card w-100 h-auto bg-light">
                                <div id="toolbar" class="d-flex justify-content-between mb-3">
                                    <!-- SEARCH BAR -->
                                    <span class="flex-grow-1 me-3">
                                     <input id="search-input" type="search" class="form-control" placeholder="Search something here">
                                    </span>
                                </div>
                                <div class="row">
                                    <button class="btn btn-sm btn-danger table-opt">Delete</button>
                                </div>

                                <table id="table" class="table-striped">
                                    <thead class='thead-dark'>
                                    <tr>
                                        <th data-checkbox="true"></th>
                                        <th scope='col' data-width="100" data-halign="center" data-align="left" data-field="sign_id">Sign ID</th>
                                        <th scope='col' data-width="250" data-halign="center" data-align="left" data-sortable="true" data-field="id">ID</th>
                                        <th scope='col' data-width="750" data-align="center" data-sortable="true" data-field="name">Name</th>
                                        <th scope='col' data-width="750"  data-halign="center" data-align="left" data-sortable="true" data-field="position">Position</th>
                                        <th scope='col' data-width="100" data-align="center" data-field="action">Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>


                        </div>
                    </div>
                </div>
            </section>
            <!-- MAIN CONTENT END-->
            <!-- FOOTER -->
            <?php include_once("../inc/footer.html"); ?>
            <!-- FOOTER END -->
        </section>
    </section>
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->
    <!--MODAL-->
    <div class="modal fade" id="modal-form" tabindex="-1" aria-labelledby="modal addProgram" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0"></h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="signatory-form" action="action.php" method="POST">
                        <input type="hidden" name="action" value="">
                        <p class="text-secondary"><small>Please complete the following</small></p>
                        <div class="form-group needs-validation" novalidate>
                            <label for="id-no">ID No</label>
                            <select name="signatory" class="select2 px-0 form-select form-select-sm" id="id-no-select" required>
                                <option>Search user</option>
                                <optgroup value="administrator" label="Administrators" class="select2-result-selectable">
                                    <?php
                                    foreach($administrators as $element) {
                                        echo "<option value='{$element->admin_id}'>{$element->name}</option>";
                                    }
                                    echo "<optgroup value='faculty' label='Faculty' class='select2-result-selectable'>";
                                    foreach($faculty as $element) {
                                        echo "<option value='{$element->get_teacher_id()}'>{$element->get_name()}</option>";
                                    }
                                    ?>
                            </select>
                            <div class="invalid-input">
                                Please enter a unique code
                            </div>
                        </div>
                        <br>
                        <div class="form-group needs-validation" novalidate>
                            <label for="position">Role | Position</label>
                            <input id="position" class="form-control form-control-sm mb-0" type="text" name="position">
                            <div class="invalid-input">
                                Please provide position
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                    <input type="submit" name="delete" form="signatory-form" class="btn btn-danger" value="Delete">
                    <input type="submit" id="submit-again" class="btn btn-secondary" value="Submit & Add again">
                    <input type="submit" name="submit" form="signatory-form" class="btn btn-primary" value="Add">
                </div>
            </div>
        </div>
    </div>
    <!--MODAL END-->

    <!-- BOOTSTRAP TABLE JS -->
    <script src="../assets/js/bootstrap-table.min.js"></script>
    <script src="../assets/js/bootstrap-table-en-US.min.js"></script>
    <!--CUSTOM JS-->
    <script src="../js/common-custom.js"></script>
    <script>
        function renderData(data = null) {
            showSpinner()
            let modal = $("#modal-form")

            let action = "Add"
            $("[name='delete']").addClass('d-none')
            if (data !== null) {

                let info = signatoryTable.bootstrapTable('getRowByUniqueId', data)
                $("#id-no-select").select2('val', info.id)
                $("#position").val(info.position)
                $("[name='delete']").removeClass('d-none')
                action = 'Save'
            }
            $("#signatory-form").find("input[name='action']").val(action.toLowerCase() + "Signatory")
            modal.find(".modal-title h4").html(`${action} Signatory`)
            modal.find(".modal-footer [name='submit']").val(action)
            modal.modal('show')
            hideSpinner()
        }

        const tableSetup = {
            url:                `getAction.php?data=signatory`,
            method:             'GET',
            uniqueId:           'id',
            idField:            'id',
            height:             425,
            maintainMetaDat:    true,       // set true to preserve the selected row even when the current table is empty
            clickToSelect:      true,
            pageSize:           10,
            pagination:         true,
            buttonsToolbar:     ".buttons-toolbar",
            pageList:           "[10, 25, 50, All]",
            paginationParts:    ["pageInfoShort", "pageSize", "pageList"]
        }
        let signatoryTable = $("#table").bootstrapTable(tableSetup)

        $(function() {
            preload("#signatory")
            // $.fn.select2.defaults.set("theme", "bootstrap");
            $("#id-no-select").select2({
                theme: "bootstrap-5",
                width: null,
                dropdownParent: $('.modal')
            })

            // $(document).on("submit", "#signatory-form", function (e) {
            //     e.preventDefault()
            //     let formData = $(this).serializeArray()
            //     formData.push({name: 'stud_id', value: ''})
            // }).

            $(document).on("click", ".table-opt", function (e) {
                let selections = signatoryTable.bootstrapTable('getSelections')
                if (selections.length < 1) return showToast("danger", "Please select a signatory first")
            })
            $(document).on("click", "#submit-again", function (e) {
                e.preventDefault()

            })
            hideSpinner()
        })
    </script>


</body>

</html>