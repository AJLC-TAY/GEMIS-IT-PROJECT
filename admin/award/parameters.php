<?php
include("../class/Administration.php");
$admin = new Administration();
$result = $admin->query("SELECT sub_code, sub_name FROM sysub JOIN subject USING (sub_code) WHERE sy_id = '{$_SESSION['sy_id']}';");
$subjects = [];
while ($row = mysqli_fetch_assoc($result)) {
    $subjects[$row['sub_code']] = $row['sub_name'];
}

$param = [
        "Highest" => ['min' => "98", 'max' => "100"],
        "High" => ['min' => "95", 'max' => "97"],
        "With" => ['min' => "90", 'max' => "94"],
];

//$subjects = $admin->getCurrentSubjects();
?>
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Award</a></li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between mb-3">
        <h3 class="fw-bold">Award Parameters</h3>
    </div>
</header>
<div class="container">
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card pt-3 px-3 pb-2">
                <form id="acad-parameter-form" action="award.php?type=ae" method="post">
                    <div class="row justify-content-between align-content-center px-0">
                        <div class="col-md-auto my-1">
                            <h6 class="mb-0 fw-bold">Academic Excellence</h6>
                        </div>
                        <div class="col-md-auto d-flex justify-content-lg-end my-1">
                            <div class="col-auto me-3">
                                <select required class="form-select form-select-sm mb-0" name="semester" id="semester">
                                    <option disabled selected value="">Semester</option>
                                    <option value="1">First</option>
                                    <option value="2">Second</option>
                                </select>
                            </div>
                            <div class="col-auto me-3">
                                <select required class="form-select form-select-sm mb-0" name="grade" id="grade">
                                    <option disabled selected value="">Grade Level</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" form="acad-parameter-form" class="btn-sm btn btn-dark"><i class="bi bi-funnel me-2"></i>Generate</button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="action" value="report">
                    <input type="hidden" name="type" value="aExcellence">
                    <table class="table table-sm table-hover table-bordered mt-2">
                        <thead class="text-center">
                        <th>Description</th>
                        <th colspan='2'>Range Min-Max (Grades)</th>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($param as $info => $range) {
                            echo "<tr>
                                <td align='center' class='align-middle'>$info Honor</td>";
                                foreach ($range as $i => $val) {
                                    echo "<td><input required value='$val' name='$info-honor-$i' type='text' class='form-control form-control-sm number text-end mb-1' placeholder='Enter Value'></td>";
                                }
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row card p-3">
                <h5>Perfect Attendance</h5>
                <form id="attendance-form" action="award.php?type=pa" method="post">
                    <input type="hidden" name="action" value="report">
                    <input type="hidden" name="type" value="attendance">
                    <button type="submit" form="attendance-form" class="btn-sm btn btn-dark w-100 mt-3"><i class="bi bi-funnel me-2"></i>Generate</button>
                </form>
                <hr class="my-2">
                <form id="other-award-form" action="award.php?type=re" method="post">
                    <div class="row mb-3">
                        <div class="col-auto">
                            <h5>Other awards</h5>
                        </div>
                        <div class="col-auto">
                            <select name="sub_code" class="form-select form-select-sm" id="subject">
                                <?php
                                foreach($subjects as $sub_code => $sub_name) {
                                    echo "<option value='$sub_code'>$sub_name</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="report">
                    <input type="hidden" name="type" value="research">
                    <div class="row">
                        <div class="col-6">Lowest Grade</div>
                        <div class="col-4">
                            <input required value='90' name='filter' type='text' class='form-control form-control-sm number text-end mb-0' placeholder='Enter Value'>
                        </div>
                        <div class="col-2">
                            <button type="submit" form="other-award-form" class="btn-sm btn btn-dark"><i class="bi bi-funnel"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
<!--        <div class="col-lg-4 mb-4">-->
<!--            <div class="card p-3 h-100 d-flex-column justify-content-between">-->
<!--                <h5>Conduct Award</h5>-->
<!--                <form id="conduct-form" action="award.php?type=conduct" method="post">-->
<!--                    <input type="hidden" name="action" value="report">-->
<!--                    <input type="hidden" name="type" value="conduct">-->
<!--                    <p>They must have obtained a rating of at least 75% “Always Observed” (AO) at the end of the school year (with at least 21 out of 28 AO rating in the report card). </p>-->
<!--                    <button type="submit" form="conduct-form" class="btn-sm btn btn-dark w-100"><i class="bi bi-funnel me-2"></i>Generate</button>-->
<!--                </form>-->
<!--            </div>-->
<!--        </div>-->
    </div>

    <div class="row">
<!--        <div class="col-md-4">-->
<!--            <div class="card p-3 mb-4">-->
<!--                <h5>Perfect Attendance</h5>-->
<!--                <form id="attendance-form" action="award.php?type=attendance" method="post">-->
<!--                    <input type="hidden" name="action" value="report">-->
<!--                    <input type="hidden" name="type" value="attendance">-->
<!--                    <button type="submit" form="attendance-form" class="btn-sm btn btn-dark w-100 mt-3"><i class="bi bi-funnel me-2"></i>Generate</button>-->
<!--                </form>-->
<!--            </div>-->
<!--        </div>-->

<!--        <div class="col-md-8">-->
<!--            <div class="card p-3 mb-4">-->
<!--                <div class="d-flex justify-content-between align-content-center px-0">-->
<!--                    <h5 class="mb-0">Other Awards</h5>-->
<!--                </div>-->
<!--                <table class="table table-sm table-hover table-bordered mt-3">-->
<!--                    <thead class="text-center">-->
<!--                    <th>Awards for</th>-->
<!--                    <th>Minimum Grade</th>-->
<!--                    <th>Action</th>-->
<!--                    </thead>-->
<!--                    <tbody>-->
<!--                    <tr>-->
<!--                        <td>Research</td>-->
<!--                        <td>-->
<!--                            <form id="research-form" action="award.php?type=research" method="post">-->
<!--                                <input type="hidden" name="action" value="report">-->
<!--                                <input type="hidden" name="type" value="research">-->
<!--                                <input value='90' name='award-for-research' type='text' class='form-control form-control-sm number text-end mb-0' placeholder='Enter Value'>-->
<!--                            </form>-->
<!--                        </td>-->
<!--                        <td class="text-center"><button type="submit" form="research-form" class="btn-sm btn btn-dark"><i class="bi bi-funnel"></i></button></td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td>Work Immersion Senior High</td>-->
<!--                        <td>-->
<!--                            <form id="immersion-form" action="award.php?type=immersion" method="post">-->
<!--                                <input type="hidden" name="action" value="report">-->
<!--                                <input type="hidden" name="type" value="immersion">-->
<!--                                <input value='90' name='immersion' type='text' class='form-control form-control-sm number text-end mb-0' placeholder='Enter Value'>-->
<!--                            </form>-->
<!--                        </td>-->
<!--                        <td class="text-center"><button type="submit" form="immersion-form" class="btn-sm btn btn-dark"><i class="bi bi-funnel"></i></button></td>-->
<!--                    </tr>-->
<!--                    </tbody>-->
<!--                </table>-->
<!--            </div>-->
<!--        </div>-->
    </div>
</div>