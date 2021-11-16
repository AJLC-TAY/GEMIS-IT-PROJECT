<?php
include("../class/Administration.php");
$admin = new Administration();
$result = $admin->query("SELECT CASE WHEN award_code = 'ae1_highestHonors' THEN 'Highest' 
                        WHEN award_code = 'ae1_highHonors' THEN 'High'
                        WHEN award_code = 'ae1_withHonors' THEN 'With' END AS info, 
                        min_gwa AS min, max_gwa AS max
                        FROM academicexcellence;");
$param = [];
while ($row = mysqli_fetch_assoc($result)) {
    $param[$row['info']] = ['min' => $row['min'], 'max' => $row['max']];
}
?>
<!DOCTYPE html>
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
                    <div class="d-flex justify-content-between align-content-center px-0">
                        <div class="col-auto">
                            <h5 class="mb-0">Academic Excellence</h5>
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <select required class="form-select form-select-sm mb-0 me-3" name="grade" id="grade">
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
                                foreach ($range as $val) {
                                    echo "<td><input required value='$val' name='$info-honor[]' type='text' class='form-control form-control-sm number text-end mb-0' placeholder='Enter Value'></td>";
                                }
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card p-3 h-100 d-flex-column justify-content-between">
                <h5>Conduct Award</h5>
                <form id="conduct-form" action="award.php?type=conduct" method="post">
                    <input type="hidden" name="action" value="report">
                    <input type="hidden" name="type" value="conduct">
                    <p>They must have obtained a rating of at least 75% “Always Observed” (AO) at the end of the school year (with at least 21 out of 28 AO rating in the report card). </p>
                    <button type="submit" form="conduct-form" class="btn-sm btn btn-dark w-100"><i class="bi bi-funnel me-2"></i>Generate</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card p-3 mb-4">
                <h5>Perfect Attendance</h5>
                <form id="attendance-form" action="award.php?type=attendance" method="post">
                    <input type="hidden" name="action" value="report">
                    <input type="hidden" name="type" value="attendance">
                    <button type="submit" form="attendance-form" class="btn-sm btn btn-dark w-100 mt-3"><i class="bi bi-funnel me-2"></i>Generate</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card p-3 mb-4">
                <div class="d-flex justify-content-between align-content-center px-0">
                    <h5 class="mb-0">Other Awards</h5>
                </div>
                <table class="table table-sm table-hover table-bordered mt-3">
                    <thead class="text-center">
                    <th>Awards for</th>
                    <th>Minimum Grade</th>
                    <th>Action</th>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Research</td>
                        <td>
                            <form id="research-form" action="award.php?type=research" method="post">
                                <input type="hidden" name="action" value="report">
                                <input type="hidden" name="type" value="research">
                                <input value='90' name='award-for-research' type='text' class='form-control form-control-sm number text-end mb-0' placeholder='Enter Value'>
                            </form>
                        </td>
                        <td class="text-center"><button type="submit" form="research-form" class="btn-sm btn btn-dark"><i class="bi bi-funnel"></i></button></td>
                    </tr>
                    <tr>
                        <td>Work Immersion Senior High</td>
                        <td>
                            <form id="immersion-form" action="award.php?type=immersion" method="post">
                                <input type="hidden" name="action" value="report">
                                <input type="hidden" name="type" value="immersion">
                                <input value='90' name='immersion' type='text' class='form-control form-control-sm number text-end mb-0' placeholder='Enter Value'>
                            </form>
                        </td>
                        <td class="text-center"><button type="submit" form="immersion-form" class="btn-sm btn btn-dark"><i class="bi bi-funnel"></i></button></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>