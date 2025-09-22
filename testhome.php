<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ระบบผลการเข้าเรียน</title>
<link href="https://fonts.googleapis.com/css2?family=Itim&display=swap" rel="stylesheet">
<style>
    :root {
        --blue-500: #3B82F6;
        --blue-600: #2563EB;
        --blue-700: #1E40AF;
        --blue-800: #1E3A8A;
        --blue-300: #93C5FD;
        --blue-100: #DBEAFE;
    }

    body {
        font-family: 'Itim', cursive;
        margin: 0;
        padding: 0;
        background: var(--blue-100);
        min-height: 100vh;
        color: var(--blue-800);
        overflow-x: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* ☁️ ก้อนเมฆ */
    .cloud {
        position: absolute;
        background: #fff;
        border-radius: 50%;
        opacity: 0.8;
        animation: float 60s linear infinite;
        z-index: 0;
    }
    .cloud::before, .cloud::after {
        content: '';
        position: absolute;
        background: #fff;
        border-radius: 50%;
    }
    .cloud1 { width: 120px; height: 60px; top: 10%; left: -150px; animation-duration: 80s; }
    .cloud1::before { width: 60px; height: 60px; left: -30px; top: -20px; }
    .cloud1::after { width: 80px; height: 80px; left: 50px; top: -40px; }

    .cloud2 { width: 180px; height: 80px; top: 30%; left: -200px; animation-duration: 100s; }
    .cloud2::before { width: 80px; height: 80px; left: -40px; top: -20px; }
    .cloud2::after { width: 100px; height: 100px; left: 60px; top: -30px; }

    .cloud3 { width: 150px; height: 70px; top: 60%; left: -180px; animation-duration: 120s; }
    .cloud3::before { width: 70px; height: 70px; left: -35px; top: -25px; }
    .cloud3::after { width: 90px; height: 90px; left: 60px; top: -35px; }

    @keyframes float {
        from { transform: translateX(0); }
        to { transform: translateX(120vw); }
    }

    .title {
        font-size: 2rem;
        color: var(--blue-600);
        margin: 40px 0 20px;
        text-align: center;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .content-wrapper {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: flex-start;
    gap: 40px;
    flex-wrap: wrap;
    z-index: 1;
    margin-left: 160px; /* เดิม 60px → ลดให้ใกล้ซ้ายมากขึ้น */
    width: 100%;
    max-width: 1200px;
}

.student {
    width: 280px;
    max-width: 40%;
    animation: bounce 2s ease-in-out infinite;
    margin-left: 0; /* ชิดซ้ายสุด */
}

    form {
        background: #FFFFFF;
        color: var(--blue-800);
        padding: 30px 40px;
        border-radius: 15px;
        border: 2px solid var(--blue-300);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        width: 360px;
        max-width: 90%;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    form:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.15);
    }

    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
    }

    .custom-select {
        position: relative;
        width: 100%;
        margin-bottom: 15px;
    }

    .custom-select select {
        width: 100%;
        padding: 10px 40px 10px 12px;
        border-radius: 8px;
        border: 2px solid var(--blue-500);
        font-size: 1rem;
        color: var(--blue-800);
        background: #FFFFFF;
        appearance: none;
        cursor: pointer;
        transition: 0.3s;
    }

    .custom-select select:hover {
        border-color: var(--blue-600);
        box-shadow: 0 0 5px rgba(37,99,235,0.5);
    }

    .custom-select::after {
        content: '▼';
        font-size: 0.7rem;
        color: var(--blue-800);
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .submit-btn {
        margin-top: 16px;
        width: 60%;
        padding: 12px;
        border: none;
        border-radius: 10px;
        background: var(--blue-500);
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: 0.2s;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .submit-btn:hover {
        background: var(--blue-600);
        transform: translateY(-2px);
    }

    .submit-btn:active {
        transform: translateY(1px);
    }

    .back-btn {
        margin-top: 20px;
        padding: 10px 20px;
        border-radius: 8px;
        border: 2px solid var(--blue-500);
        background: #FFFFFF;
        color: var(--blue-600);
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .back-btn:hover {
        background: var(--blue-100);
        transform: translateY(-2px);
    }

    .student-img {
        max-width: 250px;
        height: auto;
    }

    @media (max-width: 600px) {
        .content-wrapper {
            flex-direction: column;
        }
        .student-img {
            max-width: 180px;
        }
    }
</style>
<script>
    function updateSubjects() {
        const year = document.getElementById('year').value;
        const subjectSelect = document.getElementById('subject');
        subjectSelect.innerHTML = '';

        const subjects = {
            '3': ['ระเบียบวิธีวิจัย'],
            '4': ['ระบบเครือข่ายคอมพิวเตอร์เพื่อการสื่อสารข้อมูล','เศรษฐศาสตร์สาธารณสุขและการประกันสุขภาพ']
        };

        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.text = '-- เลือกวิชา --';
        subjectSelect.appendChild(defaultOption);

        if (subjects[year]) {
            subjects[year].forEach(subj => {
                const option = document.createElement('option');
                option.value = subj;
                option.text = subj;
                subjectSelect.appendChild(option);
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('courseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const course = document.getElementById('course').value;
            const year = document.getElementById('year').value;
            const subject = document.getElementById('subject').value;

            if (course === 'bscmrs' && year === '4' && subject === 'ระบบเครือข่ายคอมพิวเตอร์เพื่อการสื่อสารข้อมูล') {
                window.location.href = 'testnew.php';
            } else if (course === 'bscmrs' && year === '3' && subject === 'ระเบียบวิธีวิจัย') {
                window.location.href = 'testoldresearch.php';
            } else if (course === 'bscmrs' && year === '4' && subject === 'เศรษฐศาสตร์สาธารณสุขและการประกันสุขภาพ') {
                window.location.href = 'testnewmoney.php';
            } else {
                alert('กรุณาเลือกหลักสูตร ปี และวิชาให้ถูกต้อง');
            }
        });
    });
</script>
</head>
<body>
    <!-- ☁️ ก้อนเมฆ -->
    <div class="cloud cloud1"></div>
    <div class="cloud cloud2"></div>
    <div class="cloud cloud3"></div>

    <h1 class="title">ระบบผลการเข้าเรียน</h1>

    <div class="content-wrapper">
        <!-- รูปเด็กผู้ชาย ซ้าย -->
        <img src="student_cutout.png" alt="เด็กผู้ชาย" class="student-img">

        <!-- ฟอร์ม ขวา -->
        <form id="courseForm">
            <label for="course">หลักสูตร:</label>
            <div class="custom-select">
                <select id="course" name="course">
                    <option value="">-- เลือกหลักสูตร --</option>
                    <option value="bscmrs">วิทยาศาสตรบัณฑิต สาขาวิชาเวชระเบียน</option>
                </select>
            </div>

            <label for="year">ชั้นปี:</label>
            <div class="custom-select">
                <select id="year" name="year" onchange="updateSubjects()">
                    <option value="">-- เลือกชั้นปี --</option>
                    <option value="3">ปี 3</option>
                    <option value="4">ปี 4</option>
                </select>
            </div>

            <label for="subject">วิชา:</label>
            <div class="custom-select">
                <select id="subject" name="subject">
                    <option value="">-- เลือกวิชา --</option>
                </select>
            </div>

            <button type="submit" class="submit-btn">ตกลง</button>
        </form>
    </div>

    <button type="button" class="back-btn" onclick="window.location.href='index.php'">← กลับหน้าแรก</button>
</body>
</html>
