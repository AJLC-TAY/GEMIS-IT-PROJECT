try {
    var stepper = new Stepper($('#stepper')[0]);
} catch (e) {}

$(function() {
    preload("#enrollment", "#enrollment-sub");

    try {
        $("#id-no-select").select2({
            theme: "bootstrap-5",
            width: null
        });
    } catch (e) {}

    
    $(document).on('submit', '#enroll-report-form', function() {
        showToast('dark', 'Downloading file ...');
        setTimeout(() => {
            showToast('dark', 'Redirecting to enrollment dashboard ...');
        }, 2000);
    });

    $(document).on("change", "#id-no-select", function(e) {
        e.preventDefault();
        let selected = $("#id-no-select option:selected");
        $("input[name='signatory']").val(selected.attr("data-name").trim());
        $("input[name='position']").val(selected.attr("data-position"));
    });

    /** Stepper */ 
    $(document).on("click", ".next", () => {
        stepper.next();
    });
    
    $(document).on("click", ".previous", () => {
        stepper.previous();
    });

    /** Indigenous Group */
    $(document).on("click", ".i-group-opt", function() {
        $("[name='group-name']").prop("disabled", $(this).val() == "Yes" ? false : true);
    });

    hideSpinner();
});