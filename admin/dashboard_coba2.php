<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }
        .card {
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.16);
            border: none;
            text-align: center;
            background: linear-gradient(to bottom,rgba(209, 236, 242, 0.67), #fff);
            border-radius: 15px;
            transition: transform 0.3s ease-in-out;
            margin-top: 20px;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card i {
            font-size: 2rem;
            color: #00b9b9;
        }
        .card h5 {
            color: black;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .card p {
            font-size: 2rem;
            font-weight: bold;
            color: #42c3cf;
        }

        /* Sidebar Default */
        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #ffffff;
            color: #104342;
            position: fixed;
            padding-top: 35px;
            transition: all 0.3s ease-in-out;
            overflow-y: auto;
            z-index: 1000;
        }
        .sidebar a {
            color: #104342;
            text-decoration: none;
            padding: 12px 30px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 20px;
            transition: all 0.3s ease-in-out;
            border-radius: 10px;
        }
        .sidebar a:hover {
            background-color: #42c3cf;
            color: #e0f7f7; 
            
        }
        .sidebar a.active{

            background-color: #42c3cf;
            color: #e0f7f7;
        }


        /* Sidebar Collapsed */
        .sidebar.collapsed {
            width: 60px;
            padding-top: 50px;
        }
        .sidebar.collapsed a.active {
            color: #42c3cf;
            background-color:rgba(255, 255, 255, 0);
        }
        .sidebar.collapsed a:hover{
            color: #42c3cf;
            background-color:rgba(66, 195, 207, 0);
        }
        .sidebar.collapsed a {
            padding: 12px 10px; /* Ikon lebih ke tengah */
            justify-content: center;
        }
        .sidebar.collapsed a span {
            display: none; /* Sembunyikan teks */
        }
        .sidebar.collapsed .avatar-container {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }
        .sidebar.collapsed .admin-avatar {
            width: 40px;
            height: 40px;
        }
        .sidebar.collapsed #admin-name {
            display: none;
        }
        .sidebar.collapsed #admin-panel {
            display: none;
        }

        /* Avatar Container */
        .admin-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }
        .avatar-container {
            text-align: center;
            margin-bottom: 20px;
            padding-top: 10px;
        }

        /* Content */
        .content {
            margin-left: 250px;
            padding: 35px;
            transition: all 0.3s ease-in-out;
        }
        .content.collapsed {
            margin-left: 70px;
        }

        /* Overlay for Mobile */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
        .overlay.show {
            display: block;
        }

        /* Toggle Button */
        .toggle-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: rgb(255, 255, 255);
            color: #42c3cf;
            border: none;
            cursor: pointer;
            padding: 5px 10px;
            font-size: 1.3rem;
            z-index: 1100;
            border-radius: 5px;
        }

        /* Mobile Media Query */
        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
            }
            .sidebar.open {
                left: 0;
            }
            .content {
                padding: 55px 40px;
                margin-left: 0;
            }
        }
    </style>
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
            <h4 id="admin-panel">Admin Panel</h4>
            <img src="../assets/images/admin.png" class="admin-avatar" alt="Admin">
            <h6 class="mt-2 mb-0" id="admin-name">Admin</h6>
        </div>
        <a href="#" class="active"><i class="fas fa-chart-pie""></i> <span>Dashboard</span></a>
        <a href="#"><i class="fas fa-user-md"></i> <span>Kelola Dokter</span></a>
        <a href="#"><i class="fas fa-users"></i> <span>Kelola Pasien</span></a>
        <a href="#"><i class="fas fa-hospital"></i> <span>Kelola Poli</span></a>
        <a href="#"><i class="fas fa-pills"></i> <span>Kelola Obat</span></a>
        <a href="#"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
    </div>

    <!-- Main Content -->
    <div class="content" id="content">
        <h2>Selamat Datang, admin!</h2>
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card p-3">
                    <i class="fas fa-user-md mb-2"></i>
                    <h5>Total Dokter</h5>
                    <h3>4</h3>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card p-3">
                    <i class="fas fa-users mb-2"></i>
                    <h5>Total Pasien</h5>
                    <h3>16</h3>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card p-3">
                    <i class="fas fa-hospital mb-2"></i>
                    <h5>Total Poli</h5>
                    <h3>4</h3>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card p-3">
                    <i class="fas fa-pills mb-2"></i>
                    <h5>Total Obat</h5>
                    <h3>6</h3>
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
