
try {
    var stepper = new Stepper($('#stepper')[0])
} catch (e) {}

$(function() {
    preload("#enrollment", "#enrollment-sub");

    /** Validation */
    try {
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    } catch (e) {}


    /** Select2 */
    try {
        $("#id-no-select").select2({
            theme: "bootstrap-5",
            width: null
        });
    } catch (e) {}

    
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

    /** Stepper */ 
    $(document).on("click", ".next", () => {
        stepper.next();
    });
    
    $(document).on("click", ".previous", () => {
        stepper.previous();
    });


    hideSpinner();
});