<?php
session_start();
if ($_SESSION['enrolled'] != TRUE) {
    header('Location: ../student/enrollment_form.php');
}
include_once("../inc/head.html"); ?>
<title>Enrollment | GEMIS</title>
</head>

<body>
    <!-- SPINNER -->
    <div id="main-spinner-con" class="spinner-con">
        <div id="main-spinner-border" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="container">
        <h3>You have successfully submitted an enrollment request for PCNHS Senior Highschool SY <? echo $_SESSION['school_year']; ?></h3>
        <h5>Please submit the physical copy of the following documents to the school to be fully enrolled.</h5>
        <p><b>Documents</b></p>
        <ul>
           <li>Form 137</li> 
           <li>PSA Birth Certificate</li> 
        </ul>
    </div>
    <?php include_once("../inc/footer.html"); ?>
    <script src="../js/common-custom.js"></script>
    <script>
        $(function () {
            $("#main-spinner-con").hide();
        });
    </script>
</body>

</html>