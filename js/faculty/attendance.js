import { commonTableSetup } from "../admin/utilities.js";

let tableSetup = {
    ...commonTableSetup,
    search: true,
    searchSelector: "#search-input",
    uniqueId: "stud_id",
    fieldId: "stud_id",
    // height:         800
};
let table = $("#table").bootstrapTable(tableSetup);
let tempChanges = [];

function toggleDisableMonthSelector(bool) {
    $("select[name='month']").prop("disabled", bool);
}

/**
 * Submits new attendance value and make row inputs to readonly.
 * @param {Object} row tr object
 * */
function saveRow(row) {
    let formData = new FormData();
    $.each(row.find(".number"), function(i, val) {
        formData.append(val.getAttribute('name'), val.value);
        val.setAttribute("readonly", true);
    });
    formData.append('action', "changeAttendance");
    // formData.append('month', $("[name='month']").val());
    $.ajax({
        url: "action.php",
        method: "POST",
        processData: false,
        contentType: false,
        data: formData,
        success: data => {}
    });
    showToast("success", "Successfully saved");
}

/**
 * Returns the original values temporarily stored in the row inputs and make them not editable
 * @param {Object} row tr object
 * */
function cancelEditRow(row) {
    // return original values to input & set them to readonly
    $.each(row.find(".number"), function(i, val) {
        val.value = tempChanges[i];
        val.setAttribute("readonly", true);
    });
}
/**
 * Make row inputs editable and store their values temporarily.
 * @param {Object} row tr object
 * */
function editRow(row) {
    row.find(".number").each(function() {
        let e = $(this);
        tempChanges.push(e.val());
        e.removeAttr("readonly");
    });
}
/**
 * Hides the specific edit options and shows edit button of the given row.
 * It also empties the temp change array and enables the month selector.
 * @param {Object} row tr object
 * */
function exitEditMode(row) {
    // hide specific edit options
    row.find(".edit-spec-options").toggle(false);
    // show specific edit btn
    row.find(".action[data-type='edit']").toggle(true);
    // empty the temporary array
    tempChanges = [];
    // enable month selector
    toggleDisableMonthSelector(false);
    hideSpinner();
}

$(function() {
    preload("#attendance");

    $(document).on("click", ".edit-btn", function(e) {
        e.preventDefault();
        showSpinner();
        // hide main edit button & show main edit options
        $(this).toggle(false);
        $(".edit-options").toggle(true);
        // make all inputs editable
        $(".number").removeAttr("readonly");
        // disabled month selector
        toggleDisableMonthSelector(true);
        // disabled specified edit buttons and options
        $('.edit-spec-btn').toggle(true).prop("disabled", true);
        $('.edit-spec-options').toggle(false);
        hideSpinner();
    });

    $(document).on("submit", "#attendance-form", function(e) {
        e.preventDefault();
        showSpinner();
        let formData = new FormData($(this)[0]);
        $.ajax({
            url: "action.php",
            method: "POST",
            contentType: false,
            processData: false,
            data: formData,
            success: () => {
                // show main edit button & hide main edit options
                $(".edit-btn").toggle(true);
                $(".edit-options").toggle(false);
                // Make specific edit buttons editable & inputs to readonly
                $(".edit-spec-btn").prop("disabled", false);
                $(".number").prop("readonly", true);
                // empty temporary changes
                tempChanges = [];
                // enable month selector
                toggleDisableMonthSelector(false);

                hideSpinner();
                showToast("success", "Successfully saved");
            }
        });
    });

    $(document).on("click", ".action", function(e) {
        e.preventDefault();
        showSpinner();
        let row = $(this).closest("tr");
        let action = $(this).attr("data-type");
        switch (action) {
            case "edit":
                // hide specific btn
                $(this).toggle(false);
                // disable month selector
                toggleDisableMonthSelector(true);
                editRow(row);
                // show specific edit options
                row.find('.edit-spec-options').toggle(true);
                hideSpinner();
                return;
            case "save":
                saveRow(row);
                break;
            case "cancel":
                cancelEditRow(row);
                break;
        }
        exitEditMode(row);
    });

    // $(document).on("click", ".edit-spec-btn", function (e) {
    //     e.preventDefault();
    //     // hide specific btn
    //     $(this).toggle(false);
    //     // disable month selector
    //     toggleDisableMonthSelector(true);
    //
    //     // make row inputs editable and store their values temporarily
    //     let row = $(this).closest("tr");
    //     row.find(".number").each(function() {
    //         let e = $(this);
    //         tempChanges.push(e.val());
    //         e.removeAttr("readonly");
    //     });
    //     // show specific edit options
    //     row.find('.edit-spec-options').toggle(true);
    // });

    // $(document).on("click", ".cancel-spec-btn", function (e) {
    //     e.preventDefault();
    //     // hide specific edit options
    //     $(this).closest(".edit-spec-options").toggle(false);
    //
    //     // return original values to input & set them to readonly
    //     let row = $(this).closest("tr");
    //     $.each(row.find(".number"), function(i, val) {
    //         val.value = tempChanges[i];
    //         val.setAttribute("readonly", true);
    //     });
    //     // show specific edit btn
    //     row.find('.edit-spec-btn').toggle(true);
    //     // empty the temporary array
    //     tempChanges = [];
    //     // enable month selector
    //     toggleDisableMonthSelector(false);
    // });

    // $(document).on("click", ".save-spec-btn", function (e) {
    //     e.preventDefault();
    //     $(this).closest(".edit-spec-options").toggle(false);
    //     let row = $(this).closest("tr");
    //     let formData = new FormData();
    //     $.each(row.find(".number"), function(i, val) {
    //         formData.append(val.getAttribute('name'), val.value);
    //         val.setAttribute("readonly", true);
    //     });
    //     formData.append('action', "changeAttendance");
    //     formData.append('month', $("[name='month'] option:selected").text());
    //     $.ajax({
    //         url: "action.php",
    //         method: "POST",
    //         processData: false,
    //         contentType: false,
    //         data: formData,
    //         success: data => {
    //             tempChanges = [];
    //             toggleDisableMonthSelector(false);
    //         }
    //     })
    //     row.find('.edit-spec-btn').toggle(true);
    // });

    /** Event handler if month selector is changed */

    $(document).on("change", "select[name='month']", function(e) {
        e.preventDefault();
        table.bootstrapTable("refresh", { url: `getAction.php?data=class_attendance&class=${currentClass}&month=${$("select[name='month']").val()}` })
    });
    hideSpinner();
});