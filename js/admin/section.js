import { commonTableSetup } from "./utilities.js";

let tableSetup, tableFormSetup, url, id, table, tableForm;
tableSetup = {
    method: 'GET',
    uniqueId: 'code',
    idField: 'code',
    search: true,
    searchSelector: "#search-input",
    ...commonTableSetup
};

tableFormSetup = {
    url: "getAction.php?data=enrolled",
    uniqueId: 'id',
    idField: 'id',
    search: true,
    searchSelector: "#search-input",
    method: 'GET',
    ...commonTableSetup,
    pageSize: 50,
    pageList: "[50, 100, All]",
    height: 800
}

url = "getAction.php?";
id = '';

if (isViewPage) {
    url += `data=student&section=${sectionCode}`;
    id = 'lrn';
} else {
    url += 'data=section';
    id = 'code';
}
tableSetup.url = url;
tableSetup.idField = id;
tableSetup.uniqueId = id;
tableSetup.height = 425;
table = $("#table").bootstrapTable(tableSetup);

try {
    tableForm = $("#section-enrollees-table").bootstrapTable(tableFormSetup);
} catch (e) {}

let addAnother = false;


function refreshCount() {
    $("#table").bootstrapTable("refresh");
    $("#no-of-stud").html( $("#table").bootstrapTable("getData").length);
}
$(function() {
    preload('#enrollment', '#section');
    $("#adviser").select2({
        theme: "bootstrap-5",
        width: null,
        // dropdownParent: $('#add-modal')
    });
    // $("#adviser").select2({
    //     theme: "bootstrap-5",
    //     width: null,
    //     dropdownParent: $('#add-modal')
    // });

    $("#adviser-section").select2({
        theme: "bootstrap-5",
        width: null
    });

    $(document).on("click", '#add-btn', function() {
        $("#add-modal").modal("show");
    });

    $(document).on("click", ".submit-another", function(e) {
        e.preventDefault();
        addAnother = true;
        $("#section-form").submit();
    });
    $(document).on("submit", '#section-form', function(e) {
        e.preventDefault();
        let form = $(this);
        $.post("action.php", form.serializeArray(), function(data) {
            // console.log(JSON.parse(data))
            form.trigger("reset");
            $("#table").bootstrapTable('refresh');
            if (!addAnother) {
                $("#add-modal").modal("hide");
                addAnother = false;
            }
            showToast("success", "Section successfully added");
        });
    });

    $(document).on("click", '#edit-btn', function(e) {
        e.preventDefault();
        $("#empty-msg").addClass("d-none");
        $(".edit-opt").removeClass("d-none");
        $(this).addClass('d-none');
        $("#section-edit-form").find('input').each(function() {
            let input = $(this);
            // tempData.push(input.val())
            input.removeClass('d-none');
            input.prop("disabled", false);
            $("a.link").addClass("d-none");
        });
    });

    $(document).on("click", '#cancel-btn', function(e) {
        e.preventDefault();
        $(".edit-opt").addClass("d-none");
        $('#edit-btn').toggleClass('d-none');

        if (!adviser) $("#empty-msg").removeClass("d-none"); // show empty message if no assigned adviser originally

        let inputs = $("#section-edit-form").find('input');
        let maxInput = inputs.eq(0);
        maxInput.val(tempData[0]);
        maxInput.prop("disabled", true);

        let teacherInput = inputs.eq(1);
        teacherInput.val(tempData[1]);
        // teacherInput.addClass("d-none")

        $("a.link").removeClass("d-none");
    });

    $(document).on("submit", "#section-edit-form", function(e) {
        e.preventDefault();
        showSpinner();
        let form = $(this);
        let formData = form.serializeArray();
        console.log(formData);
        $.post("action.php", formData, function(data) {
            let teacherID, inputs, teacherInput, teacherLink;
            data = JSON.parse(data);

            // inputs = form.find("input");
            // inputs.eq(0).prop("disabled", true);
            //
            // teacherID = formData[1].value;
            // teacherLink = $("a.link");
            // if (teacherID.trim().length === 0) {
            //     // $("#empty-msg").removeClass("d-none")
            //     // teacherLink = $("a.link")
            //     // teacherLink.attr("href", "")
            //     // teacherLink.html("")
            //     // teacherLink.addClass("d-none")
            // } else {
            //     teacherInput = inputs.eq(1);
            //     teacherInput.val(teacherID);
            //
            //     teacherLink.attr("href", `faculty.php?id=${teacherID}`);
            //     let name = $(`#adviser-list option[value*='${teacherID}']`).html();
            //     name = "Teacher " + name.substring(name.indexOf("-") + 2);
            //     teacherLink.html(name);
            //     teacherLink.removeClass("d-none");
            //
            // }
            location.replace(`section.php?sec_code=${data.section}`);


            // $("#edit-btn").toggleClass('d-none')
            // $(".edit-opt").addClass('d-none')

            // tempData = []
            // hideSpinner()
            // showToast("success", "Successfully updated section")
        });
    });

    /** Clears the teacher input if clear button is clicked */
    $(document).on("click", "#adviser-clear-btn", function(e) {
        e.preventDefault();
        $("#adviser-section").val("");
        $("#adviser-section").trigger("change");
    });

    $(document).on("click", "#transfer-btn", function() {

    });

    /** Specific subject */
    $(document).on("click", "#add-subject-btn", function() {
        let subSetUp = {...tableSetup };
        subSetUp.url = "getAction.php?data=subjects";
        subSetUp.idField = 'sub_code';
        subSetUp.uniqueId = 'sub_code';
        subSetUp.height = 300;
        subSetUp.search = true;
        subSetUp.searchSelector = "#search-sub-input";
        $("#subject-table").bootstrapTable(subSetUp);

    });

    // clear button for search subject input in the as-modal
    $(document).on("click", ".clear-table-btn", () => {
        showSpinner();
        $("#subject-table").bootstrapTable("resetSearch");
        hideSpinner();
    });


    /** Section Form */
    $(document).on("change", ".filter-form", function() {
        showSpinner();
        let value = $(this).val();
        let otherFilter, filters;
        let tb = $("#section-enrollees-table");
        console.log(value);
        switch ($(this).attr("name")) {
            case 'program':
                otherFilter = $("[name='grade-level']").val();
                filters = (value == '*') ? { "grade": otherFilter } : { "strand": value, "grade": otherFilter };
                console.log(otherFilter);
                break;
            case 'grade-level':
                otherFilter = $("[name='program']").val()
                filters = { "strand": otherFilter, "grade": value };
                break;
        }
        tb.bootstrapTable("filterBy", filters);
        console.log(value);
        hideSpinner();
    });

    $(document).on("submit", "#section-form-page", function(e) {
        e.preventDefault();
        let formData = $(this).serializeArray();
        let tb = $("#section-enrollees-table");
        let selections = tb.bootstrapTable("getSelections");
        selections.forEach(item => {
            formData.push({ name: "students[]", value: item.id });
        });

        formData = formData.filter(function(e) {
            return !e.name.includes("btSelect");
        });

        formData.push({ name: 'count', value: selections.length });
        $.post("action.php", formData, function(data) {
            window.location.replace(`section.php?sec_code=${JSON.parse(data)}`);
        });
    });

    /** Add student */
    $(document).on("click", "#add-student", function() {
        let syID = $(this).attr("data-sy-id");
        let gradeLevel = $(this).attr("data-grade-level");
        let tableSetup = {
            url: `getAction.php?data=students&sy_id=${syID}&grade=${gradeLevel}`,
            maintainMetaDat: true,
            clickToSelect: true,
            method: "GET",
            uniqueId: 'stud_id',
            idField: 'stud_id',
            search: true,
            height: 450,
            searchSelector: "#search-student-input",
        }
        $("#student-options-table").bootstrapTable(tableSetup);
        $("#add-student-modal").modal('show');
    });

    $("#add-student-modal").on("shown.bs.modal", function() {
        $("#student-options-table").bootstrapTable('resetView');
    });

    $(document).on("submit", "#add-student-form", function(e) {
        e.preventDefault();
        let formData = $(this).serializeArray();
        let selections = $("#student-options-table").bootstrapTable("getSelections");
        selections.forEach(e => {
            formData.push({ name: `students[${e.stud_id}]`, value: e.section_code });
        });


        $.post("action.php", formData, function() {
            $("#add-student-modal").modal("hide");
            location.reload();
            // $("#student-options-table").bootstrapTable("refresh");
            // refreshCount();
        });
    });

    /** Transfer student */
    $(document).on("click", "#transfer-btn", function() {
        let selections = $("#table").bootstrapTable("getSelections");
        if (selections.length == 0) {
            return showToast('danger', "Please select a student first");
        }
        let syID = $(this).attr("data-sy-id");
        let gradeLevel = $(this).attr("data-grade-level");
        let section = $(this).attr("data-section");
        let tableSetup = {
            url: `getAction.php?data=sections&sy_id=${syID}&grade=${gradeLevel}&section=${section}`,
            maintainMetaDat: true,
            clickToSelect: true,
            method: "GET",
            uniqueId: 'section_code',
            idField: 'section_code',
            search: true,
            height: 450,
            searchSelector: "#search-section-input"
        };
        $("#section-options-table").bootstrapTable(tableSetup);
        $("#transfer-modal").modal('show');
    });

    $(document).on("submit", "#transfer-form", function(e) {
        e.preventDefault();

        let formData = $(this).serializeArray();
        let selections = $("#table").bootstrapTable("getSelections");
        selections.forEach(e => {
            formData.push({ name: `students[${e.stud_id}]`, value: e.section_code });
        });

        let newSection = $("#section-options-table").bootstrapTable("getSelections")[0].section_code;
        formData.push({name: "section_code", value: newSection});
        
        $.post("action.php", formData, function() {
            $("#transfer-modal").modal("hide");
            location.reload();
            // $("#section-options-table").bootstrapTable("refresh");
            // refreshCount();
        });
    });


    $(document).on("click", ".edit-sub-class", function() {
        let sectionCode, sectionName, row, studNo, grdLevel;
        sectionCode = $(this).attr("data-code");
        // section data
        row = $("#table").bootstrapTable('getRowByUniqueId', sectionCode );
        $("#sect-name").html(row.name);
        $("#stud-no").html(row.stud_no);
        $("#grd-level").html(row.grd_level);

        $.get(`getAction.php?data=sectionInfo&code=${sectionCode}`, function (data) {
            let temp = JSON.parse(data);
            // update the section input in form
            $("#selected-section").val(sectionCode);
            // reset program list and update
            $("#program-list").html('');
            temp.programs.forEach(e => {
                $("#program-list").append(e.link);
            });
            let form = $("#subject-class-form");
            let recommendedHTML = '';
            Object.entries(temp.recommended).forEach(e => {
                let progCode = e[0];
                recommendedHTML += `<div class="d-flex justify-content-between border rounded-3 p-3"><span class="fw-bold">${progCode}</span><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#${progCode}-collapse"><i class="bi bi-eye"></i></button></div>`; // strand code
                recommendedHTML += `<div id="${progCode}-collapse" class="collapse p-3 bg-light">`; // strand code
                Object.entries(e[1]).forEach(semester => {
                    let semDesc = semester[0]; // 1 or 2
                    recommendedHTML += `<div class="form-check">
                                          <input class="form-check-input" type="checkbox" value="" id="${progCode}-${semDesc}">
                                          <label class="form-check-label" for="${progCode}-${semDesc}">
                                            ${(semDesc == 1 ? "First Semester" : "Second Semester" )}
                                          </label>
                                        </div>`; // strand code
                    recommendedHTML += `<ul data-program="${progCode}" data-semester="${semDesc}" class="list-group mb-3">`;
                    semester[1].forEach(semItem => {
                        recommendedHTML += semItem;
                    });
                    recommendedHTML += "</ul>";
                });
                recommendedHTML += `</div>`; // strand code
            });
            // console.log(temp.recommended)
            // temp['recommended'].forEach(function(e, i) {
            //     console.log(i);
            // })
            form.find(".recommended").html(recommendedHTML);
            // form.find(".other");
        });
        $("#sub-class-modal").modal("show");
    });

    hideSpinner();
})