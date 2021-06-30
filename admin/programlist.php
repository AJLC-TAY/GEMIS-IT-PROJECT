<?php include_once("../inc/head.html"); ?>
<title>Program Page | GEMIS</title>
<link rel="stylesheet" href="../css/general.css"></link>
</head>

<body>
    <?php include('../class/Administration.php'); 
    $admin = new Administration();

    require_once('../class/Dataclasses.php');
    ?>

    <section id="container">
        <?php include_once ('../inc/admin/sidebar.html'); ?>
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="row mt ps-3">
                            <!-- HEADER -->
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Programs</li>
                                    </ol>
                                </nav>
                                <h2>Programs</h2>
                                <!-- SEARCH BAR -->
                                <input id="search-input" type="search" class="form-control" placeholder="Search something here">
                            </header>
                            <!-- SPINNER -->
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <!-- No result message -->
                            <div class="msg w-100 d-flex justify-content-center d-none">
                                <p class="m-auto">No results found</p>
                            </div>
                            <div class="program-con d-flex flex-wrap container">
                                <?php $programList = $admin->listPrograms();
                                foreach ($programList as  $prog) {
                                    $code = $prog->get_prog_code();
                                    $curr_code = $prog->get_curr_code();
                                    $desc = $prog->get_prog_desc();
                                    
                                    echo "<div data-id='" .  $code . "' class='card shadow-sm p-0'>
                                            <div class='card-body'>
                                                <div class='dropdown'>
                                                    <button type='button' class='kebab btn btn-link rounded-circle' data-bs-toggle='dropdown'></button>
                                                    <ul class='dropdown-menu'>
                                                        <li><a class='dropdown-item' href='program.php?id=" .   $code . "'>Edit</a></li>
                                                        <li><button data-name='" .  $desc ."' class='archive-btn dropdown-item'>Archive</button></li>
                                                        <li><button class='delete dropdown-item' id='" . $code . "'>Delete</button></li>
                                                    </ul>
                                                </div>
                                                <h4>". $desc ." </h4>
                                                <p> ". $curr_code ." | ". $code ."</p>
                                            </div>
                                            <div class='modal-footer p-0'>
                                                <a role='button' class='btn' href='program.php?code=" .  $code . "'>View</a>
                                            </div>
                                        </div>";
                                }

                                    echo "<div class='btn add-program card shadow-sm'>
                                        <div class='card-body'>
                                            Add Program
                                        </div>
                                    </div>";
                                ?>
                            </div>
                            <button type="button" class="view-archive btn btn-link">View Archived Programs</button>
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

     <!-- MODAL -->
     <div class="modal" id="add-prog-modal" tabindex="-1" aria-labelledby="modal addProgram" aria-hidden="true">
        <div class="modal-dialog">
            <form id="program-form" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <h4 class="mb-0">Add Program</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Please complete the following:</h6>
                        <div class="form-group">
                            <label for="prog-code">Code</label>
                            <input id="prog-code" type="text" name="code" class='form-control' placeholder="Enter unique code here. ex. ABM" required>
                            <p class="unique-error-msg text-danger m-0 invisible"><small>Please provide a unique program code</small></p>
                            <label for="prog-desc">Description</label>
                            <input id="prog-name" type="text" name="desc" class='form-control' placeholder="ex. Accaountancy,Business, and Management" required>
                            <p class="name-error-msg text-danger m-0 invisible"><small>Please provide a unique program description</small></p>
                            <label for="curr-code">Curriculum Code</label>
                            <input id="curr-code" type="text" name="curr-code" class='form-control' placeholder="ex. K12 Academic" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="action" id="action" value="addProgram"/>
                        <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                        <input type="submit" form="program-form" class="submit btn btn-primary" value="Add"/>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="archive-modal" tabindex="-1" aria-labelledby="modal confirmation msg" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Confirmation</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Do you want to archive <span id="modal-identifier"></span>?</h5>
                    <p class="modal-msg"></p>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary close-btn">Archive</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="view-arch-prog-modal" tabindex="-1" aria-labelledby="modal viewArchivedProgram" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Archived Programs</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="overflow-auto" style="height: 50vh;">

                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Junior High School
                                <button class="btn btn-link">Unarchive</button>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Junior High School
                                <button class="btn btn-link">Unarchive</button>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Junior High School
                                <button class="btn btn-link">Unarchive</button>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Junior High School
                                <button class="btn btn-link">Unarchive</button>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Junior High School
                                <button class="btn btn-link">Unarchive</button>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Junior High School
                                <button class="btn btn-link">Unarchive</button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- Scripts -->
<script type="text/javascript">
    var programList = <?php echo json_encode($programList); ?>;
    var programCon = $('.progam-con')
    var kebab = $('.kebab')
    var addProgramBtn = $('.add-program')
    var noResultMsg = $('.msg')
    var spinner = $('.spinner-border')
    var searchInput = $('#search-input')
    var timeout = null


    function reloadProgram() {
        // location.reload()
        spinner.show()
        var action = 'listProgramsJSON'
        $.post('action.php', {action}, function (data) {
            programCon.empty()
            programList = JSON.parse(data)
            programList.forEach(element => {
                var prog_code = element.prog_code
                var cur_code = element.curr_code
                var prog_desc = element.prog_desc
                programCon.append(
                    `<div data-id='${prog_code}' class='card shadow-sm p-0'>
                                            <div class='card-body'>
                                                <div class='dropdown'>
                                                    <button type='button' class='kebab btn btn-link rounded-circle' data-bs-toggle='dropdown'></button>
                                                    <ul class='dropdown-menu'>
                                                        <li><a class='dropdown-item' href='program.php?id="${prog_code}'>Edit</a></li>
                                                        <li><button data-name='${prog_desc}' class='archive-btn dropdown-item'>Archive</button></li>
                                                        <li><button class='delete dropdown-item' id='${prog_code}'>Delete</button></li>
                                                    </ul>
                                                </div>
                                                <h4>${prog_desc}</h4>
                                                <p>${cur_code} | "${prog_code}"</p>
                                            </div>
                                            <div class='modal-footer p-0'>
                                                <a role='button' class='btn' href='program.php?code=${prog_code}'>View</a>
                                            </div>
                                        </div>`
                )
            })
            programCon.append(`<div class='btn add-program card shadow-sm'>
                                    <div class='card-body'> Add Program</div>
                                </div>`)
        })

        spinner.fadeOut()
    }
     
    function showWarningToast(msg) {
        let msgToast = $('.warning-toast')
        msgToast.find('.toast-body').text(msg)
        msgToast.toast('show')
    }

    /** Shows all program cards with the add button */
    function showAllProgram() {
        spinner.show()
        $('.card').each(function() {
            $(this).show()
        })
        noResultMsg.addClass('d-none') // hide 'No result' message
        spinner.fadeOut()
    }

    /** Shows only the matching cards with the keyword */
    function showResults(results) {
        var len = results.length
        var cards = $('.card')
        if (len === programList.length) return showAllProgram()

        if (len > 0) {
            noResultMsg.addClass('d-none')
            cards.each(function() {
                var card = $(this)
                if (results.includes(card.attr('data-id'))) card.show()
                else card.hide()
            })
            return
        }

        // no results found at this point
        cards.each(function() {
            $(this).hide()
            noResultMsg.removeClass('d-none')
        })
    }

    $(document).ready(function() {
        spinner.fadeOut("slow")
        /** Display active menu item */
        $('#curr-management a:first').click()
        $('#program').addClass('active-sub')
    
        $('#program-form').submit(function(event) {
            event.preventDefault()
            spinner.show()
            var form = $(this)
            var formData = form.serialize()
            $.post("action.php", formData, function(data) {
                form.trigger('reset')
                $('#add-prog-modal').modal('hide')
                reloadProgram()
            }).fail(function () {

            })

            /** Example of ajax */
            // $.ajax({
            //     url:"action.php",
            //     method:"POST",
            //     data: formData,
            //     processData: false,
            //     contentType: false,
            //     success: function(data){				
            //         $(this).trigger('reset')
            //         $('#add-curr-modal').modal('hide')		
            //     },
            //     error: function() {
            //         alert('error')
            //     }

            // })
        })

         
    })

    /*** Event delegation applied here. This concept binds all the event listener to the target element even when dynamically created. */

    $(document).on('search', '#search-input', function () {
        if (searchInput.val().length == 0) showAllProgram()
    })

    $(document).on('keyup', '#search-input', function() {
        spinner.show()
        clearTimeout(timeout) // resets the timer
        timeout = setTimeout(() => { // executes the function after the specified milliseconds
            var keywords = $('#search-input').val().trim().toLowerCase()
            let filterProgram = (program) => { // returns the curriculum info that contain the keyword
                return (program.prog_code.toLowerCase().includes(keywords) || curriculum.prog_desc.toLowerCase().includes(keywords) || prog.curr_code.toLowerCase().includes(keywords))
            }
            var results = programList.filter(filterProgram)
            showResults(results.map((element) => { // map function returns an array containing the specified component of the element
                return element.prog_code
            }))
            spinner.fadeOut()
        }, 500)
    })

    $(document).on('click', '.view-archive', () => $('#view-arch-prog-modal').modal('toggle'))

    /*** Add Modal Section */
    $(document).on('click', '.add-program', () => $('#add-prog-modal').modal('toggle'))

    /*** Modal Options */
    $(document).on('click', '.archive-btn', function() {
        let name = $(this).attr('data-name')
        $('#modal-identifier').html(`${name} Program`)
        $('.modal-msg').html('Archiving this program will also archive all subjects, and student grades under this program/strand.')
        $('#archive-modal').modal('toggle')
    })

    $(document).on('click', '.delete', function() {
        spinner.show()
        var code = $(this).attr('id')
        var action = "deleteProgram"

        if(confirm("Are you sure you want to delete this Program?")) {
            $.post("action.php", {code, action}, function(data) {					
                reloadProgram()
            })
        } else {
            return false
        }
        spinner.fadeOut()
    })

    /*** Footer modal buttons */
    /*** Reset Program form and hide error messages */
    $(document).on('click', ".close", () => {
        $('#program-form').trigger('reset')              // reset form
        $("[class*='error-msg']").addClass('invisible')     // hide error messages
    })

/*** Add new curriculum information through AJAX */
// $('#curriculum-form').submit(function(event) {
//     event.preventDefault()
//     spinner.show()
//     var element = $(this)
//     var formData = element.serializeArray()
//     var currCode = formData[0].value.trim()
//     var currName = formData[1].value.trim()
//     var currDesc = formData[2].value.trim()
//     var showUniqueErrorMsg = () => $('.unique-error-msg').removeClass('invisible')

//     // if (currCode.length == 0) showUniqueErrorMsg()

//     // if (currName.length == 0) $('.name-error-msg').removeClass('invisible')
//     // else {

//     $.post('../src/admin/add.php', formData, function(data) {
//         // success
//         element.closest('.modal').modal('toggle')
//         location.reload()
//     }).fail(function(xhr, textStatus, error) {
//         let responseText = JSON.parse(xhr.responseText)
//         responseText.error.forEach(processError)
//     })
// })
</script>
</html>