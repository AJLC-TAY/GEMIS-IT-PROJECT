import {
    implementAssignSubjectMethods as asMethods,
    implementAssignSubjectClassMethods as scMethods, commonTableSetup
} from "./utilities.js";

let asTableSetup = {
    data:               subjects,
    uniqueId:           "sub_code",
    idField:            "sub_code",
    search:             true,
    searchSelector:     '#search-sub-input',
    clickToSelect:      true,
    height:             500,
    maintainMetaDat:    true,       // set true to preserve the selected row even when the current table is empty
    onPostBody:         () => { $("#subject-table").bootstrapTable("checkBy",
                                                                    {field: 'sub_code', values: assigned});
    }
};

const detailFormatter = (index, row) => {
    // row details for reference
    // corequisite: []Management
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


let assignedSCTableSetup = {
    data:               assignedSubClasses,
    uniqueId:           "sub_class_code",
    idField:            "sub_class_code",
    search:             true,
    searchSelector:     '#search-assigned-sc-input',
    height:             "400",
    detailView:         true,
    detailFormatter,
    ...commonTableSetup
};

let scTableSetup = {
    data:               subjectClasses,
    uniqueId:           "sub_class_code",
    idField:            "sub_class_code",
    search:             true,
    searchSelector:     '#search-sc-input',
    height:             380,
    ...commonTableSetup
    // onPostBody:          () => $("#sc-table").bootstrapTable('resetView')
    // detailFormatter:    detailFormatter
};

const ASSIGNEDSCID = "#assigned-sc-table";
const SCID = "#sc-table";

let assignedSubClassTable = $(ASSIGNEDSCID).bootstrapTable(assignedSCTableSetup);
var subClassTable = $(SCID).bootstrapTable(scTableSetup);
let subjectTable = $("#subject-table").bootstrapTable(asTableSetup);

$(function () {
    preload('#faculty');

    /** Handling image upload */
    const readURL = input => {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#resultImg').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    };

    $("#upload").change(function(){
        readURL(this);
    });

    try {
        /** Assign Subject to Faculty Methods */
        asMethods(assigned, subjectTable);
        /** Add Subject Class Methods */
        scMethods(ASSIGNEDSCID, SCID);
    } catch (e) {}

    $(document).on("submit", "#faculty-form", function (e) {
        e.preventDefault();
        // var action = $(this).attr('data-action');
        var formData = new FormData($(this)[0]);

        try {
            $(ASSIGNEDSCID).bootstrapTable("getData")
                           .forEach(e => {
                               formData.append("asgn-sub-class[]",  e.sub_class_code);
                           });

            subjectTable.bootstrapTable("getSelections")
                               .forEach(e => {
                                   formData.append("subjects[]", e.sub_code);
                               });
        } catch (e) {}

        // formData.append("profile", "faculty");
        // formData.append("action", action);

        $.ajax({
            url: "action.php",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: data => {
                let response = JSON.parse(data);
                window.location.replace(`faculty.php?id=${response.teacher_id}`);
            }
        });
    });

    $(".edit-text").click(()=> $("#upload").click());
    hideSpinner();
});