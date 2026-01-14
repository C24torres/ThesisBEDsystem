<?php
require '../../includes/session.php';

$schedule_id = $_GET['schedule_id'];
$section = $_GET['section'];

if (isset($_GET['semester']) && isset($_GET['acadyear'])) {
  $acadyear = $_GET['acadyear'];
  $semester = $_GET['semester'];
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
  <title>Class | BED OnGrade - Laspinas</title>

  <?php include '../../includes/links.php'; ?>

    <?php
  // Get grade level for the section (only once)
  $level_query = mysqli_query($conn, "SELECT tbl_schoolyears.grade_level_id
  FROM tbl_enrolled_subjects
  LEFT JOIN tbl_students ON tbl_students.student_id = tbl_enrolled_subjects.student_id
  LEFT JOIN tbl_schoolyears ON tbl_schoolyears.student_id = tbl_students.student_id
  WHERE tbl_enrolled_subjects.schedule_id = '$schedule_id'
  LIMIT 1");

  $level = mysqli_fetch_assoc($level_query);
  $grade_level_id = $level['grade_level_id'] ?? 0;

  // Grade level conditions
  $is_k10 = ($grade_level_id >= 1 && $grade_level_id <= 13);
  $is_shs = ($grade_level_id >= 14 && $grade_level_id <= 15);
  ?>
  
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
            </b> List of Students <b>(
              <?php 
                if($is_shs){
                    echo $semester . ' - ' . $acadyear; 
                } else {
                    echo $acadyear;
                }
              ?>
          )</b></h3>
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
                  
                  <?php if($is_shs): ?>
                      <th>Strand</th>
                      <th>Midterms</th>
                      <th>Finalterms</th>
                  <?php elseif($is_k10): ?>
                      <th>1st Quarter</th>
                      <th>2nd Quarter</th>
                      <th>3rd Quarter</th>
                      <th>4th Quarter</th>
                  <?php endif; ?>

                  <th>Final Grade</th>
                  <th>Remarks</th>
                  <th>Absences</th>
                  <th>INC Status</th>
                  <th>Updated At</th>
                  <th>Updated By</th>
                </tr>
                </thead>
                <tbody>
                  <?php
                if ($is_k10) {
                    // K–10: ignore semester, get all 4 quarters for the acadyear
                    $load_info = mysqli_query($conn, "SELECT tbl_students.student_id, img, stud_no, first_quarter, second_quarter, third_quarter, fourth_quarter,
                              ofgrade, numgrade, remarks, absences, inc_status, updated, tbl_enrolled_subjects.last_update, enrolled_sub_id, special_tut, class_code,
                              CONCAT(tbl_students.student_lname, ', ', tbl_students.student_fname, ' ', tbl_students.student_mname) as fullname
                        FROM tbl_enrolled_subjects 
                        LEFT JOIN tbl_students ON tbl_students.student_id = tbl_enrolled_subjects.student_id
                        LEFT JOIN tbl_schoolyears ON tbl_schoolyears.student_id = tbl_students.student_id
                        LEFT JOIN tbl_schedules ON tbl_schedules.schedule_id = tbl_enrolled_subjects.schedule_id
                        WHERE tbl_schedules.schedule_id = '$schedule_id'
                          AND tbl_schedules.section = '$section'
                          AND tbl_schoolyears.grade_level_id BETWEEN 1 AND 13
                          AND tbl_schoolyears.acadyear = '$acadyear'
                          AND tbl_schoolyears.remark = 'Approved'
                        ORDER BY tbl_students.student_lname ASC
                    ");
                } elseif ($is_shs) {
                    // SHS: filter by semester, show Midterm & Final
                    $load_info = mysqli_query($conn, "SELECT tbl_students.student_id, img, stud_no, strand_name, midterm, finalterm,
                              ofgrade, numgrade, remarks, absences, inc_status, updated, tbl_enrolled_subjects.last_update, enrolled_sub_id, special_tut, class_code,
                              CONCAT(tbl_students.student_lname, ', ', tbl_students.student_fname, ' ', tbl_students.student_mname) as fullname
                        FROM tbl_enrolled_subjects
                        LEFT JOIN tbl_students ON tbl_students.student_id = tbl_enrolled_subjects.student_id
                        LEFT JOIN tbl_schoolyears ON tbl_schoolyears.student_id = tbl_students.student_id
                        LEFT JOIN tbl_schedules ON tbl_schedules.schedule_id = tbl_enrolled_subjects.schedule_id
                        LEFT JOIN tbl_strands ON tbl_strands.strand_id = tbl_schoolyears.strand_id
                        LEFT JOIN tbl_acadyears ON tbl_acadyears.ay_id = tbl_schoolyears.ay_id
                        LEFT JOIN tbl_semesters ON tbl_semesters.semester_id = tbl_schoolyears.semester_id
                        WHERE tbl_schedules.schedule_id = '$schedule_id'
                          AND tbl_schedules.section = '$section'
                          AND tbl_schoolyears.grade_level_id BETWEEN 14 AND 15
                          AND tbl_acadyears.academic_year = '$acadyear'
                          AND tbl_semesters.semester = '$semester'
                          AND tbl_schoolyears.remark = 'Approved'
                        ORDER BY tbl_students.student_lname ASC
                    ");
                }

                  while ($row = mysqli_fetch_array($load_info)) {
                    $last_updated = new DateTime($row['last_update']);
                    ?>
                    <tr>
                      <input type="text" name="enrolled_sub_id[]" value="<?php echo $row['enrolled_sub_id']; ?>" hidden>
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
                      <?php if($is_shs): ?>
                        <td>
                        <?php echo $row['strand_name']; ?>
                      </td>
                        <td>
                          <input type="text" class="form-control" placeholder="Enter ..." onkeyup="ofGrade()" name="midterm[]"
                          id="midterm" value="<?php echo $row['midterm'] ?>">
                        </td>
                        <td>
                          <input type="text" class="form-control" placeholder="Enter ..." onkeyup="ofGrade()"
                          name="finalterm[]" id="finalterm" value="<?php echo $row['finalterm'] ?>">
                        </td>
                    <?php elseif($is_k10): ?>
                        <td>
                          <input type="text" class="form-control" placeholder="Enter ..." onkeyup="ofGrade()"
                          name="first_quarter[]" id="first_quarter" value="<?php echo $row['first_quarter'] ?>">
                        </td>
                        <td>
                          <input type="text" class="form-control" placeholder="Enter ..." onkeyup="ofGrade()"
                          name="second_quarter[]" id="second_quarter" value="<?php echo $row['second_quarter'] ?>">
                        </td>
                        <td>
                          <input type="text" class="form-control" placeholder="Enter ..." onkeyup="ofGrade()"
                          name="third_quarter[]" id="third_quarter" value="<?php echo $row['third_quarter'] ?>">
                        </td>
                        <td>
                          <input type="text" class="form-control" placeholder="Enter ..." onkeyup="ofGrade()"
                          name="fourth_quarter[]" id="finalterm" value="<?php echo $row['finalterm'] ?>">
                        </td>
                        
                    <?php endif; ?>
                      
                      <td>
                        <?php echo $row['ofgrade']; ?>
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