<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ตารางเรียน</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(180deg, #DBEAFE, #FFFFFF);
        margin: 0;
        padding: 40px;
        color: #1E3A8A;
    }

    h1 {
        text-align: center;
        color: #1E40AF;
        margin-bottom: 40px;
        font-size: 2rem;
    }

    form {
        max-width: 500px;
        margin: 0 auto 30px auto;
        background: #FFFFFF;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }

    form:hover {
        transform: translateY(-5px);
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #1E3A8A;
    }

    select {
        width: 100%;
        padding: 10px 40px 10px 12px;
        border-radius: 8px;
        border: 2px solid #3B82F6;
        font-size: 1rem;
        color: #1E3A8A;
        background: #ffffff url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 20 20"><polygon fill="%233B82F6" points="0,0 20,0 10,10"/></svg>') no-repeat right 12px center;
        background-size: 12px;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        cursor: pointer;
        transition: 0.3s;
        margin-bottom: 20px;
    }

    select:hover {
        border-color: #2563EB;
        box-shadow: 0 0 5px rgba(37,99,235,0.5);
    }

    #image-container {
        text-align: center;
        margin-bottom: 30px;
        opacity: 0;
        transform: translateY(-20px);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    #image-container img {
        max-width: 100%;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    }

    .back-btn {
        display: block;
        margin: 0 auto;
        padding: 10px 22px;
        font-size: 1rem;
        color: #FFFFFF;
        background: #3B82F6;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s, transform 0.2s;
    }

    .back-btn:hover {
        background: #2563EB;
        transform: translateY(-2px);
    }

    .back-btn:active {
        transform: translateY(1px);
    }
</style>
</head>
<body>
<h1>ตารางเรียน</h1>

<form>
    <label for="course">หลักสูตร:</label>
    <select id="course" name="course" onchange="showImage()">
        <option value="">-- เลือกหลักสูตร --</option>
        <option value="วิทยาศาสตรบัณฑิต สาขาวิชาเวชระเบียน">วิทยาศาสตรบัณฑิต สาขาวิชาเวชระเบียน</option>
    </select>

    <label for="year">ชั้นปี:</label>
    <select id="year" name="year" onchange="showImage()">
        <option value="">-- เลือกชั้นปี --</option>
        <option value="3">ปี 3</option>
        <option value="4">ปี 4</option>
    </select>
</form>

<div id="image-container"></div>

<button class="back-btn" onclick="window.location.href='index.php'; return false;">← กลับหน้าแรก</button>

<script>
function showImage() {
    const course = document.getElementById('course').value;
    const year = document.getElementById('year').value;
    const container = document.getElementById('image-container');

    if (course === 'วิทยาศาสตรบัณฑิต สาขาวิชาเวชระเบียน') {
        if (year === '3') {
            container.innerHTML = '<img src="schedule03v2.jpg" alt="ตารางเรียน ปี 3">';
        } else if (year === '4') {
            container.innerHTML = '<img src="schedule04v2.jpg" alt="ตารางเรียน ปี 4">';
        } else {
            container.innerHTML = '';
        }
    } else {
        container.innerHTML = '';
    }

    // reset animation
    container.style.opacity = 0;
    container.style.transform = 'translateY(-20px)';

    setTimeout(() => { 
        container.style.opacity = 1;
        container.style.transform = 'translateY(0)';

        // scroll ลงไปยังรูปภาพ
        if(container.innerHTML.trim() !== '') {
            container.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }, 50);
}
</script>
</body>
</html>
