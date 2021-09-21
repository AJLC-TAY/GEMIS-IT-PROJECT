const tableSetup = {
    url:                `getAction.php?data=student`, 
    method:             'GET',
    uniqueId:           '0',
    idField:            'student_id',
    height:             425,
    maintainMetaDat:    true,       // set true to preserve the selected row even when the current table is empty
    clickToSelect:      true,
    pageSize:           10,
    pagination:         true,
    search:             true,
    pageList:           "[10, 25, 50, All]",
    paginationParts:    ["pageInfoShort", "pageSize", "pageList"],
    fixedColumns:       true,
    searchSelector:     '#search-input',

};


let studentTable = $("#table").bootstrapTable(tableSetup);

$(function() {
    preload('#student');
    hideSpinner();
});