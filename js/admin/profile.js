// Deleted roles
let rolesDel = []
let rolestmp = []
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
        if (rolestmp.length == 0) emptyMsg.removeClass('d-none')
        else emptyMsg.addClass('d-none')
    }

    /** Role Methods */
    $("#role-edit-btn").click(function(event) {
        event.preventDefault()
        rolestmp = [...roles]
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
        rolestmp.push(value)
        $(`.role-to-delete-btn[data-value=${value}]`).removeClass('d-none')
        checkRolesTagForMsg()
        element.addClass("d-none")
    })

    $(".role-to-delete-btn button").click(function () {
        let element = $(this).closest("div")
        let value = element.attr('data-value')
        let toDel = rolestmp.splice(rolestmp.indexOf(value), 1)[0]
        rolesDel.push(toDel)
        console.log("Before\nRoles: ", rolestmp)
        console.log("Roles to delete: ", rolesDel)
        if (rolestmp == 0) $("#role-empty-msg").removeClass('d-none')

        $(`#role-option-tag-con [data-value=${value}]`).removeClass('d-none')
        element.addClass('d-none')
    })


    let hideRoleEditButtons = function () {
        toggleElements($("#role-save-btn, #role-edit-btn, .rounded-pill i"), 'd-none')
        if (roles.length == 0) $("#role-empty-msg").removeClass('d-none')
        $("#role-add-btn").addClass('d-none')
    }

    let addTag = (container, desc, value) => {
        $(container).prepend(`<div class='rounded-pill border border-secondary d-inline-block me-1'><span class='ms-3'>${desc}</span>
                <button class='btn btn-link text-dark btn-sm' data-value='${value}'><i class='bi bi-x-circle-fill'></i></button></div>`)
    }

    // cancel

    $("#role-cancel-btn").click(function(event) {
        event.preventDefault()
        // hide
        $("#role-section").removeClass("border")
        $("#role-option-tag-con").addClass('d-none')
        $("#role-decide-con").addClass('d-none')

        // show
        $("#role-edit-btn").removeClass("d-none")
        console.log("Cancel button")
        console.log("Before\nRoles: ", rolestmp)
        console.log("Roles to delete: ", rolesDel)
        
        roles.forEach(e => {
            let eHTML = $(`.role-to-delete-btn[data-value=${e}]`)
            eHTML.find("button").addClass('d-none')
            eHTML.removeClass('d-none')
        })
        rolesDel.forEach(e => {
            let eHTML = $(`#role-option-tag-con [data-value=${e}]`)
            eHTML.addClass('d-none')
        })
        rolesDel = []
        rolestmp = []
        console.log("After\nRoles: ", rolestmp)
        console.log("Roles to delete: ", rolesDel)
    })

    $("#role-save-btn").click(() => $("#role-form").submit())

    $("#role-form").submit(function(event) {
        event.preventDefault()
        var formData = $(this).serialize()

        rolestmp.forEach(role => {
            formData += encodeURI(`&access[]=${role}`)
        })

        $.post('action.php', formData, function(){
            // hide
            $("#role-section").removeClass("border")
            $("#role-cancel-btn").addClass('d-none')
            $("#role-tag-con div").removeAttr("role")
            $("#role-option-tag-con").addClass('d-none')
            $("#role-decide-con").addClass('d-none')
            rolestmp.forEach(e=> {
                let eHTML = $(`.role-to-delete-btn[data-value=${e}]`)
                eHTML.find("button").addClass('d-none')
            })

            // show
            $("#role-edit-btn").removeClass('d-none')

            if (rolestmp.length == 0) $("#role-empty-msg").removeClass("d-none")
            roles = rolestmp.slice()
            rolestmp = []
            rolesDel = []
            showToast('success', "Roles successfully updated")
        })
    })


    hideSpinner()
})