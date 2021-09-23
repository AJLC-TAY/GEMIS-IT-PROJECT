import {commonTableSetup} from "./utilities.js";

preload("#enrollment", "#set-up");

/** Faculty privilege table method */

const defaultSetup = {
    method:             'GET',
    search:             true,
    ...commonTableSetup
};

let facultyTS = {...defaultSetup,
    url:                'getAction.php?data=faculty-privilege',
    uniqueId:           'teacher_id',
    idField:            'teacher_id',
    height:             425,
    searchSelector:     "#faculty-search-input",
};

let sectionTS = {...defaultSetup,
    url:                'getAction.php?data=section&current=true',
    uniqueId:           'code',
    idField:            'code',
    height:             300,
    searchSelector:     "#section-search-input",
};

let facultyTable = $("#faculty-table").bootstrapTable(facultyTS);
let sectionTable = $("#section-table").bootstrapTable(sectionTS);

facultyTable.bootstrapTable('refreshOptions', {
    filterOptions: {
        filterAlgorithm: 'or'
    }
});

/** Toggles the privilege of a selected faculty */
function togglePrivilege (teacherID, canEnroll) {
    facultyTable.bootstrapTable("showLoading");
    let formData, msg;
    formData= new FormData();
    formData.append('teacher-id[]', teacherID);
    formData.append('action', 'changeEnrollPriv' );
    formData.append('can-enroll', canEnroll);
    msg = "Faculty can " + (canEnroll == 1 ? "now" : "no longer") + " enroll students";
    $.ajax({
        url:    "action.php",
        method: "POST",
        data:   formData,
        processData: false,
        contentType: false,
        success: () => {
            facultyTable.bootstrapTable('refresh')
            setTimeout(function () {
                facultyTable.bootstrapTable("hideLoading")
            }, 3000)
            showToast("success", msg)
        }
    });
    return false;
}

window.togglePrivilege = togglePrivilege;
var stepper = new Stepper($('#stepper')[0]);

$(function () {
    /** Toggles enrollment privilege of selected on or multiple faculties */
    $(document).on('click', '.enroll-priv-btn', function(e) {
        e.preventDefault();
        let selections = facultyTable.bootstrapTable('getSelections');
        // Notify user if there is no selection
        if (selections.length === 0) return showToast("danger", "Please select a faculty first");

        facultyTable.bootstrapTable("showLoading");
        let id, value, formData, msg;

        // Get button id to determine what action
        id = $(this).attr('id');
        value = id.includes('rm') ? '0' : '1';

        // Prepare the form data
        formData = new FormData();
        formData.append('action', "changeEnrollPriv");
        formData.append('can-enroll', value);
        selections.forEach(e => formData.append('teacher-id[]', e.teacher_id));
        msg = "Selected faculty can " + (value == 1 ? "now" : "no longer") + " enroll students";

        $.ajax({
            url:         "action.php",
            method:      "POST",
            data:        formData,
            processData: false,
            contentType: false,
            success:     () => {
                            facultyTable.bootstrapTable('refresh')
                            setTimeout(function () {
                                facultyTable.bootstrapTable("hideLoading")
                            }, 700)
                            showToast("success", msg)
                         }
        });
    });

    /** Clear button of the faculty table */
    $(document).on('click', '#f-table-clear-btn', function (e) {
        facultyTable.bootstrapTable('resetSearch');
        $('#faculty-search-input').focus().select();
        $('ul[aria-labelledby="faculty-filter"] li a.active').click();
    });

    /** Filter button of the faculty table */
    $(document).on('click', '.filter-item', function (e) {
        e.preventDefault();
        facultyTable.bootstrapTable('showLoading');
        // Add active state to the button and remove active state from other options
        $(this).addClass('active');
        $(this).closest("ul").find("li a").not($(this)).removeClass('active');

        let value = $(this).attr('data-value');
        let filterData = {};
        if (value !== '*') filterData.status = [value];
        else facultyTable.bootstrapTable('refresh');

        facultyTable.bootstrapTable('filterBy', filterData)
                    .bootstrapTable('hideLoading');
    });

    /** Resets the view of the tables so as to fix their layout when shown or rendered */
    $(document).on("click", ".stepper-btn", function () {
        facultyTable.bootstrapTable('resetView');
        sectionTable.bootstrapTable('resetView');
    });

    /** Summarizes the allowed teacher to enroll and sections for the enrollment */
    $(document).on("click", "#to-step-3", function () {
        let facultyCount = 0;
        let faculty = facultyTable.bootstrapTable('getData').map(e => {
            if (e.status === 1) {
                facultyCount ++;
                return `<a class="list-group-item list-group-item-action" target='_blank' href="faculty.php?id=${e.teacher_id}">T. ${e.name}</a>`;
            }
        });
        let sections = sectionTable.bootstrapTable('getData').map(e => {
            return `<a class="list-group-item list-group-item-action" target='_blank' href="section.php?sec_code=${e.code}">${e.name}</a>`;
        });

        $("#faculty-count").html(facultyCount);
        $("#section-count").html(sections.length);

        $("#faculty-list").html(faculty.join(''));
        $("#section-list").html(sections.join(''));
    });

    $(document).on("click", "[form='section-form']", (e) => {
        e.preventDefault();
        $("#section-form").submit();
    });

    $(document).on("submit", "#section-form", function(e) {
        e.preventDefault();
        $.post("action.php", $(this).serializeArray(), function () {
            sectionTable.bootstrapTable('refresh');
            showToast('success', 'Section successfully added');
        });
    });

    /** Changes the enrollment status of the current school year */
    $(document).on("click", "[name='enrollment']", function() {
        showSpinner();
        let statusE = $(this).next(".status");
        let formData = `action=editEnrollStatus`;
        if ($(this).is(":checked")) {
            $(this).attr("title", "Turn off enrollment");
            statusE.html("On-going");
            formData += "&enrollment=on";
        } else {
            $(this).attr("title", "Turn on enrollment");
            statusE.html("Ended");
        }
        $.post("action.php", formData, function(data) {
            hideSpinner();
        });
    });

    /** Stepper */ 
    $(document).on("click", ".next", () => {
        stepper.next();
    });
    $(document).on("click", ".previous", () => {
        stepper.previous();
    });

    /** Remove section from enrollment setup */
    // $(document).on('click', '#remove-section-btn', function(e) {
    //     e.preventDefault()
    //     let selections = sectionTable.bootstrapTable('getSelections')
    //     if (selections.length === 0) return showToast('danger', 'Please select a section first')
    //
    //     let formData = new FormData()
    //     formData.append('action', 'deleteSection')
    //     selections.forEach(e => {
    //         formData.append('section-code[]', e.code)
    //     })
    //
    //
    //
    // })
    hideSpinner();
});