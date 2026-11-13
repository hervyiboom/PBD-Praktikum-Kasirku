<?php
require 'config/koneksi.php'; wajibLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi =$_POST['aksi'] ?? '';
    if ($aksi === 'tambah') {
        jalankan(function () use ($pdo) {$pdo->beginTransaction();
            if ($_POST['status'] == 1)$pdo->exec("UPDATE margin_penjualan SET status = 0");
            $pdo->prepare("INSERT INTO margin_penjualan (idmargin_penjualan, persen, status, iduser) VALUES (?, ?, ?, ?)")
                ->execute([idBerikut('margin_penjualan', 'idmargin_penjualan'), $_POST['persen'],$_POST['status'], $_SESSION['iduser']]);$pdo->commit();
        }, 'Margin berhasil ditambahkan.');
    } elseif ($aksi === 'ubah') {
        jalankan(function () use ($pdo) {$pdo->beginTransaction();
            if ($_POST['status'] == 1) {
                $pdo->prepare("UPDATE margin_penjualan SET status = 0 WHERE idmargin_penjualan <> ?")->execute([$_POST['id']]);
            }
            $pdo->prepare("UPDATE margin_penjualan SET persen = ?, status = ? WHERE idmargin_penjualan = ?")
                ->execute([$_POST['persen'],$_POST['status'], $_POST['id']]);$pdo->commit();
        }, 'Margin berhasil diubah.');
    } elseif ($aksi === 'hapus') {
        jalankan(fn() => $pdo->prepare("DELETE FROM margin_penjualan WHERE idmargin_penjualan = ?")
            ->execute([$_POST['id']]), 'Margin berhasil dihapus.');
    }
    header('Location: margin.php'); exit;
}

$data =$pdo->query("SELECT m.*, u.username
                     FROM margin_penjualan m JOIN `user` u ON u.iduser = m.iduser
                     ORDER BY m.idmargin_penjualan")->fetchAll();
$judul = 'Margin';
include 'includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Margin Penjualan</h4>
  <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForm"><i class="bi bi-plus-lg"></i> Tambah</button>
</div>
<div class="card"><div class="table-responsive">
<table class="table table-hover mb-0 align-middle">
  <thead class="table-light"><tr><th>No</th><th>Persen</th><th>Status</th><th>Dibuat Oleh</th><th>Dibuat</th><th>Diubah</th><th class="text-end">Aksi</th></tr></thead>
  <tbody>
  <?php foreach ($data as $i =>$r): ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td><?= e($r['persen']) ?>%</td>
      <td><?= $r['status'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' ?></td>
      <td><?= e(getNamaPanggilan($r['username'])) ?></td>
      <td><?= e($r['created_at']) ?></td>
      <td><?= e($r['updated_at'] ?? '-') ?></td>
      <td class="text-end"><?= tombolUbah(['id' => $r['idmargin_penjualan'], 'persen' =>$r['persen'], 'status' => $r['status']]) ?> <?= tombolHapus($r['idmargin_penjualan']) ?></td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$data): ?><tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data.</td></tr><?php endif; ?>
  </tbody>
</table></div></div>
<p class="text-muted small mt-2">Hanya satu margin yang bisa aktif; mengaktifkan margin baru otomatis menonaktifkan yang lain.</p>

<?php modalAwal('Margin'); ?>
  <div class="mb-3"><label class="form-label">Persentase Margin (%)</label>
    <input type="number" step="0.01" min="0" name="persen" class="form-control" required></div>
  <label class="form-label">Status</label>
  <select name="status" class="form-select"><option value="1">Aktif</option><option value="0">Nonaktif</option></select>
<?php modalAkhir(); ?>
<?php include 'includes/footer.php'; ?>