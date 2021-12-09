<?php
$school_year = $_SESSION['school_year'];
$current_quarter = $_SESSION['current_quarter'];
 $enrollment_item = '';
if ($_SESSION['enroll_status'] == 1) {
    $enrollment_item = " <li class='sub-menu'>"
            ."<a id='enrollment' href='enrollment.php'>"
                ."<i class='fa fa-tasks'></i>"
                ."<span>Enrollment</span>"
            ."</a>"
        ."</li>";
}
?>
<!--TOP BAR CONTENT & NOTIFICATIONS-->
<!-- HEADER START -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<header class="header black-bg">
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right"></div>
    </div>
    <!-- LOGO START -->
    <a href="index.php" class="logo"><b>PCNHS-SHS<span> GEMIS</span></b></a>
    <!-- LOGO END -->
    <div class="top-menu">
        <ul class="nav pull-right top-menu mt-3">
            <li>
                <h8 class="topbar fw-bold"><?php echo date('F j, Y'); ?></h8>
            </li>
            <li>
                <h8 class="topbar fw-bold">Hi, <?php echo $_SESSION['User']?></h8>
            </li>
            
            <li><a class="logout" href="" data-bs-toggle="modal" data-bs-target="#log-out-modal"><i class="fa fa-sign-out me-2"></i>Sign out</a></li>
        </ul>
    </div>
</header>
<!-- HEADER END -->
<!-- SIDE BAR START -->
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- SIDEBAR MENU START -->
        <ul class="sidebar-menu" id="nav-accordion">
            <?php
            if (empty($school_year)) {
                echo "<h5 class='text-center'>No initialized SY</h5>";
            } else {
                echo "<h5 class='text-center'>SY $school_year</h5>";
                echo "<p class='text-center text-light'><small>". ($current_quarter == '1' ? "First Quarter" : ($current_quarter == '2' ? "Second Quarter" : ($current_quarter == '3' ? "Third Quarter" : ($current_quarter == '4' ? "Fourth Quarter" : "Ended")))). " </small></p>";
            }
            ?>
            <li class="mt">
                <a id="home" href="index.php">
                    <i class="fa fa-home"></i>
                    <span>Home</span>
                </a>
            </li>
            <?php echo $enrollment_item; ?>
             
            <li class="sub-menu">
                <a id="student" href="student.php">
                    <i class="bi bi-person-square"></i>
                    <span>Student Profile</span>
                </a>
            </li>
            <li class="sub-menu">
                <a id="grade" href="gradeReport.php?id=<?php echo $_SESSION['id']; ?>">
                    <i class="bi bi-card-list"></i>
                    <span>Grade</span>
                </a>
            </li>
            <li class="sub-menu">
                <a id="transcript" href="transcript.php">
                    <i class="bi bi-list"></i>
                    <span>Transcript of Records</span>
                </a>
            </li>
        </ul>
        <!-- SIDEBAR MENU END -->
    </div>
</aside>
<!-- SIDEBAR END -->