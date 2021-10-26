import {commonTableSetup} from "./utilities.js";

preload('#enrollment', '#section')

let tableSetup, tableFormSetup, url, id, table, tableForm;
tableSetup = {
    method:             'GET',
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
    pageSize:  50,
    pageList: "[50, 100, All]",
    height: 800
}

url =  "getAction.php?";
id = '';

if (isViewPage) {
    url += `data=student&section=${sectionCode}`;
    id = 'lrn';
}
else {
    url += 'data=section';
    id = 'seciton_code';
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

$(function() {
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

        if (!adviser) $("#empty-msg").removeClass("d-none");     // show empty message if no assigned adviser originally

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
        let formData= form.serializeArray();
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
        let subSetUp = {...tableSetup};
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
        switch($(this).attr("name")) {
            case 'program': 
                otherFilter = $("[name='grade-level']").val();
                filters = (value == '*') ? {"grade": otherFilter} : {"strand": value, "grade" : otherFilter};
                console.log(otherFilter);
                break;
            case 'grade-level': 
                otherFilter = $("[name='program']").val()
                filters = {"strand": otherFilter, "grade" : value};
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
            formData.push({name: "students[]", value: item.id});
        });

        formData = formData.filter(function(e) {
            return !e.name.includes("btSelect");
        });

        formData.push({name: 'count', value: selections.length});
        $.post("action.php", formData, function(data) {
            window.location.replace(`section.php?sec_code=${JSON.parse(data)}`);
        });
    });
    

    // $('#save-btn').click(function() {
    //     $(this).prop("disabled", true)
    //     $("#edit-btn").prop("disabled", false)
    //     $(this).closest('form').find('input').each(function() {
    //         $(this).prop('disabled', true)
    //     })
    // })

    hideSpinner();
})