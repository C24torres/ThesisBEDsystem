<?php
include '../../../includes/session.php';
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

    $updated_by = $_SESSION['name'] . ' - ' . $_SESSION['role'];
    $date = date('Y-m-d h:i:s');


    $prelim_array = array();

    if (isset($_POST['prelim'])) {
        $temp_array = $_POST['prelim'];

        foreach ($temp_array as $index) {
            if ($index != null) {
                array_push($prelim_array, $index);
            } else {
                array_push($prelim_array, 0);
            }
        }
    }

    $midterm_array = array();

    if (isset($_POST['midterm'])) {
        $temp_array = $_POST['midterm'];

        foreach ($temp_array as $index) {
            if ($index != null) {
                array_push($midterm_array, $index);
            } else {
                array_push($midterm_array, 0);
            }
        }
    }

    $finalterm_array = array();

    if (isset($_POST['finalterm'])) {
        $temp_array = $_POST['finalterm'];

        foreach ($temp_array as $index) {
            if ($index != null) {
                array_push($finalterm_array, $index);
            } else {
                array_push($finalterm_array, 0);
            }
        }
    }

    $absences_array = array();

    if (isset($_POST['absences'])) {
        $temp_array = $_POST['absences'];

        foreach ($temp_array as $index) {
            if ($index != null) {
                array_push($absences_array, $index);
            } else {
                array_push($absences_array, 0);
            }
        }
    }

    $special_tut_array = array();

    if (isset($_POST['special_tut'])) {
        $temp_array = $_POST['special_tut'];

        foreach ($temp_array as $index) {
            if ($index != null) {
                array_push($special_tut_array, $index);
            } else {
                array_push($special_tut_array, 0);
            }
        }
    }

    $enrolled_subj_array = array();

    if (isset($_POST['enrolled_subj_id'])) {
        $temp_array = $_POST['enrolled_subj_id'];

        foreach ($temp_array as $index) {
            if ($index != null) {
                array_push($enrolled_subj_array, $index);
            } else {
                array_push($enrolled_subj_array, 0);
            }
        }
    }

    $i = 0;

    foreach ($enrolled_subj_array as $enrolled_subj_id) {
    $ofgrade = 0;

    if (($_SESSION['active_semester'] == "Summer") || ($special_tut_array[$i] == 1)) {

        if (!empty($midterm_array[$i]) && !empty($finalterm_array[$i])) {
            $ofgrade = number_format((float) (($midterm_array[$i] * 0.4) + ($finalterm_array[$i] * 0.6)), 2, '.', '');
            
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

        } elseif (($midterm_array[$i] == 0) || ($finalterm_array[$i] == 0)) {
            $numgrade = "INC";
            $remarks = "INC";

        }

    } else {
        
        if (!empty($prelim_array[$i]) && !empty($midterm_array[$i]) && !empty($finalterm_array[$i])) {
            $ofgrade = number_format((float) (($prelim_array[$i] * 0.3) + ($midterm_array[$i] * 0.3) + ($finalterm_array[$i] * 0.4)), 2, '.', '');
            
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

        } elseif (($prelim_array[$i] == 0) || ($midterm_array[$i] == 0) || ($finalterm_array[$i] == 0)) {
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
        SET prelim = '$prelim_array[$i]',
        midterm = '$midterm_array[$i]',
        finalterm = '$finalterm_array[$i]',
        ofgrade = '$ofgrade',
        numgrade = '$numgrade',
        absences = '$absences_array[$i]',
        remarks = '$remarks',
        updated = '$updated_by',
        last_update = '$date'
        WHERE enrolled_subj_id = '$enrolled_subj_id'");
        
        
    } else {
        $add_grade = mysqli_query($conn, "UPDATE tbl_enrolled_subjects
        SET prelim = '$prelim_array[$i]',
        midterm = '$midterm_array[$i]',
        finalterm = '$finalterm_array[$i]',
        ofgrade = '$ofgrade',
        numgrade = '$numgrade',
        absences = '$absences_array[$i]',
        remarks = '$remarks',
        updated = '$updated_by',
        last_update = '$date',
        inc_status = '$inc_status'
        WHERE enrolled_subj_id = '$enrolled_subj_id'");
        
        
    }
    

    
    
    $i++;
    }

    $_SESSION['update_success'] = true;
    header("location: ../grade.class.php?class_id=" . $class_id . "&section=" . $section . "&acadyear=" . $acadyear . "&semester=" . $semester);

    



}