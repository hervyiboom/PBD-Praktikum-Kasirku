<?php
require 'config/koneksi.php'; wajibAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    if ($aksi === 'tambah') {
        jalankan(fn() => $pdo->prepare("INSERT INTO role (idrole, nama_role) VALUES (?, ?)")
            ->execute([idBerikut('role', 'idrole'), trim($_POST['nama_role'])]), 'Role berhasil ditambahkan.');
    } elseif ($aksi === 'ubah') {
        jalankan(fn() => $pdo->prepare("UPDATE role SET nama_role = ? WHERE idrole = ?")
            ->execute([trim($_POST['nama_role']), $_POST['id']]), 'Role berhasil diubah.');
    } elseif ($aksi === 'hapus') {
        jalankan(fn() => $pdo->prepare("DELETE FROM role WHERE idrole = ?")
            ->execute([$_POST['id']]), 'Role berhasil dihapus.');
    }
    header('Location: role.php'); exit;
}

$data  = $pdo->query("SELECT * FROM role ORDER BY idrole")->fetchAll();
$judul = 'Role';
include 'includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Data Role</h4>
  <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForm"><i class="bi bi-plus-lg"></i> Tambah</button>
</div>
<div class="card"><div class="table-responsive">
<table class="table table-hover mb-0 align-middle">
  <thead class="table-light"><tr><th>No</th><th>Nama Role</th><th class="text-end">Aksi</th></tr></thead>
  <tbody>
  <?php foreach ($data as $i => $r): ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td><?= e($r['nama_role']) ?></td>
      <td class="text-end"><?= tombolUbah(['id' => $r['idrole'], 'nama_role' => $r['nama_role']]) ?> <?= tombolHapus($r['idrole']) ?></td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$data): ?><tr><td colspan="3" class="text-center text-muted py-4">Tidak ada data.</td></tr><?php endif; ?>
  </tbody>
</table></div></div>

<?php modalAwal('Role'); ?>
  <label class="form-label">Nama Role</label>
  <input name="nama_role" class="form-control" maxlength="45" required>
<?php modalAkhir(); ?>
<?php include 'includes/footer.php'; ?>