<?php
$school_year = $_SESSION['school_year'];
$roles = $_SESSION['roles'];

$enrollment_item = '';
if (in_array('can_enroll', $roles)) {
    $enrollment_item = " <li class='sub-menu'>"
                ."<a id='enrollment' href='enrollment.php'>"
                    ."<i class='fa fa-tasks'></i>"
                    ."<span>Enrollment</span>"
                ."</a>"
            ."</li>";
}
$award_coor_item = '';
if (in_array('award_coor', $roles)) {
    $award_coor_item = " <li class='sub-menu'>"
                ."<a id='awards' href='awards.php'>"
                    ."<i class='bi bi-award-fill'></i>"
                    ."<span>Awards</span>"
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
            <li><a role="button" class="logout" data-bs-toggle="modal" data-bs-target="#log-out-modal"><i class="fa fa-sign-out me-2"></i>Sign out</a></li>
        </ul>
    </div>
</header>
<!-- HEADER END -->
<!-- SIDE BAR START -->
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- SIDEBAR MENU START -->
        <ul class="sidebar-menu" id="nav-accordion">
            <h5 class="centered">SY <?php echo $school_year ?></h5>
            <li class="mt">
                <a id="home" href="index.php">
                    <i class="fa fa-home"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="sub-menu">
                <a id='faculty' href="faculty.php">
                    <i class="bi bi-person-square"></i>
                    <span>Profile</span>
                </a>
            </li>
            <li class="sub-menu">
                <a id="advisory" href="advisory.php">
                    <i class="fa fa-graduation-cap"></i>
                    <span>Advisory Class</span>
                </a>
            </li>
            <li class="sub-menu">
                <a id="subject" href="subject.php">
                    <i class="bi bi-card-list"></i>
                    <span>Subject Class</span>
                </a>
            </li>
            <li class="sub-menu">
                <a id="attendance" href="attendance.php">
                    <i class="bi bi-clipboard-check"></i>
                    <span>Attendance</span>
                </a>
            </li>
            <?php echo $enrollment_item; ?>
            <?php echo $award_coor_item; ?>
            <li class="sub-menu">
                <a id='archived' href="archivedClasses.php">
                    <i class="fa fa-archive"></i>
                    <span>Archived Classes</span>
                </a>
            </li>
        </ul>
        <!-- SIDEBAR MENU END -->
    </div>
</aside>
<!-- SIDEBAR END -->