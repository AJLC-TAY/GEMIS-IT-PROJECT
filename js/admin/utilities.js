// let selectedSubjects = []
// const removeFromSelectedSubject = (subjectCode) => {
//     const index = selectedSubjects.indexOf(subjectCode)
//     if (index > -1) {
//         selectedSubjects.splice(index, 1);
//     }
// }
//
// export let setSubjectSelected = list => {
//     selectedSubjects = list
// }
//
// export let getSubjectSelected = () => {return selectedSubjects};
//
// export function addSubjectFn (e) {
//     e.preventDefault()
//     let id, subject, code
//     id = $('#search-input').val()
//     subject = subjects.filter(function (element) {
//         return element.sub_code == id
//     })
//
//     if (subject.length == 1) {
//         subject = subject[0]
//         code = subject.sub_code
//         if (selectedSubjects.includes(code)) return showToast('warning', 'Subject already added')
//         selectedSubjects.push(code)
//         $('#emptyMsg').addClass('d-none')
//         $('table').find('tbody').append(`<tr class='text-center'>
//             <td class='cb-con' scope='col'><input type='checkbox' value='${code}' /></td>
//             <td scope='col'><input type='hidden' name='subjects[]' value='${code}'/>${code}</td>
//             <td scope='col'>${subject.sub_name}</td>
//             <td scope='col'>${subject.sub_type}</td>
//             <td scope='col'>
//                 <button data-value='${code}' class='remove-btn btn btn-sm btn-danger m-auto shadow-sm' title='Delete subject'><i class='bi bi-x-square'></i></button>
//                 <a href='subject.php?sub_code=${code}&state=view' role='button' class='view-btn btn btn-sm btn-primary m-auto shadow-sm d-none' title='View subject'><i class='bi bi-eye'></i></a>
//             </td>
//         </tr>`)
//     } else {
//         showToast('warning', 'Code did not match')
//     }
// }
//
// export function removeAllBtnFn (e) {
//     e.preventDefault()
//     let selected = $("tbody input[type=checkbox]:checked")
//     $("#selectAll").prop('checked', false)
//     if (selected.length == 0) {
//         showToast('warning', 'No subject is selected')
//     } else {
//         selected.each(function() {
//             const element = $(this)
//             const id = element.val()
//             element.closest("tr").remove()
//             removeFromSelectedSubject(id)
//         })
//         if (selectedSubjects.length == 0) $('#emptyMsg').removeClass('d-none')
//     }
// }
//
// export function removeSubjectBtnFn (e){
//     e.preventDefault()
//     let element = $(this)
//     let id = element.attr('data-value')
//     element.closest("tr").remove()
//     removeFromSelectedSubject(id)
//     if (selectedSubjects.length == 0) $('#emptyMsg').removeClass('d-none')
// }
//
// export function selectAll () {
//     $(this).prop('checked', this.checked)
//     var table= this.closest('table')
//     $('td input:checkbox', table).prop('checked', this.checked)
// }


/** Assign Subject to Faculty Methods */
export const implementAssignSubjectMethods = (assignedSub, subTable) => {
    let assigned = assignedSub
    let subjectTable = subTable
    $(document).on("click", ".edit-as-btn, #edit-as-btn", function() {
        // show
        console.log(assigned);
        // subjectTable.bootstrapTable('checkBy', {field: 'sub_code', values: assigned})
    })

    $(document).on("click", "#cancel-as-btn", () => {
        subjectTable.bootstrapTable("uncheckAll")
    })
    // $("#save-as-btn").click(() => $("#as-form").submit())
    $(document).on("submit", "#as-form", function(e) {
        e.preventDefault()
        showSpinner()
        let form = $(this)
        let formData = form.serializeArray()
        let selection = subjectTable.bootstrapTable("getSelections")
        let newSubCodes = selection.map(e => {return e.sub_code})
        let newSubjects = newSubCodes.map(value => {return {name: "subjects[]", value}})
        formData.push(...newSubjects)
        $.post("action.php", formData, function() {
            assigned = newSubCodes
            let emptyMsg = $("#empty-as-msg")
            let emptySubjectCon = () => $(".assigned-sub-con a").remove()
            if (assigned.length == 0) {
                emptyMsg.removeClass("d-none")
                emptySubjectCon()
            }
            else {
                emptyMsg.addClass("d-none")
                emptySubjectCon()
                selection.forEach(e => {
                    $(".assigned-sub-con").append(`<a target='_blank' href='subject.php?sub_code=${e.sub_code}' class='list-group-item list-group-item-action' aria-current='true'>
                                            <div class='d-flex w-100 justify-content-between'>
                                                <p class='mb-1'>${e.sub_name}</p>
                                                <small>${e.sub_type}</small>
                                            </div>
                                            <small class='mb-1 text-secondary'><b>${e.for_grd_level}</b> | ${e.sub_code}</small>
                                        </a>`)
                })
            }
            form.trigger("reset")
            $("#as-modal").modal("hide")
            hideSpinner()
            showToast('success', "Handled subjects successfully updated")
        })
    })

    // clear button for search subject input in the as-modal
    $(document).on("click", ".clear-table-btn", () => {
        showSpinner()
        subjectTable.bootstrapTable("resetSearch")
        hideSpinner()
    })
}

/** Assign subject class to faculty */

export const implementAssignSubjectClassMethods = (ASSIGNEDSCID, SCID) => {

    $(document).on("click", "#add-sc-option", e => {
        e.preventDefault()
        $("#add-sc-modal").modal("show")
    })

    $("#add-sc-modal").on("show.bs.modal", function() {
        // subClassTable.bootstrapTable('showLoading')
        showSpinner()
        $(SCID).bootstrapTable('uncheckAll')
        setTimeout( () => {
            $(SCID).bootstrapTable('resetView')
            // subClassTable.bootstrapTable('hideLoading')
            hideSpinner()
        }, 1000);
    })

    // $(document).on('click', '#assigned-sc-btn', function (e) {
    //     e.preventDefault()
    //
    //     $('#sc-form').submit()
    // })

    const toggleSCFilterActive = (element) => {
        filterDropDownActiveEvent("ul[aria-labelledby='sc-filter']", element)
    }

    $(document).on("click", "#all-btn", function() {
        showSpinner()
        toggleSCFilterActive(this)
        $(SCID).bootstrapTable('filterBy', {})
        hideSpinner()
    })

    $(document).on("click", ".clear-table-btn", () => {
        showSpinner()
        $(SCID).bootstrapTable("resetSearch")
        hideSpinner()
    })

    $(document).on("click", "#available-btn", function() {
        showSpinner()
        toggleSCFilterActive(this)
        $(SCID).bootstrapTable('filterBy', {status: 'available'})
        hideSpinner()
    })

    $(document).on("click", "#unavailable-btn", function() {
        showSpinner()
        toggleSCFilterActive(this)
        $(SCID).bootstrapTable('filterBy', {status: 'taken'})
        hideSpinner()
    })

    const moveData = (dataList, origin, destination) => {
        dataList.forEach(e => moveElement(e.sub_class_code, origin, destination))
    }

    const moveElement = (uniqueID, origin, destination) => {
        let color = (origin == ASSIGNEDSCID) ? 'success' : 'warning'
        // change value of selected row
        let selected = $(origin).bootstrapTable('getRowByUniqueId', uniqueID)
        selected.teacher_id = null
        selected.status = "available"
        selected.statusImg = `<span class='badge available'><div class='bg-${color} rounded-circle me-2' style='width: 10px; height: 10px;' title='Available'></div></span>`

        // remove selected from the table
        $(origin).bootstrapTable('removeByUniqueId', uniqueID)
        // prepend data to destination table
        $(destination).bootstrapTable('prepend', selected)
    }

    $(document).on("submit", "#sc-form", function (e) {
        e.preventDefault()
        showSpinner()

        let form, formData, selections, subClasses
        form = $(this)
        selections = $(SCID).bootstrapTable('getSelections')

        if (form.attr('data-page') === 'profile') { // allow database update on profile page only
            // form page
            formData = form.serializeArray()
            subClasses = selections.map(e => {
                return { name: "sub_class_code[]", value: e.sub_class_code }
            })
            formData.push(...subClasses)
            $.post("action.php", formData)
        }

        moveData(selections, SCID, ASSIGNEDSCID)

        $(ASSIGNEDSCID).bootstrapTable("uncheckAll")
        $("#add-sc-modal").modal("hide")
        hideSpinner()
    })

    let createUnassignForm = () => {
        let formData = new FormData()
        formData.append("action", "unassignSubClasses")
        formData.append("teacher_id", teacherID)
        return formData
    }

    $(document).on("click", ".unassign-btn", function (e) {
        e.preventDefault()
        showSpinner()
        let subCode = $(this).attr("data-sc-code")
        let formData = createUnassignForm()
        formData.append("sub_class_code[]", subCode)

        $.ajax({
            url: "action.php",
            data: formData,
            cache: false,
            contentType: false,  // sending form data object will create error if content type and process data is not set to false
            processData: false,
            method: 'POST',
            success: function (data) {
                moveData(subCode, ASSIGNEDSCID, SCID)
                // $(SCID).bootstrapTable()
            }
        })
        hideSpinner()
    })

    /** Unassign all */
    $(document).on('click', '.unassign-selected-btn', (e) => {
        e.preventDefault()
        showSpinner()
        let selection = $(ASSIGNEDSCID).bootstrapTable("getSelections")
        if (selection.length == 0) return showToast("danger", "Please select a subject class first")
        let formData = createUnassignForm()
        let scCodes = []
        selection.forEach(e => {
            let scCode = e.sub_class_code
            scCodes.push(scCode)
            let data = ["sub_class_code[]", scCode]
            formData.append(...data)
        })
        $.ajax({
            url: "action.php",
            data: formData,
            cache: false,
            contentType: false,  // sending form data object will create error if content type and process data is not set to false
            processData: false,
            method: 'POST',
            success: function (data) {
                scCodes.forEach(e => moveData(e, ASSIGNEDSCID, SCID))
                // $(SCID).bootstrapTable()
            }
        })
        hideSpinner()
    })
}

/** Search List */
export const searchKeyBindEvent = (searchInputSelector, listContainer) => {
    $(document).on("keyup", searchInputSelector, function() {
        showSpinner()
        var value = $(this).val().toLowerCase()
        $(`${listContainer} li`).filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        })
        hideSpinner()
    })
}

export const filterDropDownActiveEvent = (ulSelector, element) => {
    $(`${ulSelector} li a`).not(element).removeClass('active')
    $(element).addClass('active')
}