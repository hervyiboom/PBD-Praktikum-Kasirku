<?php
$userNama = $_SESSION['nama_tampil'] ?? getNamaPanggilan($_SESSION['username'] ?? 'User');
$userRole = $_SESSION['role'] ?? 'User';
$currentPage = basename($_SERVER['PHP_SELF']);
$isAdmin = in_array(strtolower($userRole), ['super admin', 'admin', 'administrator']);
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($judul ?? 'Dashboard') ?> - <?= e($namaApp) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
  body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #334155; }
  .navbar-brand { font-weight: 700; color: #1b7f4f !important; }
  .nav-link { font-weight: 500; color: #64748b; }
  .nav-link:hover, .nav-link.active { color: #1b7f4f !important; font-weight: 600; }
  .card { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
  .btn-success { background-color: #1b7f4f; border-color: #1b7f4f; }
  .btn-success:hover { background-color: #14623d; border-color: #14623d; }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-2 mb-4 sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
      <i class="bi bi-shop fs-4"></i> <?= e($namaApp) ?>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>" href="index.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'barang.php' ? 'active' : '' ?>" href="barang.php">Barang</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'satuan.php' ? 'active' : '' ?>" href="satuan.php">Satuan</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'vendor.php' ? 'active' : '' ?>" href="vendor.php">Vendor</a></li>
        <li class="nav-item"><a class="nav-link <?= $currentPage == 'margin.php' ? 'active' : '' ?>" href="margin.php">Margin</a></li>
        <?php if ($isAdmin): ?>
          <li class="nav-item"><a class="nav-link <?= $currentPage == 'user.php' ? 'active' : '' ?>" href="user.php">User</a></li>
          <li class="nav-item"><a class="nav-link <?= $currentPage == 'role.php' ? 'active' : '' ?>" href="role.php">Role</a></li>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center gap-3">
        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
          <i class="bi bi-person-circle me-1" style="color: #1b7f4f;"></i> <?= e($userNama) ?> (<strong><?= e($userRole) ?></strong>)
        </span>
        <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">
          <i class="bi bi-box-arrow-right"></i> Keluar
        </a>
      </div>
    </div>
  </div>
</nav>

<div class="container mb-5">
  <?php if ($flashMessage = flash()): ?>
    <div class="alert alert-<?= $flashMessage['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show rounded-3 mb-4" role="alert">
      <?= e($flashMessage['message']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>