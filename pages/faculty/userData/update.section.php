<?php
include '../../../includes/session.php';
$schedule_id = $_GET['schedule_id'];
$section = $_GET['section'];
$enrolled_sub_id = $_GET['enrolled_sub_id'];

if (isset($_GET['semester']) && isset($_GET['acadyear'])) {
  $acadyear = $_GET['acadyear'];
  $semester = $_GET['semester'];
} else {
  $acadyear = $_SESSION['active_acadyears'];
  $semester = $_SESSION['active_semester'];
}

if (isset($_POST['submit'])) {

    $new_class_id = mysqli_real_escape_string($conn, $_POST['new_class_id']);

    echo $new_class_id.' new class <br>';
    echo $schedule_id;

    $student_sched = mysqli_query($conn, "UPDATE tbl_enrolled_subjects SET schedule_id = '$new_class_id' WHERE enrolled_sub_id = '$enrolled_sub_id'");

    $_SESSION['update_success'] = true;
    header("location: ../class.php?schedule_id=" . $schedule_id . "&section=" . $section . "&acadyear=" . $acadyear . "&semester=" .$semester);

}


?>