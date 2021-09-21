import {
    commonTableSetup,
    implementAssignSubjectClassMethods as scMethods,
    implementAssignSubjectMethods as implementASMethods
} from "./utilities.js";

const isViewPage = false;
const ASSIGNEDSCID = "#assigned-sc-table";
const SCID = "#sc-table";

// Deleted roles
let rolesDel = [];
let rolesTmp = [];

// Department
let inputData;

let setupWithPagination = {search: true , ...commonTableSetup};

let sTableSetup = {
    search:             true,
    maintainMetaDat:    true,        // set true to preserve the selected row even when the current table is empty
    clickToSelect:      true,
    data:               subjects,
    uniqueId:           "sub_code",
    idField:            "sub_code",
    searchSelector:     '#search-sub-input',
    smartDisplay:       false,
    loadingTemplate,
    onPostBody:         () => {$("#subject-table").bootstrapTable("checkBy",  
                                                                  {field: 'sub_code', values: assigned})}
};

const detailFormatter = (index, row) => {
    // row details for reference
    // corequisite: []
    // for_grd_level: "12"
    // prerequisite: []
    // school_yr: "0"
    // section_code: "2"
    // section_name: "ABM 12"
    // sub_class_code: "9200"
    // sub_code: "Project"
    // sub_name: "Research Project"
    // sub_semester: "2"
    // sub_type: "applied"
    // teacher_id: 1
    return "<div class='container'>"
        + `<h5 class='mb-1'>${row.section_name}</h5>`
        + `<p class='text-secondary'><small>Section Code | ${row.section_code}</small></p>`
        + `<div class='ms-1'>`
        + `<p>Subject: ${row.sub_name}</p>`
        + `<p>Subject Type: ${row.sub_type}</p>`
        + `<p>Grade Level: ${row.for_grd_level}</p>`
        + `<p>Semester: ${row.sub_semester}</p>`
        + "</div>"
    + "</div>";
};

let assignedSCTableSetup = {...setupWithPagination, ...{
    data:               assignedSubClasses,
    uniqueId:           "sub_class_code",
    idField:            "sub_class_code",
    searchSelector:     '#search-assigned-sc-input',
    height:             "400",
    detailView:         true,
    detailFormatter:    detailFormatter
}};

let scTableSetup = {...setupWithPagination, ...{
    data:               subjectClasses,
    uniqueId:           "sub_class_code",
    idField:            "sub_class_code",
    searchSelector:     "#search-sc-input",
    height:             "380"
    // onPostBody:          () => $("#sc-table").bootstrapTable('resetView')
    // detailFormatter:    detailFormatter
}};

let advisoryTableSetup = {
    uniqueId:           "section_code",
    idField:            "section_code",
    height:             300,
    ...commonTableSetup
};

let subjectTable = $("#subject-table").bootstrapTable(sTableSetup);
let assignedSubClassTable = $(ASSIGNEDSCID).bootstrapTable(assignedSCTableSetup);
let subClassTable = $(SCID).bootstrapTable(scTableSetup);
let advisoryClassTable = $("#advisory-class-table").bootstrapTable(advisoryTableSetup);

$(function() {
    preload('#faculty');

    /** Tab pane initialization */
    var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'));
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl);
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault();
            tabTrigger.show();
        });
    });
    

    /** Role Methods */
    // If rolesTmp is 0, empty message is shown, else hidden
    const checkRolesTagForMsg = () => {
        let emptyMsg = $("#role-empty-msg");
        if (rolesTmp.length === 0) emptyMsg.removeClass('d-none');
        else emptyMsg.addClass('d-none');
    };

    // Edit roles
    $(document).on("click", "#role-edit-btn", function(e) {
        e.preventDefault();
        // hide edit button
        $(this).addClass('d-none');

        rolesTmp = [...roles]; // clone current roles to roles temp
        rolesDel = [];         // initialize roles to delete

        // show 
        $("#role-section").addClass("border");
        $(".role-to-delete-btn button, #role-option-tag-con, #role-decide-con").removeClass('d-none');
        checkRolesTagForMsg();
    });

    // Add role tag
    $("#role-option-tag-con button").click(function() {
        let element = $(this);
        element.addClass("d-none");

        let value = element.attr('data-value');
        rolesTmp.push(value);                                                // value is pushed to temporary roles
        $(`.role-to-delete-btn[data-value=${value}]`).removeClass('d-none'); // icon inside delete con is shown
        checkRolesTagForMsg();

        // console.log("*****Add clicked******")
        // console.log("Temp Roles:", rolesTmp)
        // console.log("Roles to Delete:", rolesDel)
    });

    // delete role tag
    $(".role-to-delete-btn button").click(function () {
        let element = $(this).closest("div");
        element.addClass('d-none');

        let value = element.attr('data-value');
        if (roles.includes(value)) rolesDel.push(value);         // if the removed role tag exist in
                                                                // the current row, add it to roles to delete array
        rolesTmp.splice(rolesTmp.indexOf(value), 1);             // then remove it from the temporary roles
       
        checkRolesTagForMsg();
        $(`#role-option-tag-con [data-value=${value}]`).removeClass('d-none');   // show add role tag button

        // console.log("*****Delete clicked******")
        // console.log("Temp Roles:", rolesTmp)
        // console.log("Roles to Delete:", rolesDel)
       
    });

    // cancel role edit
    $("#role-cancel-btn").click(function(e) {
        e.preventDefault();
        // hide
        $("#role-section").removeClass("border");
        $("#role-option-tag-con, #role-decide-con").addClass('d-none');

        // show
        $("#role-edit-btn").removeClass("d-none");
        
        // for each element in the current roles, their corresponding 
        // tag will be shown but their delete icon is hidden
        roles.forEach(e => {
            let eHTML = $(`.role-to-delete-btn[data-value=${e}]`);
            eHTML.find("button").addClass('d-none');
            eHTML.removeClass('d-none');
        });

        // for each element in the roles to be delete, their add button tag are hidden
        rolesDel.forEach(e => {
            let eHTML = $(`#role-option-tag-con [data-value=${e}]`);
            eHTML.addClass('d-none');
        });

        // temporary arrays are set to empty
        rolesDel = [];
        rolesTmp = [];

        // console.log("*****Cancel clicked******")
        // console.log("Temp Roles:", roles)
        // console.log("Temp Roles:", rolesTmp)
        // console.log("Roles to Delete:", rolesDel)
    });

    // $("#role-save-btn").click(() => $("#role-form").submit())

    $("#role-form").submit(function(e) {
        e.preventDefault();
        showSpinner();
        var formData = $(this).serialize();

        // add each element in the temporary roles to the formdata
        rolesTmp.forEach(role => {
            formData += encodeURI(`&access[]=${role}`);
        });

        $.post('action.php', formData, function(){
            // hide
            $("#role-section").removeClass("border");
            $("#role-option-tag-con, #role-decide-con").addClass('d-none');
            // the delete icon of tags corresponding to each of the 
            // element in the temporary roles are hidden 
            rolesTmp.forEach(e=> {
                let eHTML = $(`.role-to-delete-btn[data-value=${e}]`);
                eHTML.find("button").addClass('d-none');
            });

            // show
            $("#role-edit-btn").removeClass('d-none');
            checkRolesTagForMsg();

            // override roles by cloning the temprary roles
            roles = [...rolesTmp];
            hideSpinner();
            showToast('success', "Roles successfully updated");
        });
    });

    /** Department Methods */
    $("#dept-edit-btn").click(function() {
        showSpinner();
        let input = $("#dept-input");
        // Get input value and store it to the input data
        inputData = input.val();
        // empty input if no department is set
        if (!deptExist) input.val("");
        
        // show
        $("#dept-section").addClass("border");
        // $("#dept-decide-con, #dept-clear-btn, .dept-ins").removeClass('d-none')
        $("#dept-decide-con, #dept-clear-btn, .dept-ins").fadeIn();
        // input.removeClass('d-none')
        input.prop('readonly', false);
        
        // hide
        // $(this, "#dept-empty-msg").addClass('d-none')
        $(this).fadeOut();
        hideSpinner();
    });

    $("#dept-clear-btn").click(function(e) {
        e.preventDefault();
        $("#dept-input").val('');
    });

    $("#dept-cancel-btn").click(function(e) {
        e.preventDefault();
        showSpinner();
        let input = $("#dept-input");
        input.attr('readonly', true);
        input.val(inputData);

        // hide
        $("#dept-section").removeClass("border");
        // $("#dept-decide-con, #dept-clear-btn, .dept-ins").toggleClass('d-none');
        $("#dept-decide-con, #dept-clear-btn, .dept-ins").fadeOut();

        // show
        $("#dept-edit-btn").fadeIn();
        // $("#dept-edit-btn").removeClass("d-none");
        // $("#dept-empty-msg").toggleClass('d-none');
        hideSpinner();

        
    });

    $("#dept-form").submit(function(e) {
        e.preventDefault();
        showSpinner();

        $.post("action.php?", $(this).serialize(), function() {
            // hide
            $("#dept-section").removeClass("border");
            $("#dept-decide-con, #dept-clear-btn, .dept-ins").fadeOut();

            let input = $("#dept-input");
            input.attr('readonly', true);
            // empty input if no department is set
            if (input.val().length === 0) {
                input.val("No department set");
                deptExist = false ;
            } else deptExist = true;
            
            // show
            $("#dept-edit-btn").fadeIn();
            
            hideSpinner();
            showToast('success', "Department successfully updated");
        });
    });
    /** Department methods end */

    /** Subject methods */
    implementASMethods(assigned, subjectTable);

    /** Advisory Methods */

    const reloadSectionSelection = data => {
        let html = "", container = $("#section-list");
        data.forEach(e => {
            const sectionCd = e.section_code;
            const teacherID = e.adviser_id;
            const sectionNm = e.section_name;
            const sectionGr = e.section_grd;
            const sectionAd = e.adviser_name;

            let colorBadge = "success";
            let availability = "available";

            if (teacherID) {
                colorBadge = "warning";
                availability = "unavailable";
            }

            html += ` <li class='list-group-item'>
                    <div class='form-row row'>
                        <span class='col-1'><input id='${sectionCd}' class='form-check-input me-1' data-current-adviser='${teacherID ?? ""}' name='section' type='radio' value='${sectionCd}'></span>
                        <div class='section-info d-flex justify-content-between col-sm-6'>
                            <label for='${sectionCd}'>${sectionCd} - ${sectionNm} </label> 
                            <span class='text-secondary'>G${sectionGr}</span>
                        </div>
                        <div class='section-status d-flex justify-content-between col-sm-5'>
                            <div class='teacher-con' title='Current class adviser'>${sectionAd}</div>
                            <span class='badge ${availability}'><div class='bg-${colorBadge} rounded-circle' style='width: 10px; height: 10px;'></div></span>
                        </div>
                    </div>
                </li>`;
        });
        container.html(html);
    };

    $(document).on("shown.bs.modal", "#advisory-modal", function () {
        $("#advisory-spinner").fadeOut();
        // $("#section-list").show();
    });
  
      $("#advisory-form").submit(function(e) {
          e.preventDefault();
          showSpinner();
          let form = $(this);
          let formData = form.serializeArray();
  
          let currentAdviser = $("#advisory-form [type='radio']:checked").attr("data-current-adviser");
          if (currentAdviser) formData.push({name : "current-adviser", value : currentAdviser});
          $.post("action.php", formData, function(data) {
              let sectionData = JSON.parse(data);
              form.trigger("reset");
            
              let currentSectValue = sectionData.section_code;
              let sectionDetail = `${currentSectValue} - ${sectionData.section_name}`;
              
              // toggle editable state of the unassign checkbox
              let cbEditable = false;
              if (!sectionData.section_code) {
                  cbEditable = true;
                  currentSectValue = "";
                  sectionDetail = sectionData.section_name;
              }
              $("#current-advisory").html(`${sectionDetail}`);
              $("input[name='current-section']").val(currentSectValue);
              $("input[name='unassign']").prop("disabled", cbEditable);
  
              reloadSectionSelection(sectionData.data);
              $("#section-opt-con input, #section-filter").prop("disabled", false);
              $("#advisory-modal").modal("hide");
              hideSpinner();
              showToast("success", "Successfully updated advisory class");
          });
          // console.log($("input[type='radio']:checked"))
      });
  
      // Disable all section input when unassign checkbox is checked
      $(document).on("click", "input[name='unassign']", function() {
          let bool = false;
          if ($(this).is(":checked")) {
              bool = true;
              $("#section-opt-con [type='radio']").prop("checked", false);
          }
          $("#section-opt-con input, #section-filter").prop("disabled", bool);
      });
  
    /***
     *  Adds search feature to the specified input inorder to filter 
     *  the list of elements referred by the given selector. 
     * @param {String} searchInputID    Search input selector
     * @param {String} itemSelector     Item selector of the list 
     * */
    const listSearchEventBinder = (searchInputID, itemSelector) => {
        $(document).on("keyup", searchInputID, function() {
            var value = $(this).val().toLowerCase();
            $(itemSelector).filter(function() {
                if ($(this).text().toLowerCase().indexOf(value) > -1) return $(this).removeClass("d-none");
                return $(this).addClass("d-none");
            });
        });
    };

    listSearchEventBinder("#search-section", "#section-list li");
    listSearchEventBinder("#search-subject", ".assigned-sub-con a");
  
    $(document).on("click", "#all-section-btn", function() {
        $("#section-list li").removeClass("d-none");
    });
  
    const filterSection = (parameter) => {
        $("#section-list li").filter(function() {
            if ($(this).find(".form-row .section-status span").hasClass(parameter)) return $(this).removeClass("d-none");
            return $(this).addClass("d-none");
        });
    };

    $(document).on("click", "#no-adv-btn", function(e) {
        e.preventDefault();
        filterSection("available");
    });

    $(document).on("click", "#with-adv-btn", function(e) {
        e.preventDefault();
        filterSection("unavailable");
    });

    /** Add Subject Class Methods */
    scMethods(ASSIGNEDSCID, SCID);

    $(document).on("show.bs.modal", "#add-sc-modal", function () {
        $("#sc-form input[name='teacher_id']").val(teacherID);
    });

    hideSpinner();
});