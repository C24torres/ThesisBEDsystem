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
          <div class="card-body">
            <table id="example3" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Subject Code</th>
                  <th>Subject Description</th>
                  <th>Prelim</th>
                  <th>Midterm</th>
                  <th>Finalterm</th>
                  <th>Numerical Grade</th>
                  <th>Final Grade</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sy_info = mysqli_query($conn, "SELECT *, tbl_schoolyears.ay_id FROM tbl_schoolyears
                LEFT JOIN tbl_strands ON tbl_strands.strand_id = tbl_schoolyears.strand_id
                LEFT JOIN tbl_semesters ON tbl_schoolyears.semester_id = tbl_semesters.semester
                LEFT JOIN tbl_acadyears ON tbl_schoolyears.ay_id = tbl_acadyears.academic_year
                LEFT JOIN tbl_grade_levels ON tbl_schoolyears.grade_level_id = tbl_grade_levels.grade_level_id 
                WHERE student_id = '$student_id' AND remark = 'Approved'
                ORDER BY tbl_grade_levels.grade_level_id ASC, tbl_semesters.semester_id ASC");
                while ($row = mysqli_fetch_array($sy_info)) {

                ?>
                <tr>
                  <td><b><?php echo $row['grade_level'].'<br>'. $row['semester'].' - '. $row['academic_year']?></b></td>
                  <td><b><?php echo $row['strand_def']?></b></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <?php
                    $enrolled_subjects = mysqli_query($conn, "SELECT * FROM tbl_enrolled_subjects
                    LEFT JOIN tbl_students ON tbl_students.student_id = tbl_enrolled_subjects.student_id
                    LEFT JOIN tbl_subjects_senior ON tbl_subjects_senior.subject_id = tbl_enrolled_subjects.subject_id
                    WHERE tbl_enrolled_subjects.student_id = '$student_id' AND tbl_subjects_senior.strand_id = '$row[strand_id]' AND acad_year = '$row[ay_id]' AND semester = '$row[semester]'");

                    while ($row2 = mysqli_fetch_array($enrolled_subjects)) {

                      $faculty_info = mysqli_query($conn, "SELECT *, CONCAT(teacher_lname, ', ', teacher_fname, ' ', teacher_mname) AS teacher_name FROM tbl_teachers
                      LEFT JOIN tbl_schedules ON tbl_schedules.teacher_id = tbl_teachers.teacher_id WHERE schedule_id = '$row2[schedule_id]'");

                      $row3 = mysqli_fetch_array($faculty_info);
                ?>
                
                <tr>
                  <td><?php echo $row2['subject_code']?></td>
                  <td><?php echo $row2['subject_description']?><br>Instructor: <?php echo $row3['teacher_name']?></td>
                  
                  <?php
                  if ($_SESSION['role'] == "Student" && $row['accounting_status'] == "Disabled") {
                  ?>
                  <td class="justify-content-center" colspan="5">Your grades are currently unavaible due to pending accounts.<br> Please refer to the <b>Registrar's Office</b> for clarification.</td>
                  <?php
                  } else {
                  ?>
                  <td><?php echo $row2['prelim']?></td>
                  <td><?php echo $row2['midterm']?></td>
                  <td><?php echo $row2['finalterm']?></td>
                  <td><?php echo $row2['numgrade']?></td>
                  <td><?php echo $row2['ofgrade']?></td>
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