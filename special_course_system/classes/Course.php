<?php
/**
 * Course — จัดการข้อมูลรายวิชา
 */
class Course
{
    private PDO $conn;
    private const TABLE = 'courses';

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    /**
     * ค้นหาวิชาด้วยรหัสวิชา
     *
     * @return array<string, mixed>|false
     */
    public function findByCode(string $courseCode): array|false
    {
        $stmt = $this->conn->prepare(
            'SELECT * FROM ' . self::TABLE . ' WHERE course_code = :code AND status = "active" LIMIT 1'
        );
        $stmt->execute([':code' => $courseCode]);
        return $stmt->fetch();
    }

    /**
     * ค้นหาวิชาด้วยชื่อ (ภาษาไทย)
     *
     * @return array<int, array<string, mixed>>
     */
    public function searchByName(string $keyword): array
    {
        $stmt = $this->conn->prepare(
            'SELECT * FROM ' . self::TABLE . '
              WHERE status = "active"
                AND (course_name_th LIKE :kw OR course_code LIKE :kw)
              ORDER BY course_code
              LIMIT 50'
        );
        $stmt->execute([':kw' => '%' . $keyword . '%']);
        return $stmt->fetchAll();
    }

    /**
     * ตรวจสอบว่าวิชานี้มีในตารางสอนปกติของภาคเรียนนั้นหรือไม่
     * ใช้ตาราง enrollments (import มาจากระบบทะเบียนของมหาวิทยาลัย)
     */
    public function isInRegularSchedule(
        string $studentId,
        string $courseCode,
        int    $semester,
        int    $academicYear
    ): bool {
        try {
            $stmt = $this->conn->prepare(
                'SELECT COUNT(*) AS cnt
                   FROM enrollments
                  WHERE student_id    = :sid
                    AND course_code   = :code
                    AND semester      = :sem
                    AND academic_year = :year
                    AND status        = :st'
            );
            $stmt->execute([
                ':sid'  => $studentId,
                ':code' => $courseCode,
                ':sem'  => $semester,
                ':year' => $academicYear,
                ':st'   => 'enrolled',
            ]);
            return (int)$stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            // ตาราง enrollments ยังไม่ถูก import → ถือว่าไม่มีในตารางปกติ แต่ต้องเห็นใน log
            error_log('[Course::isInRegularSchedule] ' . $e->getMessage());
            return false;
        }
    }

    /**
     * รายวิชาทั้งหมดที่ active
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        return $this->conn
            ->query(
                'SELECT * FROM ' . self::TABLE . '
                  WHERE status = "active"
                  ORDER BY course_code'
            )
            ->fetchAll();
    }
}
