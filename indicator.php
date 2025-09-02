<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ตัวชี้วัดรายวิชา</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        label { display: block; margin-top: 15px; }
        select { width: 250px; padding: 5px; }
        .back-btn { margin-top: 25px; padding: 8px 16px; font-size: 16px; }
    </style>
    <script>
        function updateSubjects() {
            const year = document.getElementById('year').value;
            const subjectSelect = document.getElementById('subject');
            subjectSelect.innerHTML = '';

            let subjects = [];
            if (year === '3') {
                subjects = ['คอม', 'คณิต'];
            } else if (year === '4') {
                subjects = ['อังกฤษ', 'สัมมนา'];
            }

            subjects.forEach(function(subj) {
                const option = document.createElement('option');
                option.value = subj;
                option.text = subj;
                subjectSelect.appendChild(option);
            });
        }
    </script>
</head>
<body>
    <form>
        <label for="course">หลักสูตร:</label>
        <select id="course" name="course">
            <option value="">-- กรุณาเลือกหลักสูตร --</option>
            <option value="bsc_medrec">วิทยาศาสตรบัณฑิต สาขาวิชาเวชระเบียน</option>
        </select>

        <label for="year">ชั้นปี:</label>
        <select id="year" name="year" onchange="updateSubjects()">
            <option value="">-- กรุณาเลือกชั้นปี --</option>
            <option value="3">ปี 3</option>
            <option value="4">ปี 4</option>
        </select>

        <label for="subject">รายวิชา:</label>
        <select id="subject" name="subject">
            <option value="">-- กรุณาเลือกรายวิชา --</option>
        </select>
    </form>
    <button class="back-btn" onclick="window.location.href='index.php'; return false;">กลับหน้าแรก</button>
</body>
</html>