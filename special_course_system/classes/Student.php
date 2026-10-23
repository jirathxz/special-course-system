<?php
/**
 * Student — จัดการข้อมูลนักศึกษา
 */
class Student
{
    private PDO $conn;
    private const TABLE = 'students';

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    /**
     * ค้นหานักศึกษาด้วยรหัสนักศึกษา
     *
     * @return array<string, mixed>|false
     */
    public function findById(string $studentId): array|false
    {
        $stmt = $this->conn->prepare(
            'SELECT * FROM ' . self::TABLE . ' WHERE student_id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $studentId]);
        return $stmt->fetch();
    }

    /**
     * ค้นหาด้วยอีเมล
     *
     * @return array<string, mixed>|false
     */
    public function findByEmail(string $email): array|false
    {
        $stmt = $this->conn->prepare(
            'SELECT * FROM ' . self::TABLE . ' WHERE student_email = :email LIMIT 1'
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    /**
     * ตรวจสอบว่านักศึกษามีสิทธิ์ขอเปิดหมู่เรียนพิเศษหรือไม่
     * เงื่อนไข: นักศึกษาปี 4 ขึ้นไป หรือประเภท evening/weekend
     */
    public function isEligibleForSpecialCourse(string $studentId): bool
    {
        $student = $this->findById($studentId);
        if (!$student) {
            return false;
        }
        return $student['study_year'] >= 4
            || in_array($student['student_type'], ['evening', 'weekend'], true);
    }

    /**
     * รายชื่อนักศึกษาทั้งหมด (สำหรับ Admin)
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        return $this->conn
            ->query('SELECT * FROM ' . self::TABLE . ' ORDER BY student_id')
            ->fetchAll();
    }
}
