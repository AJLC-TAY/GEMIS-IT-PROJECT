import {toggleEnrollment} from "./utilities.js";

const SECONDS = 3000;

function refresh() {
    $.ajax({
        url:    'getAction.php?data=enroll-data',
        method: 'GET',
        success: (data) => {
            let enData = JSON.parse(data);
            $("#enrolled").html(enData.enrolled);
            $("#pending").html(enData.pending);
            $("#rejected").html(enData.rejected);
        }
    });
}

function startAutoRefresh () {
    return setInterval(refresh, SECONDS);
}

window.refresh = refresh;

var interval = startAutoRefresh();

$(function() {
    preload("#enrollment", "#enrollment-sub");
    refresh();
    hideSpinner();

    $(document).on("click", ".refresh-switch", function () {
        refresh();
        if ($(this).is(":checked")) {
            interval = startAutoRefresh();
        } else {
            clearInterval(interval);
        }
    });

    toggleEnrollment();

    $(document).on("change", "[name='enrollment']", function() {
        $("#auto-refresh").prop("checked", $(this).is(":checked"));
    });
});

