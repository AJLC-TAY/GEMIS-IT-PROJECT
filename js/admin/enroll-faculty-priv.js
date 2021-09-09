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

function togglePrivilege (teacherID, canEnroll) {
    facultyTable.bootstrapTable("showLoading")
    let formData, msg
    formData= new FormData()
    formData.append('teacher-id', teacherID)
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
    $(document).on('click', '.can-enroll-btn', function(e) {

    })
    hideSpinner()
})