-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 11, 2026 at 09:18 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `griya_sehat`
--

-- --------------------------------------------------------

--
-- Table structure for table `antrians`
--

CREATE TABLE `antrians` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `kode_antrian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `poli` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dokter` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `keluhan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('menunggu','diproses','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialization` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `specialization`, `schedule`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Catty Santoso, B.Med', '', 'Jumat | 13.00 - 17.00', NULL, 1, '2026-02-05 22:05:53', '2026-02-05 22:05:53'),
(2, 'Retnawati, B.Med., B.Ed', '', 'Selasa & Kamis | 13.00 - 18.00', NULL, 1, '2026-02-05 22:05:53', '2026-02-05 22:05:53'),
(3, 'Alfredo Aldo E. P. Tjundawan, B.Med., M.MED.', '', 'Senin 08.00-13.00 | Rabu & Jumat 18.00-21.00', NULL, 1, '2026-02-05 22:05:53', '2026-02-05 22:05:53'),
(4, 'Impian Delillah Jazmine, S.Tr.Battra', '', 'Selasa & Kamis | 18.00 - 21.00', NULL, 1, '2026-02-05 22:05:53', '2026-02-05 22:05:53'),
(5, 'Fadilla Ilmi Zarkasi, S.Tr.Battra', '', 'Selasa & Jumat | 18.00 - 21.00', NULL, 1, '2026-02-05 22:05:53', '2026-02-05 22:05:53');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_schedules`
--

CREATE TABLE `doctor_schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `doctor_id` bigint UNSIGNED NOT NULL,
  `day_of_week` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `quota` int NOT NULL DEFAULT '20',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctor_schedules`
--

INSERT INTO `doctor_schedules` (`id`, `doctor_id`, `day_of_week`, `start_time`, `end_time`, `quota`, `is_active`, `created_at`, `updated_at`) VALUES
(26, 1, 'Jumat', '13:00:00', '17:00:00', 999, 1, '2026-02-11 02:07:27', '2026-02-11 02:07:27'),
(27, 2, 'Selasa', '13:00:00', '18:00:00', 999, 1, '2026-02-11 02:07:58', '2026-02-11 02:07:58'),
(28, 2, 'Kamis', '13:00:00', '18:00:00', 999, 1, '2026-02-11 02:08:20', '2026-02-11 02:08:20'),
(29, 3, 'Senin', '08:00:00', '13:00:00', 999, 1, '2026-02-11 02:08:43', '2026-02-11 02:08:43'),
(30, 3, 'Rabu', '18:00:00', '21:00:00', 999, 1, '2026-02-11 02:09:03', '2026-02-11 02:09:03'),
(31, 3, 'Jumat', '18:00:00', '21:00:00', 999, 1, '2026-02-11 02:09:20', '2026-02-11 02:09:20'),
(32, 4, 'Selasa', '18:00:00', '21:00:00', 999, 1, '2026-02-11 02:10:04', '2026-02-11 02:10:04'),
(33, 4, 'Kamis', '18:00:00', '21:00:00', 999, 1, '2026-02-11 02:10:27', '2026-02-11 02:10:27'),
(34, 5, 'Selasa', '18:00:00', '21:00:00', 999, 1, '2026-02-11 02:10:50', '2026-02-11 02:10:50'),
(35, 5, 'Jumat', '18:00:00', '21:00:00', 999, 1, '2026-02-11 02:11:13', '2026-02-11 02:11:13');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `id` bigint UNSIGNED NOT NULL,
  `patient_id` bigint UNSIGNED NOT NULL,
  `terapis_id` bigint UNSIGNED NOT NULL,
  `queue_id` bigint UNSIGNED DEFAULT NULL,
  `complaint` text COLLATE utf8mb4_unicode_ci,
  `anamnesis` text COLLATE utf8mb4_unicode_ci,
  `riwayat_penyakit` text COLLATE utf8mb4_unicode_ci,
  `diagnosis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diagnosis_awal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diagnosis_akhir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `treatment` text COLLATE utf8mb4_unicode_ci,
  `pengobatan` text COLLATE utf8mb4_unicode_ci,
  `medicine` text COLLATE utf8mb4_unicode_ci,
  `obat_diberikan` text COLLATE utf8mb4_unicode_ci,
  `doctor_note` text COLLATE utf8mb4_unicode_ci,
  `catatan_tambahan` text COLLATE utf8mb4_unicode_ci,
  `checkup_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_28_075119_add_phone_address_to_users_table', 1),
(5, '2025_12_15_070655_create_patient_histories_table', 2),
(6, '2026_02_05_051428_create_antrians_table', 3),
(7, '2026_02_05_060712_add_queue_fields_to_patient_histories_table', 4),
(8, '2026_02_05_061242_add_role_to_users_table', 5),
(9, '2026_02_06_045331_create_doctors_table', 6),
(10, '2026_02_06_050947_create_services_table', 7),
(11, '2026_02_07_181459_create_medical_records_table', 8),
(12, '2026_02_08_054231_add_fields_to_medical_records_table', 9),
(13, '2026_02_08_075000_fix_medical_records_table', 10),
(14, '2026_02_08_100000_recreate_medical_records_table', 11),
(15, '2026_02_11_062237_create_doctor_schedules_table', 12),
(16, '2026_02_11_063055_add_booking_fields_to_patient_histories_table', 13),
(18, '2026_02_11_182321_update_patient_histories_confirmed_at_timezone', 14),
(19, '2026_02_11_220013_create_pharmacy_products_table', 14),
(20, '2026_02_12_035353_add_reset_token_to_users_table', 15);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_histories`
--

CREATE TABLE `patient_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_antrian` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `patient_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visit_date` date DEFAULT NULL,
  `service` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `arrival_status` enum('Belum Hadir','Sudah Hadir','Tidak Hadir') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Belum Hadir',
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `poli` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dokter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `keluhan` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy_products`
--

CREATE TABLE `pharmacy_products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `tokopedia_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Akupuntur Biasa', 1, '2026-02-05 22:11:17', '2026-02-05 22:11:17'),
(2, 'Akupuntur Cepat', 1, '2026-02-05 22:11:17', '2026-02-05 22:11:17'),
(3, 'Akupuntur Kilat', 1, '2026-02-05 22:11:17', '2026-02-05 22:11:17'),
(4, 'Akupuntur Mengeluarkan Darah', 1, '2026-02-05 22:11:17', '2026-02-05 22:11:17'),
(5, 'Kop Jalan', 1, '2026-02-05 22:11:17', '2026-02-05 22:11:17'),
(6, 'Kop Kilat', 1, '2026-02-05 22:11:17', '2026-02-05 22:11:17'),
(7, 'Kop Tinggal', 1, '2026-02-05 22:11:17', '2026-02-05 22:11:17'),
(8, 'Kop Lengkap', 1, '2026-02-05 22:11:17', '2026-02-05 22:11:17'),
(9, 'Konsultasi + Resep', 1, '2026-02-05 22:11:17', '2026-02-05 22:11:17'),
(10, 'Totok Wajah (25 menit)', 1, '2026-02-05 22:11:17', '2026-02-05 22:11:17'),
(11, 'Pengobatan Tradisional Lengkap', 1, '2026-02-05 22:11:17', '2026-02-05 22:11:17'),
(12, 'Pengobatan Tradisional Khusus ABK', 1, '2026-02-05 22:11:17', '2026-02-05 22:11:17'),
(13, 'Kop / bagian', 1, '2026-02-05 22:11:17', '2026-02-05 22:11:17');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('FaqX5rQzY9htUlgvxvqDwV8CoCfcQ0EpShJMCKFR', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYmg2cGEwNGl2YzZ1TUhFajJlM3dhdUl4c2tYcGdNcDAyZUhzQ2w1eiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1770841829),
('uQVEjEWzJY40C0hv08PmZ1F0Pbqm5cvjwqtVnGtk', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTklJMTN0dG1INU1PRlNLYlF3bllLZkFXT1ExZnFQS3dPb2JnNGxZbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1770844369);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_token_expires_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `address`, `email`, `role`, `email_verified_at`, `password`, `reset_token`, `reset_token_expires_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(14, 'catty santoso', '0845678910', 'JL. Dr. Ir. H. Soekarno No.201b', 'cattysantoso@gmail.com', 'terapis', NULL, '$2y$12$QfavMgfp5ByjLczJej8Xdu8sOduS7Xb9pcEC9tGN/ffzYP6IgBmlO', NULL, NULL, NULL, '2026-02-06 22:14:09', '2026-02-06 22:14:09'),
(16, 'admin_griya_sehat', '082227272234', 'JL. Dr. Ir. H. Soekarno No.201b', 'griyasehat@ukdc.ukdc.ac.id', 'admin', NULL, '$2y$12$uzDa9pswWvr.EjDtvfNRheMSdEmtrn5MfCQI2UTh9JDQ0etmrwipG', NULL, NULL, NULL, '2026-02-08 22:58:31', '2026-02-08 22:58:31'),
(17, 'Alfredo Aldo E. P. Tjundawan, B.Med., M.MED', '0812345678910', 'Jl. Dr. Ir. H. Soekarno No.201b', 'AlfredoAldo@gmail.com', 'terapis', NULL, '$2y$12$F5/YlDG37VFU0DKs0m9NRetLYFSbNzScI9i9ObljybRDLVgJ9tcxW', NULL, NULL, NULL, '2026-02-10 21:13:30', '2026-02-10 21:13:30'),
(18, 'Retnawati, B,Med., B.Ed', '08512345678', 'Jl. Dr. Ir. H. Soekarno No.201b', 'retnawati@gmail.com', 'terapis', NULL, '$2y$12$mu9FtPfnwWVTNfH6pp09h.Psi2vVv2q.Au3dqGjp/Jz92c5WWzWeW', NULL, NULL, NULL, '2026-02-11 01:25:18', '2026-02-11 01:25:18'),
(19, 'Fadlila Ilmi Zarkasi, S.Tr.Battra.', '08123568974', 'Jl. Dr. Ir. H. Soekarno No.201b', 'fadlila@gmail.com', 'terapis', NULL, '$2y$12$.bXFAh0rlm8v9UuDARpqAObEJB16xsVkg/MvXUUx6naUq/u23BRkC', NULL, NULL, NULL, '2026-02-11 01:42:23', '2026-02-11 01:42:23'),
(20, 'Impian Delillah Jazmine, S.Tr.Battra', '08987654321', 'Jl. Dr. Ir. H. Soekarno No.201b', 'ImpianDelillah@gmail.com', 'terapis', NULL, '$2y$12$sMKzGtdvgrEUeB9d/5502uuhw1iMTmLpgoGaB554AyVP9KN9uXElC', NULL, NULL, NULL, '2026-02-11 01:44:47', '2026-02-11 01:44:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `antrians`
--
ALTER TABLE `antrians`
  ADD PRIMARY KEY (`id`),
  ADD KEY `antrians_user_id_foreign` (`user_id`);

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
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_schedules_doctor_id_foreign` (`doctor_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medical_records_patient_id_foreign` (`patient_id`),
  ADD KEY `medical_records_terapis_id_foreign` (`terapis_id`),
  ADD KEY `medical_records_queue_id_foreign` (`queue_id`);

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
-- Indexes for table `patient_histories`
--
ALTER TABLE `patient_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_histories_user_id_foreign` (`user_id`);

--
-- Indexes for table `pharmacy_products`
--
ALTER TABLE `pharmacy_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT for table `antrians`
--
ALTER TABLE `antrians`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `patient_histories`
--
ALTER TABLE `patient_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pharmacy_products`
--
ALTER TABLE `pharmacy_products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `antrians`
--
ALTER TABLE `antrians`
  ADD CONSTRAINT `antrians_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  ADD CONSTRAINT `doctor_schedules_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD CONSTRAINT `medical_records_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `medical_records_queue_id_foreign` FOREIGN KEY (`queue_id`) REFERENCES `patient_histories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `medical_records_terapis_id_foreign` FOREIGN KEY (`terapis_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patient_histories`
--
ALTER TABLE `patient_histories`
  ADD CONSTRAINT `patient_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
