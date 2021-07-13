var curriculumCon = $('.curriculum-con')
var kebab = $('.kebab')
var addCurriculumBtn = $('.add-curriculum')
var noResultMsg = $('.msg')
var spinner = $('.spinner-border')
var searchInput = $('#search-input')
var timeout = null
var curriculumList = []

function reloadCurriculum() {
    spinner.show()
    var action = 'getCurriculumJSON'
    $.post('action.php', {action}, function (data) {
        curriculumCon.empty()
        curriculumList = JSON.parse(data)
        curriculumList.forEach(element => {
            var code = element.cur_code
            var name = element.cur_name
            var desc = element.cur_desc
            curriculumCon.append(
                `<div data-id='${code}' class='card shadow-sm p-0'>
                    <div class='card-body'>
                        <div class='dropdown'>
                            <button type='button' class='kebab btn btn-link rounded-circle' data-bs-toggle='dropdown'></button>
                            <ul class='dropdown-menu'>
                                <li><a class='dropdown-item' href='curriculum.php?code=${code}'>Edit</a></li>
                                <li><button data-name='${name}' class='archive-btn dropdown-item'>Archive</button></li>
                                <li><button class='delete dropdown-item' id='${code}'>Delete</button></li>
                            </ul>
                        </div>
                        <h4>${name}</h4>
                        <p>${desc}</p>
                    </div>
                    <div class='modal-footer p-0'>
                        <a role='button' class='btn' href='curriculum.php?code=${code}'>View</a>
                    </div>
                </div>`
            )
        })
        curriculumCon.append(`<div class='btn add-curriculum card shadow-sm'>
                                <div class='card-body'> Add Curriculum</div>
                            </div>`)
    })

    spinner.fadeOut()
}
    
function showWarningToast(msg) {
    let msgToast = $('.warning-toast')
    msgToast.find('.toast-body').text(msg)
    msgToast.toast('show')
}

/** Shows all curriculum cards with the add button */
function showAllCurriculum() {
    spinner.show()
    $('.card').each(function() {
        $(this).show()
    })
    noResultMsg.addClass('d-none') // hide 'No result' message
    spinner.fadeOut()
}

/** Shows only the matching cards with the keyword */
function showResults(results) {
    var len = results.length
    var cards = $('.card')
    if (len === curriculumList.length) return showAllCurriculum()

    if (len > 0) {
        noResultMsg.addClass('d-none')
        cards.each(function() {
            var card = $(this)
            if (results.includes(card.attr('data-id'))) card.show()
            else card.hide()
        })
        return
    }

    // no results found at this point
    cards.each(function() {
        $(this).hide()
        noResultMsg.removeClass('d-none')
    })
}

$(document).ready(function() {
    /** Fetch data */
    reloadCurriculum()
    spinner.fadeOut("slow")

    /** Display active menu item */
    $('#curr-management a:first').click()
    $('#curriculum').addClass('active-sub')

    $('#curriculum-form').submit(function(event) {
        event.preventDefault()
        spinner.show()
        var form = $(this)
        var formData = form.serialize()
        $.post("action.php", formData, function(data) {
            form.trigger('reset')
            $('#add-curr-modal').modal('hide')
            reloadCurriculum()
        }).fail(function () {

        })
    })
})

/*** Event delegation applied here. This concept binds all the event listener to the target element even when dynamically created. */

$(document).on('search', '#search-input', function () {
    if (searchInput.val().length == 0) showAllCurriculum()
})

$(document).on('keyup', '#search-input', function() {
    spinner.show()
    clearTimeout(timeout) // resets the timer
    timeout = setTimeout(() => { // executes the function after the specified milliseconds
        var keywords = $('#search-input').val().trim().toLowerCase()
        let filterCurriculum = (curriculum) => { // returns the curriculum info that contain the keyword
            return (curriculum.cur_code.toLowerCase().includes(keywords) || curriculum.cur_desc.toLowerCase().includes(keywords) || curriculum.cur_name.toLowerCase().includes(keywords))
        }
        var results = curriculumList.filter(filterCurriculum)
        showResults(results.map((element) => { // map function returns an array containing the specified component of the element
            return element.cur_code
        }))
        spinner.fadeOut()
    }, 500)
})

$(document).on('click', '.view-archive', () => $('#view-arch-curr-modal').modal('toggle'))

$(document).on('click', '.add-curriculum', () => $('#add-curr-modal').modal('toggle'))

/*** Modal Options */
$(document).on('click', '.archive-btn', function() {
    let name = $(this).attr('data-name')
    $('#modal-identifier').html(`${name} Curriculum`)
    $('.modal-msg').html('Archiving this curriculum will also archive all programs/strands, subjects, and student grades under this curriculum.')
    $('#archive-modal').modal('toggle')
})

$(document).on('click', '.delete', function() {
    spinner.show()
    var code = $(this).attr('id')
    var action = "deleteCurriculum"

    if(confirm("Are you sure you want to delete this Curriculum?")) {
        $.post("action.php", {code, action}, function(data) {					
            reloadCurriculum()
        })
    } else {
        return false
    }
    spinner.fadeOut()
})

/*** Footer modal buttons */
/*** Reset curriculum form and hide error messages */
$(document).on('click', ".close", () => {
    $('#curriculum-form').trigger('reset')              // reset form
    $("[class*='error-msg']").addClass('invisible')     // hide error messages
})