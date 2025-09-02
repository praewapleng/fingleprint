<?php
require('tabledata.php'); 

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

// ดึงรายชื่อนักศึกษาทุกคน
$sql_students = "SELECT studentcode, studentname 
                 FROM student 
                 WHERE studentcode LIKE '65208306%' 
                 ORDER BY studentcode";
$result_students = mysqli_query($con, $sql_students);

$students = [];
while ($row = mysqli_fetch_assoc($result_students)) {
    $students[$row['studentcode']] = [
        'name' => $row['studentname'],
        'dates' => [],
        'late' => []
    ];
}

// วันพิเศษที่ต้องโชว์ และวันพุธ
$specificDates = ['2025-08-23','2025-06-26'];
$wantedDays = [3]; // วันพุธ

// ดึงข้อมูลการสแกนเวลา
$sql = "SELECT b.studentcode, a.datetimescan
        FROM transcantime a
        INNER JOIN student b ON a.enrollnumber = b.enrollnumber
        WHERE b.studentcode LIKE '65208306%'
        ORDER BY a.enrollnumber, a.datetimescan";
$result = mysqli_query($con, $sql);

$dates = [];
foreach ($specificDates as $sd) $dates[$sd] = true;

while ($row = mysqli_fetch_assoc($result)) {
    $enroll = $row["studentcode"];
    $rawDate = $row["datetimescan"];

    if (!empty($rawDate)) {
        $parts = preg_split('/\s+/', $rawDate);
        if(count($parts) >= 2){
            $date = $parts[0];
            $timeStr = $parts[1];
            $timeParts = explode(":", $timeStr);
            if(count($timeParts)==3){
                $seconds = (int)$timeParts[0]*3600 + (int)$timeParts[1]*60 + (int)$timeParts[2];
                $start = 12*3600 + 0*60;   // 12:00
                $end   = 13*3600 + 15*60;  // 13:15
                $dayOfWeek = (int)date("w", strtotime($date));

                // มาในช่วงเวลาเรียน
                if ($seconds >= $start && $seconds <= $end) {
                    if (in_array($dayOfWeek, $wantedDays) || in_array($date, $specificDates)) {
                        $students[$enroll]['dates'][$date] = true;
                        $dates[$date] = true;
                    }
                } 
                // มาสายหลังเวลาเรียน
                elseif ($seconds > $end && (in_array($dayOfWeek, $wantedDays) || in_array($date, $specificDates))) {
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
    // นับจำนวนวันที่มาเรียนจริง รวมมาสาย
    $attended = count(array_unique(array_merge(array_keys($data['dates']), array_keys($data['late']))));
    $percent = $totalSessions>0 ? number_format(($attended/$totalSessions)*100,1) : 0;

    // เปลี่ยนสีพื้นหลังตามเงื่อนไขใหม่
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
?>
