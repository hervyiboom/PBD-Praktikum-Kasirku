<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$db   = 'db_pbd'; // Sesuaikan dengan nama database di MySQL Workbench
$user = 'root';   // Sesuaikan user MySQL kamu
$pass = 'naura_2009';       // Sesuaikan password MySQL kamu

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (\PDOException $e) {
    die("Koneksi Database Gagal: " . $e->getMessage());
}

$namaApp = 'Kasirku';

// Helper Sanitasi HTML
if (!function_exists('e')) {
    function e($str) {
        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
    }
}

// Helper Flash Message
if (!function_exists('flash')) {
    function flash($type = null, $message = null) {
        if ($type !== null && $message !== null) {
            $_SESSION['flash'] = ['type' => $type, 'message' => $message];
        } else {
            if (isset($_SESSION['flash'])) {
                $f = $_SESSION['flash'];
                unset($_SESSION['flash']);
                return $f;
            }
            return null;
        }
    }
}

// Validasi Wajib Login
if (!function_exists('wajibLogin')) {
    function wajibLogin() {
        if (empty($_SESSION['iduser'])) {
            header('Location: login.php');
            exit;
        }
    }
}

// Validasi Akses Admin / Super Admin
if (!function_exists('wajibAdmin')) {
    function wajibAdmin() {
        wajibLogin();
        $role = strtolower($_SESSION['role'] ?? '');
        if (!in_array($role, ['super admin', 'admin', 'administrator'])) {
            flash('danger', 'Akses ditolak! Halaman ini hanya untuk Admin / Super Admin.');
            header('Location: index.php');
            exit;
        }
    }
}

// Eksekutor Query dengan Try-Catch
if (!function_exists('jalankan')) {
    function jalankan($fn, $pesanSukses = 'Berhasil disimpan.') {
        try {
            $fn();
            flash('success', $pesanSukses);
        } catch (\PDOException $e) {
            flash('danger', 'Gagal operasi database: ' . $e->getMessage());
        } catch (\Exception $e) {
            flash('danger', 'Gagal: ' . $e->getMessage());
        }
    }
}

// Helper Hitung ID Auto-Increment Manual (MAX + 1)
if (!function_exists('idBerikut')) {
    function idBerikut($table, $pkColumn) {
        global $pdo;
        try {
            $tableClean = str_replace('`', '', $table);
            $stmt =$pdo->query("SELECT MAX({$pkColumn}) FROM `{$tableClean}`");
            $max =$stmt->fetchColumn();
            return ($max ? (int)$max : 0) + 1;         } catch (\PDOException$e) {
            return 1;
        }
    }
}

// Helper Format Nama Tampil dari Email / Username
if (!function_exists('getNamaPanggilan')) {
    function getNamaPanggilan($str) {
        if (is_array($str)) {
            $str =$str['username'] ?? '';
        }
        if (strpos($str, '@') !== false) {$str = explode('@', $str)[0];         }$str = preg_replace('/[-_]?\d{4}$/', '',$str);
        $str = str_replace(['.', '_', '-'], ' ',$str);
        return ucwords(trim($str)) ?: 'User';
    }
}

// Helper Catat Log Aktivitas ke File log_aktivitas.txt
if (!function_exists('catatLog')) {
    function catatLog($aksi,$keterangan = '') {
        $fileLog = __DIR__ . '/../log_aktivitas.txt';$waktu   = date('Y-m-d H:i:s');
        $user    =$_SESSION['username'] ?? 'GUEST';
        $ip      =$_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        $baris   = "{$waktu} {$aksi} email={$user} status={$keterangan} ip={$ip}\n";
        file_put_contents($fileLog,$baris, FILE_APPEND | LOCK_EX);
    }
}

// Helper Tombol Ubah Modal
if (!function_exists('tombolUbah')) {
    function tombolUbah($data) {
        $json = htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8');
        return '<button type="button" class="btn btn-sm btn-outline-warning btn-edit me-1" data-json=\'' . $json . '\'><i class="bi bi-pencil"></i> Ubah</button>';
    }
}

// Helper Tombol Hapus Form
if (!function_exists('tombolHapus')) {
    function tombolHapus($id) {
        return '<form method="post" class="d-inline" onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\')">
                  <input type="hidden" name="aksi" value="hapus">
                  <input type="hidden" name="id" value="' . e($id) . '">
                  <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
                </form>';
    }
}

// Helper Tag Awal Modal
if (!function_exists('modalAwal')) {
    function modalAwal($judul) {
        echo '
        <div class="modal fade" id="modalForm" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="post" id="formUtama">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalFormTitle">Tambah ' . e($judul) . '</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="aksi" id="formAksi" value="tambah">
                  <input type="hidden" name="id" id="formId" value="">
        ';
    }
}

// Helper Tag Akhir Modal
if (!function_exists('modalAkhir')) {
    function modalAkhir() {
        echo '
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-success">Simpan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        ';
    }
}