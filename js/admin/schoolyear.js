import {Table} from "./Class.js"
    
let tableId, url, method, id, search, searchSelector, height

tableId = '#table'
url = 'getAction.php?data=school_year'
method = 'GET'
id = 'id'
search = true
searchSelector = '#search-input'
height = 425

let onPostBodyOfTable = () => {
    $(".view-btn").click(function() {
        let data = $("#table").bootstrapTable("getRowByUniqueId", $(this).attr("data-id"))
        console.log(data)
        let modal = $("#view-modal")
        modal.find("#school-year").html(data.sy_year)
        modal.find("#enrollment-status").click()
        modal.modal("show")
    })

    $(".edit-btn").click(function() {
        showSpinner()
        let element = $(this)
        element.toggleClass('d-none')
        element.next(".edit-options").toggleClass('d-none')
        let row = element.closest("tr")
        row.find("select").prop("disabled", false)
        $("input.switch").removeClass("d-none")

        hideSpinner()
    })

    const toggleEditOnInputs = (element, selectInputs) => {
        selectInputs.prop("disabled", true)
        $("input.switch").removeClass("d-none")

        let editOptionsCon = element.closest(".edit-options")
        editOptionsCon.toggleClass('d-none')
        editOptionsCon.prev(".edit-btn").toggleClass('d-none')
        hideSpinner()
    }

    $(".cancel-btn").click(function() {
        showSpinner()
        let element = $(this)
        let row = element.closest("tr")
        let selectInputs = row.find("select")

        // get original data 
        let data = $("#table").bootstrapTable("getRowByUniqueId", $(this).attr("data-id"))
        let oldGrd = data.current_grd_val
        let oldQtr = data.current_qtr_val
        let oldSem = data.current_sem_val
        data = [oldGrd, oldQtr, oldSem]

        // put original data to options
        let i = 0
        selectInputs.each(function() {      // order of select inputs: grade, quarter, semester
            $(this).val(data[i])
            i++
        })

        toggleEditOnInputs(element, selectInputs)
    })

    $(".save-btn").click(function() {
        showSpinner()
        let element = $(this)
        let id = element.attr("data-id")
        let row = element.closest("tr")
        let selectInputs = row.find("select")
        let formData = selectInputs.serialize()
        formData += `&sy_id=${id}`
        formData += "&action=editSY"

        // alert(formData)
        $.post("action.php", formData)
        
        toggleEditOnInputs(element, selectInputs)
        hideSpinner()
        showToast('success', 'Successfully updated!')
    })

    $("[name='enrollment']").click(function() {
        showSpinner()
        let statusE = $(this).next(".status")
        let syID = $(this).attr("data-id")
        let formData = `sy_id=${syID}&action=editEnrollStatus`
        if ($(this).is(":checked")) {
            $(this).attr("title", "Turn off enrollment")
            statusE.html("On-going")
            formData += "&enrollment=on"
        } else {
            $(this).attr("title", "Turn on enrollment")
            statusE.html("Ended")
        }
        console.log(formData)
        $.post("action.php", formData, function() {
            showToast("success", "Enrollment successfully updated!")
        })

        hideSpinner()
    })
}

var sy_table = new Table(tableId, url, method, id, id, height, search, searchSelector, null, onPostBodyOfTable)

preload('#curr-management', '#school-yr')
$(function() {
    $("#enrollment-switch").click(function() {
        let enrollStat = $("#enrollment-status")
        if ($(this).is(":checked")) enrollStat.html("System will start accepting enrollees once submitted")
        else enrollStat.html("No enrollment temporarily")
    })
    hideSpinner()
})