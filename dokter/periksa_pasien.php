<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'dokter') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db.php';

// Ambil data dokter dari sesi
$dokterUsername = $_SESSION['username'];
$query_dokter = $conn->prepare("SELECT id, nama FROM dokter WHERE username = ?");
$query_dokter->bind_param("s", $dokterUsername);
$query_dokter->execute();
$result_dokter = $query_dokter->get_result();

if ($result_dokter->num_rows === 0) {
    echo "Data dokter tidak ditemukan. Silakan login kembali.";
    exit();
}

$dokterData = $result_dokter->fetch_assoc();
$dokterId = $dokterData['id'];
$dokterName = $dokterData['nama'];

// Ambil daftar pasien berdasarkan ID dokter
$query_pasien = "
    SELECT dp.id AS id_daftar, p.nama AS nama_pasien, dp.no_antrian, dp.keluhan, dp.status, dp.created_at 
    FROM daftar_poli dp
    JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
    JOIN pasien p ON dp.id_pasien = p.id
    WHERE jp.id_dokter = ?
    ORDER BY dp.created_at DESC
";
$stmt = $conn->prepare($query_pasien);
$stmt->bind_param("i", $dokterId);
$stmt->execute();
$result_pasien = $stmt->get_result();

$current_page = basename($_SERVER['PHP_SELF']);



// Ambil data nama dokter dari database
$dokterData = $conn->query("SELECT nama FROM dokter WHERE id = '$dokterId'")->fetch_assoc();
$dokterName = $dokterData['nama']; // Nama asli dokter dari database

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
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
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
        <div class="container">
            <h1 class="mb-4">Periksa Pasien</h1>
                <div class="welcome">
                    Pasien <span><strong style="color: #42c3cf;"><?= htmlspecialchars($dokterName) ?>!</strong></span> :
                </div>
                <!-- Filter Tanggal dan Nama -->
                <div class="row mt-4 mb-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label">Cari Nama Pasien:</label>
                        <input type="text" id="search" class="form-control" placeholder="Masukkan nama pasien">
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">Pilih Tanggal:</label>
                        <input type="date" id="tanggal" class="form-control">
                    </div>
                </div>

                <!-- Tabel Pasien -->
                <table class="table-periksapasien table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pasien</th>
                            <th>Nomor Antrian</th>
                            <th>Keluhan</th>
                            <th>Tanggal Pendaftaran</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1; 
                        if ($result_pasien->num_rows > 0): 
                            while ($row = $result_pasien->fetch_assoc()): 
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_pasien']) ?></td>
                                <td><?= htmlspecialchars($row['no_antrian']) ?></td>
                                <td><?= htmlspecialchars($row['keluhan']) ?></td>
                                <td><?= htmlspecialchars(date('Y-m-d', strtotime($row['created_at']))) ?></td>
                                <td>
                                    <?php if ($row['status'] === 'Belum Diperiksa'): ?>
                                        <span style="font-weight: bold; color: red;">&#10060; Belum diperiksa</span>
                                    <?php else: ?>
                                        <span style="font-weight: bold; color: green;">&#9989; Sudah diperiksa</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['status'] === 'Belum Diperiksa'): ?>
                                        <a href="detail_periksa_pasien.php?id=<?= $row['id_daftar'] ?>" class="btn btn-primary btn-sm">
                                            <i class="bi bi-clipboard2-plus"></i> Periksa
                                        </a>
                                    <?php else: ?>
                                        <a href="detail_periksa_pasien.php?id=<?= $row['id_daftar'] ?>" class="btn btn-secondary btn-sm">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php 
                            endwhile; 
                        else: 
                        ?>
                            <tr>
                                <td colspan="7" class="text-center">Belum ada pasien yang mendaftar.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- JavaScript untuk Filter Real-Time -->
            <script>
                $(document).ready(function() {
                    function filterTable() {
                        const tanggal = $('#tanggal').val();
                        const search = $('#search').val().toLowerCase();

                        $('.table-periksapasien tbody tr').each(function() {
                            const row = $(this);
                            const nama = row.find('td:eq(1)').text().toLowerCase();
                            const tanggalCreated = row.find('td:eq(4)').text();

                            const matchesTanggal = !tanggal || tanggalCreated.includes(tanggal);
                            const matchesSearch = !search || nama.includes(search);

                            row.toggle(matchesTanggal && matchesSearch);
                        });
                    }

                    // Event listener for filter inputs
                    $('#tanggal, #search').on('input', filterTable);

                    // Initial filter application
                    filterTable();
                });
            </script>

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

