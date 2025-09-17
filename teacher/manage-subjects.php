<?php
include("../config/database.php");

// ✅ ลบรายวิชา
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM subject01 WHERE idsubj=?";
    $params = array($id);
    sqlsrv_query($conn, $sql, $params);
    header("Location: manage-subjects.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $namesub   = $_POST['namesub'];
    $starttime = date("Y-m-d H:i:s", strtotime($_POST['starttime']));
    $endtime   = date("Y-m-d H:i:s", strtotime($_POST['endtime']));
    $codesub   = $_POST['codesub'];

    $sql = "INSERT INTO subject01 (namesub, starttime, endtime, codesub) VALUES (?, ?, ?, ?)";
    $params = array($namesub, $starttime, $endtime, $codesub);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true)); // ดู error
    }

    header("Location: manage-subjects.php");
    exit();
}

// ✅ แก้ไขรายวิชา
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = intval($_POST['idsubj']);
    $namesub = $_POST['namesub'];
    $starttime = $_POST['starttime'];
    $endtime = $_POST['endtime'];
    $codesub = $_POST['codesub'];

    $sql = "UPDATE subject01 SET namesub=?, starttime=?, endtime=?, codesub=? WHERE idsubj=?";
    $params = array($namesub, $starttime, $endtime, $codesub, $id);
    sqlsrv_query($conn, $sql, $params);
    header("Location: manage-subjects.php");
    exit();
}

// ✅ ดึงรายวิชาทั้งหมด
$result = sqlsrv_query($conn, "SELECT * FROM subject01 ORDER BY starttime ASC");
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>จัดการรายวิชา</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="mb-4">📘 จัดการรายวิชา</h2>

  <!-- ✅ ฟอร์มเพิ่มรายวิชา -->
  <div class="card mb-4 shadow">
    <div class="card-header bg-primary text-white">เพิ่มรายวิชา</div>
    <div class="card-body">
      <form method="post">
        <div class="mb-3">
          <label class="form-label">ชื่อวิชา</label>
          <input type="text" name="namesub" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">เวลาเริ่ม</label>
          <input type="datetime-local" name="starttime" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">เวลาเลิก</label>
          <input type="datetime-local" name="endtime" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">รหัสวิชา</label>
          <input type="text" name="codesub" class="form-control" required>
        </div>
        <button type="submit" name="create" class="btn btn-success">บันทึก</button>
      </form>
    </div>
  </div>

  <!-- ✅ ตารางรายวิชา -->
  <div class="card shadow">
    <div class="card-header bg-dark text-white">รายวิชาที่มีอยู่</div>
    <div class="card-body">
      <table class="table table-bordered table-striped">
        <thead class="table-secondary">
          <tr>
            <th>ชื่อวิชา</th>
            <th>เวลาเริ่ม</th>
            <th>เวลาเลิก</th>
            <th>รหัสวิชา</th>
            <th>จัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)): ?>
            <tr>
              <td><?= htmlspecialchars($row['namesub']) ?></td>
              <td><?= $row['starttime'] instanceof DateTime ? $row['starttime']->format('Y-m-d H:i') : '' ?></td>
              <td><?= $row['endtime'] instanceof DateTime ? $row['endtime']->format('Y-m-d H:i') : '' ?></td>
              <td><?= htmlspecialchars($row['codesub']) ?></td>
              <td>
                <!-- ปุ่มแก้ไข -->
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['idsubj'] ?>">แก้ไข</button>
                <!-- ปุ่มลบ -->
                <a href="?delete=<?= $row['idsubj'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจว่าต้องการลบรายวิชานี้?')">ลบ</a>
              </td>
            </tr>

            <!-- Modal แก้ไขรายวิชา -->
            <div class="modal fade" id="editModal<?= $row['idsubj'] ?>" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form method="post">
                    <div class="modal-header bg-warning">
                      <h5 class="modal-title">แก้ไขรายวิชา</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <input type="hidden" name="idsubj" value="<?= $row['idsubj'] ?>">
                      <div class="mb-3">
                        <label class="form-label">ชื่อวิชา</label>
                        <input type="text" name="namesub" class="form-control" value="<?= htmlspecialchars($row['namesub']) ?>" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">เวลาเริ่ม</label>
                        <input type="datetime-local" name="starttime" class="form-control"
                               value="<?= $row['starttime'] instanceof DateTime ? $row['starttime']->format('Y-m-d\TH:i') : '' ?>" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">เวลาเลิก</label>
                        <input type="datetime-local" name="endtime" class="form-control"
                               value="<?= $row['endtime'] instanceof DateTime ? $row['endtime']->format('Y-m-d\TH:i') : '' ?>" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">รหัสวิชา</label>
                        <input type="text" name="codesub" class="form-control" value="<?= htmlspecialchars($row['codesub']) ?>" required>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" name="update" class="btn btn-success">บันทึก</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- ปุ่มกลับหน้าหลัก -->
<div class="container mt-3">
  <a href="choose.php" class="btn btn-outline-primary ms-2">กลับไปยังหน้าที่แล้ว</a>
  <a href="../index.php" class="btn btn-secondary">กลับหน้าหลัก</a>
</div>
</body>
</html>
<?php