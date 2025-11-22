<?php
require('../fpdf/fpdf.php');
include '../../includes/session.php';


class PDF extends FPDF
{

    // Page header

}

$pdf = new PDF('L', 'mm', 'Legal');
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

$pdf->Ln(5);
$pdf->Cell(75, 13, 'TEACHER', 1, 0, 'C');
for ($i = 1; $i < 16; $i++) {
    $pdf->Cell(10, 13, $i, 1, 0, 'C');
}
$pdf->Cell(20, 13, 'TOTAL', 1, 0, 'C');
$pdf->Cell(25, 13, 'Evaluators', 1, 0, 'C');
$pdf->Cell(65, 13, 'Course', 1, 1, 'C');

$faculty_info = mysqli_query($conn, "SELECT tbl_faculties_staff.faculty_id, CONCAT(faculty_lastname, ', ', faculty_firstname) AS fullname FROM tbl_faculties_staff
INNER JOIN tbl_schedules ON tbl_faculties_staff.faculty_id = tbl_schedules.faculty_id
INNER JOIN tbl_enrolled_subjects ON tbl_schedules.class_id = tbl_enrolled_subjects.class_id
INNER JOIN tbl_evaluations ON tbl_evaluations.enrolled_subj_id = tbl_enrolled_subjects.enrolled_subj_id
WHERE tbl_evaluations.sem_id = '$_SESSION[active_sem_id]' AND tbl_evaluations.ay_id = '$_SESSION[active_acad_id]'
GROUP BY tbl_faculties_staff.faculty_id");

while ($row1 = mysqli_fetch_array($faculty_info)) {
    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(75, 7, $row1['fullname'], 1, 0, 'L');

    $X = 85;

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

        
        $pdf->SetX($X);
        $i = 0;
        $sum = 0;

        $pdf->SetFont('Arial', '', 9);
        foreach ($q_sum as $index) {
            $sum += $index/$total_evals;
            
            $pdf->Cell(10, 7, number_format(round($index/$total_evals, 2), 2, '.'), 1, 0, 'C'); //
            $i++;
            
        }
        $sum_total += round($sum/$i, 2);
        $pdf->setFillColor(250,250,0); 
        $pdf->Cell(20, 7, number_format(round($sum/$i, 2) , 2, '.'), 1, 0, 'C', 1);  //
        $pdf->Cell(25, 7, $total_evals, 1, 0, 'C'); //
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(65, 7, $row['subj_code'], 1, 1, 'C'); //


        
        $subject_total++;
    }

        $pdf->SetX($X+150);
        $pdf->SetFont('Arial', '', 9);
        $pdf->setFillColor(250,250,0);
        $pdf->Cell(20, 7, number_format(round($sum_total/$subject_total,2), 2, '.'), 1, 0, 'C', 1);  //
        $pdf->Cell(25, 7, $eval_total, 1, 1, 'C'); //

        $pdf->Ln(5);

    
}


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