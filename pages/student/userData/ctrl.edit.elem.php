<?php
include '../../../includes/session.php';

$student_id = $_GET['student_id'];
if ( isset($_GET['acadyear'])) {
    $acadyear = $_GET['acadyear'];
} else {
    $acadyear = $_SESSION['active_acadyears'];
}

if (isset($_POST['submit'])) {

    
    header("location: ../list.students.php?acadyear=". $acadyear );

} elseif (isset($_POST['submit2'])) {
    $status = mysqli_real_escape_string($conn, $_POST['tuition_status']);
    $updated_by = $_SESSION['name'] . ' - ' . $_SESSION['role'];
    
    if ($status == "Unpaid") {
        $acc_status = "Disabled";
    } else {
        $acc_status = "Enabled";
    }

    $student_info = mysqli_query($conn, "UPDATE tbl_schoolyears 
    LEFT JOIN tbl_acadyears ON tbl_acadyears.ay_id = tbl_schoolyears.ay_id
    SET accounting_status = '$acc_status', tuition_status = '$status', updatedby = '$updated_by'
    
    WHERE  academic_year = '$acadyear' AND student_id = '$student_id'");
    
    $_SESSION['update_success'] = true;
    header("location: ../list.elem.php?acadyear=". $acadyear);

    $_SESSION['update_success'] = true;
    header("location: ../list.elem.php?acadyear=". $acadyear );
    
} elseif (isset($_POST['submit_all2'])) {
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $updated_by = $_SESSION['name'] . ' - ' . $_SESSION['role'];
    
    if ($status == "Unpaid") {
        $acc_status = "Disabled";
    } else {
        $acc_status = "Enabled";
    }

    $student_info = mysqli_query($conn, "UPDATE tbl_schoolyears 
    LEFT JOIN tbl_acadyears ON tbl_acadyears.ay_id = tbl_schoolyears.ay_id
    SET accounting_status = '$acc_status', tuition_status = '$status', updatedby = '$updated_by'
    
    WHERE  academic_year = '$acadyear'");
    
    $_SESSION['update_success'] = true;
    header("location: ../list.elem.php?acadyear=". $acadyear );

}



?>