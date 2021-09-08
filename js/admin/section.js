preload('#enrollment', '#section')

let tableSetup = {
    method:             'GET',
    maintainMetaDat:    true,       // set true to preserve the selected row even when the current table is empty
    clickToSelect:      true,
    pageSize:           10,
    pagination:         true,
    pageList:           "[10, 25, 50, All]",
    paginationParts:    ["pageInfoShort", "pageSize", "pageList"]
}

let studSetUp = {...tableSetup}
studSetUp.url = "getAction.php?" + (isViewPage ? `data=student&section=${sectionCode}` : 'data=section')
studSetUp.idField = 'lrn'
studSetUp.uniqueId = 'lrn'
studSetUp.height = 300

let studentTable = $("#table").bootstrapTable(studSetUp)
let subjectTable

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

let addAnother = false


$(function() {
    $('#add-btn').click(function() {
        $("#add-modal").modal("show")
    })

    $(".submit-another").click(function(e) {
        e.preventDefault()
        addAnother = true
        $("#section-form").submit()
    })
    $('#section-form').submit(function(e) {
        e.preventDefault()
        let form = $(this)
        $.post("action.php", form.serializeArray(), function(data) {
            // console.log(JSON.parse(data))
            form.trigger("reset")
            $("#table").bootstrapTable('refresh')
            if (!addAnother) { 
                $("#add-modal").modal("hide")
                addAnother = false
            }
            // $("#add-modal").dispose()
            showToast("success", "Section successfully added")
        })
    })

    $('#edit-btn').click(function(e) {
        e.preventDefault()
        $("#empty-msg").addClass("d-none")
        $(".edit-opt").removeClass("d-none")
        $(this).addClass('d-none')
        $("#section-edit-form").find('input').each(function() {
            let input = $(this)
            // tempData.push(input.val())
            input.removeClass('d-none')
            input.prop("disabled", false)
            $("a.link").addClass("d-none")
        })
    })

    $('#cancel-btn').click(function(e) {
        e.preventDefault()
        $(".edit-opt").addClass("d-none")
        $('#edit-btn').toggleClass('d-none')

        if (!adviser) $("#empty-msg").removeClass("d-none")     // show empty message if no assigned adviser originally 

        let inputs = $("#section-edit-form").find('input')
        let maxInput = inputs.eq(0)
        maxInput.val(tempData[0])
        maxInput.prop("disabled", true)

        let teacherInput = inputs.eq(1)
        teacherInput.val(tempData[1])
        // teacherInput.addClass("d-none")
        
        $("a.link").removeClass("d-none")
    })

    $("#section-edit-form").submit(function(e) {
        e.preventDefault()
        showSpinner()
        let form = $(this)
        let formData= form.serializeArray()
        $.post("action.php", formData, function(data) {
            let teacherID, inputs, teacherInput, teacherLink
            data = JSON.parse(data)

            inputs = form.find("input")
            inputs.eq(0).prop("disabled", true)

            teacherID = formData[1].value
            teacherLink = $("a.link")
            if (teacherID.trim().length == 0) {
                // $("#empty-msg").removeClass("d-none")
                // teacherLink = $("a.link")
                // teacherLink.attr("href", "")
                // teacherLink.html("")
                // teacherLink.addClass("d-none")
            } else {
                teacherInput = inputs.eq(1)
                teacherInput.val(teacherID)

                teacherLink.attr("href", `faculty.php?id=${teacherID}`)
                let name = $(`#adviser-list option[value*='${teacherID}']`).html()
                name = "Teacher " + name.substring(name.indexOf("-") + 2)
                teacherLink.html(name)
                teacherLink.removeClass("d-none")

            }
            location.replace(`section.php?code=${data.section}`)
          

            // $("#edit-btn").toggleClass('d-none')
            // $(".edit-opt").addClass('d-none')

            // tempData = []
            // hideSpinner()
            // showToast("success", "Successfully updated section")
        })
    })

    /** Clears the teacher input if clear button is clicked */
    $("#adviser-clear-btn").click(function(e) {
        e.preventDefault()
        $("input[name='adviser']").val("")
    })

    $("#transfer-btn").click(function() {
                
    })

    /** Specific subject */
    $("#add-subject-btn").click(function() {
        let subSetUp = {...tableSetup}
        subSetUp.url = "getAction.php?data=subjects"
        subSetUp.idField = 'sub_code'
        subSetUp.uniqueId = 'sub_code'
        subSetUp.height = 300
        subSetUp.search = true
        subSetUp.searchSelector = "#search-sub-input"

        subjectTable = $("#subject-table").bootstrapTable(subSetUp)

    })

     // clear button for search subject input in the as-modal
     $(document).on("click", ".clear-table-btn", () => {
        showSpinner()
        $("#subject-table").bootstrapTable("resetSearch")
        hideSpinner()
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