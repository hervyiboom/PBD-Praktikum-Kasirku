<?php
require_once 'config/koneksi.php';

if (!empty($_SESSION['iduser'])) {
    header('Location: index.php');
    exit;
}

$error = '';

try {
    $stmtRole = $pdo->query("SELECT * FROM role ORDER BY 1 ASC");
    $listRole = $stmtRole->fetchAll();
} catch (\PDOException $e) {
    $listRole = [];
    $error = 'Gagal mengambil data role dari database.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = trim($_POST['username'] ?? '');
    $password  = trim($_POST['password'] ?? '');
    $confirmPw = trim($_POST['confirm_password'] ?? '');
    $idRole    = (int)($_POST['id_role'] ?? 0);

    if ($username === '' || $password === '' || $confirmPw === '' || $idRole === 0) {
        $error = 'Semua kolom wajib diisi!';
    } elseif ($password !== $confirmPw) {
        $error = 'Konfirmasi password tidak cocok!';
    } else {
        try {
            $stmtCek = $pdo->prepare("SELECT COUNT(*) FROM user WHERE username = ?");
            $stmtCek->execute([$username]);
            
            if ($stmtCek->fetchColumn() > 0) {
                $error = 'Username sudah terpakai!';
            } else {
                $newId = idBerikut('user', 'iduser');
                
                $colRole = 'idrole';
                try {
                    $chk = $pdo->query("SHOW COLUMNS FROM user LIKE 'id_role'");
                    if ($chk && $chk->fetch()) {
                        $colRole = 'id_role';
                    }
                } catch (\PDOException $ex) {}

                $stmtIns = $pdo->prepare("INSERT INTO user (iduser, username, password, {$colRole}) VALUES (?, ?, ?, ?)");
                $stmtIns->execute([$newId, $username, $password, $idRole]);

                flash('success', 'Akun berhasil dibuat! Silakan masuk.');
                header('Location: login.php');
                exit;
            }
        } catch (\PDOException $e) {
            $error = 'Gagal mendaftar: ' . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registrasi - <?= e($namaApp) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
  body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px 0; }
  .register-card { background: #fff; border-radius: 20px; padding: 2.5rem; width: 100%; max-width: 420px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3); }
  .btn-accent { background-color: #0d9488; color: white; border: none; font-weight: 600; padding: 0.75rem; border-radius: 10px; }
  .btn-accent:hover { background-color: #0f766e; color: white; }
</style>
</head>
<body>

<div class="register-card">
    <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center justify-content-center text-white rounded-3 p-3 mb-2" style="background-color: #0d9488;">
            <i class="bi bi-person-plus-fill fs-2"></i>
        </div>
        <h4 class="fw-bold m-0 text-dark">Daftar Akun Baru</h4>
        <p class="text-muted small">Buat akun untuk mengakses <?= e($namaApp) ?></p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label small fw-semibold">Username</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                <input type="text" name="username" class="form-control border-start-0 bg-light" placeholder="Masukkan username" required value="<?= e($_POST['username'] ?? '') ?>">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Role / Hak Akses</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-lock text-muted"></i></span>
                <select name="id_role" class="form-select border-start-0 bg-light" required>
                    <option value="">-- Pilih Role --</option>
                    <?php foreach ($listRole as $r): ?>
                        <?php 
                            $val = $r['idrole'] ?? $r['id_role'] ?? array_values($r)[0]; 
                            $text = $r['nama_role'] ?? $r['namarole'] ?? $r['nama'] ?? array_values($r)[1]; 
                        ?>
                        <option value="<?= $val ?>"><?= e($text) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                <input type="password" name="password" class="form-control border-start-0 bg-light" placeholder="Masukkan password" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label small fw-semibold">Konfirmasi Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-check2-circle text-muted"></i></span>
                <input type="password" name="confirm_password" class="form-control border-start-0 bg-light" placeholder="Ulangi password" required>
            </div>
        </div>

        <button type="submit" class="btn btn-accent w-100 mb-3">Daftar Akun</button>
        <div class="text-center">
            <a href="login.php" class="text-decoration-none small text-muted">Sudah punya akun? <strong style="color: #0d9488;">Masuk di sini</strong></a>
        </div>
    </form>
</div>

</body>
</html>