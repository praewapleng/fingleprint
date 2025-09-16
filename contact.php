<?php
// contact_us.php
// Simple contact window with tel:, Gmail Compose links, maps links 

// --- Data ---
$people = [
    1 => [
        'first'   => 'นางสาวแพรวา',
        'last'    => 'หาจำปา',
        'phone'   => '+66956549568',
        'email'   => 'bsc65praewa.haj@kmpht.ac.th',
        'address' => 'วิทยาลัยเทคโนโลยีทางการแพทย์และสาธารณสุข กาญจนาภิเษก 56 หมู่ 1 ถนน คลองขวาง - เจ้าเฟื่อง ตำบล ราษฎร์นิยม อำเภอ ไทรน้อย จังหวัด นนทบุรี 11150',
        'note'    => 'นักศึกษา สาขาวิชาเวชระเบียน ชั้นปีที่ 4',
        'photo'   => 'praewa.jpg'
    ],
    2 => [
        'first'   => 'นางสาวสุปรารี',
        'last'    => 'ขันธวิเศษ',
        'phone'   => '+66885578678',
        'email'   => 'bsc65sopari.kan@kmpht.ac.th',
        'address' => 'วิทยาลัยเทคโนโลยีทางการแพทย์และสาธารณสุข กาญจนาภิเษก 56 หมู่ 1 ถนน คลองขวาง - เจ้าเฟื่อง ตำบล ราษฎร์นิยม อำเภอ ไทรน้อย จังหวัด นนทบุรี 11150',
        'note'    => 'นักศึกษา สาขาวิชาเวชระเบียน ชั้นปีที่ 4',
        'photo'   => 'supari.jpg'
    ]
];
// ---------------------------------------------------

// Google Maps URL
function maps_search_url($address) {
    return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($address);
}
?>
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>ติดต่อเรา</title>
<!-- Google Font Itim -->
<link href="https://fonts.googleapis.com/css2?family=Itim&display=swap" rel="stylesheet">
<style>
    :root{
        --bg:#e0f2ff;
        --card:#ffffffcc;
        --accent:#2b6cb0;
        --muted:#6b7280;
        --radius:12px;
    }
    body{
        font-family: 'Itim', cursive;
        background: linear-gradient(180deg, #e0f2ff, #f0f7ff 70%);
        margin:0;
        padding:32px;
        color:#111827;
    }
    .container{
        max-width:900px;
        margin:0 auto;
    }
    h1{margin:0 0 18px;font-size:1.6rem}
    .grid{
        display:grid;
        grid-template-columns: repeat(auto-fit,minmax(280px,1fr));
        gap:18px;
    }
    .card{
        background: var(--card);
        border-radius: var(--radius);
        padding:18px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        border:1px solid rgba(15,23,42,0.08);
        text-align:center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover{
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.12);
    }
    .card-avatar{
        margin-bottom:12px;
    }
    .avatar{
        width:140px;
        height:140px;
        border-radius:50%;
        background:linear-gradient(135deg,var(--accent),#4c9bd8);
        color:white;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:700;
        font-size:1.6rem;
        overflow:hidden;
        margin:0 auto;
        border:4px solid #fff;
        box-shadow:0 4px 12px rgba(0,0,0,0.15);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: pointer;
    }
    .avatar img{
        width:100%;
        height:100%;
        object-fit:cover;
    }
    .avatar:hover{
        transform: scale(1.08);
        box-shadow:0 6px 20px rgba(0,0,0,0.25);
    }
    .avatar:active{
        transform: scale(0.95);
        box-shadow:0 3px 8px rgba(0,0,0,0.2);
    }
    .fullname{font-size:1.05rem;font-weight:700}
    .role{font-size:0.92rem;color:var(--muted);margin-bottom:8px}
    .actions{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px;justify-content:center}
    .btn{
        display:inline-flex;align-items:center;gap:8px;
        padding:8px 10px;border-radius:10px;border:1px solid rgba(15,23,42,0.06);
        background:white;text-decoration:none;color:inherit;font-size:0.95rem;
        cursor:pointer;
    }
    .btn:active{transform:translateY(1px)}
    .btn.primary{
        background:var(--accent);color:white;border:none;
        box-shadow: 0 6px 14px rgba(43,108,176,0.12);
    }
    .meta{font-size:0.92rem;color:var(--muted);margin-top:10px;text-align:left}
    .meta a{color:inherit;text-decoration:none}
    footer{margin-top:18px;color:var(--muted);font-size:0.9rem;text-align:center}
</style>
</head>
<body>
<div class="container">
    <h1>ติดต่อเรา</h1>
    <p style="color:var(--muted);margin-top:6px;margin-bottom:18px;">
        คลิกที่รูปเพื่อเปิดที่อยู่บน Google Maps หรือใช้ลิงก์ด้านล่างเพื่อโทร ส่งอีเมล 
    </p>

    <div class="grid">
        <?php foreach ($people as $id => $p): 
            $initials = mb_substr($p['first'],0,1) . mb_substr($p['last'],0,1);
            $tel_link = 'tel:' . preg_replace('/[^+0-9]/','',$p['phone']);
            $mail_link = 'https://mail.google.com/mail/?view=cm&fs=1&to=' . rawurlencode($p['email']);
            $maps = maps_search_url($p['address']);
        ?>
        <article class="card">
            <div class="card-avatar">
                <a href="<?php echo $maps; ?>" target="_blank" rel="noopener noreferrer" 
                   aria-label="เปิดที่อยู่ของ <?php echo htmlspecialchars($p['first']); ?>">
                    <?php if (!empty($p['photo'])): ?>
                        <div class="avatar">
                            <img src="<?php echo htmlspecialchars($p['photo']); ?>" 
                                 alt="รูปของ <?php echo htmlspecialchars($p['first']); ?>">
                        </div>
                    <?php else: ?>
                        <div class="avatar"><?php echo htmlspecialchars($initials); ?></div>
                    <?php endif; ?>
                </a>
            </div>
            <div class="fullname"><?php echo htmlspecialchars($p['first'] . ' ' . $p['last']); ?></div>
            <div class="role"><?php echo htmlspecialchars($p['note']); ?></div>

            <div class="meta">
                <div><strong>เบอร์โทร:</strong> <a href="<?php echo $tel_link; ?>"><?php echo htmlspecialchars($p['phone']); ?></a></div>
                <div style="margin-top:6px;">
                    <strong>อีเมล:</strong> 
                    <a href="<?php echo $mail_link; ?>" target="_blank" rel="noopener noreferrer">
                        <?php echo htmlspecialchars($p['email']); ?>
                    </a>
                </div>
                <div style="margin-top:6px;"><strong>ที่อยู่:</strong> <a href="<?php echo $maps; ?>" target="_blank"><?php echo htmlspecialchars($p['address']); ?></a></div>
            </div>

            <div class="actions">
                <a class="btn primary" href="<?php echo $tel_link; ?>">โทรตอนนี้</a>
                <a class="btn" href="<?php echo $mail_link; ?>" target="_blank" rel="noopener noreferrer">ส่งอีเมล</a>
                <a class="btn" href="<?php echo $maps; ?>" target="_blank">เปิดในแผนที่</a>
            </div>
        </article>
        <?php endforeach; ?>
    </div>

    <footer>
        <p>หมายเหตุ: ลิงก์ <code>tel:</code> จะทำงานบนอุปกรณ์/เบราว์เซอร์ที่รองรับ</p>
    </footer>
</div>

<div style="text-align:center;margin-top:28px;">
    <a href="index.php" class="btn primary" style="font-size:1.05rem;padding:10px 22px;">
        ← กลับหน้าแรก
    </a>
</div>
</body>
</html>
