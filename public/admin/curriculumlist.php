<?php include_once("../head.html"); ?>
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
        content: url('../../assets/kebab.svg');
    }

    .kebab:hover,
    .kebab:focus {
        background-color: #DCDCDC;
        box-shadow: none;
    }

    .spinner-grow {
        position: fixed;
        z-index: 99;
        overflow: show;
        margin: auto;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }
</style>

<body>
    <main class="container">
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
            <input type="search" class="form-control" placeholder="Search something here">
        </header>
        <div class="curriculum-con d-flex flex-wrap container">
            <!-- SPINNER -->
            <div class="spinner-grow" role="status" hidden>
                <span class="visually-hidden">Loading...</span>
            </div>
            <!-- No result message -->
            <div class="msg w-100 d-flex justify-content-center d-none">
                <p class="m-auto">No results found</p>
            </div>
            <?php require_once("../../src/getCurriculum.php");
            foreach ($curriculumList as $curr) {
                echo "<div id='" . $curr->code . "-card' class='card shadow-sm p-0'>
                        <div class='card-body'>
                            <div class='dropdown'>
                                <button class='kebab btn btn-link rounded-circle' data-bs-toggle='dropdown'></button>
                                <ul class='dropdown-menu'>
                                    <li><a class='dropdown-item' href='curriculum.php?id=" . $curr->code . "'>Edit</a></li>
                                    <li><a class='dropdown-item' href='#'>Archive</a></li>
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
        <?php include("../footer.html"); ?>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="addCurriculumModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">Add Curriculum</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <h6>Please complete the following:</h6>
                        <div class="form-group">
                            <label for="curriculumCode">Code</label>
                            <input type="text" name="code" class='form-control' placeholder="Enter unique code here. ex. K12A">
                            <label for="curriculumName">Name</label>
                            <input type="text" name="code" class='form-control' placeholder="ex. K12 Academic">
                            <label for="curriculumName">Description</label>
                            <textarea name="code" class='form-control' placeholder="ex. K-12 Basic Education Academic Track"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                    <a type="button" class="btn btn-primary" href="Add.php">Add</a>
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
    var msg = $('.msg')
    var timeout = null;

    $(document).ready(function() {
        kebab.click(function() {
            $(this).siblings('.dropdown-menu').show()
        })

        kebab.focusout(() => $('.dropdown-menu').hide()) // hides the all dropmenu if the kebab is unfocused

        // $('[data-link]').click(function () {
        //     window.location.href = $(this).attr('data-link')
        // })

        function displayResults (results) {
            if (results.length == 0) {
                msg.addClass('d-none')
                cards.each(function () {        // show all curriculum cards if the search bar is empty
                    $(this).show()      
                })
                return
            } 

            if (results.length == curriculumList.length) {
                cards.each(function () {
                    $(this).hide()
                    addCurriculumBtn.hide()      
                })
                msg.removeClass('d-none')
                return
            }

            results.forEach(curriculum => {
                cards.each(function() {
                    let element = $(this)
                    if (curriculum.code + '-card' == element.attr('id')) {
                        element.hide()
                        addCurriculumBtn.hide()
                    }
                })
            })
        }

        $('input[type=search]').keyup(function() {
            clearTimeout(timeout) // resets the timer
            timeout = setTimeout(() => { // executes the function after the specified milliseconds
                var keywords = $(this).val().trim().toLowerCase()
                let filterOutCurriculum = (curriculum) => { // returns the curriculum info that does not contain the keyword
                    return !(curriculum.code.toLowerCase().includes(keywords) || curriculum.description.toLowerCase().includes(keywords) || curriculum.title.toLowerCase().includes(keywords))
                }
                var results = curriculumList.filter(filterOutCurriculum)
                displayResults(results)
            }, 500)
        })

        $('.add-curriculum').click(() => $('#addCurriculumModal').modal('toggle'))
    })
</script>

</html>