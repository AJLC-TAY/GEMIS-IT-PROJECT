let reload = true;
let interval = 5000;

function refresh() {
    $.ajax({
        url: 'getAction.php?data=enroll-data',
        method: "GET",
        success: (data) => {
            let enData = JSON.parse(data);
            $("#enrolled").html(enData.enrolled);
            $("#pending").html(enData.pending);
            $("#rejected").html(enData.rejected);
        }
    });
}

function setInterval(sec) {
    interval = sec;
}

function toggleRefresh() {
    reload = !reload;
}

$(function () {
    preload("#enrollment", "#enrollment-sub");
    hideSpinner();
    refresh();
    // setInterval(refresh(), interval);
    while(reload) {
        setTimeout( () => refresh(), interval);
    }
});