<?php
include '../../../includes/session.php';

date_default_timezone_set('Asia/Manila');

$class_id = $_GET['class_id'];
$section = $_GET['section'];

if (isset($_GET['semester']) && isset($_GET['acadyear'])) {
  $acadyear = $_GET['acadyear'];
  $semester = $_GET['semester'];
} else {
  $acadyear = $_SESSION['active_acadyear'];
  $semester = $_SESSION['active_semester'];
}

if (isset($_POST['submit'])) {

    $enrolled_subj_id = mysqli_real_escape_string($conn, $_POST['enrolled_subj_id']);
    $special_tut = mysqli_real_escape_string($conn, $_POST['special_tut']);
    $prelim = mysqli_real_escape_string($conn, $_POST['prelim']);
    $midterm = mysqli_real_escape_string($conn, $_POST['midterm']);
    $finalterm = mysqli_real_escape_string($conn, $_POST['finalterm']);
    $absences = mysqli_real_escape_string($conn, $_POST['absences']);
    $updated_by = $_SESSION['name'] . ' - ' . $_SESSION['role'];
    $date = date('Y-m-d h:i:s');



    if (($_SESSION['active_semester'] == "Summer") || ($special_tut == 1)) {

        if (!empty($_POST['midterm']) && !empty($_POST['finalterm'])) {
            $ofgrade = number_format((float) (($midterm * 0.4) + ($finalterm * 0.6)), 2, '.', '');

            if ($ofgrade <= 74.49) {
                $numgrade = "5.00";
                $remarks = "Failed";
        
            } elseif ($ofgrade <= 79.49) {
                $numgrade = "3.00";
                $remarks = "Passed";
        
            } elseif ($ofgrade <= 82.49) {
                $numgrade = "2.75";
                $remarks = "Passed";
        
            } elseif ($ofgrade <= 84.49) {
                $numgrade = "2.50";
                $remarks = "Passed";
        
            } elseif ($ofgrade <= 87.49) {
                $numgrade = "2.25";
                $remarks = "Passed";
        
            } elseif ($ofgrade <= 92.49) {
                $numgrade = "2.00";
                $remarks = "Passed";
        
            } elseif ($ofgrade <= 95.49) {
                $numgrade = "1.75";
                $remarks = "Passed";
        
            } elseif ($ofgrade <= 97.49) {
                $numgrade = "1.50";
                $remarks = "Passed";
        
            } elseif ($ofgrade <= 99.99) {
                $numgrade = "1.25";
                $remarks = "Passed";
        
            } elseif ($ofgrade <= 100) {
                $numgrade = "1.00";
                $remarks = "Passed";
        
            } else {
        
            }

        } elseif (($midterm == 0) || ($finalterm == 0)) {
            $numgrade = "INC";
            $remarks = "INC";

        }

    } else {

        
        if (!empty($_POST['prelim']) && !empty($_POST['midterm']) && !empty($_POST['finalterm'])) {
            $ofgrade = number_format((float) (($prelim * 0.3) + ($midterm * 0.3) + ($finalterm * 0.4)), 2, '.', '');

            if ($ofgrade <= 74.49) {
                $numgrade = "5.00";
                $remarks = "Failed";
        
            } elseif ($ofgrade <= 79.49) {
                $numgrade = "3.00";
                $remarks = "Passed";
        
            } elseif ($ofgrade <= 82.49) {
                $numgrade = "2.75";
                $remarks = "Passed";
        
            } elseif ($ofgrade <= 84.49) {
                $numgrade = "2.50";
                $remarks = "Passed";
        
            } elseif ($ofgrade <= 87.49) {
                $numgrade = "2.25";
                $remarks = "Passed";
        
            } elseif ($ofgrade <= 92.49) {
                $numgrade = "2.00";
                $remarks = "Passed";
        
            } elseif ($ofgrade <= 95.49) {
                $numgrade = "1.75";
                $remarks = "Passed";
        
            } elseif ($ofgrade <= 97.49) {
                $numgrade = "1.50";
                $remarks = "Passed";
        
            } elseif ($ofgrade <= 99.99) {
                $numgrade = "1.25";
                $remarks = "Passed";
        
            } elseif ($ofgrade <= 100) {
                $numgrade = "1.00";
                $remarks = "Passed";
        
            } else {
        
            }

        } elseif (($prelim == 0) || ($midterm == 0) || ($finalterm == 0)) {
            $numgrade = "INC";
            $remarks = "INC";

        }

    }
    
    if ($remarks == 'INC') {
        $inc_status = "Yes";
        
    } else {
        $inc_status = "No";
        
    }
    
    $select_es = mysqli_query($conn, "SELECT inc_status FROM tbl_enrolled_subjects WHERE enrolled_subj_id = '$enrolled_subj_id'");
    $row = mysqli_fetch_array($select_es);
    
    if ($row['inc_status'] == 'Yes') {
        $add_grade = mysqli_query($conn, "UPDATE tbl_enrolled_subjects
        SET prelim = '$prelim',
        midterm = '$midterm',
        finalterm = '$finalterm',
        ofgrade = '$ofgrade',
        numgrade = '$numgrade',
        absences = '$absences',
        remarks = '$remarks',
        updated = '$updated_by',
        last_update = '$date'
        WHERE enrolled_subj_id = '$enrolled_subj_id'");
        
    } else {
        $add_grade = mysqli_query($conn, "UPDATE tbl_enrolled_subjects
        SET prelim = '$prelim',
        midterm = '$midterm',
        finalterm = '$finalterm',
        ofgrade = '$ofgrade',
        numgrade = '$numgrade',
        absences = '$absences',
        remarks = '$remarks',
        updated = '$updated_by',
        last_update = '$date',
        inc_status = '$inc_status'
        WHERE enrolled_subj_id = '$enrolled_subj_id'");
        
    }


    $_SESSION['update_success'] = true;
    header("location: ../class.php?class_id=" . $class_id . "&section=" . $section . "&acadyear=" . $acadyear . "&semester=" . $semester);

}