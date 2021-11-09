<!DOCTYPE html>
<header>
    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="grade.php">Grade</a></li>
            <li class="breadcrumb-item active" aria-current="page">Values Grade</a></li>
        </ol>
    </nav>
</header>
<div class="container mt-1 ms-0">
    <div class="card w-100 h-auto bg-light" style="min-height: 70vh !important;">
        <h5 class="fw-bold"><?php echo $sem ?> Semester & <?php echo $grading ?> Quarter</h5>
        <div class="d-flex justify-content-between mb-1">
            <!-- SEARCH BAR -->

            <!-- <div class="w-25">
                <select class="form-select form-select-sm" id="classes">
                    <?php
                    // echo $adv_opn;
                    // echo $sub_class_opn;
                    ?>
                </select>
            </div> -->
            <!-- <div>
                <form method="post" action="action.php" class="ms-2">
                    <input class='hidden' id='export_code' name='code' value=''>
                    <input type="submit" name="export" value="Export">
                </form>
            </div> -->
            <div>
                <!-- <form method="post" action="action.php"><input type="submit" id='export' name="export" class="btn btn-secondary" value="EXPORT"></form> -->
                <!-- <button type="submit" class="btn btn-secondary export" >EXPORT</button>
                                            <button onclick="Export()" class="btn btn-secondary">EXPORT</button> -->
                <button type="button" class="btn btn-success ms-2 save"></i>Save</button>
                <button type="button" class="btn btn-success submit">Submit</button>
            </div>
        </div>
        <h6 class="text-center"><b>Learner's Observed Values</b></h6>
        <!-- 
                                        <div class="d-flex justify-content-between">
                                            <p><b>AO</b> - Always Observed</p>
                                            <p><b>SO</b> - Sometimes Observed</p>
                                            <p><b>RO</b> - Rarely Observed</p>
                                            <p><b>NO</b> - Not Observed</p>
                                        </div> -->

        <table class="table-bordered table w-100">
            <col style='width: 20%;'>
            <col style='width: 40%;'>
            <col style='width: 10%;'>
            <col style='width: 10%;'>
            <col style='width: 10%;'>
            <col style='width: 10%;'>

            <thead class='text-center fw-bold'>
                <tr>
                    <td>Core Values</td>
                    <td>Behavior Statements</td>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                </tr>
            </thead>
            <tbody>
                <?php
                // echo json_encode($observed_values);
                // echo "<tr>";

                $observed_values = $faculty->listValuesReport();

                foreach ($observed_values as $id => $values) { //id = MakaDiyos , values = [ 'sfdsdfsdfdsf => 1 => 'AO',2 => 'AO,],
                    echo "<td rowspan='" . count($values) . "'><b>$id</b></td>";
                    foreach ($values as $bh_staments => $bh_qtr) { // $bh_staments = sfdsdfsdfdsf
                        foreach ($bh_qtr as $bh_staments => $marking) {
                            echo "<td>$bh_staments</td>";

                            $first = $marking[0];
                            $second = $marking[1];
                            $third = $marking[2];
                            $fourth = $marking[3];
                            echo "<td>$first</td>";
                            echo "<td>$second</td>";
                            echo "<td>$third</td>";
                            echo "<td>$fourth</td>";
                            echo "</tr>";
                        }
                    }

                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="container">
            <h6><b>Observed Values</b></h6>
            <div class="row g-5">
                <div class="col-auto">LEGEND:</div>
                <div class="col-auto"><b>Marking</b>
                    <p>AO<br>SO<br>RO<br>NO</p>
                </div>
                <div class="col-auto">
                    <b>Non-numerical Rating</b>
                    <p>Always Observed<br>Sometimes Observed<br>Rarely Observed<br>Not Observed</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">var type = 'values'</script>