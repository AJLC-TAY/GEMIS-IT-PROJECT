<?php 
$school_year = $_SESSION['school_year'];
$enroll_menu_item = '';
$award_menu_item = '';
$current_quarter = $_SESSION['current_quarter'];
if (isset($_SESSION['sy_id'])) {
    $enroll_menu_item = "<li id='enrollment' class='sub-menu'>
                <a href='javascript:;'>
                    <i class='fa fa-tasks'></i>
                    <span>Enrollment Management</span>
                </a>
                <ul class='sub'>
                    <li><a id='enrollment-sub' href='enrollment.php'>Enrollment</a></li>
                    <li><a id='set-up' href='enrollment.php?page=setup'>Set Up</a></li>
                    <li><a id='section' href='section.php'>Section</a></li>
                    <li><a id='sub-classes' href='section.php?page=sub_classes'>Subject Class</a></li>
                </ul>
            </li>";
    $award_menu_item = "<li class='sub-menu'>
                <a id='awards' href='award.php'>
                    <i class='bi bi-award-fill'></i>
                    <span>Awards</span>
                </a>
            </li>";
}
?>
<!--TOP BAR CONTENT & NOTIFICATIONS-->
<!-- HEADER START -->
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
            <li><a class="topbar" href=""><i class="fa fa-user me-2"></i><?php echo $_SESSION['User']; ?> (Logged in as Admin) </a></li>
            <li><a role="button" class="logout" data-bs-toggle="modal" data-bs-target="#log-out-modal"><i class="fa fa-sign-out me-2"></i>Sign out</a></li>
        </ul>
    </div>
</header>
<!-- HEADER END -->
<!-- SIDE BAR START -->
<aside>
    <div id="sidebar" class="nav-collapse ">
        <!-- SIDEBAR MENU START -->
        <ul class="sidebar-menu" id="nav-accordion">
            <?php
            if (empty($school_year)) {
                echo "<h5 class='text-center'>No initialized SY</h5>";
            } else {
                echo "<h5 class='text-center'>SY $school_year</h5>";
                echo "<p class='text-center text-light'><small>". ($current_quarter == '1' ? "First" : ($current_quarter == '2' ? "Second" : ($current_quarter == '3' ? "Third" : "Fourth"))). "  Quarter </small></p>";
            }
            ?>
            <li class="mt">
                <a id="home" href="index.php">
                    <i class="fa fa-home"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="sub-menu">
                <a id="admin" href="admin.php">
                    <i class="fa fa-user"></i>
                    <span>Admin</span>
                </a>
            </li>
            <li id="curr-management" class="sub-menu">
                <a href="javascript:;">
                    <i class="fa fa-book"></i>
                    <span>Curriculum Management</span>
                </a>
                <ul class="sub">
                    <li><a id="curriculum" href="curriculum.php">Curriculum</a></li>
                    <li><a id="program" href="program.php">Program/Strand</a></li>
                    <li><a id="subject" href="subject.php">Subject</a></li>
                    <li><a id="school-yr" href="schoolYear.php">School Year</a></li>
                </ul>
            </li>
            <?php echo $enroll_menu_item; ?>
            <li id="student" class="sub-menu">
                <a  href="student.php">
                    <i class="fa fa-book"></i>
                    <span>Student</span>
                </a>
            </li>
            <li class="sub-menu">
                <a id="faculty" href="faculty.php">
                    <i class="fa fa-users"></i>
                    <span>Faculty</span>
                </a>
            </li>      
            <?php echo $award_menu_item; ?>
            <li class="sub-menu">
                <a id='signatory' href="signatory.php">
                    <i class="fa fa-pencil-square-o"></i>
                    <span>Signatory Management</span>
                </a>
            </li>
            <li class="sub-menu">
                <a id='system-logs' href="systemLogs.php">
                    <i class="fa fa-list"></i>
                    <span>System Logs</span>
                </a>
            </li>
        </ul>
        <!-- SIDEBAR MENU END -->
    </div>
</aside>
<!-- SIDEBAR END -->