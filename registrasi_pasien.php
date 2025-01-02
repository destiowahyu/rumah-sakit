<?php
include 'includes/db.php';

function generateNoRM($conn) {
    $yearMonth = date('Ym');
    $query = "SELECT COUNT(*) AS total FROM pasien WHERE no_rm LIKE ?";
    $likePattern = $yearMonth . "%";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $likePattern);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $newNumber = $row['total'] + 1; // Hapus str_pad, langsung gunakan angka
    return $yearMonth . '-' . $newNumber;
}


$success = false;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_ktp = $_POST['no_ktp'];
    $no_hp = $_POST['no_hp'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $no_rm = generateNoRM($conn);

    $query = "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $nama, $alamat, $no_ktp, $no_hp, $no_rm, $username, $password);

    if ($stmt->execute()) {
        $success = true;
    } else {
        $error = "Terjadi kesalahan saat registrasi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="assets/images/patient-icon.png">
    <style>
        body {
            background-color:rgb(255, 255, 255);
        }
        .text-green {
            color: #42c3cf;
        }
        .btn-green {
            background-color: #42c3cf;
            color: white;
        }
        .btn-green:hover {
            background-color: #208b8a;
            color: #fff;
        }
        .modal-header-success {
            background-color: #208b8a;
            color: white;
        }
        .modal-header-danger {
            background-color: #208b8a;
            color: white;
        }
    </style>
    <title>Registrasi Pasien</title>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-12">
            <div class="card shadow border-0">
                <div class="card-header bg-yellow text-center">
                    <h2 class="text-green">Registrasi Pasien</h2>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="no_rm" class="form-label">Nomor RM</label>
                            <input type="text" id="no_rm" class="form-control readonly-input" value="<?php echo generateNoRM($conn); ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" name="alamat" id="alamat" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_ktp" class="form-label">Nomor KTP</label>
                            <input type="text" name="no_ktp" id="no_ktp" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">Nomor HP</label>
                            <input type="text" name="no_hp" id="no_hp" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-green w-100">Registrasi</button>
                        <a href="login_pasien.php" class="btn btn-green w-100 mt-2">Kembali</a>
                    </form>
                </div>
                <div class="card-footer text-center text-muted" style="font-size: 12px;">
                    &copy; Destio Wahyu. All Rights Reserved.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal" id="successModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-success">
                <h5 class="modal-title">Registrasi Berhasil</h5>
            </div>
            <div class="modal-body">
                <p>Akun Anda telah berhasil dibuat.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="redirectToLogin()">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal" id="errorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <h5 class="modal-title">Registrasi Gagal</h5>
            </div>
            <div class="modal-body">
                <p><?php echo $error; ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php if ($success): ?>
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
        <?php elseif ($error): ?>
        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
        <?php endif; ?>
    });

    function redirectToLogin() {
        window.location.href = 'login_pasien.php';
    }
</script>
</body>
</html>
