<?php
include("../config/database.php");

// ✅ รับรหัสนักศึกษา
$student_id = $_GET['student_id'] ?? 36;

// ✅ ดึงตารางเรียนของนักศึกษา
$sql = "SELECT s.id, s.studentname, sj.namesub, sj.starttime, sj.endtime
        FROM enrollment01 e
        JOIN student s ON e.id_std = s.id
        JOIN subject01 sj ON e.id_subj = sj.idsubj
        WHERE s.id = ?
        ORDER BY sj.starttime ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ตารางเรียนของนักศึกษา</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="mb-4">📅 ตารางเรียนของนักศึกษา</h2>

  <!-- ✅ เลือกรหัสนักศึกษา -->
  <form method="get" class="mb-4">
    <div class="row g-3">
      <div class="col-md-10">
        <input type="number" name="student_id" value="<?= $student_id ?>" class="form-control" placeholder="ใส่รหัสนักศึกษา เช่น 36" required>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">ค้นหา</button>
      </div>
    </div>
  </form>

  <!-- ✅ ตารางผลลัพธ์ -->
  <?php if ($student && count($student) > 0): ?>
    <div class="card shadow">
      <div class="card-header bg-dark text-white">ตารางเรียน</div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead class="table-secondary">
            <tr>
              <th>รหัสนักศึกษา</th>
              <th>ชื่อนักศึกษา</th>
              <th>รายวิชา</th>
              <th>เวลาเริ่ม</th>
              <th>เวลาเลิก</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($student as $row): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['studentname']) ?></td>
                <td><?= htmlspecialchars($row['namesub']) ?></td>
                <td><?= date("d/m/Y H:i", strtotime($row['starttime'])) ?></td>
                <td><?= date("d/m/Y H:i", strtotime($row['endtime'])) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-warning">ไม่พบข้อมูลตารางเรียนของนักศึกษา <?= $student_id ?></div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
