<?php
require('../fpdf/fpdf.php');
include '../../includes/session.php';

date_default_timezone_set('Asia/Manila');

if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];
} else {
    $student_id = $_SESSION['id'];
}

if (isset($_GET['semester']) && isset($_GET['acadyear'])) {
    $acadyear = $_GET['acadyear'];
    $semester = $_GET['semester'];
} else {
    $acadyear = $_SESSION['active_acadyear'];
    $semester = $_SESSION['active_semester'];
}

$query = mysqli_query($conn, "SELECT *,CONCAT(tbl_students.student_lname, ' ', tbl_students.student_fname, ' ', tbl_students.student_mname)  as fullname FROM tbl_schoolyears
    LEFT JOIN tbl_students ON tbl_students.student_id = tbl_schoolyears.student_id
    LEFT JOIN tbl_strands ON tbl_strands.strand_id = tbl_schoolyears.strand_id
    LEFT JOIN tbl_grade_levels ON tbl_grade_levels.grade_level_id = tbl_schoolyears.grade_level_id
    where tbl_schoolyears.student_id = '$student_id' AND tbl_schoolyears.ay_id = '$acadyear' AND tbl_schoolyears.semester_id = '$semester'");
$info = mysqli_fetch_array($query);


class PDF extends FPDF
{

// Page header

}

$pdf = new PDF('P', 'mm', 'Legal');
//left right top
$pdf->SetAutoPageBreak(true, 8);
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(0, 5, 'SAINT FRANCIS OF ASSISI COLLEGE', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, '96 Bayanan, City of Bacoor, Cavite', 0, 1, 'C');
$pdf->Cell(0, 5, '', 0, 1);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 5, 'OFFICE OF THE REGISTRAR', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 5, 'APPLICATION FORM FOR ACADEMIC SCHOLARSHIP', 0, 1, 'C');

$pdf->Cell(0, 5, '', 0, 1); //SPACE

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25, 5, 'Directions:', 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(74, 5, 'Accomplish this form in two (2) copies. Submit both copies to the Registrar\'s Office.', 0, 1);

$pdf->Cell(25, 5, '', 0, 0, 'L');
$pdf->Cell(74, 5, 'Return for your personal file copy upon announcement from the Registrar\'s Office.', 0, 1);

$pdf->Cell(150, 10, '', 0, 0);
$pdf->Cell(45, 10, '', 'B', 1);
$pdf->Cell(150, 5, '', 0, 0);
$pdf->Cell(45, 5, 'Date', 0, 1, 'C');

$pdf->SetY(70);
$pdf->Cell(20, 5, 'Ma\'am/Sir,', 0, 1);

$pdf->Cell(20, 5, '', 0, 0);
$pdf->Cell(175, 5, 'I have the honor to apply as Academic Scholar for President\'s for '. $semester . ', Academic Year ' . $acadyear . '.', 0, 1);
$pdf->Cell(195, 5, 'Hereunder are the subjects taken during the said semester with the corresponding number of units and final grades and the', 0, 1);
$pdf->Cell(195, 5, 'computed General Weigthed Average (GWA).', 0, 1);

$pdf->SetY(95);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(105, 10, 'SUBJECTS', 0, 0, 'C');
$pdf->Cell(15, 10, '', 0, 0, 'C');
$pdf->Cell(30, 10, 'UNITS', 0, 0, 'C');
$pdf->Cell(15, 10, '', 0, 0, 'C');
$pdf->Cell(30, 10, 'FINAL GRADE', 0, 1, 'C');

$subjects = mysqli_query($conn, "SELECT * FROM tbl_enrolled_subjects
LEFT JOIN tbl_subjects_senior ON tbl_subjects_senior.subject_id = tbl_enrolled_subjects.subject_id
where tbl_enrolled_subjects.acad_year = '$acadyear'
AND tbl_enrolled_subjects.semester = '$semester'
AND student_id = '$student_id'") or die($conn);

$sum = 0;

while ($row = mysqli_fetch_array($subjects)) {

    if (is_numeric($row['total_units'])) {

        $pdf->SetFont('Arial', '', 10);

        $subject_info = $row['subject_code'] . ' - ' . $row['subject_description'];
        $tempFontSize = 10;
        $cellwidth = 105;
        while ($pdf->GetStringWidth($subject_info) > $cellwidth) {
            $pdf->SetFontSize($tempFontSize -= 0.1);
        }

        $pdf->Cell(105, 7, $subject_info, 'B', 0); //subject
        $pdf->Cell(15, 7, '', 0, 0, 'C');
        $pdf->Cell(30, 7, $row['total_units'], 'B', 0, 'C'); //units
        $pdf->Cell(15, 7, '', 0, 0, 'C');

        if ($row['numgrade'] != 0 && !empty($row['numgrade'])) {
            $pdf->Cell(30, 7, $row['numgrade'], 'B', 1, 'C'); //grade

        } else {
            $pdf->Cell(30, 7, 'INC', 'B', 1, 'C'); //grade
            $inc = true;
        }

        $x = $row['total_units'] * $row['numgrade'];
        $sum += $x; 

    }

}

$credited = mysqli_query($conn, "SELECT SUM(total_units) as UN FROM tbl_enrolled_subjects LEFT JOIN tbl_subjects_senior ON tbl_subjects_senior.subject_id = tbl_enrolled_subjects.subject_id where remarks = 'Passed' and tbl_enrolled_subjects.student_id = '$student_id' AND tbl_enrolled_subjects.acad_year = '$acadyear' AND tbl_enrolled_subjects.semester = '$semester'");
$rowew = mysqli_fetch_array($credited);

if (isset($inc)) {
    $gwa = "INC";
} else {
    $gwa = $sum / $rowew['UN'];
    $gwa = number_format($gwa, 3, '.', '');
}





$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(105, 7, 'Total No. of Units:', 0, 0, 'R');
$pdf->Cell(15, 7, '', 0, 0, 'C');
$pdf->Cell(30, 7, $rowew['UN'], 'B', 1, 'C');

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(105, 7, 'GENERAL WEIGHTED AVERAGE (GWA):', 0, 0, 'R');
$pdf->Cell(15, 7, '', 0, 0, 'C');
$pdf->Cell(30, 7, $gwa, 'B', 1, 'C');

$pdf->Cell(0, 5, '', 0, 1); //SPACE
$pdf->Cell(0, 5, '', 0, 1); //SPACE
$pdf->Cell(0, 5, '', 0, 1); //SPACE

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 7, 'PROCEDURE FOR COMPUTATION:', 0, 1);

$pdf->Cell(0, 5, '', 0, 1); //SPACE

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 15, 'GWA    = ', 0, 0);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(175, 7.5, '(Grade in 1st Subject x Unit of 1st Subject) + (Grade in 2nd Subject x Unit of 2nd Subject) + ...', 'B', 1, 'C');
$pdf->Cell(20, 7.5, '', 0, 0);
$pdf->Cell(175, 7.5, 'Total Number of Units', 'T', 1, 'C');


$pdf->SetY(250);

$pdf->Cell(105, 5, '', 0, 0, 'R');
$pdf->Cell(55, 5, 'Respectfully Yours,', 0, 1);

$pdf->Cell(0, 5, '', 0, 1); //SPACE
$pdf->Cell(0, 5, '', 0, 1); //SPACE

$pdf->Cell(105, 5, '', 0, 0);
$pdf->SetFont('Arial', 'B', 10);
$tempFontSize = 10;
$cellwidth = 53;
while ($pdf->GetStringWidth($info['fullname']) > $cellwidth) {
    $pdf->SetFontSize($tempFontSize -= 0.1);
}
$pdf->Cell(55, 5, $info['fullname'], 'B', 0, 'C');
$pdf->Cell(5, 5, '', 0, 0);
$pdf->Cell(30, 5, $info['strand_name']. ' - ' .$info['grade_level'], 'B', 1, 'C');

$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(105, 5, '', 0, 0);
$pdf->Cell(55, 5, 'Signature Over Student\'s Printed Name', 0, 0, 'C');
$pdf->Cell(5, 5, '', 0, 0);
$pdf->Cell(30, 5, 'Program and Yr. Level.', 0, 1);

$pdf->Cell(0, 5, '', 0, 1); //SPACE
$pdf->Cell(0, 5, '', 0, 1); //SPACE
$pdf->Cell(0, 5, '', 0, 1); //SPACE
$pdf->Cell(0, 5, '', 0, 1); //SPACE

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(55, 5, 'Endorsed By:', 0, 0);
$pdf->Cell(10, 5, '', 0, 0);
$pdf->Cell(55, 5, 'Checked By:', 0, 0);
$pdf->Cell(10, 5, '', 0, 0);
$pdf->Cell(55, 5, 'Verified By:', 0, 0);

$pdf->Cell(0, 5, '', 0, 1); //SPACE
$pdf->Cell(0, 5, '', 0, 1); //SPACE
$pdf->Cell(0, 5, '', 0, 1); //SPACE

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(55, 5, '', 'B', 0);
$pdf->Cell(10, 5, '', 0, 0);
$pdf->Cell(55, 5, 'REBECCA D. VICENTE, LPT', 0, 0);
$pdf->Cell(10, 5, '', 0, 0);
$pdf->Cell(55, 5, 'ARTURO O. OROSCO, JR., Ph. D.', 0, 1);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(55, 5, 'Program Director', 0, 0);
$pdf->Cell(10, 5, '', 0, 0);
$pdf->Cell(55, 5, 'Chief Registrar', 0, 0);
$pdf->Cell(10, 5, '', 0, 0);
$pdf->Cell(55, 5, 'President', 0, 1);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(10, 5, 'Date: ', 0, 0);
$pdf->Cell(45, 5, '', 'B', 0);
$pdf->Cell(10, 5, '', 0, 0);
$pdf->Cell(10, 5, 'Date: ', 0, 0);
$pdf->Cell(45, 5, '', 'B', 0);
$pdf->Cell(10, 5, '', 0, 0);
$pdf->Cell(10, 5, 'Date: ', 0, 0);
$pdf->Cell(45, 5, '', 'B', 1);

$pdf->Cell(0, 5, '', 0, 1); //SPACE
$pdf->Cell(0, 5, '', 0, 1); //SPACE
$pdf->Cell(0, 5, '', 0, 1); //SPACE

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 7.5, 'Note: Please submit this form with photocopy of course cards on or before _______________ .', 1, 1, 'C');



$pdf->Output();
?>