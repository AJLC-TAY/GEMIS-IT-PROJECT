preload("#enrollment", "#set-up")

/** Faculty privilege table method */
const tableSetup = {
    url:                'getAction.php?data=faculty-privilege',
    method:             'GET',
    uniqueId:           'teacher_id',
    idField:            'teacher_id',
    height:             425,
    search:             true,
    searchSelector:     "#search-input",
    maintainMetaDat:    true,
    clickToSelect:      true,
    pageSize:           10,
    pagination:         true,
    pageList:           '[10, 25, 50, All]',
    paginationParts:    ['pageInfoShort', 'pageSize', 'pageList']
}

let facultyTable = $("#table").bootstrapTable(tableSetup)

facultyTable.bootstrapTable('refreshOptions', {
    filterOptions: {
        filterAlgorithm: 'or'
    }
})

function togglePrivilege (teacherID, canEnroll) {
    facultyTable.bootstrapTable("showLoading")
    let formData, msg
    formData= new FormData()
    formData.append('teacher-id[]', teacherID)
    formData.append('action', 'changeEnrollPriv' )
    formData.append('can-enroll', canEnroll)
    msg = "Faculty can " + (canEnroll == 1 ? "now" : "no longer") + " enroll students"
    $.ajax({
        url:    "action.php",
        method: "POST",
        data:   formData,
        processData: false,
        contentType: false,
        success: () => {
            setTimeout(function () {
                facultyTable.bootstrapTable('refresh')
                facultyTable.bootstrapTable("hideLoading")
            }, 500)
            showToast("success", msg)
        }
    })


}


$(function () {
    $(document).on('click', '.enroll-priv-btn', function(e) {
        let selections = facultyTable.bootstrapTable('getSelections')
        // Notify user if there is no selection
        if (selections == 0) return showToast("danger", "Please select a faculty first")

        facultyTable.bootstrapTable("showLoading")
        let id, value, formData, actionName, msg
        // Get button id to determine what action
        id = $(this).attr('id')
        value = id.includes('rm') ? '0' : '1'

        // Prepare the form data
        formData = new FormData()
        formData.append('action', "changeEnrollPriv")
        formData.append('can-enroll', value)
        selections.forEach(e => formData.append('teacher-id[]', e.teacher_id))
        msg = "Selected faculty can " + (value == 1 ? "now" : "no longer") + " enroll students"

        $.ajax({
            url:         "action.php",
            method:      "POST",
            data:        formData,
            processData: false,
            contentType: false,
            success:     () => {
                            setTimeout(function () {
                                facultyTable.bootstrapTable('refresh')
                                facultyTable.bootstrapTable("hideLoading")
                            }, 500)
                            showToast("success", msg)
                         }
        })
    })

    $(document).on('click', '.filter-item', function (e) {
        e.preventDefault()
        // Add active state to the button and remove active state from other options
        $(this).addClass('active')
        $(this).closest("ul").find("li a").not($(this)).removeClass('active')

        let value = $(this).attr('id')
        let filterData = {}
        if (value !== 'all') filterData.status = [(value.includes('cant') ? '0' : '1')]
        facultyTable.bootstrapTable('filterBy', filterData)
    })
    hideSpinner()
})