preload('#curr-management', '#school-yr')

let onPostBodyOfTable = () => {

}

const tableSetup = {
    url:                'getAction.php?data=school_year',
    method:             'GET',
    uniqueId:           'id',
    idField:            'id',
    height:             425,
    maintainMetaDat:    true,       // set true to preserve the selected row even when the current table is empty
    search:             true,
    searchSelector:     '#search-input',
    pageSize:           10,
    pagination:         true,
    pageList:           "[10, 25, 50, All]",
    paginationParts:    ["pageInfoShort", "pageSize", "pageList"],
    // onPostBody:         onPostBodyOfTable
}

var syTable = $("#table").bootstrapTable(tableSetup)

/**
 * Displays the current values of the row to the given html tag
 * @param {Number} id        School Year ID, which is also the unique ID of the row
 * @param {Object} row       Record containing all html elements
 * @param {String} inputs    Receiving HTML tag of the current values
 */
const setCurrentValuesToInput = (id, row, inputs = "input") => { // id of row
    let data, grade, quarter, semester, inputsToDisplay, inputsToHide
    // get values from table
    data = syTable.bootstrapTable("getRowByUniqueId", id)
    console.log(id, "\n")
    console.log(data)
    grade = data.current_grd_val
    quarter = data.current_qtr_val
    semester = data.current_sem_val

    // show all the elements with the provided html tag
    inputsToDisplay = row.find(inputs)
    inputsToDisplay.removeClass("d-none")

    // hide select elements if the provided tag is input, or vise versa
    inputsToHide = (inputs == "select") ? "input[class*='form-control']" : "select"
    row.find(inputsToHide).addClass("d-none")

    if (inputs == "input") {
        const SELECTOR = `select[data-id=${id}]`
        let grd = $(`${SELECTOR} [value=${grade}]`).text()
        let qtr = $(`${SELECTOR} [value=${quarter}]`).text()
        let sem = $(`${SELECTOR} [value=${semester}]`).text()

        const INPUT = `input[data-id=${id}]`
        $(`${INPUT} [data_name=grade-level]`).val(grd)
        $(`${INPUT} [data_name=quarter]`).val(qtr)
        $(`${INPUT} [data_name=semester]`).val(sem)
        return
    }

    inputsToDisplay.eq(0).val(grade)
    inputsToDisplay.eq(1).val(quarter)
    inputsToDisplay.eq(2).val(semester)
}

$(function() {
    $(document).on("click", ".edit-btn", function() {
        showSpinner()
        let element, row, id
        element = $(this)
        id = element.attr("data-id")                        // store the id of the row
        element.toggleClass('d-none')                       // hide edit button
        element.next(".edit-options").toggleClass('d-none') // show the edit options div, which contains the cancel and save buttons
        row = element.closest("tr")                         // get the row
        $(".edit-btn").prop("disabled", true)               // disable other edit buttons

        setCurrentValuesToInput(id, row, "select")
        hideSpinner()
    })

    $(document).on("click", ".cancel-btn", function() {
        showSpinner()
        let element, id, row, editOptions
        element = $(this)
        id = element.attr("data-id")
        row = element.closest("tr")
        $(".edit-btn").prop("disabled", false)              // enable all edit button

        setCurrentValuesToInput(id, row)
        editOptions = element.closest(".edit-options")      // hide the edit options
        editOptions.toggleClass("d-none")
        editOptions.prev(".edit-btn").toggleClass("d-none")
        hideSpinner()
    })

    $(document).on("click", ".save-btn", function() {
        showSpinner()
        let element, id, record, selectInputs, formData
        element = $(this)
        id = element.attr("data-id")
        record = element.closest("tr")
        selectInputs = record.find("select")

        // add additional post variables to form data
        formData = selectInputs.serialize()
        formData += `&sy_id=${id}`
        formData += "&action=editSY"

        $.post("action.php", formData)

        let newGradeVal, newQtrVal, newSemVal
        newGradeVal = selectInputs.eq(0).val() // .eq() is like getting the element through its index
        newQtrVal = selectInputs.eq(1).val()
        newSemVal = selectInputs.eq(2).val()

        // update the current values in the record of bootstrap table with
        // the given row ID, and row values
        $("#table").bootstrapTable('updateByUniqueId', {
            id,
            row: {
                current_grd_val: newGradeVal,
                current_qtr_val: newQtrVal,
                current_sem_val: newSemVal
            }}
        )

        let grade, quarter, semester, inputsToDisplay
        // find select inputs get their labels and set them to the input html tags
        grade = selectInputs.eq(0).find(`[value='${newGradeVal}']`).text()
        quarter = selectInputs.eq(1).find(`[value='${newQtrVal}']`).text()
        semester = selectInputs.eq(2).find(`[value='${newSemVal}']`).text()

        inputsToDisplay = $(`input[class*='form-control'][data-id='${id}']`)
        inputsToDisplay.eq(0).val(grade)
        inputsToDisplay.eq(1).val(quarter)
        inputsToDisplay.eq(2).val(semester)

        $(".edit-btn").prop("disabled", false)          // enable all edit buttons
        hideSpinner()
        showToast('success', 'Successfully updated!')
    })

    $(document).on("click", "[name='enrollment']", function() {
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
            // showToast("success", "Enrollment status changed")
        })

        hideSpinner()
    })

    hideSpinner()
})