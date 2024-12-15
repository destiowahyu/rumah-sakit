<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'pasien') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db.php';

// Ambil ID dan nama pasien dari session
$pasienId = $_SESSION['id'];
$pasienName = $_SESSION['username'];

// Fetch data untuk dashboard
$jadwalPeriksa = $conn->query(
    "SELECT hari, jam_mulai, jam_selesai FROM jadwal_periksa 
     INNER JOIN daftar_poli ON jadwal_periksa.id = daftar_poli.id_jadwal 
     WHERE daftar_poli.id_pasien = '$pasienId' AND jadwal_periksa.status = 'aktif' 
     LIMIT 1"
)->fetch_assoc();

$riwayatCount = $conn->query(
    "SELECT COUNT(*) AS total 
     FROM periksa 
     WHERE id_daftar_poli IN (
         SELECT id FROM daftar_poli WHERE id_pasien = '$pasienId'
     )"
)->fetch_assoc()['total'];

$jadwalMessage = $jadwalPeriksa ? $jadwalPeriksa['hari'] . " (" . $jadwalPeriksa['jam_mulai'] . " - " . $jadwalPeriksa['jam_selesai'] . ")" : "Tidak Ada Jadwal Aktif";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin/styles.css">
</head>
<body>
    <div class="sidebar" id="sidebar">
        <h3>Pasien Panel</h3>
        <div class="profile">
            <img src="../assets/images/pasien.png" alt="Avatar">
            <span><?= htmlspecialchars($pasienName) ?></span>
        </div>
        <a href="dashboard.php" class="active"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a href="jadwal_periksa.php"><i class="fas fa-calendar-alt"></i> Jadwal Periksa</a>
        <a href="riwayat_periksa.php"><i class="fas fa-history"></i> Riwayat Pemeriksaan</a>
        <a href="profil.php"><i class="fas fa-user"></i> Profil</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content" id="content">
        <div class="header">
            <button class="toggle-btn" id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>
            <h1>Dashboard</h1>
        </div>
        <div class="welcome mt-4">
            Selamat Datang, <span><?= htmlspecialchars($pasienName) ?></span>!
        </div>
        <div class="container-fluid">
            <div class="row mt-4">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <i class="fas fa-calendar-check"></i>
                        <h5>Jadwal Periksa Aktif</h5>
                        <p><?= $jadwalMessage ?></p>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <i class="fas fa-history"></i>
                        <h5>Riwayat Pemeriksaan</h5>
                        <p><?= $riwayatCount ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
            content.classList.toggle('collapsed');
        });

        content.addEventListener('click', () => {
            if (!sidebar.classList.contains('hidden') && window.innerWidth <= 768) {
                sidebar.classList.add('hidden');
                content.classList.add('collapsed');
            }
        });
    </script>
</body>
</html>
