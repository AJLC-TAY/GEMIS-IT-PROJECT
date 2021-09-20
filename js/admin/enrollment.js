preload("#enrollment", "#enrollment-sub");

$(function() {
    $("#id-no-select").select2({
        theme: "bootstrap-5",
        width: null
    });
    $(document).on('submit', '#enroll-report-form', function() {
        // showSpinner();
        console.log($(this).serializeArray());
        showToast('dark', 'Downloading file ...');
        setTimeout(() => {
            showToast('dark', 'Redirecting to enrollment dashboard ...');
        }, 2000);
        // setTimeout(() => {
        //     window.location.replace("enrollment.php?page=enrollees");
        // }, 4000);
    });

    $(document).on("change", "#id-no-select", function(e) {
        e.preventDefault();
        let selected = $("#id-no-select option:selected");
        $("input[name='signatory']").val(selected.attr("data-name").trim());
        $("input[name='position']").val(selected.attr("data-position"));
    });


    hideSpinner();
});