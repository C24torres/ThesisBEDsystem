<?php
require '../../includes/session.php';

$schedule_id = $_GET['schedule_id'];
$section = $_GET['section'];

if (isset($_GET['semester']) && isset($_GET['acadyear'])) {
  $acadyear = $_GET['acadyear'];
  $semester = $_GET['semester'];
} else {
  $acadyear = $_SESSION['active_acadyear'];
  $semester = $_SESSION['active_semester'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Class | BED OnGrade - Laspinas</title>

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
              <h1 class="m-0">Students</h1>
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
          <form action="userData/enter.grade.class.php?schedule_id=<?php echo $schedule_id; ?>&section=<?php echo $section; ?>&acadyear=<?php echo $acadyear?>&semester=<?php echo $semester?>" method="POST">
            <div class="card-header">
              <h3 class="card-title"><b>
                  <?php echo $section ?>'s
                </b> List of Students</h3>

              <div class="card-tools">
                <button type="submit" class="btn btn-success btn-sm" name="submit">Save Changes</button>
              </div>
            </div>
            <div class="card-body">
              <table id="example4" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Image</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Course</th>
                    <th>Prelims</th>
                    <th>Midterms</th>
                    <th>Finalterms</th>
                    <th>Final Grade</th>
                    <th>Numerical Grade</th>
                    <th>Remarks</th>
                    <th>Absences</th>
                    <th>INC Status</th>
                    <th>Update At</th>
                    <th>Updated By</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $load_info = mysqli_query($conn, "SELECT *, CONCAT(tbl_students.lastname, ', ', tbl_students.firstname, ' ', tbl_students.middlename)  as fullname, tbl_enrolled_subjects.last_update
                FROM tbl_enrolled_subjects 
                LEFT JOIN tbl_subjects_new ON tbl_subjects_new.subj_id = tbl_enrolled_subjects.subj_id
                LEFT JOIN tbl_students ON tbl_students.stud_id = tbl_enrolled_subjects.stud_id
                LEFT JOIN tbl_schoolyears ON tbl_schoolyears.stud_id = tbl_students.stud_id
                LEFT JOIN tbl_schedules ON tbl_schedules.schedule_id = tbl_enrolled_subjects.schedule_id
                LEFT JOIN tbl_courses ON tbl_courses.course_id = tbl_schoolyears.course_id
                WHERE tbl_schedules.schedule_id = '$schedule_id'
                AND tbl_schedules.section = '$section' 
                AND tbl_schoolyears.ay_id = '$acadyear'
                AND tbl_schoolyears.sem_id = '$semester'
                AND tbl_schoolyears.remark = 'Approved'
                ORDER BY lastname ASC");

                  while ($row = mysqli_fetch_array($load_info)) {
                    $last_updated = new DateTime($row['last_update']);
                    ?>
                    <tr>
                      <input type="text" name="enrolled_subj_id[]" value="<?php echo $row['enrolled_subj_id']; ?>" hidden>
                      <input type="text" name="special_tut[]" value="<?php echo $row['special_tut']; ?>" hidden>
                      <td>
                        <?php
                        if (empty($row['img'])) {

                        } else {
                          ?>
                          <img style="width: 80px; height: 80px;"
                            src="data:image/jpeg;base64,<?php echo base64_encode($row['img']) ?>">
                          <?php
                        }
                        ?>
                      </td>
                      <td>
                        <?php echo $row['stud_no']; ?>
                      </td>
                      <td>
                        <?php echo strtoupper($row['fullname']); ?>
                      </td>
                      <td>
                        <?php echo $row['course_abv']; ?>
                      </td>
                      <td>
                        <?php
                        if ($_SESSION['active_semester'] == "Summer" || $row['special_tut'] == 1) {
                          ?>
                          <input type="text" class="form-control" placeholder="Enter ..." onkeyup="ofGrade()" name="prelim[]"
                            id="prelim" value="<?php echo $row['prelim'] ?>" disabled>
                          <?php
                        } else {
                          ?>
                          <input type="text" class="form-control" placeholder="Enter ..." onkeyup="ofGrade()" name="prelim[]"
                            id="prelim" value="<?php echo $row['prelim'] ?>">
                          <?php
                        }
                        ?>
                      </td>
                      <td>
                        <input type="text" class="form-control" placeholder="Enter ..." onkeyup="ofGrade()" name="midterm[]"
                          id="midterm" value="<?php echo $row['midterm'] ?>">
                      </td>
                      <td>
                        <input type="text" class="form-control" placeholder="Enter ..." onkeyup="ofGrade()"
                          name="finalterm[]" id="finalterm" value="<?php echo $row['finalterm'] ?>">
                      </td>
                      <td>
                        <?php echo $row['ofgrade']; ?>
                      </td>
                      <td>
                        <?php echo $row['numgrade']; ?>
                      </td>
                      <?php
                      if ($row['remarks'] == "Passed") {
                        ?>
                        <td style="color: green; font-weight: bold;">
                          <?php echo $row['remarks']; ?>
                        </td>
                        <?php
                      } elseif ($row['remarks'] == "Failed") {
                        ?>
                        <td style="color: red; font-weight: bold;">
                          <?php echo $row['remarks']; ?>
                        </td>
                        <?php
                      } else {
                        ?>
                        <td style="color: orange; font-weight: bold;">
                          <?php echo $row['remarks']; ?>
                        </td>
                        <?php
                      }
                      ?>
                      
                      <td>
                        <input type="text" class="form-control" placeholder="Enter ..." onkeyup="ofGrade()"
                          name="absences[]" id="absences" value="<?php echo $row['absences'] ?>">
                      </td>
                      <td>
                          <?php echo $row['inc_status']; ?>
                      </td>
                      <td>
                        <?php echo $last_updated->format('h:i a \o\n M d, Y') ?>
                      </td>
                      <td>
                        <?php echo $row['updated']; ?>
                      </td>
                    </tr>
                    <?php
                  }
                  ?>
                </tbody>
                <tfoot>
                </tfoot>
              </table>
          </form>
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