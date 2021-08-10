
    let tableId, url, method, id

    tableId = '#table'
    url = 'getAction.php?data=faculty'
    method = 'GET'
    id = 'teacher_id'
    search = true
    searchSelector = '#search-input'
    height = 425

    let onPostBodyOfTable = () => {

    }

    let faculty_table = new Table(tableId, url, method, id, id, height, search, searchSelector)

    preload('#faculty')
    
    $(function() {
        $('#edit-btn').click(function() {
            $(this).prop("disabled", true)
            $("#save-btn").prop("disabled", false)
            $(this).closest('form').find('.form-input').each(function() {
                $(this).prop('disabled', false)
            })
        })

        // $('#save-btn').click(function() {
        //     $(this).prop("disabled", true)
        //     $("#edit-btn").prop("disabled", false)
        //     $(this).closest('form').find('input').each(function() {
        //         $(this).prop('disabled', true)
        //     })
        // })

        hideSpinner()
    })