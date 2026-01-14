<?php
require '../../includes/session.php';

if (isset($_GET['semester']) && isset($_GET['acadyear'])) {
    $acadyear = $_GET['acadyear'];
    $semester = $_GET['semester'];
} else {
    $acadyear = $_SESSION['active_acadyears'];
    $semester = $_SESSION['active_semester'];
}

function mf_low($grade) {
    if ($grade <= 60) return 1;
    if ($grade >= 75) return 0;
    return (75 - $grade) / 15;   // linear decrease 60->75
}

// Average membership
function mf_average($grade) {
    if ($grade <= 60 || $grade >= 95) return 0;
    if ($grade == 77.5) return 1; // peak average
    if ($grade < 77.5) return ($grade - 60) / 17.5;
    return (95 - $grade) / 17.5;
}

// High membership
function mf_high($grade) {
    if ($grade <= 85) return 0;
    if ($grade >= 100) return 1;
    return ($grade - 85) / 15;
}

// Defuzzification
function defuzzify($low, $avg, $high, $grade) {
    // Weighted centroid
    $numerator = ($low * 60) + ($avg * 77.5) + ($high * 100);
    $denominator = $low + $avg + $high;

    if ($denominator == 0) return $grade; // fallback: raw grade

    $fuzzy = $numerator / $denominator;

    // Clamp fuzzy score to raw grade maximum
    if ($fuzzy > $grade) $fuzzy = $grade;

    return $fuzzy;
}

// Fuzzy ranking function
function fuzzy_rank_student($grade) {
    $low = mf_low($grade);
    $avg = mf_average($grade);
    $high = mf_high($grade);

    return defuzzify($low, $avg, $high, $grade);
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
              <h1 class="m-0">Student List <b><?php echo $semester .' - '. $acadyear?></b></h1>
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
            <h3 class="card-title">Student List for <b><?php echo $semester .' - '. $acadyear?></b></h3>
            <div class="card-tools">
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-md1">Set Sem and AY</button>
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-md2">Set Tuition Status</button>
            </div>
            
                  
                  <div class="modal fade" id="modal-md2">
                    <div class="modal-dialog modal-md">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Select Tuition Status for <b>
                              all students
                            </b></h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form action="userData/ctrl.edit.student.php?acadyear=<?php echo $acadyear?>&semester=<?php echo $semester?>"
                          method="POST">
                          <div class="modal-body">
                            <div class="row justify-content-center">
                              <div class="col-sm-12">
                                <div class="form-group">
                                  <label>Tuition Status</label>
                                  <select class="form-control select2" name="status">
                                    <option>Paid</option>
                                    <option>Unpaid</option>
                                  </select>
                                    <p><i>* note that changing this to unpaid will mark all of the students with <b>INC-T</b> in class R.O.G.</i><br><i>** this will also disable viewing of grades</i></p>
                                 
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" name="submit_all2" class="btn btn-primary">Save changes</button>
                          </div>
                        </form>
                      </div>
                    </div>
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
                                  <label>Semester</label>
                                  <select class="form-control select2" name="semester">
                                    <?php
                                    $select_sem = mysqli_query($conn, 'SELECT * FROM tbl_semesters');
                                    while ($row = mysqli_fetch_array($select_sem)) {
                                        ?>
                                        <option value='<?php echo $row['semester']?>'><?php echo $row['semester']?></option>
                                        <?php
                                    }
                                    ?>
                                    
                                  </select>
                                </div>
                              </div>
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
                  <th>Strand</th>
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

                    $student_info = mysqli_query($conn, "SELECT stud_no, strand_name, grade_level, tbl_students.student_id, 
                    CONCAT(tbl_students.student_lname, ', ', tbl_students.student_fname, ' ', tbl_students.student_mname)  as fullname
                    FROM tbl_schoolyears
                    iNNER JOIN tbl_students ON tbl_students.student_id = tbl_schoolyears.student_id
                    LEFT JOIN tbl_strands ON tbl_strands.strand_id = tbl_schoolyears.strand_id
                    LEFT JOIN tbl_grade_levels ON tbl_grade_levels.grade_level_id = tbl_schoolyears.grade_level_id
                    LEFT JOIN tbl_acadyears ON tbl_acadyears.ay_id = tbl_schoolyears.ay_id
                    LEFT JOIN tbl_semesters ON tbl_semesters.semester_id = tbl_schoolyears.semester_id
                    WHERE tbl_acadyears.academic_year = '$acadyear'
                    AND tbl_semesters.semester = '$semester'
                    AND tbl_schoolyears.remark = 'Approved'
                    AND tbl_grade_levels.grade_level_id = 14
                    AND (student_fname LIKE '%$search%'
                    OR student_mname LIKE '%$search%'
                    OR student_lname LIKE '%$search%'
                    OR strand_name LIKE '%$search%'
                    OR strand_def LIKE '%$search%'
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

                    // -------------------------------
                    // FIRST SEMESTER AVERAGE
                    // -------------------------------
                    $first_total = 0;
                    $first_count = 0;

                    $first_sem = mysqli_query($conn, "SELECT ofgrade FROM tbl_enrolled_subjects
                        LEFT JOIN tbl_schedules ON tbl_schedules.schedule_id = tbl_enrolled_subjects.schedule_id
                        WHERE student_id = '{$row['student_id']}'
                        AND tbl_schedules.semester = 'First Semester'
                        AND tbl_schedules.acadyear = '$acadyear'
                    ");

                    while ($fs = mysqli_fetch_array($first_sem)) {
                        if ($fs['ofgrade'] !== null && is_numeric($fs['ofgrade'])) {
                            $first_total += (float)$fs['ofgrade'];
                            $first_count++;
                        }
                    }


                    $first_avg = ($first_count > 0) ? $first_total / $first_count : 0;


                    // -------------------------------
                    // SECOND SEMESTER AVERAGE
                    // -------------------------------
                    $second_total = 0;
                    $second_count = 0;

                    $second_sem = mysqli_query($conn, "SELECT ofgrade FROM tbl_enrolled_subjects
                        LEFT JOIN tbl_schedules ON tbl_schedules.schedule_id = tbl_enrolled_subjects.schedule_id
                        WHERE student_id = '{$row['student_id']}'
                        AND tbl_schedules.semester = 'Second Semester'
                        AND tbl_schedules.acadyear = '$acadyear'
                    ");

                    while ($ss = mysqli_fetch_array($second_sem)) {
                        if ($ss['ofgrade'] !== null && is_numeric($ss['ofgrade'])) {
                            $second_total += (float)$ss['ofgrade'];
                            $second_count++;
                        }
                    }


                    $second_avg = ($second_count > 0) ? $second_total / $second_count : 0;


                    // -------------------------------
                    // FINAL AVERAGE (CONDITION)
                    // -------------------------------
                    if ($second_count > 0) {
                        // ONLY compute combined average if second sem exists
                        $total_ave = ($first_avg + $second_avg) / 2;
                    } else {
                        // Otherwise, show first semester only
                        $total_ave = $first_avg;
                    }

                        $total_ave = (float)$total_ave;

                        $distinction = get_distinction($total_ave);

                        // PUSH into array
                        $students[] = [
                        "stud_no" => $row['stud_no'],
                        "fullname" => $row['fullname'],
                        "strand_name" => $row['strand_name'],
                        "grade_level" => $row['grade_level'],
                        "average" => $total_ave,
                        "fuzzy_score" => fuzzy_rank_student($total_ave),
                        "distinction" => get_distinction($total_ave)
                    ];
                    }

                        usort($students, function($a, $b) {
                            return $b["fuzzy_score"] <=> $a["fuzzy_score"];
                        });

                        $rank = 0;                 // current rank number
                        $position = 0;             // actual position in list
                        $prev_score = null;        // previous fuzzy score

                        foreach ($students as $key => $stud) {
                            $position++;

                            // If first student OR score is different → update rank
                            if ($prev_score === null || $stud["fuzzy_score"] != $prev_score) {
                                $rank = $position;
                            }

                            $students[$key]["rank"] = $rank;
                            $prev_score = $stud["fuzzy_score"];
                        }


                        foreach ($students as $s) {
                        echo "
                        <tr>
                            <td>{$s['stud_no']}</td>
                            <td>{$s['fullname']}</td>
                            <td>{$s['strand_name']}</td>
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