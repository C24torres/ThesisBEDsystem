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
date_default_timezone_set('Asia/Manila');
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
          <div class="card-header">
            <h3 class="card-title"><b>
                <?php echo $section ?>'s
              </b> List of Students <b>(<?php echo $semester .' - '. $acadyear?>)</b></h3>

            <div class="card-tools">
                <a href="grade.class.php?schedule_id=<?php echo $schedule_id; ?>&section=<?php echo $section; ?>&acadyear=<?php echo $acadyear?>&semester=<?php echo $semester?>"
                class="btn btn-primary btn-sm">Enter Section Grade</a>
                <a href="transfer.class.php?schedule_id=<?php echo $schedule_id; ?>&section=<?php echo $section; ?>&acadyear=<?php echo $acadyear?>&semester=<?php echo $semester?>"
                class="btn btn-primary btn-sm my-1">Transfer Students</a>
            </div>
          </div>
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Image</th>
                  <th>Student ID</th>
                  <th>Student Name</th>
                  <th>Strand</th>
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
                  <th>Option</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $load_info = mysqli_query($conn, "SELECT *, CONCAT(tbl_students.student_lname, ', ', tbl_students.student_fname, ' ', tbl_students.student_mname)  as fullname, tbl_enrolled_subjects.last_update
                FROM tbl_enrolled_subjects 
                LEFT JOIN tbl_subjects_senior ON tbl_subjects_senior.subject_id = tbl_enrolled_subjects.subject_id
                LEFT JOIN tbl_students ON tbl_students.student_id = tbl_enrolled_subjects.student_id
                LEFT JOIN tbl_schoolyears ON tbl_schoolyears.student_id = tbl_students.student_id
                LEFT JOIN tbl_schedules ON tbl_schedules.schedule_id = tbl_enrolled_subjects.schedule_id
                LEFT JOIN tbl_strands ON tbl_strands.strand_id = tbl_schoolyears.strand_id
                LEFT JOIN tbl_acadyears ON tbl_acadyears.ay_id = tbl_schoolyears.ay_id
                LEFT JOIN tbl_semesters ON tbl_semesters.semester_id = tbl_schoolyears.semester_id
                WHERE tbl_schedules.schedule_id = '$schedule_id'
                AND tbl_schedules.section = '$section' 
                AND tbl_acadyears.academic_year = '$acadyear'
                AND tbl_semesters.semester = '$semester'
                AND tbl_schoolyears.remark = 'Approved'
                ORDER BY student_lname ASC");

                while ($row = mysqli_fetch_array($load_info)) {
                    // $last_updated = 0;
                  ?>
                  <tr>
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
                      <?php echo $row['strand_name']; ?>
                    </td>
                    <td>
                      <?php echo $row['prelim']; ?>
                    </td>
                    <td>
                      <?php echo $row['midterm']; ?>
                    </td>
                    <td>
                      <?php echo $row['finalterm']; ?>
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
                      <?php echo $row['absences']; ?>
                    </td>
                    <td>
                      <?php echo $row['inc_status']; ?>
                    </td>
                    <td>
                      <?php //echo $last_updated->format('h:i a \o\n M d, Y') ?>
                    </td>
                    <td>
                      <?php echo $row['updated']; ?>
                    </td>
                    <td>
                      <button class="btn btn-primary btn-sm" data-toggle="modal"
                        data-target="#modal-lg<?php echo $row['enrolled_sub_id']; ?>">Enter Grade</button>
                        <button class="btn btn-primary btn-sm my-1" data-toggle="modal"
                        data-target="#modal-lg-transfer<?php echo $row['enrolled_sub_id']; ?>">Transfer Section</button>
                    </td>
                  </tr>
                  <!-- Modal for grade input -->
                  <div class="modal fade" id="modal-lg<?php echo $row['enrolled_sub_id']; ?>">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Enter Grade/Absences for <b>
                              <?php echo strtoupper($row['fullname']); ?>
                            </b></h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form action="userData/enter.grade.php?schedule_id=<?php echo $schedule_id ?>&section=<?php echo $section ?>&acadyear=<?php echo $acadyear?>&semester=<?php echo $semester?>"
                          method="POST">
                          <div class="modal-body">
                            <input name="enrolled_sub_id" value="<?php echo $row['enrolled_sub_id']; ?>" hidden>
                            <input name="special_tut" value="<?php echo $row['special_tut']; ?>" hidden>
                            <div class="row">
                              <div class="col-sm-4">
                                <div class="form-group">
                                  <label>Prelims
                                    <?php echo $_SESSION['active_semester'] ?>
                                  </label>
                                  <?php
                                  if ($_SESSION['active_semester'] == "Summer" || $row['special_tut'] == 1) {
                                    ?>
                                    <input type="text" class="form-control" placeholder="Enter ..." onkeyup="ofGrade()"
                                      name="prelim" id="prelim" value="<?php echo $row['prelim'] ?>" disabled>
                                    <?php
                                  } else {
                                    ?>
                                    <input type="text" class="form-control" placeholder="Enter ..." onkeyup="ofGrade()"
                                      name="prelim" id="prelim" value="<?php echo $row['prelim'] ?>">
                                    <?php
                                  }
                                  ?>
                                </div>
                              </div>
                              <div class="col-sm-4">
                                <div class="form-group">
                                  <label>Midterms</label>
                                  <input type="text" class="form-control" placeholder="Enter ..." onkeyup="ofGrade()"
                                    name="midterm" id="midterm" value="<?php echo $row['midterm'] ?>">
                                </div>
                              </div>
                              <div class="col-sm-4">
                                <div class="form-group">
                                  <label>Finalterm</label>
                                  <input type="text" class="form-control" placeholder="Enter ..." onkeyup="ofGrade()"
                                    name="finalterm" id="finalterm" value="<?php echo $row['finalterm'] ?>">
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-sm-4">
                                <div class="form-group">
                                  <label>Final Grade</label>
                                  <input type="text" class="form-control" placeholder="Enter ..." name="ofgrade"
                                    id="ofgrade" value="<?php echo $row['ofgrade'] ?>" disabled>
                                </div>
                              </div>
                              <div class="col-sm-4">
                                <div class="form-group">
                                  <label>Numerical Grade</label>
                                  <input type="text" class="form-control" placeholder="Enter ..." name="numgrade"
                                    id="numgrade" value="<?php echo $row['numgrade'] ?>" disabled>
                                </div>
                              </div>
                              <div class="col-sm-4">
                                <div class="form-group">
                                  <label>Final Remark</label>
                                  <input type="text" class="form-control" placeholder="Enter ..." name="remarks"
                                    id="remarks" value="<?php echo $row['remarks'] ?>" disabled>
                                </div>
                              </div>
                            </div>
                            <hr>
                            <div class="row">
                              <div class="col-sm-4">
                                <div class="form-group">
                                  <label>Absences</label>
                                  <input type="text" class="form-control" placeholder="Enter ..." name="absences"
                                    id="absences" value="<?php echo $row['absences'] ?>">
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
                  <!-- Modal for grade input -->
                  <div class="modal fade" id="modal-lg-transfer<?php echo $row['enrolled_sub_id']; ?>">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Transfer <b>
                              <?php echo strtoupper($row['fullname']); ?>
                            </b> to other section</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form action="userData/update.section.php?enrolled_sub_id=<?php echo $row['enrolled_sub_id']; ?>&schedule_id=<?php echo $schedule_id ?>&section=<?php echo $section ?>&acadyear=<?php echo $acadyear?>&semester=<?php echo $semester?>"
                          method="POST">
                          <div class="modal-body">
                            <div class="row">
                              <div class="col-sm-6">
                                <div class="form-group">
                                  <label>Current Section</label>
                                  <input type="text" class="form-control" placeholder="Enter ..."
                                    name="midterm" id="midterm" value="<?php echo $section?>" disabled>
                                </div>
                              </div>
                              <div class="col-sm-6">
                                <div class="form-group">
                                  <label>Transfer to</label>
                                  <select class="form-control select2" name="new_class_id">
                                    <option selected disabled>Select section</option>
                                    <?php
                                    $sechedules_info = mysqli_query($conn, "SELECT * FROM tbl_schedules
                                    LEFT JOIN tbl_subjects ON tbl_schedules.subject_id = tbl_subjects.subject_id
                                    LEFT JOIN tbl_teachers ON tbl_schedules.teacher_id = tbl_teachers.teacher_id
                                    WHERE class_code = '$row[class_code]' AND acadyear = '$acadyear' AND semester = '$semester' AND section NOT IN ('$section')");
                                    while ($row1 = mysqli_fetch_array($sechedules_info)) {
                                    ?>
                                    <option value="<?php echo $row1['schedule_id']?>"><?php echo $row1['class_code'] .' - '. $row1['section'] .' ('. $row1['teacher_lname'] .')'?></option></option>
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
                            <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
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