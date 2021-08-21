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
    let checkRolesTagForMsg = () => {
        let emptyMsg = $("#role-empty-msg")
        if (rolesTmp.length == 0) emptyMsg.removeClass('d-none')
        else emptyMsg.addClass('d-none')
    }

    /** Role Methods */
    // Edit roles
    $("#role-edit-btn").click(function(e) {
        e.preventDefault()
        rolesTmp = [...roles]
        rolesDel = []
        console.log("****Edit clicked********")
        console.log("Roles:", roles)
        // show 
        $("#role-section").addClass("border")
        $(".role-to-delete-btn button").removeClass('d-none')
        $("#role-option-tag-con").removeClass('d-none')
        $("#role-decide-con").removeClass('d-none')
        checkRolesTagForMsg()

        // hide
        $(this).addClass('d-none') 
    })

    // Add role tag
    $("#role-option-tag-con button").click(function() {
        let element = $(this)
        let value = element.attr('data-value')
        rolesTmp.push(value)
        // console.log("*****Add clicked******")
        // console.log("Temp Roles:", rolesTmp)
        // console.log("Roles to Delete:", rolesDel)
        $(`.role-to-delete-btn[data-value=${value}]`).removeClass('d-none')
        checkRolesTagForMsg()
        element.addClass("d-none")
    })

    // delete role tag
    $(".role-to-delete-btn button").click(function () {
        let element = $(this).closest("div")
        let value = element.attr('data-value')
        if (roles.includes(value)) rolesDel.push(value)
        rolesTmp.splice(rolesTmp.indexOf(value), 1)
        // console.log("*****Delete clicked******")
        // console.log("Temp Roles:", rolesTmp)
        // console.log("Roles to Delete:", rolesDel)
        if (rolesTmp == 0) $("#role-empty-msg").removeClass('d-none')

        $(`#role-option-tag-con [data-value=${value}]`).removeClass('d-none')
        element.addClass('d-none')
    })


    // let hideRoleEditButtons = function () {
    //     toggleElements($("#role-save-btn, #role-edit-btn, .rounded-pill i"), 'd-none')
    //     if (roles.length == 0) $("#role-empty-msg").removeClass('d-none')
    //     $("#role-add-btn").addClass('d-none')
    // }


    $("#role-cancel-btn").click(function(e) {
        e.preventDefault()
        // hide
        $("#role-section").removeClass("border")
        $("#role-option-tag-con").addClass('d-none')
        $("#role-decide-con").addClass('d-none')

        // show
        $("#role-edit-btn").removeClass("d-none")
        // console.log("*****Cancel clicked******")
        // console.log("Temp Roles:", roles)
        // console.log("Temp Roles:", rolesTmp)
        // console.log("Roles to Delete:", rolesDel)
        
        roles.forEach(e=> {
            let eHTML = $(`.role-to-delete-btn[data-value=${e}]`)
            eHTML.find("button").addClass('d-none')
            eHTML.removeClass('d-none')
        })


        rolesDel.forEach(e => {
            let eHTML = $(`#role-option-tag-con [data-value=${e}]`)
            eHTML.addClass('d-none')
        })
        rolesDel = []
        rolesTmp = []
    })

    $("#role-save-btn").click(() => $("#role-form").submit())

    $("#role-form").submit(function(e) {
        e.preventDefault()
        var formData = $(this).serialize()

        rolesTmp.forEach(role => {
            formData += encodeURI(`&access[]=${role}`)
        })

        $.post('action.php', formData, function(){
            // hide
            $("#role-section").removeClass("border")
            $("#role-option-tag-con").addClass('d-none')
            $("#role-decide-con").addClass('d-none')
            rolesTmp.forEach(e=> {
                let eHTML = $(`.role-to-delete-btn[data-value=${e}]`)
                eHTML.find("button").addClass('d-none')
            })

            // show
            $("#role-edit-btn").removeClass('d-none')

            if (rolesTmp.length == 0) $("#role-empty-msg").removeClass("d-none")
            roles = [...rolesTmp]
            showToast('success', "Roles successfully updated")
        })
    })

    $("#dept-edit-btn").click(function() {
        let input = $("#dept-input")
        inputData = input.val()
        // empty input if no department is set
        if (!deptExist) input.val("")
        
        // show
        $("#dept-section").addClass("border")
        $("#dept-decide-con").removeClass('d-none')
        $("#dept-clear-btn").removeClass('d-none')
        $(".dept-ins").removeClass('d-none')
        input.attr('readonly', false)
        input.removeClass('d-none')
        
        // hide
        $(this).toggleClass("d-none")
        $("#dept-empty-msg").addClass('d-none')
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

        // // empty input if no department is set
        // if (inputData == ) {
        //     input.val("No department set")
        //     deptExist = false 
        // } else deptExist = true


        // hide
        $("#dept-section").removeClass("border")
        $("#dept-decide-con").toggleClass('d-none')
        $("#dept-clear-btn").toggleClass('d-none')
        $(".dept-ins").toggleClass('d-none')


   

        
        // show
        $("#dept-edit-btn").toggleClass("d-none")
        $("#dept-empty-msg").toggleClass('d-none')
    })


    $("#dept-save-btn").click(() => $("#dept-form").submit())
    $("#dept-form").submit(function(e) {
        e.preventDefault()
        showSpinner()

        $.post("action.php?", $(this).serialize(), function() {
            // hide
            $("#dept-section").removeClass("border")
            $("#dept-decide-con").toggleClass('d-none')
            $("#dept-clear-btn").toggleClass('d-none')
            $(".dept-ins").toggleClass('d-none')
            let input = $("#dept-input")
            input.attr('readonly', true)
            // empty input if no department is set
            if (input.val().length == 0) {
                input.val("No department set")
                deptExist = false 
            } else deptExist = true

            
            // show
            $("#dept-edit-btn").toggleClass("d-none")
            $("#dept-empty-msg").toggleClass('d-none')
            
            hideSpinner()
            showToast('success', "Department successfully updated")
        })
    })

    $(document).on('click', '.add-subject', addSubjectFn)
    $(document).on('click', '.remove-all-btn', removeAllBtnFn)
    $(document).on('click', '.remove-btn', removeSubjectBtnFn)
    $(document).on('click', '#selectAll', selectAll)

    const toggleElements = (eList, className) => eList.forEach(e => $(`${e}`).toggleClass(className))
    const addEmptySubjectMsg = () => $("#as-table tbody").html("<tr id='emptyMsg' class='text-center'><td colspan='5'>No subject set</td></tr>")
    const hideEditSubjectElements = () => toggleElements([".edit-con", ".finder-con", ".decision-as-con", 
                                                        ".remove-btn", ".view-btn", ".cb-con",
                                                        "#as-table thead tr th:first-child"], "d-none")

    $("#edit-as-btn").click(function() {
        $(this).closest("div").toggleClass("d-none")
        toggleElements([".finder-con", ".remove-btn", ".view-btn", ".decision-as-con", 
                        "#as-table tr th:first-child", "#as-table tr td:first-child"], 'd-none')
    })

    $("#cancel-as-btn").click(() => {
        let con = $("#as-table tbody")
        con.empty()
        setSubjectSelected([])
        console.log(assigned)
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
        $.post("action.php", $(this).serialize(), function() {
            assigned = getSubjectSelected()
            if (assigned.length == 0) addEmptySubjectMsg()
            hideEditSubjectElements()
            // $("#as-table thead tr th:first-child").toggleClass("d-none")
            hideSpinner()
            showToast('success', "Handled subjects successfully updated")
        })
    })

    hideSpinner()
})