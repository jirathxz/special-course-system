<?php
/**
 * Common Header — ใช้กับทุกหน้า
 * ต้องกำหนด $pageTitle ก่อน include
 */
checkSession();
$pageTitle = $pageTitle ?? 'ระบบขอเปิดหมู่เรียนพิเศษ';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | ระบบขอเปิดหมู่เรียนพิเศษ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url('index.php') ?>">
            <i class="fas fa-graduation-cap me-2"></i>ขอเปิดหมู่เรียนพิเศษ
        </a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="<?= base_url('submit_request.php') ?>">
                <i class="fas fa-plus"></i> ยื่นคำร้อง
            </a>
            <a class="nav-link" href="<?= base_url('track_request.php') ?>">
                <i class="fas fa-search"></i> ติดตามสถานะ
            </a>
            <?php if (isAdmin()): ?>
            <a class="nav-link" href="<?= base_url('admin/dashboard.php') ?>">
                <i class="fas fa-tachometer-alt"></i> จัดการคำร้อง
            </a>
            <a class="nav-link" href="<?= base_url('admin/logout.php') ?>">
                <i class="fas fa-sign-out-alt"></i> ออกจากระบบ
            </a>
            <?php else: ?>
            <a class="nav-link" href="<?= base_url('admin/login.php') ?>">
                <i class="fas fa-user-shield"></i> เจ้าหน้าที่
            </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<main class="py-4">
<div class="container">
<?= showFlash() ?>
