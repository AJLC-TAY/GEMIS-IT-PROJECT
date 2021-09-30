<?php
// session handling
session_start();
$school_year = $_SESSION['sy_desc'];
$roles = $_SESSION['roles'];

$enrollment_item = '';
if (in_array('can_enroll', $roles)) {
    $enrollment_item = " <li class='sub-menu'>"
                ."<a id='enrollment' href='enrollment.php'>"
                    ."<i class='fa fa-users'></i>"
                    ."<span>Enrollment</span>"
                ."</a>"
            ."</li>";
}

$award_coor_item = '';
if (in_array('award_coor', $roles)) {
    $award_coor_item = " <li class='sub-menu'>"
                ."<a id='awards' href='awards.php'>"
                    ."<i class='fa fa-users'></i>"
                    ."<span>Enrollment</span>"
                ."</a>"
            ."</li>";
}
?>
<!--TOP BAR CONTENT & NOTIFICATIONS-->
<!-- HEADER START -->
<header class="header black-bg">
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right"></div>
    </div>
    <!-- LOGO START -->
    <a href="index.php" class="logo"><b>PCNHS<span> GEMIS</span></b></a>
    <!-- LOGO END -->
    <div class="top-menu">
        <ul class="nav pull-right top-menu mt-3">
            <li>
                <h8 class="topbar fw-bold">Date | <?php echo date('F j, Y'); ?></h8>
            </li>
            <li><a class="topbar" href=""><i class="fa fa-user me-2"></i>Login as Admin</a></li>
            <li><a class="logout" href=""><i class="fa fa-sign-out me-2"></i>Sign out</a></li>
        </ul>
    </div>
</header>
<!-- HEADER END -->
<!-- SIDE BAR START -->
<aside>
    <div id="sidebar" class="nav-collapse ">
        <!-- SIDEBAR MENU START -->
        <ul class="sidebar-menu" id="nav-accordion">
            <h5 class="centered">SY <?php echo $school_year ?></h5>
            <li class="mt">
                <a id="home" href="index.php">
                    <i class="fa fa-home"></i>
                    <span>Home</span>
                </a>
            </li>
            <?php echo $enrollment_item; ?>
            <li class="sub-menu">
                <a id="students" href="students.php">
                    <i class="fa fa-graduation-cap"></i>
                    <span>Students</span>
                </a>
            </li>
            <li class="sub-menu">
                <a id="grade" href="grade.php">
                    <i class="fa fa-user"></i>
                    <span>Grade</span>
                </a>
            </li>
            <li class="sub-menu">
                <a id="attendance" href="attendance.php">
                    <i class="fa fa-user"></i>
                    <span>Attendance</span>
                </a>
            </li>
            <li class="sub-menu">
                <a id='archived' href="archived.php">
                    <i class="fa fa-pencil-square-o"></i>
                    <span>Archived Classes</span>
                </a>
            </li>
            <?php echo $award_coor_item; ?>
            <li class="sub-menu">
                <a id='faculty' href="profile.php">
                    <i class="fa fa-pencil-square-o"></i>
                    <span>Profile</span>
                </a>
            </li>
        </ul>
        <!-- SIDEBAR MENU END -->
    </div>
</aside>
<!-- SIDEBAR END -->