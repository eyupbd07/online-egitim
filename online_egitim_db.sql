-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 17 Şub 2026, 23:11:19
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `online_egitim_db`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `assignments`
--

CREATE TABLE `assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `due_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `assignments`
--

INSERT INTO `assignments` (`id`, `course_id`, `title`, `description`, `due_date`, `created_at`, `updated_at`) VALUES
(30, 6, 'pğ56o0pğk', 'kor', '2035-02-20 00:00:00', '2025-12-27 11:51:53', '2025-12-27 11:51:53'),
(31, 9, 'elşfmrqşm', 'kör', '9131-09-23 00:00:00', '2025-12-28 18:43:23', '2025-12-28 18:43:23'),
(32, 22, 'e', 'e', '2026-09-23 00:00:00', '2025-12-30 09:42:36', '2025-12-30 10:45:12'),
(33, 22, 'test', '1', '2026-01-08 00:00:00', '2026-01-07 05:30:41', '2026-01-07 05:30:41'),
(34, 22, 'test2', 'taldak', '2026-02-07 00:00:00', '2026-01-07 05:45:04', '2026-01-07 05:45:04');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `assignment_submissions`
--

CREATE TABLE `assignment_submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `assignment_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `student_note` text DEFAULT NULL,
  `submitted_at` datetime NOT NULL,
  `grade` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `assignment_submissions`
--

INSERT INTO `assignment_submissions` (`id`, `assignment_id`, `user_id`, `file_path`, `student_note`, `submitted_at`, `grade`, `feedback`, `created_at`, `updated_at`) VALUES
(1, 32, 2, 'assignments/0eTqh60kO3UbeF2SKzhaNY0XioxKqo5s0XcLewj8.docx', 'deneme', '2026-01-07 08:29:31', NULL, NULL, '2025-12-30 10:07:30', '2026-01-07 05:29:31'),
(2, 32, 7, 'assignments/hhXtXckYFzQznX0qAvusffjqBMcveCnehpTnnj52.rar', 'deneme', '2025-12-30 18:15:21', NULL, NULL, '2025-12-30 15:15:21', '2025-12-30 15:15:21'),
(3, 33, 2, 'assignments/GWWWAhHTabZLv9oH97Xk7S4bYAFxKV9tcUOlwn3e.pdf', 'deneme', '2026-01-07 08:31:12', NULL, NULL, '2026-01-07 05:31:12', '2026-01-07 05:31:12'),
(4, 34, 8, 'assignments/v60RFqpN3TkraR0gbm66hPgnGfaWcEtwYFXJ5xFr.pdf', 'test', '2026-01-07 08:45:27', NULL, NULL, '2026-01-07 05:45:27', '2026-01-07 05:45:27');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `certificates`
--

CREATE TABLE `certificates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `certificate_code` varchar(255) NOT NULL,
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `certificates`
--

INSERT INTO `certificates` (`id`, `user_id`, `course_id`, `certificate_code`, `issued_at`, `created_at`, `updated_at`) VALUES
(1, 2, 5, 'TEST-3526', '2025-12-26 09:01:36', '2025-12-26 09:01:36', '2025-12-26 09:01:36'),
(2, 2, 6, 'CRT-2HBRYXTBTO', '2025-12-26 09:39:02', '2025-12-26 09:39:02', '2025-12-26 09:39:02'),
(3, 2, 9, 'CRT-3B33F8B7', '2025-12-29 13:16:53', '2025-12-29 13:16:53', '2025-12-29 13:16:53'),
(4, 2, 22, 'CRT-2025-A4CD43', '2025-12-30 10:21:12', '2025-12-30 10:21:12', '2025-12-30 10:21:12'),
(6, 7, 22, 'CRT-2025-5BA402', '2025-12-30 15:15:32', '2025-12-30 15:15:32', '2025-12-30 15:15:32'),
(7, 8, 22, 'CRT-2026-1B6603', '2026-01-07 05:43:53', '2026-01-07 05:43:53', '2026-01-07 05:43:53');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Eğitmen ID',
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `youtube_url` varchar(255) DEFAULT NULL,
  `youtube_embed_url` varchar(255) DEFAULT NULL,
  `instructor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `status` enum('draft','published','private') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `courses`
--

INSERT INTO `courses` (`id`, `user_id`, `category_id`, `title`, `slug`, `description`, `youtube_url`, `youtube_embed_url`, `instructor_id`, `thumbnail_path`, `status`, `created_at`, `updated_at`) VALUES
(5, NULL, NULL, 'test', 'test', 'test', 'https://www.youtube.com/watch?v=rUZ1o9GUr9M', NULL, 4, NULL, 'draft', '2025-10-28 14:05:30', '2025-10-28 14:05:30'),
(6, NULL, NULL, 'Test 2', 'test-2', 'wss', 'https://www.youtube.com/watch?v=HyDlHh0gJJk', NULL, 4, NULL, 'draft', '2025-10-28 14:22:22', '2025-10-28 14:23:17'),
(9, NULL, NULL, 'C Ders 1 Merhaba Dünya', 'c-ders-1-merhaba-dunya-9', 'Udemy\'de bulunan ve daha profesyonelce hazırlanmış olan kurslarımıza indirimli fiyatlarla linkten ulaşabilirsiniz :)', NULL, NULL, 4, NULL, 'draft', '2025-12-27 12:19:45', '2025-12-28 17:53:11'),
(22, NULL, NULL, 'python', 'python-4119', 'fkl', 'https://www.youtube.com/watch?v=t176iXgG5PI&list=PL3kMAPso9YQ1Ls-5uTTIWWMkJoF_vyj5J', 'https://www.youtube.com/embed/t176iXgG5PI', 4, 'https://img.youtube.com/vi/t176iXgG5PI/maxresdefault.jpg', 'draft', '2025-12-29 15:01:53', '2025-12-29 15:01:53');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `course_user`
--

CREATE TABLE `course_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `course_user`
--

INSERT INTO `course_user` (`id`, `course_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 6, 2, NULL, NULL),
(2, 5, 2, '2025-12-24 18:49:26', '2025-12-24 18:49:26'),
(3, 9, 2, '2025-12-29 08:01:47', '2025-12-29 08:01:47'),
(4, 22, 2, '2025-12-29 15:18:20', '2025-12-29 15:18:20'),
(6, 22, 7, '2025-12-30 15:14:49', '2025-12-30 15:14:49'),
(7, 22, 8, '2026-01-07 05:40:35', '2026-01-07 05:40:35');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `failed_jobs`
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
-- Tablo için tablo yapısı `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `job_batches`
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
-- Tablo için tablo yapısı `lessons`
--

CREATE TABLE `lessons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `youtube_url` varchar(255) DEFAULT NULL,
  `youtube_embed_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_premium` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `lessons`
--

INSERT INTO `lessons` (`id`, `course_id`, `title`, `content`, `youtube_url`, `youtube_embed_url`, `description`, `video_url`, `order`, `is_premium`, `created_at`, `updated_at`) VALUES
(13, 6, 'Deneme', NULL, 'https://www.youtube.com/watch?v=511zuhZ5YRY&list=PLY20HpFruiK1cFSUqhP_XJSc_T0LWRh6t', 'https://www.youtube.com/embed/511zuhZ5YRY?autoplay=1&rel=0', NULL, 'https://www.youtube.com/watch?v=511zuhZ5YRY&list=PLY20HpFruiK1cFSUqhP_XJSc_T0LWRh6t', 2, 0, '2025-11-04 10:18:00', '2025-11-04 10:18:00'),
(15, 6, 'Yazılım nasıl öğrenilir? Ders 1 | Bilgisayar nasıl çalışır | Data tipleri | değişkenler | typing', NULL, NULL, NULL, NULL, 'https://www.youtube.com/watch?v=ofomjccpX0k&list=PLZYKO7600KN-SrrzbLe4dIt1MjR-bIOAY', 0, 0, '2025-12-25 16:36:34', '2025-12-25 16:36:34'),
(16, 9, 'delmedl', 'kfodl', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=HyDlHh0gJJk', 1, 0, '2025-12-28 18:51:40', '2025-12-28 18:51:40'),
(17, 22, 'deneme', 'test', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=HyDlHh0gJJk', 1, 0, '2025-12-30 09:43:01', '2025-12-30 15:21:53');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `lesson_user`
--

CREATE TABLE `lesson_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lesson_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `lesson_user`
--

INSERT INTO `lesson_user` (`id`, `lesson_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 13, 2, '2025-12-25 10:56:57', '2025-12-25 10:56:57');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 2, 4, 'd', 0, '2025-12-30 11:25:35', '2025-12-30 11:25:35'),
(2, 2, 4, 'rt', 0, '2025-12-30 11:26:54', '2025-12-30 11:26:54'),
(3, 2, 4, 't', 0, '2025-12-30 11:26:55', '2025-12-30 11:26:55'),
(4, 4, 2, 'deb', 0, '2025-12-30 11:33:12', '2025-12-30 11:33:12'),
(5, 2, 4, 'tesyt', 0, '2025-12-30 11:37:36', '2025-12-30 11:37:36'),
(6, 4, 2, 'başarılı', 0, '2025-12-30 11:37:41', '2025-12-30 11:37:41'),
(7, 2, 4, '1', 0, '2025-12-30 11:40:58', '2025-12-30 11:40:58'),
(8, 4, 2, '2', 0, '2025-12-30 11:41:00', '2025-12-30 11:41:00'),
(9, 2, 4, 'd', 0, '2025-12-30 11:56:02', '2025-12-30 11:56:02'),
(10, 4, 2, 'd', 0, '2025-12-30 11:56:05', '2025-12-30 11:56:05'),
(11, 2, 4, 'test 39', 0, '2025-12-30 15:11:13', '2025-12-30 15:11:13'),
(13, 4, 2, 'selam', 0, '2025-12-31 11:53:23', '2025-12-31 11:53:23'),
(14, 2, 4, 'selam', 0, '2025-12-31 11:53:32', '2025-12-31 11:53:32'),
(15, 4, 2, '12', 0, '2025-12-31 11:54:06', '2025-12-31 11:54:06'),
(16, 2, 4, '1', 0, '2025-12-31 12:00:07', '2025-12-31 12:00:07'),
(17, 4, 2, '3', 0, '2025-12-31 12:00:09', '2025-12-31 12:00:09'),
(18, 4, 2, '2', 0, '2026-01-07 05:14:43', '2026-01-07 05:14:43'),
(19, 2, 4, '3', 0, '2026-01-07 05:14:48', '2026-01-07 05:14:48');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_27_152854_create_categories_table', 1),
(5, '2025_10_27_152854_create_courses_table', 1),
(6, '2025_10_27_152856_create_course_user_table', 1),
(7, '2025_10_27_152856_create_lessons_table', 1),
(8, '2025_10_27_152856_create_qna_messages_table', 1),
(9, '2025_10_27_152857_create_assignments_table', 1),
(10, '2025_10_27_152857_create_quizzes_table', 1),
(11, '2025_10_27_152858_create_questions_table', 1),
(12, '2025_10_27_152858_create_submissions_table', 1),
(13, '2025_10_27_152859_create_lesson_user_table', 1),
(14, '2025_12_25_104857_create_lesson_user_table', 2),
(15, '2025_12_25_115454_create_question_options_table', 2),
(16, '2025_12_25_115548_create_quiz_results_table', 2),
(17, '2025_12_25_131548_create_quizzes_table', 3),
(18, '2025_12_25_193318_add_content_to_lessons_table', 4),
(19, '2025_12_25_194915_update_lessons_and_quizzes_tables', 5),
(20, '2025_12_26_111409_create_certificates_table', 6),
(21, '2025_12_26_131853_create_assignments_table', 7),
(22, '2025_12_26_132958_create_assignments_table', 8),
(23, '2025_12_28_130706_create_personal_access_tokens_table', 9),
(24, '2025_12_30_132708_add_student_note_to_submissions_table', 10),
(25, '2025_12_30_135711_create_messages_table', 10);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('deneme@gmail.com', '$2y$12$DDs4F6zBol4ok6pDG1fs..0oLpdyKMHOjQdHTnOn50TOGestOCb.C', '2025-11-04 09:32:00'),
('test123@gmail.com', '$2y$12$ZPSeujm8zi.U2/OTD/LoFerKdvahrdA9lGAwxOTrNqbDGW3VPS46u', '2025-12-30 14:44:19');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `qna_messages`
--

CREATE TABLE `qna_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lesson_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `questions`
--

CREATE TABLE `questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `question_text` text NOT NULL,
  `option_a` varchar(255) DEFAULT NULL,
  `option_b` varchar(255) DEFAULT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `correct_option` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`, `created_at`, `updated_at`) VALUES
(8, 5, 'wmjekdqsNJFIOHQW', 'fjklewşfşowıjkl', 'eıofjwjofewojowlk', 'ejfılmowlfkjew', 'fejıolwkfjlkqw', 'a', '2025-12-25 17:13:38', '2025-12-25 17:13:38'),
(9, 5, 'joıwdjıqkldjkw', 'q', 'jdomqwlkdqlkmd', 'nofopeklqjşl', 'ğpeklşwqa', 'b', '2025-12-25 17:13:49', '2025-12-25 17:13:49'),
(10, 5, 'qwoqjıd.şkl', '.mkmoqwpdjo', 'kpoqwdjşqwidğq', 'kopwdfqkipğdk', 'ğdkpqwdeş', 'd', '2025-12-25 17:14:01', '2025-12-25 17:14:01'),
(155, 2, 'dekdşelwm', 'defkmjoşof', 'fcwefkmklqwj', 'kodqolşwkpflşq', 'kodqpwdpa', 'option_b', '2025-12-27 11:50:17', '2025-12-27 11:50:17'),
(156, 2, 'denemDL', 'dklşfesöafkd.', 'flşeköwd', 'fmlşddml.qw', 'lşföelw.şmş', 'option_c', '2025-12-27 11:50:17', '2025-12-27 11:50:17'),
(157, 2, 'dokpwşleqmo', 'ewfca', 'p2ğ0rokqow', 'kqowpdşkl.', 'dökşl.', 'option_a', '2025-12-27 11:50:17', '2025-12-27 11:50:17'),
(158, 2, 'wqdööprkkqpo', 'opkrp1şl', 'kqorpşko', 'ekop', '2k', 'option_b', '2025-12-27 11:50:17', '2025-12-27 11:50:17'),
(159, 2, 'fğkeroqrwko', 'kporq', 'k', 'kpr', 'pk', 'option_b', '2025-12-27 11:50:17', '2025-12-27 11:50:17'),
(160, 2, 'qwkopqk', '2', '20', '02', '020', 'option_d', '2025-12-27 11:50:17', '2025-12-27 11:50:17'),
(161, 6, 'wldkmqçkl', 'de', 'jklj', 'ke', 'jlkejlk', 'option_a', '2025-12-27 12:07:35', '2025-12-27 12:07:35'),
(175, 20, 'dkrğpş', 'ELŞMRKWKJ', 'KOPRLŞ', 'RKO', 'LŞKLKL', 'a', '2025-12-28 09:04:14', '2025-12-29 11:55:55'),
(176, 21, 'fjkomlqşkrşq', NULL, NULL, NULL, NULL, NULL, '2025-12-28 18:25:16', '2025-12-28 18:25:16'),
(177, 20, 'FOKJŞRLJ', 'korlk', 'fkpqşl', 'epopk', 'oppırp', 'c', '2025-12-29 07:55:29', '2025-12-29 11:37:37'),
(178, 20, 'test', '2', '4', '5', '0', 'd', '2025-12-29 08:01:04', '2025-12-29 12:41:22'),
(179, 22, 'aşağıdaki 5 sayısını seç', '3', '5', '6', '7', 'b', '2025-12-29 15:32:45', '2025-12-30 11:57:06'),
(180, 22, '3 sayısını seçiniz', '2', '98', '3', '9', 'c', '2025-12-30 10:20:18', '2026-01-07 05:14:25'),
(181, 24, 'aşağıdaki hanfgisi 6 dkodkol', '6', 'fojkwoı', 'j', 'rjıo', 'a', '2026-01-07 05:43:21', '2026-01-07 05:43:21');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `question_options`
--

CREATE TABLE `question_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `option_text` varchar(255) NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `question_options`
--

INSERT INTO `question_options` (`id`, `question_id`, `option_text`, `is_correct`, `created_at`, `updated_at`) VALUES
(1, 175, 'lpf', 1, '2025-12-28 09:04:14', '2025-12-28 09:04:14'),
(2, 175, 'iş', 0, '2025-12-28 09:04:14', '2025-12-28 09:04:14'),
(3, 175, 'lödkşf', 0, '2025-12-28 09:04:14', '2025-12-28 09:04:14'),
(4, 175, 'd2şlmrd.', 0, '2025-12-28 09:04:14', '2025-12-28 09:04:14'),
(5, 176, 'kpfdqş', 1, '2025-12-28 18:25:16', '2025-12-28 18:25:16'),
(6, 176, 'kfşqlkklş', 0, '2025-12-28 18:25:16', '2025-12-28 18:25:16'),
(7, 176, 'wqrşlmqş', 0, '2025-12-28 18:25:16', '2025-12-28 18:25:16'),
(8, 176, 'qrklmşqwrmö', 0, '2025-12-28 18:25:16', '2025-12-28 18:25:16');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `quizzes`
--

CREATE TABLE `quizzes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL DEFAULT 30,
  `description` text DEFAULT NULL,
  `time_limit` int(11) DEFAULT NULL,
  `passing_score` int(11) NOT NULL DEFAULT 50,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `quizzes`
--

INSERT INTO `quizzes` (`id`, `course_id`, `title`, `duration`, `description`, `time_limit`, `passing_score`, `created_at`, `updated_at`) VALUES
(2, 6, 'test2', 30, 'deneme', 30, 45, '2025-12-25 10:42:11', '2025-12-25 10:42:11'),
(5, 6, 'test3', 25, NULL, NULL, 45, '2025-12-25 17:13:26', '2025-12-25 17:13:26'),
(6, 6, 'djenqpğ', 30, 'dokqşl', NULL, 50, '2025-12-27 11:59:32', '2025-12-27 11:59:32'),
(20, 9, 'pr3ğ2kğp', 30, '2pğe1', NULL, 70, '2025-12-28 09:04:14', '2025-12-28 09:04:14'),
(21, 9, 'rkoşlq', 30, 'lfkqşlqke', NULL, 38, '2025-12-28 18:25:16', '2025-12-28 18:25:16'),
(22, 22, 'test5', 30, 'kopya çekmek yasaktır.', NULL, 50, '2025-12-29 15:32:23', '2025-12-29 15:32:23'),
(24, 22, 'Deneme', 30, 'test', NULL, 50, '2026-01-07 05:42:28', '2026-01-07 05:42:28');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `quiz_results`
--

CREATE TABLE `quiz_results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `score` int(11) NOT NULL,
  `correct_answers` int(11) NOT NULL DEFAULT 0,
  `wrong_answers` int(11) NOT NULL DEFAULT 0,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`answers`)),
  `is_passed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `quiz_results`
--

INSERT INTO `quiz_results` (`id`, `user_id`, `quiz_id`, `score`, `correct_answers`, `wrong_answers`, `answers`, `is_passed`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 100, 1, 0, '\"{\\\"1\\\":\\\"a\\\"}\"', 1, '2025-12-25 14:06:01', '2025-12-25 11:06:01', '2025-12-25 11:06:01'),
(2, 2, 2, 100, 1, 0, '\"{\\\"1\\\":\\\"a\\\"}\"', 1, '2025-12-25 14:06:06', '2025-12-25 11:06:06', '2025-12-25 11:06:06'),
(3, 2, 2, 100, 1, 0, '{\"1\":\"a\"}', 1, '2025-12-25 14:10:58', '2025-12-25 11:10:58', '2025-12-25 11:10:58'),
(4, 2, 2, 100, 1, 0, '{\"1\":\"a\"}', 1, '2025-12-25 14:11:20', '2025-12-25 11:11:20', '2025-12-25 11:11:20'),
(5, 2, 2, 100, 1, 0, '{\"1\":\"a\"}', 1, '2025-12-25 14:16:22', '2025-12-25 11:16:22', '2025-12-25 11:16:22'),
(6, 2, 2, 0, 0, 1, '{\"1\":\"b\"}', 0, '2025-12-25 14:16:28', '2025-12-25 11:16:28', '2025-12-25 11:16:28'),
(7, 2, 2, 100, 1, 0, '{\"1\":\"a\"}', 1, '2025-12-25 14:18:09', '2025-12-25 11:18:09', '2025-12-25 11:18:09'),
(8, 2, 2, 100, 1, 0, '{\"1\":\"a\"}', 1, '2025-12-25 14:22:34', '2025-12-25 11:22:34', '2025-12-25 11:22:34'),
(9, 2, 2, 100, 1, 0, '{\"1\":\"a\"}', 1, '2025-12-25 14:24:38', '2025-12-25 11:24:38', '2025-12-25 11:24:38'),
(10, 2, 2, 100, 1, 0, '{\"1\":\"a\"}', 1, '2025-12-25 14:26:49', '2025-12-25 11:26:49', '2025-12-25 11:26:49'),
(11, 2, 2, 100, 1, 0, '{\"1\":\"a\"}', 1, '2025-12-25 14:29:12', '2025-12-25 11:29:12', '2025-12-25 11:29:12'),
(21, 2, 2, 0, 0, 1, '{\"1\":\"c\"}', 0, '2025-12-25 14:44:05', '2025-12-25 11:44:05', '2025-12-25 11:44:05'),
(25, 2, 2, 100, 1, 0, '{\"1\":\"a\"}', 1, '2025-12-25 18:37:40', '2025-12-25 15:37:40', '2025-12-25 15:37:40'),
(26, 2, 2, 0, 0, 1, '[]', 0, '2025-12-25 18:44:55', '2025-12-25 15:44:55', '2025-12-25 15:44:55'),
(37, 2, 5, 67, 2, 1, '{\"8\":\"a\",\"9\":\"d\",\"10\":\"d\"}', 1, '2025-12-25 20:14:10', '2025-12-25 17:14:10', '2025-12-25 17:14:10'),
(39, 2, 5, 0, 0, 3, '[]', 0, '2025-12-26 10:58:50', '2025-12-26 07:58:50', '2025-12-26 07:58:50'),
(40, 2, 5, 0, 0, 3, '[]', 0, '2025-12-26 11:24:42', '2025-12-26 08:24:42', '2025-12-26 08:24:42'),
(41, 2, 5, 67, 2, 1, '{\"8\":\"a\",\"9\":\"c\",\"10\":\"d\"}', 1, '2025-12-26 11:24:56', '2025-12-26 08:24:56', '2025-12-26 08:24:56'),
(42, 2, 5, 0, 0, 3, '[]', 0, '2025-12-26 11:35:37', '2025-12-26 08:35:37', '2025-12-26 08:35:37'),
(43, 2, 5, 67, 2, 1, '{\"8\":\"a\",\"9\":\"b\",\"10\":\"c\"}', 1, '2025-12-26 11:38:15', '2025-12-26 08:38:15', '2025-12-26 08:38:15'),
(44, 2, 5, 67, 2, 1, '{\"8\":\"a\",\"9\":\"c\",\"10\":\"d\"}', 1, '2025-12-26 11:41:26', '2025-12-26 08:41:26', '2025-12-26 08:41:26'),
(45, 2, 5, 67, 2, 1, '{\"8\":\"a\",\"9\":\"c\",\"10\":\"d\"}', 1, '2025-12-26 11:51:14', '2025-12-26 08:51:14', '2025-12-26 08:51:14'),
(47, 2, 5, 67, 2, 1, '{\"8\":\"a\",\"9\":\"c\",\"10\":\"d\"}', 1, '2025-12-26 11:53:42', '2025-12-26 08:53:42', '2025-12-26 08:53:42'),
(48, 2, 5, 100, 3, 0, '{\"8\":\"a\",\"9\":\"b\",\"10\":\"d\"}', 1, '2025-12-26 11:55:52', '2025-12-26 08:55:52', '2025-12-26 08:55:52'),
(49, 2, 5, 100, 3, 0, '{\"8\":\"a\",\"9\":\"b\",\"10\":\"d\"}', 1, '2025-12-26 11:58:39', '2025-12-26 08:58:39', '2025-12-26 08:58:39'),
(50, 2, 5, 67, 2, 1, '{\"8\":\"a\",\"9\":\"c\",\"10\":\"d\"}', 1, '2025-12-26 12:02:18', '2025-12-26 09:02:18', '2025-12-26 09:02:18'),
(51, 2, 5, 100, 3, 0, '{\"8\":\"a\",\"9\":\"b\",\"10\":\"d\"}', 1, '2025-12-26 12:05:07', '2025-12-26 09:05:07', '2025-12-26 09:05:07'),
(52, 2, 5, 67, 2, 1, '{\"8\":\"a\",\"9\":\"b\",\"10\":\"a\"}', 1, '2025-12-26 12:07:34', '2025-12-26 09:07:34', '2025-12-26 09:07:34'),
(53, 2, 5, 67, 2, 1, '{\"8\":\"a\",\"9\":\"c\",\"10\":\"d\"}', 1, '2025-12-26 12:09:43', '2025-12-26 09:09:43', '2025-12-26 09:09:43'),
(54, 2, 5, 67, 2, 1, '{\"8\":\"a\",\"9\":\"b\",\"10\":\"c\"}', 1, '2025-12-26 12:17:00', '2025-12-26 09:17:00', '2025-12-26 09:17:00'),
(56, 2, 5, 100, 3, 0, '{\"8\":\"a\",\"9\":\"b\",\"10\":\"d\"}', 1, '2025-12-26 12:20:15', '2025-12-26 09:20:15', '2025-12-26 09:20:15'),
(57, 2, 5, 67, 2, 1, '{\"8\":\"a\",\"9\":\"b\",\"10\":\"a\"}', 1, '2025-12-26 12:22:35', '2025-12-26 09:22:35', '2025-12-26 09:22:35'),
(58, 2, 5, 100, 3, 0, '{\"8\":\"a\",\"9\":\"b\",\"10\":\"d\"}', 1, '2025-12-26 12:23:59', '2025-12-26 09:23:59', '2025-12-26 09:23:59'),
(60, 2, 5, 100, 3, 0, '{\"8\":\"a\",\"9\":\"b\",\"10\":\"d\"}', 1, '2025-12-26 12:28:16', '2025-12-26 09:28:16', '2025-12-26 09:28:16');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `submissions`
--

CREATE TABLE `submissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `assignment_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `student_note` text DEFAULT NULL,
  `grade` int(11) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Talebi oluşturan eğitmenin IDsi',
  `subject` varchar(255) NOT NULL COMMENT 'Talebin konusu',
  `message` text NOT NULL COMMENT 'Eğitmenin mesajı',
  `status` enum('open','closed','pending') NOT NULL DEFAULT 'open' COMMENT 'Talebin durumu',
  `admin_reply` text DEFAULT NULL COMMENT 'Adminin cevabı',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `support_tickets`
--

INSERT INTO `support_tickets` (`id`, `user_id`, `subject`, `message`, `status`, `admin_reply`, `created_at`, `updated_at`) VALUES
(1, 4, 'deneme', '12345', 'pending', 'başarılı', '2025-10-29 12:53:22', '2025-11-06 07:36:17'),
(2, 4, 'yest', '1234', 'open', 'test', '2025-10-29 14:06:15', '2025-11-06 07:36:27'),
(3, 4, 'deeneme', 'test0987', 'closed', 'başarılı', '2025-10-30 11:11:30', '2025-10-30 11:13:42'),
(4, 4, 'merhaba', 'roqjrkljq', 'open', 'merhaba', '2025-12-29 14:33:40', '2025-12-29 14:33:57'),
(5, 4, 'deneme5', '12345', 'closed', 'başarılı', '2025-12-30 13:13:09', '2026-01-06 17:27:45');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','instructor','student') NOT NULL DEFAULT 'student',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Kullanıcı', 'admin@example.com', NULL, '$2y$12$qgNoiK.dKx4p4d6/wABEPuVkqcVS7dxyx.UGFmQbQrbphtjzXg/ne', 'admin', 'l9UHMGOSluwVNiASAwHmyMegM63uAS15a0K6zfawCpBS0H8feWcgwP9vFeYZ', '2025-10-27 16:48:48', '2025-10-27 16:48:48'),
(2, 'eyyüp bademci', 'test123@gmail.com', NULL, '$2y$12$mLtoRBAgPkPYeO0I3nWmUuEqqjIw.7W42.We/c1dbV.RAOhSnw1Bu', 'student', NULL, '2025-10-28 08:38:58', '2025-10-28 08:38:58'),
(4, 'Yusuf Uysal', 'deneme@gmail.com', NULL, '$2y$12$M.xUn9NMmQq5umAHp2e8oO5.hgDXWQuyBVSOAzxksml.4hre.Fx8O', 'instructor', NULL, '2025-10-28 12:52:41', '2025-11-03 11:32:35'),
(5, 'Caner kara', 'caner@gmail.com', NULL, '$2y$12$eM.aVhxTGBbmw0CP.yH1wODSFqpM2U2HnoKyVCcU0WI6SEsEO98KG', 'instructor', NULL, '2025-11-06 08:37:56', '2025-12-30 14:37:58'),
(7, 'ufuk tepe', 'ufuk@gmail.com', NULL, '$2y$12$fteRmgK7zsWZA4u/onZt7.cbEvAzvRXdBYXDzQ5LRhMAQl01hPPQ6', 'student', NULL, '2025-12-30 15:14:40', '2025-12-30 15:18:05'),
(8, 'Caner kara', 'canerkaraf@gmail.com', NULL, '$2y$12$3bffQnK99/CY0ftYX4T8X.//E53j39AzTz5zuOm.e32KnJ/TT9Ah2', 'student', NULL, '2026-01-07 05:38:24', '2026-01-07 05:38:24');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assignments_course_id_foreign` (`course_id`);

--
-- Tablo için indeksler `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assignment_submissions_assignment_id_foreign` (`assignment_id`),
  ADD KEY `assignment_submissions_user_id_foreign` (`user_id`);

--
-- Tablo için indeksler `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Tablo için indeksler `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Tablo için indeksler `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Tablo için indeksler `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `certificates_certificate_code_unique` (`certificate_code`),
  ADD KEY `certificates_user_id_foreign` (`user_id`),
  ADD KEY `certificates_course_id_foreign` (`course_id`);

--
-- Tablo için indeksler `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courses_slug_unique` (`slug`),
  ADD KEY `courses_user_id_foreign` (`user_id`),
  ADD KEY `courses_category_id_foreign` (`category_id`),
  ADD KEY `courses_instructor_id_foreign` (`instructor_id`);

--
-- Tablo için indeksler `course_user`
--
ALTER TABLE `course_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_user_course_id_user_id_unique` (`course_id`,`user_id`),
  ADD KEY `course_user_user_id_foreign` (`user_id`);

--
-- Tablo için indeksler `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Tablo için indeksler `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Tablo için indeksler `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lessons_course_id_foreign` (`course_id`);

--
-- Tablo için indeksler `lesson_user`
--
ALTER TABLE `lesson_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lesson_user_lesson_id_user_id_unique` (`lesson_id`,`user_id`),
  ADD KEY `lesson_user_user_id_foreign` (`user_id`);

--
-- Tablo için indeksler `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`);

--
-- Tablo için indeksler `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Tablo için indeksler `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Tablo için indeksler `qna_messages`
--
ALTER TABLE `qna_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qna_messages_lesson_id_foreign` (`lesson_id`),
  ADD KEY `qna_messages_user_id_foreign` (`user_id`),
  ADD KEY `qna_messages_parent_id_foreign` (`parent_id`);

--
-- Tablo için indeksler `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_quiz_id_foreign` (`quiz_id`);

--
-- Tablo için indeksler `question_options`
--
ALTER TABLE `question_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_options_question_id_foreign` (`question_id`);

--
-- Tablo için indeksler `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizzes_course_id_foreign` (`course_id`);

--
-- Tablo için indeksler `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_results_user_id_foreign` (`user_id`),
  ADD KEY `quiz_results_quiz_id_foreign` (`quiz_id`);

--
-- Tablo için indeksler `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submissions_assignment_id_foreign` (`assignment_id`),
  ADD KEY `submissions_user_id_foreign` (`user_id`);

--
-- Tablo için indeksler `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_tickets_user_id_foreign` (`user_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Tablo için AUTO_INCREMENT değeri `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Tablo için AUTO_INCREMENT değeri `course_user`
--
ALTER TABLE `course_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Tablo için AUTO_INCREMENT değeri `lesson_user`
--
ALTER TABLE `lesson_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Tablo için AUTO_INCREMENT değeri `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `qna_messages`
--
ALTER TABLE `qna_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;

--
-- Tablo için AUTO_INCREMENT değeri `question_options`
--
ALTER TABLE `question_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Tablo için AUTO_INCREMENT değeri `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- Tablo için AUTO_INCREMENT değeri `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `assignment_submissions`
--
ALTER TABLE `assignment_submissions`
  ADD CONSTRAINT `assignment_submissions_assignment_id_foreign` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignment_submissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `certificates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `courses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `course_user`
--
ALTER TABLE `course_user`
  ADD CONSTRAINT `course_user_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `lesson_user`
--
ALTER TABLE `lesson_user`
  ADD CONSTRAINT `lesson_user_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lesson_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `qna_messages`
--
ALTER TABLE `qna_messages`
  ADD CONSTRAINT `qna_messages_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `qna_messages_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `qna_messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `qna_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `question_options`
--
ALTER TABLE `question_options`
  ADD CONSTRAINT `question_options_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD CONSTRAINT `quiz_results_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_results_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_assignment_id_foreign` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `submissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
