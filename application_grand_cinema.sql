-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2026 at 06:40 PM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `application_grand_cinema`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-8dbf625f20750bc67dea7d42c5d92a1a', 'i:1;', 1795024553),
('laravel-cache-8dbf625f20750bc67dea7d42c5d92a1a:timer', 'i:1795024553;', 1795024553);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `genre_id` int(11) NOT NULL,
  `genre_label` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`genre_id`, `genre_label`) VALUES
(1, 'Action'),
(2, 'Comédie'),
(3, 'Drame'),
(4, 'Science-fiction'),
(5, 'Thriller'),
(6, 'Animation'),
(7, 'Aventure'),
(8, 'Romance'),
(9, 'Fantastique'),
(10, 'Documentaire');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_11_17_173346_create_cache_table', 0),
(5, '2026_11_17_173346_create_cache_locks_table', 0),
(6, '2026_11_17_173346_create_failed_jobs_table', 0),
(7, '2026_11_17_173346_create_genres_table', 0),
(8, '2026_11_17_173346_create_job_batches_table', 0),
(9, '2026_11_17_173346_create_jobs_table', 0),
(10, '2026_11_17_173346_create_movies_table', 0),
(11, '2026_11_17_173346_create_movies_genres_table', 0),
(12, '2026_11_17_173346_create_password_reset_tokens_table', 0),
(13, '2026_11_17_173346_create_reservations_table', 0),
(14, '2026_11_17_173346_create_roles_table', 0),
(15, '2026_11_17_173346_create_rooms_table', 0),
(16, '2026_11_17_173346_create_screenings_table', 0),
(17, '2026_11_17_173346_create_sessions_table', 0),
(18, '2026_11_17_173346_create_users_table', 0),
(19, '2026_11_17_173349_add_foreign_keys_to_movies_genres_table', 0),
(20, '2026_11_17_173349_add_foreign_keys_to_reservations_table', 0),
(21, '2026_11_17_173349_add_foreign_keys_to_screenings_table', 0),
(22, '2026_11_17_173349_add_foreign_keys_to_users_table', 0);

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `movie_id` int(11) NOT NULL,
  `movie_name` varchar(255) NOT NULL,
  `director` varchar(255) NOT NULL,
  `duration_minutes` int(11) NOT NULL,
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movie_id`, `movie_name`, `director`, `duration_minutes`, `description`) VALUES
(1, 'Le Dernier Voyage', 'Élodie Marchand', 118, 'Un astronaute entreprend une mission qui pourrait changer le destin de l’humanité.'),
(2, 'Un Été à Paris', 'Thomas Girard', 96, 'Deux inconnus se rencontrent au détour des rues de Paris.'),
(3, 'Les Ombres du passé', 'Nadia Fontaine', 132, 'Une enquêtrice rouvre une affaire classée depuis vingt ans.'),
(4, 'Mission Minuit', 'Antoine Lefort', 109, NULL),
(5, 'Le Royaume des lucioles', 'Claire Morel', 88, 'Une jeune héroïne découvre un monde caché dans la forêt.'),
(6, 'À Contretemps', 'Julien Perrin', 104, 'Une famille tente de se reconstruire après un événement inattendu.'),
(7, 'Planète Océan', 'Sophie Garnier', 91, NULL),
(8, 'Le Dîner impossible', 'Maxime Roux', 101, 'Un repas de famille tourne à la catastrophe.'),
(9, 'La Cité de verre', 'Maya Benali', 127, 'Dans une ville futuriste, une journaliste découvre un secret inquiétant.'),
(10, 'Les Petits Explorateurs', 'Pauline Chevalier', 79, 'Trois enfants partent à la recherche d’un trésor.'),
(11, 'Dernière danse', 'Romain Gauthier', 115, 'Une danseuse prépare son ultime représentation.'),
(12, 'Sans étiquette', 'Lina Caron', 93, NULL),
(13, 'Le Mystère du phare', 'Arthur Noël', 100, 'Un gardien de phare est confronté à d’étranges phénomènes.'),
(14, 'Au fil des saisons', 'Manon Vidal', 84, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `movies_genres`
--

CREATE TABLE `movies_genres` (
  `movie_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies_genres`
--

INSERT INTO `movies_genres` (`movie_id`, `genre_id`) VALUES
(1, 3),
(1, 4),
(1, 7),
(2, 2),
(2, 8),
(3, 3),
(3, 5),
(4, 1),
(4, 5),
(5, 6),
(5, 7),
(5, 9),
(6, 3),
(7, 10),
(8, 2),
(9, 4),
(9, 5),
(10, 6),
(10, 7),
(11, 3),
(11, 8),
(13, 5),
(13, 9);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `seats_quantity` int(11) NOT NULL,
  `id` bigint(11) UNSIGNED NOT NULL,
  `screening_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `seats_quantity`, `id`, `screening_id`) VALUES
(1, 2, 1, 1),
(2, 1, 2, 1),
(3, 3, 3, 2),
(4, 2, 4, 3),
(5, 4, 5, 4),
(6, 1, 6, 5),
(7, 2, 7, 6),
(8, 1, 8, 7),
(9, 3, 9, 9),
(10, 2, 10, 9),
(11, 4, 11, 10),
(12, 2, 12, 11),
(13, 1, 1, 12),
(14, 3, 2, 13),
(15, 2, 3, 15),
(16, 1, 4, 17),
(17, 2, 5, 18);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_code` char(1) NOT NULL,
  `role_label` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_code`, `role_label`) VALUES
('A', 'Administateur'),
('C', 'Agent de contrôle'),
('U', 'Utilisateur');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_name`, `capacity`) VALUES
(1, 'Salle Premium A', 300),
(2, 'Salle Premium B', 300),
(4, 'Salle Standard A', 180),
(5, 'Salle Standard B', 180),
(6, 'Salle Standard C', 180),
(7, 'Salle Standard D', 180),
(8, 'Salle VIP A', 120),
(9, 'Salle VIP B', 120),
(10, 'Salle Modulable A', 70),
(11, 'Salle Modulable B', 70);

-- --------------------------------------------------------

--
-- Table structure for table `screenings`
--

CREATE TABLE `screenings` (
  `screening_id` int(11) NOT NULL,
  `screening_date` datetime NOT NULL,
  `room_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `screenings`
--

INSERT INTO `screenings` (`screening_id`, `screening_date`, `room_id`, `movie_id`) VALUES
(1, '2026-11-05 14:00:00', 1, 1),
(2, '2026-11-08 18:30:00', 4, 2),
(3, '2026-11-12 20:00:00', 2, 3),
(4, '2026-11-16 16:00:00', 5, 4),
(5, '2026-11-21 14:30:00', 8, 5),
(6, '2026-11-28 20:30:00', 6, 6),
(7, '2026-12-03 17:00:00', 9, 7),
(8, '2026-12-06 19:00:00', 7, 8),
(9, '2026-12-10 20:15:00', 1, 9),
(10, '2026-12-14 11:00:00', 10, 10),
(11, '2026-12-18 18:00:00', 4, 11),
(12, '2026-12-22 21:00:00', 2, 1),
(13, '2026-12-27 15:30:00', 5, 4),
(14, '2027-01-04 14:00:00', 8, 2),
(15, '2027-01-09 20:00:00', 7, 3),
(16, '2027-01-15 17:30:00', 10, 5),
(17, '2026-11-29 16:00:00', 9, 9),
(18, '2027-01-09 20:00:00', 9, 7),
(20, '2026-11-14 19:00:00', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('rxOkaa8OOxKpbvD2RZqbg5g5yetotiNPVnA6FraA', 18, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', 'eyJfdG9rZW4iOiJXUjdTZ3BUbG9tQ1RhZGZOVzRLWXBodG5MNmY1UzJpRDZvMFZBMTJOIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbl9kYXNoYm9hcmQiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjE4LCJwYXNzd29yZF9oYXNoX3dlYiI6IjUzMzZkOWFjNzQ3NzM0NGQ4YzQ2MDQzOGMyYjBjMTNkYjZmODc2ZjI5NzVkODQ1MTJiYzM1MWQ5YjU1ZTIxZWEifQ==', 1795010833),
('U87W9MFNGYbNMPBe3PYpFpYZo1dKkxzZsORBR5hc', 18, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', 'eyJfdG9rZW4iOiJuWEN6WXFNVGdHbElEMm5ZUWZ1NThWWFRINGlVYUtMZHdwSUdKZk1xIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9tb3ZpZXNfbGlzdCIsInJvdXRlIjpudWxsfSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MTgsInBhc3N3b3JkX2hhc2hfd2ViIjoiNTMzNmQ5YWM3NDc3MzQ0ZDhjNDYwNDM4YzJiMGMxM2RiNmY4NzZmMjk3NWQ4NDUxMmJjMzUxZDliNTVlMjFlYSJ9', 1795025058),
('Y5RdZQuuWjJss1FLZNZilW3kxyWucfZqdUuurDoI', 18, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', 'eyJfdG9rZW4iOiJHZnZ0ZWQwYm1lVXd0Ukh2NWdNY2lZdnFuQ2hEWVVWYmxqZm1JYWpRIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3Jlc2VydmF0aW9uc19saXN0Iiwicm91dGUiOm51bGx9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MTgsInBhc3N3b3JkX2hhc2hfd2ViIjoiNTMzNmQ5YWM3NDc3MzQ0ZDhjNDYwNDM4YzJiMGMxM2RiNmY4NzZmMjk3NWQ4NDUxMmJjMzUxZDliNTVlMjFlYSJ9', 1794940358);

-- --------------------------------------------------------

--
-- Table structure for table `users`
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
  `role_code` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `comment` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_code`, `comment`) VALUES
(1, 'Alice Martin', 'alice.martin@example.test', '2026-11-12 16:21:45', '$2y$12$sHtRqBustV7UbBxkvBDdNOmeclTg4XMIX7UHGUF4b8jjo4shg.ChW', NULL, '2026-11-12 16:21:45', '2026-11-12 16:21:45', 'U', '!_RZKW2jm_m%5Dap'),
(2, 'Lucas Bernard', 'lucas.bernard@example.test', '2026-11-12 16:21:45', '$2y$12$Ttml4OgwfS4fND7EgFSYCu/3DmFZLx8t71RsWeSMQGVxVqcOTwizO', NULL, '2026-11-12 16:21:45', '2026-11-12 16:21:45', 'U', '!jXcdA73LHQT8Vvo'),
(3, 'Emma Petit', 'emma.petit@example.test', '2026-11-12 16:21:45', '$2y$12$ROS0LGKs4.i4G5MwaNb70e.AuOe2LsXXM6EETlnUEJKTGIQFdxCbq', NULL, '2026-11-12 16:21:45', '2026-11-12 16:21:45', 'U', '!!#4gLNR3-ynWY-3'),
(4, 'Hugo Robert', 'hugo.robert@example.test', '2026-11-12 16:21:45', '$2y$12$Y72ZUaRmMh/RgOIgOUzXOuoC/wyZyz0o.ODdC97PbACtzhKy1UdEG', NULL, '2026-11-12 16:21:45', '2026-11-12 16:21:45', 'U', '!-tSb6BJYjWnphLX'),
(5, 'Chloé Richard', 'chloe.richard@example.test', '2026-11-12 16:21:45', '$2y$12$xbl6/awT3FppaXtuJU.9zeYt.rzILMNB51kVoABXlRV/hCWHxYluS', NULL, '2026-11-12 16:21:45', '2026-11-12 16:21:45', 'U', '!Q%ayrN5fyyiJmih'),
(6, 'Nathan Durand', 'nathan.durand@example.test', '2026-11-12 16:21:45', '$2y$12$r7hitT44a7Sah61Hwz98EODB98V9Hd8Ca9bW0ARjrplyxF2pb8.zW', NULL, '2026-11-12 16:21:45', '2026-11-12 16:21:45', 'U', '!r7!PiBuh@a2oft$'),
(7, 'Inès Dubois', 'ines.dubois@example.test', '2026-11-12 16:21:45', '$2y$12$aXPqhpJ.bVDIxblHPtKbeOMBQTQnEAHKaYw523S.cwee2jl19jfai', NULL, '2026-11-12 16:21:45', '2026-11-12 16:21:45', 'U', '!xVSxM@yHtnBLBcY'),
(8, 'Gabriel Moreau', 'gabriel.moreau@example.test', '2026-11-12 16:21:45', '$2y$12$vrwe/Vs35FOFUu/W4C/mOOWFU3c8Opz2x4fK9AY1/8bL6myzhr5yi', NULL, '2026-11-12 16:21:45', '2026-11-12 16:21:45', 'U', '!!6ZVY-2dYbVV#Rx'),
(9, 'Sarah Laurent', 'sarah.laurent@example.test', '2026-11-12 16:21:45', '$2y$12$Q15bWKN.dNR7f64Rm57wcuwpEUC6AqBh.wa9kbMccNcai.A5qqFya', NULL, '2026-11-12 16:21:45', '2026-11-12 16:21:45', 'U', '!odRBGkkSq5@g4Lr'),
(10, 'Thomas Simon', 'thomas.simon@example.test', '2026-11-12 16:21:45', '$2y$12$AU0LsIIFrNyTfgnT6/iX0.QW0uh.ypKLGvWRFS1aQOwQVgOjowF8i', NULL, '2026-11-12 16:21:45', '2026-11-12 16:21:45', 'U', '!zhs7Mpngz!MNgwW'),
(11, 'Julie Michel', 'julie.michel@example.test', '2026-11-12 16:21:45', '$2y$12$iY8yacWU9Y5N.jwq/Fx1H.c9G0UW2h/wJ2A5J6yeY82Zc2ESZXvqq', NULL, '2026-11-12 16:21:45', '2026-11-12 16:21:45', 'U', '!8J$nZMfVnAhToTx'),
(12, 'Mehdi Lefèvre', 'mehdi.lefevre@example.test', '2026-11-12 16:21:45', '$2y$12$dm53QJqN6Gxxu.d.uUo0heJfNlFIZ64paZe4fEz.hZX7EKgnGqlGm', NULL, '2026-11-12 16:21:45', '2026-11-12 16:21:45', 'U', '!xK-6quXR9XJ5dtA'),
(13, 'Camille Garcia', 'camille.garcia@example.test', '2026-11-12 16:21:45', '$2y$12$ER4vrxOMDc5HbxEzPx56pO/HagDsbrgN5kJjMrSyJtNyUCthK.EkO', NULL, '2026-11-12 16:21:45', '2026-11-12 16:21:45', 'U', '!vm84%b3xgDe7w9o'),
(17, 'user_test', 'user_test@example.test', NULL, '$2y$12$k13XYyBcxBloTY1YNv51uuyDLrPSVf.T7t.FdRo6.qsSHlN11fsi2', NULL, '2026-11-17 18:13:12', '2026-11-17 18:13:12', 'U', '!mdp_usertest'),
(18, 'admin_test', 'admin_test@example.test', NULL, '$2y$12$XY8LY2DV1hi/n9TGeQ32Qeiqm93nkbk4eyoHRnSecR0Jf3IbYnh8m', NULL, '2026-11-17 18:13:12', '2026-11-17 18:13:12', 'A', '!mdp_admintest'),
(19, 'control_test', 'control_test@example.test', NULL, '$2y$12$eku2gFxmMLnsxY4iAKD5r.c/Nz.L0xgzBRP1HUazY5.NT5Oi1xVBW', NULL, '2026-11-17 18:13:12', '2026-11-17 18:13:12', 'C', '!mdp_controltest');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genre_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movie_id`);

--
-- Indexes for table `movies_genres`
--
ALTER TABLE `movies_genres`
  ADD PRIMARY KEY (`movie_id`,`genre_id`),
  ADD KEY `fk_mg_genres_movies` (`genre_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `fk_reservations_screenings` (`screening_id`),
  ADD KEY `fk_reservations_users` (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_code`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `screenings`
--
ALTER TABLE `screenings`
  ADD PRIMARY KEY (`screening_id`),
  ADD KEY `fk_screenings_movies` (`movie_id`),
  ADD KEY `fk_screenings_rooms` (`room_id`);

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
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `fk_users_roles` (`role_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `genre_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `screenings`
--
ALTER TABLE `screenings`
  MODIFY `screening_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `movies_genres`
--
ALTER TABLE `movies_genres`
  ADD CONSTRAINT `fk_mg_genres_cascade` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`genre_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_mg_movies_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_reservations_screenings` FOREIGN KEY (`screening_id`) REFERENCES `screenings` (`screening_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reservations_users` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `screenings`
--
ALTER TABLE `screenings`
  ADD CONSTRAINT `fk_screenings_movies` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_screenings_rooms` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_roles` FOREIGN KEY (`role_code`) REFERENCES `roles` (`role_code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
