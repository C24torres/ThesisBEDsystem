<?php
include '../../../includes/session.php';
$schedule_id = $_GET['schedule_id'];
$section = $_GET['section'];

if (isset($_GET['semester']) && isset($_GET['acadyear'])) {
  $acadyear = $_GET['acadyear'];
  $semester = $_GET['semester'];
} else {
  $acadyear = $_SESSION['active_acadyear'];
  $semester = $_SESSION['active_semester'];
}

if (isset($_POST['submit'])) {

    $enrolled_subj_array = array();

    if (isset($_POST['enrolled_sub_id'])) {
        $temp_array = $_POST['enrolled_sub_id'];

        foreach ($temp_array as $index) {
            if ($index != null) {
                array_push($enrolled_subj_array, $index);
            } else {
                array_push($enrolled_subj_array, 0);
            }
        }
    }

    echo $enrolled_subj_array[0];

    foreach ($enrolled_subj_array as $enrolled_sub_id) {

        $new_schedule_id = mysqli_real_escape_string($conn, $_POST['new_schedule_id']);
    
        $student_sched = mysqli_query($conn, "UPDATE tbl_enrolled_subjects SET schedule_id = '$new_schedule_id' WHERE enrolled_sub_id = '$enrolled_sub_id'");
    
    }

    $_SESSION['update_success'] = true;
    header("location: ../transfer.class.php?schedule_id=" . $schedule_id . "&section=" . $section . "&acadyear=" . $acadyear . "&semester=" .$semester);
}


?>