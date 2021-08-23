<?php include_once("../inc/head.html"); 
    session_start();
?>
<title>School Year | GEMIS</title>
<link href='../assets/css/bootstrap-table.min.css' rel='stylesheet'>
</link>
</head>

<body>
    <!-- SPINNER -->
    <div class="spinner-con">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- SPINNER END -->
    <section id="container">
        <?php include_once('../inc/admin/sidebar.html'); ?>
        <!-- MAIN CONTENT START -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-10">
                        <div class="row mt ps-3">
                            <?php 
                                if (isset($_GET['action'])) {
                                    include_once("schoolyearform.php");
                                } else {
                                    include_once("schoolyearlist.php");
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <!--MAIN CONTENT END-->
                <!--FOOTER START-->
                <?php include_once("../inc/footer.html"); ?>
                <!--FOOTER END-->
            </section>
        </section>
    </section>
    <!-- VIEW MODAL -->
    <div class="modal" id="view-modal" tabindex="-1" aria-labelledby="modal viewSchoolYear" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="mb-0">School Year</h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                   <!-- Curriculum code -->
                    <div class="form-group row d-none">
                        <label for="curr-code-input" class="col-sm-3 col-form-label">Curriculum code</label>
                        <div class="col-sm-9">
                            <p id='curriculum-code'></p>
                        </div>
                    </div>
                    <!-- Curriculum code end -->
                    <!-- School year -->
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">School Year (Start-End)</label>
                        <div class="col-sm-9">
                            <p class='school-year'></p>
                        </div>
                    </div>
                    <!-- School year end -->
                    <!-- Grade level -->
                    <div class="form-group row">
                        <label for="grade-level-input" class="col-sm-3 col-form-label">Grade Level</label>
                        <div class="col-sm-9">
                            <select name="grade-level" id="grade-level-input" class="grd-level form-select">
                            <?php 
                                $grd_lvl_list = array('0' => '-- Select grade level --', '11' => '11', '12' => '12' );  
                                $grd_opt = '';
                                foreach($grd_lvl_list as $id => $value) {
                                    $grd_opt .= "<option value='$id'". ($id == 0 ? "selected" : "") .">$value</option>";
                                }
                                echo $grd_opt;
                            ?>
                            </select>
                        </div>
                    </div>
                    <!-- Grade level end -->
                    <!-- Current quarter -->
                    <div class="form-group row <?php echo $display?>">
                        <label for="grade-level-input" class="col-sm-3 col-form-label">Current Quarter</label>
                        <div class="col-sm-9">
                            <select name="quarter" id="grade-level-input" class="form-select" <?php echo $state; ?>>
                                <?php echo $quarter_opt;?>
                            </select>
                        </div>
                    </div>
                    <!-- Current quarter end -->
                    <!-- Enrollment status -->
                    <div class="form-group row">
                        <label for="grade-level-input" class="col-sm-3 col-form-label">Enrollment Status</label>
                        <div class="col-sm-9">
                            <div class="d-flex align-items-center">
                                <div class="form-check form-switch  my-auto ms-2 me-3">
                                    <input name='enrollment'  class="form-check-input" type="checkbox" id="enrollment-switch" title='Start/End enrollment'>
                                    <!-- <label class="form-check-label" for="flexSwitchCheckDefault">Default switch checkbox input</label> -->
                                </div>
                                <input value="" class='form-control m-0' id='enrollment-status' readonly>
                            </div>
                        </div>
                    </div>
                    <!-- Enrollment status end -->
                </form>
                </div>
                <div class="modal-footer">
                    <button class="close btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="submit-prog" form="prog-form" class="submit btn btn-primary" data-link='addProg.php'>Add</button>
                </div>
            </div>
        </div>
    </div>
    <!-- VIEW MODAL -->
    <!-- TOAST -->
    <div aria-live="polite" aria-atomic="true" class="position-relative" style="bottom: 0px; right: 0px">
        <div id="toast-con" class="position-fixed d-flex flex-column-reverse overflow-visible " style="z-index: 99999; bottom: 20px; right: 25px;"></div>
    </div>
    <!-- TOAST END -->
    <!-- JQUERY FOR BOOTSTRAP TABLE -->
    <script src="../assets/js/bootstrap-table.min.js"></script>
    <script src="../assets/js/bootstrap-table-en-US.min.js"></script>
    <script src="../js/common-custom.js"></script>
    <script type="module" src="../js/admin/schoolyear.js"></script>
</body>
</html>