<?php
require 'config/koneksi.php'; wajibLogin();
$jenisLabel = ['M' => 'Makanan', 'I' => 'Minuman', 'K' => 'Kebutuhan rumah'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    if ($aksi === 'tambah') {
        jalankan(fn() => $pdo->prepare("INSERT INTO barang (idbarang, jenis, nama, idsatuan, status, harga) VALUES (?, ?, ?, ?, ?, ?)")
            ->execute([idBerikut('barang', 'idbarang'), $_POST['jenis'], trim($_POST['nama']), $_POST['idsatuan'], $_POST['status'], $_POST['harga']]), 'Barang berhasil ditambahkan.');
    } elseif ($aksi === 'ubah') {
        jalankan(fn() => $pdo->prepare("UPDATE barang SET jenis = ?, nama = ?, idsatuan = ?, status = ?, harga = ? WHERE idbarang = ?")
            ->execute([$_POST['jenis'], trim($_POST['nama']), $_POST['idsatuan'], $_POST['status'], $_POST['harga'], $_POST['id']]), 'Barang berhasil diubah.');
    } elseif ($aksi === 'hapus') {
        jalankan(fn() => $pdo->prepare("DELETE FROM barang WHERE idbarang = ?")
            ->execute([$_POST['id']]), 'Barang berhasil dihapus.');
    }
    header('Location: barang.php'); exit;
}

$q = trim($_GET['q'] ?? '');
$stmt = $pdo->prepare("SELECT b.*, s.nama_satuan
                       FROM barang b JOIN satuan s ON s.idsatuan = b.idsatuan
                       WHERE b.nama LIKE ? ORDER BY b.idbarang");
$stmt->execute(["%$q%"]);
$data   = $stmt->fetchAll();
$satuan = $pdo->query("SELECT idsatuan, nama_satuan FROM satuan WHERE status = 1 ORDER BY nama_satuan")->fetchAll();

$judul = 'Barang';
include 'includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
  <h4 class="mb-0">Data Barang</h4>
  <div class="d-flex gap-2">
    <form class="d-flex" method="get">
      <input name="q" value="<?= e($q) ?>" class="form-control" placeholder="Cari nama barang...">
      <button class="btn btn-outline-success ms-1"><i class="bi bi-search"></i></button>
    </form>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForm"><i class="bi bi-plus-lg"></i> Tambah</button>
  </div>
</div>
<div class="card"><div class="table-responsive">
<table class="table table-hover mb-0 align-middle">
  <thead class="table-light"><tr><th>No</th><th>Nama</th><th>Jenis</th><th>Satuan</th><th class="text-end">Harga</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
  <tbody>
  <?php foreach ($data as $i => $r): ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td><?= e($r['nama']) ?></td>
      <td><?= e($jenisLabel[$r['jenis']] ?? $r['jenis']) ?></td>
      <td><?= e($r['nama_satuan']) ?></td>
      <td class="text-end">Rp <?= number_format($r['harga'], 0, ',', '.') ?></td>
      <td><?= $r['status'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' ?></td>
      <td class="text-end"><?= tombolUbah(['id' => $r['idbarang'], 'nama' => $r['nama'], 'jenis' => $r['jenis'], 'idsatuan' => $r['idsatuan'], 'harga' => $r['harga'], 'status' => $r['status']]) ?> <?= tombolHapus($r['idbarang']) ?></td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$data): ?><tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data.</td></tr><?php endif; ?>
  </tbody>
</table></div></div>

<?php modalAwal('Barang'); ?>
  <div class="mb-3"><label class="form-label">Nama Barang</label>
    <input name="nama" class="form-control" maxlength="45" required></div>
  <div class="row">
    <div class="col-6 mb-3"><label class="form-label">Jenis</label>
      <select name="jenis" class="form-select">
        <?php foreach ($jenisLabel as $k => $v): ?><option value="<?= $k ?>"><?= $v ?></option><?php endforeach; ?>
      </select></div>
    <div class="col-6 mb-3"><label class="form-label">Satuan</label>
      <select name="idsatuan" class="form-select" required>
        <?php foreach ($satuan as $s): ?><option value="<?= $s['idsatuan'] ?>"><?= e($s['nama_satuan']) ?></option><?php endforeach; ?>
      </select></div>
  </div>
  <div class="row">
    <div class="col-6"><label class="form-label">Harga (Rp)</label>
      <input type="number" name="harga" class="form-control" min="0" required></div>
    <div class="col-6"><label class="form-label">Status</label>
      <select name="status" class="form-select"><option value="1">Aktif</option><option value="0">Nonaktif</option></select></div>
  </div>
<?php modalAkhir(); ?>
<?php include 'includes/footer.php'; ?>