<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

include '../includes/db.php';

// Handle messages for notifications
$message = '';
$type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $no_hp = $_POST['no_hp'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $conn->query("INSERT INTO pasien (nama, alamat, no_hp, username, password) 
                      VALUES ('$nama', '$alamat', '$no_hp', '$username', '$password')");
        $message = 'Pasien berhasil ditambahkan!';
        $type = 'success';
    }

    if (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $no_hp = $_POST['no_hp'];
        $username = $_POST['username'];

        $conn->query("UPDATE pasien SET nama='$nama', alamat='$alamat', no_hp='$no_hp', username='$username' WHERE id='$id'");
        $message = 'Pasien berhasil diperbarui!';
        $type = 'success';
    }

    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $conn->query("DELETE FROM pasien WHERE id='$id'");
        $message = 'Pasien berhasil dihapus!';
        $type = 'success';
    }
}

// Handle search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$pasienList = $conn->query("SELECT * FROM pasien WHERE nama LIKE '%$search%'");
if (!$pasienList) {
    die("Query gagal: " . $conn->error);
}

$adminName = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --main-bg-color: #f9fafa;
            --sidebar-bg-color: #ffffff;
            --text-color: #2ac4c2;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--main-bg-color);
        }

        .sidebar {
            height: 100vh;
            background-color: var(--sidebar-bg-color);
            border-right: 1px solid #ddd;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
        }

        h1 {
            color: var(--text-color);
        }

        .btn-primary {
            background-color: #42c3cf;
            border-color: #42c3cf;
        }

        .btn-primary:hover {
            background-color: #35b5bf;
        }

        .btn-warning {
            background-color: #ffdd57;
        }

        .btn-danger {
            background-color: #f87171;
        }

        table thead {
            background-color: #42c3cf;
            color: #fff;
        }

        .modal-header {
            background-color: #42c3cf;
            color: #fff;
        }
    </style>
</head>
<body>


    <div class="content">
        <h1>Kelola Pasien</h1>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari pasien..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">Tambah Pasien</button>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Pasien</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                    <th>Username</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($row = $pasienList->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['nama'] ?></td>
                        <td><?= $row['alamat'] ?></td>
                        <td><?= $row['no_hp'] ?></td>
                        <td><?= $row['username'] ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editPatientModal<?= $row['id'] ?>">Edit</button>
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" name="delete" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editPatientModal<?= $row['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Pasien</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Nama</label>
                                            <input type="text" name="nama" class="form-control" value="<?= $row['nama'] ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label>Alamat</label>
                                            <input type="text" name="alamat" class="form-control" value="<?= $row['alamat'] ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label>No HP</label>
                                            <input type="text" name="no_hp" class="form-control" value="<?= $row['no_hp'] ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label>Username</label>
                                            <input type="text" name="username" class="form-control" value="<?= $row['username'] ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="edit" class="btn btn-warning">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addPatientModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Pasien</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Alamat</label>
                            <input type="text" name="alamat" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>No HP</label>
                            <input type="text" name="no_hp" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if ($message): ?>
    <script>
        Swal.fire({
            icon: '<?= $type ?>',
            title: '<?= $message ?>',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
