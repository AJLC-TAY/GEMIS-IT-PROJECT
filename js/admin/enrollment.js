
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

    /** Validate Form */
    $(document).on("click", "#validate-form [type='submit']", function (e) {
        e.preventDefault();
        let status = $(this).attr("name");
        let formData = $("#validate-form").serialize() + `&${status}=true`;
        console.log(formData);
        $.post("action.php", formData, function() {
            $(".edit-opt").hide();
            $("#valid-change-btn").closest(".badge").show();
            $("#status").html((status == "accept" ? "Enrolled" : "Rejected"));
        });
    });

    $(document).on("click", ".action", function () {
        $(this).hide();
        switch($(this).attr("data-type")) {
            case "change":
                $(".edit-opt").show();
                break;
            case "cancel":
                $(".edit-opt").hide();
                $("#valid-change-btn").show();
                break;
        }
    });

    /** Credential Page */
    $(document).on("click", "#pop",  function() {
        $('#imagepreview').attr('src', $('#imageresource').attr('src')); // here asign the image to the modal when the user click the enlarge link
        $('#imagemodal').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
    });

     /** Indigenous Group */
    $(document).on("click", ".i-group-opt", function() {
        $("[name='group-name']").prop("disabled", $(this).val() == "Yes" ? false : true);
    });
    
    /** Enrollment curriculum options */
    $(document).on("change, click", "#track-select, #program-select", function() {
        let type = $(this).attr("name");
        let value = $(this).val();
        let html = '';
        switch(type) {
            case 'track':
                enrollCurrOptions[value].programs.forEach(e => {
                    html += `<option value='${Object.keys(e)}'>${Object.values(e)}</option>`;
                })
                $("#program-select").html(html);
                break;
            case 'program':
                let track; 
                Object.entries(enrollCurrOptions).forEach(e => {
                    e[1].programs.forEach(ep => {
                        if (Object.keys(ep)[0] == value) {
                            track = e[0];
                        }
                    });
                });
                $("#track-select").val(track);
                break;
        }
    });

    hideSpinner();
});