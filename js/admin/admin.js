

const tableSetup = {
    url:                'getAction.php?data=administrators',
    method:             'GET',
    uniqueId:           'admin_id',
    idField:            'admin_id',
    height:             425,
    maintainMetaDat:    true,       // set true to preserve the selected row even when the current table is empty
    search:             true,
    searchSelector:     '#search-input',
    pageSize:           10,
    pagination:         true,
    pageList:           "[10, 25, 50, All]",
    paginationParts:    ["pageInfoShort", "pageSize", "pageList"],
    // onPostBody:         onPostBodyOfTable
}

var adminTable = $("#table").bootstrapTable(tableSetup)

$(function () {
    preload("#admin")
    hideSpinner()
})