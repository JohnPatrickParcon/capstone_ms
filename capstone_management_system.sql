-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2024 at 07:12 AM
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
-- Database: `capstone_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `advisers`
--

CREATE TABLE `advisers` (
  `id` int(11) NOT NULL,
  `adviser_id` int(11) NOT NULL,
  `group_reference` text NOT NULL,
  `score` text DEFAULT NULL,
  `is_finalized` int(11) NOT NULL DEFAULT 0,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `advisers`
--

INSERT INTO `advisers` (`id`, `adviser_id`, `group_reference`, `score`, `is_finalized`, `created_at`, `updated_at`) VALUES
(1, 144, 'YwzAvvRRfRXyZhLydW3MPh4PkGNV38fS37D7EcACJVCDYRGMMA', NULL, 0, '2024-12-22', '2024-12-22'),
(2, 145, 'nlaQdHeWbNntd4P2E9KQdx1faGJTfXdi4T5mR4WV5b5athNumP', NULL, 0, '2024-12-22', '2024-12-22'),
(3, 151, 'yHsRgWuwBJmmLlYJbs5QZuKUQmH88tTlN7PgbZWschTU1fpZco', NULL, 0, '2024-12-22', '2024-12-22');

-- --------------------------------------------------------

--
-- Table structure for table `capstone_grading_forms`
--

CREATE TABLE `capstone_grading_forms` (
  `form_id` int(11) NOT NULL,
  `group_reference` text NOT NULL,
  `data` text NOT NULL,
  `coordinator_info` text DEFAULT NULL,
  `responses` text DEFAULT NULL,
  `approved` int(11) NOT NULL DEFAULT 0,
  `project_status` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `capstone_list`
--

CREATE TABLE `capstone_list` (
  `id` int(11) NOT NULL,
  `capstone_title` text NOT NULL,
  `capstone_file` text NOT NULL,
  `capstone_number` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `adviser_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `is_public` int(11) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `consultation_hours`
--

CREATE TABLE `consultation_hours` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_request`
--

CREATE TABLE `email_request` (
  `id` int(11) NOT NULL,
  `email` text NOT NULL,
  `code` text NOT NULL,
  `is_active` int(11) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `groupings`
--

CREATE TABLE `groupings` (
  `id` int(11) NOT NULL,
  `group_reference` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groupings`
--

INSERT INTO `groupings` (`id`, `group_reference`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'YwzAvvRRfRXyZhLydW3MPh4PkGNV38fS37D7EcACJVCDYRGMMA', 123, '2024-12-22', '2024-12-22'),
(2, 'nlaQdHeWbNntd4P2E9KQdx1faGJTfXdi4T5mR4WV5b5athNumP', 125, '2024-12-22', '2024-12-22'),
(3, 'nlaQdHeWbNntd4P2E9KQdx1faGJTfXdi4T5mR4WV5b5athNumP', 126, '2024-12-22', '2024-12-22'),
(4, 'nlaQdHeWbNntd4P2E9KQdx1faGJTfXdi4T5mR4WV5b5athNumP', 124, '2024-12-22', '2024-12-22'),
(5, 'yHsRgWuwBJmmLlYJbs5QZuKUQmH88tTlN7PgbZWschTU1fpZco', 127, '2024-12-22', '2024-12-22'),
(6, 'yHsRgWuwBJmmLlYJbs5QZuKUQmH88tTlN7PgbZWschTU1fpZco', 139, '2024-12-22', '2024-12-22'),
(7, 'yHsRgWuwBJmmLlYJbs5QZuKUQmH88tTlN7PgbZWschTU1fpZco', 129, '2024-12-22', '2024-12-22');

-- --------------------------------------------------------

--
-- Table structure for table `groupings_capstone`
--

CREATE TABLE `groupings_capstone` (
  `id` int(11) NOT NULL,
  `group_reference` text NOT NULL,
  `title` text NOT NULL,
  `abstract` text DEFAULT NULL,
  `file` text DEFAULT NULL,
  `defense_schedule` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groupings_capstone_comments`
--

CREATE TABLE `groupings_capstone_comments` (
  `id` int(11) NOT NULL,
  `group_reference` text NOT NULL,
  `comments` text NOT NULL,
  `status` int(11) NOT NULL COMMENT '1 = For Revision,\r\n2 = Revised,\r\n3 = Accepted,\r\n4 = Deleted',
  `added_by` int(11) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groupings_capstone_status`
--

CREATE TABLE `groupings_capstone_status` (
  `id` int(11) NOT NULL,
  `status_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groupings_request`
--

CREATE TABLE `groupings_request` (
  `id` int(11) NOT NULL,
  `request_by` int(11) NOT NULL,
  `data` text NOT NULL,
  `is_approved` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groupings_request`
--

INSERT INTO `groupings_request` (`id`, `request_by`, `data`, `is_approved`) VALUES
(4, 123, '{\"final_student_list\":[\"124\",\"125\",\"126\",\"123\"],\"final_panel_list\":[\"146\",\"147\"],\"final_adviser_list\":[\"143\"]}', 2),
(5, 123, '{\"final_student_list\":[\"123\"],\"final_panel_list\":[\"147\",\"148\"],\"final_adviser_list\":[\"144\"]}', 1),
(6, 124, '{\"final_student_list\":[\"125\",\"126\",\"124\"],\"final_panel_list\":[\"147\",\"151\"],\"final_adviser_list\":[\"145\"]}', 1),
(7, 129, '{\"final_student_list\":[\"127\",\"139\",\"129\"],\"final_panel_list\":[\"146\",\"152\"],\"final_adviser_list\":[\"151\"]}', 1),
(8, 135, '{\"final_student_list\":[\"126\",\"141\",\"135\"],\"final_panel_list\":[\"146\",\"152\"],\"final_adviser_list\":[\"149\"]}', 2),
(9, 137, '{\"final_student_list\":[\"138\",\"137\"],\"final_panel_list\":[\"145\",\"146\"],\"final_adviser_list\":[\"150\"]}', 0);

-- --------------------------------------------------------

--
-- Table structure for table `groupings_request_update`
--

CREATE TABLE `groupings_request_update` (
  `id` int(11) NOT NULL,
  `request_by` int(11) NOT NULL,
  `data` text NOT NULL,
  `option_value` text NOT NULL,
  `is_approved` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invitation_token`
--

CREATE TABLE `invitation_token` (
  `id` int(11) NOT NULL,
  `token` text NOT NULL,
  `email` text NOT NULL,
  `enabled` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lib_capstone_status`
--

CREATE TABLE `lib_capstone_status` (
  `id` int(11) NOT NULL,
  `status_tag` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lib_comment_status`
--

CREATE TABLE `lib_comment_status` (
  `id` int(11) NOT NULL,
  `status_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lib_comment_status`
--

INSERT INTO `lib_comment_status` (`id`, `status_name`) VALUES
(1, 'For Revision'),
(2, 'Revised'),
(3, 'Accepted'),
(4, 'Deleted');

-- --------------------------------------------------------

--
-- Table structure for table `lib_roles`
--

CREATE TABLE `lib_roles` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lib_roles`
--

INSERT INTO `lib_roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '2024-07-25', '2024-07-25'),
(2, 'Coordinator', '2024-07-25', '2024-07-25'),
(3, 'Adviser', '2024-07-25', '2024-07-25'),
(4, 'Student', '2024-07-25', '2024-07-25');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) DEFAULT NULL,
  `group_reference` text DEFAULT NULL,
  `time` time NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `targets` text NOT NULL,
  `content` text NOT NULL,
  `users` text NOT NULL,
  `seen_by` text NOT NULL,
  `added_by` int(11) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `targets`, `content`, `users`, `seen_by`, `added_by`, `created_at`) VALUES
(1, 'Groupings Request', 'I Sauer would like to request a groupings.', '[122]', '[]', 123, '2024-12-22'),
(2, 'Groupings Request', 'I Sauer would like to request a groupings.', '[122]', '[]', 123, '2024-12-22'),
(3, 'Groupings Request', 'I Sauer would like to request a groupings.', '[122]', '[]', 123, '2024-12-22'),
(4, 'Groupings Request', 'I Sauer would like to request a groupings.', '[122]', '[]', 123, '2024-12-22'),
(5, 'Groupings Request', 'I Sauer would like to request a groupings.', '[122]', '[]', 123, '2024-12-22'),
(6, 'Groupings Request', 'I Reynolds would like to request a groupings.', '[122]', '[]', 124, '2024-12-22'),
(7, 'Groupings Request', 'I Gaylord would like to request a groupings.', '[122]', '[]', 129, '2024-12-22'),
(8, 'Groupings Request', 'I Hansen would like to request a groupings.', '[122]', '[]', 135, '2024-12-22'),
(9, 'Groupings Request', 'I Kuhic would like to request a groupings.', '[122]', '[]', 137, '2024-12-22'),
(10, 'Groupings Request', 'Groupings has been approved!', '[\"123\",\"147\",\"148\",\"144\"]', '[]', 122, '2024-12-22'),
(11, 'Groupings Request', 'Groupings has been approved!', '[\"125\",\"126\",\"124\",\"147\",\"151\",\"145\"]', '[]', 122, '2024-12-22'),
(12, 'Groupings Request', 'Groupings has been approved!', '[\"127\",\"139\",\"129\",\"146\",\"152\",\"151\"]', '[]', 122, '2024-12-22');

-- --------------------------------------------------------

--
-- Table structure for table `panels`
--

CREATE TABLE `panels` (
  `id` int(11) NOT NULL,
  `panel_id` int(11) NOT NULL,
  `group_reference` text NOT NULL,
  `score` text DEFAULT NULL,
  `is_finalized` int(11) NOT NULL DEFAULT 0,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `panels`
--

INSERT INTO `panels` (`id`, `panel_id`, `group_reference`, `score`, `is_finalized`, `created_at`, `updated_at`) VALUES
(1, 147, 'YwzAvvRRfRXyZhLydW3MPh4PkGNV38fS37D7EcACJVCDYRGMMA', NULL, 0, '2024-12-22', '2024-12-22'),
(2, 148, 'YwzAvvRRfRXyZhLydW3MPh4PkGNV38fS37D7EcACJVCDYRGMMA', NULL, 0, '2024-12-22', '2024-12-22'),
(3, 147, 'nlaQdHeWbNntd4P2E9KQdx1faGJTfXdi4T5mR4WV5b5athNumP', NULL, 0, '2024-12-22', '2024-12-22'),
(4, 151, 'nlaQdHeWbNntd4P2E9KQdx1faGJTfXdi4T5mR4WV5b5athNumP', NULL, 0, '2024-12-22', '2024-12-22'),
(5, 146, 'yHsRgWuwBJmmLlYJbs5QZuKUQmH88tTlN7PgbZWschTU1fpZco', NULL, 0, '2024-12-22', '2024-12-22'),
(6, 152, 'yHsRgWuwBJmmLlYJbs5QZuKUQmH88tTlN7PgbZWschTU1fpZco', NULL, 0, '2024-12-22', '2024-12-22');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `published_capstone`
--

CREATE TABLE `published_capstone` (
  `id` int(11) NOT NULL,
  `group_reference` text NOT NULL,
  `title` text NOT NULL,
  `abstract` text NOT NULL,
  `file` text NOT NULL,
  `enabled` int(11) NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scheduler`
--

CREATE TABLE `scheduler` (
  `schedule_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_reference` text NOT NULL,
  `purpose` text NOT NULL,
  `is_approved` int(11) NOT NULL DEFAULT 0 COMMENT '0 = Pending,\r\n1 = Approved,\r\n2 = Rejected,\r\n3 = Reschedule',
  `date` date NOT NULL,
  `time` time NOT NULL,
  `time_requested` time NOT NULL,
  `approved_by` text NOT NULL DEFAULT '{}'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` int(11) NOT NULL DEFAULT 4,
  `is_verified` int(11) NOT NULL DEFAULT 0,
  `verification_code` varchar(10) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `enabled` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `is_verified`, `verification_code`, `email_verified_at`, `password`, `remember_token`, `enabled`, `created_at`, `updated_at`) VALUES
(122, 'Paucek', 'Paucek@clsu2.edu.ph', 2, 0, NULL, NULL, '$2y$10$8DAyC3A4CERU0/cjhzcdfOe7AyquAn8JE3w6klKeJ7QwsSYcoQgU6', NULL, 1, NULL, NULL),
(123, 'Sauer', 'Sauer@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$X55zj3VajH9976QY.2NjZeAJyATwshMFsFiR5IP7sv4y3flezOP5y', NULL, 1, NULL, NULL),
(124, 'Reynolds', 'Reynolds@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$4x6.qmwYRFz2FrXNbgeYdOT0GznH.xm81FfnWgV8.Hb0xbpbykIxq', NULL, 1, NULL, NULL),
(125, 'Bode', 'Bode@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$8Wm9TJfauUcDQtxwNLHs7.zQuUxAOjIraeuKI8JDTLt.7FVBc4REW', NULL, 1, NULL, NULL),
(126, 'Cronin', 'Cronin@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$4r9Xu6SychaUbMCXfUzPFOcNVNyYyY4F9N7fga0c1/w8Gnmy3hPti', NULL, 1, NULL, NULL),
(127, 'Murray', 'Murray@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$7YUnWh5Fe68u.9oApXXu2OXndx97ujzujaMCZfJ7IHWsuPMtwGBHG', NULL, 1, NULL, NULL),
(128, 'Satterfield', 'Satterfield@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$4MmNeEMCLRPW/NhxtnD0QeypRCQttVxkOjaTf4vPocptGUTgRPzJC', NULL, 1, NULL, NULL),
(129, 'Gaylord', 'Gaylord@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$I.zPN3DGPL049ExuiFKC7eQA17g65Ddn89V5oqsLDdwW//DO1Hzse', NULL, 1, NULL, NULL),
(130, 'Hane', 'Hane@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$lYP7VohqnyJbdB.zQc8UX.JqsVaPvOLCWqW84nKu2C39hHR7dWCYu', NULL, 1, NULL, NULL),
(131, 'Grimes', 'Grimes@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$LNn1NU.Sq1wbIIjkfkx5a.kdidbKGfXYCayltUDRMjRjT3smU2sBK', NULL, 1, NULL, NULL),
(132, 'Ernser', 'Ernser@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$eUf2iu0ULSOfWgPYRuE9a.bZ8orBGS28Ou19n8jF3SgtKrWKS5bHS', NULL, 1, NULL, NULL),
(133, 'Lind', 'Lind@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$CR8wbs1jy4VTnUAtwJPrH.VqvywOsPB8O5UVqrBor/XnwjG76qdPy', NULL, 1, NULL, NULL),
(134, 'Dare', 'Dare@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$SUqBwINTRgXV8rozK4fbauwgiPpBI.dFzuS6.b8HLol7laFDGsIli', NULL, 1, NULL, NULL),
(135, 'Hansen', 'Hansen@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$Scl984xPUNcJpJe4UxOeyesKcoSa136Ou.0bfUwNbQsIUFnog1Qkm', NULL, 1, NULL, NULL),
(136, 'Koepp', 'Koepp@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$P7cZbzNnWGzcGgTiThql9.mfD8pqko3gutBHpmCR8WA6mUcLTMShu', NULL, 0, NULL, NULL),
(137, 'Kuhic', 'Kuhic@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$tME3NVUK4eZ5q056Gy9DweA3uhuUR3GE7WjSNy9.uqcU5dzWkfdNW', NULL, 1, NULL, NULL),
(138, 'Stark', 'Stark@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$MKelwCEkii6o53jnljJTFOyMYRPOXQVl1kD4NZObZeQ2nn.ByaOTS', NULL, 1, NULL, NULL),
(139, 'Gerhold', 'Gerhold@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$ZEbHRCYgdVmHh0WZM0KASeBQ6T0mhG343aJVYC7MSdKVbpSIxZyO.', NULL, 1, NULL, NULL),
(140, 'Jacobi', 'Jacobi@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$XzpCDS5crfXaf82HG92AwO4cheMS4mJjxIC6YK.YkQYcMvVKzFlhi', NULL, 1, NULL, NULL),
(141, 'Wintheiser', 'Wintheiser@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$sezLq/Y5.2gWAFvVL59pQ.cGi.4vCrfsw3zJcPRd92LMVfttRdO0u', NULL, 1, NULL, NULL),
(142, 'Ziemann', 'Ziemann@clsu2.edu.ph', 4, 0, NULL, NULL, '$2y$10$9iX/sD8oxs.HBDDtmTfClOV0H6v9pT/ecCF3kY5jbUITyE5qzb4Oe', NULL, 1, NULL, NULL),
(143, 'Medhurst', 'Medhurst@clsu2.edu.ph', 3, 0, NULL, NULL, '$2y$10$hwIjoLz6r7urBBgP76lKu.kS9p3rhUTGWJrbbc6yDxVvSfwfItCDi', NULL, 1, NULL, NULL),
(144, 'Strosin', 'Strosin@clsu2.edu.ph', 3, 0, NULL, NULL, '$2y$10$FK6lPw28IC4Su5kFuJvcU.Bx6yzZRXjQ.9fUIPmu8fzaNBcCBMnAa', NULL, 1, NULL, NULL),
(145, 'Crist', 'Crist@clsu2.edu.ph', 3, 0, NULL, NULL, '$2y$10$bjjgz/pneuYVSxaqblcld.Vt2g1aHi6rxSPl9d6HejLjg7wf9FoCm', NULL, 1, NULL, NULL),
(146, 'Graham', 'Graham@clsu2.edu.ph', 3, 0, NULL, NULL, '$2y$10$je.6RzB3hTAd7Gpp3B.4EO5.XzxUrpBuGF96gRnX3PxlbOabHWLD2', NULL, 1, NULL, NULL),
(147, 'O\'Hara', 'O\'Hara@clsu2.edu.ph', 3, 0, NULL, NULL, '$2y$10$pkiQFzerCR0u/0OBePwUzekTqXKETLPQDZvEkSfDBDU4cEXXy8/Gy', NULL, 1, NULL, NULL),
(148, 'Schumm', 'Schumm@clsu2.edu.ph', 3, 0, NULL, NULL, '$2y$10$rom8rd73j1n4uQTl868/n.RU9H6YPUz8zdyt8pGnx45AyzZu.65ua', NULL, 1, NULL, NULL),
(149, 'Williamson', 'Williamson@clsu2.edu.ph', 3, 0, NULL, NULL, '$2y$10$PaTKKYhzG3lsV44b.OJrXOX0YgIuJ1zUuvViocI4cxieh27exEdKy', NULL, 1, NULL, NULL),
(150, 'Hodkiewicz', 'Hodkiewicz@clsu2.edu.ph', 3, 0, NULL, NULL, '$2y$10$Z1fWdrwdxXzIDQc.stG6ceaIwnSU8yJRUZ13SMee1eyVbA81oDffe', NULL, 1, NULL, NULL),
(151, 'O\'Kon', 'O\'Kon@clsu2.edu.ph', 3, 0, NULL, NULL, '$2y$10$wLMbkVJa8BEhFgxyPWncrO66gp/l.huiKA4fkYVNRaNQjJoDbU.2W', NULL, 1, NULL, NULL),
(152, 'Nikolaus', 'Nikolaus@clsu2.edu.ph', 3, 0, NULL, NULL, '$2y$10$wxyl7kub4uT2hogOTf/PLOFYqISPMFeXyVB/Fb7R3MwIxuiMATGNG', NULL, 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advisers`
--
ALTER TABLE `advisers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `capstone_grading_forms`
--
ALTER TABLE `capstone_grading_forms`
  ADD PRIMARY KEY (`form_id`);

--
-- Indexes for table `capstone_list`
--
ALTER TABLE `capstone_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consultation_hours`
--
ALTER TABLE `consultation_hours`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_request`
--
ALTER TABLE `email_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `groupings`
--
ALTER TABLE `groupings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groupings_capstone`
--
ALTER TABLE `groupings_capstone`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groupings_capstone_comments`
--
ALTER TABLE `groupings_capstone_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groupings_capstone_status`
--
ALTER TABLE `groupings_capstone_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groupings_request`
--
ALTER TABLE `groupings_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groupings_request_update`
--
ALTER TABLE `groupings_request_update`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invitation_token`
--
ALTER TABLE `invitation_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lib_comment_status`
--
ALTER TABLE `lib_comment_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lib_roles`
--
ALTER TABLE `lib_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `panels`
--
ALTER TABLE `panels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `published_capstone`
--
ALTER TABLE `published_capstone`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scheduler`
--
ALTER TABLE `scheduler`
  ADD PRIMARY KEY (`schedule_id`);

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
-- AUTO_INCREMENT for table `advisers`
--
ALTER TABLE `advisers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `capstone_grading_forms`
--
ALTER TABLE `capstone_grading_forms`
  MODIFY `form_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `capstone_list`
--
ALTER TABLE `capstone_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `consultation_hours`
--
ALTER TABLE `consultation_hours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_request`
--
ALTER TABLE `email_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groupings`
--
ALTER TABLE `groupings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `groupings_capstone`
--
ALTER TABLE `groupings_capstone`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `groupings_capstone_comments`
--
ALTER TABLE `groupings_capstone_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groupings_capstone_status`
--
ALTER TABLE `groupings_capstone_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groupings_request`
--
ALTER TABLE `groupings_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `groupings_request_update`
--
ALTER TABLE `groupings_request_update`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invitation_token`
--
ALTER TABLE `invitation_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lib_comment_status`
--
ALTER TABLE `lib_comment_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lib_roles`
--
ALTER TABLE `lib_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `panels`
--
ALTER TABLE `panels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `published_capstone`
--
ALTER TABLE `published_capstone`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scheduler`
--
ALTER TABLE `scheduler`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
