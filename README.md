# Week 6 — ระบบขอเปิดหมู่เรียนพิเศษ

โปรเจกต์รายวิชา: ระบบยื่นคำร้องขอเปิดหมู่เรียนพิเศษ (PHP + MySQL)

## เนื้อหาใน repo

| ไฟล์ / โฟลเดอร์ | คำอธิบาย |
|---|---|
| `special_course_system/` | โค้ดระบบที่ใช้งานได้จริง — ดูวิธีติดตั้งใน [`special_course_system/README.md`](special_course_system/README.md) |
| `special_class_system.html` | เอกสารออกแบบระบบ (HCI / UI) |
| `special_course_system_diagrams.html` | ไดอะแกรมระบบ (architecture, ER, sequence, state) |

## เริ่มใช้งานเร็ว

```bash
cd special_course_system
mysql -u root -p < database_schema.sql     # สร้าง DB + seed data
php -S localhost:8000                      # หรือวางใต้ htdocs ของ XAMPP/Laragon
```

- นักศึกษา: ยื่นคำร้อง / ติดตามสถานะ / ยกเลิกคำร้อง
- เจ้าหน้าที่: `admin/login.php` (ค่าเริ่มต้น `admin` / `admin1234` — เปลี่ยนก่อนใช้จริง)

ข้อมูลทดสอบและรายละเอียดทั้งหมดอยู่ใน `special_course_system/README.md`
