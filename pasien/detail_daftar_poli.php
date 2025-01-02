<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'pasien') {
    header("Location: ../index.php");
    exit();
}

$active_page = 'daftar_poli.php'; // Menandai halaman aktif sebagai "daftar_poli.php"

include '../includes/db.php';

// Ambil data pasien dari sesi
$pasienName = $_SESSION['username'];
$pasienData = $conn->query("SELECT * FROM pasien WHERE username = '$pasienName'")->fetch_assoc();

if (!isset($_GET['id'])) {
    echo "ID pendaftaran tidak ditemukan.";
    exit();
}

$id_daftar_poli = intval($_GET['id']);

// Ambil detail pendaftaran
$query_detail = "
    SELECT dp.id, po.nama_poli, d.nama AS nama_dokter, 
           j.hari, j.jam_mulai, j.jam_selesai, dp.no_antrian, dp.created_at,
           dp.status, dp.keluhan, p.catatan, p.tgl_periksa, 
           p.biaya_periksa
    FROM daftar_poli dp
    LEFT JOIN periksa p ON dp.id = p.id_daftar_poli
    JOIN jadwal_periksa j ON dp.id_jadwal = j.id
    JOIN dokter d ON j.id_dokter = d.id
    JOIN poli po ON d.id_poli = po.id
    WHERE dp.id = ?
";
$stmt = $conn->prepare($query_detail);
$stmt->bind_param("i", $id_daftar_poli);
$stmt->execute();
$result_detail = $stmt->get_result();

if ($result_detail->num_rows === 0) {
    echo "Detail tidak ditemukan.";
    exit();
}

$detail = $result_detail->fetch_assoc();

// Ambil daftar obat yang diresepkan
$query_obat = "
    SELECT o.nama_obat
    FROM detail_periksa dp
    JOIN obat o ON dp.id_obat = o.id
    WHERE dp.id_periksa = (
        SELECT id FROM periksa WHERE id_daftar_poli = ?
    )
";
$stmt_obat = $conn->prepare($query_obat);
$stmt_obat->bind_param("i", $id_daftar_poli);
$stmt_obat->execute();
$result_obat = $stmt_obat->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Daftar Poli</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin/styles.css">
    <link rel="icon" type="image/png" href="../assets/images/pasien.png">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="avatar-container">
            <h4 id="admin-panel">Pasien Panel</h4>
            <img src="../assets/images/pasien.png" class="admin-avatar" alt="Pasien">
            <h6 id="admin-name"><?= htmlspecialchars($pasienName) ?></h6>
        </div>
        <a href="dashboard.php" class="<?php echo ($active_page == 'dashboard.php') ? 'active' : ''; ?>">
            <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
        </a>
        <a href="daftar_poli.php" class="<?php echo ($active_page == 'daftar_poli.php') ? 'active' : ''; ?>">
            <i class="fas fa-hospital"></i> <span>Daftar Poli</span>
        </a>
        <a href="../logout.php" class="<?php echo ($active_page == 'logout.php') ? 'active' : ''; ?>">
            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
        </a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container mt-5">
            <h1 class="text-center">Detail Daftar Poli</h1>
            <table class="table table-bordered mt-4">
                <tr>
                    <th>Poli</th>
                    <td><?php echo htmlspecialchars($detail['nama_poli']); ?></td>
                </tr>
                <tr>
                    <th>Dokter</th>
                    <td><?php echo htmlspecialchars($detail['nama_dokter']); ?></td>
                </tr>
                <tr>
                    <th>Jadwal</th>
                    <td><?php echo htmlspecialchars($detail['hari'] . " (" . $detail['jam_mulai'] . " - " . $detail['jam_selesai'] . ")"); ?></td>
                </tr>
                <tr>
                    <th>Nomor Antrian</th>
                    <td><?php echo htmlspecialchars($detail['no_antrian']); ?></td>
                </tr>
                <tr>
                    <th>Waktu Mendaftar</th>
                    <td><?php echo htmlspecialchars(date('d-m-Y H:i:s', strtotime($detail['created_at']))); ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <?php if ($detail['status'] === 'Belum Diperiksa'): ?>
                            <span style="font-weight: bold; color: red;">&#10060; Belum diperiksa</span>
                        <?php else: ?>
                            <span style="font-weight: bold; color: green;">&#9989; Sudah diperiksa</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Keluhan</th>
                    <td><?php echo htmlspecialchars($detail['keluhan']); ?></td>
                </tr>
                <tr>
                    <th>Tanggal Periksa</th>
                    <td><?php echo htmlspecialchars($detail['tgl_periksa'] ?? 'Belum diperiksa'); ?></td>
                </tr>
                <tr>
                    <th>Catatan Dokter</th>
                    <td><?php echo htmlspecialchars($detail['catatan'] ?? 'Belum ada catatan'); ?></td>
                </tr>
                <tr>
                    <th>Biaya Periksa</th>
                    <td><?php echo htmlspecialchars($detail['biaya_periksa'] ?? 'Belum ditentukan'); ?></td>
                </tr>
            </table>

            <h1 class="mt-5 text-center">Daftar Obat yang Diresepkan</h1>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Nama Obat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_obat->num_rows > 0): ?>
                        <?php while ($row = $result_obat->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nama_obat']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td class="text-center">Tidak ada obat yang diresepkan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <a href="daftar_poli.php" class="btn btn-primary mt-3">Kembali</a>
        </div>
    </div>
</body>
</html>
