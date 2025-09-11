CREATE DATABASE fingerprint_attendance CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE fingerprint_attendance;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,  -- เก็บรหัสผ่านแบบเข้ารหัส (hash)
    role ENUM('teacher','student') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- เพิ่มผู้ใช้ตัวอย่าง (password = 123456)
INSERT INTO users (username, password, usertype) 
VALUES ('teacher01', MD5('123456'), 'teacher'),
       ('student01', MD5('123456'), 'student');

