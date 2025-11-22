<?php
require '../../includes/session.php';

if (isset($_GET['teacher_id'])) {
  $teacher_id = $_GET['teacher_id'];

} else {
  $teacher_id = $_SESSION['id'];

}

if (isset($_POST['semester']) && isset($_POST['acadyear'])) {
  $acadyear = $_POST['acadyear'];
  $semester = $_POST['semester'];
} else {
  $acadyear = $_SESSION['active_acadyears'];
  $semester = $_SESSION['active_semester'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Subject Load | BED OnGrade - Laspinas</title>

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
              <h1 class="m-0">Subjects Loads</h1>
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
            $faculty_info = mysqli_query($conn, "SELECT CONCAT(teacher_lname, ', ' , teacher_fname) AS fullname FROM tbl_teachers WHERE teacher_id = '$teacher_id'");
            $row = mysqli_fetch_array($faculty_info);
            ?>
            <h3 class="card-title"><b><?php echo $row['fullname']; ?>'s</b> Subjects Load for <b><?php echo $semester?> - <?php echo $acadyear?></b></h3>

              <div class="card-tools">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-lg-class-history">Class History</button>
                    <div class="modal fade" id="modal-lg-class-history">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Search Class History for <b><?php echo $row['fullname']; ?></b></h4>
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
                                    <option value="<?php echo $row['semester']?>"><?php echo $row['semester']?></option>
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
                                    <option value="<?php echo $row['academic_year']?>"><?php echo $row['academic_year']?></option>
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
              </div>
          </div>
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Subjects</th>
                  <th>Option</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $load_info = mysqli_query($conn, "SELECT * FROM tbl_schedules
                LEFT JOIN tbl_subjects_senior ON tbl_subjects_senior.subject_id = tbl_schedules.subject_id
                WHERE teacher_id = '$teacher_id' AND acadyear = '$acadyear' AND semester = '$semester'
                GROUP BY tbl_subjects_senior.subject_code");

                while ($row = mysqli_fetch_array($load_info))  {
                ?>
                <tr>
                  <td><b><?php echo $row['subject_code'].'</b> - '. $row['subject_description']; ?></td>
                  <td><a href="section.load.php?subject_code=<?php echo $row['subject_code']; ?>&teacher_id=<?php echo $row['teacher_id']; ?>&subject_description=<?php echo $row['subject_description']; ?>&acadyear=<?php echo $acadyear?>&semester=<?php echo $semester?>" class="btn btn-primary btn-sm">View Sections</a>
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