<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'dokter') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db.php';

// Fetch doctor's current data
$username = $_SESSION['username'];
$query = "SELECT * FROM dokter WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$dokter = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();
    
    // Get form data
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $new_username = $_POST['username'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Start building the update query
    $update_fields = array();
    $param_types = "";
    $param_values = array();

    // Add basic fields
    $update_fields[] = "nama = ?";
    $param_types .= "s";
    $param_values[] = $nama;

    $update_fields[] = "alamat = ?";
    $param_types .= "s";
    $param_values[] = $alamat;

    $update_fields[] = "no_hp = ?";
    $param_types .= "s";
    $param_values[] = $no_hp;

    // Check if username is being changed
    if ($new_username !== $username) {
        // Check if new username already exists
        $check_username = "SELECT id FROM dokter WHERE username = ? AND username != ?";
        $check_stmt = $conn->prepare($check_username);
        $check_stmt->bind_param("ss", $new_username, $username);
        $check_stmt->execute();
        if ($check_stmt->get_result()->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Username sudah digunakan']);
            exit;
        }
        $update_fields[] = "username = ?";
        $param_types .= "s";
        $param_values[] = $new_username;
    }

    // Check if password is being changed
    if (!empty($new_password)) {
        if ($new_password !== $confirm_password) {
            echo json_encode(['status' => 'error', 'message' => 'Password baru dan konfirmasi tidak cocok']);
            exit;
        }
        $hashed_password = md5($new_password); // Updated password hashing
        $update_fields[] = "password = ?";
        $param_types .= "s";
        $param_values[] = $hashed_password;
    }

    // Build and execute the update query
    $query = "UPDATE dokter SET " . implode(", ", $update_fields) . " WHERE username = ?";
    $param_types .= "s";
    $param_values[] = $username;

    $stmt = $conn->prepare($query);
    $stmt->bind_param($param_types, ...$param_values);

    if ($stmt->execute()) {
        // Update session if username was changed
        if ($new_username !== $username) {
            $_SESSION['username'] = $new_username;
        }
        echo json_encode(['status' => 'success', 'message' => 'Profil berhasil diperbarui']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui profil']);
    }
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Dokter</title>
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
            <h6 id="admin-name"><?= htmlspecialchars($username) ?></h6>
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
            <h1 class="mb-4">Profil Dokter</h1>
                    <div class="card-profildokter">
                        <div class="card-body">
                            <form id="profileForm" method="POST">
                                <div class="mb-3">
                                    <label for="nama" class="form-label"><span><strong style="color: #42c3cf;">Nama Lengkap</strong></span></label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($dokter['nama']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="alamat" class="form-label"><span><strong style="color: #42c3cf;">Alamat</strong></span></label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= htmlspecialchars($dokter['alamat']) ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="no_hp" class="form-label"><span><strong style="color: #42c3cf;">No. HP</strong></span></label>
                                    <input type="tel" class="form-control" id="no_hp" name="no_hp" value="<?= htmlspecialchars($dokter['no_hp']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="username" class="form-label"><span><strong style="color: #42c3cf;">Username</strong></span></label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($dokter['username']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="new_password" class="form-label"><span><strong style="color: #42c3cf;">Password Baru (Kosongkan jika tidak ingin mengubah)</strong></span></label>
                                    <input type="password" class="form-control" id="new_password" name="new_password">
                                    <!--<div class="form-text">Minimal 6 karakter</div>--> </div>

                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label"><span><strong style="color: #42c3cf;">Konfirmasi Password Baru</strong></span></label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                </div>

                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="notificationToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notifikasi</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="notificationMessage"></div>
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

    $(document).ready(function() {
        // Form submission handling
        $('#profileForm').on('submit', function(e) {
            e.preventDefault();

            // Basic validation
            const newPassword = $('#new_password').val();
            const confirmPassword = $('#confirm_password').val();

            //if (newPassword && newPassword.length < 6) {
            //    showNotification('Password baru harus minimal 6 karakter', 'danger');
            //    return;
            //}

            if (newPassword !== confirmPassword) {
                showNotification('Password baru dan konfirmasi tidak cocok', 'danger');
                return;
            }

            // Submit form via AJAX
            $.ajax({
                url: 'profil.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        showNotification(response.message, 'success');
                        // Update displayed username if it was changed
                        if ($('#username').val() !== '<?= $username ?>') {
                            $('#admin-name').text($('#username').val());
                        }
                        // Clear password fields
                        $('#new_password, #confirm_password').val('');
                    } else {
                        showNotification(response.message, 'danger');
                    }
                },
                error: function() {
                    showNotification('Terjadi kesalahan. Silakan coba lagi.', 'danger');
                }
            });
        });

        function showNotification(message, type = 'success') {
            const toast = $('#notificationToast');
            const toastBody = $('#notificationMessage');
            
            // Set toast header color based on type
            const header = toast.find('.toast-header');
            header.removeClass('bg-success bg-danger text-white');
            if (type === 'success') {
                header.addClass('bg-success text-white');
            } else {
                header.addClass('bg-danger text-white');
            }
            
            toastBody.text(message);
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }
    });
    </script>
</body>
</html>

