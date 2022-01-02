import { commonTableSetup } from "./utilities.js";

let tableSetup, tableFormSetup, url, id, table, tableForm, currentSubTeacher;
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

try {
    if (isViewPage) {
        url += `data=student&section=${sectionCode}`;
        id = 'lrn';
    } else {
        url += 'data=section';
        id = 'code';
    }
} catch (e) {}
tableSetup.url = url;
tableSetup.idField = id;
tableSetup.uniqueId = id;
tableSetup.height = 425;
table = $("#table").bootstrapTable(tableSetup);

try {
    tableForm = $("#section-enrollees-table").bootstrapTable(tableFormSetup);
} catch (e) {}

let addAnother = false;

const renderSelect2 = () => {
    $(".teacher-select").select2({
        theme: "bootstrap-5",
        width: "100%",
        dropdownParent: $("#sub-class-modal")
    });
}
$(function() {
    preload('#enrollment', '#section');
    $("#adviser").select2({
        theme: "bootstrap-5",
        width: null,
    });

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

        $("a.link").removeClass("d-none");
    });

    $(document).on("submit", "#section-edit-form", function(e) {
        e.preventDefault();
        showSpinner();
        let form = $(this);
        let formData = form.serializeArray();
        $.post("action.php", formData, function(data) {
            data = JSON.parse(data);
            location.replace(`section.php?sec_code=${data.section}`);
        });
    });

    /** Clears the teacher input if clear button is clicked */
    $(document).on("click", "#adviser-clear-btn", function(e) {
        e.preventDefault();
        $("#adviser-section").val("");
        $("#adviser-section").trigger("change");
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
                otherFilter = $("[name='grade-lvl-filter']").val();
                filters = (value == '*') ? { "grade": otherFilter } : { "strand": value, "grade": otherFilter };
                break;
            case 'grade-lvl-filter':
                otherFilter = $("[name='program']").val()
                filters = { "strand": otherFilter, "grade": value };
                break;
        }
        console.log(filters);

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
        let tableSetup = {
            url: `getAction.php?data=students&sy_id=${syID}&section=${sectionCode}`,
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
        });
    });

    /** Transfer student */
    $(document).on("click", "#transfer-btn", function(e) {
        e.preventDefault();
        let selections = $("#table").bootstrapTable("getSelections");
        if (selections.length === 0) {
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
        });
    });

    /** Edit subject class */
    $(document).on("click", ".edit-sub-class", function() {
        let sectionCode, row;
        sectionCode = $(this).attr("data-code");
        // section data
        try {
            row = $("#table").bootstrapTable('getRowByUniqueId', sectionCode );
            $("#section-name-modal").html(row.name);
            $("#stud-no").html(row.stud_no);
            $(".grd-level").html(row.grd_level);
        } catch (e) {
            $("#section-name-modal").html(currentSectName);
            $("#stud-no").html(currentSectNo);
            $(".grd-level").html(currentSectLevel);
        }

        $.get(`getAction.php?data=sectionInfo&code=${sectionCode}`, function (data) {
            let temp = JSON.parse(data);
            console.log(temp);
            // update the section input in form
            $("#selected-section").val(sectionCode);
            // reset program list and update
            $("#program-list").html('');
            // populate programs in subject class modal
            temp.programs.forEach(e => {
                $("#program-list").append(e.link);
            });
            /** Dynamically create tables */
            let form = $("#subject-class-form");
            form.find(".recommended").html('');
            let recommendedHTML = '';
            Object.entries(temp.recommended).forEach(e => {
                let progCode = e[0];
                recommendedHTML += `<div class="d-flex justify-content-between border rounded-3 p-3"><span class="fw-bold">${progCode}</span><button type="button" class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#${progCode}-collapse"><i class="bi bi-eye"></i></button></div>`; // strand code
                recommendedHTML += `<div id="${progCode}-collapse" class="collapse p-3 bg-light overflow-auto">`; // strand code
                Object.entries(e[1]).forEach(semester => {
                    let semDesc = semester[0]; // 1 or 2
                    recommendedHTML += `<table data-program="${progCode}" data-semester="${semDesc}" class="table table-sm table-striped table-bordered">
                                            <col width="5%">
                                            <col width="45%">
                                            <col width="50%">
                                            <thead class="text-center">
                                                <tr><td colspan="3">${(semDesc == 1 ? "First Semester" : "Second Semester")}</td></tr>
                                                <tr>
                                                    <td><input class="form-check-input semester-checkbox" type="checkbox" data-program="${progCode}" data-semester="${semDesc}"value="" id="${progCode}-${semDesc}"></td>
                                                    <td>Subject Name</td>
                                                    <td>Faculty</td>
                                                </tr>
                                            </thead>
                                            <tbody>`;
                    semester[1].forEach(semItem => {
                        recommendedHTML += `<tr>
                            <td class='text-center'>${semItem.checkbox}</td>
                            <td>${semItem.subName}</td>
                            <td>${semItem.action}</td>
                            </tr>`;
                    });
                    recommendedHTML += "</tbody></table>";
                });
                recommendedHTML += `</div>`; // strand code
            });
            form.find(".recommended").html(recommendedHTML);
            /** Update current subject faculty */
            currentSubTeacher = temp.currentSubTeacher;
        });

        $("#sub-class-modal").modal("show");
    });

    $(document).on('shown.bs.collapse', '.collapse', function () {
        /** Populate select2 options */
        let selectOptions = "<option value=''>-- Select faculty --</option>";
        activeFacultyList.forEach(e => {
            selectOptions += `<option value="${parseInt(e.teacher_id)}">T. ${e.name}</option>`;
        });
        $(".teacher-select").html(selectOptions);
        /** Prepare teacher options */
        renderSelect2();
        /** Update select2 values */
        Object.entries(currentSubTeacher).forEach(function (e) {
            let info = e[1];
            $(`select[name='subjectClass[${info.sect_code}][${info.sub_code}]']`).val(info.sub_teacher).change();
        });
    });

    $(document).on("click", ".form-check-input", function() {
        let row = $(this).closest("tr");
        row.find(".teacher-select").prop("disabled", !$(this).is(":checked"));
    });

    $(document).on("click", ".action[data-type='unassign']", function() {
        let sectionCode = $(this).attr("data-section");
        let subjectCode = $(this).attr("data-sub-code");
        $(this).hide();
        $(this).closest("div").find("[data-type='add']").show();
        $(`select[name='subjectClass[${sectionCode}][${subjectCode}]']`).val('').change();
    });

    $(document).on("change", ".teacher-select", function() {
        let container = $(this).closest("div");
        let addBtn = container.find(".action[data-type='add']");
        let unassignBtn = container.find(".action[data-type='unassign']");
        if ($(this).val().trim().length === 0) {
            addBtn.show();
            unassignBtn.hide();
        } else {
            addBtn.hide();
            unassignBtn.show();
        }
    });

    $(document).on("submit", "#subject-class-form", function (e) {
        e.preventDefault();
        $.post("action.php", $(this).serializeArray(), function () {
            $("#sub-class-modal").modal("hide");
            showToast('success', "Successfully updated subject class")
        });
    });

    /** Automatically show collapse */
    $(document).on("shown.bs.modal", "#sub-class-modal", function () {
       $(this).find(".collapse").collapse("show");
    });

    const toggleListElement = (program, semester, bool) => {
        $(`ul[data-program='${program}'][data-semester='${semester}']`).find("input").prop("checked", bool);
    }

    $(document).on("change", ".semester-checkbox", function() {
        const program = $(this).attr("data-program");
        const semester = $(this).attr("data-semester");
        const bool = $(this).is(":checked");
        $(`table[data-program='${program}'][data-semester='${semester}'] tbody`).find("input.form-check-input").prop("checked", bool);
    });

    /** End subject class end */
    hideSpinner();
})