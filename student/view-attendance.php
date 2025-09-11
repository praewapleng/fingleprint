<?php
include("../config/database.php");

// ✅ รับค่าโหมดการดู (individual = รายบุคคล, all = ทุกคน)
$mode = $_GET['mode'] ?? "individual";
$student_id = $_GET['student_id'] ?? 36; // กำหนดค่า default
$subject_id = $_GET['subject_id'] ?? null;

// ✅ ดึงรายชื่อวิชา
$subjects = $conn->query("SELECT * FROM subject01 ORDER BY starttime ASC");

// ✅ ถ้าเลือกวิชาแล้ว
$students = null;
if ($subject_id) {
    if ($mode === "individual") {
        // ดูเฉพาะคนเดียว
        $sql = "SELECT s.id, s.studentname, sj.namesub, sj.starttime, sj.endtime, 
                       MIN(t.datetimescan) AS scan_time
                FROM enrollment01 e
                JOIN student s ON e.id_std = s.id
                JOIN subject01 sj ON e.id_subj = sj.idsubj
                LEFT JOIN transcantime t 
                    ON t.enrollnumber = s.enrollnumber
                    AND DATE(t.datetimescan) = DATE(sj.starttime)
                WHERE sj.idsubj = ? AND s.id = ?
                GROUP BY s.id, s.studentname, sj.namesub, sj.starttime, sj.endtime";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $subject_id, $student_id);
    } else {
        // ดูทุกคน
        $sql = "SELECT s.id, s.studentname, sj.namesub, sj.starttime, sj.endtime, 
                       MIN(t.datetimescan) AS scan_time
                FROM enrollment01 e
                JOIN student s ON e.id_std = s.id
                JOIN subject01 sj ON e.id_subj = sj.idsubj
                LEFT JOIN transcantime t 
                    ON t.enrollnumber = s.enrollnumber
                    AND DATE(t.datetimescan) = DATE(sj.starttime)
                WHERE sj.idsubj = ?
                GROUP BY s.id, s.studentname, sj.namesub, sj.starttime, sj.endtime";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $subject_id);
    }
    $stmt->execute();
    $students = $stmt->get_result();
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
        return ["ลา", "secondary"];
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ผลการเข้าเรียน (นักศึกษา)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="mb-4">👨‍🎓 ผลการเข้าเรียน</h2>

  <!-- ✅ เลือกรายวิชาและโหมด -->
  <form method="get" class="mb-4">
    <div class="row g-3">
      <div class="col-md-4">
        <select name="subject_id" class="form-select" required>
          <option value="">-- เลือกรายวิชา --</option>
          <?php while ($sub = $subjects->fetch_assoc()): ?>
            <option value="<?= $sub['idsubj'] ?>" 
              <?= ($subject_id == $sub['idsubj']) ? 'selected' : '' ?>>
              <?= $sub['namesub'] ?> (<?= date("d/m/Y H:i", strtotime($sub['starttime'])) ?>)
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-3">
        <select name="mode" class="form-select">
          <option value="individual" <?= ($mode == "individual") ? "selected" : "" ?>>รายบุคคล</option>
          <option value="all" <?= ($mode == "all") ? "selected" : "" ?>>ทุกคน</option>
        </select>
      </div>
      <div class="col-md-3">
        <input type="number" name="student_id" value="<?= $student_id ?>" class="form-control" placeholder="รหัสนักศึกษา (เช่น 36)">
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">แสดงผล</button>
      </div>
    </div>
  </form>

  <!-- ✅ ตารางผลลัพธ์ -->
  <?php if ($students && $students->num_rows > 0): ?>
    <div class="card shadow">
      <div class="card-header bg-dark text-white">ผลการเข้าเรียน</div>
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
            <?php while ($row = $students->fetch_assoc()): ?>
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
    <div class="alert alert-warning">ไม่พบข้อมูลการเข้าเรียน</div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
