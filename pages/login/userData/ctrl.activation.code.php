<?php
require '../../../includes/conn.php';
session_start();


if (isset($_POST['submit']) && isset($_POST['activation_code'])) {
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $activation_code = mysqli_real_escape_string($conn, $_POST['activation_code']);

    $check_username_sa = mysqli_query($conn, "SELECT *, name AS fullname FROM tbl_master_key WHERE username = '$username'") or die(mysqli_error($conn));
    $count_sa = mysqli_num_rows($check_username_sa);

    $check_username_admins = mysqli_query($conn, "SELECT *, CONCAT(reg_fname, ', ', reg_lname) AS fullname FROM tbl_registrars WHERE username = '$username'") or die(mysqli_error($conn));
    $count_admins = mysqli_num_rows($check_username_admins);

    $check_username_faculties_staff = mysqli_query($conn, "SELECT *, CONCAT(teacher_fname, ', ', teacher_lname) AS fullname FROM tbl_teachers WHERE username = '$username'") or die(mysqli_error($conn));
    $count_faculties_staff = mysqli_num_rows($check_username_faculties_staff);

    $check_username_students = mysqli_query($conn, "SELECT *, CONCAT(student_fname, ', ', student_lname) AS fullname FROM tbl_students WHERE username = '$username'") or die(mysqli_error($conn));
    $count_students = mysqli_num_rows($check_username_students);

    if ($count_sa > 0) {
        $row = mysqli_fetch_array($check_username_sa);
        if ($row['activation_code'] == $activation_code) {
            $pass = true;

        } else {
            $pass = false;

        }
        

    } elseif ($count_admins > 0) {
        $row = mysqli_fetch_array($check_username_admins);
        if ($row['activation_code'] == $activation_code) {
            $pass = true;
            
        } else {
            $pass = false;

        }

    } elseif ($count_faculties_staff > 0) {
        $row = mysqli_fetch_array($check_username_faculties_staff);
        if ($row['activation_code'] == $activation_code) {
            $pass = true;
            
        } else {
            $pass = false;

        }

    } elseif ($count_students > 0) {
        $row = mysqli_fetch_array($check_username_students);
        if ($row['activation_code'] == $activation_code) {
            $pass = true;
            
        } else {
            $pass = false;

        }

    } else {

    }

    if ($pass == true) {
        $_SESSION['activation_success'] = true;
        header("location: ../change.password.php");
         
    } else {
        $_SESSION['activation_error'] = true;
        header("location: ../activation.code.php");

    }



}


?>