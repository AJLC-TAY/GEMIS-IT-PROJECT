
try {
    var stepper = new Stepper($('#stepper')[0])
} catch (e) {}

function generatePDF() {
    const template = document.querySelector(".template");
    var opt = {
        margin: 0.5,
        filename: 'myfile.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 4, dpi: 300 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    };
    html2pdf().from(template).set(opt).save();
}
window.generatePDF = generatePDF;

$(function() {
    preload("#enrollment", "#enrollment-sub");

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