<?php

/**
 * ----------------------------------------------
 * FUZZY RANKING LOGIC FOR STUDENT GRADES (PHP)
 * ----------------------------------------------
 * This algorithm:
 * 1. Fuzzifies numerical grades
 * 2. Applies fuzzy rules
 * 3. Defuzzifies using the centroid method
 * 4. Produces a ranking score
 * 5. Sorts students by fuzzy ranking score
 */

// -------------------------------
// Membership Functions
// -------------------------------
function mf_low($grade) {
    if ($grade <= 75) return 1;
    if ($grade >= 85) return 0;
    return (85 - $grade) / 10;  // linear decrease
}

function mf_average($grade) {
    if ($grade <= 75 || $grade >= 95) return 0;
    if ($grade == 85) return 1;
    if ($grade < 85) return ($grade - 75) / 10;  // increase to 1
    return (95 - $grade) / 10;  // decrease from 1
}

function mf_high($grade) {
    if ($grade <= 85) return 0;
    if ($grade >= 95) return 1;
    return ($grade - 85) / 10;  // linear increase
}

// -------------------------------
// Defuzzification via Centroid
// -------------------------------
function defuzzify($low, $avg, $high) {
    // weighted centroid formula
    $numerator = ($low * 60) + ($avg * 85) + ($high * 95);
    $denominator = ($low + $avg + $high);

    if ($denominator == 0) return 0;

    return $numerator / $denominator;
}

// -------------------------------
// Fuzzy Ranking Function
// -------------------------------
function fuzzy_rank_student($grade) {
    // fuzzification
    $low = mf_low($grade);
    $avg = mf_average($grade);
    $high = mf_high($grade);

    // defuzzification
    return defuzzify($low, $avg, $high);
}

// -------------------------------
// Example: List of Students
// -------------------------------
$students = [
    ["name" => "Alice", "grade" => 92],
    ["name" => "Ben", "grade" => 85],
    ["name" => "Charlie", "grade" => 78],
    ["name" => "Daisy", "grade" => 88],
    ["name" => "Evan", "grade" => 95]
];

// -------------------------------
// Compute Fuzzy Scores
// -------------------------------
foreach ($students as $key => $s) {
    $students[$key]["fuzzy_score"] = fuzzy_rank_student($s["grade"]);
}

// -------------------------------
// Sort by fuzzy ranking score
// -------------------------------
usort($students, function($a, $b) {
    return $b["fuzzy_score"] <=> $a["fuzzy_score"];
});

// -------------------------------
// Display Results
// -------------------------------
echo "<h2>Fuzzy Ranking Results</h2>";
echo "<table border='1' cellpadding='6'>
        <tr>
            <th>Name</th>
            <th>Grade</th>
            <th>Fuzzy Score</th>
            <th>Rank</th>
        </tr>";

$rank = 1;
foreach ($students as $s) {
    echo "<tr>
            <td>{$s['name']}</td>
            <td>{$s['grade']}</td>
            <td>" . number_format($s['fuzzy_score'], 2) . "</td>
            <td>{$rank}</td>
          </tr>";
    $rank++;
}

echo "</table>";
?>

            <tbody>
                <?php

                $students = []; // array to store all students + fuzzy score

                if (isset($_POST['search'])) {
                    $search = addslashes($_POST['search']);

                    $student_info = mysqli_query($conn, "SELECT stud_no, strand_name, grade_level, tbl_students.student_id, 
                    CONCAT(tbl_students.student_lname, ', ', tbl_students.student_fname, ' ', tbl_students.student_mname)  as fullname
                    FROM tbl_schoolyears
                    iNNER JOIN tbl_students ON tbl_students.student_id = tbl_schoolyears.student_id
                    LEFT JOIN tbl_strands ON tbl_strands.strand_id = tbl_schoolyears.strand_id
                    LEFT JOIN tbl_grade_levels ON tbl_grade_levels.grade_level_id = tbl_schoolyears.grade_level_id
                    LEFT JOIN tbl_acadyears ON tbl_acadyears.ay_id = tbl_schoolyears.ay_id
                    LEFT JOIN tbl_semesters ON tbl_semesters.semester_id = tbl_schoolyears.semester_id
                    WHERE tbl_acadyears.academic_year = '$acadyear'
                    AND tbl_semesters.semester = '$semester'
                    AND tbl_schoolyears.remark = 'Approved'
                    AND tbl_grade_levels.grade_level_id = 14
                    AND (student_fname LIKE '%$search%'
                    OR student_mname LIKE '%$search%'
                    OR student_lname LIKE '%$search%'
                    OR strand_name LIKE '%$search%'
                    OR strand_def LIKE '%$search%'
                    OR grade_level LIKE '%$search%'
                    OR stud_no LIKE '%$search%')
                    ORDER BY student_lname");

                    while ($row = mysqli_fetch_array($student_info))  {

                        // compute average grade
                        $grade_info = mysqli_query($conn, "SELECT * FROM tbl_enrolled_subjects
                            LEFT JOIN tbl_schedules ON tbl_schedules.schedule_id = tbl_enrolled_subjects.schedule_id
                            LEFT JOIN tbl_subjects_senior ON tbl_subjects_senior.subject_id = tbl_schedules.subject_id
                            WHERE student_id = '$row[student_id]'
                            AND tbl_subjects_senior.semester_id = '$_SESSION[active_semester_id]'
                            AND tbl_schedules.acadyear = '$_SESSION[active_acadyears]'");

                        $average = 0;
                        $index = 0;
                        while($row1 = mysqli_fetch_array($grade_info))  {
                            $average += $row1['ofgrade'];
                            $index++;
                        }
                        $total_ave = ($index > 0) ? $average / $index : 0;

                        // PUSH into array
                        $students[] = [
                            "stud_no" => $row['stud_no'],
                            "fullname" => $row['fullname'],
                            "strand_name" => $row['strand_name'],
                            "grade_level" => $row['grade_level'],
                            "fuzzy_score" => fuzzy_rank_student($total_ave)   // <- this is the score you will rank
                        ];
                    }

                         usort($students, function($a, $b) {
                            return $b["fuzzy_score"] <=> $a["fuzzy_score"];
                        });

                        $rank = 1;
                        foreach ($students as $key => $stud) {
                            $students[$key]["rank"] = $rank;
                            $rank++;
                        }

                        foreach ($students as $s) {
                        echo "
                        <tr>
                            <td>{$s['stud_no']}</td>
                            <td>{$s['fullname']}</td>
                            <td>{$s['strand_name']}</td>
                            <td>{$s['grade_level']}</td>
                            <td>" . number_format($s['fuzzy_score'], 2) . "</td>
                            <td>{$s['rank']}</td>
                        </tr>";
                    }
                }
                                                                            
                ?>
                
              </tbody>



              <tbody>
                <?php
                if (isset($_POST['search'])) {
                    $search = addslashes($_POST['search']);

                    $student_info = mysqli_query($conn, "SELECT stud_no, strand_name, grade_level, tbl_students.student_id, 
                    CONCAT(tbl_students.student_lname, ', ', tbl_students.student_fname, ' ', tbl_students.student_mname)  as fullname
                    FROM tbl_schoolyears
                    iNNER JOIN tbl_students ON tbl_students.student_id = tbl_schoolyears.student_id
                    LEFT JOIN tbl_strands ON tbl_strands.strand_id = tbl_schoolyears.strand_id
                    LEFT JOIN tbl_grade_levels ON tbl_grade_levels.grade_level_id = tbl_schoolyears.grade_level_id
                    LEFT JOIN tbl_acadyears ON tbl_acadyears.ay_id = tbl_schoolyears.ay_id
                    LEFT JOIN tbl_semesters ON tbl_semesters.semester_id = tbl_schoolyears.semester_id
                    WHERE tbl_acadyears.academic_year = '$acadyear'
                    AND tbl_semesters.semester = '$semester'
                    AND tbl_schoolyears.remark = 'Approved'
                    AND tbl_grade_levels.grade_level_id = 14
                    AND (student_fname LIKE '%$search%'
                    OR student_mname LIKE '%$search%'
                    OR student_lname LIKE '%$search%'
                    OR strand_name LIKE '%$search%'
                    OR strand_def LIKE '%$search%'
                    OR grade_level LIKE '%$search%'
                    OR stud_no LIKE '%$search%')
                    ORDER BY student_lname");

                    while ($row = mysqli_fetch_array($student_info))  {
                        $grade_info = mysqli_query($conn, "SELECT * FROM tbl_enrolled_subjects
                        LEFT JOIN tbl_schedules ON tbl_schedules.schedule_id = tbl_enrolled_subjects.schedule_id
                        LEFT JOIN tbl_subjects_senior ON tbl_subjects_senior.subject_id = tbl_schedules.subject_id
                        WHERE student_id = '$row[student_id]'
                        AND tbl_subjects_senior.semester_id = '$_SESSION[active_semester_id]'
                        AND tbl_schedules.acadyear = '$_SESSION[active_acadyears]'");
                        $average = 0;
                        $index = 0;
                        while($row1 = mysqli_fetch_array($grade_info))  {
                            $average += $row1['ofgrade'];
                            $index ++;
                        }
                        $total_ave =  $average/$index;
                        
                ?>

                <tr>
                  <td><?php echo $row['stud_no']?></td>
                  <td><?php echo $row['fullname']?></td>
                  <td><?php echo $row['strand_name']?></td>
                  <td><?php echo $row['grade_level']?></td>
                  <td><?php echo number_format((float)$total_ave , 2, '.', '');?></td>
                  
        
                  
                </tr>
                  
                <?php
                }}
                ?>
              </tbody>