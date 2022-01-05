<?php
include("../class/Administration.php");
$admin = new Administration();
$result = $admin->query("SELECT sub_code, sub_name FROM subject;");
$subjects = [];
while ($row = mysqli_fetch_assoc($result)) {
    $subjects[$row['sub_code']] = $row['sub_name'];
}

$param = [
    "Highest" => ['min' => "98", 'max' => "100"],
    "High" => ['min' => "95", 'max' => "97"],
    "With" => ['min' => "90", 'max' => "94"],
];
?>
<div class="container">
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
                                <button type="submit" form="acad-parameter-form" class="btn-sm btn btn-success"><i class="bi bi-file-earmark-text me-2"></i>    Generate</button>
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
                    <button type="submit" form="attendance-form" class="btn-sm btn btn-success w-100 mt-3 mb-2"><i class="bi bi-file-earmark-text me-2"></i>Generate</button>
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
                                foreach ($subjects as $sub_code => $sub_name) {
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
                            <button type="submit" form="other-award-form" class="btn-sm btn btn-outline-primary"><i class="bi bi-funnel"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>