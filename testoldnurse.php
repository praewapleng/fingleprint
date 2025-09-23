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
                     WHERE studentcode BETWEEN 66208306003 AND 66208306059
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
                 WHERE studentcode BETWEEN 66208306003 AND 66208306059 ";
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

// ดึงวันที่เรียน
$wantedDays = [];
$specificDates = [];

$sql_dates = "SELECT DISTINCT CONVERT(date, starttime) AS learn_date
              FROM subject01
              WHERE codesub = '0208304036'";
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
        WHERE b.studentcode BETWEEN 66208306003 AND 66208306059 ";

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
                $start = 7*3600;   // 12:00
                $end   = 9*3600 + 15*60;  // 13:15
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

// ====== คำนวณสรุปสำหรับ Dashboard ======
$totalStudents = count($students);
$eligible = 0;
$notEligible = 0;
$totalLate = 0;

$totalSessions = count($allDates);

foreach ($students as $enroll => $data) {
    $attended = count(array_unique(array_merge(array_keys($data['dates']), array_keys($data['late']))));
    $absent = $totalSessions - $attended;
    $percent = $totalSessions>0 ? ($attended/$totalSessions)*100 : 0;

    // ✅ เงื่อนไขใหม่
    if ($percent >= 80 || ($percent < 80 && $absent < 3)) {
        $eligible++;
    } elseif ($percent < 80 && $absent >= 3) {
        $notEligible++;
    }

    $totalLate += count($data['late']);
}
?>

<div class="dashboard">
    <h2>สรุปผลการเข้าเรียน (Dashboard)</h2>
    <div class="cards">
        <div class="card blue">
            <h3>นักศึกษาทั้งหมด</h3>
            <p><?php echo $totalStudents; ?> คน</p>
        </div>
        <div class="card green">
            <h3>ผู้มีสิทธิ์สอบ</h3>
            <p><?php echo $eligible; ?> คน</p>
        </div>
        <div class="card red">
            <h3>ไม่มีสิทธิ์สอบ</h3>
            <p><?php echo $notEligible; ?> คน</p>
        </div>
        <div class="card orange">
            <h3>จำนวนมาสายรวม</h3>
            <p><?php echo $totalLate; ?> ครั้ง</p>
        </div>
    </div>

    <!-- กราฟวงกลม -->
    <div style="max-width:400px; margin:30px auto;">
        <canvas id="examChart"></canvas>
    </div>
</div>

<!-- เรียกใช้ Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('examChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['มีสิทธิ์สอบ', 'ไม่มีสิทธิ์สอบ'],
        datasets: [{
            data: [<?php echo $eligible; ?>, <?php echo $notEligible; ?>],
            backgroundColor: ['#4caf50', '#f44336'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let total = context.dataset.data.reduce((a,b)=>a+b,0);
                        let value = context.parsed;
                        let percentage = ((value/total)*100).toFixed(1);
                        return context.label + ': ' + value + ' คน ('+percentage+'%)';
                    }
                }
            }
        }
    }
});
</script>

<?php
// ====== ตารางเดิม ======
echo '<table border="1" cellpadding="5" cellspacing="0">';
echo '<tr><th>รหัสนักศึกษา</th><th>ชื่อ-นามสกุล</th>';
foreach ($allDates as $d) echo '<th>' . formatThaiDate($d) . '</th>';
echo '<th>จำนวนครั้งที่มาเรียน</th><th>เปอร์เซ็นต์การเข้าเรียน</th></tr>';

foreach ($students as $enroll => $data) {
    $attended = count(array_unique(array_merge(array_keys($data['dates']), array_keys($data['late']))));
    $absent = $totalSessions - $attended;
    $percent = $totalSessions>0 ? number_format(($attended/$totalSessions)*100,1) : 0;

    // ✅ เงื่อนไขใหม่
    if ($percent >= 80 || ($percent < 80 && $absent < 3)) {
        $bgcolor = "#c6efce"; // เขียว = มีสิทธิ์สอบ
    } elseif ($percent < 80 && $absent >= 3) {
        $bgcolor = "#ffcccc"; // แดง = ไม่มีสิทธิ์สอบ
    }

    echo '<tr>';
    echo '<td>'.$enroll.'</td>';
    echo '<td>'.$data['name'].'</td>';

    foreach($allDates as $d){
        if(isset($data['dates'][$d])){
            echo '<td align="center" style="background-color:#c6efce;">✓</td>';
        } elseif(isset($data['late'][$d])) {
            echo '<td align="center" style="background-color:#ffeb99;">สาย</td>';
        } else {
            echo '<td align="center" style="background-color:#ffc7ce;">-</td>';
        }
    }

    echo '<td align="center">'.$attended.'/'.$totalSessions.'</td>';
    echo '<td align="center" style="background-color:'.$bgcolor.';">'.$percent.'% (ขาด '.$absent.' ครั้ง)</td>';
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

<style>
body {
    font-family: 'TH Sarabun New', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #e3f0ff 0%, #f8fbff 100%);
    margin: 0;
    padding: 0;
}
form {
    background: #f4faff;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,50,0.04);
    padding: 20px 30px;
    display: inline-block;
}
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,50,0.07);
    margin-bottom: 30px;
}
th, td {
    padding: 10px 8px;
    text-align: center;
}
th {
    background: linear-gradient(90deg, #4f8edc 0%, #6cb6f7 100%);
    color: #fff;
    font-weight: 600;
    border-bottom: 2px solid #1976d2;
}
tr:nth-child(even) td { background: #f0f7ff; }
tr:hover td { background: #e3f0ff; }
td { border-bottom: 1px solid #e0eafc; }
button {
    background: linear-gradient(90deg, #1976d2 0%, #4f8edc 100%);
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 7px 18px;
    font-size: 1em;
    cursor: pointer;
    margin: 5px 0;
    transition: background 0.2s;
}
button:hover {
    background: linear-gradient(90deg, #1565c0 0%, #1976d2 100%);
}
select, input[type="text"] {
    border: 1px solid #b3d1f7;
    border-radius: 5px;
    background: #f4faff;
    color: #1976d2;
    font-size: 1em;
    padding: 5px 10px;
    outline: none;
}
.dashboard {
    margin: 30px auto;
    padding: 20px;
    background: #f4faff;
    border-radius: 12px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.05);
}
.dashboard h2 {
    color: #1976d2;
    margin-bottom: 20px;
    text-align: center;
}
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(200px,1fr));
    gap: 20px;
}
.card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    transition: transform 0.2s;
}
.card:hover { transform: translateY(-4px); }
.card h3 { font-size: 1.2em; margin-bottom: 10px; }
.card p { font-size: 1.5em; font-weight: bold; }
.card.blue { border-top: 4px solid #42a5f5; }
.card.green { border-top: 4px solid #4caf50; }
.card.red { border-top: 4px solid #f44336; }
.card.orange { border-top: 4px solid #ff9800; }
</style>
