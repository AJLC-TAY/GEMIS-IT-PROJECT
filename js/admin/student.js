let prepareSectionHTML = section => {
    let html = ''
    section.forEach(element => {
        var code = element.code
        html += `<li class='list-group-item d-flex justify-content-between align-items-center'> ${code}
                <button data-name='$name' class='transfer-option btn' id='${code}'>Transfer</button></li>`
    })
    return html
}
var message = 'Are you sure you want to transfer the student?'
$(function() {
    // Data Picker Initialization
    // $('#datepicker').datepicker();
    $('.transfer-stud').click(function(){
        $('#select-section-modal').modal('toggle')
    })

    $('#select-section-modal').on('shown.bs.modal', function(){
        $.post('action.php', {action:'getSectionJSON'} ,(data) => {
            var sections = JSON.parse(data)
            $('.sec-list').html(prepareSectionHTML(sections))
        })
    })

    $("transfer-student-confirmation").on('shown.bs.modal', function (e) {
        $("#select-section-modal").modal("hide");
    });

    $(document).on('click', '.transfer-btn', function() {
        $('#transfer-student-confirmation').modal('hide')	
        var code = $(this).attr('id')
        var action = `transferStudent`
        $.post("action.php", {code, action}, function(data) {	
            $('#transfer-student-confirmation').modal('hide')	
        })
    })


    $(document).on('click', '.transfer-option', function() {
        var code = $(this).attr('id')
        let transferModal = $('#transfer-student-confirmation')
        transferModal.find('#modal-identifier').html(`adf`)
        transferModal.find('.modal-msg').html(message)
        transferModal.find('.transfer-btn').attr('id', code)
        transferModal.modal('toggle')
    })
})