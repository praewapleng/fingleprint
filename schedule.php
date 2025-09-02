<!-- ตั้งชื่อแท็บว่า ตารางเรียน -->
<?php
echo '<title>ตารางเรียน</title>';
?>
<form>
    <label for="course">หลักสูตร:</label>
    <select id="course" name="course" onchange="showImage()">
        <option value="">-- เลือกหลักสูตร --</option>
        <option value="วิทยาศาสตรบัณฑิต สาขาวิชาเวชระเบียน">วิทยาศาสตรบัณฑิต สาขาวิชาเวชระเบียน</option>
        <!-- เพิ่มหลักสูตรอื่น ๆ ตามต้องการ -->
    </select>

    <label for="year">ชั้นปี:</label>
    <select id="year" name="year" onchange="showImage()">
        <option value="">-- เลือกชั้นปี --</option>
        <option value="3">ปี 3</option>
        <option value="4">ปี 4</option>
    </select>
</form>

<div id="image-container"></div>

<script>
function showImage() {
    var course = document.getElementById('course').value;
    var year = document.getElementById('year').value;
    var container = document.getElementById('image-container');

    if (course === 'วิทยาศาสตรบัณฑิต สาขาวิชาเวชระเบียน') {
        if (year === '3') {
            container.innerHTML = '<img src="schedule03.jpg" alt="ตารางเรียน ปี 3" style="width:300px;">';
        } else if (year === '4') {
            container.innerHTML = '<img src="schedule04v2.jpg" alt="ตารางเรียน ปี 4" style="width:1000px;">';
        } else {
            container.innerHTML = '';
        }
    } else {
        container.innerHTML = '';
    }
}
</script>

<button onclick="window.location.href='index.php'" type="button">กลับหน้าแรก</button>
