import {commonTableSetup} from "./utilities.js";

const tableSetup = {
    url: `getAction.php?data=signatory`,
    method: 'GET',
    uniqueId: 'sign_id',
    idField: 'sign_id',
    search: true,
    searchSelector: "#search-input",
    height: 425,
    buttonsToolbar: ".buttons-toolbar",
    ...commonTableSetup
};
let signatoryTable = $("#table").bootstrapTable(tableSetup);
let addAnother = false;
const deleteEvent = (id = null) => {
    var action = 'deleteSignatory';
    let selected = signatoryTable.bootstrapTable('getSelections');
    if (selected.length > 0 && id == null) {
        selected.forEach(element => {
            var id = element.sign_id;
            $.post("action.php", {id,action}, function(data) {
                signatoryTable.bootstrapTable('refresh');
                $("#confirmation-modal").modal("hide");
            });
        });
    } else {
        $.post("action.php", {id,action}, function(data) {
            signatoryTable.bootstrapTable('refresh');
            $("#confirmation-modal").modal("hide");
        });
        $("#delete-signatory-confirm").removeAttr("data-id");
    }
}

$(function() {
    preload("#signatory");

    $(document).on("click", ".show-modal", function () {
        showSpinner();
        try {
            $("#modal-view").modal("hide")
        } catch (e) {}
        let modal = $("#modal-form");

        let action = $(this).attr("data-action");
        let signID = $(this).attr('data-id');
        let displaySubmitAgainBtn = true;
        if (action === 'Delete') {
            let confirmModal = $("#confirmation-modal");
            confirmModal.find("#delete-signatory-confirm").attr("data-id", signID);
            confirmModal.modal("show");
            hideSpinner();
            return;
        }
        if (action == 'Edit') {
            let info = signatoryTable.bootstrapTable('getRowByUniqueId', signID);
            $("#sig-id").val(info.sign_id);
            $("#last-name").val(info.last_name);
            $("#first-name").val(info.first_name);
            $("#middle-name").val(info.middle_name);
            $("#academic-degree").val(info.acad_degree);
            $("#start-year").val(info.start_year);
            $("#end-year").val(info.end_year);
            $("#position").val(info.position);
            $("[name='delete']").removeClass('d-none');
            displaySubmitAgainBtn = false;
        }

        $("#submit-again").toggle(displaySubmitAgainBtn);
        $("#signatory-form").find("input[name='action']").val(action.toLowerCase() + "Signatory");
        modal.find(".modal-title h4").html(`${action} Signatory`);
        modal.find(".modal-footer [name='submit']").val(action != "Add" ? "Save" : action);
        modal.modal('show');
        hideSpinner();
    });

    /** Reset input fields when modal form is closed */
    $(document).on("hidden.bs.modal", "#modal-form", function() {
        $(this).find('form').trigger('reset');
    });

    $("#id-no-select").select2({
        theme: "bootstrap-5",
        width: null,
        dropdownParent: $('#modal-form')
    });

    $(document).on("click", "#submit-again", function() {
        addAnother = true;
        $("#signatory-form").submit();
    });

    $("#signatory-form").validate({
        rules: {
            "last-name": {required: true},
            "first-name": {required: true},
            position: {required: true}
        },
        messages: {
            "last-name": {required: REQUIRED},
            "first-name": {required: REQUIRED},
            position: {required: REQUIRED}
        },
        submitHandler: function(form) {
            $.ajax({
                url: "action.php",
                data: new FormData(form),
                type: "post",
                processData: false,
                contentType: false,
                success: function() {
                    $(form).trigger('reset');
                    signatoryTable.bootstrapTable("refresh")
                    if (!addAnother) {
                        console.log("test");
                        $("#modal-form").modal("hide");
                        addAnother = false;
                    }
                }
                // showToast("success", "Signatory successfully added");
            });
        }
    });

    $(document).on("click", ".table-opt", function(e) {
        let selections = signatoryTable.bootstrapTable('getSelections');
        if (selections.length < 1) return showToast("danger", "Please select a signatory first");
        $("#confirmation-modal").modal("show");
    });

    $(document).on("click", ".view-btn", function() {
        showSpinner();
        let id = $(this).attr("data-id");
        let data = signatoryTable.bootstrapTable("getRowByUniqueId", id);
        let modal = $("#modal-view");

        modal.find("#id-no-view").val(data.sign_id);
        modal.find("#last-name-view").val(data.last_name);
        modal.find("#first-name-view").val(data.first_name);
        modal.find("#middle-name-view").val(data.middle_name);
        modal.find("#position-view").val(data.position);
        modal.find("#start-year-view").val(data.start_year);
        modal.find("#end-year-view").val(data.end_year);
        modal.find("#academic-degree-view").val(data.acad_degree);
        modal.find(".modal-footer .edit-btn").attr("data-id", id);
        modal.modal("show");
        hideSpinner();
    });

    $('#delete-signatory-confirm').click(function(e) {
        e.preventDefault();
        deleteEvent($(this).attr("data-id") || null);
    });

    hideSpinner();
});