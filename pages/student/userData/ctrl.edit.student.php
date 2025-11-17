<?php
include '../../../includes/session.php';

$stud_id = $_GET['student_id'];
if (isset($_GET['semester']) && isset($_GET['acadyear'])) {
    $acadyear = $_GET['acadyear'];
    $semester = $_GET['semester'];
} else {
    $acadyear = $_SESSION['active_acadyears'];
    $semester = $_SESSION['active_semester'];
}

if (isset($_POST['submit'])) {

    
    header("location: ../list.students.php?acadyear=". $acadyear ."&semester=". $semester);

} elseif (isset($_POST['submit2'])) {
    $status = mysqli_real_escape_string($conn, $_POST['tuition_status']);
    $updated_by = $_SESSION['name'] . ' - ' . $_SESSION['role'];
    
    if ($status == "Unpaid") {
        $acc_status = "Disabled";
    } else {
        $acc_status = "Enabled";
    }

    $student_info = mysqli_query($conn, "UPDATE tbl_schoolyears SET accounting_status = '$acc_status', tuition_status = '$status', updatedby = '$updated_by'
    WHERE semester_id = '$semester' AND ay_id = '$acadyear' AND student_id = '$stud_id'");
    
    $_SESSION['update_success'] = true;
    header("location: ../list.students.php?acadyear=". $acadyear ."&semester=". $semester);
    
} elseif (isset($_POST['submit_all2'])) {
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $updated_by = $_SESSION['name'] . ' - ' . $_SESSION['role'];
    
    if ($status == "Unpaid") {
        $acc_status = "Disabled";
    } else {
        $acc_status = "Enabled";
    }

    $student_info = mysqli_query($conn, "UPDATE tbl_schoolyears SET accounting_status = '$acc_status', tuition_status = '$status', updatedby = '$updated_by'
    WHERE semester_id = '$semester' AND ay_id = '$acadyear'");
    
    $_SESSION['update_success'] = true;
    header("location: ../list.students.php?acadyear=". $acadyear ."&semester=". $semester);

}



?>