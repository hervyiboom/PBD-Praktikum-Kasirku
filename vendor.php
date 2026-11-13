<?php
require 'config/koneksi.php'; wajibLogin();
$badanHukum = ['Y' => 'Berbadan hukum', 'T' => 'Tidak berbadan hukum'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    if ($aksi === 'tambah') {
        jalankan(fn() => $pdo->prepare("INSERT INTO vendor (idvendor, nama_vendor, badan_hukum, status) VALUES (?, ?, ?, ?)")
            ->execute([idBerikut('vendor', 'idvendor'), trim($_POST['nama_vendor']), $_POST['badan_hukum'], $_POST['status']]), 'Vendor berhasil ditambahkan.');
    } elseif ($aksi === 'ubah') {
        jalankan(fn() => $pdo->prepare("UPDATE vendor SET nama_vendor = ?, badan_hukum = ?, status = ? WHERE idvendor = ?")
            ->execute([trim($_POST['nama_vendor']), $_POST['badan_hukum'], $_POST['status'], $_POST['id']]), 'Vendor berhasil diubah.');
    } elseif ($aksi === 'hapus') {
        jalankan(fn() => $pdo->prepare("DELETE FROM vendor WHERE idvendor = ?")
            ->execute([$_POST['id']]), 'Vendor berhasil dihapus.');
    }
    header('Location: vendor.php'); exit;
}

$data  = $pdo->query("SELECT * FROM vendor ORDER BY idvendor")->fetchAll();
$judul = 'Vendor';
include 'includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Data Vendor</h4>
  <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForm"><i class="bi bi-plus-lg"></i> Tambah</button>
</div>
<div class="card"><div class="table-responsive">
<table class="table table-hover mb-0 align-middle">
  <thead class="table-light"><tr><th>No</th><th>Nama Vendor</th><th>Badan Hukum</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
  <tbody>
  <?php foreach ($data as $i => $r): ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td><?= e($r['nama_vendor']) ?></td>
      <td><?= e($badanHukum[$r['badan_hukum']] ?? $r['badan_hukum']) ?></td>
      <td><?= $r['status'] === 'A' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' ?></td>
      <td class="text-end"><?= tombolUbah(['id' => $r['idvendor'], 'nama_vendor' => $r['nama_vendor'], 'badan_hukum' => $r['badan_hukum'], 'status' => $r['status']]) ?> <?= tombolHapus($r['idvendor']) ?></td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$data): ?><tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data.</td></tr><?php endif; ?>
  </tbody>
</table></div></div>

<?php modalAwal('Vendor'); ?>
  <div class="mb-3"><label class="form-label">Nama Vendor</label>
    <input name="nama_vendor" class="form-control" maxlength="100" required></div>
  <div class="mb-3"><label class="form-label">Badan Hukum</label>
    <select name="badan_hukum" class="form-select">
      <?php foreach ($badanHukum as $k => $v): ?><option value="<?= $k ?>"><?= $v ?></option><?php endforeach; ?>
    </select></div>
  <label class="form-label">Status</label>
  <select name="status" class="form-select"><option value="A">Aktif</option><option value="N">Nonaktif</option></select>
<?php modalAkhir(); ?>
<?php include 'includes/footer.php'; ?>