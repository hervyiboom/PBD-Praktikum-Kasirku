<?php
require 'config/koneksi.php';

if (!empty($_SESSION['iduser'])) { 
    header('Location: index.php'); 
    exit; 
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameInput = trim($_POST['username'] ?? '');
    $passwordInput =$_POST['password'] ?? '';

    if ($usernameInput !== '' &&$passwordInput !== '') {
        try {
            $stmt =$pdo->prepare("SELECT u.iduser, u.username, u.password, r.nama_role
                                   FROM `user` u 
                                   JOIN role r ON r.idrole = u.idrole
                                   WHERE u.username = ?");
            $stmt->execute([$usernameInput]);
            $u =$stmt->fetch();

            if ($u) {$hashSha256 = hash('sha256', $passwordInput);$isPasswordValid = (
                    $passwordInput ===$u['password'] || 
                    $hashSha256 ===$u['password'] || 
                    password_verify($passwordInput,$u['password'])
                );

                if ($isPasswordValid) {
                    session_regenerate_id(true);
                    $_SESSION['iduser']      =$u['iduser'];
                    $_SESSION['username']    =$u['username'];
                    $_SESSION['nama_tampil'] = getNamaPanggilan($u['username']);
                    $_SESSION['role']        =$u['nama_role'];

                    catatLog('LOGIN', 'sukses');

                    header('Location: index.php'); 
                    exit;
                } else {
                    $error = 'Password salah!';
                    catatLog('LOGIN_GAGAL', 'password_salah');
                }
            } else {
                $error = 'Username / Email tidak ditemukan!';
                catatLog('LOGIN_GAGAL', 'user_tidak_ditemukan');
            }
        } catch (\PDOException $e) {
            $error = 'Error database: ' .$e->getMessage();
        }
    } else {
        $error = 'Harap isi username dan password!';
    }
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk - <?= e($namaApp) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
  body { background:#f4f8f5; } 
  .card { border:0; box-shadow:0 1px 6px rgba(0,0,0,.1); } 
  .brand { color:#1b7f4f; }
</style>
</head>
<body>
<div class="container" style="max-width:400px; margin-top:12vh">
  <div class="text-center mb-4">
    <div class="brand fs-1"><i class="bi bi-shop"></i></div>
    <h4 class="fw-bold brand"><?= e($namaApp) ?></h4>
    <div class="text-muted">Silakan masuk untuk melanjutkan</div>
  </div>
  <div class="card p-4">
    <?php if ($error !== ''): ?>
      <div class="alert alert-danger py-2 small mb-3"><?= e($error) ?></div>
    <?php endif; ?>
    
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Username / Email</label>
        <input name="username" class="form-control" value="<?= e($_POST['username'] ?? '') ?>" maxlength="45" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" maxlength="100" required>
      </div>
      <button class="btn btn-success w-100">Masuk</button>
    </form>
  </div>
</div>
</body>
</html>