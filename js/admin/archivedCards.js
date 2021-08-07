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
        $('.cards-con').html(prepareHTMLOfData(dataList))
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


// const eventDelegations = () => {
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


// } 