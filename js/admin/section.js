import {Table} from "./Class.js"
    
let tableId, url, method, id, search, searchSelector, height

tableId = '#table'
url = "getAction.php?" + (isViewPage ? `data=student&section=${sectionCode}` : 'data=section')
method = 'GET'
id = 'code'
search = true
searchSelector = '#search-input'
height = 425

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

let sectionTable = new Table(tableId, url, method, id, id, height, search, searchSelector)
let addAnother = false
let tempData = []

preload('#enrollment', '#section')
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
            tempData.push(input.val())
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
        tempData = []
    })

    $("#section-edit-form").submit(function(e) {
        e.preventDefault()
        showSpinner()
        let form = $(this)
        let formData= form.serializeArray()
        // $.post("action.php", formData, function(data) {
            let teacherID, inputs, teacherInput, teacherLink

            inputs = form.find("input")
            inputs.eq(0).prop("disabled", true)

            teacherID = formData[1].value
            teacherLink = $("a.link")
            if (teacherID.trim().length == 0) {
                $("#empty-msg").removeClass("d-none")
                teacherLink = $("a.link")
                teacherLink.attr("href", "")
                teacherLink.html("")
                teacherLink.addClass("d-none")
            } else {
                teacherInput = inputs.eq(1)
                teacherInput.val(teacherID)

                teacherLink.attr("href", `faculty.php?id=${teacherID}`)
                let name = $(`#adviser-list option[value*='${teacherID}']`).html()
                name = "Teacher " + name.substring(name.indexOf("-") + 2)
                teacherLink.html(name)
                teacherLink.removeClass("d-none")
            }
          

            $("#edit-btn").toggleClass('d-none')
            $(".edit-opt").addClass('d-none')

            tempData = []
            hideSpinner()
            showToast("success", "Successfully updated section")
        // })
    })

    /** Clears the teacher input if clear button is clicked */
    $("#adviser-clear-btn").click(function(e) {
        e.preventDefault()
        $("input[name='adviser']").val("")
    })

    $("#transfer-btn").click(function() {
                
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