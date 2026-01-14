<?php
include '../../../includes/session.php';
date_default_timezone_set('Asia/Manila');

$schedule_id = $_GET['schedule_id'];
$section = $_GET['section'];

if (isset($_GET['semester']) && isset($_GET['acadyear'])) {
    $acadyear = $_GET['acadyear'];
    $semester = $_GET['semester'];
} else {
    $acadyear = $_SESSION['active_acadyears'];
    $semester = $_SESSION['active_semester'];
}

if (isset($_POST['submit'])) {

    $enrolled_sub_id = mysqli_real_escape_string($conn, $_POST['enrolled_sub_id']);
    $special_tut = mysqli_real_escape_string($conn, $_POST['special_tut']);
    $absences = mysqli_real_escape_string($conn, $_POST['absences']);
    $updated_by = $_SESSION['name'] . ' - ' . $_SESSION['role'];
    $date = date('Y-m-d H:i:s');

    // Initialize grades
    $midterm = isset($_POST['midterm']) ? floatval($_POST['midterm']) : 0;
    $finalterm = isset($_POST['finalterm']) ? floatval($_POST['finalterm']) : 0;
    $first_quarter = isset($_POST['first_quarter']) ? floatval($_POST['first_quarter']) : 0;
    $second_quarter = isset($_POST['second_quarter']) ? floatval($_POST['second_quarter']) : 0;
    $third_quarter = isset($_POST['third_quarter']) ? floatval($_POST['third_quarter']) : 0;
    $fourth_quarter = isset($_POST['fourth_quarter']) ? floatval($_POST['fourth_quarter']) : 0;

    // Fetch grade level from database
    $grade_query = mysqli_query($conn, "SELECT tbl_schoolyears.grade_level_id 
        FROM tbl_enrolled_subjects
        LEFT JOIN tbl_schoolyears ON tbl_schoolyears.student_id = tbl_enrolled_subjects.student_id
        WHERE enrolled_sub_id = '$enrolled_sub_id' LIMIT 1");
    $grade_row = mysqli_fetch_assoc($grade_query);
    $grade_level_id = $grade_row['grade_level_id'] ?? 0;

    // Determine student type
    $is_k10 = ($grade_level_id >= 1 && $grade_level_id <= 13);
    $is_shs = ($grade_level_id >= 14 && $grade_level_id <= 15);

    // Check if any grades are entered
    $has_grades = ($midterm > 0 || $finalterm > 0 || $first_quarter > 0 || $second_quarter > 0 || $third_quarter > 0 || $fourth_quarter > 0);

    if (!$has_grades) {
        // No grades entered, set INC and skip calculation
        $ofgrade = 0;
        $numgrade = "INC";
        $remarks = "INC";
        $inc_status = "Yes";
    } else {
        // Compute OF grade
        if ($is_shs) {
            if ($_SESSION['active_semester'] == "Summer" || $special_tut == 1) {
                $ofgrade = ($midterm * 0.4) + ($finalterm * 0.6);
            } else {
                $ofgrade = ($midterm + $finalterm) / 2;
            }
        } elseif ($is_k10) {
            $ofgrade = ($first_quarter + $second_quarter + $third_quarter + $fourth_quarter) / 4;
        } else {
            $ofgrade = 0;
        }
        $ofgrade = number_format($ofgrade, 2, '.', '');

        // Determine numerical grade and remarks
        if ($ofgrade == 0) {
            $numgrade = "INC";
            $remarks = "INC";
        } elseif ($ofgrade <= 74.49) {
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
            $numgrade = "INC";
            $remarks = "INC";
        }

        $inc_status = ($remarks === "INC") ? "Yes" : "No";
    }

    // Update database
    $query = "UPDATE tbl_enrolled_subjects
        SET midterm='$midterm',
            finalterm='$finalterm',
            first_quarter='$first_quarter',
            second_quarter='$second_quarter',
            third_quarter='$third_quarter',
            fourth_quarter='$fourth_quarter',
            ofgrade='$ofgrade',
            numgrade='$numgrade',
            absences='$absences',
            remarks='$remarks',
            updated='$updated_by',
            last_update='$date',
            inc_status='$inc_status'
        WHERE enrolled_sub_id='$enrolled_sub_id'";

    mysqli_query($conn, $query);

    $_SESSION['update_success'] = true;
    header("location: ../class.php?schedule_id=$schedule_id&section=$section&acadyear=$acadyear&semester=$semester");
    exit();
}
?>
