<?php
include '../../../includes/conn.php';
date_default_timezone_set("Asia/Manila");
ob_start();
session_start();

//check users
if (isset($_POST['submit']) || isset($_SESSION['update_success'])) {

    if (isset($_SESSION['update_success'])) {
        $username = $_SESSION['username'];
        unset($_SESSION['username']);
        $password = $_SESSION['password'];
        unset($_SESSION['password']);

        unset($_SESSION['email']);

    } else {

        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
    }

    $registrar = mysqli_query($conn, "SELECT * FROM tbl_registrars WHERE username = '$username'");
    $numrow_registrar = mysqli_num_rows($registrar);

    $teacher = mysqli_query($conn, "SELECT * FROM tbl_teachers WHERE username = '$username'");
    $numrow_teacher = mysqli_num_rows($teacher);

    $student = mysqli_query($conn, "SELECT * FROM tbl_students WHERE username = '$username'");
    $numrow_student = mysqli_num_rows($student);

    if ($numrow_registrar > 0) {
        $row = mysqli_fetch_array($registrar);
        $hashedpass = password_verify($password, $row['password']);

        if ($hashedpass == true) {
            $_SESSION['role'] = "Registrar";
            $_SESSION['id'] = $row['reg_id'];
            $_SESSION['name'] = $row['reg_lname'] . ", " . $row['reg_fname'];

            header("location: ../../dashboard/index.php");

        } else {
            $_SESSION['password_incorrect'] = true;
            header("location: ../login.php");
        }

    } elseif ($numrow_teacher > 0) {
        $row = mysqli_fetch_array($faculty);
        $hashedpass = password_verify($password, $row['password']);

        if ($hashedpass == true) {
            $_SESSION['role'] = "Adviser";
            $_SESSION['id'] = $row['teacher_id'];
            $_SESSION['name'] = $row['teacher_lname'] . ", " . $row['teacher_name'];

            header("location: ../../dashboard/index.php");

        } else {
            $_SESSION['password_incorrect'] = true;
            header("location: ../login.php");
        }

    } elseif ($numrow_student > 0) {
        $row = mysqli_fetch_array($student);
        $hashedpass = password_verify($password, $row['password']);

        if ($hashedpass == true) {
            $_SESSION['role'] = "Student";
            $_SESSION['id'] = $row['student_id'];
            $_SESSION['name'] = $row['student_lname'] . ", " . $row['student_fname'];

            $select_settings = mysqli_query($conn, "SELECT * FROM tbl_eval_settings");
            $row = mysqli_fetch_array($select_settings);
            $start_date = date("Y-m-d", strtotime($row['day_start']));
            $start_end = date("Y-m-d", strtotime($row['day_end']));
            
            $date_now = date("Y-m-d", time());
            
            if ($date_now >= $start_date && $date_now <= $start_end) {
               header("location: ../../evaluation/userData/ctrl.check.evaluation.php");
            
            } else {
                header("location: ../../dashboard/index.php");
            }
        } else {
            $_SESSION['password_incorrect'] = true;
            header("location: ../login.php");
        }

    }  else {
        $_SESSION['username_incorrect'] = true;
        header("location: ../login.php");
    }
}
?>