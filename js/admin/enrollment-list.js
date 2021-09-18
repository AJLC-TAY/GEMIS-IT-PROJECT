preload("#enrollment", "#enrollment-sub")

// const tableSetup = {
//     url:                'getAction.php?data=enrollees',
//     method:             'GET',
//     uniqueId:           'LRN',
//     idField:            'LRN',
//     height:             440,
//     maintainMetaDat:    true,       // set true to preserve the selected row even when the current table is empty
//     clickToSelect:      true,
//     pageSize:           20,
//     pagination:         true,
//     pageList:           "[20, 40, 80, 100, All]",
//     paginationParts:    ["pageInfoShort", "pageSize", "pageList"],
//     searchSelector:     '#search-input'
// }

let selections = []

function queryParams(params) {
    params.sy = $("#sy").val()
    params.track = $("#tracks").val()
    params.strand = $("#strands").val()
    params.yearLevel = $("#year-level").val()
    params.status = $("#status").val()
    return params
}

function checkSelections() {
    try {
        $('#table').bootstrapTable("checkBy", {field: 'LRN', values: selections})
    } catch (e) {}
}

const tableSetup = {
    search:              true,
    autoRefresh:         true,
    showRefresh:         true,
    showAutoRefresh:     true,
    autoRefreshInterval: 10,
    searchSelector:     "#search-input",
    url:                "getAction.php?data=enrollees",
    buttonsToolbar:     ".buttons-toolbar",
    sidePagination:     "server",
    uniqueId:           "LRN",
    idField:            "LRN",
    queryParams:        queryParams,
    toggle:             "#toolbar",
    height:             440,
    maintainMetaDat:    true,       // set true to preserve the selected row even when the current table is empty
    clickToSelect:      true,
    pageSize:           25,
    pagination:         true,
    pageList:           "[25, 50, 100, All]",
    onPostBody:         checkSelections
    // responseHandler:         responseHandler
}
let enrolleesTable = $('#table').bootstrapTable(tableSetup)
enrolleesTable.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function () {
    // save your data, here just save the current page
    selections = $.map(enrolleesTable.bootstrapTable('getSelections'), function (row) {
        return row.LRN
    })
    // push or splice the selections if you want to save all data selections
})

$(function() {

    /** Set default values of table buttons */
    // Add other table buttons in the buttons toolbar
    $(".buttons-toolbar").prepend(`<div class="col-auto d-inline-flex"><button id="subject-archive-btn" class="btn btn-secondary btn-sm me-1"><i class="bi bi-archive me-2"></i>Archive</button>
            <button id="export-opt" type="submit" class="btn btn-dark btn-sm" title='Export'><i class="bi bi-box-arrow-up-left me-2"></i>Export</button></div>`);

    // Add icons and labels in the buttons
    $("[name='refresh']").addClass('btn-sm').html("<i class='bi bi-arrow-repeat me-2'></i>Refresh")
    $(".auto-refresh").addClass('btn-sm btn-dark').html("<i class='bi bi-alarm me-2'></i> Auto Refresh")
    $(".auto-refresh").attr("title", "Turn off auto refresh")
    $(".auto-refresh").attr("data-status", "on")

    function autoRefreshEvents(elem) {
        if (elem.attr("data-status") === "on") {
            elem.attr("data-status", "off")
            elem.removeClass('btn-dark')
            elem.attr('title', 'Turn on auto refresh')
            return 'off'
        }
        elem.attr("data-status", "on")
        elem.addClass('btn-dark')
        elem.attr('title', 'Auto refresh on')
        return 'on'
    }

    $(document).on("click", ".auto-refresh", function () {
        enrolleesTable.bootstrapTable("showLoading")
        let status = autoRefreshEvents($(this))
        setTimeout(() => {
            enrolleesTable.bootstrapTable("hideLoading")
            showToast('dark', `Table auto refresh is turned ${status}`)
        }, 300)
    })


    $(document).on("change", ".filter-item", function() {
        $("#table").bootstrapTable("refresh")
    })





    hideSpinner()
})