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

let selections = [];

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
        $('#table').bootstrapTable("checkBy", { field: 'LRN', values: selections })
    } catch (e) {}
}

const tableSetup = {
    search: true,
    autoRefresh: true,
    showRefresh: true,
    showAutoRefresh: true,
    autoRefreshInterval: 10,
    searchSelector: "#search-input",
    url: "getAction.php?data=enrollees",
    buttonsToolbar: ".buttons-toolbar",
    sidePagination: "server",
    uniqueId: "LRN",
    idField: "LRN",
    queryParams: queryParams,
    toggle: "#toolbar",
    height: 880,
    maintainMetaDat: true, // set true to preserve the selected row even when the current table is empty
    clickToSelect: true,
    pageSize: 25,
    pagination: true,
    pageList: "[25, 50, 100, All]",
    onPostBody: checkSelections,
    // responseHandler:         responseHandler
};
let enrolleesTable = $('#table').bootstrapTable(tableSetup);
enrolleesTable.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function() {
    // save your data, here just save the current page
    selections = $.map(enrolleesTable.bootstrapTable('getSelections'), function(row) {
        return row.LRN
    });
    // push or splice the selections if you want to save all data selections
});



$(function() {
    preload("#enrollment", "#enrollment-sub");

    $(".buttons-toolbar").hide();

    /** Table options */
    $(document).on("click", ".table-opt", function() {
        if (selections.length === 0) {
            return showToast("danger", "Please select a student first");
        }
        switch ($(this).attr("id")) {
            case "delete-opt":
                let modal = $("#delete-student-modal");
                modal.find("#student-count").html(selections.length);
                let studentList = '';
                enrolleesTable.bootstrapTable('getSelections').forEach(e => {
                    studentList += `<li class="list-group-item"><div class="d-flex justify-content-between"><span>${e.name}</span><span>${e.status}</span></div></li>`;
                });
                modal.find("#student-selected").html(studentList);
                modal.modal("show");
                break;
            case "export-opt":
                break;
            case "archive-opt":
                break;

        }
    });

    $(document).on("click", ".delete-student-btn", function() {
        let lrn = $(this).attr("data-lrn");
        let modal = $("#delete-student-modal");
        modal.find("#student-count").html('');
        let row = enrolleesTable.bootstrapTable('getRowByUniqueId', lrn);
        modal.find("#student-selected").html(`<li class="list-group-item"><div class="d-flex justify-content-between"><span>${row.name}</span><span>${row.status}</span></div></li>`);
        modal.modal("show");
    })

    /** Delete students */
    $(document).on("submit", "#delete-form", function(e) {
        e.preventDefault();
        let formData = $(this).serializeArray();
        enrolleesTable.bootstrapTable('getSelections').forEach(e => {
            formData.push({ 'name': 'students[]', 'value': e.stud_id });
        });
        $.post("action.php", formData, function() {
            $("#delete-student-modal").modal("hide");
            enrolleesTable.bootstrapTable("refresh");
            selections = [];
            showToast("success", "Students successfully deleted");
        });
    });

    /** Updates the button title of the switch */
    function autoRefreshEvents(elem) {
        if (elem.attr("data-status") === "on") {
            elem.attr("data-status", "off")
            elem.removeClass('btn-dark')
            elem.attr('title', 'Turn on auto refresh')
            return 'off'
        }
        elem.attr("data-status", "on")
        elem.addClass('btn-dark')
        elem.attr('title', 'Turn off auto refresh')
        return 'on'
    };

    /** Refreshes the table when the button with refresh class is clicked */
    $(document).on("click", ".refresh", function() {
        enrolleesTable.bootstrapTable("showLoading")
        enrolleesTable.bootstrapTable("refresh");
        setTimeout(() => {
            enrolleesTable.bootstrapTable("hideLoading")
        }, 500);
    });

    /** Clicks the hidden auto refresh button and shows then hides the loading status of the table  */
    $(document).on("click", ".auto-refresh-switch", function() {
        $(".auto-refresh").click();
        let status = autoRefreshEvents($(this))
        enrolleesTable.bootstrapTable("showLoading")
        setTimeout(() => {
            enrolleesTable.bootstrapTable({ autoRefreshStatus: false });
            enrolleesTable.bootstrapTable("hideLoading")
            showToast('dark', `Table auto refresh is turned ${status}`)
        }, 300);
    });

    /** Refreshes the table when a filter item is changed */
    $(document).on("change", ".filter-item", function() {
        $("#table").bootstrapTable("refresh")
    });

    /** Sets all select filter into All with value of '*' and refreshes the table */
    $(document).on("click", ".reset-filter-btn", function() {
        $(this).closest(".collapse").find("select").val("*");
        enrolleesTable.bootstrapTable("refresh");
    });

    hideSpinner();
});