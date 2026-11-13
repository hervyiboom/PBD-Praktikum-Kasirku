<?php
require_once 'config/koneksi.php';
wajibAdmin(); // Hanya admin yang bisa buka

$fileLog = __DIR__ . '/log_aktivitas.txt';$isiLog = file_exists($fileLog) ? file_get_contents($fileLog) : 'Belum ada catatan log aktivitas.';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Log Aktivitas - <?= e($namaApp) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold text-success"><i class="bi bi-journal-text me-2"></i>Log Aktivitas Sistem</h4>
    <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      <label class="form-label text-muted small">Catatan Real-Time (`log_aktivitas.txt`):</label>
      <textarea class="form-control font-monospace bg-dark text-light p-3" rows="18" readonly style="font-size: 13px; resize: vertical;"><?= e($isiLog) ?></textarea>
    </div>
  </div>
</div>

</body>
</html>