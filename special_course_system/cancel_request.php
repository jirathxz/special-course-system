<?php
/**
 * หน้ายกเลิกคำร้อง (นักศึกษา)
 * ยกเลิกได้เฉพาะสถานะรอดำเนินการ / กำลังตรวจสอบ
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Logger.php';
require_once __DIR__ . '/classes/Request.php';
require_once __DIR__ . '/includes/functions.php';

checkSession();

$requestNumber = cleanInput($_GET['request_number'] ?? '');
$errors        = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken();

    $requestNumber = cleanInput($_POST['request_number'] ?? '');

    if ($requestNumber === '') {
        $errors[] = 'กรุณากรอกหมายเลขคำร้อง';
    } else {
        $reqModel = new Request();
        $result   = $reqModel->cancelByNumber($requestNumber);

        if ($result['success']) {
            redirect(
                '/track_request.php?request_number=' . urlencode($requestNumber),
                $result['message'],
                'success'
            );
        }
        $errors[] = $result['message'];
    }
}

$pageTitle = 'ยกเลิกคำร้อง';
require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-ban me-2"></i>ยกเลิกคำร้อง</h5>
            </div>
            <div class="card-body p-4">

                <?php if ($errors): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <div class="alert alert-warning small">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    ยกเลิกได้เฉพาะคำร้องที่ยัง <strong>รอดำเนินการ</strong> หรือ
                    <strong>กำลังตรวจสอบ</strong> เท่านั้น และไม่สามารถย้อนกลับได้
                </div>

                <form method="POST" action=""
                      onsubmit="return confirm('ยืนยันการยกเลิกคำร้องนี้?');">
                    <?= csrfField() ?>

                    <div class="mb-3">
                        <label for="request_number" class="form-label required">หมายเลขคำร้อง</label>
                        <input type="text" class="form-control" id="request_number" name="request_number"
                               placeholder="เช่น SCR-671-0001" required
                               value="<?= htmlspecialchars($requestNumber, ENT_QUOTES, 'UTF-8') ?>">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-ban me-1"></i>ยืนยันการยกเลิก
                        </button>
                        <a href="<?= base_url('track_request.php') ?>" class="btn btn-outline-secondary">
                            ย้อนกลับ
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
