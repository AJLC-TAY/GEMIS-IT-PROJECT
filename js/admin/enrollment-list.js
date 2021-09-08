preload("#enrollment", "#enrollment-sub")

const tableSetup = {
    url:                'getAction.php?data=enrollees',
    method:             'GET',
    uniqueId:           'LRN',
    idField:            'LRN',
    height:             440,
    maintainMetaDat:    true,       // set true to preserve the selected row even when the current table is empty
    clickToSelect:      true,
    pageSize:           20,
    pagination:         true,
    pageList:           "[20, 40, 80, 100, All]",
    paginationParts:    ["pageInfoShort", "pageSize", "pageList"],
    search:             true,
    searchSelector:     '#search-input'
}

let enrolleesTable = $('#table').bootstrapTable(tableSetup)

$(function() {
    hideSpinner()
})