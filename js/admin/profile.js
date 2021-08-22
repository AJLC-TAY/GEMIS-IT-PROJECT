import { addSubjectFn, removeAllBtnFn, removeSubjectBtnFn, 
         selectAll, setSubjectSelected, getSubjectSelected } from "./utilities.js"

// Deleted roles
let rolesDel = []
let rolesTmp = []

// Department
let inputData

// Subjects Handled
setSubjectSelected(assigned.map(e => e.sub_code)) // we have to set the selected subject list towards the  utilities.js 

$(function () {
    preload('#faculty')
    /** Initialization */
    // Modal
    let modalE = $('.modal')
    // Toast 
    let toastList = $('.toast').map(function (toastEl) {
        return new bootstrap.Toast(toastEl)
    })
    // Popover for the instruction
    let popover = new bootstrap.Popover($("#instruction"))

    // If rolesTmp is 0, empty message is shown, else hidden
    let checkRolesTagForMsg = () => {
        let emptyMsg = $("#role-empty-msg")
        if (rolesTmp.length == 0) emptyMsg.removeClass('d-none')
        else emptyMsg.addClass('d-none')
    }

    /** Role Methods */
    // Edit roles
    $("#role-edit-btn").click(function(e) {
        e.preventDefault()
        // hide edit button
        $(this).addClass('d-none') 

        rolesTmp = [...roles] // clone current roles to roles temp
        rolesDel = []         // initialize roles to delete

        // show 
        $("#role-section").addClass("border")
        $(".role-to-delete-btn button, #role-option-tag-con, #role-decide-con").removeClass('d-none')
        checkRolesTagForMsg()
    })

    // Add role tag
    $("#role-option-tag-con button").click(function() {
        let element = $(this)
        element.addClass("d-none")

        let value = element.attr('data-value')
        rolesTmp.push(value)                                                // value is pushed to temporary roles
        $(`.role-to-delete-btn[data-value=${value}]`).removeClass('d-none') // icon inside delete con is shown
        checkRolesTagForMsg()

        // console.log("*****Add clicked******")
        // console.log("Temp Roles:", rolesTmp)
        // console.log("Roles to Delete:", rolesDel)
    })

    // delete role tag
    $(".role-to-delete-btn button").click(function () {
        let element = $(this).closest("div")
        element.addClass('d-none')

        let value = element.attr('data-value')
        if (roles.includes(value)) rolesDel.push(value)         // if the removed role tag exist in 
                                                                // the current row, add it to roles to delete array
        rolesTmp.splice(rolesTmp.indexOf(value), 1)             // then remove it from the temporary roles
       
        checkRolesTagForMsg()
        $(`#role-option-tag-con [data-value=${value}]`).removeClass('d-none')   // show add role tag button

        // console.log("*****Delete clicked******")
        // console.log("Temp Roles:", rolesTmp)
        // console.log("Roles to Delete:", rolesDel)
       
    })

    // cancel role edit
    $("#role-cancel-btn").click(function(e) {
        e.preventDefault()
        // hide
        $("#role-section").removeClass("border")
        $("#role-option-tag-con, #role-decide-con").addClass('d-none')

        // show
        $("#role-edit-btn").removeClass("d-none")
        
        // for each element in the current roles, their corresponding 
        // tag will be shown but their delete icon is hidden
        roles.forEach(e => {
            let eHTML = $(`.role-to-delete-btn[data-value=${e}]`)
            eHTML.find("button").addClass('d-none')
            eHTML.removeClass('d-none')
        })

        // for each element in the roles to be delete, their add button tag are hidden
        rolesDel.forEach(e => {
            let eHTML = $(`#role-option-tag-con [data-value=${e}]`)
            eHTML.addClass('d-none')
        })

        // temporary arrays are set to empty
        rolesDel = []
        rolesTmp = []

        // console.log("*****Cancel clicked******")
        // console.log("Temp Roles:", roles)
        // console.log("Temp Roles:", rolesTmp)
        // console.log("Roles to Delete:", rolesDel)
    })

    $("#role-save-btn").click(() => $("#role-form").submit())

    $("#role-form").submit(function(e) {
        e.preventDefault()
        showSpinner()
        var formData = $(this).serialize()

        // add each element in the temporary roles to the formdata
        rolesTmp.forEach(role => {
            formData += encodeURI(`&access[]=${role}`)
        })

        $.post('action.php', formData, function(){
            // hide
            $("#role-section").removeClass("border")
            $("#role-option-tag-con, #role-decide-con").addClass('d-none')
            // the delete icon of tags corresponding to each of the 
            // element in the temporary roles are hidden 
            rolesTmp.forEach(e=> {
                let eHTML = $(`.role-to-delete-btn[data-value=${e}]`)
                eHTML.find("button").addClass('d-none')
            })

            // show
            $("#role-edit-btn").removeClass('d-none')
            checkRolesTagForMsg()

            // override roles by cloning the temprary roles
            roles = [...rolesTmp]
            hideSpinner()
            showToast('success', "Roles successfully updated")
        })
    })

    /** Deparment Methods */
    $("#dept-edit-btn").click(function() {
        let input = $("#dept-input")
        // Get input value and store it to the input data
        inputData = input.val()
        // empty input if no department is set
        if (!deptExist) input.val("")
        
        // show
        $("#dept-section").addClass("border")
        $("#dept-decide-con, #dept-clear-btn, .dept-ins").removeClass('d-none')
        input.removeClass('d-none')
        input.prop('readonly', false)
        
        // hide
        $(this, "#dept-empty-msg").addClass('d-none')
    })

    $("#dept-clear-btn").click(function(e) {
        e.preventDefault()
        $("#dept-input").val('')
    })

    $("#dept-cancel-btn").click(function(e) {
        e.preventDefault()
        
        let input = $("#dept-input")
        input.attr('readonly', true)
        input.val(inputData)

        // hide
        $("#dept-section").removeClass("border")
        $("#dept-decide-con, #dept-clear-btn, .dept-ins").toggleClass('d-none')
        
        // show
        $("#dept-edit-btn, #dept-empty-msg").fadeIn()
    })


    $("#dept-save-btn").click(() => $("#dept-form").submit())
    $("#dept-form").submit(function(e) {
        e.preventDefault()
        showSpinner()

        $.post("action.php?", $(this).serialize(), function() {
            // hide
            $("#dept-section").removeClass("border")
            $("#dept-decide-con, #dept-clear-btn, .dept-ins").toggleClass('d-none')

            let input = $("#dept-input")
            input.attr('readonly', true)
            // empty input if no department is set
            if (input.val().length == 0) {
                input.val("No department set")
                deptExist = false 
            } else deptExist = true
            
            // show
            $("#dept-edit-btn, #dept-empty-msg").toggleClass('d-none')
            
            hideSpinner()
            showToast('success', "Department successfully updated")
        })
    })

    /** Subject Methods */
    $(document).on('click', '.add-subject', addSubjectFn)
    $(document).on('click', '.remove-all-btn', removeAllBtnFn)
    $(document).on('click', '.remove-btn', removeSubjectBtnFn)
    $(document).on('click', '#selectAll', selectAll)

    const addEmptySubjectMsg = () => $("#as-table tbody").html("<tr id='emptyMsg' class='text-center'><td colspan='5'>No subject set</td></tr>")
    const hideEditSubjectElements = () => $(`.edit-con, .finder-con, .decision-as-con, .remove-btn, .view-btn, .cb-con, #as-table thead tr th:first-child`).toggleClass("d-none")

    $("#edit-as-btn").click(function() {
        // show
        $(this).closest("div").toggleClass("d-none")
        $(`.finder-con, .remove-btn, .view-btn, .decision-as-con, 
           #as-table tr th:first-child, #as-table tr td:first-child`).toggleClass('d-none')
    })

    $("#cancel-as-btn").click(() => {
        let con = $("#as-table tbody")
        con.empty()
        setSubjectSelected([])

        if (assigned.length == 0) {
            addEmptySubjectMsg()
        } else {
            assigned.forEach(e => {
                let code = e.sub_code
                // view button has d-none class since it will be toggled in the hideEditSubjectElements function; 
                con.append(`<tr class='text-center content'>
                    <td class='cb-con' scope='col'><input type='checkbox' value='${code}' /></td>
                    <td scope='col'><input type='hidden' name='subjects[]' value='${code}'/>${code}</td>
                    <td scope='col'>${e.sub_name}</td>
                    <td scope='col'>${e.sub_type}</td>
                    <td scope='col'>
                        <button data-value='${code}' class='remove-btn btn btn-sm btn-danger m-auto shadow-sm' title='Remove'><i class='bi bi-x-square'></i></button>
                        <a href='subject.php?sub_code=${code}&state=view' role='button' class='view-btn btn btn-sm btn-primary m-auto shadow-sm d-none' title='View subject'><i class='bi bi-eye'></i></a>
                    </td>
                </tr>`)
            })
        }
        
        hideEditSubjectElements()
    })
    $("#save-as-btn").click(() => $("#as-form").submit())
    $("#as-form").submit(function(e) {
        e.preventDefault()
        showSpinner()
        $.post("action.php", $(this).serialize(), function() {
            assigned = getSubjectSelected()
            if (assigned.length == 0) addEmptySubjectMsg()
            hideEditSubjectElements()
            hideSpinner()
            showToast('success', "Handled subjects successfully updated")
        })
    })

    hideSpinner()
})