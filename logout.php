<?php
session_start();

// Hapus semua sesi
session_unset();
session_destroy();

// Arahkan pengguna ke halaman login
header("Location: index.php");
exit();
