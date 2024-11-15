-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 15, 2024 at 02:10 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventorilabbiologi`
--

-- --------------------------------------------------------

--
-- Table structure for table `barangs`
--

CREATE TABLE `barangs` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_barang_id` bigint UNSIGNED NOT NULL,
  `stok` decimal(10,2) NOT NULL,
  `kadaluarsa` date DEFAULT NULL,
  `lokasi` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barangs`
--

INSERT INTO `barangs` (`id`, `nama_barang`, `jenis_barang_id`, `stok`, `kadaluarsa`, `lokasi`, `created_at`, `updated_at`) VALUES
(1, 'HCl', 1, '93.50', '2024-11-14', 'Rak a11', '2024-10-26 19:04:54', '2024-11-13 19:06:38'),
(2, 'Pipet', 4, '104.00', NULL, 'Rak a100', '2024-10-26 19:22:16', '2024-11-14 04:10:31'),
(3, 'Etanol', 1, '114.00', '2024-11-14', 'Rak a4', '2024-10-26 19:49:52', '2024-11-13 19:06:46'),
(4, 'Mikroskop', 4, '100.00', NULL, 'Rak a1', '2024-11-09 22:57:20', '2024-11-09 22:57:20');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('admina@gmail.com|127.0.0.1', 'i:1;', 1731486819),
('admina@gmail.com|127.0.0.1:timer', 'i:1731486819;', 1731486819);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_penerimaan_barangs`
--

CREATE TABLE `detail_penerimaan_barangs` (
  `id` bigint UNSIGNED NOT NULL,
  `master_penerimaan_barang_id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `jumlah_diterima` decimal(15,2) NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `total_harga` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_penerimaan_barangs`
--

INSERT INTO `detail_penerimaan_barangs` (`id`, `master_penerimaan_barang_id`, `barang_id`, `jumlah_diterima`, `harga`, `total_harga`, `created_at`, `updated_at`) VALUES
(1, 8, 1, '12.00', '100000.00', '1200000.00', '2024-11-02 08:28:51', '2024-11-02 08:28:51'),
(3, 12, 2, '4.00', '8000.00', '32000.00', '2024-11-02 09:29:22', '2024-11-14 04:10:31'),
(4, 13, 1, '20.00', '20000.00', '400000.00', '2024-11-02 09:49:26', '2024-11-09 21:34:19');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pengeluaran_barangs`
--

CREATE TABLE `detail_pengeluaran_barangs` (
  `id` bigint UNSIGNED NOT NULL,
  `master_pengeluaran_barang_id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `jumlah_keluar` decimal(15,2) NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `total_harga` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_pengeluaran_barangs`
--

INSERT INTO `detail_pengeluaran_barangs` (`id`, `master_pengeluaran_barang_id`, `barang_id`, `jumlah_keluar`, `harga`, `total_harga`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '1.00', '20000.00', '20000.00', '2024-11-07 01:00:35', '2024-11-07 06:21:04'),
(3, 3, 1, '6.00', '2000.00', '12000.00', '2024-11-07 01:04:28', '2024-11-07 06:42:30');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_barangs`
--

CREATE TABLE `jenis_barangs` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_jenis_barang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan_stok` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_barangs`
--

INSERT INTO `jenis_barangs` (`id`, `nama_jenis_barang`, `satuan_stok`, `created_at`, `updated_at`) VALUES
(1, 'Reagen', 'ml', '2024-10-26 18:46:56', '2024-10-26 20:04:27'),
(2, 'Larutan Buffer', 'ml', '2024-10-26 18:48:43', '2024-11-09 22:58:36'),
(4, 'Alat', 'per alat', '2024-10-26 19:05:27', '2024-11-09 22:58:12'),
(5, 'Agar-Agar', 'gram', '2024-10-26 19:50:12', '2024-11-09 22:59:26'),
(6, 'Media Kultur', 'ml', '2024-11-09 22:59:44', '2024-11-09 22:59:44'),
(7, 'Pewarna', 'ml', '2024-11-09 22:59:55', '2024-11-09 22:59:55');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_penerimaan_barangs`
--

CREATE TABLE `jenis_penerimaan_barangs` (
  `id` bigint UNSIGNED NOT NULL,
  `jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_penerimaan_barangs`
--

INSERT INTO `jenis_penerimaan_barangs` (`id`, `jenis`, `created_at`, `updated_at`) VALUES
(1, 'Barang masuk supplier', '2024-11-02 10:50:44', '2024-11-02 10:50:44'),
(3, 'Sampel', '2024-11-06 20:48:53', '2024-11-09 23:01:28'),
(7, 'Sisa', '2024-11-06 21:23:17', '2024-11-09 23:01:55');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_pengeluaran_barangs`
--

CREATE TABLE `jenis_pengeluaran_barangs` (
  `id` bigint UNSIGNED NOT NULL,
  `jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_pengeluaran_barangs`
--

INSERT INTO `jenis_pengeluaran_barangs` (`id`, `jenis`, `created_at`, `updated_at`) VALUES
(1, 'Praktikum', '2024-11-07 07:58:51', '2024-11-07 07:58:51'),
(2, 'Buang / Kadaluarsa', '2024-11-07 01:06:32', '2024-11-07 01:06:32');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_penerimaan_barangs`
--

CREATE TABLE `master_penerimaan_barangs` (
  `id` bigint UNSIGNED NOT NULL,
  `jenis_id` bigint UNSIGNED NOT NULL,
  `supkonpro_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_pengantar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_penerimaan_barangs`
--

INSERT INTO `master_penerimaan_barangs` (`id`, `jenis_id`, `supkonpro_id`, `user_id`, `nama_pengantar`, `keterangan`, `created_at`, `updated_at`) VALUES
(8, 1, 1, 1, 'andre', 'sampel', '2024-11-02 08:28:51', '2024-11-02 08:28:51'),
(12, 1, 1, 1, 'kakak', 'pembelian', '2024-11-02 09:29:22', '2024-11-14 04:10:31'),
(13, 1, 1, 1, 'kakak', 'sampel', '2024-11-02 09:49:26', '2024-11-09 21:34:19');

-- --------------------------------------------------------

--
-- Table structure for table `master_pengeluaran_barangs`
--

CREATE TABLE `master_pengeluaran_barangs` (
  `id` bigint UNSIGNED NOT NULL,
  `jenis_id` bigint UNSIGNED NOT NULL,
  `supkonpro_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_pengambil` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_pengeluaran_barangs`
--

INSERT INTO `master_pengeluaran_barangs` (`id`, `jenis_id`, `supkonpro_id`, `user_id`, `nama_pengambil`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 'siska', 'praktikum bio', '2024-11-07 01:00:35', '2024-11-07 06:21:04'),
(3, 2, 1, 1, 'koko', 'kadaluarsa', '2024-11-07 01:04:28', '2024-11-07 06:42:30');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_10_17_083125_create_jenis_barangs_table', 1),
(5, '2024_10_17_113851_create_barangs_table', 1),
(6, '2024_10_27_031428_create_saldo_awals_table', 2),
(9, '2024_10_27_045404_create_supkonpros_table', 3),
(10, '2024_11_02_094344_create_penerimaan_barangs_table', 3),
(11, '2024_11_07_043432_create_pengeluaran_barangs_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saldo_awals`
--

CREATE TABLE `saldo_awals` (
  `id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `tahun` year NOT NULL,
  `bulan` tinyint UNSIGNED NOT NULL,
  `saldo_awal` decimal(15,2) NOT NULL,
  `total_terima` decimal(15,2) NOT NULL,
  `total_keluar` decimal(15,2) NOT NULL,
  `saldo_akhir` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `saldo_awals`
--

INSERT INTO `saldo_awals` (`id`, `barang_id`, `tahun`, `bulan`, `saldo_awal`, `total_terima`, `total_keluar`, `saldo_akhir`, `created_at`, `updated_at`) VALUES
(1, 1, 2024, 1, '0.00', '100000.00', '10000.00', '90000.00', '2024-10-26 21:42:58', '2024-10-26 21:42:58'),
(2, 1, 2024, 2, '90000.00', '100000.00', '20000.00', '170000.00', '2024-10-26 21:45:17', '2024-10-26 21:45:17'),
(3, 2, 2024, 1, '0.00', '0.00', '0.00', '0.00', '2024-10-26 21:46:09', '2024-10-26 21:46:09'),
(6, 2, 2024, 2, '0.00', '700000.00', '700000.00', '0.00', '2024-11-02 08:39:54', '2024-11-02 08:39:54'),
(7, 1, 2024, 3, '170000.00', '900000.00', '900000.00', '170000.00', '2024-11-02 08:40:16', '2024-11-02 08:40:16'),
(18, 1, 2024, 10, '900000.00', '100000.00', '100000.00', '900000.00', '2024-11-09 20:23:47', '2024-11-09 20:23:47'),
(19, 1, 2024, 11, '900000.00', '100000.00', '100000.00', '900000.00', '2024-11-09 20:30:44', '2024-11-09 20:30:44'),
(20, 2, 2024, 11, '0.00', '50000000.00', '20000000.00', '30000000.00', '2024-11-09 21:19:42', '2024-11-09 21:19:42'),
(21, 1, 2024, 4, '170000.00', '100000.00', '0.00', '270000.00', '2024-11-14 04:07:52', '2024-11-14 04:07:52');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('qDdRiWNROEycghBd5Vp7WCmtInJKEAxUkomjoLc2', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTDlEVDZyZzhkYk0zNmw3T2Z1ZW51SFhJNm5KbWhjVldYWDNkZDdlWSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9pbnZlbnRvcmlsYWJiaW9sb2dpLnRlc3Q6ODA4MCI7fX0=', 1731583137),
('z95emTlIIMdqwQOuSPqP4TburbtifzHZ6VdJlGRn', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieTllS1JsQnNRMHAxa3BUV3kwQmJkWnpmT0l1bXRMSHk4aVVXOTlESCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9pbnZlbnRvcmlsYWJiaW9sb2dpLnRlc3Q6ODA4MCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1731636597);

-- --------------------------------------------------------

--
-- Table structure for table `supkonpros`
--

CREATE TABLE `supkonpros` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kota` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` enum('supplier','konsumen','proyek') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supkonpros`
--

INSERT INTO `supkonpros` (`id`, `nama`, `alamat`, `kota`, `telepon`, `email`, `jenis`, `status`, `created_at`, `updated_at`) VALUES
(1, 'supplier 1', 'jl bandung', 'bandung', '1234', 'supplier1@gmail.com', 'supplier', 'aktif', '2024-11-02 03:49:21', '2024-11-02 03:49:21'),
(2, 'konsumen 1', 'jl bandung', 'bandung', '1234', 'konsumen1@gmail.com', 'konsumen', 'aktif', '2024-11-02 03:49:50', '2024-11-02 03:49:50'),
(3, 'proyek 1', 'jl bandung', 'bandung', '12334', 'proyek1@gmail.com', 'proyek', 'aktif', '2024-11-02 03:50:12', '2024-11-14 03:26:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('super admin','admin','user') COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `role`, `email`, `phone_number`, `password`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'super admin', 'superadmin@gmail.com', '1234', '$2y$12$.RLDZpsDTWZ5iemEkEQVS.Gdiu79V7adyulSjObIOGXCvEn01VMF6', NULL, NULL, NULL, '2024-11-09 23:04:12'),
(2, 'Admin1', 'admin', 'admin@gmail.com', '123', '$2y$12$x3BRccN7vghPSd9AibG3TeyAAyRxlyfGDSou629hsKrBN6uk5X3tC', NULL, NULL, '2024-10-27 07:07:40', '2024-11-09 23:03:12'),
(3, 'User1', 'user', 'user@gmail.com', '1234', '$2y$12$5cpa5gz23e0IWfu2/9IokeIlQ1Lc9K5aIQgMMMnZC1darbb6c.Ase', NULL, NULL, '2024-10-27 07:08:20', '2024-11-09 23:03:57'),
(4, 'Admin2', 'admin', 'admin2@gmail.com', '1234', '$2y$12$ptIBmMdhSbFYBL57T0ItTOhvEar8Hf/BCCMu7bWh7AP0/BwhzpLzy', NULL, NULL, '2024-10-27 07:09:37', '2024-11-09 23:03:23'),
(5, 'Admin3', 'admin', 'admin3@gmail.com', '213', '$2y$12$ipVbOEQHRMqIGXGZ9l8tduZUcrYM44CdSrReF4gwBfPk9fQK.ElKu', NULL, NULL, '2024-10-27 07:18:00', '2024-11-09 23:03:40'),
(6, 'Aris', 'user', 'aris@gmail.com', '123', '$2y$12$75PqFK3/rOXbkSxR7LgF1O1N1pU4yfRPKKTTJS5NBPpKkwErld/7G', NULL, NULL, '2024-11-14 03:25:36', '2024-11-14 04:18:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangs`
--
ALTER TABLE `barangs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barangs_jenis_barang_id_foreign` (`jenis_barang_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `detail_penerimaan_barangs`
--
ALTER TABLE `detail_penerimaan_barangs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_penerimaan_barangs_master_penerimaan_barang_id_foreign` (`master_penerimaan_barang_id`),
  ADD KEY `detail_penerimaan_barangs_barang_id_foreign` (`barang_id`);

--
-- Indexes for table `detail_pengeluaran_barangs`
--
ALTER TABLE `detail_pengeluaran_barangs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_pengeluaran_barangs_master_pengeluaran_barang_id_foreign` (`master_pengeluaran_barang_id`),
  ADD KEY `detail_pengeluaran_barangs_barang_id_foreign` (`barang_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jenis_barangs`
--
ALTER TABLE `jenis_barangs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jenis_penerimaan_barangs`
--
ALTER TABLE `jenis_penerimaan_barangs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jenis_pengeluaran_barangs`
--
ALTER TABLE `jenis_pengeluaran_barangs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_penerimaan_barangs`
--
ALTER TABLE `master_penerimaan_barangs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `master_penerimaan_barangs_supkonpro_id_foreign` (`supkonpro_id`),
  ADD KEY `master_penerimaan_barangs_user_id_foreign` (`user_id`),
  ADD KEY `master_penerimaan_barangs_jenis_id_foreign` (`jenis_id`);

--
-- Indexes for table `master_pengeluaran_barangs`
--
ALTER TABLE `master_pengeluaran_barangs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `master_pengeluaran_barangs_jenis_id_foreign` (`jenis_id`),
  ADD KEY `master_pengeluaran_barangs_supkonpro_id_foreign` (`supkonpro_id`),
  ADD KEY `master_pengeluaran_barangs_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `saldo_awals`
--
ALTER TABLE `saldo_awals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `saldo_awals_barang_id_foreign` (`barang_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `supkonpros`
--
ALTER TABLE `supkonpros`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barangs`
--
ALTER TABLE `barangs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `detail_penerimaan_barangs`
--
ALTER TABLE `detail_penerimaan_barangs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `detail_pengeluaran_barangs`
--
ALTER TABLE `detail_pengeluaran_barangs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_barangs`
--
ALTER TABLE `jenis_barangs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jenis_penerimaan_barangs`
--
ALTER TABLE `jenis_penerimaan_barangs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jenis_pengeluaran_barangs`
--
ALTER TABLE `jenis_pengeluaran_barangs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_penerimaan_barangs`
--
ALTER TABLE `master_penerimaan_barangs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `master_pengeluaran_barangs`
--
ALTER TABLE `master_pengeluaran_barangs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `saldo_awals`
--
ALTER TABLE `saldo_awals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `supkonpros`
--
ALTER TABLE `supkonpros`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barangs`
--
ALTER TABLE `barangs`
  ADD CONSTRAINT `barangs_jenis_barang_id_foreign` FOREIGN KEY (`jenis_barang_id`) REFERENCES `jenis_barangs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `detail_penerimaan_barangs`
--
ALTER TABLE `detail_penerimaan_barangs`
  ADD CONSTRAINT `detail_penerimaan_barangs_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_penerimaan_barangs_master_penerimaan_barang_id_foreign` FOREIGN KEY (`master_penerimaan_barang_id`) REFERENCES `master_penerimaan_barangs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `detail_pengeluaran_barangs`
--
ALTER TABLE `detail_pengeluaran_barangs`
  ADD CONSTRAINT `detail_pengeluaran_barangs_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_pengeluaran_barangs_master_pengeluaran_barang_id_foreign` FOREIGN KEY (`master_pengeluaran_barang_id`) REFERENCES `master_pengeluaran_barangs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `master_penerimaan_barangs`
--
ALTER TABLE `master_penerimaan_barangs`
  ADD CONSTRAINT `master_penerimaan_barangs_jenis_id_foreign` FOREIGN KEY (`jenis_id`) REFERENCES `jenis_penerimaan_barangs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `master_penerimaan_barangs_supkonpro_id_foreign` FOREIGN KEY (`supkonpro_id`) REFERENCES `supkonpros` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `master_penerimaan_barangs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `master_pengeluaran_barangs`
--
ALTER TABLE `master_pengeluaran_barangs`
  ADD CONSTRAINT `master_pengeluaran_barangs_jenis_id_foreign` FOREIGN KEY (`jenis_id`) REFERENCES `jenis_pengeluaran_barangs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `master_pengeluaran_barangs_supkonpro_id_foreign` FOREIGN KEY (`supkonpro_id`) REFERENCES `supkonpros` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `master_pengeluaran_barangs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `saldo_awals`
--
ALTER TABLE `saldo_awals`
  ADD CONSTRAINT `saldo_awals_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
