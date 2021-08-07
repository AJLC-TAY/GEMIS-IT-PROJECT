// HTML elements
let addBtn, cardCon, kebab, noResultMsg,searchInput, addToast, addModal, warningToast

// Data
let page, camelized, action, deleteMessage, archiveMessage, keywords, dataList, 
    timeout, elementAccess

// Function
let prepareHTMLOfData, filter

const setup = (page, prepareHTML, filter) => {
    // string detail to be added in the delete and archive modal messages
    page = page
    var programString = ""
    switch (page) {
        case 'curriculum':
            preload("#curr-management", "#curriculum")
            programString = "programs/strands, "
            elementAccess = "cur_code"
            action = "getCurriculumJSON" 
            break;
        case 'program':
            preload("#curr-management", "#program")
            elementAccess = "prog_code"
            action = "getProgramJSON" 
            break;
    }

    camelized = page.charAt(0).toUpperCase() + page.slice(1)  // ex. camelized = Curriculum 

    // access html elements
    addBtn = $('.add-btn')
    cardCon = $('.cards-con')
    kebab = $('.kebab')
    noResultMsg = $('.msg')
    searchInput = $('#search-input')
    addToast = $('.add-toast')
    addModal = $('#add-modal')
    warningToast = $('.warning-toast')

    // delete and arhive messages
    deleteMessage = `Deleting this ${page} will also delete all ${programString}subjects, and student grades under this ${page}.`
    archiveMessage = `Archiving this ${page} will also archive all ${programString}subjects and student grades under this ${page}.`
    
    // functions
    prepareHTMLOfData = prepareHTML

    filter = filter

    keywords = ""
    timeout = null
    dataList = []
}

const reload = () => {
    spinner.show()
    $.post('action.php', {action}, (data) => {
        dataList = JSON.parse(data)
        let addBtn = `<div class='btn add-btn card shadow-sm'>
            <div class='card-body'>Add ${camelized}</div>
        </div>`
        $('.cards-con').html(prepareHTMLOfData(dataList) + addBtn)
    })
    hideSpinner()
}


/** Shows all curriculum cards with the add button */
const showAllCards = () => {
    spinner.show()
    $('.card').each(function() {
        $(this).show()
    })
    noResultMsg.addClass('d-none') // hide 'No result' message
    hideSpinner()
}

/** Shows only the matching cards with the keyword */
const showResults = (results) => {
    var len = results.length
    var cards = $('.card')
    if (len === dataList.length) return showAllCards()

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

const showWarning = () => {
    showWarningToast(`${camelized} successfully deleted`)
}

/*** Event delegation applied here. This concept binds all the event listener to the target element even when dynamically created. */
// search 
$(document).on('search', '#search-input', () => {
    if ($('#search-input').val().length == 0) showAllCards()
})

$(document).on('keyup', '#search-input', () => {
    spinner.show()
    clearTimeout(timeout) // resets the timer
    timeout = setTimeout(() => { // executes the function after the specified milliseconds
        let results = getDataResult(dataList)
        showResults(results.map((element) => { // map function returns an array containing the specified component of the element
            return element[elementAccess]
        }))
        hideSpinner()
    }, 500)
})
// add, delete, and view archive buttons
$(document).on('click', '.add-btn', () => $('#add-modal').modal('toggle'))
$(document).on('click', '.view-archive', () => $('#view-arch-modal').modal('toggle'))
$(document).on('click', '.delete-btn', function() {
    var code = $(this).attr('id')
    var action = `delete${camelized}`
    $.post("action.php", {code, action}, function(data) {	
        $('#delete-modal').modal('hide')		
        reload()
        showWarningToast()
    })
})

// Modal Options 
$(document).on('click', '.archive-btn', function() {
    let name = $(this).attr('data-name')
    $('#modal-identifier').html(`${name} ${camelized}`)
    $('.modal-msg').html(archiveMessage)
    $('#archive-modal').modal('toggle')
})

$(document).on('click', '.delete-option', function() {
    var code = $(this).attr('id')
    let name = $(this).attr('data-name')
    let deleteModal = $('#delete-modal')
    deleteModal.find('.modal-identifier').html(`${name} ${camelized}`)
    deleteModal.find('.modal-msg').html(deleteMessage)
    deleteModal.find('.delete-btn').attr('id', code)
    deleteModal.modal('toggle')
})

/*** Footer modal buttons */
/*** Reset form and hide error messages */
$(document).on('click', ".close", () => {
    $(`#${page}-form`).trigger('reset')              // reset form
    $("[class*='error-msg']").addClass('invisible')     // hide error messages
})