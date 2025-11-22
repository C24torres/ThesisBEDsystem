<?php
require('../fpdf/fpdf.php');
include '../../includes/session.php';
$faculty_id = $_GET['faculty_id'];

class PDF extends FPDF
{

    // Page header

}

$pdf = new PDF('P', 'mm', 'Legal');
//left top right
$pdf->SetRightMargin(10);
$pdf->SetAutoPageBreak(true, 8);
$pdf->AddPage();

$pdf->SetTextColor(255, 0, 0);
$pdf->SetFont('Times', 'B', 30);
$pdf->Cell(0, 10, 'Saint Francis of Assisi College', 0, 1, 'C');

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 5, '96 Bayanan, City of Bacoor, Cavite', 0, 1, 'C');

$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 5, 'TEACHER\'S EVALUATION BY THE STUDENTS', 0, 1, 'C');
$pdf->Cell(0, 5, $_SESSION['active_semester'] . ', ' . $_SESSION['active_acadyear'], 0, 1, 'C');

$select_faculty = mysqli_query($conn, "SELECT *, CONCAT(faculty_lastname, ', ', faculty_firstname) AS fullname FROM tbl_faculties_staff WHERE faculty_id = '$faculty_id'");
$row = mysqli_fetch_array($select_faculty);
$pdf->Ln(4);
$pdf->Cell(58, 5, 'Name of Professor/Instructor: ', 0, 0, 'L');

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 5, $row['fullname'], 0, 1, 'L');

$select_courses = mysqli_query($conn, "SELECT * FROM tbl_schedules
LEFT JOIN tbl_subjects_new ON tbl_subjects_new.subj_id = tbl_schedules.subj_id
WHERE faculty_id = '$faculty_id' AND acad_year = '$_SESSION[active_acadyear]' AND semester = '$_SESSION[active_semester]'
GROUP BY tbl_subjects_new.subj_code");

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(20, 5, 'Courses: ', 0, 0, 'L');
$subject_codes = "";
$pdf->SetFont('Arial', '', 11);
while ($row = mysqli_fetch_array($select_courses)) {
    $temp = $subject_codes ." ". $row['subj_code'];
    $subject_codes = $temp;
}
$tempFontSize = 11;
    $cellwidth = 200;
    while ($pdf->GetStringWidth($subject_codes) > $cellwidth) {
        $pdf->SetFontSize($tempFontSize -= 0.1);
    }
    $pdf->Cell(200, 5, $subject_codes, 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 11);




$select_eval = mysqli_query($conn, "SELECT * FROM tbl_evaluations
LEFT JOIN tbl_enrolled_subjects ON tbl_evaluations.enrolled_subj_id = tbl_enrolled_subjects.enrolled_subj_id
LEFT JOIN tbl_schedules ON tbl_schedules.class_id = tbl_enrolled_subjects.class_id
LEFT JOIN tbl_faculties_staff ON tbl_faculties_staff.faculty_id = tbl_schedules.faculty_id
WHERE tbl_faculties_staff.faculty_id = '$faculty_id' AND tbl_evaluations.sem_id = '$_SESSION[active_sem_id]' AND tbl_evaluations.ay_id = '$_SESSION[active_acad_id]'");

$pdf->Ln(15);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(20, 5, 'Comments ', 0, 1, 'L');
$no = 1;
while ($row = mysqli_fetch_array($select_eval)) {
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(10, 5, '', 0, 0, 'L');
    $pdf->Cell(0, 5, $no . ". " . $row['comments'], 0, 1, 'L');
    $no++;
}

$select_eval = mysqli_query($conn, "SELECT * FROM tbl_evaluations
LEFT JOIN tbl_enrolled_subjects ON tbl_evaluations.enrolled_subj_id = tbl_enrolled_subjects.enrolled_subj_id
LEFT JOIN tbl_schedules ON tbl_schedules.class_id = tbl_enrolled_subjects.class_id
LEFT JOIN tbl_faculties_staff ON tbl_faculties_staff.faculty_id = tbl_schedules.faculty_id
WHERE tbl_faculties_staff.faculty_id = '$faculty_id' AND tbl_evaluations.sem_id = '$_SESSION[active_sem_id]' AND tbl_evaluations.ay_id = '$_SESSION[active_acad_id]'");


$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(20, 5, 'What aspects of this course were most useful or valuable?', 0, 1, 'L');
$no = 1;
while ($row = mysqli_fetch_array($select_eval)) {
    if (!empty($row['improvement1'])) {
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(10, 5, '', 0, 0, 'L');
        $pdf->Cell(0, 5, $no . ". " . $row['improvement1'], 0, 1, 'L');
        $no++;
    }
}

$select_eval = mysqli_query($conn, "SELECT * FROM tbl_evaluations
LEFT JOIN tbl_enrolled_subjects ON tbl_evaluations.enrolled_subj_id = tbl_enrolled_subjects.enrolled_subj_id
LEFT JOIN tbl_schedules ON tbl_schedules.class_id = tbl_enrolled_subjects.class_id
LEFT JOIN tbl_faculties_staff ON tbl_faculties_staff.faculty_id = tbl_schedules.faculty_id
WHERE tbl_faculties_staff.faculty_id = '$faculty_id' AND tbl_evaluations.sem_id = '$_SESSION[active_sem_id]' AND tbl_evaluations.ay_id = '$_SESSION[active_acad_id]'");


$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(20, 5, 'How would you like to improve this course?', 0, 1, 'L');
$no = 1;
while ($row = mysqli_fetch_array($select_eval)) {
    if (!empty($row['improvement2'])) {
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(10, 5, '', 0, 0, 'L');
        $pdf->Cell(0, 5, $no . ". " . $row['improvement2'], 0, 1, 'L');
        $no++;
    }
}

$select_eval = mysqli_query($conn, "SELECT * FROM tbl_evaluations
LEFT JOIN tbl_enrolled_subjects ON tbl_evaluations.enrolled_subj_id = tbl_enrolled_subjects.enrolled_subj_id
LEFT JOIN tbl_schedules ON tbl_schedules.class_id = tbl_enrolled_subjects.class_id
LEFT JOIN tbl_faculties_staff ON tbl_faculties_staff.faculty_id = tbl_schedules.faculty_id
WHERE tbl_faculties_staff.faculty_id = '$faculty_id' AND tbl_evaluations.sem_id = '$_SESSION[active_sem_id]' AND tbl_evaluations.ay_id = '$_SESSION[active_acad_id]'");

while ($row1 = mysqli_fetch_array($select_eval)) {

    $select_subject = mysqli_query($conn, "SELECT * FROM tbl_schedules
    INNER JOIN tbl_enrolled_subjects ON tbl_enrolled_subjects.class_id = tbl_schedules.class_id
    INNER JOIN tbl_subjects_new ON tbl_subjects_new.subj_id = tbl_schedules.subj_id
    INNER JOIN tbl_evaluations ON tbl_evaluations.enrolled_subj_id = tbl_enrolled_subjects.enrolled_subj_id
    WHERE faculty_id = '$row1[faculty_id]'
    GROUP BY tbl_subjects_new.subj_code");
    //calculations
    $sum_total = 0;
    $subject_total = 0;
    $eval_total = 0;
    while ($row = mysqli_fetch_array($select_subject)) {
        $q_sum = [];
        $total_evals = 0;
        
        $select_eval = mysqli_query($conn, "SELECT * FROM tbl_evaluations
        LEFT JOIN tbl_enrolled_subjects ON tbl_enrolled_subjects.enrolled_subj_id = tbl_evaluations.enrolled_subj_id
        WHERE class_id = '$row[class_id]'");
        
        while ($row3 = mysqli_fetch_array($select_eval)) {
            $student_rating = explode(', ', $row['rating']);
            $student_rating = array_splice($student_rating, 0, 15); //tanggalin
            $i = 0;

            if ($total_evals == 0) {
                foreach ($student_rating as $index) {
                    $q_sum[] = $index;
                } 
            } else {
                foreach ($student_rating as $index) {
                    $temp = $q_sum[$i] + $index;
                    $q_sum[$i] = $temp;
                    $i++;
                } 
            }

            $total_evals++;
            
        }

        $eval_total += $total_evals;

        $i = 0;
        $sum = 0;

        foreach ($q_sum as $index) {
            $sum += $index/$total_evals;
            $i++;
            
        }
        $sum_total += round($sum/$i, 2);
        $subject_total++;
    }

}


$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 5, 'OVERALL RATING: ', 0, 0, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(20, 5, number_format(round($sum_total/$subject_total,2), 2, '.'), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 5, 'Total No. of Evaluators: ', 0, 0, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(20, 5, $eval_total, 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(60, 5, 'Rating (No. of Evaluators): ', 0, 0, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(20, 5, number_format(round($sum_total/$subject_total,2), 2, '.'), 0, 1, 'L');


$pdf->Ln(25);
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(20, 5, 'Prepared By: ', 0, 1, 'L');

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(20, 5, strtoupper($_SESSION['name']), 0, 1, 'L');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(20, 5, 'HR Staff', 0, 1, 'L');


$pdf->Output();
?>