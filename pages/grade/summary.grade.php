<?php
require '../../includes/session.php';

if (($_SESSION['role'] == 'Registrar' || $_SESSION['role'] == 'Enrollment Staff') && isset($_GET['student_id'])) {
    
    $student_id = $_GET['student_id'];
    
} else {
        $student_id = $_SESSION['id'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Student's Summary Grade | BED OnGrade - Laspinas</title>

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
              <h1 class="m-0">Summary of Grade</h1>
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
            <?php
            $student_info = mysqli_query($conn, "SELECT CONCAT(student_lname, ', ' , student_fname , ' ', student_mname) AS fullname FROM tbl_students WHERE student_id = '$student_id'");
            $row = mysqli_fetch_array($student_info);
            ?>
            <h3 class="card-title"><b><?php echo $row['fullname']; ?>'s</b> Grade for <b><?php echo $_SESSION['active_acadyears']?></b></h3>

              <div class="card-tools">
              </div>
          </div>
          <div class="card-body" <?php
            $level_check = mysqli_query($conn, "SELECT tbl_grade_levels.grade_level_id
              FROM tbl_schoolyears
              LEFT JOIN tbl_grade_levels 
                ON tbl_schoolyears.grade_level_id = tbl_grade_levels.grade_level_id
              WHERE student_id = '$student_id'
              AND remark = 'Approved'
              ORDER BY tbl_schoolyears.ay_id DESC
              LIMIT 1
            ");

            $level_row = mysqli_fetch_assoc($level_check);

            $is_k10 = ($level_row['grade_level_id'] >= 1 && $level_row['grade_level_id'] <= 13);
            $is_shs = ($level_row['grade_level_id'] >= 14 && $level_row['grade_level_id'] <= 15);
            ?>
            >
            <table id="example3" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Subject Code</th>
                  <th>Subject Description</th>

                  <?php if ($is_k10) { ?>
                    <th>1st Quarter</th>
                    <th>2nd Quarter</th>
                    <th>3rd Quarter</th>
                    <th>4th Quarter</th>
                    <th>Numerical Grade</th>
                    <th>Final Grade</th>
                  <?php } elseif ($is_shs) { ?>
                    <th>Midterm</th>
                    <th>Finalterm</th>
                    <th>Numerical Grade</th>
                    <th>Final Grade</th>
                  <?php } ?>

                  
                </tr>
              </thead>
              <tbody>
                <?php
                $sy_info = mysqli_query($conn, "SELECT *, tbl_schoolyears.ay_id FROM tbl_schoolyears
                LEFT JOIN tbl_strands ON tbl_strands.strand_id = tbl_schoolyears.strand_id
                LEFT JOIN tbl_semesters ON tbl_schoolyears.semester_id = tbl_semesters.semester_id
                LEFT JOIN tbl_acadyears ON tbl_schoolyears.ay_id = tbl_acadyears.ay_id
                LEFT JOIN tbl_grade_levels ON tbl_schoolyears.grade_level_id = tbl_grade_levels.grade_level_id 
                WHERE student_id = '$student_id' AND remark = 'Approved'
                ORDER BY tbl_grade_levels.grade_level_id ASC, tbl_semesters.semester_id ASC");
                while ($row = mysqli_fetch_array($sy_info)) {
                  $is_k10 = ($row['grade_level_id'] >= 1 && $row['grade_level_id'] <= 13);
                  $is_shs = ($row['grade_level_id'] >= 14 && $row['grade_level_id'] <= 15);
                ?>
                <tr>
                  
                  <?php if ($is_k10) { ?>
                    <td><b><?php echo $row['grade_level'].'<br>'. $row['semester'].' - '. $row['academic_year']?></b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    
                  <?php } elseif ($is_shs) { ?>
                    <td><b><?php echo $row['grade_level'].'<br>'. $row['semester'].' - '. $row['academic_year']?></b></td>
                    <td><b><?php echo $row['strand_def']?></b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  <?php } ?>
                  
                </tr>
                <?php
                    if ($row['grade_level_id'] <= 13) {
                    // NURSERY TO GRADE 10
                    $enrolled_subjects = mysqli_query($conn, "SELECT * FROM tbl_enrolled_subjects
                        LEFT JOIN tbl_students ON tbl_students.student_id = tbl_enrolled_subjects.student_id
                        LEFT JOIN tbl_schedules ON tbl_schedules.schedule_id = tbl_enrolled_subjects.schedule_id
                        LEFT JOIN tbl_subjects ON tbl_subjects.subject_id = tbl_schedules.subject_id
                        WHERE tbl_enrolled_subjects.student_id = '$student_id'
                        AND tbl_schedules.acadyear = '$row[academic_year]'
                    ");
                } else {
                    // SENIOR HIGH SCHOOL
                    $enrolled_subjects = mysqli_query($conn, "SELECT * FROM tbl_enrolled_subjects
                        LEFT JOIN tbl_students ON tbl_students.student_id = tbl_enrolled_subjects.student_id
                        LEFT JOIN tbl_schedules ON tbl_schedules.schedule_id = tbl_enrolled_subjects.schedule_id
                        LEFT JOIN tbl_subjects_senior ON tbl_subjects_senior.subject_id = tbl_schedules.subject_id
                        WHERE tbl_enrolled_subjects.student_id = '$student_id'
                        AND tbl_subjects_senior.strand_id = '$row[strand_id]'
                        AND tbl_schedules.acadyear = '$row[academic_year]'
                        AND tbl_schedules.semester = '$row[semester]'
                    ");
                }


                    while ($row2 = mysqli_fetch_array($enrolled_subjects)) {

                      $faculty_info = mysqli_query($conn, "SELECT *, CONCAT(teacher_lname, ', ', teacher_fname, ' ', teacher_mname) AS teacher_name FROM tbl_teachers
                      LEFT JOIN tbl_schedules ON tbl_schedules.teacher_id = tbl_teachers.teacher_id WHERE schedule_id = '$row2[schedule_id]'");

                      $row3 = mysqli_fetch_array($faculty_info);
                ?>
                
                <tr>
                  <td><?php echo $row2['subject_code']?></td>
                  <td><?php echo $row2['subject_description']?><br>Instructor: <?php echo !empty($row3['teacher_name']) ? $row3['teacher_name'] : 'TBA'; ?></td>
                  
                  <?php
                  if ($_SESSION['role'] == "Student" && $row['accounting_status'] == "Disabled") {
                  ?>
                  <td class="justify-content-center" colspan="5">Your grades are currently unavaible due to pending accounts.<br> Please refer to the <b>Registrar's Office</b> for clarification.</td>
                  <?php
                  } else {
                  ?>
                  <?php if ($is_k10) { ?>
                    <td><?php echo $row2['first_quarter']; ?></td>
                    <td><?php echo $row2['second_quarter']; ?></td>
                    <td><?php echo $row2['third_quarter']; ?></td>
                    <td><?php echo $row2['fourth_quarter']; ?></td>
                    <td><?php echo $row2['numgrade']; ?></td>
                    <td><?php echo $row2['ofgrade']; ?></td>
                  <?php } elseif ($is_shs) { ?>
                    <td><?php echo $row2['midterm']; ?></td>
                    <td><?php echo $row2['finalterm']; ?></td>
                    <td><?php echo $row2['numgrade']; ?></td>
                    <td><?php echo $row2['ofgrade']; ?></td>
                  <?php } ?>
                </tr>
                <?php
                  }
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