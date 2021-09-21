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
$(function() {
    preload("#signatory");

    $(document).on("click", ".show-modal", function () {
        showSpinner();
        try {
            $("#modal-view").modal("hide")
        } catch (e) {}
        let modal = $("#modal-form");

        let action = $(this).attr("data-action");
        $("input[name='delete']").addClass('d-none');
        let displaySubmitAgainBtn = true;
        if (action == 'Edit') {
            let info = signatoryTable.bootstrapTable('getRowByUniqueId', $(this).attr('data-id'));
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

    $(document).on("submit", "#signatory-form", function(e) {
        e.preventDefault();
        let form = $(this);
        console.log(form.serializeArray());
        $.post("action.php", form.serializeArray(), function() {
            form.trigger('reset');
            signatoryTable.bootstrapTable("refresh")
            if (!addAnother) {
                $("#modal-form").modal("hide");
                addAnother = false;
            };
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
        let data = signatoryTable.bootstrapTable("getRowByUniqueId", id);

        let modal = $("#modal-view");

        modal.find("#id-no-view").val(data.sign_id);
        modal.find("#name-view").val(data.name);
        modal.find("#position-view").val(data.position);
        modal.find("#years-view").val(data.years);
        modal.find(".modal-footer .edit-btn").attr("data-id", id);
        hideSpinner();
    });
    hideSpinner();
});