<?php
require 'config/koneksi.php'; wajibLogin();
$judul = 'Dashboard';$namaUser = $_SESSION['nama_tampil'] ?? getNamaPanggilan($_SESSION['username'] ?? 'User');

$kartu = [
    ['Barang aktif',  "SELECT COUNT(*) FROM barang WHERE status = 1", 'bi-box-seam',   'success'],
    ['Satuan',        "SELECT COUNT(*) FROM satuan",                  'bi-box',       'primary'],
    ['Vendor aktif',  "SELECT COUNT(*) FROM vendor WHERE status='A'", 'bi-truck',     'warning'],
    ['User',          "SELECT COUNT(*) FROM `user`",                  'bi-people',    'info'],
];
$margin =$pdo->query("SELECT persen FROM margin_penjualan WHERE status = 1 LIMIT 1")->fetchColumn();
include 'includes/header.php';
?>
<h4 class="mb-3">Selamat datang, <?= e($namaUser) ?> 👋</h4>
<div class="row g-3 mb-4">
  <?php foreach ($kartu as [$label,$sql, $ikon,$warna]): ?>
    <div class="col-6 col-md-3"><div class="card p-3">
      <div class="text-<?= $warna ?> fs-3"><i class="bi <?= $ikon ?>"></i></div>
      <div class="fs-3 fw-bold"><?= $pdo->query($sql)->fetchColumn() ?></div>
      <div class="text-muted"><?= $label ?></div>
    </div></div>
  <?php endforeach; ?>
</div>
<div class="card p-3">
  <strong>Margin penjualan aktif:</strong>
  <span class="fs-4 text-success"><?= $margin !== false ? e($margin) . '%' : 'belum diatur' ?></span>
</div>
<?php include 'includes/footer.php'; ?>