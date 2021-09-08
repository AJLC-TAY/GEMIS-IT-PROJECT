let onPostBodyOfTable = () => {
    // $('.profile-btn').click(function() {
    //     let id = $(this).attr('data-id')
    //     let state = $(this).attr('data-state')
    //     let formData = new FormData()
    //     formData.append('id', id)
    //     formData.append('state', state)
    //     $.post("profile.php", formData, function() {
            
    //     })


    // })
}

function buttons () {
    return {
        deactivateBtn: {
            text: 'Highlight Users',
            icon: '',
            event: function () {
                alert('Do some stuff to e.g. search all users which has logged in the last week')
            },
            attributes: {
                title: 'Search all users which has logged in the last week'
            }
        },
        resetPassword: {
            text: 'Add new row',
            icon: 'bi-box-arrow-up-left',
            event: function () {
                alert('Do some stuff to e.g. add a new row')
            },
            attributes: {
                title: 'Add a new row to the table'
            }
        },
        addBtn: {
            text: 'Add new row',
            icon: 'bi bi-plus-square-fill',
            buttonClass: 'success',
            event: function () {
                alert('Do some stuff to e.g. add a new row')
            },
            attributes: {
                title: 'Add a new row to the table'
            }
        }
    }
}

const tableSetup = {
    url:                'getAction.php?data=faculty',
    method:             'GET',
    uniqueId:           'teacher_id',
    idField:            'teacher_id',
    height:             440,
    maintainMetaDat:    true,       // set true to preserve the selected row even when the current table is empty
    clickToSelect:      true,
    pageSize:           10,
    pagination:         true,
    pageList:           "[10, 25, 50, All]",
    paginationParts:    ["pageInfoShort", "pageSize", "pageList"],
    search:             true,
    searchSelector:     '#search-input',
 
}
let facultyTable = $('#table').bootstrapTable(tableSetup)
let selection

$(function() {
    preload('#faculty')
    $('#edit-btn').click(function() {
        $(this).prop("disabled", true)
        $("#save-btn").prop("disabled", false)
        $(this).closest('form').find('.form-input').each(function() {
            $(this).prop('disabled', false)
        })
    })

    /** 
     *  Counts the number of selected records, then shows a warning if empty and returns false; 
     *  otherwise, return true.
     */
    const countSelection = () => {
        selection = facultyTable.getSelections()
        let length = selection.length 
        if (length < 1) showToast("danger", "No faculty selected")
        return length
    }

    $("#deactivate-opt").click(function() {
        let length = countSelection()
        if (length) {
            let modal = $("#deactivate-modal")         
            let question = (length == 1) ? "this faculty?" : `${length} faculties?`
            modal.find("#question").html(question)
            modal.modal("show")
        }
    })

    $("#deactivate-btn").click(() => $("#deactivate-form").submit())

    $("#deactivate-form").submit(function(e) {
        e.preventDefault()
        let formData = $(this).serializeArray()

        formData.push(...selection.map(e => {return {name: "id[]", value: `${e.teacher_id}`}}))
        $.post("action.php")

    })

    $("#reset-pass-opt").click(function() {
        if (countSelection()) {
            
        }
    })

    $("#export-opt").click(function() {
        if (countSelection()) {

        }
    })


    

    // $('#save-btn').click(function() {
    //     $(this).prop("disabled", true)
    //     $("#edit-btn").prop("disabled", false)
    //     $(this).closest('form').find('input').each(function() {
    //         $(this).prop('disabled', true)
    //     })
    // })

    hideSpinner()
})