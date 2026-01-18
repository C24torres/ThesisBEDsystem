<?php
require '../../includes/session.php';

$teacher_id = $_GET['teacher_id'];
$subject_code = $_GET['subject_code'];
$subject_description = $_GET['subject_description'];

if (isset($_GET['semester']) && isset($_GET['acadyear'])) {
  $acadyear = $_GET['acadyear'];
  $semester = $_GET['semester'];
} else {
  $acadyear = $_SESSION['active_acadyears'];
  $semester = $_SESSION['active_semester'];
}

/* ✅ PUT THIS HERE — ALWAYS INITIALIZE */
$is_senior = false;

/* CHECK IF SUBJECT IS SHS */
$checkSenior = mysqli_query($conn, "SELECT subject_id 
    FROM tbl_subjects_senior 
    WHERE subject_code = '$subject_code'
");

if (mysqli_num_rows($checkSenior) > 0) {
    $is_senior = true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Section | BED OnGrade - Laspinas</title>

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
              <h1 class="m-0">Sections</h1>
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
            <h3 class="card-title">Sections for <b><?php echo $subject_code .' - '. $subject_description; ?></b></h3>

            <div class="card-tools">
            </div>
          </div>
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Section</th>
                  <th>Time</th>
                  <th>Day</th>
                  <th>Room</th>
                  <th>Option</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($is_senior) {

                    // SHS subjects
                    $load_info = mysqli_query($conn, "SELECT tbl_schedules.*
                        FROM tbl_schedules
                        INNER JOIN tbl_subjects_senior 
                            ON tbl_subjects_senior.subject_id = tbl_schedules.subject_id
                        WHERE tbl_schedules.teacher_id = '$teacher_id'
                        AND tbl_subjects_senior.subject_code = '$subject_code'
                        AND acadyear = '$acadyear'
                    ");

                } else {

                    // K–10 subjects
                    $load_info = mysqli_query($conn, "SELECT tbl_schedules.*
                        FROM tbl_schedules
                        INNER JOIN tbl_subjects 
                            ON tbl_subjects.subject_id = tbl_schedules.subject_id
                        WHERE tbl_schedules.teacher_id = '$teacher_id'
                        AND tbl_subjects.subject_code = '$subject_code'
                        AND acadyear = '$acadyear'
                    ");

                }

                while ($row = mysqli_fetch_array($load_info))  {
                ?>
                <tr>
                  <td><?php echo $row['section']; ?></td>
                  <td><?php echo $row['time']; ?></td>
                  <td><?php echo $row['day']; ?></td>
                  <td><?php echo $row['room']; ?></td>
                  <td>
                    <?php
                    if ($_SESSION['role'] == "Registrar" || $_SESSION['role'] == "Faculty Staff") {
                    ?>
                      <a href="class.php?schedule_id=<?php echo $row['schedule_id']; ?>&section=<?php echo $row['section']; ?>&acadyear=<?php echo $acadyear?>&semester=<?php echo $semester?>" class="btn btn-primary btn-sm">View Class</a>
                    <?php
                    } else {
                    ?>
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