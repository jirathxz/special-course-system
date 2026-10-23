<?php
/**
 * Admin — เข้าสู่ระบบเจ้าหน้าที่
 *
 * บัญชีกำหนดผ่าน Environment Variable ADMIN_USER / ADMIN_PASS
 * (ค่าเริ่มต้นสำหรับพัฒนา: admin / admin1234 — ต้องเปลี่ยนก่อนใช้งานจริง)
 */
require_once __DIR__ . '/../includes/functions.php';

checkSession();

if (isAdmin()) {
    redirect('/admin/dashboard.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken();

    $username = cleanInput($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    $creds    = adminCredentials();

    if ($username !== '' && hash_equals($creds['user'], $username)
        && hash_equals($creds['pass'], $password)) {
        session_regenerate_id(true);       // ป้องกัน session fixation
        $_SESSION['role']      = 'admin';
        $_SESSION['user_name'] = $username;
        redirect('/admin/dashboard.php', 'เข้าสู่ระบบเรียบร้อย', 'success');
    }

    $errors[] = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
}

$pageTitle = 'เข้าสู่ระบบเจ้าหน้าที่';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user-shield me-2"></i>เข้าสู่ระบบเจ้าหน้าที่</h5>
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

                <form method="POST" action="">
                    <?= csrfField() ?>

                    <div class="mb-3">
                        <label for="username" class="form-label required">ชื่อผู้ใช้</label>
                        <input type="text" class="form-control" id="username" name="username"
                               required autofocus autocomplete="username">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label required">รหัสผ่าน</label>
                        <input type="password" class="form-control" id="password" name="password"
                               required autocomplete="current-password">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-sign-in-alt me-1"></i>เข้าสู่ระบบ
                    </button>
                </form>

                <p class="small text-muted mt-3 mb-0">
                    สำหรับเจ้าหน้าที่ผู้พิจารณาคำร้องเท่านั้น
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
