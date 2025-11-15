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
            header("location: ../pages/login/login.php");
        }

        
    } else {
        $_SESSION['username_incorrect'] = true;
        header("location: ./pages/login/login.php");
    } 

    } 
?>