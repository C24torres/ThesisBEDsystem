<?php
require '../../../includes/conn.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

if (isset($_POST['submit']) && !empty($_POST['email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $check_email_sa = mysqli_query($conn, "SELECT *, name AS fullname FROM tbl_master_key WHERE email = '$email'") or die(mysqli_error($conn));
    $count_sa = mysqli_num_rows($check_email_sa);

    $check_email_admins = mysqli_query($conn, "SELECT *, CONCAT(reg_fname, ', ', reg_lname) AS fullname FROM tbl_registrars WHERE email = '$email'") or die(mysqli_error($conn));
    $count_admins = mysqli_num_rows($check_email_admins);

    $check_email_faculties_staff = mysqli_query($conn, "SELECT *, CONCAT(teacher_fname, ', ', teacher_lname) AS fullname FROM tbl_teachers WHERE email = '$email'") or die(mysqli_error($conn));
    $count_faculties_staff = mysqli_num_rows($check_email_faculties_staff);

    $check_email_students = mysqli_query($conn, "SELECT *, CONCAT(student_fname, ', ', student_lname) AS fullname FROM tbl_students WHERE email = '$email'") or die(mysqli_error($conn));
    $count_students = mysqli_num_rows($check_email_students);

    if ($count_sa > 0 || $count_admins > 0 || $count_faculties_staff > 0 || $count_students > 0) {

        $activation_code = rand(100000, 999999);
        
        if ($count_sa > 0) {
            $row = mysqli_fetch_array($check_email_sa);
            $add_code = mysqli_query($conn, "UPDATE tbl_master_key SET activation_code = '$activation_code' WHERE mk_id = '$row[mk_id]'");

        } elseif ($count_admins > 0) {
            $row = mysqli_fetch_array($check_email_admins);
            $add_code = mysqli_query($conn, "UPDATE tbl_registrars SET activation_code = '$activation_code' WHERE reg_id = '$row[reg_id]'");

        } elseif ($count_faculties_staff > 0) {
            $row = mysqli_fetch_array($check_email_faculties_staff);
            $add_code = mysqli_query($conn, "UPDATE tbl_teachers SET activation_code = '$activation_code' WHERE teacher_id = '$row[teacher_id]'");

        } elseif ($count_students > 0) {
            $row = mysqli_fetch_array($check_email_students);
            $add_code = mysqli_query($conn, "UPDATE tbl_students SET activation_code = '$activation_code' WHERE student_id = '$row[student_id]'");

        }

        $message = '
        Hello ' . $row['fullname'] . '<br><br>
        Verification Code: <strong><h2>' . $activation_code . '</h2></strong>
        This 6-digit is to reset the Password of your account. Please copy and enter it in the request code. <br><br>
        SFAC-Bacoor Representatives will never ask for this code. For your protection, please do not share this code with anyone.<br><br>
        Thanks,<br>
        Saint Francis of Assisi College - Bacoor<br>
        All rights reserved.';

        $email_final = $email;
        $mail = new PHPMailer(true);
        $mail->IsSMTP();
        $mail->SMTPDebug = 0; 
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->Username = "stfrancisbacoor.pass.reset@gmail.com"; //Enter your username/emailid
        $mail->Password = "islqiaavsjlrgoyf"; //Enter your password
        $mail->setFrom('sfacbacoor1981@gmail.com', 'SFAC-Bacoor');
        $mail->AddAddress($email_final);
        $mail->Subject = "Reset Password | Verification Code";
        $mail->isHTML(true);
        $mail->Body = $message;
        if ($mail->send()) {
            $_SESSION['email_success'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            header("location: ../activation.code.php");
        } else {
            $_SESSION['email_error'] = true;
            header("location: ../forgot.password.php");
        }

    } else {
        $_SESSION['email_error'] = true;
        header("location: ../forgot.password.php");
    }
}

?>