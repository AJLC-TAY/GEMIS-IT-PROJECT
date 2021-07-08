<?php include_once("../inc/head.html"); ?>
<title>Curriculum Page | GEMIS</title>
<link rel="stylesheet" href="../css/general.css"></link>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" />
</head>


<body>
    <?php include('../class/Administration.php'); 
    $admin = new Administration();
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
                                        <li class="breadcrumb-item active" aria-current="page">Curriculum</li>
                                    </ol>
                                </nav>
                                <h2>Curriculum</h2>
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
                            <div class="curriculum-con d-flex flex-wrap container">
                                <?php // $curriculumList = $admin->listCurriculum();
                                // foreach ($curriculumList as  $curr) {
                                //     $code = $curr->get_cur_code();
                                //     $name = $curr->get_cur_name();
                                //     $desc = $curr->get_cur_desc();
                                //     echo "<div data-id='" .  $code . "' class='card shadow-sm p-0'>
                                //             <div class='card-body'>
                                //                 <div class='dropdown'>
                                //                     <button type='button' class='kebab btn btn-link rounded-circle' data-bs-toggle='dropdown'></button>
                                //                     <ul class='dropdown-menu'>
                                //                         <li><a class='dropdown-item' href='curriculum.php?code=" .   $code . "&state=edit'>Edit</a></li>
                                //                         <li><button data-name='" .  $name ."' class='archive-btn dropdown-item'>Archive</button></li>
                                //                         <li><button class='delete dropdown-item' id='" .  $code . "'>Delete</button></li>
                                //                     </ul>
                                //                 </div>
                                //                 <h4>". $name ." </h4>
                                //                 <p> ". $desc ."</p>
                                //             </div>
                                //             <div class='modal-footer p-0'>
                                //                 <a role='button' class='btn' href='curriculum.php?code=" .  $code . "'>View</a>
                                //             </div>
                                //         </div>";
                                // }

                                //     echo "<div class='btn add-curriculum card shadow-sm'>
                                //         <div class='card-body'>
                                //             Add Curriculum
                                //         </div>
                                //     </div>";
                                ?>
                            </div>
                            <button type="button" class="view-archive btn btn-link">View Archived Curriculums</button>
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
        <div class="modal" id="add-curr-modal" tabindex="-1" aria-labelledby="modal addCurriculum" aria-hidden="true">
            <div class="modal-dialog">
                <form id="curriculum-form" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-title">
                                <h4 class="mb-0">Add Curriculum</h4>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h6>Please complete the following:</h6>
                            <div class="form-group">
                                <label for="curr-code">Code</label>
                                <input id="curr-code" type="text" name="code" class='form-control' placeholder="Enter unique code here. ex. K12A" required>
                                <p class="unique-error-msg text-danger m-0 invisible"><small>Please provide a unique curriculum code</small></p>
                                <label for="curr-name">Name</label>
                                <input id="curr-name" type="text" name="name" class='form-control' placeholder="ex. K12 Academic" required>
                                <p class="name-error-msg text-danger m-0 invisible"><small>Please provide a curriculum name</small></p>
                                <label for="curr-desc">Short Description</label>
                                <textarea name="curriculum-desc" class='form-control' maxlength="250" placeholder="ex. K-12 Basic Education Academic Track"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="action" id="action" value="addCurriculum"/>
                            <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                            <input type="submit" form="curriculum-form" class="submit btn btn-primary" value="Add"/>
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

        <div class="modal" id="view-arch-curr-modal" tabindex="-1" aria-labelledby="modal viewArchivedCurriculum" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <h4 class="mb-0">Archived Curriculums</h4>
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

        <!-- TOAST -->
        <div aria-live="polite" aria-atomic="true" class="position-relative" style="min-height: 200px;">
            <div class="position-absolute" style="bottom: 20px; right: 25px;">
                <div class="toast warning-toast bg-danger text-white" data-animation="true" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-body"></div>
                </div>

                <div class="toast add-toast bg-dark text-white" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-body">
                        Curriculum successfully added
                    </div>
                </div>
            </div>
        </div>
</body>

<script type="text/javascript">
    //var curriculumList = <? // echo json_encode($curriculumList); ?>;
    var curriculumCon = $('.curriculum-con')
    var kebab = $('.kebab')
    var addCurriculumBtn = $('.add-curriculum')
    var noResultMsg = $('.msg')
    var spinner = $('.spinner-border')
    var searchInput = $('#search-input')
    var timeout = null
    var curriculumList = []


    function reloadCurriculum() {
        // location.reload()
        spinner.show()
        var action = 'getCurriculumJSON'
        $.post('action.php', {action}, function (data) {
            curriculumCon.empty()
            curriculumList = JSON.parse(data)
            curriculumList.forEach(element => {
                var code = element.cur_code
                var name = element.cur_name
                var desc = element.cur_desc
                curriculumCon.append(
                    `<div data-id='${code}' class='card shadow-sm p-0'>
                        <div class='card-body'>
                            <div class='dropdown'>
                                <button type='button' class='kebab btn btn-link rounded-circle' data-bs-toggle='dropdown'></button>
                                <ul class='dropdown-menu'>
                                    <li><a class='dropdown-item' href='curriculum.php?code=${code}'>Edit</a></li>
                                    <li><button data-name='${name}' class='archive-btn dropdown-item'>Archive</button></li>
                                    <li><button class='delete dropdown-item' id='${code}'>Delete</button></li>
                                </ul>
                            </div>
                            <h4>${name}</h4>
                            <p>${desc}</p>
                        </div>
                        <div class='modal-footer p-0'>
                            <a role='button' class='btn' href='curriculum.php?code=${code}'>View</a>
                        </div>
                    </div>`
                )
            })
            curriculumCon.append(`<div class='btn add-curriculum card shadow-sm'>
                                    <div class='card-body'> Add Curriculum</div>
                                </div>`)
        })

        spinner.fadeOut()
    }
     
    function showWarningToast(msg) {
        let msgToast = $('.warning-toast')
        msgToast.find('.toast-body').text(msg)
        msgToast.toast('show')
    }

    /** Shows all curriculum cards with the add button */
    function showAllCurriculum() {
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
        if (len === curriculumList.length) return showAllCurriculum()

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
        /** Fetch data */
        reloadCurriculum()
        spinner.fadeOut("slow")

        /** Display active menu item */
        $('#curr-management a:first').click()
        $('#curriculum').addClass('active-sub')
    
        $('#curriculum-form').submit(function(event) {
            event.preventDefault()
            spinner.show()
            var form = $(this)
            var formData = form.serialize()
            $.post("action.php", formData, function(data) {
                form.trigger('reset')
                $('#add-curr-modal').modal('hide')
                reloadCurriculum()
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
        if (searchInput.val().length == 0) showAllCurriculum()
    })

    $(document).on('keyup', '#search-input', function() {
        spinner.show()
        clearTimeout(timeout) // resets the timer
        timeout = setTimeout(() => { // executes the function after the specified milliseconds
            var keywords = $('#search-input').val().trim().toLowerCase()
            let filterCurriculum = (curriculum) => { // returns the curriculum info that contain the keyword
                return (curriculum.cur_code.toLowerCase().includes(keywords) || curriculum.cur_desc.toLowerCase().includes(keywords) || curriculum.cur_name.toLowerCase().includes(keywords))
            }
            var results = curriculumList.filter(filterCurriculum)
            showResults(results.map((element) => { // map function returns an array containing the specified component of the element
                return element.cur_code
            }))
            spinner.fadeOut()
        }, 500)
    })

    $(document).on('click', '.view-archive', () => $('#view-arch-curr-modal').modal('toggle'))

    $(document).on('click', '.add-curriculum', () => $('#add-curr-modal').modal('toggle'))

    /*** Modal Options */
    $(document).on('click', '.archive-btn', function() {
        let name = $(this).attr('data-name')
        $('#modal-identifier').html(`${name} Curriculum`)
        $('.modal-msg').html('Archiving this curriculum will also archive all programs/strands, subjects, and student grades under this curriculum.')
        $('#archive-modal').modal('toggle')
    })

    $(document).on('click', '.delete', function() {
        spinner.show()
        var code = $(this).attr('id')
        var action = "deleteCurriculum"

        if(confirm("Are you sure you want to delete this Curriculum?")) {
            $.post("action.php", {code, action}, function(data) {					
                reloadCurriculum()
            })
        } else {
            return false
        }
        spinner.fadeOut()
    })

    /*** Footer modal buttons */
    /*** Reset curriculum form and hide error messages */
    $(document).on('click', ".close", () => {
        $('#curriculum-form').trigger('reset')              // reset form
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