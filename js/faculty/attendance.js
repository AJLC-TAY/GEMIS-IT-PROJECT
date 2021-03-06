import { commonTableSetup } from "../admin/utilities.js";

let tableSetup = {
    ...commonTableSetup,
    search: true,
    searchSelector: "#search-input",
    uniqueId: "stud_id",
    fieldId: "stud_id",
    ...commonTableSetup,
    pagination: false
    // height:         800
};
let table = $("#table").bootstrapTable(tableSetup);
let tempChanges = [];
var submitMsg = "Submitted attendance records are final and are not editable. For necessary changes, contact the admin.";
var saveMsg = "Saved attendance records are editable within the duration of the current quarter.";
var stat = document.getElementById("label").innerText == "submit?" ? "1" : "0";
var msg = document.getElementById("label").innerText;
var row = '';
function toggleDisableMonthSelector(bool) {
    $("select[name='month']").prop("disabled", bool);
}

/**
 * Submits new attendance value and make row inputs to readonly.
 * @param {Object} row tr object
 * */
function saveRow(row) {
    let formData = new FormData();
    $.each(row.find(".number"), function (i, val) {
        formData.append(val.getAttribute('name'), val.value);
        val.setAttribute("readonly", true);
    });
    formData.append('action', "changeAttendance");
    formData.append('stat', stat);
    // formData.append('month', $("[name='month']").val());
    $.ajax({
        url: "action.php",
        method: "POST",
        processData: false,
        contentType: false,
        data: formData,
        success: data => { }
    });
    $(".attendancerect-confirmation").modal("hide");
    table.bootstrapTable("refresh");
    showToast("success", "Successful!");
}

function saveAll(){
        showSpinner();
        let formData = new FormData($("#attendance-form")[0]);
        formData.append('stat', stat);            
        console.log(...formData);
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
                $(".attendancerect-confirmation").modal("hide");
                table.bootstrapTable("refresh");
                hideSpinner();
                showToast("success", "Successfully saved");
            }
        });
}

/**
 * Returns the original values temporarily stored in the row inputs and make them not editable
 * @param {Object} row tr object
 * */
function cancelEditRow(row) {
    // return original values to input & set them to readonly
    $.each(row.find(".number"), function (i, val) {
        val.value = tempChanges[i];
        val.setAttribute("readonly", true);
    });
}
/**
 * Make row inputs editable and store their values temporarily.
 * @param {Object} row tr object
 * */
function editRow(row) {
    row.find(".number").each(function () {
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

function saveConfirmation() {
    console.log("from faculty/attendance.js save clicked");
        if (typeof user !== 'undefined') {
            document.getElementById("label").innerText = "CONFIRMATION";
            stat = "0";
            document.getElementById("modal-msg").innerText = "Are you sure you want to save?";
        } else {
            document.getElementById("stmt").innerText = "Are you sure you want to ";
            document.getElementById("label").innerText = "save?";
            document.getElementById("modal-msg").innerText = saveMsg;
            stat = "0";
        }

        document.getElementById("confirm").innerText = "Save";
        $(".attendancerect-confirmation").modal("toggle");
}

function submitConfirmation() {
    stat = "1";
    $("#stmt").text("Are you sure you want to ");
    $("#label").text("submit?");
    $("#modal-msg").text(submitMsg);
    $("#confirm").text("Submit");
    $(".attendancerect-confirmation").modal("toggle");
}

$(function () {
    preload("#attendance");

    $(document).on("click", ".edit-btn", function (e) {
        e.preventDefault();
        showSpinner();
        // hide main edit button & show main edit options
        $(this).toggle(false);
        $("#rectification-type").text("multiple");
        $(".edit-options").toggle(true);

        // make all inputs editable
        $(".final").removeAttr("readonly");

        // disabled month selector
        toggleDisableMonthSelector(true);
        // disabled specified edit buttons and options
        $('.edit-spec-btn').toggle(true).prop("disabled", true);
        $('.edit-spec-options').toggle(false);
        hideSpinner();
    });

    $(document).on("submit", "#attendance-form", function (e) {
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

    $(document).on("click", ".submit-btn", () => {
        submitConfirmation(row);
    });

    $(document).on("click", ".save-btn", () => {
        saveConfirmation(row);
    });

    $(document).on("click", "#confirm", () => {
        if(document.getElementById('rectification-type').innerText == 'multiple'){
            saveAll();
        } else{
            saveRow(row);
        }
    });

    $(document).on("click", ".action", function (e) {
        e.preventDefault();
        showSpinner();
        row = $(this).closest("tr");
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
                saveConfirmation(row);
                // saveRow(row);
                break;
            case "submit":
                submitConfirmation(row);
                break;
            case "cancel":
                cancelEditRow(row);
                break;
        }
        exitEditMode(row);
    });

    /** Event handler if month selector is changed */

    $(document).on("change", "select[name='month']", function (e) {
        e.preventDefault();
        table.bootstrapTable("refresh", { url: `getAction.php?data=class_attendance&class=${currentClass}&month=${$("select[name='month']").val()}` })
    });
    hideSpinner();
});