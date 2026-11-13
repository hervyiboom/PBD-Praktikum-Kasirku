<?php
require 'config/koneksi.php'; wajibAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi =$_POST['aksi'] ?? '';
    if ($aksi === 'tambah') {
        jalankan(fn() => $pdo->prepare("INSERT INTO `user` (iduser, username, password, idrole) VALUES (?, ?, SHA2(?, 256), ?)")
            ->execute([idBerikut('`user`', 'iduser'), trim($_POST['username']), $_POST['password'],$_POST['idrole']]), 'User berhasil ditambahkan.');
    } elseif ($aksi === 'ubah') {
        if (trim($_POST['password']) !== '') {
            jalankan(fn() => $pdo->prepare("UPDATE `user` SET username = ?, password = SHA2(?, 256), idrole = ? WHERE iduser = ?")
                ->execute([trim($_POST['username']),$_POST['password'], $_POST['idrole'],$_POST['id']]), 'User berhasil diubah.');
        } else {
            jalankan(fn() => $pdo->prepare("UPDATE `user` SET username = ?, idrole = ? WHERE iduser = ?")
                ->execute([trim($_POST['username']), $_POST['idrole'],$_POST['id']]), 'User berhasil diubah (password tidak berubah).');
        }
    } elseif ($aksi === 'hapus') {
        if ($_POST['id'] ==$_SESSION['iduser']) {
            flash('danger', 'Tidak bisa menghapus akun yang sedang dipakai.');
        } else {
            jalankan(fn() => $pdo->prepare("DELETE FROM `user` WHERE iduser = ?")
                ->execute([$_POST['id']]), 'User berhasil dihapus.');
        }
    }
    header('Location: user.php'); exit;
}

$data =$pdo->query("SELECT u.iduser, u.username, u.idrole, r.nama_role
                     FROM `user` u JOIN role r ON r.idrole = u.idrole ORDER BY u.iduser")->fetchAll();
$roles =$pdo->query("SELECT * FROM role ORDER BY nama_role")->fetchAll();

$judul = 'User';
include 'includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Data User</h4>
  <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalForm"><i class="bi bi-plus-lg"></i> Tambah</button>
</div>
<div class="card"><div class="table-responsive">
<table class="table table-hover mb-0 align-middle">
  <thead class="table-light"><tr><th>No</th><th>Username / Email</th><th>Nama Tampil</th><th>Role</th><th class="text-end">Aksi</th></tr></thead>
  <tbody>
  <?php foreach ($data as $i =>$r): ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td><?= e($r['username']) ?></td>
      <td><strong><?= e(getNamaPanggilan($r['username'])) ?></strong></td>
      <td><span class="badge bg-info text-dark"><?= e($r['nama_role']) ?></span></td>
      <td class="text-end"><?= tombolUbah(['id' => $r['iduser'], 'username' =>$r['username'], 'idrole' => $r['idrole']]) ?> <?= tombolHapus($r['iduser']) ?></td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$data): ?><tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data.</td></tr><?php endif; ?>
  </tbody>
</table></div></div>

<?php modalAwal('User'); ?>
  <div class="mb-3"><label class="form-label">Username / Email</label>
    <input name="username" class="form-control" maxlength="45" required></div>
  <div class="mb-3"><label class="form-label">Password <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
    <input type="password" name="password" class="form-control" maxlength="100"></div>
  <label class="form-label">Role</label>
  <select name="idrole" class="form-select" required>
    <?php foreach ($roles as$ro): ?><option value="<?= $ro['idrole'] ?>"><?= e($ro['nama_role']) ?></option><?php endforeach; ?>
  </select>
<?php modalAkhir(); ?>
<?php include 'includes/footer.php'; ?>