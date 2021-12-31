try {
    var stepper = new Stepper($('#stepper')[0])
} catch (e) {}
var balikAral = false;
function submitValidationForm(status) {
    showSpinner();
    let formData = $("#validate-form").serialize() + `&${status}=true`;
    $.post("action.php", formData, function() {
        // location.reload();
        $(".edit-opt").hide();
        let validChangeBtn = $("#valid-change-btn");
        validChangeBtn.closest(".badge").show();
        validChangeBtn.show();
        $("#status").html((status == "accept" ? "Enrolled" : "Rejected"));
        $("#confirmation-modal").modal("hide");
        $(".enroll-request-con select").prop("disabled", true);
        showToast("success", (status === 'accept' ? "Student is now enrolled" : "Rejected student's enrollment request"));

        hideSpinner();
    });
}

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
        showToast('dark', 'Downloading file ...');
        setTimeout(() => {
            showToast('dark', 'Redirecting to enrollment dashboard ...');
        }, 2000);
        setTimeout(() => {
            window.location.replace("enrollment.php?page=enrollees");
        }, 4000);
    });

    $(document).on("change", "#id-no-select", function(e) {
        e.preventDefault();
        let selected = $("#id-no-select option:selected");
        $("input[name='signatory']").val(selected.attr("data-name").trim());
        $("input[name='position']").val(selected.attr("data-position"));
    });

    /** Validate Form */
    $(document).on("click", ".validate", function (e) {
        let value = $("[name='valid']:checked").val();
        if (value == 'accept') {
            submitValidationForm(value);
        } else {
            $("#confirmation-modal").modal("show");
        }
    });

    $(document).on("click", ".validateReject", function (e) {
        submitValidationForm('reject');
    });

    $(document).on("click", ".action", function () {
        $(this).hide();
        switch($(this).attr("data-type")) {
            case "change":
                $(".edit-opt").show();
                $(".enroll-request-con select").prop("disabled", false);
                break;
            case "cancel":
                $(".edit-opt").hide();
                $("#valid-change-btn").show();
                $(".enroll-request-con select").prop("disabled", true);
                break;
        }
    });

    $("#psa").click(function(){
        let preview = $('#psaPreview');
        preview.modal('toggle');
    });

    $("#form137").click(function(){
        let preview = $('#137Preview');
        preview.modal('toggle');
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
                    e[1].programs.forEach(ep => { // e[1] holds the object containing the array e[0] = key of track
                        if (Object.keys(ep)[0] == value) { // ep [0] holds the program code
                            track = e[0];
                        }
                    });
                });
                $("#track-select").val(track);
                break;
        }
    });

    $(document).on("click", ".to-transferee-form", function() {
        let strandCode = $("#program-select").val();
        $("#chosen-strand").html($("#program-select option:selected").text().toUpperCase());
        $.get(`getAction.php?data=schedule&code=${strandCode}`, function (scheduleData) {
            let subData = JSON.parse(scheduleData).data;
            let template = $("#table-cell-template").html();
            let html = '';

            let elevenFirSem, elevenSecSem, twelveFirSem, twelveSecSem;
            elevenFirSem = subData[0].data.subjects.length;
            elevenSecSem = subData[1].data.subjects.length;
            twelveFirSem = subData[2].data.subjects.length;
            twelveSecSem = subData[3].data.subjects.length;

            function renderCellHTML(sub) {
                if (sub) {
                    return template.replaceAll("%ID%", sub.sub_code).replace("%SUBJECTNAME%", sub.sub_name);
                }
                return "<td></td>";
            }

            let count = Math.max(elevenFirSem, elevenSecSem, twelveFirSem, twelveSecSem);
            for (let i = 0; i < count; i++) {
                html += '<tr>';
                let subjectElevenFir = subData[0].data.subjects[i] ?? "";
                let subjectElevenSec = subData[1].data.subjects[i] ?? "";
                let subjectTwelveFir = subData[2].data.subjects[i] ?? "";
                let subjectTwelveSec = subData[3].data.subjects[i] ?? "";
                html += renderCellHTML(subjectElevenFir);
                html += renderCellHTML(subjectElevenSec);
                html += renderCellHTML(subjectTwelveFir);
                html += renderCellHTML(subjectTwelveSec);
                html += '</tr>';
            }
            console.log(html);
            $("#transfer-table tbody").html(html);
            $(".trans-detail input, textarea").prop("disabled", !balikAral);
            $("#transfer-table tbody").find(".form-check-input").prop("disabled", !balikAral);

        });
    });

    $(document).on("click", "[name='balik']", function() {
        balikAral = $(this).val() == "Yes";
        let disabled  = !(balikAral);
        $(".balik-con input, textarea").prop("disabled", disabled);
    });

    hideSpinner();
});