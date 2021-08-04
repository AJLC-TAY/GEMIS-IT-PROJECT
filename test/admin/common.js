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
 *  Show toast basing from the specified toast type, message, and/or delay.
 * 
 *  @param {String} type    Toast type.
 *  @param {String} msg     Text to be showed.
 *  @param {Number} delay   Milliseconds to wait before fading out.
 */
function showToast(type, msg, delay = 0) {
    let toast = $(`.${type}-toast`)
    toast.find('.toast-body').text(msg)

    if (delay == 0) {
        toast.toast('show')
    } else {
        toast.toast({delay})
        toast.toast('show')
    }
}

function setToast(type, msg, delay = 0) {
    let toast = $(`.${type}-toast`)
    toast.find('.toast-body').text(msg)

    // if (delay == 0) {
    //     // toast.toast('show')
    // } else {
    //     toast.toast({delay})
    //     toast.toast('show')
    // }
}