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
$(function() {
    
    preload('#student');
    
    const readURL = input => {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#resultImg').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        };
    };

    $("#upload").change(function(){
        readURL(this);
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
        readpsaURL(this);
    });

    $(".psa-photo").click(()=> $("#psaUpload").click());

    $(document).on('click','.deactivate', function(){
        user_id = $(this).attr('data-user-id');
        let confirmationModal = $('#deactivateConfirmation');
        confirmationModal.find('.modal-indentifier').html(section);
        confirmationModal.modal('toggle');
    });

    $(document).on('click', '.deact-btn', function() {
        var action = 'deactivate'
        $.post("action.php", {user_id, action}, function() {	
            $('#deactivateConfirmation').modal("hide");
            showToast("success", "Student Deactivated.");
            // location.reload();
        });

    });

    hideSpinner();
});