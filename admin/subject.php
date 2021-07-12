<?php include_once ("../inc/head.html"); ?>
    <title>PCNHS GEMIS</title>
</head>

<?php 
    $content = null;
    $isAddPageUnderProgram = FALSE;
    include('../class/Administration.php');
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
    $(document).ready(function () {
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
    })
</script>
</html>