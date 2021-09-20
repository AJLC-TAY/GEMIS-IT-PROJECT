let prepareSectionHTML = section => {
    let html = '';
    section.forEach(element => {
        var code = element.code;
        html += `<li class='list-group-item d-flex justify-content-between align-items-center'> ${code}
                <button class='transfer-option btn' id='${code}'>Transfer</button></li>`;
    });
    return html;
}
var message = 'Are you sure you want to transfer the student?';
var stud_id;
$(function() {
    // Data Picker Initialization
    // $('#datepicker').datepicker();
    // $('.transfer-stud').click(function(){
    //     $('#select-section-modal').modal('toggle')
    // })
    preload('#student');
    // $(document).on('click','.transfer-stud', function(){
    //     stud_id = $(this).attr('id');
    //     $('#select-section-modal').modal('toggle')
    // })

    // $('#select-section-modal').on('shown.bs.modal', function(){
    //     $.post('action.php', {action:'getSectionJSON'} ,(data) => {
    //         var sections = JSON.parse(data)
    //         $('.sec-list').html(prepareSectionHTML(sections))
    //     })
    // })

    

    const readURL = input => {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#resultImg').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
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
        }
    };

    $("#psaUpload").change(function(){
        readpsaURL(this);
    })

    $(".psa-photo").click(()=> $("#psaUpload").click());
    hideSpinner();
});