import {averageSubjectGradesEvent, commonTableSetup} from "./utilities.js";
let tableSetup = {
    ...commonTableSetup,
    search: true,
    searchSelector: "#search-input",
    uniqueId: "month",
    fieldId: "month",
    // height:         800
};
let table = $("#table").bootstrapTable(tableSetup);

let prepareSectionHTML = section => {
    let html = '';
    section.forEach(element => {
        var code = element.code;
        html += `<li class='list-group-item d-flex justify-content-between align-items-center'> ${code}
                <button class='transfer-option btn' id='${code}'>Transfer</button></li>`;
    });
    return html;
};
var message = 'Are you sure you want to transfer the student?';
var stud_id;
let row = '';
var gradesTemp = [];
let formData = [];
let tempChanges = [];



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
    hideSpinner();
}

$(function() {
    preload('#student');

    const readURL = (input, destination) => {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(destination).attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        };
    };

    $("#upload").change(function(){
        readURL(this, "#resultImg");
    });

    $(".profile-photo").click(()=> $("#upload").click());

    const readpsaURL = input => {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#psaResult').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        };
    };

    $("#psaUpload").change(function(){
        readURL(this, "#psaResult");
    });

    $("#form137Upload").change(function(){
        readURL(this, "#form137Result");
    });

    $(".psa-photo").click(()=> $("#psaUpload").click());
    $(".form137-photo").click(()=> $("#form137Upload").click());

    $("#psa").click(function(){
        let preview = $('#imgPreview');
        img = document.getElementById("psaPreview");
        img.src = this.src;
        //  this.src;
        preview.find('.modal-title').text(this.alt);
        preview.modal('toggle');
    });

    $("#form137").click(function(){
        let preview = $('#imgPreview');
        img = document.getElementById("psaPreview");
        img.src = this.src;
        //  this.src;
        preview.find('.modal-title').text(this.alt);
        preview.modal('toggle');
    });

    // $(document).on("click", "#reset-btn", function() {
    //     let modal = $("#confirmation-modal");
    //     modal.find(".message").html("Reset password of this student?");
    //     $("#reset-form").html(`<input type="hidden" name="id[]" value="${$(this).attr("data-id")}">`);
    //     modal.modal("show");
    // });

    $(document).on("submit", "#reset-form", function(e) {
        e.preventDefault();
        $.post("action.php", $(this).serializeArray(), function () {
            $("#reset-confirmation-modal").modal("hide");
            $(".modal-backdrop").remove();
            showToast('success', "Password successfully put to default");
        });
    });


    $(document).on("click", ".grade-table .action", function () {
        showSpinner();
        let gradeID = $(this).attr("data-grade-id");
        let row = $(this).closest("tr");
        let inputs = row.find("input");
        let inputOne = inputs.eq(0);
        let inputTwo = inputs.eq(1);
        let inputFin = inputs.eq(2);


        switch($(this).attr("data-action")) {
            case "edit":
                inputs.prop("disabled", false);
                gradesTemp.push({'gid' : gradeID, 'data' : [inputOne.val(), inputTwo.val(), inputFin.val()]});
                $(this).addClass('d-none');
                $(this).siblings(".edit-options").removeClass("d-none");
                hideSpinner();
                return;
            case "save":
                inputs.each(function() {
                    formData.push({'name': $(this).attr('name'), 'value': $(this).val()});
                });
                formData.push({'name': 'action', 'value': 'editSubjectGrade'});
                document.getElementById("teacher").innerText = "subject teachers";
                document.getElementById("type").innerText = "grades";
                document.getElementsByClassName('submit-edit-button')[0].setAttribute('data-type','grades');
                $("#confirmation-edit-modal").modal('show');
                hideSpinner();
                return;
            case "cancel":
                let gradeData;
                gradesTemp = gradesTemp.filter(e => {
                    if (e.gid == gradeID) {
                        gradeData = e;
                    }
                    return e.gid != gradeID;
                });

                inputOne.val(gradeData.data[0]);
                inputTwo.val(gradeData.data[1]);
                inputFin.val(gradeData.data[2]);

                break;
        }
        let editOptions = $(this).closest('.edit-options');
        editOptions.addClass('d-none');
        editOptions.siblings("button").removeClass("d-none");
        inputs.prop("disabled", true);
        hideSpinner();
    });


    $(document).on("click", ".submit-edit-button", function() {
        switch($(this).attr('data-type')) {
            case 'grades':
                $.post("action.php", formData, function(data) {
                    formData = [];
                    let gradeID = JSON.parse(data);
                    $(`[data-action='edit'][data-grade-id='${gradeID}']`).removeClass("d-none");
                    $(`[data-action='edit'][data-grade-id='${gradeID}']`).siblings("").addClass("d-none");
                    $(`input[name*="grade[${gradeID}]"]`).prop("disabled", true);
                    showToast('success', 'Subject grade successfully edited')
                });
              break;
            case 'attendance':
                saveRow(row);
              break;
          }
        
    });

    $(document).on("click", ".action", function(e) {
        e.preventDefault();
        showSpinner();
        row = $(this).closest("tr");
        let action = $(this).attr("data-type");
        switch (action) {
            case "edit":
                // hide specific btn
                $(this).toggle(false);
                // disable month selector
                // toggleDisableMonthSelector(true);
                editRow(row);
                // show specific edit options
                row.find('.edit-spec-options').toggle(true);
                hideSpinner();
                return;
            case "save":
                document.getElementById("teacher").innerText = "advisers";
                document.getElementById("type").innerText = "attendance";
                document.getElementsByClassName('submit-edit-button')[0].setAttribute('data-type','attendance');
                $("#confirmation-edit-modal").modal('show');
                break;
            case "cancel":
                cancelEditRow(row);
                break;
        }
        exitEditMode(row);
    });
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
    formData.append('stat', "1");

    for (var pair of formData.entries()) {
        console.log(pair[0] + " - " + pair[1]);
      }
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
    /** Call automatic average */
    averageSubjectGradesEvent();
    hideSpinner();
});