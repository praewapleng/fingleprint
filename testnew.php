<?php
// บังคับให้เว็บเพจแสดงผลเป็น UTF-8
header("Content-Type: text/html; charset=UTF-8");

require_once 'config/database.php';

function formatThaiDate($dateStr) {
    $days = ["อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์"];
    $months = [
        1=>"มกราคม", 2=>"กุมภาพันธ์", 3=>"มีนาคม", 4=>"เมษายน",
        5=>"พฤษภาคม", 6=>"มิถุนายน", 7=>"กรกฎาคม", 8=>"สิงหาคม",
        9=>"กันยายน", 10=>"ตุลาคม", 11=>"พฤศจิกายน", 12=>"ธันวาคม"
    ];
    $ts = strtotime($dateStr);
    $dayName = $days[date("w", $ts)];
    $day = date("j", $ts);
    $month = $months[date("n", $ts)];
    $year = date("Y", $ts) + 543;
    return "วัน{$dayName} ที่ {$day} {$month} {$year}";
}

// รับค่าจากฟอร์ม
$mode = $_GET['mode'] ?? 'all';
$selected_student = $_GET['student_code'] ?? '';

// ดึงรายชื่อนักศึกษาทั้งหมดสำหรับ dropdown
$sql_all_students = "SELECT studentcode, studentname 
                     FROM student 
                     WHERE studentcode LIKE '65208306%' 
                     ORDER BY studentcode";
$all_students_result = sqlsrv_query($conn, $sql_all_students);

// สร้างฟอร์มเลือกนักศึกษา
echo '<form method="get" style="margin-bottom: 20px;">
    <select name="mode" style="padding: 5px; margin-right: 10px;">
        <option value="all" '.($mode == 'all' ? 'selected' : '').'>แสดงทุกคน</option>
        <option value="individual" '.($mode == 'individual' ? 'selected' : '').'>แสดงรายบุคคล</option>
    </select>';

echo '<select name="student_code" style="padding: 5px; margin-right: 10px;" '.($mode == 'all' ? 'disabled' : '').'>';
echo '<option value="">เลือกนักศึกษา</option>';
while ($student = sqlsrv_fetch_array($all_students_result, SQLSRV_FETCH_ASSOC)) {
    echo '<option value="'.$student['studentcode'].'" '.($selected_student == $student['studentcode'] ? 'selected' : '').'>';
    echo $student['studentcode'].' - '.$student['studentname'];
    echo '</option>';
}
echo '</select>';
echo '<button type="submit" style="padding: 5px 15px;">แสดงผล</button></form>';

// ดึงรายชื่อนักศึกษาตามเงื่อนไข
$sql_students = "SELECT studentcode, studentname 
                 FROM student 
                 WHERE studentcode LIKE '65208306%' ";
if ($mode == 'individual' && !empty($selected_student)) {
    $sql_students .= "AND studentcode = '$selected_student' ";
}
$sql_students .= "ORDER BY studentcode";
$result_students = sqlsrv_query($conn, $sql_students);

$students = [];
while ($row = sqlsrv_fetch_array($result_students, SQLSRV_FETCH_ASSOC)) {
    $students[$row['studentcode']] = [
        'name' => $row['studentname'],
        'dates' => [],
        'late' => []
    ];
}
// ดึงวันที่ที่มีรายวิชาใน subject01 ที่ codesub = '0208304427'
$wantedDays = [];
$specificDates = [];

$sql_dates = "SELECT DISTINCT CONVERT(date, starttime) AS learn_date
              FROM subject01
              WHERE codesub = '0208304427'";
$result_dates = sqlsrv_query($conn, $sql_dates);
if ($result_dates === false) {
    die(print_r(sqlsrv_errors(), true)); // debug error
}
while ($row = sqlsrv_fetch_array($result_dates, SQLSRV_FETCH_ASSOC)) {
    if (!empty($row['learn_date'])) {
        if ($row['learn_date'] instanceof DateTime) {
            $dateStr = $row['learn_date']->format('Y-m-d');
        } else {
            $dateStr = $row['learn_date'];
        }
        $specificDates[] = $dateStr;
    }
}

// ดึงข้อมูลการสแกนเวลา
$sql = "SELECT b.studentcode, a.datetimescan
        FROM transcantime a
        INNER JOIN student b ON a.enrollnumber = b.enrollnumber
        WHERE b.studentcode LIKE '65208306%' ";

if ($mode == 'individual' && !empty($selected_student)) {
    $sql .= "AND b.studentcode = '$selected_student' ";
}

$sql .= "ORDER BY a.enrollnumber, a.datetimescan";
$result = sqlsrv_query($conn, $sql);

$dates = [];
foreach ($specificDates as $sd) $dates[$sd] = true;

while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $enroll = $row["studentcode"];
    $rawDate = $row["datetimescan"];

    if (!empty($rawDate)) {
        if ($rawDate instanceof DateTime) {
            $rawDate = $rawDate->format("Y-m-d H:i:s");
        }

        $parts = preg_split('/\s+/', $rawDate);
        if(count($parts) >= 2){
            $date = $parts[0];
            $timeStr = $parts[1];
            $timeParts = explode(":", $timeStr);
            if(count($timeParts)==3){
                $seconds = (int)$timeParts[0]*3600 + (int)$timeParts[1]*60 + (int)$timeParts[2];
                $start = 12*3600;   // 12:00
                $end   = 13*3600 + 15*60;  // 13:15
                $dayOfWeek = (int)date("w", strtotime($date));

                if ($seconds >= $start && $seconds <= $end) {
                    if (in_array($dayOfWeek, $wantedDays) || in_array($date, $specificDates)) {
                        $students[$enroll]['dates'][$date] = true;
                        $dates[$date] = true;
                    }
                } elseif ($seconds > $end && (in_array($dayOfWeek, $wantedDays) || in_array($date, $specificDates))) {
                    $students[$enroll]['late'][$date] = true;
                    $dates[$date] = true;
                }
            }
        }
    }
}

// เรียงวันที่ทั้งหมด
$allDates = array_keys($dates);
sort($allDates);

// สร้างตาราง
echo '<table border="1" cellpadding="5" cellspacing="0">';
echo '<tr><th>รหัสนักศึกษา</th><th>ชื่อ-นามสกุล</th>';
foreach ($allDates as $d) echo '<th>' . formatThaiDate($d) . '</th>';
echo '<th>จำนวนครั้งที่มาเรียน</th><th>เปอร์เซ็นต์การเข้าเรียน</th></tr>';

$totalSessions = count($allDates);

foreach ($students as $enroll => $data) {
    $attended = count(array_unique(array_merge(array_keys($data['dates']), array_keys($data['late']))));
    $percent = $totalSessions>0 ? number_format(($attended/$totalSessions)*100,1) : 0;

    $bgcolor = ($percent > 80) ? "#c6efce" : "#ffcccc";

    echo '<tr>';
    echo '<td>'.$enroll.'</td>';
    echo '<td>'.$data['name'].'</td>';

    foreach($allDates as $d){
        if(isset($data['dates'][$d])){
            echo '<td align="center" style="background-color:#c6efce;">✓</td>';
        } elseif(isset($data['late'][$d])) {
            echo '<td align="center" style="background-color:#ffcccc;">สาย</td>';
        } else {
            echo '<td align="center" style="background-color:#ffc7ce;">-</td>';
        }
    }

    echo '<td align="center">'.$attended.'/'.$totalSessions.'</td>';
    echo '<td align="center" style="background-color:'.$bgcolor.';">'.$percent.'%</td>';
    echo '</tr>';
}

echo '</table>';
echo '<p>จำนวนนักศึกษา: '.count($students).' คน</p>';
echo '<br><a href="index.php"><button>กลับหน้าแรก</button></a>';
echo ' <a href="testhome.php"><button>กลับไปยังหน้าที่แล้ว</button></a>';

sqlsrv_close($conn);
?>
<script>
document.querySelector('select[name="mode"]').addEventListener('change', function() {
    const studentSelect = document.querySelector('select[name="student_code"]');
    studentSelect.disabled = this.value === 'all';
    if (this.value === 'all') {
        studentSelect.value = '';
    }
});
</script>
