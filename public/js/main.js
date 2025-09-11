document.getElementById("loginForm").addEventListener("submit", function(e) {
    let username = document.getElementById("username").value.trim();
    let password = document.getElementById("password").value.trim();

    if (username === "" || password === "") {
        e.preventDefault();
        alert("⚠️ กรุณากรอกชื่อผู้ใช้และรหัสผ่านให้ครบถ้วน");
    }
});
