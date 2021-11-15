try {
    var stepper = new Stepper($('#stepper')[0])
} catch (e) {}

$(function() {
    preload("#enrollment", "#enrollment-sub");

    /** Validation */
   


    /** Select2 */
    try {
        $("#id-no-select").select2({
            theme: "bootstrap-5",
            width: null
        });
    } catch (e) {}

    
    // $(document).on('submit', '#enroll-report-form', function() {
    //     showToast('dark', 'Downloading file ...');
    //     setTimeout(() => {
    //         showToast('dark', 'Redirecting to enrollment dashboard ...');
    //     }, 2000);
    //     setTimeout(() => {
    //         window.location.replace("enrollment.php?page=enrollees");
    //     }, 4000);
    // });

    // $(document).on("change", "#id-no-select", function(e) {
    //     e.preventDefault();
    //     let selected = $("#id-no-select option:selected");
    //     $("input[name='signatory']").val(selected.attr("data-name").trim());
    //     $("input[name='position']").val(selected.attr("data-position"));
    // });

    /** Stepper */ 
    $(document).on("click", ".next", function(e) {
        e.preventDefault();
        stepper.next();
    });
    
    $(document).on("click", ".previous", function(e) {
        e.preventDefault();
        stepper.previous();
    });

    /** Validate Form */
    $(document).on("click", "#enrollment-form [type='submit']", function (e) {
        console.log("submit clicked");
        
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

    hideSpinner();
});