-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Agu 2025 pada 17.08
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
-- Database: `kasir_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Rokok & Tembakau', '2025-07-29 03:59:26', '2025-08-03 07:57:50'),
(2, 'Minuman', '2025-07-29 03:59:26', '2025-07-29 03:59:26'),
(3, 'Makanan Ringan', '2025-07-29 03:59:26', '2025-07-29 03:59:26'),
(4, 'Kebutuhan Sehari-hari', '2025-07-29 03:59:26', '2025-07-29 03:59:26'),
(5, 'Obat-obatan', '2025-07-29 03:59:26', '2025-07-29 03:59:26'),
(6, 'Alat Tulis', '2025-07-29 03:59:26', '2025-07-29 03:59:26'),
(8, 'Minuman', '2025-07-29 04:02:24', '2025-07-29 04:02:24'),
(9, 'Makanan Ringan', '2025-07-29 04:02:24', '2025-07-29 04:02:24'),
(10, 'Kebutuhan Sehari-hari', '2025-07-29 04:02:24', '2025-07-29 04:02:24'),
(11, 'Obat-obatan', '2025-07-29 04:02:24', '2025-07-29 04:02:24'),
(12, 'Alat Tulis', '2025-07-29 04:02:24', '2025-07-29 04:02:24'),
(13, 'Makanan Instan', '2025-07-31 12:47:39', '2025-07-31 12:47:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_07_20_074204_create_categories_table', 2),
(6, '2025_07_20_074217_create_transactions_table', 3),
(8, '2025_07_20_112220_create_products_table', 4),
(9, '2025_07_29_105758_add_category_and_details_to_products_table', 5),
(10, '2025_08_01_091854_create_settings_table', 6),
(11, '2025_08_01_092001_create_payment_methods_table', 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('kawangopay123@gmail.com', '$2y$12$8MilMo1EEzqMYq7U7X9gpOgO6IXL58SNFAf0Nb8q0P8T7yjmf.4OS', '2025-08-02 07:41:50'),
('superadmin@gmail.com', '$2y$12$.MCQCSmFlrO/OhjX.ZzJUOrkHuwOnwAtHzsTHgT.DhIoqtM4HUyqu', '2025-08-03 02:54:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `account_number`, `account_name`, `qr_code`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'QRIS', '082262899474', 'FAUZAN GALANG RAIFAN', 'qrcodes/H7pNqIcuvQCBu2s6qt9SpeT3BTfS02AuW4HP2x5M.png', 1, '2025-08-01 14:26:33', '2025-08-01 14:26:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `min_stock` int(11) NOT NULL DEFAULT 5,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `barcode`, `description`, `image`, `price`, `stock`, `min_stock`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Roko Malboro', 'PRD250730120207277', 'Marlboro adalah merek rokok terkenal yang diproduksi oleh Philip Morris International.', 'Cm5nUGZFR9zzO4frn3QjGTkjJcfuWQO9uZ0Ffnfz.jpg', 24000.00, 40, 5, 1, '2025-07-20 20:32:31', '2025-07-31 09:13:45'),
(2, 4, 'Tissue Paseo', 'PRD250730115836700', 'Paseo adalah merek tisu yang menawarkan berbagai produk, termasuk tisu wajah, tisu dapur, dan tisu basah bayi', 'BZlGkoCh8bxEK7RIkWvwfyO21YZ7F9rXzADs5OJk.jpg', 20000.00, 70, 5, 1, '2025-07-20 22:40:05', '2025-07-30 17:24:32'),
(3, 2, 'Le Mineral', 'PRD250730114658098', 'Le Minerale adalah air mineral dalam kemasan yang diproduksi oleh PT Tirta Fresindo Jaya, anak perusahaan Mayora Indah', 'g71iNstXpMM94BKjhhF64eK53DRQ9ngZ1xeGAVzn.jpg', 5000.00, 29, 5, 1, '2025-07-20 23:47:41', '2025-08-01 14:50:55'),
(4, 5, 'Minyak Kayu Putih', '890123426430', 'Minyak Kayu Putih Cap Lang adalah minyak putih alami yang digunakan untuk membantu meringankan sakit perut, perut kembung, rasa mual, dan gatal-gatal akibat gigitan serangga/nyamuk.', 'kQHeQ9cdzbeofWJgDGralsvh1IeVfyvcJuB5MHY2.jpg', 10000.00, 49, 5, 1, '2025-07-30 04:14:21', '2025-07-30 16:57:20'),
(5, 13, 'Indomie Goreng', 'PRD250731194739159', 'ndomie Goreng adalah mie instan goreng populer dari Indofood, yang dikenal dengan rasa gurih dan lezat.', 'IyYD4sruQSgePJX0dxmltfg7kvT0ilmcw8zy0ec1.jpg', 3500.00, 100, 10, 1, '2025-07-31 12:47:39', '2025-07-31 14:19:14'),
(6, 2, 'Aqua 600ml', 'PRD250731194739625', 'Aqua 600ml adalah air mineral dalam kemasan botol yang diproduksi oleh Danone Aqua.', '4UYVlbyBAQs01SXqWmSTgJwmk50pzTvlz3G9l5G1.jpg', 3000.00, 49, 5, 1, '2025-07-31 12:47:39', '2025-08-01 03:42:52'),
(7, 6, 'Buku Tulis Sinar Dunia', 'PRD250731194739463', 'Buku Tulis Sinar Dunia ( SIDU ) adalah buku tulis yang paling digemari oleh banyak orang di Indonesia,terutama menjelang Tahun ajaran baru sekolah.', 'LM9MHzCX8ohh0kSBvoQ9VX6HO16AXQ6lDzSey6Wx.jpg', 5000.00, 25, 5, 1, '2025-07-31 12:47:39', '2025-07-31 14:21:49'),
(9, 2, 'Teh Botol Sosro', 'PRD250731195008270', 'Teh Botol Sosro pertama kali diperkenalkan pada tahun 1969 dalam bentuk botol, menjadikannya pelopor minuman teh dalam kemasan botol di Indonesia dan dunia.', 'lsUhLW2fauHTE3dIgalC6bAZpul5nFO5fEfP3AKR.jpg', 5000.00, 58, 8, 1, '2025-07-31 12:50:08', '2025-08-01 14:34:05'),
(10, 6, 'Pensil 2B Faber Castell', 'PRD250731195008233', 'Pencil Castell adalah pensil hijau klasik yang selalu bisa diandalkan. Pensil pilihan seniman dunia yang memiliki pilihan 16 ketebalan yang berbeda, menjadikan karya seni yang indah.', '68Ndv2FClqTMrRojkUciD3t9AwoNzD0Dj5LP2amo.jpg', 4500.00, 100, 15, 1, '2025-07-31 12:50:08', '2025-07-31 14:10:55'),
(11, 3, 'Snack Taro', 'PRD250731195008810', 'Taro adalah merek makanan ringan dari FKS Food. Taro merupakan salah satu merek makanan ringan yang paling terkenal di Indonesia.', 'mSzCOJKXwi82Deo7dqLxuokAntWQjqvWGgf9QuUO.jpg', 7000.00, 39, 5, 1, '2025-07-31 12:50:08', '2025-08-01 14:35:34'),
(12, 6, 'Pulpen Pilot', 'PRD250731195008189', 'Pulpen Pilot adalah merek pulpen yang dikenal karena kualitasnya yang baik dan beragam pilihan untuk kebutuhan menulis', 'H1hbWurvR65dwWJOUnlbQ6HgTPYv0bTnw8QkqonF.jpg', 5000.00, 39, 5, 1, '2025-07-31 12:50:08', '2025-07-31 17:28:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `store_name` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `tax` decimal(5,2) NOT NULL DEFAULT 10.00,
  `discount` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`id`, `store_name`, `logo`, `address`, `phone`, `tax`, `discount`, `created_at`, `updated_at`) VALUES
(1, 'Z-Mart', 'logos/KNpdBobWE4gJCNyPl4WoAVrgrNCUrfvirJsgyJQ3.png', NULL, NULL, 10.00, 0.00, '2025-08-01 14:11:10', '2025-08-02 03:03:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_code` varchar(255) DEFAULT NULL,
  `paid` decimal(10,2) NOT NULL,
  `change` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `transaction_code`, `paid`, `change`, `payment_method`, `total_price`, `created_at`, `updated_at`) VALUES
(40, 10, 'TRX-20250801213405-JXX0q', 7000.00, 2000.00, 'qris', 5000.00, '2025-08-01 14:34:05', '2025-08-01 14:34:05'),
(41, 10, 'TRX-20250801213533-0zKDR', 100000.00, 30000.00, 'qris', 70000.00, '2025-08-01 14:35:33', '2025-08-01 14:35:33'),
(42, 10, 'TRX-20250801214836-afjYf', 5000.00, 0.00, 'qris', 5000.00, '2025-08-01 14:48:36', '2025-08-01 14:48:36'),
(43, 10, 'TRX-20250801215055-dJwsu', 15000.00, 0.00, 'qris', 15000.00, '2025-08-01 14:50:55', '2025-08-01 14:50:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `transaction_details`
--

INSERT INTO `transaction_details` (`id`, `transaction_id`, `product_id`, `quantity`, `price`, `subtotal`, `created_at`, `updated_at`) VALUES
(40, 40, 9, 1, 5000.00, 5000.00, '2025-08-01 14:34:05', '2025-08-01 14:34:05'),
(41, 41, 11, 10, 7000.00, 70000.00, '2025-08-01 14:35:33', '2025-08-01 14:35:33'),
(42, 42, 3, 1, 5000.00, 5000.00, '2025-08-01 14:48:36', '2025-08-01 14:48:36'),
(43, 43, 3, 3, 5000.00, 15000.00, '2025-08-01 14:50:55', '2025-08-01 14:50:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('super_admin','admin','kasir') NOT NULL DEFAULT 'kasir',
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `status`) VALUES
(10, 'Super Admin', 'superadmin@gmail.com', NULL, '$2y$12$Gdn1dXyNkq2XTlARkafNyuOz9zGFcK7t2musc9AxI48lCAXMnHyUu', 'eFFb9zdOiChNHDykCuwkP6fxmPGIJtizo3QEPalybZmoULG0ujDmATGxFx2X', '2025-08-01 03:32:59', '2025-08-03 03:34:28', 'super_admin', 'active'),
(11, 'Fauzan Galang Raifan', 'fauzangalangraifan@gmail.com', NULL, '$2y$12$OWtTXt589z2WRy6569jqaum5H4AyQU3/Q8VsMclNcCASE1TPfu1mK', NULL, '2025-08-01 03:37:43', '2025-08-01 03:37:43', 'admin', 'active'),
(13, 'Opay', 'kawangopay123@gmail.com', NULL, '$2y$12$syj0aRYcoxkfi912N.405.xYmRVcXRCF4zebAwrLn29qBJQ7zc5vy', 'XbPKGEYiuxvwVoaQy7QtM5NpvVHvcOZVG7ZMvENpmPh0TXhfVOHVMkhgYWfm', '2025-08-02 03:41:31', '2025-08-02 03:41:31', 'admin', 'active');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_barcode_unique` (`barcode`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_details_transaction_id_foreign` (`transaction_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT untuk tabel `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transaction_details_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
