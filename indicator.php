<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ตัวชี้วัดรายวิชา</title>
<link href="https://fonts.googleapis.com/css2?family=Itim&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Itim', cursive;
        margin: 0;
        padding: 0;
        background: #93C5FD;
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 100vh;
    }
    h1 {
        margin-top: 40px;
        text-align: center;
        font-size: 2rem;
        color: #FFFFFF;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
    }
    form {
        background: #FFFFFF;
        color: #1E3A8A;
        padding: 30px 40px;
        border-radius: 15px;
        border: 2px solid #1E3A8A;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        margin-top: 30px;
        width: 350px;
        max-width: 90%;
        transition: transform 0.3s;
    }
    form:hover { transform: translateY(-5px); }
    label { display: block; margin-top: 15px; font-weight: bold; }
    .custom-select { position: relative; width: 100%; }
    .custom-select select {
        width: 100%; padding: 10px 40px 10px 12px;
        border-radius: 8px; border: 2px solid #1E3A8A;
        font-size: 1rem; color: #1E3A8A; background: #FFFFFF;
        appearance: none; cursor: pointer; transition: 0.3s;
    }
    .custom-select select:hover { border-color: #2563EB; box-shadow: 0 0 5px rgba(37,99,235,0.5); }
    .custom-select::after {
        content: '▼'; font-size: 0.7rem; color: #1E3A8A;
        position: absolute; right: 12px; top: 50%;
        transform: translateY(-50%); pointer-events: none;
    }
    select option { color: #1E3A8A; background: #FFFFFF; }
    select option:hover { background: #DBEAFE; }
    .back-btn {
        margin-top: 25px; padding: 12px 25px; font-size: 18px;
        border-radius: 12px; border: 2px solid #1E3A8A;
        background: #3B82F6; color: #FFFFFF; font-weight: bold;
        cursor: pointer; display: flex; align-items: center;
        gap: 8px; transition: 0.3s;
    }
    .back-btn:hover { background: #2563EB; transform: translateY(-3px); }
    .back-btn:active { transform: translateY(1px); }
    @media (max-width: 500px) {
        h1 { font-size: 1.8rem; }
        form { padding: 20px 20px; width: 95%; }
        .back-btn { width: 90%; font-size: 16px; padding: 12px 0; justify-content: center; }
    }
</style>

<script>
function updateSubjects() {
    const year = document.getElementById('year').value;
    const subjectSelect = document.getElementById('subject');
    subjectSelect.innerHTML = '<option value="">-- กรุณาเลือกรายวิชา --</option>';

    let subjects = [];
    if (year === '3') {
        subjects = ['กฎหมายและจรรยาบรรณวิชาชีพ', 
            'การบริหารจัดการงานเวชระเบียน', 
            'รหัสทางการแพทย์ 3', 
            'สถิติเพื่อการวิจัย',
            'โปรแกรมคอมพิวเตอร์สำหรับงานเวชระเบียน',
            'ระเบียบวิธีวิจัย',
            'การปฐมพยาบาลเบื้องต้น'];
    } else if (year === '4') {
        subjects = [
            'ภาษาอังกฤษเพื่อการอ่านและการเขียนเชิงวิชาการ',
            'สัมมนาประเด็นและแนวโน้มในงานเวชระเบียน',
            'ระบบเครือข่ายคอมพิวเตอร์เพื่อการสื่อสารข้อมูล',
            'เศรษฐศาสตร์สาธารณสุขและการประกันสุขภาพ',
            'การตรวจสอบรหัสทางการแพทย์',
            'โครงงานพิเศษทางด้านเวชระเบียน'
        ];
    }

    subjects.forEach(function(subj) {
        const option = document.createElement('option');
        option.value = subj;
        option.text = subj;
        subjectSelect.appendChild(option);
    });
}

function goToFile() {
    const course = document.getElementById('course').value;
    const year = document.getElementById('year').value;
    const subject = document.getElementById('subject').value;

    if (
        course === "bsc_medrec" &&
        year === "4" &&
        subject === "ระบบเครือข่ายคอมพิวเตอร์เพื่อการสื่อสารข้อมูล"
    ) {
        window.open("indi04.pdf", "_blank");
    } else if (
        course === "bsc_medrec" &&
        year === "4" &&
        subject === "ภาษาอังกฤษเพื่อการอ่านและการเขียนเชิงวิชาการ"
    ) {
        window.open("indi04eng.pdf", "_blank");
    } else if (
        course === "bsc_medrec" &&
        year === "4" &&
        subject === "สัมมนาประเด็นและแนวโน้มในงานเวชระเบียน"
    ) {
        window.open("indi04seminar.pdf", "_blank");
    } else if (
        course === "bsc_medrec" &&
        year === "4" &&
        subject === "เศรษฐศาสตร์สาธารณสุขและการประกันสุขภาพ"
    ) {
        window.open("indi04money.pdf", "_blank");
    } else if (
        course === "bsc_medrec" &&
        year === "4" &&
        subject === "การตรวจสอบรหัสทางการแพทย์"
    ) {
        window.open("indi04code.pdf", "_blank");
    } else if (
        course === "bsc_medrec" &&
        year === "3" &&
        subject === "การปฐมพยาบาลเบื้องต้น"
    ) {
        window.open("indi03nurse.pdf", "_blank");
    } else if (
        course === "bsc_medrec" &&
        year === "3" &&
        subject === "สถิติเพื่อการวิจัย"
    ) {
        window.open("indi03stat.pdf", "_blank");
    } else if (
        course === "bsc_medrec" &&
        year === "3" &&
        subject === "โปรแกรมคอมพิวเตอร์สำหรับงานเวชระเบียน"
    ) {
        window.open("indi03com.pdf", "_blank");
    } else if (
        course === "bsc_medrec" &&
        year === "3" &&
        subject === "ระเบียบวิธีวิจัย"
    ) {
        window.open("indi03rese.pdf", "_blank");
    } else if (
        course === "bsc_medrec" &&
        year === "3" &&
        subject === "กฎหมายและจรรยาบรรณวิชาชีพ"
    ) {
        window.open("indi03rule.pdf", "_blank");
    } else {
        alert("ยังไม่มีไฟล์ PDF สำหรับตัวเลือกนี้");
    }
}
</script>
</head>
<body>
    <h1>ตัวชี้วัดรายวิชา</h1>

    <form>
        <label for="course">หลักสูตร:</label>
        <div class="custom-select">
            <select id="course" name="course">
                <option value="">-- กรุณาเลือกหลักสูตร --</option>
                <option value="bsc_medrec">วิทยาศาสตรบัณฑิต สาขาวิชาเวชระเบียน</option>
            </select>
        </div>

        <label for="year">ชั้นปี:</label>
        <div class="custom-select">
            <select id="year" name="year" onchange="updateSubjects()">
                <option value="">-- กรุณาเลือกชั้นปี --</option>
                <option value="3">ปี 3</option>
                <option value="4">ปี 4</option>
            </select>
        </div>

        <label for="subject">รายวิชา:</label>
        <div class="custom-select">
            <select id="subject" name="subject">
                <option value="">-- กรุณาเลือกรายวิชา --</option>
            </select>
        </div>
    </form>

    <button class="back-btn" type="button" onclick="goToFile()">📄 แสดง PDF</button>
    <button class="back-btn" onclick="window.location.href='index.php'; return false;">← กลับหน้าแรก</button>
</body>
</html>
