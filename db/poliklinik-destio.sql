-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Jan 2025 pada 12.29
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `poliklinik-destio`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(4, 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Struktur dari tabel `daftar_poli`
--

CREATE TABLE `daftar_poli` (
  `id` int(11) NOT NULL,
  `id_pasien` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `keluhan` text NOT NULL,
  `no_antrian` int(11) DEFAULT NULL,
  `status` enum('Belum Diperiksa','Sudah Diperiksa') DEFAULT 'Belum Diperiksa',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `daftar_poli`
--

INSERT INTO `daftar_poli` (`id`, `id_pasien`, `id_jadwal`, `keluhan`, `no_antrian`, `status`, `created_at`) VALUES
(46, 13, 10, 'tolong kaki saya sakit banget', 3, 'Belum Diperiksa', '2024-12-30 13:43:25'),
(47, 13, 2, 'gigi saya sakit', 1, 'Sudah Diperiksa', '2024-12-30 15:33:40'),
(49, 13, 13, 'anak saya sakit', 1, 'Belum Diperiksa', '2024-12-31 02:14:05'),
(51, 13, 13, 'anak sakit', 1, 'Belum Diperiksa', '2024-12-31 02:29:20'),
(53, 14, 13, 'asasa', 1, 'Belum Diperiksa', '2024-12-31 02:31:00'),
(54, 14, 13, 'agadgag', 1, 'Belum Diperiksa', '2024-12-31 02:34:14'),
(55, 14, 13, 'fsfsfsf', 1, 'Belum Diperiksa', '2024-12-31 02:34:29'),
(56, 14, 10, 'adad', 5, 'Sudah Diperiksa', '2024-12-31 02:36:49'),
(57, 14, 13, 'adad', 1, 'Belum Diperiksa', '2024-12-31 02:36:59'),
(59, 13, 13, 'asasasde', 1, 'Belum Diperiksa', '2024-12-31 02:40:31'),
(60, 15, 13, 'adaf', 1, 'Belum Diperiksa', '2024-12-31 02:40:51'),
(61, 13, 15, 'dadada', 1, 'Belum Diperiksa', '2024-12-31 02:45:42'),
(62, 14, 15, 'adada', 1, 'Belum Diperiksa', '2024-12-31 02:45:57'),
(63, 15, 10, 'adada', 6, 'Sudah Diperiksa', '2024-12-31 09:49:32'),
(64, 15, 15, 'aadad', 2, 'Belum Diperiksa', '2024-12-31 09:49:44'),
(65, 18, 15, 'dkjgdk', 3, 'Belum Diperiksa', '2024-12-31 09:50:56'),
(66, 21, 15, 'ruyeryu', 4, 'Belum Diperiksa', '2024-12-31 09:51:59'),
(67, 14, 15, 'adfadf', 5, 'Belum Diperiksa', '2024-12-31 09:52:32'),
(68, 14, 10, '  dzzdfd', 7, 'Sudah Diperiksa', '2024-12-31 09:53:13'),
(69, 13, 10, 'adadda', 8, 'Sudah Diperiksa', '2024-12-31 09:53:45'),
(70, 13, 13, 'anak saya sakit', 2, 'Sudah Diperiksa', '2024-12-31 19:38:53'),
(71, 13, 10, 'saya sakit tenggorokan', 1, 'Sudah Diperiksa', '2025-01-01 11:12:05'),
(72, 14, 10, 'saya pilek', 2, 'Sudah Diperiksa', '2025-01-01 11:12:30'),
(73, 13, 10, 'hidung saya sakit', 3, 'Belum Diperiksa', '2025-01-01 14:59:15'),
(74, 13, 10, 'dada saya sakit', 4, 'Belum Diperiksa', '2025-01-01 15:02:46'),
(75, 13, 2, 'gigi saya sakit dok', 1, 'Sudah Diperiksa', '2025-01-01 15:05:18'),
(76, 14, 13, 'anak saya jatuh', 1, 'Sudah Diperiksa', '2025-01-01 22:30:30'),
(77, 14, 2, 'gigi saya nyut nyutan', 1, 'Sudah Diperiksa', '2025-01-02 09:17:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_periksa`
--

CREATE TABLE `detail_periksa` (
  `id` int(11) NOT NULL,
  `id_periksa` int(11) NOT NULL,
  `id_obat` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_periksa`
--

INSERT INTO `detail_periksa` (`id`, `id_periksa`, `id_obat`, `jumlah`) VALUES
(2, 3, 3, 0),
(6, 4, 1, 0),
(11, 16, 1, 1),
(12, 16, 2, 3),
(13, 17, 1, 3),
(14, 17, 2, 1),
(15, 18, 1, 1),
(16, 18, 2, 2),
(44, 20, 1, 1),
(45, 20, 2, 3),
(46, 14, 1, 1),
(47, 19, 2, 2),
(48, 19, 1, 1),
(49, 19, 5, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokter`
--

CREATE TABLE `dokter` (
  `id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `id_poli` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dokter`
--

INSERT INTO `dokter` (`id`, `nama`, `alamat`, `no_hp`, `id_poli`, `username`, `password`) VALUES
(15, 'Dokter Default', 'Jalan Sehat No. 10', '081234567890', 1, 'dokter', 'd22af4180eee4bd95072eb90f94930e5'),
(16, 'Dokter Tirta', 'Jalan Bahagia No. 21', '085955786124', 2, 'doktertirta', 'f9d50dadc65aaf13bdaad159b81bed39'),
(17, 'Dokter Richard', 'Jalan Menuju Roma No. 12', '085255123444', 4, 'dokterrichard', '44d9284a867b8f8542d1338eb6d6329d'),
(18, 'Dokter Ela', 'Jalan Kedamaian No. 20', '081325157848', 2, 'dokterela', 'e739988d67ab45e398756182c436c183'),
(21, 'Dokter Tio', 'Jalan Menuju Surga No. 1', '088455655211', 3, 'doktertio', '547caa81a5ba5ce95744015488950175'),
(23, 'Dokter Tama', 'Jalan Mustika Jati No. 113', '0895656232316', 4, 'doktertama', 'a4d2e9d80ab7e38935d654d17dc2c7ae'),
(24, 'Dokter Fira', 'Jalan Pucang Gading No. 122', '0854652346685', 3, 'dokterfira', '1877677901fdd7f5058bc75ad19e8622'),
(25, 'Dokter Lutfi', 'Jalan Cendrawasih No. 231', '084564789123', 1, 'dokterlutfi', '6c72f41855f5e7e755099749d6654da5');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_periksa`
--

CREATE TABLE `jadwal_periksa` (
  `id` int(11) NOT NULL,
  `id_dokter` int(11) NOT NULL,
  `hari` varchar(10) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `status` enum('Aktif','Tidak Aktif') DEFAULT 'Tidak Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwal_periksa`
--

INSERT INTO `jadwal_periksa` (`id`, `id_dokter`, `hari`, `jam_mulai`, `jam_selesai`, `status`) VALUES
(1, 16, 'Senin', '07:00:00', '09:15:00', 'Tidak Aktif'),
(2, 16, 'Rabu', '14:15:00', '16:00:00', 'Aktif'),
(3, 16, 'Jumat', '08:30:00', '10:30:00', 'Tidak Aktif'),
(6, 16, 'Selasa', '14:00:00', '15:10:00', 'Tidak Aktif'),
(7, 15, 'Senin', '10:20:00', '13:00:00', 'Tidak Aktif'),
(8, 15, 'Rabu', '08:00:00', '10:00:00', 'Tidak Aktif'),
(9, 15, 'Kamis', '07:00:00', '09:00:00', 'Tidak Aktif'),
(10, 15, 'Selasa', '11:30:00', '13:30:00', 'Aktif'),
(11, 21, 'Senin', '10:45:00', '13:00:00', 'Tidak Aktif'),
(12, 21, 'Selasa', '11:30:00', '13:30:00', 'Tidak Aktif'),
(13, 21, 'Jumat', '14:00:00', '15:30:00', 'Aktif'),
(14, 17, 'Senin', '11:00:00', '14:30:00', 'Tidak Aktif'),
(15, 17, 'Selasa', '08:00:00', '13:00:00', 'Aktif'),
(16, 23, 'Jumat', '07:00:00', '11:30:00', 'Aktif'),
(17, 23, 'Kamis', '11:30:00', '15:00:00', 'Tidak Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `obat`
--

CREATE TABLE `obat` (
  `id` int(11) NOT NULL,
  `nama_obat` varchar(50) NOT NULL,
  `kemasan` varchar(50) DEFAULT NULL,
  `harga` int(10) UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `obat`
--

INSERT INTO `obat` (`id`, `nama_obat`, `kemasan`, `harga`) VALUES
(1, 'Paracetamol', 'Strip', 5000),
(2, 'Amoxicillin', 'Botol', 15000),
(3, 'Ibuprofen', 'Strip', 10000),
(4, 'Cetirizine', 'Box', 7000),
(5, 'Vitamin C', 'Botol', 3000),
(6, 'Konidin', 'Botol', 14000),
(10, 'Woods', 'Botol', 12000),
(11, 'Atropin tetes mata 0,5%', 'Botol 5 ml', 10000),
(12, 'Azatioprin tablet 50 mg', 'Strip 10 tablet', 25000),
(13, 'Benzatin Benzil Penisilin', 'Vial 1,2 Juta IU', 50000),
(14, 'Besi (II) Sulfat tablet', 'Strip 10 tablet salut 300 mg', 15000),
(15, 'Atenolol tablet 50 mg', 'Strip 10 tablet', 20000),
(16, 'Bisoprolol tablet 5 mg', 'Strip 10 tablet', 30000),
(17, 'Bromheksin tablet 8 mg', 'Strip 10 tablet', 10000),
(18, 'Cetirizine sirup 5 mg/5 ml', 'Botol 60 ml', 15000),
(19, 'Dexamethasone tablet 0,5 mg', 'Strip 10 tablet', 5000),
(20, 'Glibenklamid tablet 5 mg', 'Strip 10 tablet', 10000),
(21, 'Gliklazid tablet 80 mg', 'Strip 10 tablet', 25000),
(22, 'Glimepirid tablet 2 mg', 'Strip 10 tablet', 30000),
(23, 'Glipizid tablet 5 mg', 'Strip 10 tablet', 20000),
(24, 'Gliseril trinitrat tablet 0,5 mg', 'Strip 10 tablet', 15000),
(25, 'Lidokain injeksi 50 mg/ml', 'Ampul 5 ml', 25000),
(26, 'Medroksi progesteron tablet 5 mg', 'Strip 10 tablet', 20000),
(27, 'Metformin tablet 500 mg', 'Strip 10 tablet', 10000),
(28, 'Nifedipin tablet 10 mg', 'Strip 10 tablet', 15000),
(29, 'Omeprazol kapsul 20 mg', 'Strip 10 kapsul', 25000),
(30, 'Ranitidin tablet 150 mg', 'Strip 10 tablet', 15000),
(31, 'Simvastatin tablet 10 mg', 'Strip 10 tablet', 20000),
(32, 'Amlodipin tablet 5 mg', 'Strip 10 tablet', 25000),
(33, 'Captopril tablet 25 mg', 'Strip 10 tablet', 10000),
(34, 'Furosemid tablet 40 mg', 'Strip 10 tablet', 15000),
(35, 'Spironolakton tablet 25 mg', 'Strip 10 tablet', 20000),
(36, 'Hidroklorotiazid tablet 25 mg', 'Strip 10 tablet', 10000),
(37, 'Asam Mefenamat tablet 500 mg', 'Strip 10 tablet', 20000),
(38, 'Diklofenak natrium tablet 50 mg', 'Strip 10 tablet', 25000),
(39, 'Asiklovir tablet 400 mg', 'Strip 10 tablet', 30000),
(40, 'Ketokonazol tablet 200 mg', 'Strip 10 tablet', 25000),
(41, 'Metronidazol tablet 500 mg', 'Strip 10 tablet', 20000),
(42, 'Kloramfenikol kapsul 250 mg', 'Strip 10 kapsul', 15000),
(43, 'Eritromisin tablet 250 mg', 'Strip 10 tablet', 25000),
(44, 'Doksisiklin kapsul 100 mg', 'Strip 10 kapsul', 20000),
(45, 'Amoksisilin sirup 125 mg/5 ml', 'Botol 60 ml', 15000),
(46, 'Cefadroksil kapsul 500 mg', 'Strip 10 kapsul', 35000),
(47, 'Sefiksim kapsul 100 mg', 'Strip 10 kapsul', 40000),
(48, 'Gentamisin injeksi 40 mg/ml', 'Ampul 2 ml', 30000),
(49, 'Kotrimoksazol tablet 480 mg', 'Strip 10 tablet', 15000),
(50, 'Levofloksasin tablet 500 mg', 'Strip 10 tablet', 50000),
(51, 'Moksifloksasin tablet 400 mg', 'Strip 10 tablet', 60000),
(52, 'Rifampisin kapsul 300 mg', 'Strip 10 kapsul', 25000),
(53, 'Isoniazid tablet 300 mg', 'Strip 10 tablet', 20000),
(54, 'Pirazinamid tablet 500 mg', 'Strip 10 tablet', 25000),
(55, 'Etambutol tablet 400 mg', 'Strip 10 tablet', 30000),
(56, 'Streptomisin injeksi 1 g', 'Vial', 40000),
(57, 'Dapson tablet 100 mg', 'Strip 10 tablet', 35000),
(58, 'Klorokuin tablet 250 mg', 'Strip 10 tablet', 20000),
(59, 'Artemeter injeksi 80 mg/ml', 'Ampul 1 ml', 50000),
(60, 'Lumefantrin tablet 120 mg', 'Strip 10 tablet', 45000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pasien`
--

CREATE TABLE `pasien` (
  `id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_ktp` varchar(16) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `no_rm` char(10) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pasien`
--

INSERT INTO `pasien` (`id`, `nama`, `alamat`, `no_ktp`, `no_hp`, `no_rm`, `username`, `password`) VALUES
(13, 'Pasien Default', 'Jalan Kebenaran No. 20', '3143141351513531', '084564789123', '202412-1', 'pasien', 'f5c25a0082eb0748faedca1ecdcfb131'),
(14, 'Pasien Dua', 'Jalan Cinta Kerinduan No 2', '3325456978541125', '085465789425', '202412-2', 'pasien2', 'b601ceaa524d491ab929f6943464dbac'),
(15, 'Pasien Tiga', 'Jalan Kebahagiaan No. 21', '3318454621360547', '084564789123', '202412-3', 'pasien3', '3a13f50cf7bc2cece19355b9340e91e2'),
(18, 'Pasien Lima', 'Jalan Cendrawasih No. 27', '3318454621360111', '084564784568', '202412-5', 'pasien5', 'bdd83d1c6c8fafb3b86e5b8ce9efa291'),
(21, 'Pasien Enam', 'Padaran', '3314521364879987', '084564789123', '202412-6', 'pasien6', '95444b7af22525c7c03cd80d76489df8'),
(23, 'Pasien Delapan', 'Jalan jalan no 11', '1211212121548454', '084564789123', '202412-8', 'pasien8', 'a99c57266fd9051b1f82c3a9b9a7d951'),
(48, 'aaa', 'fadd', '1341341', '13315135', '202412-9', 'eqqetqet', 'f9e2cb7b1b1436182d16b29d81b6bf78'),
(49, 'qreqtqe', 'eqteqt', '1234431', '13431', '202412-4', '13413', '0cd53149df9fefc9e3baa7d8d6e129b7'),
(50, 'ttwwr', 'rwyyryw', '53213', '315315135', '202412-7', 'teuute', '64e184540facc3ef3307b6d24f1648e6'),
(51, 'dgagadga', 'dagdga', '322462', '3515246', '202412-11', 'dgadagdag', 'fcee6aed58aafcf96d406f549e222f21'),
(52, 'adfdaf', 'adfdaf', '31441', '31531', '202412-12', 'etqte', 'a38178c46f37e626c3261b4af703006a'),
(53, 'Destio Wahyu', 'AGADG', '244225', '5245', '202412-13', 'destiowahyu', 'b08a30200bad6bca25e6443f6bb6fe21'),
(54, 'wrtr', 'trwrt', '364536', '4262', '202412-14', 'syr', '1f4dd9726f356f37a13f7c38e2c4dda4'),
(55, 'gadagd', 'adgda', '54254', '2454245', '202412-15', 'shfhfs', 'da0fc62a21bd06dc835f6c981739ef9f'),
(56, 'etwrtrwt', 'rwtr', '24524', '425445', '202412-16', 'wtrwtrw', 'fc53441220812c270838d4848d4429ff'),
(57, 'agdda', 'gadda', '315315', '531135315', '202412-17', 'dagadg', 'd1a4c4b08d921fa84274746998830b33'),
(58, 'Muhammad Archibald Wibisono Albanin', 'Bawen', '32324646523302', '0895655986565', '202412-10', 'archibald', 'f560595ec5b5bab263a2f688f157b9ad'),
(59, 'asas', 'assas', '322424242', '32323232', '202412-18', '13531', 'acb797b40a9632e6a031b31100b37630');

-- --------------------------------------------------------

--
-- Struktur dari tabel `periksa`
--

CREATE TABLE `periksa` (
  `id` int(11) NOT NULL,
  `id_daftar_poli` int(11) NOT NULL,
  `tgl_periksa` datetime NOT NULL,
  `catatan` text NOT NULL,
  `biaya_periksa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `periksa`
--

INSERT INTO `periksa` (`id`, `id_daftar_poli`, `tgl_periksa`, `catatan`, `biaya_periksa`) VALUES
(1, 69, '2024-12-31 00:00:00', 'istirahat ya mas', 155000),
(2, 69, '2024-12-31 00:00:00', 'istirahat mas\r\n', 167000),
(3, 69, '2024-12-31 00:00:00', 'koskok', 160000),
(4, 68, '2024-12-31 00:00:00', 'makan mas', 150000),
(5, 63, '2024-12-31 00:00:00', 'banyakin minum obat kak', 150000),
(6, 56, '2024-12-31 00:00:00', 'buat jalan jalan pak', 150000),
(7, 70, '2024-12-31 00:00:00', 'tolong anaknya jangan kebanyakan main', 150000),
(8, 71, '2025-01-01 00:00:00', 'jangan banyak minum es', 150000),
(9, 72, '2025-01-01 19:04:00', 'jangan minum es', 150000),
(10, 72, '2025-01-01 19:04:00', 'jangan minum es', 150000),
(12, 72, '2025-01-01 19:04:00', 'jangan minum es', 150000),
(14, 75, '2025-01-01 15:05:00', 'jangan makan es', 155000),
(15, 75, '2025-01-01 15:05:00', 'jangan makan es', 155000),
(16, 76, '2025-01-01 22:39:00', 'hati hati pak', 200000),
(17, 76, '2025-01-01 22:42:00', 'hati hati pak', 180000),
(18, 76, '2025-01-01 22:50:00', 'hati hati', 185000),
(19, 77, '2025-01-02 09:20:00', 'jangan makan batu bata pak', 194000),
(20, 47, '2025-01-02 13:01:00', 'sikat gigi', 200000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `poli`
--

CREATE TABLE `poli` (
  `id` int(11) NOT NULL,
  `nama_poli` varchar(25) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `poli`
--

INSERT INTO `poli` (`id`, `nama_poli`, `keterangan`) VALUES
(1, 'Umum', 'Pelayanan kedokteran umum kepada pasien.'),
(2, 'Gigi', 'Pelayanan kesehatan gigi yang bertujuan untuk menjaga kesehatan gigi dan mulut pasien secara umum.'),
(3, 'Anak', 'Layanan kesehatan yang menangani pasien anak-anak dari bayi hingga remajat.'),
(4, 'Jantung', 'Layanan kesehatan yang menangani masalah jantung dan pembuluh darah (kardiovaskular). ');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `daftar_poli`
--
ALTER TABLE `daftar_poli`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pasien` (`id_pasien`),
  ADD KEY `id_jadwal` (`id_jadwal`);

--
-- Indeks untuk tabel `detail_periksa`
--
ALTER TABLE `detail_periksa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_periksa` (`id_periksa`),
  ADD KEY `id_obat` (`id_obat`);

--
-- Indeks untuk tabel `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_poli` (`id_poli`);

--
-- Indeks untuk tabel `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_dokter` (`id_dokter`);

--
-- Indeks untuk tabel `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `periksa`
--
ALTER TABLE `periksa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_daftar_poli` (`id_daftar_poli`);

--
-- Indeks untuk tabel `poli`
--
ALTER TABLE `poli`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `daftar_poli`
--
ALTER TABLE `daftar_poli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT untuk tabel `detail_periksa`
--
ALTER TABLE `detail_periksa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `dokter`
--
ALTER TABLE `dokter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `obat`
--
ALTER TABLE `obat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT untuk tabel `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT untuk tabel `periksa`
--
ALTER TABLE `periksa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `poli`
--
ALTER TABLE `poli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `daftar_poli`
--
ALTER TABLE `daftar_poli`
  ADD CONSTRAINT `daftar_poli_ibfk_1` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id`),
  ADD CONSTRAINT `daftar_poli_ibfk_2` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_periksa` (`id`);

--
-- Ketidakleluasaan untuk tabel `detail_periksa`
--
ALTER TABLE `detail_periksa`
  ADD CONSTRAINT `detail_periksa_ibfk_1` FOREIGN KEY (`id_periksa`) REFERENCES `periksa` (`id`),
  ADD CONSTRAINT `detail_periksa_ibfk_2` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id`);

--
-- Ketidakleluasaan untuk tabel `dokter`
--
ALTER TABLE `dokter`
  ADD CONSTRAINT `fk_id_poli` FOREIGN KEY (`id_poli`) REFERENCES `poli` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  ADD CONSTRAINT `fk_id_dokter` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id`);

--
-- Ketidakleluasaan untuk tabel `periksa`
--
ALTER TABLE `periksa`
  ADD CONSTRAINT `periksa_ibfk_1` FOREIGN KEY (`id_daftar_poli`) REFERENCES `daftar_poli` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
