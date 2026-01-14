<?php
require '../../includes/session.php';

if (isset($_POST['semester']) && isset($_POST['acadyear'])) {
    $acadyear = $_POST['acadyear'];
    $semester = $_POST['semester'];
  } else {
    $acadyear = $_SESSION['active_acadyears'];
    $semester = $_SESSION['active_semester'];
  }

if ($_SESSION['role'] == "Student") {
    $student_id = $_SESSION['id'];
} elseif (isset($_POST['submit']) && !empty($_POST['student_id'])) {
    $student_id = $_POST['student_id'];
} else {

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student's Record | BED OnGrade - Laspinas</title>

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
                            <h1 class="m-0">Student's Permanent Record</h1>
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
                    <form method="POST">
                        <div class="card-header">
                            <h3 class="card-title">Select Student</h3>

                            <div class="card-tools">
                                <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
              <i class="fas fa-times"></i>
            </button> -->
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Student</label>
                                        <select class="form-control select2" name="student_id" style="width: 100%;" <?php echo ($_SESSION['role'] == "Student") ? 'disabled' : ''; ?>>
                                            <option selected disabled>Select Student</option>
                                            <?php
                                            if ($_SESSION['role'] == "Student") {
                                            $stud_info = mysqli_query($conn, "SELECT student_id, CONCAT(student_lname, ', ', student_fname, ' ', student_mname) as fullname FROM tbl_students WHERE student_id = '$student_id' ORDER BY student_lname ASC");
                                            while ($row = mysqli_fetch_array($stud_info)) {
                                            ?>
                                                <option selected value="<?php echo $row['student_id'] ?>"><?php echo $row['fullname'] ?>
                                                </option>
                                            <?php
                                            } } else {
                                            $stud_info = mysqli_query($conn, "SELECT student_id, CONCAT(student_lname, ', ', student_fname, ' ', student_mname) as fullname FROM tbl_students ORDER BY student_lname ASC");
                                            while ($row = mysqli_fetch_array($stud_info)) {
                                            ?>
                                                <option value="<?php echo $row['student_id'] ?>"><?php echo $row['fullname'] ?>
                                                </option>
                                            <?php } }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Semester</label>
                                        <select class="form-control select2" name="semester" style="width: 100%;">
                                            <?php
                                            $sem_info = mysqli_query($conn, "SELECT * FROM tbl_semesters WHERE semester = '$semester'");
                                            while ($row = mysqli_fetch_array($sem_info)) {
                                                ?>
                                                <option selected value="<?php echo $row['semester']; ?>"><?php echo $row['semester']; ?></option>
                                            <?php } ?>
                                            <?php
                                            $sem_info = mysqli_query($conn, "SELECT * FROM tbl_semesters WHERE NOT semester = '$semester'");
                                            while ($row = mysqli_fetch_array($sem_info)) {
                                                ?>
                                                <option value="<?php echo $row['semester']; ?>"><?php echo $row['semester']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Academic Year</label>
                                        <select class="form-control select2" name="acadyear" style="width: 100%;">
                                            <?php
                                            $ay_info = mysqli_query($conn, "SELECT * FROM tbl_acadyears WHERE academic_year = '$acadyear' ORDER BY academic_year DESC");
                                            while ($row = mysqli_fetch_array($ay_info)) {
                                                ?>
                                                <option value="<?php echo $row['academic_year'] ?>"><?php echo $row['academic_year'] ?></option>
                                            <?php } ?>
                                            <?php
                                            $ay_info = mysqli_query($conn, "SELECT * FROM tbl_acadyears WHERE NOT academic_year = '$acadyear' ORDER BY academic_year DESC");
                                            while ($row = mysqli_fetch_array($ay_info)) {
                                                ?>
                                                <option value="<?php echo $row['academic_year'] ?>"><?php echo $row['academic_year'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-sm float-right" name="submit">Search
                                Record</button>
                        </div>
                    </form>
                    <!-- /.card-footer-->
                </div>
                <!-- /.card -->
                <?php
                    if (isset($student_id)) {

                        echo 1;
                    $stud_info = mysqli_query($conn, "SELECT *, CONCAT(student_lname, ', ', student_fname, ' ', student_mname) as fullname FROM tbl_students 
                    LEFT JOIN tbl_schoolyears ON tbl_schoolyears.student_id = tbl_students.student_id WHERE tbl_schoolyears.student_id = '$student_id' AND tbl_schoolyears.ay_id = '$acadyear' AND tbl_schoolyears.semester_id = '$semester'");
                    if (mysqli_num_rows($stud_info) != 0) {
                        echo 2;
                    $row = mysqli_fetch_array($stud_info);

                    ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <b><?php echo $row['fullname']; ?>'s </b> Permanent Record for <b><?php echo $semester .' - '. $acadyear?></b>
                            </h3>
                            <div class="card-tools">
                                <?php
                                if ($_SESSION['role'] == "Registrar" || $_SESSION['role'] == "Student" && $row['accounting_status'] != "Disabled") {
                                ?>
                                <a class="btn btn-primary btn-sm" href="../forms/student.gwa.php?student_id=<?php echo $row['student_id']?>&acadyear=<?php echo $acadyear?>&semester=<?php echo $semester?>">Check GWA</a>
                                <?php
                                }
                                if ($_SESSION['role'] == "Super Administrator" || $_SESSION['role'] == "Registrar" || $_SESSION['role'] == "Enrollment Staff") {
                                ?>
                                <a class="btn btn-primary btn-sm" href="../forms/student.permanent.record.php?student_id=<?php echo $row['student_id']?>&acadyear=<?php echo $acadyear?>&semester=<?php echo $semester?>">Permanent Record</a>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Subject Code</th>
                                        <th>Subject Description</th>
                                        <th>Midterm</th>
                                        <th>Finalterm</th>
                                        <th>Final Grade</th>
                                        <th>Numerical Grade</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stud_info = mysqli_query($conn, "SELECT * FROM tbl_enrolled_subjects
                                    LEFT JOIN tbl_subjects_senior ON tbl_subjects_senior.subject_id = tbl_enrolled_subjects.subject_id
                                    WHERE student_id = '$student_id' AND academic_year = '$acadyear' AND semester = '$semester'");
                                    while ($row2 = mysqli_fetch_array($stud_info)) {
                                        $faculty_info = mysqli_query($conn, "SELECT *, CONCAT(teacher_lname, ', ', teacher_fname, ' ', teacher_mname) AS teacher_name FROM tbl_teachers
                                        LEFT JOIN tbl_schedules ON tbl_schedules.teacher_id = tbl_teachers.teacher_id WHERE schedule_id = '$row2[schedule_id]'");

                                        $row3 = mysqli_fetch_array($faculty_info);

                                        if ($_SESSION['role'] == "Student" && $row['accounting_status'] == "Disabled") {
                                        ?>
                                        <tr>
                                            <td colspan="8">Your grades are currently unavaible due to pending accounts.<br> Please refer to the <b>Registrar's Office</b> for clarification.</td>
                                        </tr>
                                        <?php
                                        break;
                                        } else { 
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $row2['subject_code']; ?>
                                            </td>
                                            <td>
                                                <?php echo $row2['subject_description']; ?><br>Instructor: <?php echo $row3['teacher_name']; ?>
                                            </td>
                                            
                                            <td>
                                                <?php echo $row2['midterm']; ?>
                                            </td>
                                            <td>
                                                <?php echo $row2['finalterm']; ?>
                                            </td>
                                            <td>
                                                <?php echo $row2['ofgrade']; ?>
                                            </td>
                                            <td>
                                                <?php echo $row2['numgrade']; ?>
                                            </td>
                                            <?php
                                            if ($row2['remarks'] == "Passed") {
                                            ?>
                                            <td style="color: green; font-weight: bold;">
                                                <?php echo $row2['remarks']; ?>
                                            </td>
                                            <?php
                                            } elseif ($row2['remarks'] == "Failed") {
                                            ?>
                                            <td style="color: red; font-weight: bold;">
                                                <?php echo $row2['remarks']; ?>
                                            </td>
                                            <?php
                                            } else {
                                            ?>
                                            <td style="color: orange; font-weight: bold;">
                                                <?php echo $row2['remarks']; ?>
                                            </td>
                                            <?php
                                            }
                                            ?>
                                        </tr>
                                        <?php
                                    } }
                                    ?>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <?php
                    } }
                    ?>
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