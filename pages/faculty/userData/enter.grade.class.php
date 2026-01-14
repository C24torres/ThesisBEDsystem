<?php
include '../../../includes/session.php';
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

    $updated_by = $_SESSION['name'] . ' - ' . $_SESSION['role'];
    $date = date('Y-m-d h:i:s');

    // Helper function: convert nulls to 0
    function process_array($arr) {
        $result = [];
        if (isset($arr)) {
            foreach ($arr as $val) {
                $result[] = ($val != null) ? $val : 0;
            }
        }
        return $result;
    }

    // Arrays
    $first_quarter_array   = process_array($_POST['first_quarter'] ?? []);
    $second_quarter_array  = process_array($_POST['second_quarter'] ?? []);
    $third_quarter_array   = process_array($_POST['third_quarter'] ?? []);
    $fourth_quarter_array  = process_array($_POST['fourth_quarter'] ?? []);
    $midterm_array         = process_array($_POST['midterm'] ?? []);
    $finalterm_array       = process_array($_POST['finalterm'] ?? []);
    $absences_array        = process_array($_POST['absences'] ?? []);
    $special_tut_array     = process_array($_POST['special_tut'] ?? []);
    $enrolled_subj_array   = process_array($_POST['enrolled_sub_id'] ?? []);
    $grade_level_array     = process_array($_POST['grade_level_id'] ?? []); // 1-13 K-10, 14-15 SHS

    for ($i = 0; $i < count($enrolled_subj_array); $i++) {
        $enrolled_sub_id = $enrolled_subj_array[$i];
        $grade_level_id  = $grade_level_array[$i];
        $ofgrade = 0;

        // ========== GRADING LOGIC ==========
        if (($_SESSION['active_semester'] == "Summer") || ($special_tut_array[$i] == 1)) {
            // Special Tutorial / Summer: midterm 40%, final 60%
            if ($midterm_array[$i] != 0 && $finalterm_array[$i] != 0) {
                $ofgrade = number_format((($midterm_array[$i] * 0.4) + ($finalterm_array[$i] * 0.6)), 2, '.', '');
            }
        } elseif ($grade_level_id >= 1 && $grade_level_id <= 13) {
            // K-10: 4 quarters, each 0.25
            $quarters = [
                $first_quarter_array[$i],
                $second_quarter_array[$i],
                $third_quarter_array[$i],
                $fourth_quarter_array[$i]
            ];
            $sum = 0;
            foreach ($quarters as $q) { $sum += $q; }
            $ofgrade = number_format($sum / 4, 2, '.', '');
        } else {
            // SHS: midterm + final, each 0.5 → divide by 2
            if ($midterm_array[$i] != 0 && $finalterm_array[$i] != 0) {
                $ofgrade = number_format((($midterm_array[$i] + $finalterm_array[$i]) / 2), 2, '.', '');
            }
        }

        // ========== NUMERIC GRADE & REMARKS ==========
        $numgrade = "";
        $remarks = "";

        if ($grade_level_id >= 1 && $grade_level_id <= 13) {
            // K-10 INC: all 4 quarters are 0
            if ($first_quarter_array[$i]==0 && $second_quarter_array[$i]==0 &&
                $third_quarter_array[$i]==0 && $fourth_quarter_array[$i]==0) {
                $numgrade = "INC";
                $remarks = "INC";
            }
        } elseif ($grade_level_id >= 14 && $grade_level_id <= 15) {
            // SHS INC: both midterm and final are 0
            if ($midterm_array[$i]==0 && $finalterm_array[$i]==0) {
                $numgrade = "INC";
                $remarks = "INC";
            }
        }

        // If not INC, calculate numeric grade
        if ($numgrade != "INC") {
            if ($ofgrade <= 74.49) { $numgrade="5.00"; $remarks="Failed"; }
            elseif ($ofgrade <= 79.49) { $numgrade="3.00"; $remarks="Passed"; }
            elseif ($ofgrade <= 82.49) { $numgrade="2.75"; $remarks="Passed"; }
            elseif ($ofgrade <= 84.49) { $numgrade="2.50"; $remarks="Passed"; }
            elseif ($ofgrade <= 87.49) { $numgrade="2.25"; $remarks="Passed"; }
            elseif ($ofgrade <= 92.49) { $numgrade="2.00"; $remarks="Passed"; }
            elseif ($ofgrade <= 95.49) { $numgrade="1.75"; $remarks="Passed"; }
            elseif ($ofgrade <= 97.49) { $numgrade="1.50"; $remarks="Passed"; }
            elseif ($ofgrade <= 99.99) { $numgrade="1.25"; $remarks="Passed"; }
            elseif ($ofgrade <= 100) { $numgrade="1.00"; $remarks="Passed"; }
        }

        $inc_status = ($remarks == "INC") ? "Yes" : "No";

        // ========== DATABASE UPDATE ==========
        $select_es = mysqli_query($conn, "SELECT inc_status FROM tbl_enrolled_subjects WHERE enrolled_sub_id='$enrolled_sub_id'");
        $row = mysqli_fetch_array($select_es);

        $update_query = "UPDATE tbl_enrolled_subjects
            SET midterm='{$midterm_array[$i]}',
                finalterm='{$finalterm_array[$i]}',
                first_quarter='{$first_quarter_array[$i]}',
                second_quarter='{$second_quarter_array[$i]}',
                third_quarter='{$third_quarter_array[$i]}',
                fourth_quarter='{$fourth_quarter_array[$i]}',
                ofgrade='$ofgrade',
                numgrade='$numgrade',
                absences='{$absences_array[$i]}',
                remarks='$remarks',
                updated='$updated_by',
                last_update='$date'";

        if ($row['inc_status'] != 'Yes') {
            $update_query .= ", inc_status='$inc_status'";
        }

        $update_query .= " WHERE enrolled_sub_id='$enrolled_sub_id'";

        mysqli_query($conn, $update_query);
    }

    $_SESSION['update_success'] = true;
    header("location: ../grade.class.php?schedule_id=$schedule_id&section=$section&acadyear=$acadyear&semester=$semester");
}
?>
