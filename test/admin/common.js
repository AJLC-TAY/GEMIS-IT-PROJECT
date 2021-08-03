let menuItem, subMenuItem, spinner

/** Display active menu item */
function preload(menuItem, subMenuItem) {
    menuItem = $(`${menuItem} a:first`)
    subMenuItem = $(`${subMenuItem}`)
    spinner = $('.spinner-con')
    spinner.show()
    menuItem.click()
    if (subMenuItem) subMenuItem.addClass('active-sub')
}

/** Fades out spinner */
function hideSpinner() {
    spinner.fadeOut(500)
}

/** 
 *  Overide the text of the toast body then toast is displayed.
 *  @param {String} msg Text to be showed.
 */
function showWarningToast(msg) {
    let toast = $('.warning-toast')
    toast.find('.toast-body').text(msg)
    toast.toast('show')
}