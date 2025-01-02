<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    // Query untuk memeriksa username dan password
    $query = "SELECT id, username FROM pasien WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $_SESSION['role'] = 'pasien';
        $_SESSION['username'] = $row['username'];
        $_SESSION['id'] = $row['id']; // Menyimpan ID pasien ke session
        header("Location: pasien/dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/loginregisterpasien/styles.css">
    <link rel="icon" type="image/png" href="assets/images/patient-icon.png">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="row" style="width: 77%;">
            <div class="col-md-5 d-flex flex-column justify-content-center align-items-center bg-yellow p-4" style="border-radius: 30px";>
                <img src="assets/images/patient-icon.png" alt="Pasien" class="rounded-circle" style="width: 100%;">
            </div>
            <div class="col-md-7 p-4">
                <h2 class="mb-4 text-green">Login Pasien</h2>
                <p class="mb-4">Silahkan masukkan username & password Anda:</p>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-green w-100">Login</button>
                </form>
                <div class="text-center mt-3">
                    <a href="registrasi_pasien.php" class="registerpasien">Belum punya akun? Registrasi di sini</a><br>
                    <a href="login_dokter.php" class="logindokter">Login sebagai dokter</a>
                </div>
                <div class="text-center mt-3 text-muted" style="font-size: 12px;">
                    &copy; Destio Wahyu. All Rights Reserved.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
