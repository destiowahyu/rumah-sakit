<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'dokter') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db.php';

$current_page = basename($_SERVER['PHP_SELF']);

// Ambil ID dokter dan username dari session
$dokterId = $_SESSION['id'];
$dokterUsername = $_SESSION['username'];

// Ambil data nama dokter dari database
$dokterData = $conn->query("SELECT nama FROM dokter WHERE id = '$dokterId'")->fetch_assoc();
$dokterName = $dokterData['nama']; // Nama asli dokter dari database

// Fetch jadwal aktif untuk dashboard
$jadwalAktif = $conn->query("
    SELECT hari, jam_mulai, jam_selesai 
    FROM jadwal_periksa 
    WHERE id_dokter = '$dokterId' AND status = 'Aktif'
    LIMIT 1
")->fetch_assoc();

// Fetch jumlah pasien yang mendaftar ke dokter ini hari ini
$pasienHariIni = $conn->query("
    SELECT COUNT(*) AS total 
    FROM daftar_poli dp
    JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
    WHERE jp.id_dokter = '$dokterId' 
    AND DATE(dp.created_at) = CURDATE()
")->fetch_assoc()['total'];

// Fetch jumlah pasien hari ini yang belum diperiksa
$pasienBelumDiperiksa = $conn->query("
    SELECT COUNT(*) AS total 
    FROM daftar_poli dp
    JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
    WHERE jp.id_dokter = '$dokterId' 
    AND DATE(dp.created_at) = CURDATE()
    AND dp.status = 'Belum Diperiksa'
")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dokter</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin/styles.css">
    <link rel="icon" type="image/png" href="../assets/images/avatar-doctor.png">
</head>
<body>
    <!-- Overlay -->
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <button class="toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <div class="sidebar" id="sidebar">
    <div class="avatar-container">
        <h4 id="admin-panel">Dokter Panel</h4>
        <img src="../assets/images/avatar-doctor.png" class="admin-avatar" alt="Admin">
        <h6 id="admin-name"><?= htmlspecialchars($dokterUsername) ?></h6>
    </div>
        <a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
        </a>
        <a href="jadwal_periksa.php" class="<?php echo ($current_page == 'jadwal_periksa.php') ? 'active' : ''; ?>">
            <i class="fas fa-calendar-alt"></i><span>Jadwal Periksa</span>
        </a>
        <a href="periksa_pasien.php" class="<?php echo ($current_page == 'periksa_pasien.php') ? 'active' : ''; ?>">
            <i class="fas fa-user-md"></i> <span>Periksa Pasien</span>
        </a>
        <a href="riwayat_pasien.php" class="<?php echo ($current_page == 'riwayat_pasien.php') ? 'active' : ''; ?>">
            <i class="fas fa-history"></i> <span>Riwayat Pasien</span>
        </a>
        <a href="profil.php" class="<?php echo ($current_page == 'profil.php') ? 'active' : ''; ?>">
            <i class="fas fa-user"></i> <span>Profil</span>
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
            Selamat Datang, <span><strong style="color: #42c3cf;"><?= htmlspecialchars($dokterName) ?>!</strong></span>
        </div>
        <div class="container-fluid">
            <div class="row mt-4">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <i class="fas fa-calendar-check"></i>
                        <h5>Jadwal Aktif</h5>
                        <p>
                            <?= $jadwalAktif ? $jadwalAktif['hari'] . " (" . $jadwalAktif['jam_mulai'] . " - " . $jadwalAktif['jam_selesai'] . ")" : "Tidak ada jadwal aktif" ?>
                        </p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <i class="fas fa-user-plus"></i>
                        <h5>Pasien Hari Ini</h5>
                        <p><?= $pasienHariIni ?></p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <i class="fas fa-user-clock"></i>
                        <h5>Pasien Belum Diperiksa</h5>
                        <p><?= $pasienBelumDiperiksa ?></p>
                    </div>
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

        // Default sidebar state on load
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

