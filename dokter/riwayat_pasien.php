<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'dokter') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db.php';

// Handle AJAX request for patient details
if (isset($_POST['action']) && $_POST['action'] === 'getDetails' && isset($_POST['id'])) {
    $id_daftar = intval($_POST['id']);
    
    $query = "
        SELECT 
            p.nama AS nama_pasien,
            p.no_rm,
            COALESCE(pr.tgl_periksa, dp.created_at) as tgl_periksa,
            dp.keluhan,
            pl.nama_poli,
            d.nama AS nama_dokter,
            COALESCE(pr.catatan, 'Belum diperiksa') as catatan,
            COALESCE(pr.biaya_periksa, 0) as biaya_periksa,
            COALESCE(pr.id, 0) as id_periksa
        FROM 
            daftar_poli dp
        JOIN pasien p ON dp.id_pasien = p.id
        JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
        JOIN dokter d ON jp.id_dokter = d.id
        JOIN poli pl ON d.id_poli = pl.id
        LEFT JOIN periksa pr ON dp.id = pr.id_daftar_poli
        WHERE dp.id = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_daftar);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        exit;
    }

    $data = $result->fetch_assoc();

    // Get medicines if patient has been examined
    if ($data['id_periksa'] > 0) {
        $query_obat = "
            SELECT o.nama_obat, dp.jumlah
            FROM detail_periksa dp
            JOIN obat o ON dp.id_obat = o.id
            WHERE dp.id_periksa = ?
        ";

        $stmt_obat = $conn->prepare($query_obat);
        $stmt_obat->bind_param("i", $data['id_periksa']);
        $stmt_obat->execute();
        $result_obat = $stmt_obat->get_result();

        $obat_list = [];
        while ($row_obat = $result_obat->fetch_assoc()) {
            $obat_list[] = $row_obat['nama_obat'] . ' (' . $row_obat['jumlah'] . ')';
        }
    } else {
        $obat_list = ['Belum ada obat'];
    }

    $data['obat_list'] = implode(', ', $obat_list);
    $data['biaya_periksa_formatted'] = number_format($data['biaya_periksa'], 0, ',', '.');
    
    echo json_encode(['status' => 'success', 'data' => $data]);
    exit;
}

// Pagination settings
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Fetch total number of records
$total_records_query = "SELECT COUNT(*) as total FROM daftar_poli";
$total_records_result = $conn->query($total_records_query);
$total_records = $total_records_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);

// Fetch patient records for current page
$query = "
    SELECT 
        dp.id AS id_daftar,
        p.nama AS nama_pasien,
        p.no_rm,
        dp.created_at AS tanggal_pendaftaran,
        pl.nama_poli,
        d.nama AS nama_dokter,
        pr.id AS id_periksa
    FROM 
        daftar_poli dp
    JOIN pasien p ON dp.id_pasien = p.id
    JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
    JOIN dokter d ON jp.id_dokter = d.id
    JOIN poli pl ON d.id_poli = pl.id
    LEFT JOIN periksa pr ON dp.id = pr.id_daftar_poli
    ORDER BY dp.created_at DESC
    LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pasien - Dokter</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin/styles.css">
    <link rel="icon" type="image/png" href="../assets/images/avatar-doctor.png">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
            <h6 id="admin-name"><?= htmlspecialchars($_SESSION['username']) ?></h6>
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
            <h1 class="mb-4">Riwayat Pasien</h1>
                <div class="welcome">
                    Riwayat <span><strong style="color: #42c3cf;">Semua Pasien</strong></span>
                </div>


            <!-- Filter Tanggal dan Nama -->
                <div class="row mt-4 mb-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label">Cari Nama Pasien:</label>
                        <input type="text" id="searchInput" class="form-control" placeholder="Masukkan nama pasien">
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">Pilih Tanggal:</label>
                        <input type="date" id="dateFilter" class="form-control">
                    </div>
                </div>


            <!-- Table -->
            <div class="table-responsive">
                <table class="table-riwayatpasien table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pasien</th>
                            <th>No RM</th>
                            <th>Tanggal Pendaftaran</th>
                            <th>Poli</th>
                            <th>Dokter</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = $offset + 1;
                        while ($row = $result->fetch_assoc()): 
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_pasien']) ?></td>
                                <td><?= htmlspecialchars($row['no_rm']) ?></td>
                                <td><?= date('Y-m-d', strtotime($row['tanggal_pendaftaran'])) ?></td>
                                <td><?= htmlspecialchars($row['nama_poli']) ?></td>
                                <td><?= htmlspecialchars($row['nama_dokter']) ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="showDetails(<?= $row['id_daftar'] ?>)">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination and Limit Selection -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="d-flex align-items-center gap-2">
                    <select id="limitSelect" class="form-select" style="width: auto;" onchange="changeLimit()">
                        <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20</option>
                        <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                        <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
                    </select>
                    <nav aria-label="Page navigation" class="ms-2">
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page-1 ?>&limit=<?= $limit ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>&limit=<?= $limit ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page+1 ?>&limit=<?= $limit ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Patient Details -->
    <div class="modal fade" id="patientDetailsModal" tabindex="-1" aria-labelledby="patientDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="patientDetailsModalLabel">Detail Riwayat Pasien</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nama Pasien:</strong> <span id="modalNamaPasien"></span></p>
                                <p><strong>No RM:</strong> <span id="modalNoRM"></span></p>
                                <p><strong>Tanggal Periksa:</strong> <span id="modalTanggalPeriksa"></span></p>
                                <p><strong>Keluhan:</strong> <span id="modalKeluhan"></span></p>
                                <p><strong>Poli:</strong> <span id="modalPoli"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Nama Dokter:</strong> <span id="modalDokter"></span></p>
                                <p><strong>Catatan Dokter:</strong> <span id="modalCatatan"></span></p>
                                <p><strong>Obat yang Diberikan:</strong> <span id="modalObat"></span></p>
                                <p><strong>Biaya Periksa:</strong> Rp <span id="modalBiaya"></span></p>
                            </div>
                        </div>
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

    // Search and filter functionality
    $(document).ready(function() {
        $("#searchInput, #dateFilter").on("input", function() {
            var searchValue = $("#searchInput").val().toLowerCase();
            var dateValue = $("#dateFilter").val();

            $("table tbody tr").filter(function() {
                var nameMatch = $(this).find("td:eq(1)").text().toLowerCase().indexOf(searchValue) > -1;
                var dateMatch = true;
                if (dateValue) {
                    var rowDate = $(this).find("td:eq(3)").text();
                    dateMatch = rowDate === dateValue;
                }
                $(this).toggle(nameMatch && dateMatch);
            });
        });
    });

    function showDetails(id) {
        $.ajax({
            url: 'riwayat_pasien.php',
            type: 'POST',
            data: { 
                action: 'getDetails',
                id: id 
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const data = response.data;
                    $('#modalNamaPasien').text(data.nama_pasien);
                    $('#modalNoRM').text(data.no_rm);
                    $('#modalTanggalPeriksa').text(data.tgl_periksa);
                    $('#modalKeluhan').text(data.keluhan);
                    $('#modalPoli').text(data.nama_poli);
                    $('#modalDokter').text(data.nama_dokter);
                    $('#modalCatatan').text(data.catatan);
                    $('#modalObat').text(data.obat_list);
                    $('#modalBiaya').text(data.biaya_periksa_formatted);
                    $('#patientDetailsModal').modal('show');
                } else {
                    alert('Data tidak ditemukan');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data pasien');
            }
        });
    }

    function changeLimit() {
        const limit = document.getElementById('limitSelect').value;
        window.location.href = `?page=1&limit=${limit}`;
    }
    </script>
</body>
</html>

