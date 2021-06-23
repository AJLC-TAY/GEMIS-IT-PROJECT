<?php include_once("../src/head.html"); ?>
<title>Curriculum Page | GEMIS</title>
</head>

<style>
    .card {
        width: 275px;
        height: 250px;
    }

    .curriculum-con {
        height: 80vh;
    }

    .kebab {
        width: 43px;
    }

    .kebab::before {
        content: url('/assets/kebab.svg');
    }

    .kebab:focus {
        background-color: #DCDCDC;
        box-shadow: none;
    }

    .spinner-border,
    [class*='-toast'] {
        position: fixed;
        z-index: 99;
        overflow: show;
        margin: auto;
    }

    .spinner-border {
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }

    [class*='-toast'] {
        right: 0.5in;
        bottom: 0.5in;
    }
</style>

<body>

    <section id="container">
        <?php include_once ('sidebar.html'); ?>
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
                            <div class="curriculum-con d-flex flex-wrap container">
                                <!-- SPINNER -->
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <!-- No result message -->
                                <div class="msg w-100 d-flex justify-content-center d-none">
                                    <p class="m-auto">No results found</p>
                                </div>
                                <?php require_once("../src/getCurriculum.php");
                                foreach ($curriculumList as $curr) {
                                    echo "<div data-id='" . $curr->code . "' class='card shadow-sm p-0'>
                                            <div class='card-body'>
                                                <div class='dropdown'>
                                                    <button type='button' class='kebab btn btn-link rounded-circle' data-bs-toggle='dropdown'></button>
                                                    <ul class='dropdown-menu'>
                                                        <li><a class='dropdown-item' href='curriculum.php?id=" . $curr->code . "'>Edit</a></li>
                                                        <li><button data-name='" . $curr->title ."' class='archive-btn dropdown-item'>Archive</button></li>
                                                    </ul>
                                                </div>
                                                <h4>$curr->title</h4>
                                                <p>$curr->description</p>
                                            </div>
                                            <div class='modal-footer p-0'>
                                                <a role='button' class='btn' href='curriculum.php?id=" . $curr->code . "'>View</a>
                                            </div>
                                        </div>";
                                }

                                    echo "<div class='btn add-curriculum card shadow-sm'>
                                        <div class='card-body'>
                                            Add Curriculum
                                        </div>
                                    </div>";
                                ?>
                            </div>
                            <button type="button" class="view-archive btn btn-link">View Archived Curriculums</button>
                        </div>
                    </div>
                </div>
                <!--main content end-->
                <!--footer start-->
                <?php include_once ("footer.html");?>
                <!--footer end-->
            </section>
        </section>
    </section>
        <!-- MODAL -->
        <div class="modal" id="add-curr-modal" tabindex="-1" aria-labelledby="modal addCurriculum" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <h4 class="mb-0">Add Curriculum</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="curriculum-form" action="">
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
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="submit-curriculum" form="curriculum-form" class="submit btn btn-primary" data-link='add.php'>Add</button>
                    </div>
                </div>
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
    var curriculumList = <?php echo json_encode($curriculumList); ?>;
    var curriculumCon = $('.curriculum-con')
    var cards = $('.card')
    var kebab = $('.kebab')
    var addCurriculumBtn = $('.add-curriculum')
    var noResultMsg = $('.msg')
    var spinner = $('.spinner-border')
    var searchInput = $('input[type=search]')
    var timeout = null

    
    function showWarningToast(msg) {
        let msgToast = $('.warning-toast')
        msgToast.find('.toast-body').text(msg)
        msgToast.toast('show')
    }

    /**
     *  Adds mouse click event listener to archive button and sets archive 
     *  confirmation modal with the specified remark and modal message.
     * 
     *  @param {string} remark - Identifies what type of data is being archived.
     *  @param {string} msg    - Modal message.
     */
    // function initializeArchiveModal (remark, msg) {
    //     $('.archive-btn').click(function() {
    //         let name = $(this).attr('data-name')
    //         $('#modal-identifier').html(`${name} ${remark}`)
    //         $('.modal-msg').html(msg)
    //         $('#archive-modal').modal('toggle')
    //     })
    // }

    $(document).ready(function() {
        spinner.fadeOut("slow")
        /** Display active menu item */
        $('#curr-management a:first').click()
        $('#curriculum').addClass('active-sub')

        /** Shows all curriculum cards with the add button */
        function showAllCurriculum() {
            spinner.show()
            cards.each(function() {
                $(this).show()
            })
            noResultMsg.addClass('d-none') // hide 'No result' message
            spinner.fadeOut()
        }

        /** Shows only the matching cards with the keyword */
        function showResults(results) {
            var len = results.length

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

        /** Executes when the clear button of the search input is clicked */
        document.getElementById('search-input').addEventListener("search", function(event) {
            if (searchInput.value.length == 0) showAllCurriculum()
        });

        searchInput.keyup(function(event) {
            spinner.show()
            clearTimeout(timeout) // resets the timer
            timeout = setTimeout(() => { // executes the function after the specified milliseconds
                var keywords = $(this).val().trim().toLowerCase()
                let filterCurriculum = (curriculum) => { // returns the curriculum info that contain the keyword
                    return (curriculum.code.toLowerCase().includes(keywords) || curriculum.description.toLowerCase().includes(keywords) || curriculum.title.toLowerCase().includes(keywords))
                }
                var results = curriculumList.filter(filterCurriculum)

                showResults(results.map((element) => { // map function returns an array containing the specified component of the element
                    return element.code
                }))
                spinner.fadeOut()
            }, 500)
        })

        $('.add-curriculum').click(() => $('#add-curr-modal').modal('toggle'))

        /*** Add new curriculum information through AJAX */
        $('#curriculum-form').submit(function(event) {
            event.preventDefault()
            var formData = $(this).serializeArray()
            var currCode = formData[0].value.trim()
            var currName = formData[1].value.trim()
            var currDesc = formData[2].value.trim()
            formData.push({
                'name': 'add_curriculum'
            })
            var showUniqueErrorMsg = () => $('.unique-error-msg').removeClass('invisible')

            // if (currCode.length == 0) showUniqueErrorMsg()

            // if (currName.length == 0) $('.name-error-msg').removeClass('invisible')
            // else {

            var processError = (error) => {
                switch (error) {
                    case 'codeError':
                        showUniqueErrorMsg()
                        break;
                    case 'undefinedName':
                        $('.name-error-msg').removeClass('invisible')
                        break;
                }
            }
            $.post('/src/add.php', formData, function(data) {
                // success
            }).fail(function(xhr, textStatus, error) {
                let responseText = JSON.parse(xhr.responseText)
                responseText.error.forEach(processError)
                // if (responseText.error === 'codeExist') showUniqueErrorMsg()
            })
            // }
        })

        /*** Reset curriculum form and hide error messages */
        $(".close").click(() => {
            $('#curriculum-form').trigger('reset')              // reset form
            $("[class*='error-msg']").addClass('invisible')     // hide error messages
        })


        $('.view-archive').click(() => $('#view-arch-curr-modal').modal('toggle'))
        // initializeArchiveModal('Curriculum', 'Archiving this curriculum will also archive all tracks, programs, subjects, and student grades under this curriculum.')
        $('.archive-btn').click(function() {
            let name = $(this).attr('data-name')
            $('#modal-identifier').html(`${name} Curriculum`)
            $('.modal-msg').html('Archiving this curriculum will also archive all programs/strands, subjects, and student grades under this curriculum.')
            $('#archive-modal').modal('toggle')
        })
    })
</script>

</html>