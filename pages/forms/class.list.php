<?php
require('../fpdf/fpdf.php');
include '../../includes/session.php';

$subject_code = $_GET['subject_code'];
$section = $_GET['section'];
$schedule_id = $_GET['schedule_id'];



class PDF extends FPDF
{
    // Page header
    function Header()
    {
        include '../../includes/conn.php';
        $subject_code = $_GET['subject_code'];
        $schedule_id = $_GET['schedule_id'];
        $section = $_GET['section'];
        
        if (isset($_GET['semester']) && isset($_GET['acadyear'])) {
            $acadyear = $_GET['acadyear'];
            $semester = $_GET['semester'];
        } else {
            $acadyear = $_SESSION['active_acadyear'];
            $semester = $_SESSION['active_semester'];
        }
        // Logo(x axis, y axis, height, width)
        $this->Image('../../docs/assets/img/logo.png', 27, 3, 19, 19);
        // font(font type,style,font size)
        $this->SetFont('Times', 'B', 28);
        $this->SetTextColor(255, 0, 0);
        // Dummy cell
        $this->Cell(50);
        // //cell(width,height,text,border,end line,[align])
        $this->Cell(110, 5, 'Saint Francis of Assisi College', 0, 0, 'C');
        // Line break
        $this->Ln(9);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', '', 10);
        // dummy cell

        // //cell(width,height,text,border,end line,[align])
        $this->Cell(0, 3, '96 Bayanan, City of Bacoor, Cavite', 0, 0, 'C');
        // Line break
        $this->Ln(4);
        $this->SetFont('Arial', 'B', 12);
        // //cell(width,height,text,border,end line,[align])
        $this->Cell(0, 4, 'COLLEGE DEPARTMENT', 0, 0, 'C');
        // Line break
        $this->Ln(8);
        $this->SetFont('Arial', 'B', 14);
        // //cell(width,height,text,border,end line,[align])
        $this->Cell(0, 6, 'CLASS LIST', 0, 1, 'C');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 4, $semester . ' ' . $acadyear, 0, 1, 'C');

        $this->Ln(3);
        $class_info = mysqli_query($conn, "SELECT *
                FROM tbl_enrolled_subjects 
                LEFT JOIN tbl_subjects_senior ON tbl_subjects_senior.subject_id = tbl_enrolled_subjects.subject_id
                LEFT JOIN tbl_schoolyears ON tbl_schoolyears.student_id = tbl_enrolled_subjects.student_id
                LEFT JOIN tbl_schedules ON tbl_schedules.schedule_id = tbl_enrolled_subjects.schedule_id
                WHERE tbl_schedules.schedule_id = '$schedule_id'
                AND tbl_schedules.section = '$section' 
                AND tbl_schoolyears.ay_id = '$acadyear'
                AND tbl_schoolyears.semester_id = '$semester'
                AND tbl_schoolyears.remark = 'Approved'");
        
        $class_size = mysqli_num_rows($class_info);
        $row = mysqli_fetch_array($class_info);

        $this->SetFont('Arial', '', '11');
        $this->Cell(25, 5, 'Course Code:', 0, 0);

        $fontsize3 = 11;
        $tempFontSize3 = $fontsize3;
        $cellwidth3 = 23;
        while ($this->GetStringWidth($subject_code) > $cellwidth3) {
            $this->SetFontSize($tempFontSize3 -= 0.1);
        }
        $this->Cell(23, 5, $subject_code, 'B', 0, 'C');
        $this->SetFont('Arial', '', '11');
        $this->Cell(23, 5, 'Course Title:', 0, 0);
        $fontsize = 11;
        $tempFontSize = $fontsize;
        $cellwidth = 77;
        while ($this->GetStringWidth($row['subject_description']) > $cellwidth) {
            $this->SetFontSize($tempFontSize -= 0.1);
        }
        $this->Cell(77, 5, $row['subject_description'], 'B', 0, 'C');
        $this->SetFont('Arial', '', '11');
        $this->Cell(25, 5, 'No. of Units:', 0, 0);
        $this->Cell(22, 5, $row['unit_total'], 'B', 1, 'C');

        $this->Cell(51, 5, 'Schedule (Time/Day/Room):', 0, 0);
        $this->Cell(102, 5, $row['time'] . ' / ' . $row['day'] . ' / ' . $row['room'], 'B', 0, 'C');
        $this->Cell(20, 5, 'Class Size:', 0, 0);
        $this->Cell(22, 5, $class_size, 'B', 1, 'C');


        $this->SetXY(10, 81.7);
        $this->SetFont('Arial', 'B', '14');
        $this->Cell(9, 10, '', 1, 0, 'C');
        $this->Cell(100, 10, 'Name', 1, 0, 'C');
        $this->Cell(60, 10, 'Program', 1, 0, 'C');
        $this->Cell(0, 10, 'Year Level', 1, 1, 'C');

        

        $this->Rect(10, 96.7, 8.87, 5);
        $this->Rect(18.87, 96.7, 100, 5);
        $this->Rect(118.87, 96.7, 60, 5);
        $this->Rect(178.87, 96.7, 27, 5);



        $this->Rect(10, 101.7, 8.87, 5);
        $this->Rect(18.87, 101.7, 100, 5);
        $this->Rect(118.87, 101.7, 60, 5);
        $this->Rect(178.87, 101.7, 27, 5);




        $this->Rect(10, 106.7, 8.87, 5);
        $this->Rect(18.87, 106.7, 100, 5);
        $this->Rect(118.87, 106.7, 60, 5);
        $this->Rect(178.87, 106.7, 27, 5);




        $this->Rect(10, 111.7, 8.87, 5);
        $this->Rect(18.87, 111.7, 100, 5);
        $this->Rect(118.87, 111.7, 60, 5);
        $this->Rect(178.87, 111.7, 27, 5);




        $this->Rect(10, 116.7, 8.87, 5);
        $this->Rect(18.87, 116.7, 100, 5);
        $this->Rect(118.87, 116.7, 60, 5);
        $this->Rect(178.87, 116.7, 27, 5);




        $this->Rect(10, 121.7, 8.87, 5);
        $this->Rect(18.87, 121.7, 100, 5);
        $this->Rect(118.87, 121.7, 60, 5);
        $this->Rect(178.87, 121.7, 27, 5);



        //77777777777777777777777777777777777777777777777777777777777777777777777777777777777777777777777777777777777
        $this->Rect(10, 126.7, 8.87, 5);
        $this->Rect(18.87, 126.7, 100, 5);
        $this->Rect(118.87, 126.7, 60, 5);
        $this->Rect(178.87, 126.7, 27, 5);




        $this->Rect(10, 131.7, 8.87, 5);
        $this->Rect(18.87, 131.7, 100, 5);
        $this->Rect(118.87, 131.7, 60, 5);
        $this->Rect(178.87, 131.7, 27, 5);




        $this->Rect(10, 136.7, 8.87, 5);
        $this->Rect(18.87, 136.7, 100, 5);
        $this->Rect(118.87, 136.7, 60, 5);
        $this->Rect(178.87, 136.7, 27, 5);




        $this->Rect(10, 141.7, 8.87, 5);
        $this->Rect(18.87, 141.7, 100, 5);
        $this->Rect(118.87, 141.7, 60, 5);
        $this->Rect(178.87, 141.7, 27, 5);




        $this->Rect(10, 146.7, 8.87, 5);
        $this->Rect(18.87, 146.7, 100, 5);
        $this->Rect(118.87, 146.7, 60, 5);
        $this->Rect(178.87, 146.7, 27, 5);




        $this->Rect(10, 151.7, 8.87, 5);
        $this->Rect(18.87, 151.7, 100, 5);
        $this->Rect(118.87, 151.7, 60, 5);
        $this->Rect(178.87, 151.7, 27, 5);




        $this->Rect(10, 156.7, 8.87, 5);
        $this->Rect(18.87, 156.7, 100, 5);
        $this->Rect(118.87, 156.7, 60, 5);
        $this->Rect(178.87, 156.7, 27, 5);




        $this->Rect(10, 161.7, 8.87, 5);
        $this->Rect(18.87, 161.7, 100, 5);
        $this->Rect(118.87, 161.7, 60, 5);
        $this->Rect(178.87, 161.7, 27, 5);




        $this->Rect(10, 166.7, 8.87, 5);
        $this->Rect(18.87, 166.7, 100, 5);
        $this->Rect(118.87, 166.7, 60, 5);
        $this->Rect(178.87, 166.7, 27, 5);




        $this->Rect(10, 171.7, 8.87, 5);
        $this->Rect(18.87, 171.7, 100, 5);
        $this->Rect(118.87, 171.7, 60, 5);
        $this->Rect(178.87, 171.7, 27, 5);




        $this->Rect(10, 176.7, 8.87, 5);
        $this->Rect(18.87, 176.7, 100, 5);
        $this->Rect(118.87, 176.7, 60, 5);
        $this->Rect(178.87, 176.7, 27, 5);



        $this->Rect(10, 181.7, 8.87, 5);
        $this->Rect(18.87, 181.7, 100, 5);
        $this->Rect(118.87, 181.7, 60, 5);
        $this->Rect(178.87, 181.7, 27, 5);




        $this->Rect(10, 186.7, 8.87, 5);
        $this->Rect(18.87, 186.7, 100, 5);
        $this->Rect(118.87, 186.7, 60, 5);
        $this->Rect(178.87, 186.7, 27, 5);




        $this->Rect(10, 191.7, 8.87, 5);
        $this->Rect(18.87, 191.7, 100, 5);
        $this->Rect(118.87, 191.7, 60, 5);
        $this->Rect(178.87, 191.7, 27, 5);




        $this->Rect(10, 196.7, 8.87, 5);
        $this->Rect(18.87, 196.7, 100, 5);
        $this->Rect(118.87, 196.7, 60, 5);
        $this->Rect(178.87, 196.7, 27, 5);




        $this->Rect(10, 201.7, 8.87, 5);
        $this->Rect(18.87, 201.7, 100, 5);
        $this->Rect(118.87, 201.7, 60, 5);
        $this->Rect(178.87, 201.7, 27, 5);




        $this->Rect(10, 206.7, 8.87, 5);
        $this->Rect(18.87, 206.7, 100, 5);
        $this->Rect(118.87, 206.7, 60, 5);
        $this->Rect(178.87, 206.7, 27, 5);




        $this->Rect(10, 211.7, 8.87, 5);
        $this->Rect(18.87, 211.7, 100, 5);
        $this->Rect(118.87, 211.7, 60, 5);
        $this->Rect(178.87, 211.7, 27, 5);




        $this->Rect(10, 216.7, 8.87, 5);
        $this->Rect(18.87, 216.7, 100, 5);
        $this->Rect(118.87, 216.7, 60, 5);
        $this->Rect(178.87, 216.7, 27, 5);




        $this->Rect(10, 221.7, 8.87, 5);
        $this->Rect(18.87, 221.7, 100, 5);
        $this->Rect(118.87, 221.7, 60, 5);
        $this->Rect(178.87, 221.7, 27, 5);




        $this->Rect(10, 226.7, 8.87, 5);
        $this->Rect(18.87, 226.7, 100, 5);
        $this->Rect(118.87, 226.7, 60, 5);
        $this->Rect(178.87, 226.7, 27, 5);




        $this->Rect(10, 231.7, 8.87, 5);
        $this->Rect(18.87, 231.7, 100, 5);
        $this->Rect(118.87, 231.7, 60, 5);
        $this->Rect(178.87, 231.7, 27, 5);




        $this->Rect(10, 236.7, 8.87, 5);
        $this->Rect(18.87, 236.7, 100, 5);
        $this->Rect(118.87, 236.7, 60, 5);
        $this->Rect(178.87, 236.7, 27, 5);
    }


    // Page footer
    function Footer()
    {
        include '../../includes/conn.php';

        // Position at 1.5 cm from bottom
        $this->Rect(10, 241.7, 196.72, 70);

        $this->SetXY(10, 255);
        $this->SetFontSize(8);
        $this->Cell(20, 5, 'Prepared by:', 0, 0);
        $this->SetFont('Arial', 'B', '10');
        $this->Cell(50, 5, $_SESSION['name'], 'B', 0, 'C'); //=====================PROFESSOR NAME=================
        $this->Cell(5, 5, '', 0, 0);
        $this->Cell(30, 5, date('M d, Y'), 'B', 1, 'C');

        $this->SetFont('Arial', '', '8');
        $this->Cell(20, 5, '', 0, 0);
        $this->Cell(50, 5, 'Professor / Instructor', 0, 0, 'C');
        $this->Cell(5, 5, '', 0, 0);
        $this->Cell(30, 5, 'Date', 0, 1, 'C');

        $this->Cell(120, 5, '', 0, 0);
        $this->Cell(50, 5, 'Verified by:', 0, 0);

        $this->Ln(10);

        $this->Cell(20, 5, 'Checked by:', 0, 0);
        $this->SetFont('Arial', 'B', '10');
        $this->Cell(50, 5, 'Dr. Santos T. Castillo, Jr.', 'B', 0, 'C'); //=================ACADEMIC HEAD=================
        $this->SetFont('Arial', '', '8');
        $this->Cell(5, 5, '', 0, 0);
        $this->Cell(30, 5, '', 'B', 0);
        $this->Cell(15, 5, '', 0, 0);
        $this->SetFont('Arial', 'B', '10');
        $this->Cell(50, 5, 'Rebecca Dela Cruz-Vicente, LPT', 'B', 1, 'C'); //=========================REGISTRAR NAME====================
        $this->SetFont('Arial', '', '8');
        $this->Cell(20, 5, '', 0, 0);
        $this->Cell(50, 5, 'Campus Director, College Dean', 0, 0, 'C');
        $this->Cell(5, 5, '', 0, 0);
        $this->Cell(30, 5, 'Date', 0, 0, 'C');
        $this->Cell(15, 5, '', 0, 0);
        $this->Cell(50, 5, 'College Registrar', 0, 1);

        $this->Ln(10);

        $this->Cell(20, 5, 'Received by:', 0, 0);
        $this->SetFont('Arial', 'B', '10');
        $this->Cell(50, 5, 'Sheryl Sajo', 'B', 0, 'C'); //=================RECORD VERIFIER=================
        $this->SetFont('Arial', '', '8');
        $this->Cell(5, 5, '', 0, 0);
        $this->Cell(30, 5, '', 'B', 0);
        $this->Cell(5, 5, '', 0, 1);


        $this->Cell(20, 4, '', 0, 0);
        $this->Cell(50, 4, 'Record Verifier', 0, 0, 'C');
        $this->Cell(5, 4, '', 0, 0);
        $this->Cell(30, 4, 'Date', 0, 0, 'C');
        $this->Cell(5, 4, '', 0, 1);

        $this->Cell(20, 4, '', 0, 0);
        $this->SetFont('Arial', 'I', '7');
        $this->Cell(50, 4, '(Signature over printed name)', 0, 0, 'C');
        $this->Cell(5, 5, '', 0, 0);
        $this->Cell(30, 4, '', 0, 0);
        $this->Cell(15, 4, '', 0, 0);
        $this->Cell(0, 4, 'Note: PLEASE READ ALL THE INSTRUCTIONS CAREFULLY AT', 0, 1);
        $this->Cell(120, 4, '', 0, 0);
        $this->Cell(0, 4, 'THE BACK BEFORE ACCOMPLISHING THIS FORM.', 0, 0, 'C');
    }
}

$pdf = new PDF('P', 'mm', 'Legal');
//left top right
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();
// $pdf->SetAutoPageBreak(true,112);
if (isset($_GET['semester']) && isset($_GET['acadyear'])) {
    $acadyear = $_GET['acadyear'];
    $semester = $_GET['semester'];
  
  } else {
    $acadyear = $_SESSION['active_acadyear'];
    $semester = $_SESSION['active_semester'];
    
  }

$pdf->SetFont('Arial', '', '11');
$pdf->SetXY(10, 91.7);
$que = mysqli_query($conn, "SELECT *, CONCAT(tbl_students.student_lname, ', ', tbl_students.student_fname, ' ', tbl_students.student_mname)  as fullname
                FROM tbl_enrolled_subjects 
                LEFT JOIN tbl_subjects_senior ON tbl_subjects_senior.subject_id = tbl_enrolled_subjects.subject_id
                LEFT JOIN tbl_students ON tbl_students.student_id = tbl_enrolled_subjects.student_id
                LEFT JOIN tbl_schoolyears ON tbl_schoolyears.student_id = tbl_students.student_id
                LEFT JOIN tbl_schedules ON tbl_schedules.schedule_id = tbl_enrolled_subjects.schedule_id
                LEFT JOIN tbl_strands ON tbl_strands.strand_id = tbl_schoolyears.strand_id
                LEFT JOIN tbl_grade_levels ON tbl_grade_levels.grade_level_id = tbl_schoolyears.grade_level_id
                WHERE tbl_schedules.schedule_id = '$schedule_id'
                AND tbl_schedules.section = '$section' 
                AND tbl_schoolyears.ay_id = '$acadyear'
                AND tbl_schoolyears.semester_id = '$semester'
                AND tbl_schoolyears.remark = 'Approved'
                ORDER BY fullname");

$y = $pdf->Gety();
$xy = 5;
$x = 1;

while ($row = mysqli_fetch_array($que)) {
        // $pdf ->SetXY(10,$y+$xy);
        $fullname = strtoupper($row['fullname']);
        # code...
        $pdf->Cell(8.98, 5, $x, 1, 0);
        $fontsize = 11;
        $tempFontSize = $fontsize;
        $cellwidth = 98;
        while ($pdf->GetStringWidth($fullname) > $cellwidth) {
            $pdf->SetFontSize($tempFontSize -= 0.1);
        }
        $pdf->Cell(100, 5, utf8_decode($fullname), 1, 0);
        $pdf->SetFont('Arial', '', '11');
        $fontsize1 = 10;
        $tempFontSize2 = $fontsize1;
        $cellwidth1 = 58;
        while ($pdf->GetStringWidth($row['strand_name']) > $cellwidth1) {
            $pdf->SetFontSize($tempFontSize2 -= 0.1);
        }
        $pdf->Cell(60, 5, $row['strand_name'], 1, 0);
        $pdf->SetFont('Arial', '', '11');
        $pdf->Cell(0, 5, $row['year_abv'], 1, 1, 'C');

        $xy += 5;
        $x += 1;
        if ($x == '31') {
            # code...
            $pdf->AddPage();
            $pdf->SetFont('Arial', '', '11');
            $pdf->SetXY(10, 91.7);
            if ($x == '61') {
                # code...
                $pdf->AddPage();
                $pdf->SetFont('Arial', '', '11');
                $pdf->SetXY(10, 91.7);
            }
        }
    
}
$pdf->SetFont('Arial', 'B', '12');
$pdf->Cell(200, 5, '*********sfac****************************Nothing Follows ' . ($x - 1) . ' Students****************************sfac**********', 0, 1, 'C');




$pdf->Output();
