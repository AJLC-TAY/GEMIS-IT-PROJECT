preload("#enrollment", "#enrollment-sub")

$(function() {
    $(document).on('submit', '#enroll-report-form', function() {
        showSpinner()
        showToast('dark', 'Downloading file ...')
        setTimeout(() => {
            showToast('dark', 'Redirecting to enrollment dashboard ...')
        }, 2000)
        setTimeout(() => {
            window.location.replace("enrollment.php?page=enrollees");
        }, 4000)
    })
    hideSpinner()
})