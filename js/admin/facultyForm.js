import {implementAssignSubjectMethods as asMethods, implementAssignSubjectClassMethods as scMethods} from "./utilities.js";

preload('#faculty')

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
                                                                    {field: 'sub_code', values: assigned})
    }
}

console.log(assignedSubClasses)

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
        + "</div>"
}
let assignedSCTableSetup = {
    data:               assignedSubClasses,
    uniqueId:           "sub_class_code",
    idField:            "sub_class_code",
    search:             true,
    searchSelector:     '#search-assigned-sc-input',
    clickToSelect:      true,
    height:             "400",
    maintainMetaDat:    true,      // set true to preserve the selected row even when the current table is empty
    pageSize:           10,
    pagination:         true,
    pageList:           "[10, 25, 50, All]",
    paginationParts:    ["pageInfoShort", "pageSize", "pageList"],
    detailView:         true,
    detailFormatter:    detailFormatter
}

let scTableSetup = {
    data:               subjectClasses,
    uniqueId:           "sub_class_code",
    idField:            "sub_class_code",
    search:             true,
    searchSelector:     '#search-sc-input',
    clickToSelect:      true,
    maintainMetaDat:    true,      // set true to preserve the selected row even when the current table is empty
    height:             380,
    pageSize:           10,
    pagination:         true,
    pageList:           "[10, 25, 50, All]",
    paginationParts:    ["pageInfoShort", "pageSize", "pageList"],
    // onPostBody:          () => $("#sc-table").bootstrapTable('resetView')
    // detailFormatter:    detailFormatter
}

const ASSIGNEDSCID = "#assigned-sc-table"
const SCID = "#sc-table"

let assignedSubClassTable = $(ASSIGNEDSCID).bootstrapTable(assignedSCTableSetup)
var subClassTable = $(SCID).bootstrapTable(scTableSetup)
let subjectTable = $("#subject-table").bootstrapTable(asTableSetup)

$(function () {
    /** Handling image upload */
    const readURL = input => {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#resultImg').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#upload").change(function(){
        readURL(this);
    })

    // $('#faculty-form').submit(function(event) {
    //     event.preventDefault()

    //     console.log($(this).serializeArray())
    //     $.post("action.php", $(this).serializeArray(), function(data) {
    //         console.log(data)
    //     }).fail(function(error) {
    //         console.log(error.responseText)
    //     }) 
    // })

    /** Assign Subject to Faculty Methods */
    asMethods(assigned, subjectTable)

    /** Add Subject Class Methods */
    scMethods(ASSIGNEDSCID, SCID)




    $(".edit-text").click(()=> $("#upload").click())
    hideSpinner()
})