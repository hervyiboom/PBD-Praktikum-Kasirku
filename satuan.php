<?php
require 'config/koneksi.php'; wajibLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    if ($aksi === 'tambah') {
        jalankan(fn() => $pdo->prepare("INSERT INTO satuan (idsatuan, nama_satuan, status) VALUES (?, ?, ?)")
            ->execute([idBerikut('satuan', 'idsatuan'), trim($_POST['nama_satuan']), $_POST['status']]), 'Satuan berhasil ditambahkan.');
    } elseif ($aksi === 'ubah') {
        jalankan(fn() => $pdo->prepare("UPDATE satuan SET nama_satuan = ?, status = ? WHERE idsatuan = ?")
            ->execute([trim($_POST['nama_satuan']), $_POST['status'], $_POST['id']]), 'Satuan berhasil diubah.');
    } elseif ($aksi === 'hapus') {
        jalankan(fn() => $pdo->prepare("DELETE FROM satuan WHERE idsatuan = ?")
            ->execute([$_POST['id']]), 'Satuan berhasil dihapus.');
    }
    header('Location: satuan.php'); exit;
}

$data  = $pdo->query("SELECT * FROM satuan ORDER BY idsatuan")->fetchAll();
$judul = 'Satuan';
include 'includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Data Satuan</h4>
  <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForm"><i class="bi bi-plus-lg"></i> Tambah</button>
</div>
<div class="card"><div class="table-responsive">
<table class="table table-hover mb-0 align-middle">
  <thead class="table-light"><tr><th>No</th><th>Nama Satuan</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
  <tbody>
  <?php foreach ($data as $i => $r): ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td><?= e($r['nama_satuan']) ?></td>
      <td><?= $r['status'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' ?></td>
      <td class="text-end"><?= tombolUbah(['id' => $r['idsatuan'], 'nama_satuan' => $r['nama_satuan'], 'status' => $r['status']]) ?> <?= tombolHapus($r['idsatuan']) ?></td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$data): ?><tr><td colspan="4" class="text-center text-muted py-4">Tidak ada data.</td></tr><?php endif; ?>
  </tbody>
</table></div></div>

<?php modalAwal('Satuan'); ?>
  <div class="mb-3"><label class="form-label">Nama Satuan</label>
    <input name="nama_satuan" class="form-control" maxlength="45" required></div>
  <label class="form-label">Status</label>
  <select name="status" class="form-select"><option value="1">Aktif</option><option value="0">Nonaktif</option></select>
<?php modalAkhir(); ?>
<?php include 'includes/footer.php'; ?>