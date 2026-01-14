<?php
include '../../../includes/session.php';

if (isset($_POST['submit'])) {

    $username = mysqli_escape_string($conn, $_POST['username']);
    $email = mysqli_escape_string($conn, $_POST['email']);
    $password = mysqli_escape_string($conn, $_POST['password']);
    $confirm_pass = mysqli_escape_string($conn, $_POST['confirm_pass']);
    $updated_by = $_SESSION['name'] . " <br> (" . $_SESSION['role'] . ")";

    if ($password == $confirm_pass) {
        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

        if ($_SESSION['role'] == "Student") {
            $stud_info = mysqli_query($conn,"UPDATE tbl_students SET username = '$username', email = '$email', password = '$hashedPwd', updated_by = '$updated_by', last_updated = CURRENT_TIMESTAMP WHERE student_id = '$_SESSION[id]'");
    
        } elseif ($_SESSION['role'] == "Super Administrator") {
            $sa_info = mysqli_query($conn,"UPDATE tbl_master_key SET username = '$username', email = '$email', password = '$hashedPwd', updated_by = '$updated_by', last_updated = CURRENT_TIMESTAMP WHERE mk_id = '$_SESSION[id]'");
    
        } elseif ($_SESSION['role'] == "Adviser") {
            $faculty_info = mysqli_query($conn,"UPDATE tbl_adviser SET username = '$username', email = '$email', password = '$hashedPwd', updated_by = '$updated_by', last_updated = CURRENT_TIMESTAMP WHERE ad_id = '$_SESSION[id]'");
            
        } elseif ($_SESSION['role'] == "Faculty Staff") {
            $faculty_staff_info = mysqli_query($conn,"UPDATE tbl_teachers SET username = '$username', email = '$email', password = '$hashedPwd', updated_by = '$updated_by', last_updated = CURRENT_TIMESTAMP WHERE teacher_id = '$_SESSION[id]'");
            
        } elseif ($_SESSION['role'] == "Registrar") {
            $admin_info = mysqli_query($conn,"UPDATE tbl_registrars SET username = '$username', email = '$email', password = '$hashedPwd', updated_by = '$updated_by', last_updated = CURRENT_TIMESTAMP WHERE reg_id = '$_SESSION[id]'");
            
        }

        $_SESSION['update_success'] = true;
        header("location: ../edit.account.php");

    } else {
        $_SESSION['password_unmatch'] = true;
        header("location: ../edit.account.php");

    }


    

}

?>