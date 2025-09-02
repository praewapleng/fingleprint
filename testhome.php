<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบผลการเข้าเรียน</title>
    <script>
        function updateSubjects() {
            var year = document.getElementById('year').value;
            var subjectSelect = document.getElementById('subject');
            subjectSelect.innerHTML = '';

            var subjects = {
                '3': ['คอมพิวเตอร์', 'คณิตศาสตร์'],
                '4': ['รหัสแพทย์', 'ภาษาอังกฤษ', 'คอมพิวเตอร์', 'เศรษฐศาสตร์']
            };

            if (subjects[year]) {
                subjects[year].forEach(function(subj) {
                    var option = document.createElement('option');
                    option.value = subj;
                    option.text = subj;
                    subjectSelect.appendChild(option);
                });
            }
        }
    </script>
</head>
<body>
    <form id="courseForm">
        <label for="course">หลักสูตร:</label>
        <select id="course" name="course">
            <option value="">-- เลือกหลักสูตร --</option>
            <option value="bscmrs">วิทยาศาสตรบัณฑิต สาขาวิชาเวชระเบียน</option>
        </select>
        <br><br>

        <label for="year">ชั้นปี:</label>
        <select id="year" name="year" onchange="updateSubjects()">
            <option value="">-- เลือกชั้นปี --</option>
            <option value="3">ปี 3</option>
            <option value="4">ปี 4</option>
        </select>
        <br><br>

        <label for="subject">วิชา:</label>
        <select id="subject" name="subject">
            <option value="">-- เลือกวิชา --</option>
        </select>
        <br><br>

        <!-- ✅ ปุ่ม submit ต้องอยู่ใน form -->
        <button type="submit">ตกลง</button>
    </form>

    <br>
    <button type="button" onclick="window.location.href='index.php'">กลับหน้าแรก</button>

    <script>
        document.getElementById('courseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var course = document.getElementById('course').value;
            var year = document.getElementById('year').value;
            var subject = document.getElementById('subject').value;

            // ✅ ตรวจสอบค่าและ redirect
            if (course === 'bscmrs' && year === '4' && subject === 'คอมพิวเตอร์') {
                window.location.href = 'testnew.php';
            } else {
                alert('กรุณาเลือกหลักสูตร ปี และวิชาให้ถูกต้อง');
            }
        });
    </script>
</body>
</html>
