-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 01, 2026 at 01:31 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `makerfest`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `entity_type` varchar(255) NOT NULL,
  `entity_id` bigint(20) UNSIGNED DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1788260957),
('5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1788260956;', 1788260957);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `event_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Sustainability & Green Tech', 'Renewable energy, waste recycling, clean water solutions', '2026-08-29 01:58:43', '2026-08-29 01:58:43'),
(2, 1, 'Healthcare & MedTech', 'Medical devices, healthcare monitoring, bio-engineering', '2026-08-29 01:58:43', '2026-08-29 01:58:43');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `judge_id` bigint(20) UNSIGNED NOT NULL,
  `rubric_template_id` bigint(20) UNSIGNED NOT NULL,
  `strengths` text DEFAULT NULL,
  `weaknesses` text DEFAULT NULL,
  `suggestions` text DEFAULT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_scores`
--

CREATE TABLE `evaluation_scores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `evaluation_id` bigint(20) UNSIGNED NOT NULL,
  `rubric_criterion_id` bigint(20) UNSIGNED NOT NULL,
  `score` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `year` varchar(255) NOT NULL,
  `registration_open` date NOT NULL,
  `submission_deadline` date NOT NULL,
  `screening_deadline` date NOT NULL,
  `evaluation_start` date NOT NULL,
  `evaluation_deadline` date NOT NULL,
  `publication_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `registration_close` date DEFAULT NULL,
  `revision_deadline` date DEFAULT NULL,
  `withdrawal_cutoff` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `year`, `registration_open`, `submission_deadline`, `screening_deadline`, `evaluation_start`, `evaluation_deadline`, `publication_date`, `is_active`, `created_at`, `updated_at`, `registration_close`, `revision_deadline`, `withdrawal_cutoff`) VALUES
(1, 'Maker Fest Vadodara 2026', '2026', '2026-08-01', '2026-10-15', '2026-10-25', '2026-11-01', '2026-11-10', '2026-11-15', 1, '2026-08-29 01:58:41', '2026-08-29 01:58:41', NULL, NULL, NULL),
(2, 'gf', '2026', '2026-09-23', '2026-10-02', '2026-10-09', '2026-10-10', '2026-10-23', '2026-11-01', 0, '2026-09-01 04:06:52', '2026-09-01 04:06:52', '2026-10-02', '2026-10-16', '2026-10-02');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `jobs`
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
-- Table structure for table `judge_assignments`
--

CREATE TABLE `judge_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `judge_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'assigned',
  `technical_score` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `judge_assignments`
--

INSERT INTO `judge_assignments` (`id`, `project_id`, `judge_id`, `status`, `technical_score`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 5, 9, 'evaluated', 8, 'nice', '2026-09-01 04:43:20', '2026-09-01 05:35:16');

-- --------------------------------------------------------

--
-- Table structure for table `judge_categories`
--

CREATE TABLE `judge_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maker_fest_schema`
--

CREATE TABLE `maker_fest_schema` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(4, '2026_08_15_065304_create_makerfest_tables', 1),
(5, '2026_08_15_065643_create_maker_fest_schema', 1),
(6, '2026_08_15_080000_add_otp_fields_to_users_table', 2),
(7, '2026_09_01_102510_add_youtube_links_to_projects_table', 3),
(8, '2026_09_01_102509_create_project_media_table', 4),
(9, '2026_08_15_081000_update_events_and_projects_tables', 5),
(10, '2026_09_01_062141_add_school_name_to_project_members_table', 5),
(11, '2026_09_01_104030_add_score_and_remarks_to_judge_assignments_table', 6);

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
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `leader_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `registration_type` varchar(255) NOT NULL DEFAULT 'individual',
  `registration_for` varchar(255) NOT NULL DEFAULT 'myself',
  `mentor_name` varchar(255) DEFAULT NULL,
  `mentor_email` varchar(255) DEFAULT NULL,
  `school_organization_name` varchar(255) DEFAULT NULL,
  `mentor_designation` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Draft',
  `youtube_link_1` varchar(255) DEFAULT NULL,
  `youtube_link_2` varchar(255) DEFAULT NULL,
  `final_decision` varchar(255) NOT NULL DEFAULT 'pending',
  `submitted_at` datetime DEFAULT NULL,
  `revision_deadline` datetime DEFAULT NULL,
  `revision_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `event_id`, `leader_id`, `category_id`, `project_code`, `title`, `description`, `registration_type`, `registration_for`, `mentor_name`, `mentor_email`, `school_organization_name`, `mentor_designation`, `status`, `youtube_link_1`, `youtube_link_2`, `final_decision`, `submitted_at`, `revision_deadline`, `revision_note`, `created_at`, `updated_at`) VALUES
(5, 1, 6, 1, 'PRJ-2026-346', 'drone', 'abcd', 'individual', 'myself', NULL, NULL, 'charusat', NULL, 'Evaluated', 'https://www.youtube.com/watch?v=WZp3gfXCxRc', 'https://www.youtube.com/watch?v=WZp3gfXCxRc', 'pending', '2026-09-01 08:49:09', NULL, NULL, '2026-09-01 03:17:30', '2026-09-01 05:35:16');

-- --------------------------------------------------------

--
-- Table structure for table `project_media`
--

CREATE TABLE `project_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `side` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_media`
--

INSERT INTO `project_media` (`id`, `project_id`, `file_path`, `side`, `created_at`, `updated_at`) VALUES
(4, 5, 'project_media/1788252451_5_front.jpg', 'front', '2026-09-01 03:17:31', '2026-09-01 03:17:31'),
(5, 5, 'project_media/1788252518_5_right.jpg', 'right', '2026-09-01 03:17:31', '2026-09-01 03:18:38'),
(6, 5, 'project_media/1788252518_5_back.jpg', 'back', '2026-09-01 03:18:38', '2026-09-01 03:18:38'),
(7, 5, 'project_media/1788252518_5_left.jpg', 'left', '2026-09-01 03:18:38', '2026-09-01 03:18:38'),
(8, 5, 'project_media/1788252518_5_top.jpg', 'top', '2026-09-01 03:18:38', '2026-09-01 03:18:38'),
(9, 5, 'project_media/1788252518_5_bottom.jpg', 'bottom', '2026-09-01 03:18:38', '2026-09-01 03:18:38');

-- --------------------------------------------------------

--
-- Table structure for table `project_members`
--

CREATE TABLE `project_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) NOT NULL,
  `school_name` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `class_standard` varchar(255) DEFAULT NULL,
  `invite_status` varchar(255) NOT NULL DEFAULT 'accepted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_members`
--

INSERT INTO `project_members` (`id`, `project_id`, `user_id`, `name`, `email`, `mobile`, `school_name`, `dob`, `class_standard`, `invite_status`, `created_at`, `updated_at`) VALUES
(15, 5, 6, 'krunal', 'krunalextra11@gmail.com', '', 'charusat', NULL, NULL, 'accepted', '2026-09-01 03:19:10', '2026-09-01 03:19:10'),
(16, 5, NULL, 'rushabh', 'soulgoodman886@gmail.com', '', 'charusat', NULL, NULL, 'accepted', '2026-09-01 03:19:10', '2026-09-01 03:19:10'),
(17, 5, NULL, 'yug', 'heisenberg10233@gmail.com', '', 'charusat', NULL, NULL, 'accepted', '2026-09-01 03:19:10', '2026-09-01 03:19:10');

-- --------------------------------------------------------

--
-- Table structure for table `rubric_criteria`
--

CREATE TABLE `rubric_criteria` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rubric_template_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `max_score` int(11) NOT NULL DEFAULT 10,
  `weightage` int(11) NOT NULL DEFAULT 20,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rubric_criteria`
--

INSERT INTO `rubric_criteria` (`id`, `rubric_template_id`, `name`, `description`, `max_score`, `weightage`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Technical Complexity', 'Evaluates the technical difficulty, engineering quality, and execution.', 10, 40, 1, '2026-08-29 01:58:43', '2026-08-29 01:58:43'),
(2, 1, 'Innovation & Originality', 'Assesses how unique, creative, and novel the solution is.', 10, 30, 2, '2026-08-29 01:58:43', '2026-08-29 01:58:43');

-- --------------------------------------------------------

--
-- Table structure for table `rubric_templates`
--

CREATE TABLE `rubric_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rubric_templates`
--

INSERT INTO `rubric_templates` (`id`, `event_id`, `name`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 1, 'Standard Evaluation Rubric 2026', 1, '2026-08-29 01:58:43', '2026-08-29 01:58:43');

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
('BEv0WjZj1Zia6mtPklSgoYTpNcBLYk4MWoEfbnYD', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'YTo2OntzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL21ha2VyL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NjoiX3Rva2VuIjtzOjQwOiJtZHJoUDgwTHlCUm9TZFBFRkZaelFkNWlOcWhZSGtEZnpkTEJ1eXpGIjtzOjc6InVzZXJfaWQiO2k6NjtzOjk6InVzZXJfcm9sZSI7czo1OiJtYWtlciI7czo5OiJ1c2VyX25hbWUiO3M6Njoia3J1bmFsIjt9', 1788260898);

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
  `role` varchar(255) NOT NULL DEFAULT 'maker',
  `mobile` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `otp_code` varchar(255) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `mobile`, `status`, `otp_code`, `otp_expires_at`) VALUES
(1, 'Admin User', 'admin@gmail.com', NULL, '$2y$12$CVBlywyFWuan2KskMQauJ.xGRUQo0muOMOUPHWtkYxDrqAn8bwiyC', NULL, '2026-08-29 01:58:41', '2026-08-29 01:58:41', 'admin', NULL, 'active', NULL, NULL),
(3, 'Judge User', 'judge@gmail.com', NULL, '$2y$12$Z2CjWZxdl5nkd8jeKBX7a.FrbSOQZptOSiDwG8uyRw.OChc9oEpN6', NULL, '2026-08-29 01:58:42', '2026-08-29 01:58:42', 'judge', NULL, 'active', NULL, NULL),
(4, 'Volunteer User', 'volunteer@gmail.com', NULL, '$2y$12$lF5Q0ZbD4FUvj7slIwlzU.cP0x8nsDowsCyByuW7z5Ohu1V8XY9b6', NULL, '2026-08-29 01:58:43', '2026-08-29 01:58:43', 'volunteer', NULL, 'active', NULL, NULL),
(6, 'krunal', 'krunalextra11@gmail.com', NULL, '$2y$12$mk6JxGDviyF1H0cRJQULeeCB8WWvO9kYL8QcD8e5nIprUd0Dj0LDC', NULL, '2026-08-31 23:47:25', '2026-09-01 04:25:01', 'maker', NULL, 'active', NULL, NULL),
(7, 'rushabh', 'soulgoodman886@gmail.com', NULL, '$2y$12$F0tiQmRn4QfxBWzdTeK/8u4Q3BbprzVBfVVPt6IJR08z0lPfTnDaS', NULL, '2026-09-01 03:22:48', '2026-09-01 03:22:48', 'maker', NULL, 'active', NULL, NULL),
(8, 'yug', 'heisenberg10233@gmail.com', NULL, '$2y$12$kPubGbW1QnfCSBPWrvkeHui//Bpn5x6qCJFLhB2ImI/CahAMzyOye', NULL, '2026-09-01 03:24:24', '2026-09-01 03:24:24', 'maker', NULL, 'active', NULL, NULL),
(9, 'judgekrv', 'gudibhai9116@gmail.com', NULL, '$2y$12$JF3sBGLYAXtG9nfPkKIij.9JvKKX8qCBp/fc5MFF2EWhrethDHLHK', NULL, '2026-09-01 04:17:53', '2026-09-01 04:22:32', 'judge', NULL, 'active', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `volunteer_tasks`
--

CREATE TABLE `volunteer_tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `volunteer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `priority` varchar(255) NOT NULL DEFAULT 'medium',
  `status` varchar(255) NOT NULL DEFAULT 'To Do',
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `volunteer_tasks`
--

INSERT INTO `volunteer_tasks` (`id`, `event_id`, `volunteer_id`, `title`, `description`, `priority`, `status`, `due_date`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 'Prepare Venue Exhibition Floor Layout', 'Draft the floor plan for Main Convention Hall including power outlets.', 'high', 'In Progress', '2026-10-24', '2026-08-29 01:58:44', '2026-08-29 01:58:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_foreign` (`user_id`);

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_event_id_foreign` (`event_id`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evaluations_project_id_foreign` (`project_id`),
  ADD KEY `evaluations_judge_id_foreign` (`judge_id`),
  ADD KEY `evaluations_rubric_template_id_foreign` (`rubric_template_id`);

--
-- Indexes for table `evaluation_scores`
--
ALTER TABLE `evaluation_scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evaluation_scores_evaluation_id_foreign` (`evaluation_id`),
  ADD KEY `evaluation_scores_rubric_criterion_id_foreign` (`rubric_criterion_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `judge_assignments`
--
ALTER TABLE `judge_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `judge_assignments_project_id_foreign` (`project_id`),
  ADD KEY `judge_assignments_judge_id_foreign` (`judge_id`);

--
-- Indexes for table `judge_categories`
--
ALTER TABLE `judge_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `judge_categories_user_id_foreign` (`user_id`),
  ADD KEY `judge_categories_category_id_foreign` (`category_id`);

--
-- Indexes for table `maker_fest_schema`
--
ALTER TABLE `maker_fest_schema`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projects_project_code_unique` (`project_code`),
  ADD KEY `projects_event_id_foreign` (`event_id`),
  ADD KEY `projects_leader_id_foreign` (`leader_id`),
  ADD KEY `projects_category_id_foreign` (`category_id`);

--
-- Indexes for table `project_media`
--
ALTER TABLE `project_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_media_project_id_foreign` (`project_id`);

--
-- Indexes for table `project_members`
--
ALTER TABLE `project_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_members_project_id_foreign` (`project_id`),
  ADD KEY `project_members_user_id_foreign` (`user_id`);

--
-- Indexes for table `rubric_criteria`
--
ALTER TABLE `rubric_criteria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rubric_criteria_rubric_template_id_foreign` (`rubric_template_id`);

--
-- Indexes for table `rubric_templates`
--
ALTER TABLE `rubric_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rubric_templates_event_id_foreign` (`event_id`);

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
-- Indexes for table `volunteer_tasks`
--
ALTER TABLE `volunteer_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `volunteer_tasks_event_id_foreign` (`event_id`),
  ADD KEY `volunteer_tasks_volunteer_id_foreign` (`volunteer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evaluation_scores`
--
ALTER TABLE `evaluation_scores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `judge_assignments`
--
ALTER TABLE `judge_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `judge_categories`
--
ALTER TABLE `judge_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maker_fest_schema`
--
ALTER TABLE `maker_fest_schema`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `project_media`
--
ALTER TABLE `project_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `project_members`
--
ALTER TABLE `project_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `rubric_criteria`
--
ALTER TABLE `rubric_criteria`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rubric_templates`
--
ALTER TABLE `rubric_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `volunteer_tasks`
--
ALTER TABLE `volunteer_tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_judge_id_foreign` FOREIGN KEY (`judge_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_rubric_template_id_foreign` FOREIGN KEY (`rubric_template_id`) REFERENCES `rubric_templates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `evaluation_scores`
--
ALTER TABLE `evaluation_scores`
  ADD CONSTRAINT `evaluation_scores_evaluation_id_foreign` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluation_scores_rubric_criterion_id_foreign` FOREIGN KEY (`rubric_criterion_id`) REFERENCES `rubric_criteria` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `judge_assignments`
--
ALTER TABLE `judge_assignments`
  ADD CONSTRAINT `judge_assignments_judge_id_foreign` FOREIGN KEY (`judge_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `judge_assignments_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `judge_categories`
--
ALTER TABLE `judge_categories`
  ADD CONSTRAINT `judge_categories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `judge_categories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `projects_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_leader_id_foreign` FOREIGN KEY (`leader_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_media`
--
ALTER TABLE `project_media`
  ADD CONSTRAINT `fk_pm_pid_new` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_media_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_members`
--
ALTER TABLE `project_members`
  ADD CONSTRAINT `project_members_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `rubric_criteria`
--
ALTER TABLE `rubric_criteria`
  ADD CONSTRAINT `rubric_criteria_rubric_template_id_foreign` FOREIGN KEY (`rubric_template_id`) REFERENCES `rubric_templates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rubric_templates`
--
ALTER TABLE `rubric_templates`
  ADD CONSTRAINT `rubric_templates_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `volunteer_tasks`
--
ALTER TABLE `volunteer_tasks`
  ADD CONSTRAINT `volunteer_tasks_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `volunteer_tasks_volunteer_id_foreign` FOREIGN KEY (`volunteer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
