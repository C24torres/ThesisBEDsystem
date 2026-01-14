<?php
require '../../includes/session.php';

if (isset($_GET['acadyear'])) {
    $acadyear = $_GET['acadyear'];
    
} else {
    $acadyear = $_SESSION['active_acadyears'];
    
}

function mf_low($grade) {
    if ($grade <= 60) return 1;     // fully low
    if ($grade >= 75) return 0;     // end of low
    return (75 - $grade) / 15;      // linear decrease 60->75
}

function mf_average($grade) {
    if ($grade <= 60 || $grade >= 95) return 0; // no average below 60 or above 95
    if ($grade == 82.5) return 1;               // peak average in middle
    if ($grade < 82.5) return ($grade - 60) / 22.5;   // increase to peak
    return (95 - $grade) / 12.5;               // decrease from peak to 0 at 95
}

function mf_high($grade) {
    if ($grade <= 85) return 0;     // no high below 85
    if ($grade >= 100) return 1;    // fully high at 100
    return ($grade - 85) / 15;      // linear increase 85->100
}


// -------------------------------
// Defuzzification via Centroid
// -------------------------------
function defuzzify($low, $avg, $high) {
    // weighted centroid formula
    $numerator = ($low * 60) + ($avg * 82.5) + ($high * 100);
    $denominator = ($low + $avg + $high);

    if ($denominator == 0) return 0;

    return $numerator / $denominator;
}

// -------------------------------
// Fuzzy Ranking Function
// -------------------------------
function fuzzy_rank_student($grade) {
    // fuzzification
    $low = mf_low($grade);
    $avg = mf_average($grade);
    $high = mf_high($grade);

    // defuzzification
    return defuzzify($low, $avg, $high);
}

function get_distinction($average) {
    if ($average >= 98 && $average <= 100) {
        return "With Highest Honors";
    } elseif ($average >= 95 && $average <= 97.99) {
        return "With High Honors";
    } elseif ($average >= 90 && $average <= 94.99) {
        return "With Honors";
    } else {
        return "—";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Student List | BED OnGrade - Laspinas</title>

  <?php include '../../includes/links.php'; ?>

</head>

<body class="hold-transition layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">

    <?php include '../../includes/navbar.php' ?>

    <?php include '../../includes/sidebar.php' ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Student List <b><?php echo $acadyear?></b></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#"></a></li>
                <li class="breadcrumb-item active"></li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        

        <!-- Default box -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Student List for <b><?php echo $acadyear?></b></h3>
            <div class="card-tools">
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-md1">Set AcadYear</button>
            </div>
                  <div class="modal fade" id="modal-md1">
                    <div class="modal-dialog modal-md">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Select <b>
                              Semester and Academic Year
                            </b></h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form 
                          method="GET">
                          <div class="modal-body">
                            <div class="row justify-content-center">
                              
                              <div class="col-sm-12">
                                <div class="form-group">
                                  <label>Academic Year</label>
                                  <select class="form-control select2" name="acadyear">
                                    <?php
                                    $select_sem = mysqli_query($conn, 'SELECT * FROM tbl_acadyears');
                                    while ($row = mysqli_fetch_array($select_sem)) {
                                        ?>
                                        <option value='<?php echo $row['academic_year']?>'><?php echo $row['academic_year']?></option>
                                        <?php
                                    }
                                    ?>
                                  </select>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" name="submit_all" class="btn btn-primary">Save changes</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
          </div>
          <div class="card-body">
            <form method="POST">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" name="search" class="form-control" placeholder="Search student">
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </form>
          </div>
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Student Number</th>
                  <th>Student</th>
                  <th>Grade Level</th>
                  <th>Fuzzy Scores</th>
                  <th>Rank</th>
                  <th>Distinction</th>

                </tr>
              </thead>
              <tbody>
                <?php

                $students = []; // array to store all students + fuzzy score

                if (isset($_POST['search'])) {
                    $search = addslashes($_POST['search']);

                    $student_info = mysqli_query($conn, "SELECT stud_no, grade_level, tbl_students.student_id, 
                    CONCAT(tbl_students.student_lname, ', ', tbl_students.student_fname, ' ', tbl_students.student_mname)  as fullname
                    FROM tbl_schoolyears
                    INNER JOIN tbl_students ON tbl_students.student_id = tbl_schoolyears.student_id
                    LEFT JOIN tbl_grade_levels ON tbl_grade_levels.grade_level_id = tbl_schoolyears.grade_level_id
                    LEFT JOIN tbl_acadyears ON tbl_acadyears.ay_id = tbl_schoolyears.ay_id
                    LEFT JOIN tbl_semesters ON tbl_semesters.semester_id = tbl_schoolyears.semester_id
                    WHERE tbl_acadyears.academic_year = '$acadyear'
                    AND tbl_schoolyears.remark = 'Approved'
                    AND tbl_grade_levels.grade_level_id = 10
                    AND (student_fname LIKE '%$search%'
                    OR student_mname LIKE '%$search%'
                    OR student_lname LIKE '%$search%'
                    OR grade_level LIKE '%$search%'
                    OR stud_no LIKE '%$search%')
                    ORDER BY student_lname");

                    while ($row = mysqli_fetch_array($student_info))  {

                        // compute average grade
                        $grade_info = mysqli_query($conn, "SELECT * FROM tbl_enrolled_subjects
                            LEFT JOIN tbl_schedules ON tbl_schedules.schedule_id = tbl_enrolled_subjects.schedule_id
                            LEFT JOIN tbl_subjects_senior ON tbl_subjects_senior.subject_id = tbl_schedules.subject_id
                            WHERE student_id = '$row[student_id]'
                            AND tbl_subjects_senior.semester_id = '$_SESSION[active_semester_id]'
                            AND tbl_schedules.acadyear = '$_SESSION[active_acadyears]'");

                        $average = 0;
                        $index = 0;
                        while($row1 = mysqli_fetch_array($grade_info))  {
                            $average += $row1['ofgrade'];
                            $index++;
                        }
                        $total_ave = ($index > 0) ? $average / $index : 0;

                        // PUSH into array
                        $students[] = [
                        "stud_no" => $row['stud_no'],
                        "fullname" => $row['fullname'],
                        "grade_level" => $row['grade_level'],
                        "average" => $total_ave,
                        "fuzzy_score" => fuzzy_rank_student($total_ave),
                        "distinction" => get_distinction($total_ave)
                    ];
                    }

                         usort($students, function($a, $b) {
                            return $b["fuzzy_score"] <=> $a["fuzzy_score"];
                        });

                        $rank = 1;
                        foreach ($students as $key => $stud) {
                            $students[$key]["rank"] = $rank;
                            $rank++;
                        }

                        foreach ($students as $s) {
                        echo "
                        <tr>
                            <td>{$s['stud_no']}</td>
                            <td>{$s['fullname']}</td>
                            <td>{$s['grade_level']}</td>
                            <td>" . number_format((float)$s['fuzzy_score'], 2,  '.', '') . "</td>
                            <td>{$s['rank']}</td>
                            <td><b>{$s['distinction']}</b></td>

                        </tr>";
                    }
                }
                                                                            
                ?>
                
              </tbody>
              <tfoot>
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer"></div>
          <!-- /.card-footer-->
        </div>
        <!-- /.card -->

      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php include '../../includes/footer.php'; ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <?php include '../../includes/script.php'; ?>
</body>

</html>