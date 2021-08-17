$(function () {
    preload('#faculty')

    let selectedSubjects = []

    $(document).on('click', '.add-subject', function(event) {
        event.preventDefault()
        let id, subject, sub_code
        id = $('#search-input').val()
        subject = subjects.filter(function (element) {
            return element.sub_code == id
        })
        
        if (subject.length == 1) {
            subject = subject[0]
            code = subject.sub_code
            if (selectedSubjects.includes(code)) {
                return showToast('warning', 'Subject already added')
            }
            selectedSubjects.push(code)
            $('#emptyMsg').addClass('d-none')
            $('table').find('tbody').append(`<tr class='text-center'>
                <td scope="col"><input type="checkbox" value='${code}' /></td>
                <td scope='col'><input type="hidden" name='subjects[]' value='${code}'/>${code}</td>
                <td scope='col'>${subject.sub_name}</td>
                <td scope='col'>${subject.sub_type}</td>
                <td scope='col'><button id='${code}' class='remove-btn btn btn-sm btn-outline-danger m-auto'>REMOVE</button></td>
            </tr>`
            )
        } else {
            showToast('warning', 'Code did not match')
        }
    })

    const removeFromSelectedSubject = (subjectCode) => {
        const index = selectedSubjects.indexOf(subjectCode)
        if (index > -1) {
            selectedSubjects.splice(index, 1);
        }
    }

    $(document).on('click', '.remove-all-btn', function (event){
        event.preventDefault()
        let selected = $("input[type=checkbox]:checked")
        if (selected.length == 0) {
            showToast('warning', 'No subject is selected')
        } else {
            selected.each(function() {
                const element = $(this)
                const id = element.val()
                element.closest("tr").remove()
                removeFromSelectedSubject(id)
            })
            if (selectedSubjects.length == 0) $('#emptyMsg').removeClass('d-none')
        }
    })

    $(document).on('click', '.remove-btn', function (event){
        event.preventDefault()
        let element = $(this)
        let id = element.attr('id')
        element.closest("tr").remove()
        removeFromSelectedSubject(id)
        if (selectedSubjects.length == 0) $('#emptyMsg').removeClass('d-none')
    })

    /** Handling image upload */
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#resultImg').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#upload").change(function(){
        readURL(this);
    }); 

    // $('#faculty-form').submit(function(event) {
    //     event.preventDefault()
    //     console.log($(this).serialize())
    // })
    hideSpinner()
})