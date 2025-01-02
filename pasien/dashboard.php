<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'pasien') {
    header("Location: ../index.php");
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
include '../includes/db.php';

// Ambil data pasien dari sesi
$pasienName = $_SESSION['username'];
$pasienData = $conn->query("SELECT * FROM pasien WHERE username = '$pasienName'")->fetch_assoc();

// Ambil riwayat pendaftaran terakhir dari tabel daftar_poli
$riwayatTerakhir = $conn->query("
    SELECT dp.created_at AS tanggal_daftar, dp.keluhan, d.nama AS nama_dokter, po.nama_poli, dp.status
    FROM daftar_poli dp
    JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
    JOIN dokter d ON jp.id_dokter = d.id
    JOIN poli po ON d.id_poli = po.id
    WHERE dp.id_pasien = '{$pasienData['id']}'
    ORDER BY dp.created_at DESC
    LIMIT 1
")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pasien</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin/styles.css">
    <link rel="icon" type="image/png" href="../assets/images/pasien.png">
</head>
<body>
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <button class="toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <div class="sidebar" id="sidebar">
        <div class="avatar-container">
            <h4 id="admin-panel">Pasien Panel</h4>
            <img src="../assets/images/pasien.png" class="admin-avatar" alt="Admin">
            <h6 id="admin-name"><?= htmlspecialchars($pasienName) ?></h6>
        </div>
        <a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
        </a>
        <a href="daftar_poli.php" class="<?php echo ($current_page == 'daftar_poli.php') ? 'active' : ''; ?>">
            <i class="fas fa-hospital"></i> <span>Daftar Poli</span>
        </a>
        <a href="../logout.php" class="<?php echo ($current_page == 'logout.php') ? 'active' : ''; ?>">
            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
        </a>
    </div>

    <!-- Main Content -->
    <div class="content" id="content">
        <div class="header">
            <h1>Dashboard</h1>
        </div>
        <div class="welcome mt-4">
            Selamat Datang, <span><strong style="color: #42c3cf;"><?= htmlspecialchars($pasienData['nama'])  ?>!</strong></span>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card-pasien p-3">
                    <h5>Data Diri Anda</h5>
                    <p><strong>Nama Lengkap</strong> : <?= htmlspecialchars($pasienData['nama']) ?></p>
                    <p><strong>Username</strong> : <?= htmlspecialchars($pasienData['username']) ?></p>
                    <p><strong>No RM</strong> : <?= htmlspecialchars($pasienData['no_rm']) ?></p>
                    <p><strong>No KTP</strong> : <?= htmlspecialchars($pasienData['no_ktp']) ?></p>
                    <p><strong>Alamat</strong> : <?= htmlspecialchars($pasienData['alamat']) ?></p>
                    <p><strong>No HP</strong> : <?= htmlspecialchars($pasienData['no_hp']) ?></p>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card-pasien p-3">
                    <h5>Riwayat Pendaftaran Terakhir</h5>
                    <?php if ($riwayatTerakhir): ?>
                        <p><strong>Tanggal Daftar</strong> : <?= htmlspecialchars($riwayatTerakhir['tanggal_daftar']) ?></p>
                        <p><strong>Dokter</strong> : <?= htmlspecialchars($riwayatTerakhir['nama_dokter']) ?></p>
                        <p><strong>Poli</strong> : <?= htmlspecialchars($riwayatTerakhir['nama_poli']) ?></p>
                        <p><strong>Keluhan</strong> : <?= htmlspecialchars($riwayatTerakhir['keluhan']) ?></p>
                        <p><strong>Status</strong> : <?= htmlspecialchars($riwayatTerakhir['status']) ?></p>
                    <?php else: ?>
                        <p>Belum ada riwayat pendaftaran.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const content = document.getElementById('content');

            if (window.innerWidth > 768) {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('collapsed');
            } else {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('show');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth > 768) {
                sidebar.classList.remove('open');
            } else {
                sidebar.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
