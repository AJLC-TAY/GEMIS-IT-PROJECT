let prepareSectionHTML = section => {
    let html = '';
    section.forEach(element => {
        var code = element.code;
        html += `<li class='list-group-item d-flex justify-content-between align-items-center'> ${code}
                <button class='transfer-option btn' id='${code}'>Transfer</button></li>`;
    });
    return html;
};
var message = 'Are you sure you want to transfer the student?';
var stud_id;
var gradesTemp = [];
$(function() {
    preload('#student', '#student-list');

    const readURL = (input, destination) => {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(destination).attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        };
    };

    $("#upload").change(function(){
        readURL(this, "#resultImg");
    });

    $(".profile-photo").click(()=> $("#upload").click());

    const readpsaURL = input => {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#psaResult').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        };
    };

    $("#psaUpload").change(function(){
        readURL(this, "#psaResult");
    });

    $("#form137Upload").change(function(){
        readURL(this, "#form137Result");
    });

    $(".psa-photo").click(()=> $("#psaUpload").click());
    $(".form137-photo").click(()=> $("#form137Upload").click());

    $("#psa").click(function(){
        let preview = $('#imgPreview');
        img = document.getElementById("psaPreview");
        img.src = this.src;
        //  this.src;
        preview.find('.modal-title').text(this.alt);
        preview.modal('toggle');
    });

    $("#form137").click(function(){
        let preview = $('#imgPreview');
        img = document.getElementById("psaPreview");
        img.src = this.src;
        //  this.src;
        preview.find('.modal-title').text(this.alt);
        preview.modal('toggle');
    });

    // $(document).on("click", "#reset-btn", function() {
    //     let modal = $("#confirmation-modal");
    //     modal.find(".message").html("Reset password of this student?");
    //     $("#reset-form").html(`<input type="hidden" name="id[]" value="${$(this).attr("data-id")}">`);
    //     modal.modal("show");
    // });

    $(document).on("submit", "#reset-form", function(e) {
        e.preventDefault();
        $.post("action.php", $(this).serializeArray(), function () {
            $("#reset-confirmation-modal").modal("hide");
            $(".modal-backdrop").remove();
            showToast('success', "Password successfully put to default");
        });
    });


    $(document).on("click", ".grade-table .action", function () {
        let gradeID = $(this).attr("data-grade-id");
        let row = $(this).closest("tr");
        let inputs = row.find("input");
        let inputOne = inputs.eq(0);
        let inputTwo = inputs.eq(1);
        let inputFin = inputs.eq(2);


        switch($(this).attr("data-action")) {
            case "edit":
                inputs.prop("disabled", false);
                gradesTemp.push({'gid' : gradeID, 'data' : [inputOne.val(), inputTwo.val(), inputFin.val()]});
                $(this).addClass('d-none');
                $(this).siblings(".edit-options").removeClass("d-none");
                return;
            case "save":
                let formData = [];
                inputs.each(function() {
                    formData.push({'name': $(this).attr('name'), 'value': $(this).val()});
                });
                formData.push({'name': 'action', 'value': 'editSubjectGrade'});
                $.post("action.php", formData, function() {
                    showToast('success', 'Subject successfully edited')
                });
                break;
            case "cancel":
                let gradeData;
                gradesTemp = gradesTemp.filter(e => {
                    if (e.gid == gradeID) {
                        gradeData = e;
                    }
                    return e.gid != gradeID;
                });

                console.log(gradeData);
                inputOne.val(gradeData.data[0]);
                inputTwo.val(gradeData.data[1]);
                inputFin.val(gradeData.data[2]);

                break;
        }
        let editOptions = $(this).closest('.edit-options');
        editOptions.addClass('d-none');
        editOptions.siblings("button").removeClass("d-none");
        inputs.prop("disabled", true);
    });
    hideSpinner();
});