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
};

const ASSIGNEDSCID = "#assigned-sc-table";
const SCID = "#sc-table";

let assignedSubClassTable = $(ASSIGNEDSCID).bootstrapTable(assignedSCTableSetup);
var subClassTable = $(SCID).bootstrapTable(scTableSetup);
let subjectTable = $("#subject-table").bootstrapTable(asTableSetup);

$(function () {
    preload('#faculty');

    try {
        /** Assign Subject to Faculty Methods */
        asMethods(assigned, subjectTable);
        /** Add Subject Class Methods */
        scMethods(ASSIGNEDSCID, SCID);
    } catch (e) {}

    $(".edit-text").click(()=> $("#upload").click());
    hideSpinner();
});