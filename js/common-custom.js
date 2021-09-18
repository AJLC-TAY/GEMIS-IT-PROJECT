let menuItem, subMenuItem, spinner;

/** Display active menu item */
function preload(menuItem, subMenuItem= null) {
    spinner = $('#main-spinner-con');
    spinner.show();
    if (subMenuItem == null) {
        console.log(menuItem);
        return $(menuItem).click();
    }
    menuItem = $(`${menuItem} a:first`);
    subMenuItem = $(`${subMenuItem}`);
    menuItem.addClass('active');
    subMenuItem.addClass('active-sub');
}

/** Shows the spinner */
function showSpinner(selector = null, bs = false) {
    if (selector != null) return $(selector).show();
    if (bs) return $(selector).bootstrapTable('showLoading');
    spinner.show();
}

/** Fades out spinner */
function hideSpinner(selector = null) {
    if (selector != null) return $(selector).hide();
    spinner.fadeOut(500);
}
    
/** 
 *  Show toast basing from the specified toast type, message, and/or delay.
 * 
 *  @param {String} type    Toast type.
 *  @param {String} msg     Text to be showed.
 *  @param {Number} delay   Milliseconds to wait before fading out.
 */
function showToast(type, msg, options= null) {
    let toast = $(`<div class="toast bg-${type} text-white" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">${msg}</div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>`);
    toast.prependTo('#toast-con');
    let newToast = new bootstrap.Toast(toast, options);
    toast.bind('hidden.bs.toast', function () {
        $(this).remove();
    })
    newToast.show();
}

/**
 * Function responsible for not allowing non-numeric characters 
 * to be entered in an input tag
 */
function isNumberKey(e){
	var charCode = (e.which) ? e.which : e.keyCode;
	return !(charCode > 31 && (charCode < 48 || charCode > 57));
}

$(document).on("keypress", ".number", isNumberKey);