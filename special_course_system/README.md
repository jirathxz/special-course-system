# ระบบขอเปิดหมู่เรียนพิเศษ (Special Course Request System)

ระบบเว็บสำหรับนักศึกษายื่นคำร้องขอเปิดหมู่เรียนพิเศษ และให้เจ้าหน้าที่พิจารณาอนุมัติ/ไม่อนุมัติ
พร้อมติดตามสถานะคำร้องออนไลน์

- PHP 8.1+ (ทดสอบบน PHP 8.5)
- MySQL 8.0+ / MariaDB 10.6+
- Bootstrap 5 (CDN) + Font Awesome + ฟอนต์ Sarabun
- ไม่ใช้ framework — PHP + PDO ล้วน

## ฟีเจอร์

| ฝั่งนักศึกษา | ฝั่งเจ้าหน้าที่ |
|---|---|
| ยื่นคำร้อง (ตรวจสิทธิ์ + ตรวจวิชาในตารางปกติ + ตรวจรหัสวิชาผ่าน AJAX) | เข้าสู่ระบบ (session) |
| ติดตามสถานะด้วยหมายเลขคำร้อง (timeline) | Dashboard สรุปจำนวนตามสถานะ |
| ยกเลิกคำร้องที่ยังไม่ถูกพิจารณา | กรอง/ค้นหารายการคำร้อง |
| | อนุมัติ / ไม่อนุมัติ พร้อมกำหนดอาจารย์ผู้สอน |

## การติดตั้ง

1. **คัดลอกโฟลเดอร์** ไปไว้ใต้ document root เช่น
   - XAMPP: `C:\xampp\htdocs\special_course_system`
   - Laragon: `C:\laragon\www\special_course_system`

2. **สร้างฐานข้อมูลและ import schema** (สร้าง DB ให้อัตโนมัติ + ข้อมูลตัวอย่าง)

   ```bash
   mysql -u root -p < database_schema.sql
   ```

   หรือ import ผ่าน phpMyAdmin ก็ได้ (ไฟล์มี `CREATE DATABASE` และ seed data อยู่ในตัว)

3. **ตั้งค่าการเชื่อมต่อ** ที่ `config/database.php` (ค่าเริ่มต้น: host `localhost`, user `root`, ไม่มีรหัสผ่าน)

   หรือใช้ environment variable: `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`

4. **เปิดเว็บ** ที่ `http://localhost/special_course_system/`

   > ถ้าใช้ PHP built-in server: `php -S localhost:8000` แล้วเปิด `http://localhost:8000`

## บัญชีเจ้าหน้าที่

ค่าเริ่มต้น (สำหรับพัฒนาเท่านั้น — **ต้องเปลี่ยนก่อนใช้งานจริง**):

```
username: admin
password: admin1234
```

เปลี่ยนได้ด้วย environment variable `ADMIN_USER` / `ADMIN_PASS`

## ข้อมูลตัวอย่าง (seed)

| รหัสนักศึกษา | ชื่อ | ชั้นปี | ประเภท | หมายเหตุ |
|---|---|---|---|---|
| 64010001 | นายสมชาย ใจดี | 4 | ปกติ | ลงทะเบียน MA201 ภาค 1/2567 แล้ว → ขอ MA201 ไม่ได้ |
| 64010002 | นางสาวสมหญิง รักเรียน | 4 | ปกติ | ยื่นคำร้องได้ |
| 65020001 | นายทดสอบ ภาคค่ำ | 2 | ภาคค่ำ | ยื่นได้เพราะเป็นภาคค่ำ |
| 63030001 | นางสาวปีสาม ตัวอย่าง | 3 | ปกติ | ถูกปฏิเสธเพราะยังไม่ถึงปี 4 |

รายวิชา: `CS401`, `CS402`, `MA201`

## โครงสร้างโปรเจกต์

```
special_course_system/
├── index.php                  หน้าแรก
├── submit_request.php         ฟอร์มยื่นคำร้อง
├── track_request.php          ติดตามสถานะ
├── cancel_request.php         ยกเลิกคำร้อง
├── database_schema.sql        schema + view + seed data
├── api/
│   └── check_course.php       JSON API ตรวจรหัสวิชา
├── admin/
│   ├── login.php / logout.php ระบบเข้าสู่ระบบเจ้าหน้าที่
│   ├── dashboard.php          สรุปภาพรวม
│   ├── manage_requests.php    รายการ + filter
│   └── approve_request.php    พิจารณาคำร้อง
├── classes/                   Database, Request, Course, Student, Logger
├── config/database.php        ค่าเชื่อมต่อฐานข้อมูล
├── includes/                  functions, header, footer
└── assets/                    css, js
```

## สถานะคำร้อง

`PENDING (1)` → `REVIEWING (2)` → `APPROVED (3)` / `REJECTED (4)`
และ `CANCELLED (5)` เมื่อนักศึกษายกเลิกก่อนถูกพิจารณา

## หมายเหตุด้านความปลอดภัย

- ทุก query ใช้ prepared statement (PDO)
- ฟอร์ม POST ทุกตัวมี CSRF token
- escape ข้อมูลด้วย `htmlspecialchars()` ที่จุดแสดงผลเท่านั้น
- `config/`, `classes/`, `includes/` ถูกบล็อกจาก HTTP ด้วย `.htaccess` (ต้องเปิด `AllowOverride`)
- รหัสผ่าน admin เก็บใน environment variable — ในระบบจริงควรเปลี่ยนไปเก็บเป็น hash ในตาราง users
