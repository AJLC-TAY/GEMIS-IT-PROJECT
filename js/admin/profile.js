$(function () {
    preload('#faculty')
    // Initialize toast
    var toastList = $('.toast').map(function (toastEl) {
        return new bootstrap.Toast(toastEl)
    })
    // Initialize popover
    let popover = new bootstrap.Popover($("#instruction"))

    $("#edit-role-btn").click(function(event) {
        event.preventDefault()
        $(this).addClass('d-none')
        $("#role-empty-msg").addClass('d-none')
        $("#role-save-btn").removeClass('d-none')
        $("#role-cancel-btn").removeClass('d-none')
        if (roles.length != 3) $("#role-add-btn").removeClass('d-none') 

        $(".rounded-pill i").each(function() {
            $(this).removeClass('d-none')
        })
    })

    let hideRoleEditButtons = function () {
        $("#role-save-btn").addClass('d-none')
        $("#role-add-btn").addClass('d-none')
        $(".rounded-pill i").each(function() {
            $(this).addClass('d-none')
        })
        $("#edit-role-btn").removeClass('d-none')
    }

    $("#role-cancel-btn").click(function(event) {
        event.preventDefault()
        $(this).addClass('d-none')
        hideRoleEditButtons()
    })

    $(document).on('click', ".rounded-pill i", function(event) {
        event.preventDefault()
        let element = $(this)
        let value = element.attr('data-value')
        element.closest(".rounded-pill").remove()
        roles = roles.filter(function (element) {
            return element != value
        })
        if (roles.length != 3) $("#role-add-btn").removeClass('d-none') 
    })

  
    $("#role-form").submit(function(event) {
        event.preventDefault()
        $("#role-cancel-btn").addClass('d-none')
        if (roles.length == 0) {
            $("#role-tag-con").append("<p id='role-empty-msg' class='text-center'>No roles/access set</p>")
        }
        hideRoleEditButtons()
        console.log(roles)
    })

    $("#role-dropdown li button").click(function() {
        let value = $(this).attr('data-value')
        if (roles.includes(value)) {
            showToast('warning', 'Role already assigned')
            // alert("Role already assigned")
            return
        } 
        let desc
        switch (value) {
            case 'editGrades':
                desc = 'Edit Grade'
                break;
            case 'canEnroll':
                desc = 'Can Enroll'
                break;
            case 'awardReport':
                desc = 'Award Coordinator'
                break;
        }
        roles.push(value)
        if (roles.length == 3) $("#role-add-btn").addClass('d-none')

        $('#role-tag-con').prepend(`<div class='rounded-pill border border-secondary d-inline-block me-1'><span class='ms-3'>${desc}</span>
                <button class='btn btn-link text-dark btn-sm '><i class='bi bi-x-circle-fill'></i></button></div>`)
    })

    hideSpinner()
})