-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 03, 2026 at 12:24 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `izzati-scheduling`
--

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
('laravel-cache-popup_seen_5_2025-12-28', 'b:1;', 1766941200);

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
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `job_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job_type` bigint UNSIGNED NOT NULL,
  `job_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `otw_at` timestamp NULL DEFAULT NULL,
  `arrived_at` timestamp NULL DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `editor_started_at` timestamp NULL DEFAULT NULL,
  `editor_finished_at` timestamp NULL DEFAULT NULL,
  `editor_pc` tinyint UNSIGNED DEFAULT NULL,
  `finished_at` timestamp NULL DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` enum('tf','cash','vendor','unpaid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `proof` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no-proof.img',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('scheduled','otw','arrived','ongoing','done','canceled') COLLATE utf8mb4_unicode_ci DEFAULT 'scheduled',
  `cancel_notes` text COLLATE utf8mb4_unicode_ci,
  `wa_sent_at` timestamp NULL DEFAULT NULL,
  `editor_status` enum('idle','editing','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'idle',
  `result_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `job_title`, `client_name`, `client_phone`, `job_type`, `job_date`, `start_time`, `end_time`, `otw_at`, `arrived_at`, `started_at`, `editor_started_at`, `editor_finished_at`, `editor_pc`, `finished_at`, `location`, `payment_method`, `amount`, `proof`, `notes`, `status`, `cancel_notes`, `wa_sent_at`, `editor_status`, `result_link`, `created_by`, `created_at`, `updated_at`) VALUES
(158, 'Wedding', 'Galih', '888888', 6, '2026-01-01', '22:00:00', '23:59:00', '2026-01-01 14:21:25', '2026-01-01 14:24:28', '2026-01-01 14:27:03', '2026-01-01 14:35:16', '2026-01-01 14:48:17', 4, '2026-01-01 14:28:29', 'BIG', 'unpaid', 0.00, 'no-proof.img', 'video (IZS)', 'done', NULL, '2026-01-01 14:15:12', 'completed', 'https://docs.google.com/spreadsheets/d/1uy46_d-tklY2lWjYiFIvkboBLidusaod/edit?usp=drivesdk&ouid=117341248038967165310&rtpof=true&sd=true', 12, '2026-01-01 14:06:45', '2026-01-01 14:48:17'),
(160, 'Foto', 'Galih', '089615152647', 6, '2026-01-01', '12:00:00', '14:00:00', '2026-01-01 15:09:45', '2026-01-01 15:09:57', '2026-01-01 15:10:03', NULL, NULL, NULL, '2026-01-01 15:10:29', 'BIG', 'cash', 0.00, 'no-proof.img', 'Kasih Video (IZS)', 'done', NULL, '2026-01-01 15:43:52', 'idle', NULL, 12, '2026-01-01 15:05:37', '2026-01-01 15:43:52'),
(161, 'Sport', 'Aji', '08976374', 6, '2026-01-01', '16:00:00', '18:00:00', '2026-01-01 15:13:30', '2026-01-01 15:13:51', '2026-01-01 15:13:54', NULL, NULL, NULL, '2026-01-01 15:14:00', 'BIG', 'tf', 0.00, 'no-proof.img', 'Video (izs)', 'done', NULL, NULL, 'idle', NULL, 12, '2026-01-01 15:12:54', '2026-01-01 15:14:00'),
(183, 'Trofeo', 'BSS', '8888888', 8, '2026-01-03', '12:00:00', '14:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://maps.app.goo.gl/o1DLwh9A2uvAZipt6?g_st=aw', 'unpaid', 0.00, 'no-proof.img', '(OKTOMOT)', 'scheduled', NULL, NULL, 'idle', NULL, 12, '2026-01-02 12:10:05', '2026-01-02 12:10:15');

-- --------------------------------------------------------

--
-- Table structure for table `job_assignments`
--

CREATE TABLE `job_assignments` (
  `id` bigint UNSIGNED NOT NULL,
  `job_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `editor_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_assignments`
--

INSERT INTO `job_assignments` (`id`, `job_id`, `user_id`, `editor_id`, `created_at`, `updated_at`) VALUES
(20, 158, 5, 13, '2026-01-01 14:06:45', '2026-01-01 14:48:17'),
(23, 160, 2, NULL, NULL, NULL),
(24, 161, 2, NULL, '2026-01-01 15:12:54', '2026-01-01 15:12:54'),
(48, 183, 5, NULL, '2026-01-02 12:10:05', '2026-01-02 12:10:05');

-- --------------------------------------------------------

--
-- Table structure for table `job_types`
--

CREATE TABLE `job_types` (
  `id` bigint UNSIGNED NOT NULL,
  `job_type_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `badge_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#6c757d',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_types`
--

INSERT INTO `job_types` (`id`, `job_type_name`, `badge_color`, `created_at`, `updated_at`) VALUES
(6, 'Foto', '#cc0000', '2025-12-19 22:51:31', '2025-12-19 22:51:31'),
(8, 'Live Streaming', '#aedd03', '2025-12-19 22:52:08', '2025-12-19 22:52:08'),
(10, 'Video Full Match', '#d16a0a', '2025-12-20 17:53:23', '2025-12-20 17:53:23'),
(11, 'Video Cinematic', '#82e2e3', '2025-12-20 17:55:22', '2025-12-20 17:55:22'),
(12, 'Video Drone', '#d16b97', '2025-12-20 17:55:40', '2025-12-20 17:55:40');

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
(1, '2025_12_17_232433_create_roles_table', 1),
(2, '2025_12_17_232440_create_users_table', 1),
(3, '2025_12_17_232444_create_job_types_table', 1),
(4, '2025_12_17_232448_create_jobs_table', 1),
(5, '2025_12_17_232454_create_job_assignments_table', 1),
(6, '2025_12_17_234550_create_sessions_table', 1),
(7, '2025_12_17_235920_add_income_and_profile_picture_to_users_table', 1),
(8, '2025_12_18_001520_add_result_link_to_jobs_table', 1),
(9, '2025_12_18_003327_create_transactions_table', 1),
(10, '2025_12_18_003702_add_editor_columns_to_jobs_table', 1),
(11, '2025_12_18_005543_create_cache_table', 2),
(12, '2025_12_18_083010_add_editor_id_columns_on_table_job_assignments', 3),
(13, '2025_12_20_053148_add_badge_color_to_job_types_table', 4),
(14, '2025_12_26_155627_add_wa_sent_at_to_jobs_table', 5),
(15, '2025_12_30_041629_add_editor_tracking_to_jobs_table', 6),
(16, '2025_12_31_173757_add_cancel_notes_column', 7),
(17, '2026_01_03_141851_create_password_reset_tokens_table', 8);

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
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'boss', '2025-12-17 17:47:03', '2025-12-17 17:47:03'),
(2, 'admin', '2025-12-17 17:47:03', '2025-12-17 17:47:03'),
(3, 'crew', '2025-12-17 17:47:03', '2025-12-17 17:47:03'),
(4, 'editor', '2025-12-17 17:47:03', '2025-12-17 17:47:03');

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
('1fiZJm27Re9uuBH3hyEaZwbGDhT5KSM8CnmUIKPS', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiT2ZBeFRFNGxsMGVKeHVBNWR6NTg1aFZndml4bDVPRFJiTGFhT3E0VSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozODoiaHR0cHM6Ly9penphdGktc2NoZWR1bGluZy50ZXN0L215LWpvYnMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozODoiaHR0cHM6Ly9penphdGktc2NoZWR1bGluZy50ZXN0L215LWpvYnMiO3M6NToicm91dGUiO3M6OToiY3Jldy5qb2JzIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTtzOjIzOiJwb3B1cF9zZWVuXzVfMjAyNi0wMS0wMyI7YjoxO30=', 1767422154),
('Bkp9Kp256li78dXP8UAOaA5DS9nhHU7YXLmGnABX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRDh3RWowaUVJNDB1dGROeEs1MVE5THVOYnl0WktEbjV1WlhHRkNkWiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozODoiaHR0cHM6Ly9penphdGktc2NoZWR1bGluZy50ZXN0L215LWpvYnMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozMDoiaHR0cHM6Ly9penphdGktc2NoZWR1bGluZy50ZXN0IjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1767422256),
('hRzKekUNiqBkVjUoo6rVzmdkiWEfUGnlMJNdJSfd', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiR2lKUkFGbTd1dXFkV05ucm1TSDFIYmtudUJQTkZwdk1sdGhnY3VCSyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozODoiaHR0cHM6Ly9penphdGktc2NoZWR1bGluZy50ZXN0L215LWpvYnMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozODoiaHR0cHM6Ly9penphdGktc2NoZWR1bGluZy50ZXN0L215LWpvYnMiO3M6NToicm91dGUiO3M6OToiY3Jldy5qb2JzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTtzOjIzOiJwb3B1cF9zZWVuXzVfMjAyNi0wMS0wMyI7YjoxO30=', 1767422795),
('nPkWhgj2DSlrQbBc5HZhnHcriE6x1aO6XxWTlxvb', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ0RRY0lHMmVsTXY1TER3eXdSb3JBNW1IT1NiT0UwMmtIU3czM21UQyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHBzOi8vaXp6YXRpLXNjaGVkdWxpbmcudGVzdCI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1767427029),
('UcAfmRKowtmuyxsor6xujydGCueY0QobEyeafm9w', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSjZTdXVuTWgzeWxUY295VW5hTGF0eFNpck5YNXlwcGZDQ0VUUUxFRSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDY6Imh0dHBzOi8vaXp6YXRpLXNjaGVkdWxpbmcudGVzdC9mb3Jnb3QtcGFzc3dvcmQiO3M6NToicm91dGUiO3M6MTY6InBhc3N3b3JkLnJlcXVlc3QiO319', 1767426157),
('x0i1su8PqhgbaZoZqFh6LDC87WDbsbDCAGAQFBkX', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVkt2d3UwRU5kS2dBMmJpQUFuWTNuNVlGTWV3WDdzaU5wT1lMT252NSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHBzOi8vaXp6YXRpLXNjaGVkdWxpbmcudGVzdC9teS1qb2JzIjtzOjU6InJvdXRlIjtzOjk6ImNyZXcuam9icyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7czoyMzoicG9wdXBfc2Vlbl81XzIwMjYtMDEtMDMiO2I6MTt9', 1767422529),
('ztEohmtnG4iItFNqofLM3xYOAqjAeq5LzpUcyENg', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiczFBenFGaUlHT1RoTjEzSU95WW9oSXowOVhuNTBHbkltQ0ZvR2dudiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHBzOi8vaXp6YXRpLXNjaGVkdWxpbmcudGVzdCI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1767426322);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `job_id` bigint UNSIGNED DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `type` enum('income','payout','expense','salary_pending') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `transaction_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `payday` enum('weekly','monthly') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly',
  `income` decimal(15,2) NOT NULL DEFAULT '0.00',
  `profile_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone_number`, `password`, `role_id`, `payday`, `income`, `profile_picture`, `created_at`, `updated_at`) VALUES
(1, 'Guntur (CEO)', 'boss@web.com', '0811111111', '$2y$12$nzl6V1fCJ3wq4KLRTl0F0.q3atHUhsIs6oRS6zl5Z30sli/qB3c9K', 1, 'monthly', 0.00, NULL, '2025-12-17 17:47:03', '2025-12-31 20:44:18'),
(2, 'Galih Sakti Azhari', 'galihsaktiazhari@gmail.com', '089517028180', '$2y$12$522KBR8LLV8Os7PfxGEUI.ot73sNk7SOBNgLUU9lctEP8uyh3bR/G', 3, 'weekly', 0.00, NULL, '2025-12-17 17:47:03', '2025-12-26 10:41:15'),
(5, 'Syami Aji', 'syamiaji1907@gmail.com', '081285796887', '$2y$12$AWew5Asnf8q6ipPa6mIjKeNPccKufTIf/OGNa7G/b3dgT9MDAwfY.', 3, 'weekly', 0.00, NULL, '2025-12-17 18:41:57', '2026-01-01 18:55:30'),
(12, 'admin', 'admin@web.com', '8888888888', '$2y$12$JinIZORu8dSLuKCKqXmUteb5qyts.rzkE7/kWJUM.EHAH2MOkmyiW', 2, 'monthly', 0.00, NULL, '2025-12-29 03:42:06', '2025-12-29 03:42:06'),
(13, 'editor', 'editor@web.com', '888888888', '$2y$12$AvXhPJRkn68tTFtovcgLyuhq7Xz4qESmw7ggp6jqXisVmM2bxkCvq', 4, 'monthly', 0.00, NULL, '2025-12-29 10:03:27', '2025-12-29 10:03:27'),
(14, 'editor2', 'editor2@web.com', '0888888888', '$2y$12$BR39KsIWiFYSJx720PWG5OHRrcN1bKWGa1681I3lkdI4weY4bw/xa', 4, 'monthly', 0.00, NULL, '2025-12-30 09:03:03', '2025-12-30 09:03:03'),
(15, 'Keanu Ryansyah', 'keanukeanu1207@gmail.com', '081944221207', '$2y$12$kW7NlceR31tnkByxfoBLQu1a7N7sCuIJZilyq02ke8Y0DmpmqrKxy', 2, 'monthly', 0.00, NULL, '2026-01-03 07:32:28', '2026-01-03 07:45:08');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_job_type_foreign` (`job_type`),
  ADD KEY `jobs_created_by_foreign` (`created_by`);

--
-- Indexes for table `job_assignments`
--
ALTER TABLE `job_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `job_assignments_job_id_user_id_unique` (`job_id`,`user_id`),
  ADD KEY `job_assignments_user_id_foreign` (`user_id`),
  ADD KEY `job_assignments_editor_id_foreign` (`editor_id`);

--
-- Indexes for table `job_types`
--
ALTER TABLE `job_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `job_types_job_type_name_unique` (`job_type_name`);

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
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`),
  ADD KEY `transactions_job_id_foreign` (`job_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_number_unique` (`phone_number`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT for table `job_assignments`
--
ALTER TABLE `job_assignments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `job_types`
--
ALTER TABLE `job_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `jobs_job_type_foreign` FOREIGN KEY (`job_type`) REFERENCES `job_types` (`id`);

--
-- Constraints for table `job_assignments`
--
ALTER TABLE `job_assignments`
  ADD CONSTRAINT `job_assignments_editor_id_foreign` FOREIGN KEY (`editor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `job_assignments_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_assignments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
