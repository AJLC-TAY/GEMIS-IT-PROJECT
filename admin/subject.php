<?php include_once ("../inc/head.html"); ?>
    <title>PCNHS GEMIS</title>
</head>

<?php 
    $content = null;
    $isAddPageUnderProgram = FALSE;
    include_once ('../class/Administration.php');
    include_once ('subjectForm.php');
    
    if (isset($_GET['state'])) {
        $state = $_GET['state'];
        if (isset($_GET['code'])) {
            $isAddPageUnderProgram = TRUE;
        }
        $content = getSubjectPageContent($state);
    } else {
        return;
    }

?>
<body>
    <section id="container">
        <?php include_once ('../inc/admin/sidebar.html'); ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <div class="col-lg-9">
                    <div class="row mt ps-3">
                        <?php echo $content->breadcrumb; ?>
                        <div class="row">
                            <?php echo $content->form; ?>
                        </div>
                    </div>
                </div>
                <!--main content end-->
                <!--footer start-->
                <?php include_once ("../inc/footer.html");?>
                <!--footer end-->
            </section>
        </section>
    </section>
</body>

<script type='text/javascript'>
    var isAddPageUnderProgram = <?php echo json_encode($isAddPageUnderProgram); ?>;
    var spinner = $('.spinner-border')
    $(document).ready(function () {
        $('.no-subject-msg').hide()
        let timeout = null;
        let spinner = $('.spinner-grow')

        $('#curr-management a:first').click()
        if (isAddPageUnderProgram) $('#program').addClass('active-sub')
        else $('#subject').addClass('active-sub')
   
        $('#req-btn').click(function () {
            $('#req-table-con').removeClass('d-none')  
        })

        $('#sub-type').change(function() {
            switch($(this).val()) {
                case 'applied':
                    $('#app-spec-options').find('input').each(function() {
                        $(this).prop('disabled', false)
                        $(this).attr('type', 'checkbox')
                    })
                    break;
                case 'specialized':
                    $('#app-spec-options').find('input').each(function() {
                        $(this).prop('disabled', false)
                        $(this).attr('type', 'radio')
                    })
                    break;
                default:
                    $('#app-spec-options').find('input').each(function() {
                        $(this).prop('disabled', true)
                    })
            }
        })

        $('#add-subject-form').submit(function(event) {
            event.preventDefault()
            spinner.show()
            var form  = $(this)
            var formData = form.serialize()
            console.log(formData)

            // $.post("action.php", formData, function(data) {

            // })

        })

        function prepareData(data) {
            if (data.length > 0) {
                var results = ''
                data.forEach((sub) => {
                    results += `<option value="${sub['code']}" class="dropdown-item subject" >${sub['name']}</option>`
                })
                return results
            } 
            return '<div class="h-100 d-flex align-items-center justify-content-center">No results found.</div>'           
        }

        function addButtonListeners(id) {
            $('.subject').click(function(e) {
                let searchList = $(`#${id}-results`)
                searchList.hide()
                $(`#${id}-msg`).remove();
                let selected = $(this)
                $(`#${id}requisite`).append([
                    "<li class='subject list-group-item d-flex justify-content-between align-items-center'>", 
                    `<option value='${selected.val()}'>${selected.text()}</option>`, 
                    "<button type='btn' class='btn btn-danger btn-sm del-sub'>Delete</button></li>"
                ].join(" "))           // ['a', 'b', 'c'].join(' ') returns 'a b c'
                $('.del-sub').click(function() {
                    let element =  $(this)
                    deleteSubject(id, element)
                })
            })
        }
    
        $('input[type=search]').keyup(function () {
            clearTimeout(timeout)                   // resets the timer
            timeout = setTimeout(() => {            // executes the function after the specified milliseconds
                let input = $(this)
                let keywords = input.val()
                var id = input.attr('id')           // co-search or pre-search
                var searchList = $(`#${id}-results`);
                if (keywords.trim().length > 0) {
                    $.ajax({
                        type: 'GET',
                        url: `getAction.php?data=subjects&keyword=${keywords}`,
                        dataType: 'json',
                        beforeSend: () => {
                            searchList.show()
                            searchList.append('<div class="h-100 d-flex align-items-center justify-content-center"><div class="spinner-border" role="status"></div></div>');
                        }, 
                        success: (data) => {
                            searchList.empty()                  // empty the search list of subjects
                            searchList.append(prepareData(data))
                        },
                        complete: () => addButtonListeners(id), 
                        error: () => {
                            searchList.empty()          
                            showEmptyMsg(id)
                        }
                    });
                } else {
                    searchList.hide()
                }
            }, 500)
        })
        $('.del-sub').click(deleteSubject)
    })

    function deleteSubject(id, element) {
        element.closest('li').remove()
        if ($(`#${id}requisite li`).length === 0) {
            // $(`#${id}requisite`).show();
            $(`#${id}requisite`).append(`<li id='${id}-msg' class='no-subject-msg border border-1 d-flex justify-content-center align-items-center'>No subjects yet.</li>`);
        } 
    }
</script>
</html>