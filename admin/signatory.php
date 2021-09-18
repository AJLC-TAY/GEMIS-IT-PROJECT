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
                                <div class="d-flex justify-content-between mb-3">
                                    <h3 class="fw-bold">Signatory List</h3>
                                    <div>
                                        <button class="btn btn-success" onclick="renderData()" data-bs-toggle="modal" data-bs-target="#modal-form"><i class="bi bi-plus me-2"></i>Add Signatory</button>
                                    </div>
                                </div>
                            </header>
                            <!-- HEADER END -->
                            <div class="container mt-1">
                                <div class="card w-100 h-auto bg-light">
                                    <table id="table" class="table-striped table-sm">
                                        <thead class='thead-dark'>
                                            <div class="d-flex justify-content-between mb-3">
                                                <!-- SEARCH BAR -->
                                                <span class="flex-grow-1 me-3">
                                                    <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
                                                </span>
                                                <div>
                                                    <button class="btn btn-sm btn-outline-danger table-opt"><i class="bi bi-trash me-2"></i>Delete</button>
                                                </div>
                                            </div>
                                            <tr>
                                                <th data-checkbox="true"></th>
                                                <th scope='col' data-width="100" data-halign="center" data-align="left" data-field="sign_id">Sign ID</th>
                                                <th scope='col' data-width="100" data-halign="center" data-align="left" data-sortable="true" data-field="id">ID</th>
                                                <th scope='col' data-width="400" data-align="center" data-sortable="true" data-field="name">Name</th>
                                                <th scope='col' data-width="300" data-halign="center" data-align="left" data-sortable="true" data-field="position">Position</th>
                                                <th scope='col' data-width="100" data-align="center" data-field="action">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
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
    <div class="modal fade" id="modal-form" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0"></h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="signatory-form" method="POST">
                        <input type="hidden" name="action" value="">
                        <p class="text-secondary"><small>Please complete the following</small></p>
                        <div class="form-group needs-validation" novalidate>
                            <label for="id-no">ID No</label>
                            <select name="signatory" class="select2 px-0 form-select form-select-sm" id="id-no-select" required>
                                <option>Search user</option>
                                <optgroup value="administrator" label="Administrators" class="select2-result-selectable">
                                    <?php
                                    foreach ($administrators as $element) {
                                        echo "<option value='{$element->admin_id}'>{$element->name}</option>";
                                    }
                                    echo "<optgroup value='faculty' label='Faculty' class='select2-result-selectable'>";
                                    foreach ($faculty as $element) {
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
                    <button class="close btn btn-dark btn-sm close-btn" data-bs-dismiss="modal">Close</button>
                    <input type="submit" name="delete" form="signatory-form" class="btn btn-danger btn-sm" value="Delete">
                    <button type="submit" id="submit-again" class="btn btn-secondary btn-sm">Submit and add again</button>
                    <input type="submit" name="submit" form="signatory-form" class="btn btn-primary btn-sm" value="Add">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-view" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">View Signatory Details</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column">
                        <div class="container mb-3">
                            <label for="id-no-view">ID No</label>
                            <input id="id-no-view" type="text" class="form-control form-control-sm mb-0" readonly>
                        </div>
                        <div class="container mb-3">
                            <label for="name-view">Name</label>
                            <input id="name-view" class="form-control form-control-sm mb-0" type="text" value="" readonly>
                        </div>
                        <div class="container mb-3">
                            <label for="position-view">Role | Position</label>
                            <input id="position-view" class="form-control form-control-sm mb-0" type="text" name="position">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-dark btn-sm close-btn" data-bs-dismiss="modal">Close</button>
                    <button data-bs-toggle="modal" data-bs-target="#modal-form" class="edit-btn btn btn-primary btn-sm">Edit</button>
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
            showSpinner();
            try {
                $("#modal-view").modal("hide")
            } catch (e) {}
            let modal = $("#modal-form");

            let action = "Add";
            $("[name='delete']").addClass('d-none');
            let displaySubmitAgainBtn = true;
            if (data !== null) {
                let info = signatoryTable.bootstrapTable('getRowByUniqueId', data);
                $("#id-no-select").select2('val', info.id);
                $("#position").val(info.position);
                $("[name='delete']").removeClass('d-none');
                displaySubmitAgainBtn = false;
                action = 'Save';
            }

            $("#submit-again").toggle(displaySubmitAgainBtn);
            $("#signatory-form").find("input[name='action']").val(action.toLowerCase() + "Signatory");
            modal.find(".modal-title h4").html(`${action} Signatory`);
            modal.find(".modal-footer [name='submit']").val(action);
            modal.modal('show');
            hideSpinner();
        }

        const tableSetup = {
            url: `getAction.php?data=signatory`,
            method: 'GET',
            uniqueId: 'id',
            idField: 'id',
            search: true,
            searchSelector: "#search-input",
            height: 425,
            maintainMetaDat: true, // set true to preserve the selected row even when the current table is empty
            clickToSelect: true,
            pageSize: 10,
            pagination: true,
            buttonsToolbar: ".buttons-toolbar",
            pageList: "[10, 25, 50, All]",
            paginationParts: ["pageInfoShort", "pageSize", "pageList"]
        };
        let signatoryTable = $("#table").bootstrapTable(tableSetup);
        let addAnother = false;
        $(function() {
            preload("#signatory");

            $("#id-no-select").select2({
                theme: "bootstrap-5",
                width: null,
                dropdownParent: $('.modal')
            });

            $(document).on("click", "#submit-again", function() {
                addAnother = true;
                $("#signatory-form").submit();
            });

            $(document).on("submit", "#signatory-form", function(e) {
                e.preventDefault();
                let form = $(this);
                $.post("action.php", form.serializeArray(), function() {
                    form.trigger('reset');
                    signatoryTable.bootstrapTable("refresh")
                    if (!addAnother) {
                        $("#modal-form").modal("hide");
                        addAnother = false;
                    }
                    showToast("success", "Signatory successfully added");
                });
            });
            $(document).on("click", ".table-opt", function(e) {
                let selections = signatoryTable.bootstrapTable('getSelections');
                if (selections.length < 1) return showToast("danger", "Please select a signatory first");
            });
            $(document).on("click", ".view-btn", function() {
                showSpinner();
                let id = $(this).attr("data-id");
                let data = signatoryTable.bootstrapTable("getRowByUniqueId", id)

                let modal = $("#modal-view");

                modal.find("#id-no-view").val(data.id);
                modal.find("#name-view").val(data.name);
                modal.find("#position-view").val(data.position);
                modal.find(".modal-footer .edit-btn").attr("onclick", `renderData('${id}')`);
                hideSpinner();
            });
            hideSpinner();
        })
    </script>


</body>

</html>