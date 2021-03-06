<?php
// session handling
//session_start();
// $_SESSION['sy_id'] = 9;
// $_SESSION['sy_desc'] = "2021 - 2022";
// $_SESSION['user_type'] = 'ST';
// $_SESSION['id'] = 110001;
// $_SESSION['sy_id'] = 9;
 $school_year = $_SESSION['school_year'];

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
                <h8 class="topbar fw-bold">Date | <?php echo date('F j, Y'); ?></h8> 
            </li>
            <li>
                <h8 class="topbar fw-bold">Hi, <?php echo $_SESSION['User']?></h8>
            </li>
            
            <!-- <li><a class="topbar" href=""><i class="fa fa-user me-2"></i>Login as Admin</a></li> -->
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
            <h5 class="text-center">SY <?php echo $school_year ?></h5>
            <li class="mt">
                <a id="home" href="index.php">
                    <i class="fa fa-home"></i>
                    <span>Home</span>
                </a>
            </li>
            <!-- <?php echo $enrollment_item; ?> -->
            <li class="sub-menu">
                <a id="enrollment" href="enrollment.php?action=edit">
                <i class="fa fa-tasks"></i>
                    <span>Enrollment</span>
                </a>
            </li> 
            <li class="sub-menu">
                <a id="student" href="student.php">
                    <i class="bi bi-person-square"></i>
                    <span>Student Profile</span>
                </a>
            </li>
            <li class="sub-menu">
                <a id="grade" href="grade.php">
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
            <!-- <?php echo $award_coor_item; ?>  -->
        </ul>
        <!-- SIDEBAR MENU END -->
    </div>
</aside>
<!-- SIDEBAR END -->