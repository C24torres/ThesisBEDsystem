<?php
include '../../../includes/session.php';
$class_id = $_GET['class_id'];
$section = $_GET['section'];
$enrolled_subj_id = $_GET['enrolled_subj_id'];

if (isset($_GET['semester']) && isset($_GET['acadyear'])) {
  $acadyear = $_GET['acadyear'];
  $semester = $_GET['semester'];
} else {
  $acadyear = $_SESSION['active_acadyear'];
  $semester = $_SESSION['active_semester'];
}

if (isset($_POST['submit'])) {

    $new_class_id = mysqli_real_escape_string($conn, $_POST['new_class_id']);

    echo $new_class_id.' new class <br>';
    echo $class_id;

    $student_sched = mysqli_query($conn, "UPDATE tbl_enrolled_subjects SET class_id = '$new_class_id' WHERE enrolled_subj_id = '$enrolled_subj_id'");

    $_SESSION['update_success'] = true;
    header("location: ../class.php?class_id=" . $class_id . "&section=" . $section . "&acadyear=" . $acadyear . "&semester=" .$semester);

}


?>