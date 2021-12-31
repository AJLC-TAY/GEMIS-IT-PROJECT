import { commonTableSetup } from "./utilities.js";

let tableSetup, table, assignMultiple, selections;
tableSetup = {
    ...commonTableSetup,
    pageSize: "25",
    pageList: "[25, 50, 75, All]",
    url: 'getAction.php?data=all-sub-classes',
    idField: 'sub_class_code',
    uniqueId: 'sub_class_code',
    height: '800',
    method: 'GET',
    search: true,
    searchSelector: '#search-input',
};

table = $("#table").bootstrapTable(tableSetup);
assignMultiple = false;
selections = [];

/**
 * Dynamically renders the faculty option DOM elements.
 * If current ID is provided, exclude it from the selection.
 * @param {String} currentID Faculty ID of the current subject teacher.
 */
function reloadOptions(currentID = null) {
    let options;
    if (currentID != null) {
        options = facultyOptions.map(e => {
            return parseInt(e.teacher_id) !== parseInt(currentID) ? `<option value="${e.teacher_id}">${e.name}</option>` : '';
        });
    } else {
        options = facultyOptions.map(e => {
            return `<option value="${e.teacher_id}">${e.name}</option>`;
        });
    }
    options = "<option value='*'>-- Select faculty here --</option>" + options.join('');

    $("#faculty-select").html(options).select2({
        theme: "bootstrap-5",
        width: null,
        dropdownParent: $('#assign-modal')
    });
}

$(function() {
    preload("#enrollment", "#sub-classes");
    try {
        reloadOptions();
    } catch(e) {}

    /** Event listener when multiple assign button is clicked */
    $(document).on("click", '#multiple-assign-opt', function() {
        selections = table.bootstrapTable('getSelections');
        if (selections.length === 0) {
            return showToast('danger', "Please select a subject class first");
        }
        // assign multiple is set to true
        assignMultiple = true;
        // add instructions and show the modal
        let modal = $("#assign-modal");
        modal.attr("data-action", "multi-assign")
        modal.find("#instruction").html(`Assign ${selections.length} subject class/es to:`);
        modal.modal("show");
    });

    /** Event listener when action buttons are clicked */
    $(document).on("click", ".action", function() {
        showSpinner();
        let modal, sub_class_code, ins;

        assignMultiple = false;
        modal = $("#assign-modal");
        sub_class_code = $(this).attr("data-sub-class-code");
        selections.push(sub_class_code);

        switch ($(this).attr("data-type")) {
            case 'assign':
                ins = `Assign subject class code, ${sub_class_code} to: `;
                break;
            case 'change':
                ins = `<small>Current subject teacher: ${$(this).attr("data-current")}</small>
                    <br><span class = 'text-dark'>Re-assign subject class code, ${sub_class_code} to:</span>`;
                let currentID = $(this).attr("data-current-id");
                reloadOptions(currentID);
                break;
            case 'unassign':
                let formData = new FormData();
                formData.append("sub_class_code[]", sub_class_code);
                formData.append("target", "SCFaculty");
                formData.append("action", "unassignSubClasses");
                $.ajax({
                    url: "action.php",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: (data) => {
                        showToast("success", "Successfully unassigned the faculty");
                        table.bootstrapTable("refresh", JSON.parse(data));
                        hideSpinner();
                    }
                });
                return;
        }
        $("#instruction").html(ins);
        modal.modal('show');
        hideSpinner();
    });

    /** Resets the multi-assign modal */
    $(document).on("hidden.bs.modal", "#assign-modal", function() {
        $("#faculty-select").val('*').trigger('change');
        $("#instruction").html("");
        reloadOptions();
    });

    /** Event listener when form is submitted */
    $(document).on("submit", '#sub-class-multi-form', function(e) {
        e.preventDefault();
        showSpinner();
        let formData = new FormData($(this)[0]);
        formData.append("target", 'SCFaculty');

        if (assignMultiple) {
            selections.forEach(e => {
                formData.append('sub_class_code[]', e.sub_class_code);
            });
        } else {
            formData.append("sub_class_code[]", selections[0]);
        }

        $.ajax({
            url: "action.php",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: data => {
                selections = [];
                table.bootstrapTable('refresh');
                $("#assign-modal").modal("hide");
                hideSpinner();
                showToast('success', "Subject class successfully updated")
            }
        });
    });
    hideSpinner();
});