<?php
require '../../includes/session.php';

if (isset($_POST['semester']) && isset($_POST['acadyear'])) {
  $acadyear = $_POST['acadyear'];
  $semester = $_POST['semester'];
} else {
  $acadyear = $_SESSION['active_acad_id'];
  $semester = $_SESSION['active_semester_id'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Faculty's Load | BED OnGrade - Laspinas</title>

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
              <h1 class="m-0">Faculties</h1>
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
            <h3 class="card-title">Faculty's Load</h3>

            <div class="card-tools">
              <?php
              if ($_SESSION['role'] == "Human Resource") {
                ?>
                <a href="../forms/faculty.question.php" type="button" class="btn btn-primary btn-sm">
                  All Teachers' Evaluation
                </a>
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-lg-class-history">Evaluation
                  History</button>
                <div class="modal fade" id="modal-lg-class-history">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Search Evaluation History</b></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form method="POST">
                        <div class="modal-body">
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label>Semester</label>
                                <select class="form-control select2" name="semester" style="width: 100%;">
                                  <option selected disabled>Select Semester</option>
                                  <?php
                                  $sem_info = mysqli_query($conn, "SELECT * FROM tbl_semesters");
                                  while ($row = mysqli_fetch_array($sem_info)) {
                                    ?>
                                    <option value="<?php echo $row['semester_id'] ?>"><?php echo $row['semester'] ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label>Academic Year</label>
                                <select class="form-control select2" name="acadyear" style="width: 100%;">
                                  <option selected disabled>Select Academic Year</option>
                                  <?php
                                  $sem_info = mysqli_query($conn, "SELECT * FROM tbl_acadyears ORDER BY academic_year DESC");
                                  while ($row = mysqli_fetch_array($sem_info)) {
                                    ?>
                                    <option value="<?php echo $row['ay_id'] ?>"><?php echo $row['academic_year'] ?>
                                    </option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <?php
              } else {

              }
              ?>
            </div>
          </div>
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Faculty</th>
                  <th>Option</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($_SESSION['role'] == "Human Resource") {
                  $faculty_info = mysqli_query($conn, "SELECT tbl_teachers.teacher_id, CONCAT(teacher_lname, ', ', teacher_fname) AS fullname FROM tbl_teachers 
                  INNER JOIN tbl_schedules ON tbl_teachers.teacher_id = tbl_schedules.teacher_id
                  INNER JOIN tbl_enrolled_subjects ON tbl_schedules.schedule_id = tbl_enrolled_subjects.schedule_id
                  INNER JOIN tbl_evaluations ON tbl_evaluations.enrolled_subj_id = tbl_enrolled_subjects.enrolled_subj_id
                  WHERE tbl_evaluations.sem_id = '$semester' AND tbl_evaluations.ay_id = '$acadyear'
                  GROUP BY tbl_teachers.teacher_id");
                } else {
                  $faculty_info = mysqli_query($conn, "SELECT *, CONCAT(teacher_lname, ', ', teacher_fname) AS fullname FROM tbl_teachers");
                }


                while ($row = mysqli_fetch_array($faculty_info)) {
                  ?>
                  <tr>
                    <td><?php echo $row['fullname'] ?></td>
                    <td>
                      <?php
                      if ($_SESSION['role'] == "Human Resource") {
                        ?>
                        <a href="../forms/faculty.evaluation.form.php?teacher_id=<?php echo $row['teacher_id']; ?>"
                          class="btn btn-primary btn-sm">View Faculty Evaluation</a>
                        <?php
                      } else {
                        ?>
                        <a href="view.load.php?teacher_id=<?php echo $row['teacher_id']; ?>"
                          class="btn btn-primary btn-sm">View Subject Load</a>
                        <?php
                      }
                      ?>
                    </td>
                  </tr>
                  <?php
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