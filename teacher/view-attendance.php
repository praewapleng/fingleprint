<?php
include("../config/database.php");

// ✅ เลือกรายวิชา
$subject_id = $_GET['subject_id'] ?? null;

// ✅ ดึงรายชื่อวิชา
$subjects = sqlsrv_query($conn, "SELECT * FROM subject01 ORDER BY starttime ASC");

// ✅ ถ้าเลือกแล้ว ให้ดึงนักศึกษา
$students = null;
if ($subject_id) {
    $sql = "SELECT s.id, s.studentname, sj.namesub, sj.starttime, sj.endtime, 
                   MIN(t.datetimescan) AS scan_time
            FROM enrollment01 e
            JOIN student s ON e.id_std = s.id
            JOIN subject01 sj ON e.id_subj = sj.idsubj
            LEFT JOIN transcantime t 
                ON t.enrollnumber = s.enrollnumber
                AND CONVERT(date, t.datetimescan) = CONVERT(date, sj.starttime)
            WHERE sj.idsubj = ?
            GROUP BY s.id, s.studentname, sj.namesub, sj.starttime, sj.endtime";

    $params = array($subject_id);
    $students = sqlsrv_query($conn, $sql, $params);
}

// ✅ ฟังก์ชันเช็คสถานะ
function getStatus($scan_time, $starttime, $endtime) {
    if (!$scan_time) {
        return ["ขาด", "danger"];
    }
    $scan = strtotime($scan_time);
    $start = strtotime($starttime);
    $end   = strtotime($endtime);

    if ($scan <= $start) {
        return ["มา", "success"];
    } elseif ($scan > $start && $scan <= strtotime("+15 minutes", $start)) {
        return ["สาย", "warning"];
    } else {
        return ["ลา", "secondary"]; // เผื่ออาจารย์บันทึกการลา
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>รายงานการเข้าเรียน</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="mb-4">📊 รายงานการเข้าเรียน</h2>

  <!-- ✅ เลือกรายวิชา -->
  <form method="get" class="mb-4">
    <div class="row">
      <div class="col-md-8">
        <select name="subject_id" class="form-select" required>
          <option value="">-- เลือกรายวิชา --</option>
          <?php while ($sub = sqlsrv_fetch_array($subjects, SQLSRV_FETCH_ASSOC)): ?>
            <option value="<?= $sub['idsubj'] ?>" 
              <?= ($subject_id == $sub['idsubj']) ? 'selected' : '' ?>>
              <?= $sub['namesub'] ?> (<?= date("d/m/Y H:i", strtotime($sub['starttime'])) ?>)
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-4">
        <button type="submit" class="btn btn-primary w-100">แสดงรายงาน</button>
      </div>
    </div>
  </form>

  <!-- ✅ ตารางรายงาน -->
  <?php if ($students): ?>
    <div class="card shadow">
      <div class="card-header bg-dark text-white">รายงานผลเข้าเรียน</div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead class="table-secondary">
            <tr>
              <th>รหัสนักศึกษา</th>
              <th>ชื่อนักศึกษา</th>
              <th>วิชา</th>
              <th>เวลาเริ่ม</th>
              <th>เวลาเลิก</th>
              <th>เวลาเข้าเรียน</th>
              <th>สถานะ</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = sqlsrv_fetch_array($students, SQLSRV_FETCH_ASSOC)): ?>
              <?php list($status, $color) = getStatus($row['scan_time'], $row['starttime'], $row['endtime']); ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['studentname']) ?></td>
                <td><?= htmlspecialchars($row['namesub']) ?></td>
                <td><?= $row['starttime'] ?></td>
                <td><?= $row['endtime'] ?></td>
                <td><?= $row['scan_time'] ?? "-" ?></td>
                <td><span class="badge bg-<?= $color ?>"><?= $status ?></span></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php elseif ($subject_id): ?>
    <div class="alert alert-warning">ไม่พบนักศึกษาในรายวิชานี้</div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
