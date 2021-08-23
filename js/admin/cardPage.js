// HTML elements
let addBtn, cardCon, kebab, noResultMsg, searchInput, addModal, form

// Data
let page, camelized, action, deleteMessage, archiveMessage, keywords, dataList, 
    timeout, elementAccess

// Function
let prepareHTMLOfData, prepareHTMLofArchive, filterDataFn

const setup = (page, prepareHTML, prepareArchiveHTML, filterFn) => {
    // string detail to be added in the delete and archive modal messages
    // page = page
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
    addModal = $('#add-modal')
    form = $(`${page}-form`)

    // delete and arhive messages
    deleteMessage = `Deleting this ${page} will also delete all ${programString}subjects, and student grades under this ${page}.`
    archiveMessage = `Archiving this ${page} will also archive all ${programString}subjects and student grades under this ${page}.`
    unarchiveMessage = `Unarchiving this ${page} will also unarchive all ${programString}subjects and student grades under this ${page}.`
    
    // functions
    prepareHTMLOfData = prepareHTML
    prepareHTMLofArchive = prepareArchiveHTML

    filterDataFn = filterFn

    keywords = ""
    timeout = null
    dataList = []
}

const reload = () => {
    console.log("from reload")
    showSpinner()
    // getArchiveAction = `getArchived${camelized}JSON`
    console.log("Action: ", action)
    $.post('action.php', {action}, (response) => {
        dataList = JSON.parse(response)
        let addBtn = 
        `   <div class='tile card shadow-sm p-0 position-relative'>
                <a role='button' class='card-link add-btn btn btn-link start-0 top-0 end-0 bottom-0 h-100' style='z-index: 2;'></a>
                <div class='card-body position-absolute d-flex-column justify-content-between'>
                    Add ${camelized}
                </div>
            </div>`
        $('.cards-con').html(prepareHTMLOfData(dataList.data) + addBtn)
        $('.arch-list').html(prepareHTMLofArchive(dataList.archived))
    })
    hideSpinner()
}


/** Shows all curriculum cards with the add button */
const showAllTiles = () => {
    showSpinner()
    $('.tile').each(function() {
        $(this).show()
    })
    noResultMsg.addClass('d-none') // hide 'No result' message
    hideSpinner()
}

/** Shows only the matching cards with the keyword */
const showResults = results => {
    var len = results.length
    var tiles = $('.tile')
    if (len === dataList.data.length) return showAllTiles()

    if (len > 0) {
        noResultMsg.addClass('d-none')
        tiles.each(function() {
            var card = $(this)
            if (results.includes(card.attr('data-id'))) card.show()
            else card.hide()
        })
        return
    }

    // no results found at this point
    tiles.each(function() {
        $(this).hide()
        noResultMsg.removeClass('d-none')
    })
}

const showWarning = () => {
    showWarningToast(`${camelized} successfully deleted`)
}

// const eventDelegations = () => {
    /*** Event delegation applied here. This concept binds all the event listener to the target element even when dynamically created. */
    // search 

    const showAllTilesIfEmpty = () => {
        if ($('#search-input').val().length == 0) showAllTiles()
    } 

    $(document).on('search', '#search-input', () => showAllTilesIfEmpty())

    $(document).on('keyup', '#search-input', () => {
        showSpinner()
        clearTimeout(timeout) // resets the timer
        timeout = setTimeout(() => { // executes the function after the specified milliseconds
            let results = filterDataFn(dataList.data)
            showResults(results.map((element) => { // map function returns an array containing the specified component of the element
                return element[elementAccess]
            }))
            showAllTilesIfEmpty()
            hideSpinner()
        }, 500)
    })
    // add, delete, archive and view archive buttons
    $(document).on('click', '.add-btn', () => $('#add-modal').modal('toggle'))
    $(document).on('click', '.view-archive', () => $('#view-arch-modal').modal('toggle'))
    $(document).on('click', '.delete-btn', function() {
        var code = $(this).attr('id')
        var action = `delete${camelized}`
        $.post("action.php", {code, action}, function(data) {	
            $('#delete-modal').modal('hide')		
            reload()
            showWarning()
        })
    })

    // Modal Options 
    $(document).on('click', '.archive-option', function() {
        var code = $(this).attr('id')
        let name = $(this).attr('data-name')
        let archiveModal = $('#archive-modal')
        archiveModal.find('#modal-identifier').html(`${name} ${camelized}`)
        archiveModal.find('.modal-msg').html(archiveMessage)
        archiveModal.find('.archive-btn').attr('id', code)
        archiveModal.modal('toggle')
    })

    $(document).on('click', '.delete-option', function() {
        var code = $(this).attr('id')
        let name = $(this).attr('data-name')
        let deleteModal = $('#delete-modal')
        deleteModal.find('#modal-identifier').html(`${name} ${camelized}`)
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

    $("#unarchive-modal").on('show.bs.modal', function (e) {
        $("#view-arch-modal").modal("hide");
    });
    
    //archive script
    $(document).on('click', '.archive-btn', function() {
        var code = $(this).attr('id')
        var action = `archive${camelized}`
        $.post("action.php", {code, action}, function(data) {	
            $('#archive-modal').modal('hide')		
            reload()
            showToast('success', `${camelized} successfully archived`)
        })
    })

    $(document).on('click', '.unarchive-btn', function() {
        $('#view-arch-modal').modal('hide')	
        var code = $(this).attr('id')
        var action = `unarchive${camelized}`
        console.log('from cardPage')
        console.log(action)
        console.log(code)
        $.post("action.php", {code, action}, function(data) {	
            $('#unarchive-modal').modal('hide')		
            reload()
            showToast('success', `${camelized} successfully unarchived`)
        })
    })

    // Modal Options 
    $(document).on('click', '.unarchive-option', function() {
        var code = $(this).attr('id')
        let name = $(this).attr('data-name')
        let unarchiveModal = $('#unarchive-modal')
        unarchiveModal.find('#modal-identifier').html(`${name} ${camelized}`)
        unarchiveModal.find('.modal-msg').html(unarchiveMessage)
        unarchiveModal.find('.unarchive-btn').attr('id', code)
        unarchiveModal.modal('toggle')
    })



// } 