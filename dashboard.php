<?php
require_once 'config/koneksi.php';
wajibLogin();

$judul = 'Dashboard';

// Hitung Ringkasan Data
$totalBarang = 0;
$totalSatuan = 0;
$totalVendor = 0;

try {
    $totalBarang = $pdo->query("SELECT COUNT(*) FROM barang")->fetchColumn() ?? 0;
    $totalSatuan = $pdo->query("SELECT COUNT(*) FROM satuan")->fetchColumn() ?? 0;
    $totalVendor = $pdo->query("SELECT COUNT(*) FROM vendor")->fetchColumn() ?? 0;
} catch (\PDOException $e) {
    // Mengabaikan error jika tabel barang/vendor belum dibuat
}

require_once 'includes/header.php';
?>

<div class="p-4 mb-4 rounded-4 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <h2 class="fw-bold">Selamat Datang, <?= e($_SESSION['username'] ?? 'User') ?> 👋</h2>
    <p class="text-white-50 m-0">Sistem Informasi Manajemen Kasir & Inventori Toko.</p>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Total Barang</span>
                    <h2 class="fw-bold m-0 mt-1"><?= $totalBarang ?></h2>
                </div>
                <div class="p-3 rounded-3 bg-primary bg-opacity-10 text-primary fs-3">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Total Satuan</span>
                    <h2 class="fw-bold m-0 mt-1"><?= $totalSatuan ?></h2>
                </div>
                <div class="p-3 rounded-3 bg-teal bg-opacity-10 fs-3" style="color: var(--accent-color);">
                    <i class="bi bi-tags-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase">Total Vendor</span>
                    <h2 class="fw-bold m-0 mt-1"><?= $totalVendor ?></h2>
                </div>
                <div class="p-3 rounded-3 bg-warning bg-opacity-10 text-warning fs-3">
                    <i class="bi bi-truck-front-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>