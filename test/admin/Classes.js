class Page {
    constructor(menuItem, subMenuItem = '') {
        this.menuItem = $(`${menuItem} a:first`)
        this.subMenuItem = $(`${subMenuItem}`)
        this.spinner = $('.spinner-con')
    }

    /** Display active menu item */
    preload() {
        this.spinner.show()
        this.menuItem.click()
        if (this.subMenuItem) this.subMenuItem.addClass('active-sub')
    }

    /** Fades out spinner */
    hideSpinner() {
        this.spinner.fadeOut(500)
    }

    /** 
     *  Overide the text of the toast body then toast is displayed.
     *  @param {String} msg Text to be showed.
     */
    showWarningToast(msg) {
        let toast = $('.warning-toast')
        toast.find('.toast-body').text(msg)
        toast.toast('show')
    }
}

class TablePage extends Page {
    constructor (menuItem, subMenuItem, tableId, url, method, uniqueId, idField, height, search = false, 
        searchSelector = '', exportDataType = 'All', onPostBody = null) {
        super(menuItem, subMenuItem)
        this.table = tableId

        var tableSetup = {
            url, 
            method,
            uniqueId,
            idField,
            height,
            pagination: true,
            paginationParts: ["pageInfoShort", "pageSize", "pageList"],
            pageSize: 10,
            pageList: "[10, 25, 50, All]"
        }

        if (search) {
            tableSetup.search = search
        }
        
        if (searchSelector) {
        	tableSetup.searchSelector = searchSelector
        }

        if (exportDataType) {
            tableSetup.exportDataType = exportDataType
        }

        if (onPostBody) {
            tableSetup.onPostBody = onPostBody
        }
        
        this.tableSetup = tableSetup
    }

    setup() {
        this.preload()

        let table = this.table
        let setup = this.tableSetup
        $(function () {
            table = $(`${table}`).bootstrapTable(setup)
        })
        this.table = table
    }
}

class CardsPage extends Page {
    constructor (menuItem, subMenuItem, page, action) {
        super(menuItem, subMenuItem)
        this.page = page                                               // ex. curriculum
        this.camelized = page.charAt(0).toUpperCase() + page.slice(1)  // ex. camelized = Curriculum 
        this.action = action // getCurriculumJSON, getProgramJSON

        // access html elements
        this.addBtn = $('.add-btn')
        this.cardCon = $('.cards-con')
        this.kebab = $('.kebab')
        this.noResultMsg = $('.msg')
        this.searchInput = $('#search-input')
        this.addToast = $('.add-toast')
        this.addModal = $('#add-modal')
        this.warningToast = $('.warning-toast')

        // string detail to be added in the delete and archive modal messages
        var programString = ""
        switch (page) {
            case 'curriculum':
                programString = "programs/strands, "
                this.elementAccess = "cur_code"
                break;
            case 'program':
                this.elementAccess = "prog_code"
                break;
        }

        // delete and arhive messages
        this.deleteMessage = `Deleting this ${page} will also delete all ${programString}subjects, and student grades under this ${page}.`
        this.archiveMessage = `Archiving this ${page} will also archive all ${programString}subjects and student grades under this ${page}.`
 
        // functions
        this.prepareHTMLOfData = null 
        this.filter = null

        this.keywords = ""
        this.timeout = null
        this.dataList = []

        this.eventDelegation()
    }

    setPrepareHTMLDataFunc (prepareHTMLOfData) {
        this.prepareHTMLOfData = prepareHTMLOfData
    }

    setGetDataResultFunc (getDataResultfunc) {
        this.getDataResult = getDataResultfunc
    }

    /** Shows all curriculum cards with the add button */
    showAllCards() {
        this.spinner.show()
        $('.card').each(function() {
            $(this).show()
        })
        this.noResultMsg.addClass('d-none') // hide 'No result' message
        this.hideSpinner()
    }

    /** Shows only the matching cards with the keyword */
    showResults(results) {

        var len = results.length
        var cards = $('.card')
        if (len === this.dataList.length) return this.showAllCards()

        if (len > 0) {
            this.noResultMsg.addClass('d-none')
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
            this.noResultMsg.removeClass('d-none')
        })
    }

    
    reload() {
        this.spinner.show()
        // action = getCurriculumJSON
        $.post('action.php', {action : this.action}, (data) => {
            this.dataList = JSON.parse(data)
            let addBtn = `<div class='btn add-btn card shadow-sm'>
                <div class='card-body'>Add ${this.camelized}</div>
            </div>`
            let html = this.prepareHTMLOfData(this.dataList) + addBtn
            $('.cards-con').html(html)
        })
        this.hideSpinner()
    }

    showWarningToast() {
        super.showWarningToast(`${this.camelized} successfully deleted`)
    }

    /** Shows all curriculum cards with the add button */
    showAllCards() {
        this.spinner.show()
        $('.card').each(function() {
            $(this).show()
        })
        this.noResultMsg.addClass('d-none') // hide 'No result' message
        this.hideSpinner()
    }

    showResults(results) {
        /** Shows only the matching cards with the keyword */
        var len = results.length
        var cards = $('.card')
        if (len === this.dataList.length) return this.showAllCards()

        if (len > 0) {
            this.noResultMsg.addClass('d-none')
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
        })
        this.noResultMsg.removeClass('d-none')
    }

    /*** Event delegation applied here. This concept binds all the event listener to the target element even when dynamically created. */
    eventDelegation() {
        var camelized = this.camelized
        var afterDelete = () => {
            this.reload()
            this.showWarningToast()
        }
        var archiveMessage = this.archiveMessage
        var deleteMessage = this.deleteMessage

        // search 
        $(document).on('search', '#search-input', () => {
            if ($('#search-input').val().length == 0) this.showAllCards()
        })

        $(document).on('keyup', '#search-input', () => {
            this.spinner.show()
            clearTimeout(this.timeout) // resets the timer
            this.timeout = setTimeout(() => { // executes the function after the specified milliseconds
                let results = getDataResult(this.dataList)
                this.showResults(results.map((element) => { // map function returns an array containing the specified component of the element
                    return element[this.elementAccess]
                }))
                this.hideSpinner()
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
                afterDelete()
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
            $(`#${this.page}-form`).trigger('reset')              // reset form
            $("[class*='error-msg']").addClass('invisible')     // hide error messages
        })
    }
}