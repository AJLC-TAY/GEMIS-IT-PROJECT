<?php 
$school_year = $_SESSION['school_year'];
?>
<!DOCTYPE html>
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
            <h5 class="text-center"><?php echo (empty($school_year) ? "No initialized SY" : 'SY '.$school_year); ?></h5>
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
                    <li><a id="school-yr" href="schoolYear.php">School Year</a></li>
                    <li><a id="curriculum" href="curriculum.php">Curriculum</a></li>
                    <li><a id="program" href="program.php">Program/Strand</a></li>
                    <li><a id="subject" href="subject.php">Subject</a></li>
                </ul>
            </li>
            <li id="enrollment" class="sub-menu">
                <a href="javascript:;">
                    <i class="fa fa-tasks"></i>
                    <span>Enrollment Management</span>
                </a>
                <ul class="sub">
                    <li><a id="enrollment-sub" href="enrollment.php">Enrollment</a></li>
                    <li><a id="set-up" href="enrollment.php?page=setup">Set Up</a></li>
                    <li><a id="section" href="section.php">Section</a></li>
                    <li><a id="sub-classes" href="section.php?page=sub_classes">Subject Class</a></li>
                </ul>
            </li>
            <li id="student" class="sub-menu">
                <a href="javascript:;">
                    <i class="fa fa-book"></i>
                    <span>Student</span>
                </a>
                <ul class="sub">
                    <li><a id="student-list" href="student.php">Student List</a></li>
                    <li><a id="grade-rect" href="student.php?page=grade_rectification">Grade Rectification</a></li>
                </ul>
            </li>
            <li class="sub-menu">
                <a id="faculty" href="faculty.php">
                    <i class="fa fa-users"></i>
                    <span>Faculty</span>
                </a>
            </li>      
            <li class="sub-menu">
                <a id="awards" href="award.php">
                    <i class="bi bi-award-fill"></i>
                    <span>Awards</span>
                </a>
            </li>
            <li class="sub-menu">
                <a id='signatory' href="signatory.php">
                    <i class="fa fa-pencil-square-o"></i>
                    <span>Signatory Management</span>
                </a>
            </li>
        </ul>
        <!-- SIDEBAR MENU END -->
    </div>
</aside>
<!-- SIDEBAR END -->