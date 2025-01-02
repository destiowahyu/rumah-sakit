<?php
session_start();
require 'includes/db.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Hash password menggunakan MD5

    // Cek Admin
    $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);
        $_SESSION['role'] = 'admin';
        $_SESSION['username'] = $admin['username'];
        $_SESSION['id'] = $admin['id']; // Menyimpan ID admin
        header("Location: admin/dashboard.php");
        exit;
    }

    // Cek Dokter
    $query = "SELECT * FROM dokter WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $dokter = mysqli_fetch_assoc($result);
        $_SESSION['role'] = 'dokter';
        $_SESSION['username'] = $dokter['username'];
        $_SESSION['id'] = $dokter['id']; // Menyimpan ID dokter
        header("Location: dokter/dashboard.php");
        exit;
    }

    $error = "Username atau password salah!";
}
?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/images/doctor-login.png">

    <link rel="stylesheet" href="assets/login/fonts/icomoon/style.css">
    <link rel="stylesheet" href="assets/login/css/owl.carousel.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/login/css/bootstrap.min.css">
    
    <!-- Style -->
    <link rel="stylesheet" href="assets/login/css/style.css">

    <title>Login Dokter</title>
    <style>
      .login-pasien-link {
        color: #42c3cf;
        font-size: 14px;
        font-weight: bold;
        display: block;
        text-align: center;
        margin-top: 15px;
        transition: color 0.3s ease;
      }

      .login-pasien-link:hover {
        color: #35b5bf;
      }

      .doctor-image {
        max-width: 60%;
        margin: auto;
        display: block;
      }
    </style>
  </head>
  <body>
    <div class="content">
      <div class="container">
        <div class="row">
          <div class="col-md-6 order-md-2">
            <!-- Gambar diperkecil -->
            <img src="assets/login/images/doctor.svg" alt="Image" class="img-fluid doctor-image">
          </div>
          <div class="col-md-6 contents">
            <div class="row justify-content-center">
              <div class="col-md-8">
                <div class="mb-4">
                  <h3>Login <strong>Dokter & Admin</strong></h3>
                  <p class="mb-4">Silahkan masukkan username & password dibawah :</p>
                </div>
                <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
                <form method="POST" action="">

                  <div class="form-group first">
                    <label for="username"></label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                  </div>

                  <div class="form-group last mb-4">
                    <label for="password"></label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password" required class="form-control">
                  </div>

                  <input type="submit" value="Login" class="btn btn-login">
                </form>

                <!-- Teks Login Pasien -->
                <a href="login_pasien.php" class="login-pasien-link">Bukan Dokter? Login sebagai Pasien</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>

    <footer class="text-center py-3 bg-light">
        <p class="mb-0">© Destio Wahyu. All Rights Reserved.</p>
    </footer>
  </body>
</html>

