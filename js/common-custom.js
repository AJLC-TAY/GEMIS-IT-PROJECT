let menuItem, subMenuItem, spinner

/** Display active menu item */
function preload(menuItem, subMenuItem=null) {
    spinner = $('.spinner-con')
    spinner.show()
    if (!subMenuItem) return $(menuItem).click()
    menuItem = $(`${menuItem} a:first`)
    subMenuItem = $(`${subMenuItem}`)
    menuItem.addClass('active')
    subMenuItem.addClass('active-sub')
}

/** Shows the spinner */
function showSpinner() {
    spinner.show()
}

/** Fades out spinner */
function hideSpinner() {
    spinner.fadeOut(500)
}
    
/** 
 *  Show toast basing from the specified toast type, message, and/or delay.
 * 
 *  @param {String} type    Toast type.
 *  @param {String} msg     Text to be showed.
 *  @param {Number} delay   Milliseconds to wait before fading out.
 */
function showToast(type, msg, options=null) {
    let toast = $(`<div class="toast bg-${type} text-white" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">${msg}</div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>`)
    toast.prependTo('#toast-con')
    let newToast = new bootstrap.Toast(toast, options)
    toast.bind('hidden.bs.toast', function () {
        $(this).remove();
    })
    newToast.show()
}