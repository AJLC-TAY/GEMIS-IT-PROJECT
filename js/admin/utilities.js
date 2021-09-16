/** Assign Subject to Faculty Methods */
export const implementAssignSubjectMethods = (assignedSub, subTable) => {
    let assigned = assignedSub      // list of assigned subjects
    let subjectTable = subTable     // the table where the data of assigned subjects will be rendered

    $(document).on("click", ".edit-as-btn, #edit-as-btn", function() {
        // show
        console.log(assigned);
        // subjectTable.bootstrapTable('checkBy', {field: 'sub_code', values: assigned})
    })

    // uncheck all records if cancel button is clicked
    $(document).on("click", "#cancel-as-btn", () => {
        subjectTable.bootstrapTable("uncheckAll")
    })

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
        showSpinner()
        $(SCID).bootstrapTable('uncheckAll')
        setTimeout( () => {
            $(SCID).bootstrapTable('resetView')
            hideSpinner()
        }, 1000);
    })

    /** Filter button of the Subject Class table */
    $(document).on('click', '.filter-item', function (e) {
        e.preventDefault()
        let subClassTable = $(SCID)
        subClassTable.bootstrapTable('showLoading')
        // Add active state to the button and remove active state from other options
        $(this).addClass('active')
        $(this).closest("ul").find("li a").not($(this)).removeClass('active')

        // Get value of the button to know what to filter
        let value = $(this).attr('data-value')
        console.log(value)
        let filterData = {}
        if (value !== '*') filterData.status = [value]
        else subClassTable.bootstrapTable('refresh')

        subClassTable.bootstrapTable('filterBy', filterData)
                     .bootstrapTable('hideLoading')
    })

    $(document).on("click", ".clear-table-btn", () => {
        $(SCID).bootstrapTable("showLoading")
               .bootstrapTable("resetSearch")
               .bootstrapTable("hideLoading")
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

        console.log(form.attr('data-page'));
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

    const createUnassignForm = () => {
        let formData = new FormData()
        formData.append("action", "unassignSubClasses")
        formData.append("teacher_id", teacherID)
        return formData
    }

    $(document).on("click", ".unassign-btn", function (e) {
        e.preventDefault()
        let asSCTable = $(ASSIGNEDSCID);
        asSCTable.bootstrapTable('showLoading')

        let subCode = $(this).attr("data-sc-code")

        if (asSCTable.attr('data-page') === 'profile') {
            let formData = createUnassignForm()
            formData.append("sub_class_code[]", subCode)

            $.ajax({
                url: "action.php",
                data: formData,
                cache: false,
                contentType: false,  // sending form data object will create error if content type and process data is not set to false
                processData: false,
                method: 'POST'
            })
        }

        moveElement(subCode, ASSIGNEDSCID, SCID)
        setTimeout(() => asSCTable.bootstrapTable('hideLoading'), 300)
    })

    /** Unassign all */
    $(document).on('click', '.unassign-selected-btn', (e) => {
        e.preventDefault()
        showSpinner(ASSIGNEDSCID, true)
        let selection = $(ASSIGNEDSCID).bootstrapTable("getSelections")
        if (selection.length === 0) {
            hideSpinner(ASSIGNEDSCID, true)
            return showToast("danger", "Please select a subject class first")
        }
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
        hideSpinner(ASSIGNEDSCID, true)
    })
}

/** Search List */
export const searchKeyBindEvent = (searchInputSelector, listContainer) => {
    $(document).on("keyup", searchInputSelector, function() {
        let noResultMsg = $(".no-result-msg")
        // hide no result message and cards
        noResultMsg.hide()

        let page = $(listContainer).attr('data-page')
        $(`${listContainer} .tile`).hide()
        // show loading status
        showSpinner(`#${page}-spinner`)
        setTimeout(() => {
            var value = $(this).val().toLowerCase()
            let match = []
            $(`${listContainer} .tile`).filter(function() {
                let thereIsMatch = $(this).text().toLowerCase().indexOf(value) > -1
                match.push(thereIsMatch)
                $(this).toggle(thereIsMatch)
            })

            if (!match.includes(true)) {
                noResultMsg.show()
            }
            hideSpinner(`#${page}-spinner`)
        }, 500)
    })
}
