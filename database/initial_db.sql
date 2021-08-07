-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 192.168.1.102
-- Generation Time: Nov 16, 2019 at 07:23 AM
-- Server version: 10.2.28-MariaDB-10.2.28+maria~stretch
-- PHP Version: 7.2.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lu_erp`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date NOT NULL,
  `platform` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `followers_count` int(11) DEFAULT NULL,
  `posts_count` int(11) DEFAULT NULL,
  `dp_count` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `is_processed` tinyint(1) NOT NULL DEFAULT 0,
  `broadcast` int(11) NOT NULL DEFAULT 0,
  `broadcasted_messages` int(11) NOT NULL DEFAULT 0,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `manual_comment` int(11) NOT NULL DEFAULT 0,
  `bulk_comment` int(11) NOT NULL DEFAULT 0,
  `blocked` int(11) NOT NULL DEFAULT 0,
  `is_seeding` int(11) NOT NULL DEFAULT 0,
  `seeding_stage` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int(10) UNSIGNED NOT NULL,
  `subject_id` int(11) NOT NULL,
  `subject_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `causer_id` int(11) NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activities_routines`
--

CREATE TABLE `activities_routines` (
  `id` int(10) UNSIGNED NOT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `times_a_day` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `times_a_week` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `times_a_month` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `log_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `subject_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` int(11) DEFAULT NULL,
  `causer_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `properties` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_schedules`
--

CREATE TABLE `ads_schedules` (
  `id` int(10) UNSIGNED NOT NULL,
  `scheduled_for` datetime NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_schedules_attachments`
--

CREATE TABLE `ads_schedules_attachments` (
  `ads_schedule_id` int(10) UNSIGNED NOT NULL,
  `attachment_id` int(10) UNSIGNED NOT NULL,
  `attachment_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

CREATE TABLE `agents` (
  `id` int(10) UNSIGNED NOT NULL,
  `model_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `analytics`
--

CREATE TABLE `analytics` (
  `operatingSystem` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `social_network` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `device_info` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sessions` int(255) NOT NULL,
  `pageviews` int(255) NOT NULL,
  `bounceRate` int(255) NOT NULL,
  `avgSessionDuration` bigint(255) NOT NULL,
  `timeOnPage` bigint(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `analytics_summaries`
--

CREATE TABLE `analytics_summaries` (
  `brand_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `article_categories`
--

CREATE TABLE `article_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assets_category`
--

CREATE TABLE `assets_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `cat_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assets_manager`
--

CREATE TABLE `assets_manager` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `asset_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `purchase_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_cycle` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(8,2) NOT NULL,
  `archived` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assigned_user_pages`
--

CREATE TABLE `assigned_user_pages` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `menu_page_id` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assinged_department_menu`
--

CREATE TABLE `assinged_department_menu` (
  `department_id` int(10) UNSIGNED NOT NULL,
  `menu_page_id` int(10) UNSIGNED NOT NULL,
  `Admin` tinyint(1) NOT NULL,
  `HOD` tinyint(1) NOT NULL,
  `Supervisor` tinyint(1) NOT NULL,
  `Users` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uploaded_to` int(11) NOT NULL,
  `external` tinyint(1) NOT NULL,
  `order` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attribute_replacements`
--

CREATE TABLE `attribute_replacements` (
  `id` int(10) UNSIGNED NOT NULL,
  `field_identifier` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_term` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_to_peform` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `replacement_term` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `authorized_by` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `automated_messages`
--

CREATE TABLE `automated_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auto_comment_histories`
--

CREATE TABLE `auto_comment_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `target` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_id` int(11) NOT NULL,
  `auto_reply_hashtag_id` int(11) NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `caption` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `is_verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auto_replies`
--

CREATE TABLE `auto_replies` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keyword` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reply` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sending_time` datetime DEFAULT NULL,
  `repeat` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auto_reply_hashtags`
--

CREATE TABLE `auto_reply_hashtags` (
  `id` int(10) UNSIGNED NOT NULL,
  `text` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `back_linkings`
--

CREATE TABLE `back_linkings` (
  `id` int(255) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `back_link_checker`
--

CREATE TABLE `back_link_checker` (
  `id` int(10) UNSIGNED NOT NULL,
  `domains` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `links` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `review_numbers` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rank` int(11) NOT NULL,
  `rating` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serp_id` int(11) NOT NULL,
  `snippet` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `visible_link` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `back_link_checkers`
--

CREATE TABLE `back_link_checkers` (
  `id` int(10) UNSIGNED NOT NULL,
  `domains` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `links` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `review_numbers` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rank` int(11) NOT NULL,
  `rating` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serp_id` int(11) NOT NULL,
  `snippet` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `visible_link` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `benchmarks`
--

CREATE TABLE `benchmarks` (
  `id` int(10) UNSIGNED NOT NULL,
  `selections` int(11) NOT NULL DEFAULT 0,
  `searches` int(11) NOT NULL DEFAULT 0,
  `attributes` int(11) NOT NULL DEFAULT 0,
  `supervisor` int(11) NOT NULL DEFAULT 0,
  `imagecropper` int(11) NOT NULL DEFAULT 0,
  `lister` int(11) NOT NULL DEFAULT 0,
  `approver` int(11) NOT NULL DEFAULT 0,
  `inventory` int(11) NOT NULL DEFAULT 0,
  `for_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bloggers`
--

CREATE TABLE `bloggers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_handle` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agency` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `followers` int(11) DEFAULT NULL,
  `followings` int(11) DEFAULT NULL,
  `avg_engagement` int(11) DEFAULT NULL,
  `fake_followers` int(11) DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `industry` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brands` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogger_email_templates`
--

CREATE TABLE `blogger_email_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `from` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bcc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogger_payments`
--

CREATE TABLE `blogger_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `blogger_id` int(10) UNSIGNED NOT NULL,
  `currency` int(11) NOT NULL DEFAULT 0,
  `payment_date` date DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `payable_amount` decimal(13,4) DEFAULT NULL,
  `paid_amount` decimal(13,4) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogger_products`
--

CREATE TABLE `blogger_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `blogger_id` int(10) UNSIGNED NOT NULL,
  `brand_id` int(10) UNSIGNED NOT NULL,
  `shoot_date` date DEFAULT NULL,
  `first_post` date DEFAULT NULL,
  `second_post` date DEFAULT NULL,
  `first_post_likes` int(11) DEFAULT NULL,
  `first_post_engagement` int(11) DEFAULT NULL,
  `first_post_response` int(11) DEFAULT NULL,
  `first_post_sales` int(11) DEFAULT NULL,
  `second_post_likes` int(11) DEFAULT NULL,
  `second_post_engagement` int(11) DEFAULT NULL,
  `second_post_response` int(11) DEFAULT NULL,
  `second_post_sales` int(11) DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `initial_quote` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_quote` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `images` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogger_product_images`
--

CREATE TABLE `blogger_product_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blogger_product_id` int(10) UNSIGNED NOT NULL,
  `other` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `restricted` tinyint(1) NOT NULL DEFAULT 0,
  `image_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookshelves`
--

CREATE TABLE `bookshelves` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `restricted` tinyint(1) NOT NULL DEFAULT 0,
  `image_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookshelves_books`
--

CREATE TABLE `bookshelves_books` (
  `bookshelf_id` int(10) UNSIGNED NOT NULL,
  `book_id` int(10) UNSIGNED NOT NULL,
  `order` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_activities`
--

CREATE TABLE `book_activities` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extra` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `entity_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_comments`
--

CREATE TABLE `book_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `entity_id` int(10) UNSIGNED NOT NULL,
  `entity_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `html` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `local_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_images`
--

CREATE TABLE `book_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `path` varchar(400) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uploaded_to` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_tags`
--

CREATE TABLE `book_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `entity_id` int(11) NOT NULL,
  `entity_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `euro_to_inr` double NOT NULL,
  `deduction_percentage` int(11) NOT NULL,
  `magento_id` int(11) UNSIGNED DEFAULT 0,
  `brand_segment` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku_strip_last` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku_add` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `references` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `euro_to_inr`, `deduction_percentage`, `magento_id`, `brand_segment`, `sku_strip_last`, `sku_add`, `references`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'ALEXANDER McQUEEN', 100, 20, 176, 'A', '4', NULL, 'Alexander Mc Queen', '2018-08-23 07:04:11', '2019-08-30 11:44:24', NULL),
(2, 'BALENCIAGA', 100, 20, 181, 'A', '4', NULL, NULL, '2018-08-23 07:04:11', '2019-08-30 12:16:10', NULL),
(3, 'BOTTEGA VENETA', 105, 15, 136, 'A', '4', NULL, NULL, '2018-08-23 07:12:11', '2019-08-30 11:55:34', NULL),
(4, 'BURBERRY', 105, 20, 137, 'A', NULL, NULL, NULL, '2018-08-23 07:13:52', '2018-10-11 21:42:02', NULL),
(5, 'BVLGARI', 100, 40, 180, 'A', NULL, NULL, 'Bulgari', '2018-08-23 08:23:31', '2018-09-22 19:31:02', NULL),
(6, 'CELINE', 93.5, 10, 158, 'A', '4', NULL, 'Céline', '2018-08-23 08:23:45', '2019-08-30 11:56:32', NULL),
(7, 'CHLOE', 100, 20, 135, 'A', '3', NULL, 'Chloé;CHLOE\';Chloe`', '2018-08-23 08:26:34', '2019-08-30 11:56:47', NULL),
(8, 'CHRISTIAN DIOR', 100, 10, 170, 'A', '4', NULL, 'DIOR;DIOR HOMME;DIOR EYEWEAR', '2018-08-23 08:26:46', '2019-08-30 11:57:17', NULL),
(9, 'CHRISTIAN LOUBOUTIN', 93.5, 10, 172, 'A', NULL, NULL, NULL, '2018-08-23 08:27:11', '2018-10-11 21:42:58', NULL),
(10, 'DOLCE & GABBANA', 100, 20, 138, 'A', '5', NULL, 'DOLCE&GABBANA;DOLCE GABBANA;DOLCE GABBANA;D&G', '2018-08-23 08:27:19', '2019-08-30 12:16:32', NULL),
(11, 'FENDI', 92, 20, 139, 'A', '5', NULL, 'FENDI EYEWEAR', '2018-08-23 08:27:35', '2018-10-11 21:43:24', NULL),
(12, 'GIVENCHY', 100, 20, 140, 'A', '3', NULL, 'GICENCY', '2018-08-23 08:28:15', '2018-10-11 21:43:40', NULL),
(13, 'GUCCI', 85, 20, 141, 'A', '4', NULL, NULL, '2018-08-23 08:28:28', '2018-08-31 14:48:33', NULL),
(14, 'ISSEY MIYAKE', 93.5, 10, 179, 'A', NULL, NULL, 'BAO BAO ISSEY MIYAKE;ISSEY MIYAKE MEN;ISSEY MIYAKE BAO BAO', '2018-08-23 08:28:44', '2018-10-11 21:43:57', NULL),
(15, 'JIMMY CHOO', 105, 20, 174, 'A', NULL, NULL, 'JIMMY CHOO EYEWEAR;JIMMY CHOO*', '2018-08-23 08:28:54', '2018-10-11 21:44:11', NULL),
(16, 'MICHAEL KORS', 78, 20, 175, 'C', NULL, NULL, 'Michael Michael Kors;MK MICHAEL KORS;MK', '2018-08-23 08:29:05', '2018-10-11 21:44:25', NULL),
(17, 'MIU MIU', 100, 20, 142, 'A', '5', NULL, NULL, '2018-08-23 08:29:14', '2018-10-11 21:44:39', NULL),
(18, 'PRADA', 100, 20, 143, 'A', '5', NULL, NULL, '2018-08-23 08:29:23', '2018-10-11 21:44:50', NULL),
(19, 'YVES SAINT LAURENT', 100, 20, 144, 'A', '4', NULL, 'Saint Laurent;St Laurent;YSL', '2018-08-23 08:29:30', '2019-08-30 12:17:02', NULL),
(20, 'SALVATORE FERRAGAMO', 100, 20, 145, 'A', NULL, NULL, 'FERRAGAMO', '2018-08-23 08:29:41', '2019-08-30 12:17:30', NULL),
(21, 'STELLA MCCARTNEY', 100, 20, 146, 'A', '4', NULL, 'STELLA MC CARTNEY', '2018-08-23 08:29:50', '2018-10-11 21:45:30', NULL),
(22, 'TODS', 78, 20, 178, 'A', '3', NULL, 'TOD\'S', '2018-08-23 08:30:01', '2018-10-11 21:45:46', NULL),
(23, 'TOM FORD', 100, 20, 182, 'A', '3', NULL, 'TOM FORD EYEWEAR', '2018-08-23 08:30:12', '2018-10-11 21:46:28', NULL),
(24, 'TORY BURCH', 100, 15, 183, 'C', NULL, NULL, NULL, '2018-08-23 08:30:22', '2018-10-11 21:46:00', NULL),
(25, 'VALENTINO GARAVANI', 100, 20, 148, 'A', '3', NULL, 'VALENTINO GARAVANI UOMO;VALENTINO', '2018-08-23 08:30:30', '2019-08-30 12:18:01', NULL),
(26, 'VERSACE', 100, 20, 149, 'A', '4', NULL, NULL, '2018-08-23 08:30:39', '2018-10-11 21:46:40', NULL),
(27, 'OFF WHITE', 100, 20, 213, 'B', '4', NULL, 'OFF-WHITE', '2018-10-15 21:16:53', '2019-08-30 12:18:33', NULL),
(28, 'RED VALENTINO', 100, 20, 224, 'A', NULL, NULL, 'REDVALENTINO;VALENTINO RED;RED (V)', '2018-11-15 18:34:53', '2018-11-15 18:34:53', NULL),
(29, 'MOSCHINO', 100, 20, 223, 'B', NULL, NULL, NULL, '2018-11-15 18:35:33', '2018-11-15 18:35:33', NULL),
(30, 'MARC JACOBS', 100, 20, 233, 'B', NULL, NULL, NULL, '2019-01-12 17:33:59', '2019-08-30 12:19:02', NULL),
(31, 'DIOR HOMME', 100, 20, 234, 'A', NULL, NULL, NULL, '2019-01-12 20:48:54', '2019-01-12 20:48:54', NULL),
(32, 'KENZO', 100, 20, 235, 'B', '2', NULL, 'KENZO DONNA', '2019-01-12 20:51:46', '2019-01-12 20:51:46', NULL),
(33, 'Philipp Plein', 100, 20, 236, 'B', NULL, NULL, NULL, '2019-01-12 20:52:53', '2019-08-30 12:19:31', NULL),
(34, 'Self Portrait', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 02:45:01', '2019-02-26 19:21:30', '2019-02-26 19:21:30'),
(35, 'Ganni', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 02:45:30', '2019-02-27 19:29:56', '2019-02-27 19:29:56'),
(37, 'Dsquared', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 02:45:54', '2019-02-27 19:30:13', '2019-02-27 19:30:13'),
(38, 'Chloe\'', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 02:46:20', '2019-02-27 19:30:28', '2019-02-27 19:30:28'),
(39, 'Cult Gaia', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 02:46:34', '2019-02-27 19:30:35', '2019-02-27 19:30:35'),
(40, 'Aquazzura', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 02:47:57', '2019-02-27 19:30:42', '2019-02-27 19:30:42'),
(41, 'Iceberg', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 02:49:10', '2019-02-27 19:30:48', '2019-02-27 19:30:48'),
(42, 'Valentino', 0, 0, 0, 'A', NULL, NULL, NULL, '2019-02-04 02:49:37', '2019-02-27 19:31:02', '2019-02-27 19:31:02'),
(43, 'Dolce &amp; Gabbana', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 02:49:49', '2019-02-27 19:30:54', '2019-02-27 19:30:54'),
(44, 'Alberta Ferretti', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 02:50:28', '2019-02-26 18:01:10', '2019-02-26 18:01:10'),
(45, 'Veronica Beard', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 03:00:34', '2019-02-27 19:31:10', '2019-02-27 19:31:10'),
(46, 'Seven For All Mankind', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 03:01:01', '2019-02-27 19:31:29', '2019-02-27 19:31:29'),
(47, 'Pollini', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 03:03:08', '2019-02-27 19:31:56', '2019-02-27 19:31:56'),
(48, 'Woolrich', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 03:04:00', '2019-02-27 19:31:47', '2019-02-27 19:31:47'),
(49, 'Loro Piana', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 03:34:05', '2019-02-27 19:31:41', '2019-02-27 19:31:41'),
(50, 'Pt01', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 03:36:37', '2019-02-26 19:21:53', '2019-02-26 19:21:53'),
(51, 'Theory', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 03:36:59', '2019-02-26 19:22:05', '2019-02-26 19:22:05'),
(52, 'Vivetta', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 03:37:25', '2019-02-26 19:22:17', '2019-02-26 19:22:17'),
(53, 'Blugirl-blumarine', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 03:39:40', '2019-02-27 19:31:35', '2019-02-27 19:31:35'),
(54, 'Blumarine', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 03:40:08', '2019-02-27 19:26:38', '2019-02-27 19:26:38'),
(55, 'Golden Goose Deluxe Brand', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 03:41:15', '2019-02-27 19:26:45', '2019-02-27 19:26:45'),
(56, 'Herno', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 04:00:30', '2019-02-27 19:26:51', '2019-02-27 19:26:51'),
(57, 'Coliac', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 04:02:25', '2019-02-27 19:26:59', '2019-02-27 19:26:59'),
(58, 'Drkshdw', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 04:03:05', '2019-02-27 19:27:04', '2019-02-27 19:27:04'),
(59, 'Bally', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 04:03:53', '2019-02-27 19:27:10', '2019-02-27 19:27:10'),
(60, 'Comme Des Garcons Play', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 04:05:38', '2019-02-27 19:27:16', '2019-02-27 19:27:16'),
(61, 'Hogan', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 04:06:27', '2019-02-27 19:27:23', '2019-02-27 19:27:23'),
(62, 'Vans', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 04:07:06', '2019-02-27 19:27:30', '2019-02-27 19:27:30'),
(63, 'Yuzefi', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 05:04:42', '2019-02-27 19:27:40', '2019-02-27 19:27:40'),
(64, 'Loewe', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 05:05:44', '2019-02-27 19:27:48', '2019-02-27 19:27:48'),
(65, 'Danse Lente', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 06:01:21', '2019-02-27 19:28:21', '2019-02-27 19:28:21'),
(66, 'Michael By Michael Kors', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 06:02:17', '2019-02-27 19:28:27', '2019-02-27 19:28:27'),
(67, 'Manu Atelier', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 06:03:01', '2019-02-27 19:28:33', '2019-02-27 19:28:33'),
(68, 'Maison Margiela', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 06:04:11', '2019-02-27 19:28:41', '2019-02-27 19:28:41'),
(69, 'Furla', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 07:00:45', '2019-02-27 19:28:47', '2019-02-27 19:28:47'),
(70, 'Thom Browne', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 07:02:10', '2019-02-27 19:28:52', '2019-02-27 19:28:52'),
(71, 'Ruslan Baginskiy', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 07:03:42', '2019-02-27 19:28:57', '2019-02-27 19:28:57'),
(72, 'Comme Des Garcons', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 07:04:28', '2019-02-27 19:29:02', '2019-02-27 19:29:02'),
(73, 'Swarovski', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 08:04:26', '2019-02-27 19:29:07', '2019-02-27 19:29:07'),
(74, 'Saint Laurent', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 08:07:23', '2019-02-27 19:29:13', '2019-02-27 19:29:13'),
(75, 'Fornasetti Profumi', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 09:07:31', '2019-02-27 19:29:27', '2019-02-27 19:29:27'),
(76, 'Jw Anderson', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 10:06:13', '2019-02-27 19:29:33', '2019-02-27 19:29:33'),
(77, 'Adidas By Alexander Wang', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 10:06:37', '2019-02-27 19:29:39', '2019-02-27 19:29:39'),
(78, 'Parosh', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 11:02:16', '2019-02-27 19:05:50', '2019-02-27 19:05:50'),
(79, 'Nike', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 11:07:51', '2019-02-27 19:19:57', '2019-02-27 19:19:57'),
(80, 'Calvin Klein', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 12:07:56', '2019-02-27 19:20:03', '2019-02-27 19:20:03'),
(81, 'Calvin Klein Jeans Est. 1978', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 12:08:49', '2019-02-27 19:20:09', '2019-02-27 19:20:09'),
(82, 'Helmut Lang', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 12:09:14', '2019-02-27 19:20:17', '2019-02-27 19:20:17'),
(83, 'Moncler Genius', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 12:10:58', '2019-02-27 19:20:22', '2019-02-27 19:20:22'),
(84, 'Moncler', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 13:00:36', '2019-02-27 19:20:28', '2019-02-27 19:20:28'),
(85, 'Puma', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 14:02:29', '2019-02-27 19:20:34', '2019-02-27 19:20:34'),
(86, 'Isabel Marant', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 14:04:47', '2019-02-27 19:20:39', '2019-02-27 19:20:39'),
(87, 'Marcelo Burlon', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 15:02:55', '2019-02-27 19:20:44', '2019-02-27 19:20:44'),
(88, 'Ajmone Sartorial Leather', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 15:03:26', '2019-02-27 19:20:49', '2019-02-27 19:20:49'),
(89, 'Montblanc', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 15:04:37', '2019-02-27 19:20:56', '2019-02-27 19:20:56'),
(90, 'Fpm', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 15:06:45', '2019-02-27 19:21:02', '2019-02-27 19:21:02'),
(91, 'Maison Kitsune', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 16:02:32', '2019-02-27 19:21:09', '2019-02-27 19:21:09'),
(92, 'Lardini', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 17:04:09', '2019-02-27 19:21:15', '2019-02-27 19:21:15'),
(93, 'Mr&amp;mrs Italy', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 17:04:46', '2019-02-27 19:21:21', '2019-02-27 19:21:21'),
(94, 'Make Money Not Friends', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 18:01:53', '2019-02-27 19:21:26', '2019-02-27 19:21:26'),
(95, 'Versace Collection', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 18:04:06', '2019-02-27 19:21:34', '2019-02-27 19:21:34'),
(96, 'Family First', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 18:04:43', '2019-02-27 19:21:42', '2019-02-27 19:21:42'),
(97, 'Lc23', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 18:04:56', '2019-02-27 19:21:48', '2019-02-27 19:21:48'),
(98, 'Belstaff', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 18:05:59', '2019-02-27 19:25:46', '2019-02-27 19:25:46'),
(99, 'Polo Ralph Lauren', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 18:06:12', '2019-02-27 19:26:08', '2019-02-27 19:26:08'),
(100, 'Dondup', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 18:06:26', '2019-02-27 19:26:16', '2019-02-27 19:26:16'),
(101, 'Riccardo Comi', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 18:06:39', '2019-02-27 19:26:22', '2019-02-27 19:26:22'),
(102, 'Palm Angels', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 18:06:52', '2019-02-27 19:05:42', '2019-02-27 19:05:42'),
(103, 'Church\'s', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 18:07:05', '2019-02-27 19:26:28', '2019-02-27 19:26:28'),
(104, 'Santoni', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 18:07:32', '2019-02-27 19:05:57', '2019-02-27 19:05:57'),
(105, 'Adidas', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 19:00:59', '2019-02-27 19:06:03', '2019-02-27 19:06:03'),
(106, 'New Era Cap', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 19:02:00', '2019-02-27 19:06:10', '2019-02-27 19:06:10'),
(107, 'Isabel Marant Etoile', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 19:03:09', '2019-02-27 19:06:16', '2019-02-27 19:06:16'),
(108, 'Adaptation', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-04 21:02:07', '2019-02-27 19:06:22', '2019-02-27 19:06:22'),
(109, 'Sophia Webster', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-05 00:04:41', '2019-02-27 19:06:28', '2019-02-27 19:06:28'),
(110, 'As 65', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-05 05:03:47', '2019-02-27 19:06:34', '2019-02-27 19:06:34'),
(111, 'Boyy', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-05 11:04:58', '2019-02-27 19:06:40', '2019-02-27 19:06:40'),
(112, 'Heron Preston', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-05 11:05:55', '2019-02-27 19:06:47', '2019-02-27 19:06:47'),
(113, 'Simon Miller', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-05 15:01:44', '2019-02-27 19:06:53', '2019-02-27 19:06:53'),
(114, 'Z Zegna', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-05 15:05:19', '2019-02-27 19:06:58', '2019-02-27 19:06:58'),
(115, 'Paolo Pecora', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-05 15:05:32', '2019-02-27 19:07:04', '2019-02-27 19:07:04'),
(116, 'Rick Owens', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-10 21:02:25', '2019-02-27 19:07:10', '2019-02-27 19:07:10'),
(117, 'Max Mara', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-10 21:05:14', '2019-02-27 19:19:02', '2019-02-27 19:19:02'),
(118, 'Stuart Weitzman', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-10 23:02:38', '2019-02-27 19:19:08', '2019-02-27 19:19:08'),
(119, 'Mm6 Maison Margiela', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-10 23:03:20', '2019-02-27 19:19:14', '2019-02-27 19:19:14'),
(120, 'Giorgio Armani', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-10 23:05:33', '2019-02-27 19:19:21', '2019-02-27 19:19:21'),
(121, 'Aspinal Of London', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-11 00:03:38', '2019-02-27 19:19:28', '2019-02-27 19:19:28'),
(122, 'Mansur Gavriel', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-11 00:05:02', '2019-02-27 19:19:33', '2019-02-27 19:19:33'),
(123, '3.1 Phillip Lim', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-11 01:01:24', '2019-02-27 19:19:43', '2019-02-27 19:19:43'),
(124, 'Elisabetta Franchi', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-11 01:02:35', '2019-02-27 19:19:49', '2019-02-27 19:19:49'),
(125, 'Neil Barrett', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-11 01:02:51', '2019-02-27 19:05:31', '2019-02-27 19:05:31'),
(126, 'Etro', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-11 01:06:58', '2019-02-27 00:47:20', '2019-02-27 00:47:20'),
(127, 'Amiri', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-11 02:02:02', '2019-02-27 00:48:51', '2019-02-27 00:48:51'),
(129, 'Kate Spade', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-14 02:02:40', '2019-02-27 16:17:45', '2019-02-27 16:17:45'),
(130, 'Zoe Karssen', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-14 05:03:30', '2019-02-27 16:18:25', '2019-02-27 16:18:25'),
(131, 'Roberto Collina', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-14 07:07:32', '2019-02-27 16:18:34', '2019-02-27 16:18:34'),
(132, 'Tagliatore', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-14 12:05:54', '2019-02-27 16:18:42', '2019-02-27 16:18:42'),
(133, 'Alpha Industries', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-14 13:02:31', '2019-02-27 16:18:49', '2019-02-27 16:18:49'),
(134, 'Tod\'s', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-14 13:06:30', '2019-02-27 16:19:32', '2019-02-27 16:19:32'),
(135, 'Enfants Riches Deprimes', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-14 14:00:42', '2019-02-27 19:00:59', '2019-02-27 19:00:59'),
(136, 'See By Chloe\'', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-14 18:02:20', '2019-02-27 19:01:06', '2019-02-27 19:01:06'),
(137, 'Philosophy Di Lorenzo Serafini', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-14 20:02:44', '2019-02-27 19:01:12', '2019-02-27 19:01:12'),
(138, 'Champion Wood Wood', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-14 21:02:41', '2019-02-27 19:01:19', '2019-02-27 19:01:19'),
(139, 'Paris Texas', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-15 12:05:11', '2019-02-27 19:01:26', '2019-02-27 19:01:26'),
(140, 'Frame Denim', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-15 15:03:06', '2019-02-27 19:01:33', '2019-02-27 19:01:33'),
(141, 'Proenza Schouler', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-15 15:03:18', '2019-02-27 19:01:39', '2019-02-27 19:01:39'),
(142, 'Balmain', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-15 15:03:44', '2019-02-27 19:01:44', '2019-02-27 19:01:44'),
(143, 'Maunakea', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-15 15:07:12', '2019-02-27 19:01:50', '2019-02-27 19:01:50'),
(144, 'MARC JACOBS', 100, 20, 233, 'B', NULL, NULL, NULL, '2019-02-15 21:27:15', '2019-08-30 12:19:57', NULL),
(145, 'KENZO', 100, 20, 235, 'B', NULL, NULL, 'KENZO DONNA', '2019-02-15 21:27:51', '2019-02-15 21:27:51', '2019-05-05 05:00:00'),
(146, 'Mira Mikati', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-19 00:03:19', '2019-02-27 19:05:23', '2019-02-27 19:05:23'),
(147, 'Alanui', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-19 00:03:45', '2019-02-27 19:05:16', '2019-02-27 19:05:16'),
(148, 'Moncler Grenoble', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-19 00:37:55', '2019-02-27 19:05:09', '2019-02-27 19:05:09'),
(149, 'Mcq', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-19 15:02:52', '2019-02-27 19:02:00', '2019-02-27 19:02:00'),
(150, 'Thom Krom', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-19 15:03:03', '2019-02-27 00:44:19', '2019-02-27 00:44:19'),
(151, 'Sigerson Morrison', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-19 22:02:32', '2019-02-27 00:44:26', '2019-02-27 00:44:26'),
(152, 'A.p.c.', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-20 04:02:57', '2019-02-27 00:44:36', '2019-02-27 00:44:36'),
(153, 'Gcds', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-20 04:03:08', '2019-02-27 00:44:42', '2019-02-27 00:44:42'),
(154, 'Mackage', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-20 04:04:15', '2019-02-27 00:44:47', '2019-02-27 00:44:47'),
(155, 'Boglioli', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-21 01:01:57', '2019-02-27 00:45:09', '2019-02-27 00:45:09'),
(156, 'LOVE MOSCHINO', 0, 0, 0, 'B', NULL, NULL, 'MOSCHINO;MOSCHINO COUTURE', '2019-02-22 20:43:08', '2019-02-27 00:45:16', '2019-02-27 00:45:16'),
(157, 'DOLCE&amp;GABBANA', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-22 20:44:40', '2019-02-27 00:45:29', '2019-02-27 00:45:29'),
(158, 'EYTYS AB', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-22 23:02:16', '2019-02-27 00:45:38', '2019-02-27 00:45:38'),
(159, 'DSQUARED2', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-22 23:02:25', '2019-02-27 00:45:45', '2019-02-27 00:45:45'),
(160, 'MOSCHINO COUTURE', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-22 23:02:50', '2019-02-27 00:45:52', '2019-02-27 00:45:52'),
(161, 'CALVIN KLEIN JEANS', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-22 23:03:09', '2019-02-27 00:45:59', '2019-02-27 00:45:59'),
(162, 'ZANELLATO', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 01:03:47', '2019-02-27 00:46:07', '2019-02-27 00:46:07'),
(163, 'MCM', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 01:05:39', '2019-02-27 00:46:14', '2019-02-27 00:46:14'),
(164, 'GIUSEPPE ZANOTTI', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 02:03:35', '2019-02-27 00:46:21', '2019-02-27 00:46:21'),
(165, 'ZADIG &amp; VOLTAIRE', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 03:04:21', '2019-02-27 00:46:27', '2019-02-27 00:46:27'),
(166, 'AMI PARIS', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 04:04:01', '2019-02-27 00:46:33', '2019-02-27 00:46:33'),
(167, 'DIESEL', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 04:05:05', '2019-02-27 00:46:38', '2019-02-27 00:46:38'),
(168, 'L\'AUTRE CHOSE', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 05:04:17', '2019-02-27 00:46:44', '2019-02-27 00:46:44'),
(169, 'PACO RABANNE', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 07:05:26', '2019-02-27 00:46:50', '2019-02-27 00:46:50'),
(170, 'N21', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 10:01:46', '2019-02-27 00:46:56', '2019-02-27 00:46:56'),
(171, 'MSGM', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 12:08:36', '2019-02-27 00:47:02', '2019-02-27 00:47:02'),
(172, 'AMEN', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 12:08:42', '2019-02-27 00:47:08', '2019-02-27 00:47:08'),
(173, 'VISION OF SUPER', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 12:10:03', '2019-02-27 00:47:14', '2019-02-27 00:47:14'),
(174, 'CAR SHOE', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 15:04:01', '2019-02-26 19:22:45', '2019-02-26 19:22:45'),
(175, '2 MONCLER 1952', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 15:04:52', '2019-02-27 00:35:11', '2019-02-27 00:35:11'),
(176, 'SALONI', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 16:02:24', '2019-02-27 00:35:22', '2019-02-27 00:35:22'),
(177, 'MARNI', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 18:08:07', '2019-02-27 00:36:33', '2019-02-27 00:36:33'),
(178, 'MAX MARA STUDIO', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 18:08:14', '2019-02-27 00:36:45', '2019-02-27 00:36:45'),
(179, 'WANDLER', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 19:00:59', '2019-02-27 00:36:54', '2019-02-27 00:36:54'),
(180, 'SPORTMAX', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 20:03:44', '2019-02-27 00:38:05', '2019-02-27 00:38:05'),
(181, 'TOMMY HILFIGER', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 21:02:26', '2019-02-27 00:38:15', '2019-02-27 00:38:15'),
(182, 'BLACKFIN', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 21:04:39', '2019-02-27 00:38:24', '2019-02-27 00:38:24'),
(183, '5 MONCLER CRAIG GREEN', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 22:03:18', '2019-02-27 00:38:32', '2019-02-27 00:38:32'),
(184, 'NANA NANA', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-23 23:02:22', '2019-02-27 00:38:40', '2019-02-27 00:38:40'),
(185, 'MAX MARA PIANOFORTE', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-24 01:05:33', '2019-02-27 00:38:50', '2019-02-27 00:38:50'),
(186, 'CHIARA FERRAGNI', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-24 02:01:34', '2019-02-27 00:39:00', '2019-02-27 00:39:00'),
(187, 'SPRAYGROUND', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-24 08:02:04', '2019-02-27 00:39:44', '2019-02-27 00:39:44'),
(188, 'EASTPAK X MAISON KITSUNE\'', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-24 19:01:59', '2019-02-27 00:40:01', '2019-02-27 00:40:01'),
(189, 'TOMMY HILFIGER JEANS', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-24 19:02:42', '2019-02-27 00:41:54', '2019-02-27 00:41:54'),
(190, '7 MONCLER FRAGMENT 2', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-24 22:00:54', '2019-02-27 00:42:00', '2019-02-27 00:42:00'),
(191, 'KENZO CHRISTMAS', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-24 22:01:11', '2019-02-27 00:42:06', '2019-02-27 00:42:06'),
(192, '3 MONCLER GRENOBLE ', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-24 23:01:13', '2019-02-27 00:42:12', '2019-02-27 00:42:12'),
(193, '7 MONCLER FRAGMENT', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-25 00:02:16', '2019-02-27 00:42:18', '2019-02-27 00:42:18'),
(194, 'JESSIE WESTERN', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-25 01:03:35', '2019-02-27 00:42:24', '2019-02-27 00:42:24'),
(195, 'CALVIN KLEIN 205W39NYC', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-25 05:01:40', '2019-02-27 00:42:30', '2019-02-27 00:42:30'),
(196, 'APM MONACO', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-25 14:03:58', '2019-02-27 00:42:36', '2019-02-27 00:42:36'),
(197, '6 MONCLER NOIR KEI NINOMIYA', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-25 14:04:39', '2019-02-27 00:42:41', '2019-02-27 00:42:41'),
(198, '4 MONCLER SIMONE ROCHA', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-25 15:03:27', '2019-02-26 19:22:37', '2019-02-26 19:22:37'),
(199, 'STAUD', 0, 0, 0, '', NULL, NULL, NULL, '2019-02-25 20:03:36', '2019-02-26 19:22:27', '2019-02-26 19:22:27'),
(200, 'LOVE MOSCHINO', 100, 20, 239, 'B', NULL, NULL, 'MOSCHINO;MOSCHINO COUTURE', '2019-05-29 00:26:23', '2019-05-29 00:26:23', NULL),
(201, 'MOSCHINO COUTURE', 100, 20, 240, 'B', NULL, NULL, NULL, '2019-05-29 01:09:39', '2019-08-30 12:20:24', NULL),
(202, 'COACH', 100, 15, 710, 'C', NULL, NULL, NULL, '2019-06-26 19:00:30', '2019-06-27 18:46:33', NULL),
(203, 'Cartier', 100, 20, 721, 'B', NULL, NULL, NULL, '2019-07-19 16:26:38', '2019-08-30 12:20:47', NULL),
(204, 'MONTBLANC', 100, 20, 722, 'B', NULL, NULL, 'MOUNT BLANC;MONT BLANC;MOUNTBLANC', '2019-07-19 16:27:28', '2019-08-30 12:21:20', NULL),
(205, 'Hublot', 100, 20, 723, 'B', NULL, NULL, NULL, '2019-07-19 16:28:13', '2019-08-30 12:21:42', NULL),
(206, 'Dita', 0, 0, 0, 'B', NULL, NULL, NULL, '2019-07-20 15:37:42', '2019-08-30 12:22:08', NULL),
(207, 'SoloLuxury', 0, 0, 0, '', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `brand_category_price_range`
--

CREATE TABLE `brand_category_price_range` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_segment` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_price` int(11) NOT NULL,
  `max_price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brand_fans`
--

CREATE TABLE `brand_fans` (
  `id` int(11) NOT NULL,
  `brand_name` varchar(191) NOT NULL,
  `brand_url` varchar(196) NOT NULL,
  `username` varchar(191) NOT NULL,
  `profile_url` varchar(400) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `brand_reviews`
--

CREATE TABLE `brand_reviews` (
  `id` int(11) NOT NULL,
  `website` varchar(191) NOT NULL,
  `brand` varchar(191) NOT NULL,
  `review_url` varchar(400) NOT NULL,
  `username` varchar(191) NOT NULL,
  `title` varchar(200) NOT NULL,
  `body` mediumtext NOT NULL,
  `stars` int(11) NOT NULL,
  `used` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `brand_tagged_posts`
--

CREATE TABLE `brand_tagged_posts` (
  `id` int(11) NOT NULL,
  `brand_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_url` mediumtext NOT NULL,
  `username` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `image_url` mediumtext NOT NULL,
  `posted_on` text NOT NULL,
  `no_likes` int(11) NOT NULL,
  `no_comments` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `broadcast_images`
--

CREATE TABLE `broadcast_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `products` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sending_time` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` int(10) UNSIGNED NOT NULL,
  `budget_category_id` int(10) UNSIGNED NOT NULL,
  `budget_subcategory_id` int(10) UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budget_categories`
--

CREATE TABLE `budget_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulk_customer_replies_keywords`
--

CREATE TABLE `bulk_customer_replies_keywords` (
  `id` int(10) UNSIGNED NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `text_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_manual` tinyint(1) NOT NULL DEFAULT 0,
  `count` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_processed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulk_customer_replies_keyword_customer`
--

CREATE TABLE `bulk_customer_replies_keyword_customer` (
  `keyword_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `call_busy_messages`
--

CREATE TABLE `call_busy_messages` (
  `id` int(11) NOT NULL,
  `lead_id` int(11) DEFAULT 0,
  `twilio_call_sid` varchar(255) DEFAULT NULL,
  `caller_sid` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `recording_url` varchar(200) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `call_histories`
--

CREATE TABLE `call_histories` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `call_recordings`
--

CREATE TABLE `call_recordings` (
  `id` int(10) UNSIGNED NOT NULL,
  `callsid` varchar(255) DEFAULT NULL,
  `twilio_call_sid` varchar(255) DEFAULT NULL,
  `recording_url` varchar(255) DEFAULT NULL,
  `lead_id` int(10) UNSIGNED DEFAULT NULL,
  `order_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_number` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

CREATE TABLE `cases` (
  `id` int(10) UNSIGNED NOT NULL,
  `lawyer_id` int(10) UNSIGNED DEFAULT NULL,
  `case_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `for_against` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `court_detail` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resource` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `last_date` date DEFAULT NULL,
  `next_date` date DEFAULT NULL,
  `cost_per_hearing` double(8,2) DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `case_costs`
--

CREATE TABLE `case_costs` (
  `id` int(10) UNSIGNED NOT NULL,
  `case_id` int(10) UNSIGNED DEFAULT NULL,
  `billed_date` date DEFAULT NULL,
  `amount` decimal(13,4) DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `amount_paid` decimal(13,4) DEFAULT NULL,
  `other` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `case_receivables`
--

CREATE TABLE `case_receivables` (
  `id` int(10) UNSIGNED NOT NULL,
  `case_id` int(10) UNSIGNED NOT NULL,
  `currency` int(11) NOT NULL DEFAULT 0,
  `receivable_date` date DEFAULT NULL,
  `received_date` date DEFAULT NULL,
  `receivable_amount` decimal(13,4) DEFAULT NULL,
  `received_amount` decimal(13,4) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cash_flows`
--

CREATE TABLE `cash_flows` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `cash_flow_category_id` int(10) UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `amount` int(11) NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expected` decimal(13,4) DEFAULT NULL,
  `actual` decimal(13,4) DEFAULT NULL,
  `cash_flow_able_id` int(11) DEFAULT NULL,
  `cash_flow_able_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `order_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `currency` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `magento_id` int(10) UNSIGNED NOT NULL,
  `show_all_id` int(10) UNSIGNED DEFAULT NULL,
  `dimension_range` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size_range` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `references` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `title`, `magento_id`, `show_all_id`, `dimension_range`, `size_range`, `created_at`, `updated_at`, `references`) VALUES
(1, 0, 'Select Category', 198, NULL, '', '', '2018-08-14 05:42:41', '2018-10-11 21:05:02', NULL),
(2, 0, 'Women', 4, NULL, '', '', '2018-08-14 05:43:17', '2019-06-15 05:10:20', 'woman,donna,female'),
(3, 0, 'Men', 5, NULL, '', '', '2018-08-14 05:43:18', '2019-06-15 05:10:20', 'man,uomo,male'),
(5, 3, 'Shoes', 37, 76, '', '', '2018-08-14 05:59:49', '2018-08-31 15:13:05', NULL),
(6, 5, 'Sandals', 80, NULL, '', '', '2018-08-14 05:59:58', '2019-07-08 00:12:37', 'Sandali,Sandals,Slides & Flip-flops,SANDALI FLATS,Sling-back,Mary-Jane,Sandal'),
(7, 5, 'Boots', 77, NULL, '', '', '2018-08-14 06:01:56', '2019-06-18 16:58:15', 'Stivali,Mid-heel,High-heel,Chelsea Boots,ANFIBI,TRONCHETTI,Polacchine,Snow Boots,Snow Boots'),
(9, 5, 'Loafers', 79, NULL, '', '', '2018-08-14 08:18:40', '2019-06-15 03:06:03', 'Mocassini,Moccasins,Flats,Loafers'),
(10, 3, 'Accessories', 35, 43, '', '', '2018-08-14 20:55:50', '2018-08-31 15:16:02', NULL),
(11, 3, 'Bags', 36, 56, '', '', '2018-08-14 20:56:19', '2018-08-31 15:16:50', NULL),
(12, 3, 'Clothing', 38, 63, '', '', '2018-08-14 20:56:35', '2018-08-31 15:17:28', NULL),
(13, 12, 'Shirts', 70, NULL, '', '', '2018-08-14 20:56:53', '2019-06-15 03:05:54', 'Camicie,Casual,Classiche,Shirts,Shirt,Plain,Print,Striped,Short Sleeves,Long Sleeves,Blouses,Casual,Classics,Short Sleeves,Long Sleeves,Camicia'),
(14, 10, 'Belts', 45, NULL, '', '', '2018-08-14 20:57:24', '2019-06-15 03:05:33', 'Cinture,Belts,Belt,Belts and Braces,Belts e Braces,Costumi da bagno'),
(17, 10, 'Scarves & Wraps', 50, NULL, '', '', '2018-08-14 20:57:45', '2019-06-18 16:58:15', 'Sciarpe e Foulard,Scarves,Scarves and silk,Scarf,SCARVES HATS AND GLOVES,SCIARPE,Sciarpe,cappelli e guanti,SCARF,Sciarpe'),
(19, 10, 'Tie & Bow Ties', 53, NULL, '', '', '2018-08-14 20:58:03', '2019-06-15 03:05:35', 'Cravatte e Papillons,Ties,Ties & Bow Ties,TIES,Tie,TIES AND BOW TIES,Cravatte,Cravatte e papillon,cravatte,papillon,bowties-e-ties,Tie Clips,Papillon e cravatte'),
(20, 10, 'Wallets & Cardholders', 55, NULL, '', '', '2018-08-14 20:58:09', '2019-06-18 16:58:15', 'Portafogli,Wallets,Other Accessories,Small Leather Goods,Card holder,Keycase,Wallet,piccola pelletteria,Minuteria/Slg,Portafogli e porta carte,Portacarte,Wallets & Billfolds,Portafogli & portamonete,Wallets & Billfolds,Portafogli & portamonete,Wallets & Billfolds,Portafogli & portamonete'),
(21, 11, 'Backpacks', 57, NULL, '', '', '2018-08-14 20:58:35', '2019-07-07 22:58:19', 'Zaini,Backpacks,Backpacks and bumbags,Backpack,Zainetti,Zainetti,Zainetti'),
(22, 11, 'Briefcases', 58, NULL, '', '', '2018-08-14 20:58:40', '2019-06-15 03:05:45', 'Borse da lavoro,Briefcases,Suitcases,Ventiquattrore'),
(23, 11, 'Clutches', 59, NULL, '', '', '2018-08-14 20:58:49', '2019-07-07 21:45:26', 'Clutches,clutch & pochette,POCHETTE,Borse da lavoro,Clutch'),
(24, 11, 'Shoulder Bags', 61, NULL, '', '', '2018-08-14 20:58:59', '2019-07-10 17:42:57', 'Tote Bags,Shoppers and Totes,Shoulder Bags,Borse,BORSE A SPALLA,Tote,Tracolle,Borse a tracolla,SHOULDER BAG,Shoulder Strap'),
(25, 11, 'Travel Bags', 62, NULL, '', '', '2018-08-14 20:59:35', '2019-06-18 16:58:15', 'Borse da viaggio,Travel,Travel Bags,Suit Bag,Trolley,Business e travels,Valigie e borsoni'),
(26, 11, 'Messenger Bags', 155, NULL, '', '', '2018-08-14 20:59:47', '2019-06-15 03:05:48', 'Messenger and Crossbody Bags,Messenger Bags'),
(28, 11, 'Others', 164, NULL, '', '', '2018-08-14 21:00:09', '2019-07-07 22:47:50', 'Jersey,SOCKS,Ski Bottoms,Ski Bottoms,Pantyhose & Stockings,Bag Accessories'),
(30, 12, 'Knitwear / Sweater', 68, NULL, '', '', '2018-08-14 21:02:52', '2019-06-18 16:58:15', 'Maglieria,Cardigan,Collo Alto,Maglie,Sweaters and Cardigans,Knitwear,Sweater,Sweaters,Turtlenecks,Cardigans,Knitwears,Sweater,V-neck sweaters,Turtleneck sweaters,Shrug,Knitted Sweaters,Loungewear,Knitted Sweaters,Loungewear,Knitted Sweaters,Loungewear'),
(31, 12, 'T-Shirts', 73, NULL, '', '', '2018-08-14 21:03:03', '2019-06-18 16:58:15', 'Polo,T-Shirt,T-Shirts and Polos,Polo Shrits,T-Shirts,T-Shirts & Polos,Polos,Tshirt,Vests & tanks,Polo & T-shirt,Topwear,Giubbini,T-Shirts & Jersey Shirts,T-shirts & Jersey,T-Shirts & Jersey Shirts,T-shirts & Jersey,Polo Shirts,T-Shirts & Jersey Shirts,T-shirts & Jersey,Polo Shirts,Polo Shirts,T-Shirts & Vests'),
(32, 12, 'Jumper', 187, NULL, '', '', '2018-08-14 21:03:11', '2019-06-15 03:05:57', ''),
(33, 12, 'Sweatshirt & Hoodies', 190, NULL, '', '', '2018-08-14 21:03:18', '2019-07-19 00:29:01', 'Felpe,Sweatshirts,Sweatshirt,Hoodies,Round neck,Crew-Neck,Hoodie,Zip,Turtleneck sweaters,Crewneck sweaters,Giubbotto,Felpa,sweatshirt & Hoodies,Maglioni,Maglioni,Maglioni'),
(34, 5, 'Brogues & Derbies', 78, NULL, '', '', '2018-08-14 21:03:45', '2019-06-15 03:06:04', 'Lace Ups,Formal Shoes,Lace-Up Shoes,Derby,Oxford,Monk,oxfords,SCOZZESE,Scarpe stringate,Derby Shoes,Oxford Shoes,Derby Shoes,Oxford Shoes,Derby Shoes,Oxford Shoes,Brogues & Oxfords'),
(36, 5, 'Slip-Ons', 81, NULL, '', '', '2018-08-14 21:04:04', '2019-06-15 03:06:05', 'Stringate'),
(37, 5, 'Sneakers', 82, NULL, '', '', '2018-08-14 21:04:15', '2019-07-13 20:04:35', 'Sneakers,Slip-on,TRAINERS,Sneaker,Low-Tops,Hi-Tops,Scarpe con lacci,Low-Tops,Hi-Tops,Scarpe con lacci,Low-Tops,Hi-Tops,Scarpe con lacci'),
(38, 2, 'Accessories', 39, 83, '', '', '2018-08-14 21:06:52', '2018-08-30 23:37:41', NULL),
(39, 2, 'Bags', 40, 97, '', '', '2018-08-14 21:06:59', '2018-08-31 16:37:17', NULL),
(40, 2, 'Clothings', 41, 108, '', '', '2018-08-14 21:07:10', '2018-08-31 15:19:23', NULL),
(41, 2, 'Shoes', 42, 120, '', '', '2018-08-14 21:07:22', '2018-08-31 15:20:02', NULL),
(42, 38, 'Belts', 84, NULL, '', '', '2018-08-14 21:08:18', '2018-08-30 05:46:15', NULL),
(43, 38, 'Wallets & Cardholders', 86, NULL, '', '', '2018-08-14 21:08:36', '2019-07-08 00:14:35', 'Piccola Pelletteria,Portafogli,Wallets,Card Holders,Small Leather Goods,Wallets,Small Goods,Small Leather Goods,COVERS,Wallets & Purses,Wallet,Continental,Zip,Billfold,Smallleathergoods,WALLETS & CARDHOLDERS,POCHETTE,Portafogli & Protamonete,portafogli,Minuteria/Slg,poratacarte,Wallets & Billfolds,Portafogli & portamonete,Wallets & Billfolds,Portafogli & portamonete,Wallets & Billfolds,Portafogli & portamonete'),
(44, 38, 'Cosmetic Pouches', 87, NULL, '', '', '2018-08-14 21:08:50', '2019-06-18 16:58:15', 'Pouches,Make Up Bags,Trousse,Make Up Bag'),
(45, 38, 'Hair Accessories', 89, NULL, '', '', '2018-08-14 21:09:01', '2019-06-15 05:10:20', 'Hats,Hat,HAIR ACCESSORIES,Cappelli,hats-e-hairbands'),
(46, 38, 'Key Rings & Chains', 91, NULL, '', '', '2018-08-14 21:09:11', '2018-08-30 05:48:25', NULL),
(47, 38, 'Sunglasses & Frames', 93, NULL, '', '', '2018-08-14 21:09:20', '2019-06-15 03:05:37', 'Occhiali Da Sole,Sunglasess,Glasses,Occhiali,Sunglasses,Dior Eyewear,Jimmy Choo Eyewear,Glasses & Frames,Fendi Eyewear,Sunglasses,Dior Eyewear,Jimmy Choo Eyewear,Glasses & Frames,Fendi Eyewear,Hublot Eyewear'),
(48, 38, 'Tech Accessories & Cases', 94, NULL, '', '', '2018-08-14 21:09:31', '2019-06-15 03:04:52', 'Phone Cases,Tech Accessories,TECH,Cover,Protachivai,COVER IPHONE/IPAD,COVERS,Cover iPhone,Cover'),
(49, 38, 'Shawls And Scarves', 156, NULL, '', '', '2018-08-14 21:09:39', '2019-07-09 18:11:42', 'Sciarpe e Foulard,Scarves,Scarves and Silk,Shawls,Scarves and Foulards,Scarf,Shawl,FOULARD,Sciarpe,Sciarpe. capelli e guanti,Scialle,Scialli e foulard,Scarves & Foulard'),
(50, 38, 'Make-Up Bags', 196, NULL, '', '', '2018-08-14 21:09:49', '2019-06-15 03:04:54', 'BEAUTY CASES'),
(51, 38, 'Jewelry', 197, NULL, '', '', '2018-08-14 21:09:58', '2019-07-20 18:58:18', 'Gioielli E Orologi,Key Rings,Bracelets,Cufflinks,Earrings,Necklaces,Rings,jewelry & Watches,Keychains,Bracelet,jewelry,Orologi,Gemelli da camicia,Portachiavi,Gioielli,Portachiavi,Bijoux,Orecchini,Orecchini,Brooches & Pins,Orecchini,Brooches & Pins'),
(52, 39, 'Backpacks', 98, NULL, '', '', '2018-08-14 21:10:26', '2018-08-30 05:51:28', NULL),
(53, 39, 'Clutches & Slings', 100, NULL, '', '', '2018-08-14 21:10:38', '2019-07-03 23:26:38', NULL),
(54, 39, 'Crossbody Bags', 101, NULL, '', '', '2018-08-14 21:10:49', '2019-07-09 19:55:10', 'Shoulder and Crossbody Bags,Crossbody Bags,Satchel & Cross Body,CROSSBODY BAGS,Mini Borse,Borse Cross body,Mini Bags,Mini Bags'),
(55, 39, 'Handbags', 719, 97, '', '', '2018-08-14 21:10:56', '2019-07-19 22:02:03', 'Borse a mano,Borse a tracolla,Handbags,Hobo Bag,Top Handle,Rolled Bag,Bucket Bags,Briefcase,Handbag'),
(56, 39, 'Laptop Bag', 103, 97, '', '', '2018-08-14 21:11:06', '2019-06-15 03:05:04', 'BUSINESS BAGS,Laptop Bags & Briefcases,Laptop Bags & Briefcases,Laptop Bags & Briefcases'),
(57, 39, 'Shoulder Bags', 104, NULL, '', '', '2018-08-14 21:11:15', '2018-08-30 00:21:01', NULL),
(58, 39, 'Tote Bags', 105, NULL, '', '', '2018-08-14 21:11:25', '2018-08-30 05:53:42', NULL),
(59, 39, 'Others', 107, NULL, '', '', '2018-08-14 21:11:37', '2018-08-30 05:55:54', NULL),
(60, 39, 'Wallet', 152, NULL, '', '', '2018-08-14 21:11:46', '2018-08-30 05:56:34', NULL),
(61, 40, 'Coats & Jackets', 109, NULL, '', '', '2018-08-14 21:12:09', '2019-07-19 00:39:36', 'Cappotti,Impermeabili e trench,Lunghi,Piumini Lunghi,Giacche,Blazer,Giacche Casual,Giacche In Pelle,Gilet,Giubbotti,Piumini,Capes,Coats,Denim Jackets,Down Jackets,Formal Jackets,Leather Jackets,Sports Jackets,Trench Coats,Blazers,Fur & Shearling,Oversized,Jacket,Sport jacket,Jeans,Bootcut,Cropped,Flared,Wide leg,Long,Capes,Trench Coats,Caban,Cape,Coat,Jeans,Sport jacket,Trenchcoat,Vest,FUR COATS AND SHEARLING COATS,GIUBBOTTO,PARKAS AND BUSH JACKETS,TRENCH COATS AND OVERCOATS,VESTS AND WAISTCOATS,Blazers and vests,Fur coats,cappoti militari,cappoti oversied,doppio-Petrol & Soprabito,Mantelle,Parka,trench & impermeahili,Blazers,bombers,guacamole biker,Gosche tweed,giacche Anderenti,giaccche crop,giacche di pelle,giacche militari,giacche oversized,gilet,Piumini,Giubbotto,Giacca,Capospalla,CAPISPALLA,Giacche e giubbotti,fur coats,Gilet,Trench,Lightweight Jackets,Waistcoats & Gilets,Leather Coats,Trench & impermeabili,Bomber Jackets,Cappotti in pelliccia & Montone,Giacca biker,Lightweight Jackets,Waistcoats & Gilets,Leather Coats,Trench & impermeabili,Bomber Jackets,Cappotti in pelliccia & Montone,Giacca biker,Double Breasted & Peacoats,Trench Coats & Raincoats,Single Breasted Coats,Faux Fur & Shearling Coats,Faux Fur & Shearling Jackets,Oversized Coats,Varsity Jackets,Cappotto monopetto,Cappotti oversized,Giacche in denim,Single-Breasted Coats,Hooded Jackets,Sport Jackets & Wind Breakers,Lightweight Jackets,Waistcoats & Gilets,Leather Coats,Trench & impermeabili,Bomber Jackets,Cappotti in pelliccia & Montone,Giacca biker,Double Breasted & Peacoats,Trench Coats & Raincoats,Single Breasted Coats,Faux Fur & Shearling Coats,Faux Fur & Shearling Jackets,Oversized Coats,Varsity Jackets,Cappotto monopetto,Cappotti oversized,Giacche in denim,Single-Breasted Coats,Hooded Jackets,Sport Jackets & Wind Breakers,Down Coats,Trench Coats & Macs,Cropped Jackets,Cardi-Coats,Dinner Suits,Tweed Jackets,Oversized Jackets'),
(62, 40, 'Dresses', 111, NULL, '', '', '2018-08-14 21:12:17', '2019-10-23 06:29:50', 'Abiti,Abiti Maxi,Abiti Midi,Abiti Mini,Tuniche,Tute,Dresses,Costume,One-pieces,Dress,Cocktail & party,Day evening,Short,Long,Sheaths,Flared,Gowns,LONG DRESSES,MIDI DRESSES,SHORT DRESSES,Jumpsuits,Long dresses,Petticoat,Abiti e vestiti,Abito stampato,Vestiti da giorno,Cocktail & Party Dresses,Vestiti da giorno,Cocktail & Party Dresses,Day Dresses,Jumpuits,Vestiti da giorno,Cocktail & Party Dresses,Day Dresses,Jumpuits,Evening Dresses'),
(63, 40, 'T-Shirts', 118, NULL, '', '', '2018-08-14 21:12:24', '2018-08-30 06:08:09', NULL),
(65, 41, 'Boots', 121, NULL, '', '', '2018-08-14 21:12:51', '2018-08-30 06:09:19', NULL),
(66, 41, 'Brogues & Derbies', 122, NULL, '', '', '2018-08-14 21:12:58', '2018-08-30 06:09:41', NULL),
(67, 41, 'Flats', 123, NULL, '', '', '2018-08-14 21:13:05', '2019-06-15 03:05:21', 'Scarpe flat,Flats,FLATS,Sandali flats'),
(68, 41, 'Slides & Flip Flops', 124, NULL, '', '', '2018-08-14 21:13:11', '2019-07-02 22:27:54', NULL),
(69, 41, 'Heels', 125, NULL, '', '', '2018-08-14 21:13:22', '2019-06-18 16:58:15', 'Heels,wedges,High-heeled shoes,Tacchi'),
(70, 41, 'Loafers', 126, NULL, '', '', '2018-08-14 21:13:33', '2018-08-30 06:11:10', NULL),
(71, 41, 'Sandals', 127, NULL, '', '', '2018-08-14 21:14:09', '2018-08-30 06:12:01', NULL),
(72, 41, 'Slip-Ons', 128, NULL, '', '', '2018-08-14 21:15:44', '2018-08-30 06:12:14', NULL),
(73, 41, 'Sneakers', 129, NULL, '', '', '2018-08-14 21:15:53', '2018-08-30 06:12:28', NULL),
(74, 41, 'Pumps', 150, NULL, '', '', '2018-08-14 21:16:08', '2019-06-18 16:58:15', 'Pump,Pumps,Ballet Pumps,Mid-heel,High-heel,PUMPS AND SLINGBACKS,DECOLLETE,BRIDAL,PUMPS,scarpe con tacco,Décolleté e pump,Scarpe con tacco'),
(75, 41, 'Others', 153, NULL, '', '', '2018-08-14 21:16:17', '2018-08-30 00:07:47', NULL),
(76, 41, 'Slippers', 154, NULL, '', '', '2018-08-14 21:16:33', '2019-06-15 03:06:07', 'Slipprs'),
(77, 10, 'Sunglasses & Frames', 51, NULL, '', '', '2018-08-29 22:14:51', '2018-08-30 04:34:59', 'Occhiali Da Sole,Sunglasess,Glasses,Occhiali,Sunglasses,Dior Eyewear,Jimmy Choo Eyewear,Glasses & Frames,Fendi Eyewear,Sunglasses,Dior Eyewear,Jimmy Choo Eyewear,Glasses & Frames,Fendi Eyewear,Hublot Eyewear'),
(78, 11, 'Crossbody Bags', 198, NULL, '', '', '2018-08-29 22:15:37', '2019-07-07 23:27:30', 'Across Body Satchels,Cross-Body,Mini Bags,Mini Bags'),
(79, 11, 'Belt Bag', 200, NULL, '', '', '2018-08-29 22:16:12', '2019-06-15 03:05:51', 'Marsupi,Bum Bags,Belt Bags,Bumbags,Marsupio'),
(80, 5, 'Slippers', 199, NULL, '', '', '2018-08-29 22:17:05', '2018-08-30 15:36:02', NULL),
(81, 39, 'Messenger Bag', 201, 97, '', '', '2018-08-29 22:17:39', '2018-10-09 17:02:25', NULL),
(83, 41, 'Ballerina', 203, NULL, '', '', '2018-08-29 22:18:10', '2019-06-18 16:58:15', 'Ballerinas,Ballerine,DANCERS,Ballerine e slippers,Scarpe basse,Ballerina Shoes'),
(84, 41, 'Slides', 204, NULL, '', '', '2018-08-29 22:18:38', '2018-08-30 16:32:56', NULL),
(85, 39, 'Bucket Bags', 202, NULL, '', '', '2018-08-29 22:21:42', '2019-06-15 03:05:07', 'Bucket Bags,Borse A Secchiello'),
(86, 10, 'Beanies & Caps', 44, NULL, '', '', '2018-08-30 04:32:54', '2019-06-15 03:05:38', 'Cappelli,Hats,Hat,Beanies,Cappelli e berretti'),
(87, 11, 'Handbags', 60, NULL, '', '', '2018-08-30 14:45:24', '2018-08-30 14:45:24', NULL),
(89, 38, 'Cufflinks', 85, NULL, '', '', '2018-08-30 16:37:44', '2018-08-30 16:37:44', NULL),
(92, 39, 'Travel', 106, NULL, '', '', '2018-08-30 16:43:39', '2019-06-15 03:05:07', 'TRAVEL & SPORT BAGS,TRAVEL BAGS,Luggage,LUGGAGE TROLLEYS,WEEKEND BAGS'),
(93, 10, 'Gloves', 48, NULL, '', '', '2018-08-30 16:45:54', '2019-06-15 03:05:39', 'Gloves'),
(94, 38, 'Gloves', 88, NULL, '', '', '2018-08-30 16:48:38', '2018-08-30 16:48:38', NULL),
(95, 38, 'Key Pouches', 90, NULL, '', '', '2018-08-30 16:49:11', '2019-06-15 03:04:58', 'Key-holders,Keycase'),
(97, 39, 'Belt Bag', 168, 97, '', '', '2018-09-12 17:47:25', '2018-09-12 17:47:25', NULL),
(98, 39, 'Duffle Bags', 161, 97, '', '', '2018-09-12 17:57:16', '2019-06-15 03:05:54', 'Briefcases and Totes,Duffle,Shoppers,Duffle bags,Borse da viaggio,Holdalls,Holdalls,Holdalls & weekend Bags,Holdalls,Holdalls & weekend Bags'),
(99, 41, 'Mules', 205, 120, '', '', '2018-09-12 18:00:54', '2019-06-15 03:05:30', 'Mules,Mules and flip-flop'),
(100, 41, 'Espadrilles', 206, 120, '', '', '2018-09-12 18:02:05', '2019-06-15 03:06:08', 'Espadrilles'),
(101, 38, 'Document Holder', 207, 83, '', '', '2018-09-12 19:23:59', '2019-06-15 03:05:39', 'Piccola Pelletteria,Pouches,Pouch,Purse,Pochette da taschino'),
(102, 38, 'Coin Case / Purse', 208, 83, '', '', '2018-09-12 19:25:54', '2019-06-18 16:58:15', 'Pouches,Money Clips'),
(103, 10, 'Document Holder', 209, 43, '', '', '2018-09-28 17:06:50', '2018-09-28 17:06:50', NULL),
(105, 11, 'Laptop Bags', 210, 56, '', '', '2018-10-02 17:44:30', '2019-06-15 03:05:53', ''),
(106, 10, 'Wristlets', 212, 43, '', '', '2018-10-06 16:37:43', '2018-10-06 16:38:50', NULL),
(107, 41, 'Wedges', 131, 120, '', '', '2018-10-11 21:16:43', '2019-06-27 21:06:34', 'Zeppe,Scarpe con zeppa'),
(108, 10, 'Others', 217, 43, '', '', '2018-10-17 23:22:51', '2018-10-17 23:22:51', NULL),
(109, 5, 'Espadrilles', 219, 76, '', '', '2018-10-30 21:58:23', '2018-10-30 22:27:48', NULL),
(112, 5, 'Others', 218, NULL, '', '', '2018-10-30 22:12:33', '2018-10-30 22:12:33', NULL),
(113, 12, 'Others', 216, 63, '', '', '2018-10-30 22:19:57', '2018-10-30 22:28:24', NULL),
(114, 41, 'Booties', 222, NULL, '', '', '2018-10-30 22:23:20', '2019-06-15 03:05:32', 'Booties'),
(115, 40, 'Others', 215, 108, '', '', '2018-10-30 22:24:25', '2018-10-30 22:25:37', NULL),
(116, 38, 'Others', 214, 83, '', '', '2018-10-30 22:24:45', '2018-10-30 22:26:04', NULL),
(117, 11, 'Duffle Bags', 226, 56, '', '', '2018-11-16 17:44:26', '2018-11-16 17:44:26', NULL),
(119, 40, 'Tops', 117, 108, '', '', '2018-11-16 19:59:26', '2019-07-19 00:30:26', 'Tuniche,Tute,Costume,One-pieces,Cocktail & party,Day evening,Long,Sheaths,Flared,Gowns,LONG DRESSES,MIDI DRESSES,SHORT DRESSES,Jumpsuits,Vestiti Cocktail & Party,Vestiti  Da Giorno,Vestiti Da Mare,Vestiti De Sera,Camicia,top,Bluse,CAMICIE,Tank,Knitted Tops,Knitted Tops,Tunic Tops & Kaftans,Vests & Tank Tops'),
(122, 40, 'Sweatshirt & Hoodies', 195, 108, '', '', '2018-11-16 20:03:24', '2019-07-07 23:15:51', 'Felpe,Maglieria,Cardigan,Maglie,Sweatshirts,Sweatshirt,Felpa,Sweatshirt & Hoodies,Maglioni,Maglioni,Maglioni'),
(123, 40, 'Pants & Shorts', 113, 108, '', '', '2018-11-16 20:04:08', '2019-07-19 00:39:36', 'Pant,Pants,Trousers,Sweatpants,Tapered Pants,Track & Running Shorts,Deck Shorts,Swim & Board Shorts,Pantaloni crop,Pantaloni sartoriali,Skinny Pants,Tailored Pants,Pantaloni dritti,Pantaloni slim,Slim Pants,Sweatpants,Tapered Pants,Track & Running Shorts,Deck Shorts,Swim & Board Shorts,Pantaloni crop,Pantaloni sartoriali,Skinny Pants,Tailored Pants,Pantaloni dritti,Pantaloni slim,Slim Pants,Flared & Bell-Bottom Pants,High Waisted Pants,Palazzo Pants,Pantaloni da ginnastica,Regular-Fit & Straight Leg Pants,Chinos,Sweatpants,Tapered Pants,Track & Running Shorts,Deck Shorts,Swim & Board Shorts,Pantaloni crop,Pantaloni sartoriali,Skinny Pants,Tailored Pants,Pantaloni dritti,Pantaloni slim,Slim Pants,Flared & Bell-Bottom Pants,High Waisted Pants,Palazzo Pants,Pantaloni da ginnastica,Regular-Fit & Straight Leg Pants,Chinos,Bermuda Shorts Pants & Shorts,Cropped Pants,Slacks,Straight-Leg Pants,Short Shorts,Pantaloni palazzo,Swimming Trunks'),
(124, 40, 'Skirts', 115, 108, '', '', '2018-11-16 20:04:38', '2019-07-09 19:55:10', 'Gonne,Skirts,Skirt,A-line,Asymmetric & draped,Fitted,High Waisted,Straight,Flared,Pleated,Pencil Skirts,Long,Gonne anderente,gonna pissettata,gonne a vita alta,gonne a ruota,gonne a trapezio,gonne asimmetriche & drappegiate,Gonna,Straight Skirts,Pleated Skirts,Gonna plissettata,Straight Skirts,Pleated Skirts,Gonna plissettata,Full Skirts,Gonne asimmetriche & drappeggiate,Straight Skirts,Pleated Skirts,Gonna plissettata,Full Skirts,Gonne asimmetriche & drappeggiate,Asymmetric & Draped Skirts,Knitted Skirts,High-Waisted Skirts,Fitted Skirts,A-Line Skirts'),
(127, 40, 'Knitwear / Sweater', 194, 108, '', '', '2018-11-16 20:11:48', '2019-07-07 22:33:19', NULL),
(128, 40, 'Denim', 110, 108, '', '', '2018-11-16 20:13:42', '2019-06-15 03:06:00', 'Jeans,Skinny,Straight-leg,Boyfriend,Boot-cut and Flared,Cropped,Skinny Jeans,Flares & Bell Bottom Jeans,Bootcut Jeans,Slim-Fit Jeans,Regular & Straight-Leg Jeans,Skinny Jeans,Flares & Bell Bottom Jeans,Bootcut Jeans,Slim-Fit Jeans,Regular & Straight-Leg Jeans,Cropped Jeans,Jeans a gamba ampia,Jeans skinny,Jeans svasati,Skinny Jeans,Flares & Bell Bottom Jeans,Bootcut Jeans,Slim-Fit Jeans,Regular & Straight-Leg Jeans,Cropped Jeans,Jeans a gamba ampia,Jeans skinny,Jeans svasati,Straight-Leg Jeans,Wide-Leg Jeans,Tapered Jeans,Drop-Crotch Jeans,Boyfriend Jeans'),
(129, 12, 'Coats & Jackets & Suits', 65, 63, '', '', '2018-11-16 20:21:33', '2019-07-02 22:59:54', NULL),
(130, 12, 'Denim', 66, 63, '', '', '2018-11-16 20:22:15', '2018-11-16 20:22:15', NULL),
(133, 12, 'Pants & Shorts', 69, 63, '', '', '2019-07-02 01:54:07', '2019-07-02 01:54:07', NULL),
(134, 40, 'Beachwear', 64, 63, '', '', '2019-07-02 02:17:55', '2019-07-02 02:17:55', NULL),
(136, 38, 'Fragrances', 1253, 39, '', '', '2019-07-02 21:36:54', '2019-07-13 01:05:44', NULL),
(138, 1, 'Beachwear', 713, 41, '', '', '2019-07-02 22:03:54', '2019-07-02 22:03:54', NULL),
(139, 1, 'Sports', 714, 41, '', '', '2019-07-02 22:04:58', '2019-07-02 22:04:58', NULL),
(140, 1, 'Sports', 714, 41, '', '', '2019-07-02 22:06:32', '2019-07-02 22:06:32', NULL),
(141, 38, 'Watches', 725, 83, '', '', '2019-08-04 22:27:52', '2019-08-04 22:27:52', NULL),
(142, 10, 'watches', 726, 43, '', '', '2019-08-04 22:29:54', '2019-08-04 22:29:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `category_maps`
--

CREATE TABLE `category_maps` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alternatives` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chapters`
--

CREATE TABLE `chapters` (
  `id` int(10) UNSIGNED NOT NULL,
  `book_id` int(11) NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `restricted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int(10) UNSIGNED NOT NULL,
  `sourceid` int(10) NOT NULL,
  `userid` int(11) NOT NULL,
  `messages` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `unique_id` varchar(191) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `message` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lead_id` int(10) UNSIGNED DEFAULT NULL,
  `order_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `purchase_id` int(11) DEFAULT NULL,
  `supplier_id` int(10) UNSIGNED DEFAULT NULL,
  `vendor_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT 0,
  `task_id` int(10) UNSIGNED DEFAULT NULL,
  `lawyer_id` int(10) UNSIGNED DEFAULT NULL,
  `case_id` int(10) UNSIGNED DEFAULT NULL,
  `blogger_id` int(10) UNSIGNED DEFAULT NULL,
  `voucher_id` int(10) UNSIGNED DEFAULT NULL,
  `developer_task_id` int(11) DEFAULT NULL,
  `issue_id` int(11) DEFAULT NULL,
  `erp_user` int(10) UNSIGNED DEFAULT NULL,
  `contact_id` int(10) UNSIGNED DEFAULT NULL,
  `dubbizle_id` int(10) UNSIGNED DEFAULT NULL,
  `assigned_to` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `approved` tinyint(1) DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `sent` tinyint(1) NOT NULL DEFAULT 0,
  `is_delivered` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `is_read` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `error_status` int(11) NOT NULL DEFAULT 0,
  `resent` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_reminder` tinyint(1) NOT NULL DEFAULT 0,
  `media_url` varchar(2048) DEFAULT NULL,
  `is_processed_for_keyword` tinyint(1) NOT NULL DEFAULT 0,
  `document_id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `old_id` varchar(191) DEFAULT NULL,
  `message_application_id` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cold_leads`
--

CREATE TABLE `cold_leads` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `platform` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `platform_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `image` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `because_of` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `messages_sent` int(11) NOT NULL DEFAULT 0,
  `account_id` int(11) DEFAULT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_gender_processed` tinyint(1) NOT NULL DEFAULT 0,
  `is_country_processed` tinyint(1) NOT NULL DEFAULT 0,
  `followed_by` int(11) DEFAULT NULL,
  `is_imported` tinyint(1) NOT NULL DEFAULT 0,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cold_lead_broadcasts`
--

CREATE TABLE `cold_lead_broadcasts` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_of_users` int(11) NOT NULL,
  `frequency` int(11) NOT NULL,
  `started_at` datetime NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `messages_sent` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `frequency_completed` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `color_names_references`
--

CREATE TABLE `color_names_references` (
  `id` int(10) UNSIGNED NOT NULL,
  `color_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `erp_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `color_references`
--

CREATE TABLE `color_references` (
  `id` int(10) UNSIGNED NOT NULL,
  `brand_id` int(11) NOT NULL,
  `original_color` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `erp_color` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments_stats`
--

CREATE TABLE `comments_stats` (
  `id` int(10) UNSIGNED NOT NULL,
  `target` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_author` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `narrative` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'common'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `communication_histories`
--

CREATE TABLE `communication_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `model_id` int(10) UNSIGNED DEFAULT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_stopped` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `competitor_followers`
--

CREATE TABLE `competitor_followers` (
  `id` int(10) UNSIGNED NOT NULL,
  `competitor_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `competitor_pages`
--

CREATE TABLE `competitor_pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `platform` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'instagram',
  `platform_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cursor` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_processed` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `platform` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complaint` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plan_of_action` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `where` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thread_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `media_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_username` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_customer_flagged` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaint_threads`
--

CREATE TABLE `complaint_threads` (
  `id` int(10) UNSIGNED NOT NULL,
  `complaint_id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED DEFAULT NULL,
  `thread` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `compositions`
--

CREATE TABLE `compositions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `replace_with` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_bloggers`
--

CREATE TABLE `contact_bloggers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_handle` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quote` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courier`
--

CREATE TABLE `courier` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cron_jobs`
--

CREATE TABLE `cron_jobs` (
  `id` int(10) UNSIGNED NOT NULL,
  `signature` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cron_job_reports`
--

CREATE TABLE `cron_job_reports` (
  `id` int(10) UNSIGNED NOT NULL,
  `signature` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cropped_image_references`
--

CREATE TABLE `cropped_image_references` (
  `id` int(10) UNSIGNED NOT NULL,
  `original_media_id` int(11) NOT NULL,
  `original_media_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_media_id` int(11) NOT NULL,
  `new_media_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crop_amends`
--

CREATE TABLE `crop_amends` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `file_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `settings` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `whatsapp_number` varchar(255) DEFAULT NULL,
  `instahandler` varchar(255) DEFAULT NULL,
  `ig_username` varchar(255) DEFAULT NULL,
  `shoe_size` varchar(191) DEFAULT NULL,
  `clothing_size` varchar(191) DEFAULT NULL,
  `gender` varchar(191) DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 1,
  `do_not_disturb` tinyint(1) NOT NULL DEFAULT 0,
  `is_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `is_flagged` tinyint(1) NOT NULL DEFAULT 0,
  `is_error_flagged` tinyint(1) NOT NULL DEFAULT 0,
  `is_priority` tinyint(1) NOT NULL DEFAULT 0,
  `credit` varchar(191) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `instruction_completed_at` datetime DEFAULT NULL,
  `facebook_id` varchar(191) DEFAULT NULL,
  `frequency` int(11) DEFAULT NULL,
  `reminder_message` text DEFAULT NULL,
  `is_categorized_for_bulk_messages` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer_categories`
--

CREATE TABLE `customer_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_marketing_platforms`
--

CREATE TABLE `customer_marketing_platforms` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL,
  `marketing_platform_id` int(11) NOT NULL,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 0,
  `remark` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_with_categories`
--

CREATE TABLE `customer_with_categories` (
  `customer_id` int(10) UNSIGNED NOT NULL,
  `category_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_activities`
--

CREATE TABLE `daily_activities` (
  `id` int(10) UNSIGNED NOT NULL,
  `time_slot` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `is_admin` int(11) DEFAULT NULL,
  `assist_msg` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `for_date` date NOT NULL,
  `pending_for` int(11) NOT NULL DEFAULT 0,
  `is_completed` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_cash_flows`
--

CREATE TABLE `daily_cash_flows` (
  `id` int(10) UNSIGNED NOT NULL,
  `received_from` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_to` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expected` int(11) DEFAULT NULL,
  `received` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_approvals`
--

CREATE TABLE `delivery_approvals` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `private_view_id` int(11) DEFAULT NULL,
  `assigned_user_id` int(10) UNSIGNED DEFAULT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT 0,
  `status` varchar(191) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `designers`
--

CREATE TABLE `designers` (
  `id` int(11) NOT NULL,
  `website` text NOT NULL,
  `title` tinytext NOT NULL,
  `address` text NOT NULL,
  `designers` mediumtext NOT NULL,
  `image` mediumtext DEFAULT NULL,
  `email` text DEFAULT NULL,
  `social_handle` text DEFAULT NULL,
  `instagram_handle` text DEFAULT NULL,
  `site_link` text DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `developer_comments`
--

CREATE TABLE `developer_comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `send_to` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `developer_costs`
--

CREATE TABLE `developer_costs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `paid_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `developer_messages_alert_schedules`
--

CREATE TABLE `developer_messages_alert_schedules` (
  `id` int(10) UNSIGNED NOT NULL,
  `time` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `developer_modules`
--

CREATE TABLE `developer_modules` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `developer_tasks`
--

CREATE TABLE `developer_tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `module_id` int(11) DEFAULT NULL,
  `priority` int(11) NOT NULL,
  `subject` varchar(191) DEFAULT NULL,
  `task` longtext NOT NULL,
  `cost` int(11) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `module` int(11) NOT NULL DEFAULT 0,
  `completed` tinyint(4) NOT NULL DEFAULT 0,
  `estimate_time` timestamp NULL DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `task_type_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `developer_task_comments`
--

CREATE TABLE `developer_task_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `version` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `from_email` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_categories`
--

CREATE TABLE `document_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_histories`
--

CREATE TABLE `document_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `document_id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `filename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_remarks`
--

CREATE TABLE `document_remarks` (
  `id` int(10) UNSIGNED NOT NULL,
  `document_id` int(11) NOT NULL,
  `module_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remark` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_send_histories`
--

CREATE TABLE `document_send_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `send_by` int(11) NOT NULL,
  `send_to` int(11) NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `via` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dubbizles`
--

CREATE TABLE `dubbizles` (
  `id` int(11) NOT NULL,
  `url` varchar(400) NOT NULL,
  `keywords` varchar(400) NOT NULL,
  `post_date` varchar(50) NOT NULL,
  `requirements` varchar(400) NOT NULL,
  `body` varchar(10000) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `frequency` int(11) DEFAULT NULL,
  `reminder_message` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` int(10) UNSIGNED NOT NULL,
  `model_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'outgoing',
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `from` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `additional_data` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cc` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bcc` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `entity_permissions`
--

CREATE TABLE `entity_permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `restrictable_id` int(11) NOT NULL,
  `restrictable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_accounts`
--

CREATE TABLE `erp_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `table` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `row_id` int(11) DEFAULT NULL,
  `transacted_by` int(11) NOT NULL,
  `debit` decimal(8,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(8,2) NOT NULL DEFAULT 0.00,
  `user_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `metadata` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_leads`
--

CREATE TABLE `erp_leads` (
  `id` int(10) UNSIGNED NOT NULL,
  `lead_status_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `brand_id` int(10) UNSIGNED DEFAULT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_price` decimal(8,2) NOT NULL,
  `max_price` decimal(8,2) NOT NULL,
  `brand_segment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_lead_status`
--

CREATE TABLE `erp_lead_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `erp_lead_status`
--

INSERT INTO `erp_lead_status` (`id`, `name`) VALUES
(1, 'Cold Lead'),
(2, 'Cold / Important Lead'),
(3, 'Hot Lead'),
(4, 'Very Hot Lead'),
(5, 'Advance Follow Up'),
(6, 'HIGH PRIORITY');

-- --------------------------------------------------------

--
-- Table structure for table `excel_importers`
--

CREATE TABLE `excel_importers` (
  `id` int(10) UNSIGNED NOT NULL,
  `md5` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excel_importer_detail_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `brand` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `excel_importer_details`
--

CREATE TABLE `excel_importer_details` (
  `id` int(10) UNSIGNED NOT NULL,
  `excel_importer_id` int(11) NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_tools` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_tool` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_tools` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku_tools` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_sku` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_sku_tools` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size_tools` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender_tools` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_tools` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `made_in` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `made_in_tools` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_tools` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discounted_price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discounted_price_tools` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock_tools` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_color_tools` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_lmeasurement` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_lmeasurement_tools` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_hmeasurement` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_hmeasurement_tools` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_composition` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_composition_tools` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_dmeasurement` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_dmeasurement_tools` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_measurement` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `property_measurement_tools` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_messages`
--

CREATE TABLE `facebook_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL,
  `sender` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receiver` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_sent_by_me` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(10) UNSIGNED NOT NULL,
  `filename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flagged_instagram_posts`
--

CREATE TABLE `flagged_instagram_posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `media_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gmail_data`
--

CREATE TABLE `gmail_data` (
  `id` int(11) NOT NULL,
  `sender` text NOT NULL,
  `received_at` text NOT NULL,
  `page_url` varchar(400) NOT NULL,
  `images` longtext NOT NULL,
  `tags` longtext NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `googlescrapping`
--

CREATE TABLE `googlescrapping` (
  `id` int(11) NOT NULL,
  `keyword` text DEFAULT NULL,
  `name` text NOT NULL,
  `link` mediumtext NOT NULL,
  `description` longtext NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `source` varchar(191) NOT NULL,
  `is_updated` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `google_analytics`
--

CREATE TABLE `google_analytics` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `id` int(11) NOT NULL,
  `group_name` varchar(191) DEFAULT NULL,
  `group_url` varchar(400) NOT NULL,
  `username` varchar(191) NOT NULL,
  `profile_url` varchar(400) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hashtag_posts`
--

CREATE TABLE `hashtag_posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashtag_id` int(10) UNSIGNED NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `likes` int(11) NOT NULL DEFAULT 0,
  `number_comments` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hashtag_post_comments`
--

CREATE TABLE `hashtag_post_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_url` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hashtag_post_id` int(10) UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_commented` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `review_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hashtag_post_histories`
--

CREATE TABLE `hashtag_post_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashtag` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `instagram_automated_message_id` int(11) DEFAULT NULL,
  `post_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cursor` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hashtag_post_likes`
--

CREATE TABLE `hashtag_post_likes` (
  `id` int(11) NOT NULL,
  `username` varchar(191) NOT NULL,
  `profile_url` varchar(400) NOT NULL,
  `hashtag_post_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hash_tags`
--

CREATE TABLE `hash_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `hashtag` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `post_count` int(11) NOT NULL DEFAULT 0,
  `is_processed` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history_whatsapp_number`
--

CREATE TABLE `history_whatsapp_number` (
  `id` int(10) UNSIGNED NOT NULL,
  `date_time` datetime NOT NULL,
  `object` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_id` int(11) NOT NULL,
  `old_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `brand` int(10) UNSIGNED DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `publish_date` timestamp NULL DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `lifestyle` int(11) NOT NULL DEFAULT 0,
  `approved_user` int(11) UNSIGNED DEFAULT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `is_scheduled` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `posted` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `image_schedules`
--

CREATE TABLE `image_schedules` (
  `id` int(11) NOT NULL,
  `image_id` int(10) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `scheduled_for` datetime DEFAULT NULL,
  `facebook` tinyint(4) NOT NULL,
  `instagram` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `facebook_post_id` varchar(255) DEFAULT NULL,
  `instagram_post_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `posted` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `image_tags`
--

CREATE TABLE `image_tags` (
  `id` int(11) NOT NULL,
  `image_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `im_queues`
--

CREATE TABLE `im_queues` (
  `id` int(10) UNSIGNED NOT NULL,
  `im_client` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_to` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_from` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` int(11) DEFAULT 10,
  `send_after` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `influencers`
--

CREATE TABLE `influencers` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `brand_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blogger` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_post` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `second_post` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deals` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `list_first_post` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `list_second_post` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `influencers_d_ms`
--

CREATE TABLE `influencers_d_ms` (
  `id` int(10) UNSIGNED NOT NULL,
  `influencer_id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_automated_messages`
--

CREATE TABLE `instagram_automated_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `sender_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `receiver_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hashtag_posts',
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachments` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `reusable` int(11) NOT NULL DEFAULT 0,
  `use_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_auto_comments`
--

CREATE TABLE `instagram_auto_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `use_count` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `options` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_bulk_messages`
--

CREATE TABLE `instagram_bulk_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(11) NOT NULL,
  `receipts` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_direct_messages`
--

CREATE TABLE `instagram_direct_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `instagram_thread_id` int(11) NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_type` int(11) NOT NULL,
  `sender_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receiver_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_posts`
--

CREATE TABLE `instagram_posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `hashtag_id` int(11) DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_id` int(11) NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `media_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `media_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `posted_at` datetime NOT NULL,
  `source` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hashtag',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_posts_comments`
--

CREATE TABLE `instagram_posts_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `instagram_post_id` int(11) NOT NULL,
  `comment_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_pic_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `posted_at` datetime NOT NULL,
  `metadata` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `people_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `priority` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_threads`
--

CREATE TABLE `instagram_threads` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `thread_id` varchar(191) DEFAULT NULL,
  `thread_v2_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cold_lead_id` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `last_message_at` datetime DEFAULT NULL,
  `last_message` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_users_lists`
--

CREATE TABLE `instagram_users_lists` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `because_of` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `insta_messages`
--

CREATE TABLE `insta_messages` (
  `id` int(11) NOT NULL,
  `number` int(11) DEFAULT NULL,
  `message` longtext NOT NULL,
  `lead_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `approved` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `media_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `instructions`
--

CREATE TABLE `instructions` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT 1,
  `instruction` longtext NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `order_id` int(11) NOT NULL DEFAULT 0,
  `assigned_from` int(10) UNSIGNED NOT NULL,
  `assigned_to` int(10) UNSIGNED NOT NULL,
  `pending` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_priority` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `verified` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `instruction_categories`
--

CREATE TABLE `instruction_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE `issues` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `issue` longtext NOT NULL,
  `priority` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `module` varchar(191) NOT NULL,
  `responsible_user_id` int(11) DEFAULT NULL,
  `resolved_at` date DEFAULT NULL,
  `is_resolved` tinyint(1) NOT NULL DEFAULT 0,
  `submitted_by` int(11) DEFAULT NULL,
  `cost` decimal(8,2) NOT NULL DEFAULT 0.00,
  `subject` text DEFAULT NULL,
  `estimate_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `joint_permissions`
--

CREATE TABLE `joint_permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(11) NOT NULL,
  `entity_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` int(11) NOT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `has_permission` tinyint(1) NOT NULL DEFAULT 0,
  `has_permission_own` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keywords`
--

CREATE TABLE `keywords` (
  `id` int(10) UNSIGNED NOT NULL,
  `text` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keyword_instructions`
--

CREATE TABLE `keyword_instructions` (
  `id` int(10) UNSIGNED NOT NULL,
  `keywords` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `instruction_category_id` int(11) NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keyword_to_categories`
--

CREATE TABLE `keyword_to_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `keyword_value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lawyers`
--

CREATE TABLE `lawyers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referenced_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `speciality_id` int(10) UNSIGNED DEFAULT NULL,
  `rating` tinyint(4) DEFAULT NULL,
  `whatsapp_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lawyer_specialities`
--

CREATE TABLE `lawyer_specialities` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `client_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contactno` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `solophone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instahandler` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` int(2) NOT NULL,
  `status` int(2) NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assigned_user` int(10) NOT NULL,
  `source` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `leadsourcetxt` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `selected_product` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` int(10) DEFAULT NULL,
  `multi_brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `multi_category` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `userid` int(11) NOT NULL,
  `assign_status` int(2) DEFAULT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `whatsapp_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lead_broadcasts_lead`
--

CREATE TABLE `lead_broadcasts_lead` (
  `lead_broadcast_id` int(11) NOT NULL,
  `lead_id` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `links_to_posts`
--

CREATE TABLE `links_to_posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `date_scrapped` date DEFAULT NULL,
  `date_posted` datetime DEFAULT NULL,
  `date_next_post` datetime DEFAULT NULL,
  `article` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `listing_histories`
--

CREATE TABLE `listing_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'update'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `listing_payments`
--

CREATE TABLE `listing_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_ids` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `paid_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_excel_imports`
--

CREATE TABLE `log_excel_imports` (
  `id` int(10) UNSIGNED NOT NULL,
  `filename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_of_products` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_google_vision`
--

CREATE TABLE `log_google_vision` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image_url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `response` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_magento`
--

CREATE TABLE `log_magento` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date_time` datetime NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `response` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_scraper`
--

CREATE TABLE `log_scraper` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_sku` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `properties` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `images` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size_system` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discounted_price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_sale` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `validated` tinyint(4) NOT NULL,
  `validation_result` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `raw_data` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_scraper_vs_ai`
--

CREATE TABLE `log_scraper_vs_ai` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `ai_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `media_input` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `result_scraper` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `result_ai` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marketing_platforms`
--

CREATE TABLE `marketing_platforms` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(10) UNSIGNED NOT NULL,
  `disk` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `directory` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aggregate_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mediables`
--

CREATE TABLE `mediables` (
  `media_id` int(10) UNSIGNED NOT NULL,
  `mediable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mediable_id` int(10) UNSIGNED NOT NULL,
  `tag` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu_pages`
--

CREATE TABLE `menu_pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` tinyint(1) NOT NULL,
  `have_child` tinyint(1) NOT NULL,
  `department` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `userid` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `assigned_to` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `moduleid` int(10) DEFAULT NULL,
  `moduletype` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message_queues`
--

CREATE TABLE `message_queues` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `chat_message_id` int(10) UNSIGNED DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sent` tinyint(1) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `group_id` int(10) UNSIGNED NOT NULL,
  `sending_time` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messsage_applications`
--

CREATE TABLE `messsage_applications` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(28, '2014_10_12_000000_create_users_table', 1),
(29, '2014_10_12_100000_create_password_resets_table', 1),
(30, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(31, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(32, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(33, '2016_06_01_000004_create_oauth_clients_table', 1),
(34, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(35, '2018_08_06_171020_create_permission_tables', 1),
(36, '2018_08_07_051656_create_products_table', 1),
(37, '2018_08_08_091826_create_products_table', 2),
(38, '2018_08_10_092522_create_notifications_table', 3),
(39, '2018_08_11_064131_create_products_table', 4),
(40, '2018_08_11_080258_create_activity_log_table', 5),
(41, '2018_08_12_191554_create_settings_table', 6),
(42, '2018_08_13_153255_create_mediable_tables', 7),
(43, '2018_08_14_105818_create_category_table', 8),
(44, '2019_03_01_202417_add_is_enriched_to_scraped_products_table', 9),
(45, '2019_03_04_162703_add_purchase_statuses_to_products', 10),
(46, '2019_03_07_212800_create_vouchers_table', 11),
(47, '2019_03_08_155254_add_can_be_deleted_to_scraped_products_table', 12),
(48, '2019_03_08_161044_add_is_updated_on_server_column_to_scraped_products_table', 13),
(49, '2019_03_09_180543_add_box_dimensions_to_waybills', 14),
(53, '2019_03_10_153733_create_message_queues_table', 15),
(54, '2019_03_12_170846_add_sent_column_to_chat_messages', 16),
(55, '2019_03_13_141129_add_sent_column_to_msg_queues', 17),
(56, '2019_03_13_145430_add_do_not_disturb_to_customers', 17),
(57, '2019_03_13_152125_add_status_column_to_ms_queues', 17),
(58, '2019_03_13_154916_add_message_id_to_message_queues', 17),
(59, '2019_03_14_125818_create_ads_schedules_attachments_table', 18),
(60, '2019_03_14_125237_create_ads_schedules_table', 19),
(61, '2019_03_14_190251_create_files_table', 20),
(62, '2019_03_15_002134_add_columns_to_vouchers', 21),
(63, '2019_03_18_154219_add_approved_to_vouchers', 22),
(64, '2019_03_19_022506_add_timing_to_instructions', 23),
(65, '2019_03_21_224953_add_status_column_to_products', 24),
(66, '2019_03_22_001600_add_import_date_to_products', 25),
(67, '2019_03_22_163957_add_is_scraped_to_products', 26),
(68, '2019_04_03_143829_create_passwords_table', 27),
(69, '2019_04_04_155451_add_location_column_to_products', 28),
(70, '2019_04_04_233244_create_documents_table', 29),
(71, '2019_04_05_143319_create_cash_flows_table', 30),
(72, '2019_04_05_215318_create_budgets_table', 31),
(73, '2019_04_05_215716_create_budget_categories_table', 31),
(74, '2019_04_05_231356_create_daily_cash_flows_table', 32),
(75, '2019_04_08_181913_create_accounts_table', 33),
(76, '2019_04_08_231946_create_review_schedules_table', 33),
(77, '2019_04_08_232207_create_reviews_table', 33),
(78, '2019_04_11_005019_add_is_blocked_to_customers', 34),
(79, '2019_04_11_205249_add_columns_count_to_table', 35),
(80, '2019_04_13_143855_change_email_column_to_accounts', 36),
(81, '2019_04_13_155026_create_scrap_activities_table', 37),
(82, '2019_04_14_134247_create_social_tags_table', 38),
(84, '2019_04_17_113253_create_vendors_table', 39),
(85, '2019_04_17_143259_create_communication_histories_table', 40),
(86, '2019_04_17_213948_create_scrap_counts_table', 41),
(87, '2019_04_17_185823_add_new_column_to_orders', 42),
(88, '2019_04_18_134657_add_is_uploaded_date', 43),
(89, '2019_04_18_184633_create_vendor_products_table', 44),
(90, '2019_04_20_140153_add_more_columns_to_reviews', 45),
(91, '2019_04_20_183808_add_status_column_to_private_viewing', 46),
(92, '2019_04_20_200204_create_suppliers_table', 47),
(93, '2019_04_20_200539_add_product_suppliers', 47),
(94, '2019_04_21_175719_add_status_to_communication_histories', 48),
(95, '2019_04_21_194033_create_customer_suggestion_table', 49),
(96, '2019_04_21_195825_create_suggestion_products_table', 49),
(97, '2019_04_21_205326_add_customer_id_to_review_schedules', 50),
(98, '2019_04_22_111242_add_more_columns_to_suppliers', 51),
(99, '2019_04_22_121343_create_agents_table', 51),
(100, '2019_04_22_194511_modify_reviews_table', 52),
(101, '2019_04_23_131040_add_supplier_id_to_purchases', 53),
(102, '2019_04_23_141440_add_whatsapp_number_to_agents', 53),
(103, '2019_04_23_204334_create_emails_table', 54),
(104, '2019_04_24_131053_add_more_columns_to_emails', 55),
(105, '2019_04_24_150400_create_product_references_table', 56),
(106, '2019_04_25_190924_create_complaints_table', 57),
(107, '2019_04_25_195505_add_credit_column_to_customers', 58),
(108, '2019_04_26_150646_create_api_keys_table', 59),
(109, '2019_04_26_205812_create_complaint_threads_table', 60),
(110, '2019_04_27_144910_add_new_fields_to_suppliers', 61),
(111, '2019_04_28_155200_create_status_changes_table', 62),
(112, '2019_04_28_174909_change_vouchers_columns', 63),
(113, '2019_04_29_131624_add_additional_column_to_message_queues', 64),
(114, '2019_04_29_182645_add_account_id_to_threads', 65),
(115, '2019_04_30_132939_add_platform_to_reviews', 66),
(116, '2019_04_30_142142_add_status_to_complaint', 67),
(117, '2019_04_30_180050_add_type_to_complaints', 68),
(118, '2019_05_01_142718_add_subject_to_dev_tasks', 69),
(119, '2019_05_01_212547_create_broadcast_images_table', 70),
(120, '2019_05_02_203249_create_auto_replies_table', 71),
(121, '2019_05_02_131419_add_assigned_to_chat_messages', 72),
(122, '2019_05_03_111302_add_flagged_to_customers', 73),
(123, '2019_05_03_194408_add_error_status_to_chat_messages', 74),
(124, '2019_05_04_150532_create_hash_tags_table', 75),
(125, '2019_05_04_195556_add_is_listed_to_products', 76),
(126, '2019_05_06_154415_change_qty_order_product_type', 77),
(127, '2019_05_06_164513_add_new_columns_to_customers', 77),
(128, '2019_05_07_111340_add_error_flag_to_customers', 78),
(129, '2019_05_07_174647_add_sending_time_to_broadcast_images', 79),
(130, '2019_05_08_123211_add_is_approved_to_products', 80),
(131, '2019_05_08_163610_adjust_columns_in_hash_tags_table', 81),
(132, '2019_05_08_165531_create_hashtag_posts_table', 81),
(133, '2019_05_08_170114_create_hashtag_post_comments_table', 81),
(134, '2019_05_08_184049_create_cron_job_reports_table', 82),
(135, '2019_05_09_111006_add_priority_to_instructions', 83),
(136, '2019_05_09_131157_add_priority_to_orders', 83),
(137, '2019_05_09_133217_add_priority_to_customers', 84),
(138, '2019_05_09_143851_add_columns_to_auto_replies', 85),
(139, '2019_05_09_151706_create_scheduled_messages_table', 85),
(140, '2019_05_10_125911_add_special_special_price_to_products', 86),
(141, '2019_05_09_224605_add_supplier_to_chat_messages', 87),
(142, '2019_05_10_113859_add_columns_to_supplier', 87),
(143, '2019_05_10_134514_add_proforma_to_purchase', 87),
(144, '2019_05_10_154328_default_email_to_suppliers', 88),
(145, '2019_05_07_075135_create_instagram_bulk_messages_table', 89),
(146, '2019_05_09_115334_add_statuses_to_hashtag_post_comments_table', 89),
(147, '2019_05_09_124410_make_customer_id_nullable_in_complaints_table', 89),
(148, '2019_05_09_161258_add_media_id_in_complaints_table', 89),
(149, '2019_05_10_140824_add_rating_in_hashtags_table', 89),
(150, '2019_05_11_153926_create_purchase_discounts_table', 89),
(151, '2019_05_11_184921_add_proforma_details_to_purchase', 89),
(152, '2019_05_11_194534_add_purchase_status_to_products', 89),
(153, '2019_05_10_214719_create_cold_leads_table', 90),
(154, '2019_05_11_132253_create_targeted_accounts_table', 90),
(155, '2019_05_11_164810_add_because_of_column_in_cold_leads_table', 90),
(156, '2019_05_11_192940_create_automated_messages_table', 90),
(157, '2019_05_11_215236_create_rejected_leads_table', 90),
(158, '2019_05_11_224137_create_page_screenshots_table', 90),
(159, '2019_05_13_140911_add_gender_to_customers', 91),
(160, '2015_03_07_311070_create_tracker_paths_table', 92),
(161, '2015_03_07_311071_create_tracker_queries_table', 92),
(162, '2015_03_07_311072_create_tracker_queries_arguments_table', 92),
(163, '2015_03_07_311073_create_tracker_routes_table', 92),
(164, '2015_03_07_311074_create_tracker_routes_paths_table', 92),
(165, '2015_03_07_311075_create_tracker_route_path_parameters_table', 92),
(166, '2015_03_07_311076_create_tracker_agents_table', 92),
(167, '2015_03_07_311077_create_tracker_cookies_table', 92),
(168, '2015_03_07_311078_create_tracker_devices_table', 92),
(169, '2015_03_07_311079_create_tracker_domains_table', 92),
(170, '2015_03_07_311080_create_tracker_referers_table', 92),
(171, '2015_03_07_311081_create_tracker_geoip_table', 92),
(172, '2015_03_07_311082_create_tracker_sessions_table', 92),
(173, '2015_03_07_311083_create_tracker_errors_table', 92),
(174, '2015_03_07_311084_create_tracker_system_classes_table', 92),
(175, '2015_03_07_311085_create_tracker_log_table', 92),
(176, '2015_03_07_311086_create_tracker_events_table', 92),
(177, '2015_03_07_311087_create_tracker_events_log_table', 92),
(178, '2015_03_07_311088_create_tracker_sql_queries_table', 92),
(179, '2015_03_07_311089_create_tracker_sql_query_bindings_table', 92),
(180, '2015_03_07_311090_create_tracker_sql_query_bindings_parameters_table', 92),
(181, '2015_03_07_311091_create_tracker_sql_queries_log_table', 92),
(182, '2015_03_07_311092_create_tracker_connections_table', 92),
(183, '2015_03_07_311093_create_tracker_tables_relations', 92),
(184, '2015_03_13_311094_create_tracker_referer_search_term_table', 92),
(185, '2015_03_13_311095_add_tracker_referer_columns', 92),
(186, '2015_11_23_311096_add_tracker_referer_column_to_log', 92),
(187, '2015_11_23_311097_create_tracker_languages_table', 92),
(188, '2015_11_23_311098_add_language_id_column_to_sessions', 92),
(189, '2015_11_23_311099_add_tracker_language_foreign_key_to_sessions', 92),
(190, '2015_11_23_311100_add_nullable_to_tracker_error', 92),
(191, '2017_01_31_311101_fix_agent_name', 92),
(192, '2017_06_20_311102_add_agent_name_hash', 92),
(193, '2017_12_13_150000_fix_query_arguments', 92),
(194, '2019_05_12_220619_create_competitor_pages_table', 92),
(195, '2019_05_12_221931_create_user_actions_table', 92),
(196, '2019_05_15_151114_add_type_column_to_emails', 93),
(197, '2019_05_13_091648_create_proxies_table', 94),
(198, '2019_05_14_092828_create_sitejabber_q_a_s_table', 94),
(199, '2019_05_16_130842_add_is_color_fixed_for_scraped_products_table', 95),
(200, '2019_05_17_113327_add_seen_to_emails', 96),
(201, '2019_05_17_214308_add_icon_to_instructions_categories', 97),
(202, '2019_05_15_115407_create_target_locations_table', 98),
(203, '2019_05_15_130026_create_instagram_users_lists_table', 98),
(204, '2019_05_17_173201_create_instagram_posts_table', 98),
(205, '2019_05_17_180539_create_instagram_posts_comments_table', 98),
(206, '2019_05_17_191817_create_keywords_table', 98),
(207, '2019_05_17_200009_add_is_processed_column_to_hash_tags_table', 98),
(208, '2019_05_18_181300_create_people_names_table', 98),
(209, '2019_05_19_022632_add_profile_url_in_hashtag_post_comments_table', 98),
(210, '2019_05_19_141112_add_is_image_processed_in_products_table', 98),
(211, '2019_05_19_192341_create_hashtag_post_histories_table', 99),
(212, '2019_05_20_131759_create_instagram_automated_messages_table', 99),
(213, '2019_05_20_150209_add_is_active_column_to_accounts_table', 99),
(214, '2019_05_20_182923_create_activities_routines_table', 99),
(215, '2019_05_20_185649_add_title_column_in_reviews_table', 99),
(216, '2019_05_20_192644_create_dubbizles_table', 99),
(217, '2019_05_21_221823_create_influencers_table', 100),
(218, '2019_05_21_223133_create_influencers_d_ms_table', 100),
(219, '2019_05_22_122019_add_columns_to_influencers_table', 101),
(221, '2019_05_22_183219_create_cron_jobs_table', 102),
(222, '2019_05_23_140849_add_columns_in_instagram_automated_messages_table', 103),
(223, '2019_05_23_164446_create_competitor_followers_table', 103),
(224, '2019_05_23_172100_add_cusror_column_in_competitor_pages_table', 103),
(225, '2019_05_23_164729_add_task_id_to_chat_messages', 104),
(226, '2019_05_24_125428_add_columns_to_instagram_threads_table', 105),
(227, '2019_05_24_130054_create_instagram_direct_messages_table', 105),
(228, '2019_05_24_133010_create_flagged_instagram_posts_table', 105),
(229, '2019_05_24_135636_add_columns_in_cold_leads_table', 105),
(230, '2019_05_24_215641_add_private_to_tasks', 106),
(231, '2019_05_25_104921_create_cold_lead_broadcasts_table', 107),
(232, '2019_05_25_120709_create_lead_broadcasts_lead_table', 107),
(233, '2019_05_25_134008_add_frequency_completed_column_in_cold_lead_broadcasts_table', 107),
(234, '2019_05_25_150219_add_messages_sent_column_in_cold_leads_table', 107),
(235, '2019_05_25_151531_add_broadcast_column_in_accounts_table', 107),
(236, '2019_05_25_192648_add_account_id_in_cold_leads_table', 108),
(238, '2019_05_25_124636_create_task_users_table', 109),
(239, '2019_05_25_203655_add_dubbizle_id_to_messages', 110),
(240, '2019_05_26_132553_add_broadcasted_messages_in_accounts_table', 110),
(241, '2019_05_27_130151_add_is_processed_column_in_competitor_pages_table', 110),
(242, '2019_05_27_153427_create_contacts_table', 111),
(243, '2019_05_27_155748_add_type_column_to_user_tasks', 111),
(244, '2019_05_27_165920_add_contact_id_to_messages', 111),
(245, '2019_05_27_173401_add_category_to_contacts', 112),
(246, '2019_05_27_180855_add_priority_to_suppliers', 113),
(247, '2019_05_29_172924_add_resent_to_chat_messages', 114),
(249, '2019_05_29_180741_change_contact_phone_type', 115),
(250, '2019_05_30_142554_create_quick_replies_table', 116),
(251, '2019_05_30_173133_add_has_error_to_suppliers', 117),
(252, '2019_05_31_134458_add_reaccuring_type_to_tasks', 118),
(253, '2019_05_31_183407_add_data_to_scheduled_messages', 119),
(254, '2019_05_31_191611_change_customer_id_for_scheduled_messages', 119),
(255, '2019_05_31_193216_add_customer_id_again_to_scheduled_messages', 119),
(256, '2019_05_31_202722_add_is_watched_to_tasks', 120),
(257, '2019_05_31_200312_create_instagram_auto_comments_table', 121),
(258, '2019_06_01_120136_add_is_without_image_to_products', 122),
(259, '2019_06_01_125130_change_description_type_for_products', 123),
(260, '2019_05_31_214618_create_auto_reply_hashtags_table', 124),
(261, '2019_05_31_214647_create_auto_comment_histories_table', 124),
(262, '2019_06_01_121109_add_columns_in_cold_leads_table', 124),
(263, '2019_06_01_130751_change_composition_type_for_products', 125),
(264, '2019_05_28_141933_add_assigned_user_to_private_viewing', 126),
(265, '2019_05_28_164043_add_details_to_delivery_approvals', 126),
(266, '2019_06_02_111304_add_columns_in_auto_comment_histories_table', 127),
(267, '2019_06_04_180113_add_columns_to_suppliers_table', 128),
(268, '2019_06_02_133615_add_is_verified_to_tasks', 129),
(269, '2019_06_05_145502_add_is_sale_column_on_scraped_products_table', 130),
(270, '2019_06_06_010753_create_users_products_table', 131),
(271, '2019_06_06_011414_add_amount_assigned_to_users', 131),
(272, '2019_06_06_113620_add_is_on_sale_to_products', 132),
(273, '2019_06_06_210602_add_is_reminder_to_messages', 133),
(274, '2019_06_06_162238_create_category_maps_table', 134),
(275, '2019_06_07_133227_add_country_column_in_instagram_auto_comments_table', 135),
(276, '2019_06_07_144804_add_country_column_in_auto_comment_histories_table', 135),
(277, '2019_06_07_171347_add_country_column_in_accounts_table', 135),
(278, '2019_06_08_151452_add_gender_column_in_different_table', 136),
(279, '2019_06_08_234500_create_comments_stats_table', 137),
(280, '2019_06_09_164934_add_is_flagged_to_tasks', 138),
(281, '2019_06_10_131634_add_post_count_in_hash_tags_table', 139),
(282, '2019_06_10_104858_add_followed_by_to_cold_leads_table', 140),
(283, '2019_06_10_162154_add_manual_comment_column_to_accounts_table', 140),
(284, '2019_06_10_191328_add_options_to_instagram_auto_comments_table', 141),
(285, '2019_06_10_190926_add_parent_id_to_task_categories', 142),
(286, '2019_06_12_010716_create_scrap_statistics_table', 143),
(287, '2019_06_12_114841_add_vendor_id_to_chat_messages', 144),
(288, '2019_06_12_121947_add_two_columns_to_vendors_table', 144),
(289, '2019_06_12_133250_add_blocked_column_in_accounts_table', 145),
(290, '2019_06_12_180502_add_narrative_column_in_comments_stats_table', 146),
(291, '2019_06_13_014103_add_columns_in_scrap_statistics_table', 147),
(292, '2019_06_13_172949_add_columns_to_product_suppliers', 148),
(293, '2019_06_13_222840_add_is_price_different_to_products', 149),
(294, '2019_06_12_201227_add_credentials_to_vendors_table', 150),
(295, '2019_06_14_133148_add_recurring_to_vendor_products', 150),
(296, '2019_06_14_183538_add_sending_time_to_tasks', 151),
(297, '2019_06_14_200438_add_more_fields_to_product_suppliers', 152),
(298, '2019_06_14_234512_add_reference_column_for_categories_table', 153),
(299, '2019_06_15_112750_add_sku_to_products_suppliers', 154),
(300, '2019_06_15_053844_add_original_sku_column_in_scraped_products_table', 155),
(301, '2019_06_16_111658_add_is_seeding_column_in_accounts_table', 156),
(302, '2019_06_16_112232_add_columns_in_accounts_table', 156),
(303, '2019_06_16_141123_add_crop_count_in_products_table', 157),
(304, '2019_06_16_165457_add_is_approved_to_task_categories', 158),
(305, '2019_06_16_182955_add_is_crop_rejected_column_in_products_table', 159),
(306, '2019_06_16_125258_add_category_id_to_vouchers', 160),
(307, '2019_06_16_125418_create_voucher_categories_table', 160),
(308, '2019_06_17_180154_add_crop_remark_column_in_products_table', 161),
(309, '2019_06_17_183616_add_discounted_price_column_in_scraped_products_table', 162),
(310, '2019_06_17_192822_add_price_discounted_to_product_suppliers', 163),
(311, '2019_06_17_200907_add_category_id_to_vendors', 164),
(312, '2019_06_17_201002_create_vendor_categories_table', 164),
(313, '2019_06_17_210828_create_user_customers_table', 165),
(314, '2019_06_18_134613_add_more_columns_to_tasks', 166),
(315, '2019_06_18_163202_create_crop_amends_table', 166),
(316, '2019_06_18_213113_add_columns_to_daily_activities', 167),
(317, '2019_06_18_203815_create_pre_accounts_table', 168),
(318, '2019_06_19_165736_add_more_columns_to_vendors', 169),
(319, '2019_06_19_183406_add_is_crop_approved_column_in_products_table', 170),
(320, '2019_06_19_222216_add_is_farfetched_column_in_products_table', 171),
(321, '2019_06_20_141903_add_is_planner_completed_to_users', 172),
(322, '2019_06_17_181655_add__shipment_date_to_purchases', 173),
(323, '2019_06_19_200418_add_shipment_date_to_order_products', 173),
(324, '2019_06_20_203115_add_is_active_to_users', 174),
(325, '2019_06_21_180958_add_columns_to_products_table', 175),
(326, '2019_06_21_181910_add_columns_in_products_table', 176),
(327, '2019_06_21_190023_add_columns_in_instagram_posts_table', 177),
(328, '2019_06_21_202719_add_is_being_cropped_in_products_table', 177),
(329, '2019_06_22_105859_add_is_crop_ordered_column_in_products_table', 178),
(330, '2019_06_21_135034_add_order_product_id_to_private_viewing', 179),
(331, '2019_06_21_145525_add_shipment_changed_count_to_order_products', 179),
(332, '2019_06_24_161840_add_listing_remark_column_in_products_table', 180),
(333, '2019_06_24_183439_add_columns_in_products_table', 181),
(334, '2019_06_25_005314_create_sops_table', 182),
(335, '2019_06_25_103344_add_crop_ordered_by_column_in_products_table', 183),
(336, '2019_06_25_120958_add_columns_in_issues_table', 184),
(337, '2019_06_25_125458_add_issue_id_in_chat_messages_table', 184),
(338, '2019_06_25_195515_add_columns_in_developer_tasks_table', 185),
(339, '2019_06_25_212157_add_developer_task_id_in_chat_messages_table', 186),
(340, '2019_06_26_011240_add_is_imported_in_cold_leads_table', 187),
(341, '2019_06_26_011805_add_address_column_in_cold_leads_table', 188),
(342, '2019_06_26_015608_add_customer_id_column_in_cold_leads_table', 189),
(343, '2019_06_26_104202_add_is_crop_being_verified_column_in_products_table', 190),
(344, '2019_06_27_022657_add_notes_column_in_customers_table', 191),
(345, '2019_06_27_022757_add_notes_column_in_vendors_table', 192),
(346, '2019_06_27_022827_add_notes_column_in_suppliers_table', 192),
(347, '2019_06_27_164535_create_listing_histories_table', 193),
(348, '2019_06_27_210833_add_columns_in_products_table', 194),
(349, '2019_06_27_211326_add_columns_in_products_table', 195),
(350, '2019_06_28_002536_add_action_column_in_listing_histories_table', 196),
(351, '2019_06_28_020414_create_user_product_feedbacks_table', 197),
(352, '2019_06_28_193405_add_is_order_rejected_column_in_products_table', 198),
(353, '2019_06_29_145926_add_more_columns_in_products_table', 199),
(354, '2019_06_29_151400_create_attribute_replacements_table', 200),
(355, '2019_06_29_155314_add_subject_column_in_issues_table', 201),
(356, '2019_06_29_174639_add_columns_in_attribute_replacements_tabke', 202),
(357, '2019_06_30_132353_add_is_titlecased_column_in_products_table', 203),
(358, '2019_06_30_191857_add_is_listing_rejected_automatically_column_in_products_table', 204),
(359, '2019_07_01_151358_add_last_inventory_at_column_in_scraped_products_table', 205),
(360, '2019_07_01_180910_add_was_auto_rejected_column_in_products_table', 206),
(361, '2019_07_03_133429_create_listing_payments_table', 207),
(362, '2019_07_05_134652_add_timestamps_column_in_user_products_table', 208),
(363, '2019_07_05_173507_add_user_id_column_in_vendor_categories_table', 209),
(364, '2019_07_05_205204_add_columns_in_users_table', 210),
(365, '2019_07_06_194552_create_compositions_table', 211),
(366, '2019_07_07_124415_create_product_sizes_table', 212),
(367, '2019_07_07_160027_add_more_columns_in_users__table', 213),
(368, '2019_07_08_130849_add_replace_with_column_in_compositions_table', 214),
(369, '2019_07_10_195033_add_is_being_ordered_column_in_products_table', 215),
(370, '2019_07_10_200557_add_instruction_completed_at_column_in_products_table', 216),
(371, '2019_07_10_210834_add_instruction_completed_at_column_in_customers_table', 217),
(372, '2019_07_11_133909_create_developer_messages_alert_schedules_table', 218),
(373, '2019_07_11_151502_add_estimate_time_column_in_issues_table', 219),
(374, '2019_07_11_192542_add_status_column_in_suppliers_table', 220),
(375, '2019_07_12_091007_resource_categories', 221),
(376, '2019_07_12_091020_resource_image', 221),
(377, '2019_07_09_175019_add_cc_bcc_columns_to_emails_table', 222),
(378, '2019_07_12_182648_add_deleted_at_column_in_vendors_table', 223),
(379, '2019_07_12_202347_add_deleted_at_column_in_suppliers_table', 224),
(380, '2019_07_13_125940_create_color_references_table', 225),
(381, '2019_07_13_144244_add_is_updated_column_in_suppliers_table', 226),
(382, '2019_07_14_200501_create_cropped_image_references_table', 227),
(383, '2019_07_16_021917_create_facebook_messages_table', 228),
(384, '2019_07_16_023245_add_facebook_id_column_in_customers_table', 229),
(385, '2019_07_17_120717_create_color_names_references_table', 230),
(386, '2019_07_17_162509_add_is_auto_processing_failed_column_in_products_table', 231),
(387, '2019_07_17_164607_create_picture_colors_table', 232),
(388, '2019_07_18_133034_add_columns_for_reminders_in_customers_table', 233),
(389, '2019_07_18_155534_create_users_auto_comment_histories_table', 234),
(390, '2019_07_19_142524_add_is_crop_skipped_column_in_products_table', 235),
(391, '2019_07_21_182032_create_erp_accounts_table', 236),
(392, '2019_07_21_190525_add_is_verified_column_in_auto_comment_histories_table', 237),
(393, '2019_07_21_191613_add_soft_deletes_in_accounts_table', 238),
(394, '2019_07_22_004219_add_reminder_column_in_suppliers_table', 239),
(395, '2019_07_22_015538_add_reminder_column_in_vendors_table', 240),
(396, '2019_07_20_112532_create_lawyer_specialities_table', 241),
(397, '2019_07_20_112533_create_lawyers_table', 241),
(398, '2019_07_21_223409_add_lawyer_id_to_chat_messages_table', 241),
(399, '2019_07_21_223711_create_cases_table', 241),
(400, '2019_07_22_223831_add_case_id_chat_messages_table', 241),
(401, '2019_07_22_242425_create_case_costs_table', 241),
(402, '2019_07_24_202925_create_s_e_o_analytics_table', 242),
(403, '2019_07_28_154508_create_keyword_instructions_table', 243),
(404, '2019_07_27_200735_create_back_link_checker_table', 244),
(405, '2019_07_28_161448_create_old_incomings_table', 245),
(406, '2019_07_29_232443_create_old_table', 245),
(407, '2019_07_24_184151_create_contact_bloggers_table', 246),
(408, '2019_07_24_184217_create_bloggers_table', 246),
(409, '2019_07_24_184309_create_blogger_products_table', 246),
(410, '2019_07_24_191936_create_blogger_product_images_table', 246),
(411, '2019_07_29_185352_add_blogger_id_field_chat_messages_table', 246),
(412, '2019_07_29_223609_create_blogger_email_templates_table', 246),
(413, '2019_07_31_200513_add_is_customer_flagged_column_in_complaints_table', 247),
(414, '2019_08_01_122021_add_cropped_at_column_in_products_table', 248),
(415, '2019_08_02_110714_create_log_scraper_vs_ai', 249),
(416, '2019_08_02_193917_add_foreign_key_to_log_scraper_vs_ai', 250),
(417, '2019_07_04_161826_fetch_composition_to_products_if_they_are_scraped', 251),
(418, '2019_07_04_190529_add_facebook_id_column_in_products_table', 251),
(419, '2019_08_02_045350_create_log_google_vision', 251),
(420, '2019_08_03_225655_create_log_google_vision_reference', 251),
(421, '2019_07_27_093800_create_pinterest_users', 252),
(422, '2019_07_27_173326_create_pinterest_boards_table', 252),
(423, '2019_08_03_173509_create_cache_table', 252),
(424, '2019_08_03_200007_create_google_analytics_table', 252),
(425, '2019_08_07_114239_create_supplier_inventory', 253),
(426, '2019_08_07_084454_create_price_comparison_site', 254),
(427, '2019_08_07_201830_create_price_comparison', 254),
(428, '2019_08_09_100041_update_log_google_vision_reference', 254),
(429, '2019_08_11_043745_alter_price_comparison', 255),
(430, '2019_08_03_122127_modify_cash_flows_table', 256),
(431, '2019_08_07_225844_create_vendor_payments_table', 256),
(432, '2019_08_08_223912_add_deleted_at_field_vendor_payments_table', 256),
(433, '2019_08_08_233032_create_case_receivables_table', 256),
(434, '2019_08_09_224439_create_blogger_payments_table', 256),
(435, '2019_08_10_111011_create_monetary_accounts_table', 256),
(436, '2019_08_10_231338_add_column_hubstaff_auth_token_to_users_table', 256),
(437, '2019_08_13_191158_log_magento', 257),
(438, '2019_08_13_204531_create_user_manual_crop_table', 258),
(439, '2019_08_14_202357_add_is_enhanced_column_in_products_table', 259),
(440, '2019_08_15_130427_create_failed_jobs_table', 260),
(441, '2019_08_15_110727_create_product_status', 261),
(442, '2019_08_15_134745_create_customer_categories_table', 262),
(443, '2019_08_15_135223_create_customer_with_category_table', 262),
(444, '2019_08_15_140006_create_keyword_to_categories_table', 262),
(445, '2019_08_15_164058_change_model_id_column_in_keyword_to_categories_table', 263),
(447, '2019_08_16_142043_create_price_range', 264),
(448, '2019_08_16_013129_add_is_categorized_for_bulk_messages_column_in_customers_table', 265),
(449, '2019_08_16_132802_create_bulk_customer_replies_keywords_table', 265),
(450, '2019_08_16_195905_alter_table_categories', 265),
(451, '2019_08_17_154656_create_bulk_customer_replies_keyword_customer_table', 266),
(452, '2019_08_17_162105_add_is_processed_column_in_bulk_customer_replies_keywords_table', 266),
(453, '2019_08_17_165823_alter_scraped_products_add_column', 266),
(457, '2019_08_19_010848_alter_table_categories_add_columns_for_range', 267),
(458, '2019_08_20_144309_create_log_scraper', 267),
(459, '2019_08_20_194743_alter_table_scraped_products_add_column_currency', 267),
(460, '2019_08_20_200910_alter_table_scraped_products_make_columns_nullable', 267),
(461, '2019_08_23_142926_alter_table_products_change_column_price_to_integer', 268),
(462, '2019_08_23_174757_alter_log_scraper_add_column_brand', 269),
(463, '2019_08_24_112507_alter_brands_add_column_references', 270),
(464, '2019_08_24_135024_alter_table_products_add_indexes_for_brand_supplier_is_on_sale_listing_approved_at', 271),
(465, '2019_08_26_151912_add_is_processed_for_keyword_column_in_chat_messages_table', 272),
(466, '2019_08_26_171638_alter_scraped_products_add_column_is_excel', 273),
(467, '2019_08_27_103232_create_status', 274),
(468, '2019_08_28_144808_create_menu_pages_table', 275),
(469, '2019_08_28_145355_create_assigned_user_pages_table', 275),
(470, '2019_08_28_145734_create_departments_table', 275),
(471, '2019_08_28_145829_create_assinged_department_menu_table', 275),
(472, '2019_08_28_150258_add_department_id_to_users', 275),
(473, '2019_08_28_125653_add_reminder_columns_in_dubbizles_table', 276),
(474, '2019_08_28_160801_alter_documents_add_category', 277),
(475, '2019_08_29_134346_create_document_categories_table', 278),
(476, '2019_08_29_153539_update_documents_table', 278),
(477, '2019_08_20_225246_add_fields_vouchers_table', 279),
(478, '2019_08_24_152907_add_voucher_id_chat_messages_table', 279),
(479, '2019_08_30_153432_alter_brands_add_columns_for_sku', 280),
(480, '2019_08_30_142932_create_assets_manager_table', 281),
(481, '2019_08_30_144421_create_assets_category_table', 281),
(482, '2019_08_19_185103_create_links_to_posts_table', 282),
(483, '2019_08_19_203458_create_article_categories_table', 282),
(484, '2019_08_30_140553_create_permission_role_table', 282),
(485, '2019_08_30_142436_create_role_user_table', 282),
(486, '2019_08_31_094229_create_permission_user_table', 282),
(487, '2019_08_30_225702_create_se_ranking_table', 283),
(488, '2019_09_04_101334_alter_log_scraper_add_column_ip_address', 284),
(489, '2019_08_30_135641_update_permissions_table', 285),
(490, '2019_09_04_203809_create_s_e_ranking_table', 286),
(491, '2019_09_06_085208_create_supplier_category_table', 287),
(492, '2019_09_06_085813_create_supplier_status_table', 287),
(493, '2019_09_06_090105_add_scraper_name_to_suppliers', 287),
(494, '2019_09_06_090418_add_supplier_category_id_to_suppliers', 287),
(495, '2019_09_06_090627_add_supplier_status_id_to_suppliers', 287),
(496, '2019_09_10_125644_create_scrap_remarks_table', 288),
(497, '2019_09_10_172606_update_scrapper_remark_table', 289),
(498, '2019_09_10_170615_alter_table_suppliers_add_indexes_for_scraper_name', 290),
(499, '2019_09_11_130933_add_inventory_lifetime_to_suppliers', 290),
(500, '2019_09_04_154340_update_chat_messages_table', 291),
(501, '2019_09_04_183633_create_document_remarks_table', 291),
(502, '2019_09_04_195101_create_document_histories_table', 291),
(503, '2019_09_10_110709_create_zoom_meetings_table', 292),
(504, '2019_09_11_154910_add_meeting_details_in_zoom_meetings_table', 292),
(505, '2019_09_12_095240_create_whats_app_groups_table', 292),
(506, '2019_09_12_100041_add_user_details_in_zoom_meetings_table', 292),
(507, '2019_09_12_100418_create_whats_app_group_numbers_table', 292),
(508, '2019_09_12_115712_update_chat_messages_table', 292),
(509, '2019_09_12_180913_create_task_types_table', 292),
(510, '2019_09_12_181343_alter_developer_tasks_add_task_type_id_column', 292),
(511, '2019_09_14_120126_add_delete_recording_flag_in_zoom_meetings_table', 292),
(512, '2019_09_17_110709_create_page_notes_table', 293),
(513, '2019_09_15_190004_update_permission_table', 294),
(514, '2019_09_15_153937_create_task_history_table', 295),
(515, '2019_09_18_154826_create_user_logs_table', 296),
(516, '2019_09_21_153937_create_history_whatsapp_number_table', 297),
(517, '2019_09_23_155645_create_password_histories_table', 298),
(518, '2019_09_23_170350_update_passwords_table', 298),
(519, '2019_09_23_170410_update_passwords_password_histories_table', 298),
(520, '2019_09_24_104429_update_password_histories_change_tables', 298),
(521, '2019_09_24_125132_alter_log_scraper_add_column_category', 299),
(522, '2019_09_16_111826_create_purchase_product_supplier_table', 300),
(523, '2019_09_25_103856_create_purchase_order_customer', 300),
(524, '2019_09_25_103856_create_purchase_status_table', 300),
(525, '2019_09_25_103858_alter_purchase_table_add_column_purchase_status_id', 300),
(526, '2019_09_25_103859_alter_purchase_product_add_column_order_product_id', 300),
(527, '2019_09_25_123846_create_sku_formats_table', 300),
(529, '2019_09_26_172822_alter_log_scraper_add_column_raw', 301),
(530, '2019_09_30_155315_create_log_excel_imports_table', 302),
(531, '2019_09_30_183008_create_supplier_category_counts_table', 303),
(532, '2019_09_29_123847_create_erp_lead_status_table', 304),
(533, '2019_09_29_123848_create_erp_leads_table', 304),
(534, '2019_10_01_142624_create_supplier_brand_counts_table', 305),
(535, '2019_10_01_152631_update_supplier_brand_count_table', 306),
(536, '2019_10_04_105949_alter_log_scraper_add_original_sku', 307),
(537, '2019_09_29_172620_create_developer_task_comments_table', 308),
(538, '2019_09_29_172858_add_createdby_to_developer_tasks_table', 308),
(539, '2019_09_29_201428_add_task_type_id_to_developers_table', 309),
(540, '2019_10_03_111826_create_page_notes_categories_table', 310),
(541, '2019_10_03_111827_alter_page_notes_add_category_id_table', 310),
(542, '2019_10_05_115329_update_vendors_table', 311),
(543, '2019_10_06_151303_update_document_tables', 312),
(544, '2019_10_03_154557_create_excel_importers_table', 313),
(545, '2019_10_03_154609_create_excel_importer_details_table', 313),
(546, '2019_10_05_024053_update_excel_importer_table', 313),
(547, '2019_10_08_013641_update_excel_importer_tables', 313),
(548, '2019_10_10_110957_update_vendor_table', 314),
(549, '2019_10_10_120342_update_supplier_table', 314),
(550, '2019_10_06_030629_create_task_attachments_table', 315),
(551, '2019_10_10_143914_create_product_quickshell_groups_table', 315),
(552, '2019_10_11_000000_create_product_location_history_table', 316),
(553, '2019_10_11_000001_alter_table_instructions', 316),
(554, '2019_10_10_220806_alter_chat_messages_add_columns_is_delivered_is_read', 317),
(555, '2019_10_12_000000_create_courier_table', 318),
(556, '2019_10_12_000000_create_product_location_table', 318),
(557, '2019_10_13_000000_create_product_disptach_table', 319),
(558, '2019_10_13_113036_create_product_quicksell_groups', 320),
(559, '2019_10_13_130441_update_products_table', 320),
(560, '2019_10_14_020213_update_supplier_brand_counts_table', 321),
(561, '2019_10_14_094002_create_supplier_brand_count_histories_table', 321),
(562, '2019_10_14_122114_create_quick_sell_groups_table', 322),
(563, '2019_10_14_122534_update_product_table', 322),
(564, '2019_10_16_130741_create_document_send_histories_table', 323),
(565, '2019_10_18_061215_alter_erp_leads_add_column_brand_segment_and_gender', 324),
(566, '2019_10_19_140553_create_product_templates_table', 325),
(567, '2019_10_17_121121_create_old_categories_table', 326),
(568, '2019_10_17_135959_update_old_table', 326),
(569, '2019_10_17_160036_update_chat_messages_table', 326),
(570, '2019_10_19_023403_create_old_payments_table', 326),
(571, '2019_10_19_041754_create_old_remarks_table', 326),
(572, '2019_10_20_125122_update_quick_sell_groups_table', 327),
(573, '2019_10_20_151020_update_quick_sell_groups_tables', 328),
(574, '2019_09_12_181325_create_activities_table', 329),
(575, '2019_09_12_181325_create_attachments_table', 329),
(576, '2019_09_12_181325_create_books_table', 329),
(577, '2019_09_12_181325_create_bookshelves_books_table', 329),
(578, '2019_09_12_181325_create_bookshelves_table', 329),
(579, '2019_09_12_181325_create_chapters_table', 329),
(580, '2019_09_12_181325_create_comments_table', 329),
(581, '2019_09_12_181325_create_entity_permissions_table', 329),
(582, '2019_09_12_181325_create_images_table', 329),
(583, '2019_09_12_181325_create_joint_permissions_table', 329),
(584, '2019_09_12_181325_create_page_revisions_table', 329),
(585, '2019_09_12_181325_create_pages_table', 329),
(586, '2019_09_12_181325_create_search_terms_table', 329),
(587, '2019_09_12_181325_create_tags_table', 329),
(588, '2019_09_12_181325_create_views_table', 329),
(589, '2019_09_12_181326_add_foreign_keys_to_bookshelves_books_table', 329),
(590, '2019_10_23_221950_alter_instructions_table_add_product_id', 330),
(591, '2019_10_23_162531_update_resource_images_table', 331),
(592, '2019_11_01_140553_create_templates_table', 332),
(593, '2019_10_30_085918_create_messsage_applications_table', 333),
(594, '2019_10_30_151141_update_chat_message_tables', 333),
(595, '2019_11_03_093226_update_hash_tags_tables', 334),
(596, '2019_11_03_095341_update_instagram_posts_comments_table', 334),
(597, '2019_11_03_102356_create_priorities_table', 334),
(598, '2019_11_03_164221_update_old_table', 335),
(599, '2019_11_03_195612_create_sku_color_references', 336),
(600, '2019_11_04_220928_alter_templates_table_add_no_of_images', 337),
(601, '2019_11_03_160642_update_hash_tags_tables', 338),
(602, '2019_11_04_110938_update_instagram_posts_table', 338),
(603, '2019_11_06_063057_alter_product_templates_table_drop_product_id_foreign', 339),
(604, '2019_11_06_063251_alter_product_templates_table_change_product_id', 339),
(605, '2019_11_07_113051_create_im_queues_table', 340),
(606, '2019_11_08_225617_create_whats_app_configs_table', 341),
(607, '2019_11_08_225729_create_marketing_platforms_table', 341),
(608, '2019_11_08_225814_create_customer_marketing_platforms_table', 341),
(609, '2019_10_17_160036_update_chat_messages_table_add_column_old_id', 342),
(610, '2019_10_30_151141_update_chat_message_tables_add_column_message_application_id', 343),
(611, '2019_11_03_164221_update_old_table_add_column_account_name', 344),
(612, '2019_11_10_100037_alter_sku_format_add_sku_format_without_color', 344),
(613, '2019_11_10_112446_update_sku_formats_add_sku_example_column_table', 345);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monetary_accounts`
--

CREATE TABLE `monetary_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `currency` int(11) NOT NULL DEFAULT 1,
  `amount` decimal(13,4) DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `short_note` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `negative_reviews`
--

CREATE TABLE `negative_reviews` (
  `id` int(11) NOT NULL,
  `website` varchar(191) NOT NULL,
  `brand` varchar(191) NOT NULL,
  `review_url` varchar(400) NOT NULL,
  `username` varchar(191) NOT NULL,
  `title` varchar(200) NOT NULL,
  `body` mediumtext NOT NULL,
  `stars` int(11) NOT NULL,
  `reply` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sale_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `task_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message_id` int(11) DEFAULT NULL,
  `sent_to` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isread` tinyint(1) NOT NULL DEFAULT 0,
  `reminder` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_queues`
--

CREATE TABLE `notification_queues` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sale_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `task_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent_to` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int(11) DEFAULT NULL,
  `time_to_add` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `message_id` int(11) DEFAULT NULL,
  `reminder` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `old`
--

CREATE TABLE `old` (
  `serial_no` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int(11) NOT NULL,
  `commitment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `communication` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','disputed','settled','paid','closed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_blocked` int(11) NOT NULL DEFAULT 0,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gst` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_iban` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_swift` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `pending_payment` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_payable` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `old_categories`
--

CREATE TABLE `old_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `old_categories`
--

INSERT INTO `old_categories` (`id`, `category`, `created_at`, `updated_at`) VALUES
(1, 'Staff', '2019-11-02 10:59:22', '2019-11-02 10:59:22');

-- --------------------------------------------------------

--
-- Table structure for table `old_incomings`
--

CREATE TABLE `old_incomings` (
  `serial_no` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int(11) NOT NULL,
  `commitment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `communication` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','disputed','settled','paid','closed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `old_payments`
--

CREATE TABLE `old_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `old_id` int(11) NOT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` date NOT NULL,
  `paid_date` date NOT NULL,
  `pending_amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid_amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_provided` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_hour` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `other` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `old_remarks`
--

CREATE TABLE `old_remarks` (
  `id` int(10) UNSIGNED NOT NULL,
  `old_id` int(11) NOT NULL,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remark` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `order_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `awb` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_detail` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `advance_detail` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `advance_date` date DEFAULT NULL,
  `balance_amount` int(11) DEFAULT NULL,
  `sales_person` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_phone_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_delivery` date DEFAULT NULL,
  `estimated_delivery_date` date DEFAULT NULL,
  `note_if_any` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assign_status` int(2) DEFAULT NULL,
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `refund_answer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refund_answer_date` datetime DEFAULT NULL,
  `auto_messaged` int(11) NOT NULL DEFAULT 0,
  `auto_messaged_date` timestamp NULL DEFAULT NULL,
  `auto_emailed` tinyint(4) NOT NULL DEFAULT 0,
  `auto_emailed_date` timestamp NULL DEFAULT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_priority` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `whatsapp_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` int(10) UNSIGNED DEFAULT 1,
  `purchase_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipment_date` datetime DEFAULT NULL,
  `reschedule_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `purchase_id` int(10) UNSIGNED DEFAULT NULL,
  `batch_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_reports`
--

CREATE TABLE `order_reports` (
  `id` int(11) NOT NULL,
  `status_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `completion_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `order_statuses`
--

CREATE TABLE `order_statuses` (
  `id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `order_statuses`
--

INSERT INTO `order_statuses` (`id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'test action', '2018-12-10 02:34:38', '2018-12-10 02:34:38'),
(2, 'test for order report', '2018-12-10 02:35:29', '2018-12-10 02:35:29'),
(3, 'Order Product', '2018-12-10 04:11:58', '2018-12-10 04:11:58');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `book_id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `html` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `restricted` tinyint(1) NOT NULL DEFAULT 0,
  `draft` tinyint(1) NOT NULL DEFAULT 0,
  `markdown` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `revision_count` int(11) NOT NULL,
  `template` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `page_notes`
--

CREATE TABLE `page_notes` (
  `id` int(10) UNSIGNED NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `page_notes_categories`
--

CREATE TABLE `page_notes_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `page_revisions`
--

CREATE TABLE `page_revisions` (
  `id` int(10) UNSIGNED NOT NULL,
  `page_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `html` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `book_slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'version',
  `markdown` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `revision_number` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `page_screenshots`
--

CREATE TABLE `page_screenshots` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_link` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `passwords`
--

CREATE TABLE `passwords` (
  `id` int(10) UNSIGNED NOT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `registered_with` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_histories`
--

CREATE TABLE `password_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `password_id` int(11) NOT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `registered_with` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `people_names`
--

CREATE TABLE `people_names` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `race` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `table_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `table_name`, `route`) VALUES
(1, 'role-list', 'web', '2018-08-08 02:22:38', '2018-08-08 02:22:38', '', 'roles-list'),
(2, 'role-create', 'web', '2018-08-08 02:22:38', '2018-08-08 02:22:38', '', 'roles-create'),
(3, 'role-edit', 'web', '2018-08-08 02:22:38', '2018-08-08 02:22:38', '', 'roles-edit'),
(4, 'role-delete', 'web', '2018-08-08 02:22:38', '2018-08-08 02:22:38', '', 'roles-delete'),
(5, 'product-list', 'web', '2018-08-08 02:22:38', '2018-08-08 02:22:38', '', 'products-list'),
(6, 'product-create', 'web', '2018-08-08 02:22:38', '2018-08-08 02:22:38', '', 'products-create'),
(7, 'product-edit', 'web', '2018-08-08 02:22:38', '2018-08-08 02:22:38', '', 'products-edit'),
(8, 'product-delete', 'web', '2018-08-08 02:22:38', '2018-08-08 02:22:38', '', 'products-delete'),
(9, 'user-list', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'users-list'),
(10, 'user-create', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'users-create'),
(11, 'user-edit', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'users-edit'),
(12, 'user-delete', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'users-delete'),
(13, 'selection-list', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productselection-list'),
(14, 'selection-create', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productselection-create'),
(15, 'selection-edit', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productselection-edit'),
(16, 'selection-delete', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productselection-delete'),
(17, 'searcher-list', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productsearcher-list'),
(18, 'searcher-create', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productsearcher-create'),
(19, 'searcher-edit', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productsearcher-edit'),
(20, 'searcher-delete', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productsearcher-delete'),
(21, 'setting-list', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'settings-list'),
(22, 'setting-create', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'settings-create'),
(23, 'supervisor-list', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productsupervisor-list'),
(24, 'supervisor-edit', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productsupervisor-edit'),
(25, 'category-edit', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'category-edit'),
(26, 'imagecropper-list', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productimagecropper-list'),
(27, 'imagecropper-create', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productimagecropper-create'),
(28, 'imagecropper-edit', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productimagecropper-edit'),
(29, 'imagecropper-delete', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productimagecropper-delete'),
(30, 'lister-list', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productlister-list'),
(31, 'lister-edit', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productlister-edit'),
(32, 'approver-list', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productapprover-list'),
(33, 'approver-edit', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productapprover-edit'),
(34, 'inventory-list', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productinventory-list'),
(35, 'inventory-edit', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productinventory-edit'),
(36, 'attribute-list', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productattribute-list'),
(37, 'attribute-create', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productattribute-create'),
(38, 'attribute-edit', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productattribute-edit'),
(39, 'attribute-delete', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'productattribute-delete'),
(40, 'view-activity', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'activity-list'),
(41, 'brand-edit', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'brand-tagged-edit'),
(42, 'lead-create', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'leads-create'),
(43, 'lead-edit', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'leads-edit'),
(44, 'lead-delete', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'leads-delete'),
(45, 'crm', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', NULL),
(46, 'order-view', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'order-view'),
(47, 'order-create', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'order-create'),
(48, 'order-edit', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'order-edit'),
(49, 'order-delete', 'web', '2018-08-08 03:58:15', '2018-08-08 03:58:15', '', 'order-delete'),
(50, 'admin', 'web', '2018-08-08 14:28:15', '2018-08-08 14:28:15', '', NULL),
(51, 'reply-edit', 'web', NULL, NULL, '', 'reply-edit'),
(52, 'purchase', 'web', NULL, NULL, '', 'purchases-list'),
(54, 'social-create', 'web', NULL, NULL, '', 'social-create'),
(55, 'social-manage', 'web', NULL, NULL, '', 'social-manage'),
(58, 'social-view', 'web', NULL, NULL, '', 'social-view'),
(60, 'developer-tasks', 'web', NULL, NULL, '', 'development-tasks'),
(61, 'developer-all', 'web', NULL, NULL, '', 'development-list'),
(62, 'voucher', 'web', NULL, NULL, '', 'voucher-list'),
(63, 'review-view', 'web', NULL, NULL, '', 'review-list'),
(64, 'private-viewing', 'web', NULL, NULL, '', 'private-viewing'),
(65, 'delivery-approval', 'web', NULL, NULL, '', 'deliveryapproval-list'),
(66, 'product-lister', 'web', NULL, NULL, '', 'products-listing'),
(67, 'vendor-all', 'web', NULL, NULL, '', 'vendor-list'),
(68, 'customer', 'web', NULL, NULL, '', 'customers-list'),
(69, 'crop-approval', 'web', NULL, NULL, '', 'products-crop-approval-confirmation'),
(70, 'crop-sequence', 'web', NULL, NULL, '', NULL),
(71, 'approved-listing', 'web', NULL, NULL, '', 'products-final'),
(72, 'product-affiliate', 'web', NULL, NULL, '', 'products-affiliate'),
(73, 'social-email', 'web', NULL, NULL, '', 'manageMailChimp-list'),
(74, 'facebook', 'web', NULL, NULL, '', 'facebook-list'),
(75, 'instagram', 'web', NULL, NULL, '', 'instagram-list'),
(76, 'sitejabber', 'web', NULL, NULL, '', 'sitejabber-accounts'),
(77, 'pinterest', 'web', NULL, NULL, '', 'pinterest-accounts'),
(78, 'rejected-listing', 'web', NULL, NULL, '', 'products-rejected'),
(79, 'instagram-manual-comment', 'web', NULL, NULL, '', 'instagram-auto-comment-history'),
(80, 'lawyer-all', 'web', NULL, NULL, '', 'lawyer-list'),
(81, 'case-all', 'web', NULL, NULL, '', 'case-list'),
(82, 'seo-analytics', 'web', NULL, NULL, '', 'seo-analytics'),
(83, 'old', 'web', NULL, NULL, '', 'old-list'),
(84, 'old-incoming', 'web', NULL, NULL, '', 'old-incomings-list'),
(85, 'blogger-all', 'web', NULL, NULL, '', 'blogger-list'),
(86, 'mailchimp', 'web', NULL, NULL, '', 'v1-auth'),
(87, 'hubstaff', 'web', NULL, NULL, '', 'hubstaff-auth'),
(91, 'Act', '', NULL, NULL, '', 'activity-list'),
(92, 'Voucher', '', NULL, NULL, '', 'voucher-list'),
(93, 'Vendor', '', NULL, NULL, '', 'vendor-list'),
(94, 'productselection', '', NULL, NULL, '', 'productselection-list'),
(95, 'seo-filter', '', NULL, NULL, '', 'seo-filter'),
(96, 'blogger-email-all', '', NULL, NULL, '', 'blogger-email-list'),
(97, 'blogger-payments', '', NULL, NULL, '', 'blogger-payments'),
(99, 'hubstaff-getusers', '', NULL, NULL, '', 'get-users-list'),
(100, 'hubstaff-getuserfromid', '', NULL, NULL, '', 'get-user-from-id-list'),
(101, 'hubstaff-projects', '', NULL, NULL, '', 'v1-projects'),
(102, 'passwords', '', NULL, NULL, '', 'passwords-list'),
(103, 'documents', '', NULL, NULL, '', 'documents-list'),
(104, 'cold-leads', '', NULL, NULL, '', 'cold-leads-list'),
(105, 'crop-approved', '', NULL, NULL, '', 'crop-approved'),
(106, 'products-auto-cropped', '', NULL, NULL, '', 'products-auto-cropped'),
(107, 'products-crop-issue-summary', '', NULL, NULL, '', 'products-crop-issue-summary'),
(108, 'products-rejected-auto-cropped', '', NULL, NULL, '', 'products-rejected-auto-cropped'),
(109, 'order-cropped-images', '', NULL, NULL, '', 'order-cropped-images-list'),
(110, 'products-stats', '', NULL, NULL, '', 'products-stats'),
(111, 'scrap-auto-rejected-stat', '', NULL, NULL, '', 'scrap-auto-rejected-stat'),
(112, 'listing-payments-list', '', NULL, NULL, '', 'listing-payments-list'),
(113, 'scrap-statistics', '', NULL, NULL, '', 'scrap-statistics'),
(114, 'scrap-activity', '', NULL, NULL, '', 'scrap-activity'),
(115, 'scrap-products', '', NULL, NULL, '', 'scrap-products'),
(116, 'purchaseGrid-list', '', NULL, NULL, '', 'purchaseGrid-list'),
(117, 'purchase-calendar', '', NULL, NULL, '', 'purchase-calendar'),
(118, 'purchaseGrid-canceled-refunded', '', NULL, NULL, '', 'purchaseGrid-canceled-refunded'),
(119, 'purchaseGrid-ordered', '', NULL, NULL, '', 'purchaseGrid-ordered'),
(120, 'purchaseGrid-delivered', '', NULL, NULL, '', 'purchaseGrid-delivered'),
(121, 'supplier-list', '', NULL, NULL, '', 'supplier-list'),
(122, 'scrap-sales', '', NULL, NULL, '', 'scrap-sales'),
(123, 'scrap-designer', '', NULL, NULL, '', 'scrap-designer'),
(124, 'scrap-gmail', '', NULL, NULL, '', 'scrap-gmail'),
(125, 'scrap-google-images', '', NULL, NULL, '', 'scrap-google-images'),
(126, 'social-tags-list', '', NULL, NULL, '', 'social-tags-list'),
(127, 'scrap-dubbizle', '', NULL, NULL, '', 'scrap-dubbizle'),
(128, 'imported-leads', '', NULL, NULL, '', 'imported-leads'),
(129, 'instruction-list', '', NULL, NULL, '', 'instruction-list'),
(130, 'keyword-instruction', '', NULL, NULL, '', 'keyword-instruction-list'),
(131, 'leads-list', '', NULL, NULL, '', 'leads-list'),
(132, 'leads-imageGrid', '', NULL, NULL, '', 'leads-imageGrid'),
(133, 'refund-list', '', NULL, NULL, '', 'refund-list'),
(134, 'order-list', '', NULL, NULL, '', 'order-list'),
(135, 'complaint-list', '', NULL, NULL, '', 'complaint-list'),
(136, 'order-missed-calls', '', NULL, NULL, '', 'order-missed-calls'),
(137, 'stock-private-viewing', '', NULL, NULL, '', 'stock-viewing'),
(138, 'category-messages-bulk-messages', '', NULL, NULL, '', 'category-messages-bulk-messages'),
(139, 'category-messages-category', '', NULL, NULL, '', 'category-messages-category'),
(140, 'category-messages-keyword', '', NULL, NULL, '', 'category-messages-keyword'),
(141, 'broadcast-list', '', NULL, NULL, '', 'broadcast-list'),
(142, 'broadcast-images', '', NULL, NULL, '', 'broadcast-images'),
(143, 'broadcast-calendar', '', NULL, NULL, '', 'broadcast-calendar'),
(144, 'vendor-product', '', NULL, NULL, '', 'vendor-product'),
(145, 'users-logins', '', NULL, NULL, '', 'users-logins'),
(146, 'permissions-list', '', NULL, NULL, '', 'permissions-list'),
(147, 'permissions-create', '', NULL, NULL, '', 'permissions-create'),
(148, 'permissions-grandaccess-users', '', NULL, NULL, '', 'permissions-users'),
(149, 'graph-user', '', NULL, NULL, '', 'graph-user'),
(150, 'benchmark-create', '', NULL, NULL, '', 'benchmark-create'),
(151, 'product-listing-users', '', NULL, NULL, '', 'product-users'),
(152, 'pre-accounts-list', '', NULL, NULL, '', 'pre-accounts-list'),
(153, 'instagram-post-media', '', NULL, NULL, '', 'instagram-post-media'),
(154, 'instagram-media-schedules', '', NULL, NULL, '', 'instagram-schedules'),
(155, 'scrap-facebook', '', NULL, NULL, '', 'scrap-facebook'),
(156, 'scrap-facebook-group', '', NULL, NULL, '', 'scrap-group'),
(157, 'social-get-post-page', '', NULL, NULL, '', 'social-page'),
(158, 'social-ad-report', '', NULL, NULL, '', 'social-report'),
(159, 'social-ad-adset-create', '', NULL, NULL, '', 'social-create'),
(160, 'social-ad-schedules', '', NULL, NULL, '', 'social-schedules'),
(161, 'sitejabber-accounts', '', NULL, NULL, '', 'sitejabber-accounts'),
(162, 'quick-reply-list', '', NULL, NULL, '', 'quick-reply-list'),
(163, 'images-grid', '', NULL, NULL, '', 'images-grid'),
(164, 'images-grid-approvedImages', '', NULL, NULL, '', 'images-approvedImages'),
(165, 'images-grid-finalApproval', '', NULL, NULL, '', 'images-finalApproval'),
(166, 'display-back-link-details', '', NULL, NULL, '', 'display-back-link-details'),
(167, 'display-broken-link-details', '', NULL, NULL, '', 'display-broken-link-details'),
(168, 'display-analytics-data', '', NULL, NULL, '', 'display-analytics-data'),
(169, 'display-analytics-customer-behaviour', '', NULL, NULL, '', 'display-analytics-customer-behaviour'),
(170, 'se-ranking-sites', '', NULL, NULL, '', 'se-ranking-sites'),
(171, 'display-articles', '', NULL, NULL, '', 'display-articles'),
(172, 'supplier-scrapping-info', '', NULL, NULL, '', 'supplier-scrapping-info-list'),
(173, 'dev-task-planner', '', NULL, NULL, '', 'dev-task-planner-list'),
(174, 'manageMailChimp', '', NULL, NULL, '', 'manageMailChimp-list'),
(175, 'development-issue-create', '', NULL, NULL, '', 'development-create'),
(176, 'category-list', '', NULL, NULL, '', 'category-list'),
(177, 'category-references', '', NULL, NULL, '', 'category-references'),
(178, 'brand', '', NULL, NULL, '', 'brand-list'),
(179, 'reply', '', NULL, NULL, '', 'reply-list'),
(180, 'autoreply', '', NULL, NULL, '', 'autoreply-list'),
(181, 'customers-post-show', '', NULL, NULL, '', 'customers-post-show'),
(182, 'customer-edit', '', NULL, NULL, '', 'customer-edit'),
(183, 'customer-create', '', NULL, NULL, '', 'customer-create'),
(184, 'customer-fetch', '', NULL, NULL, '', 'customer-fetch'),
(185, 'customers-load', '', NULL, NULL, '', 'customers-load-list'),
(186, 'customers-post-show', '', NULL, NULL, '', 'customers-post-show'),
(187, 'products-final', '', NULL, NULL, '', 'products-final'),
(188, 'products-quickDownload', '', NULL, NULL, '', 'products-quickDownload'),
(189, 'order-cropped-images-list', '', '2019-09-22 12:38:14', '2019-09-22 12:38:14', '', 'order-cropped-images-list'),
(190, 'supplier category-count', '', '2019-10-09 14:08:28', '2019-10-09 14:08:28', '', 'supplier-categorycount'),
(191, 'supplier brand-count', '', '2019-10-09 14:09:02', '2019-10-09 14:09:02', '', 'supplier-brandcount');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(26, 6),
(27, 6),
(28, 6),
(29, 6),
(60, 20),
(61, 20),
(69, 6),
(69, 28),
(70, 6),
(70, 31),
(105, 6),
(106, 6),
(107, 6),
(108, 6),
(109, 6),
(189, 6),
(190, 6),
(191, 6);

-- --------------------------------------------------------

--
-- Table structure for table `permission_user`
--

CREATE TABLE `permission_user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `picture_colors`
--

CREATE TABLE `picture_colors` (
  `id` int(10) UNSIGNED NOT NULL,
  `image_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `picked_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `picked_color` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pinterest_boards`
--

CREATE TABLE `pinterest_boards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pinterest_users_id` bigint(20) UNSIGNED NOT NULL,
  `board_id` bigint(20) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pinterest_users`
--

CREATE TABLE `pinterest_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pinterest_id` bigint(20) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pre_accounts`
--

CREATE TABLE `pre_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instagram` int(11) NOT NULL DEFAULT 0,
  `facebook` int(11) NOT NULL DEFAULT 0,
  `pinterest` int(11) NOT NULL DEFAULT 0,
  `twitter` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `price_comparison`
--

CREATE TABLE `price_comparison` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `price_comparison_site_id` bigint(20) NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double(10,2) NOT NULL,
  `shipping` double(10,2) NOT NULL,
  `checkout_price` double(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `price_comparison_site`
--

CREATE TABLE `price_comparison_site` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_cat_shoes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_cat_bags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_cat_clothing` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_cat_accessories` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_brands` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `priorities`
--

CREATE TABLE `priorities` (
  `id` int(10) UNSIGNED NOT NULL,
  `keyword` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `private_views`
--

CREATE TABLE `private_views` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `assigned_user_id` int(10) UNSIGNED DEFAULT NULL,
  `order_product_id` int(10) UNSIGNED DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `status` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `private_view_products`
--

CREATE TABLE `private_view_products` (
  `id` int(11) NOT NULL,
  `private_view_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `status_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `short_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `stage` tinyint(1) NOT NULL DEFAULT 1,
  `measurement_size_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lmeasurement` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hmeasurement` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dmeasurement` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size_value` int(4) DEFAULT NULL,
  `composition` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `made_in` varchar(191) CHARACTER SET utf8 DEFAULT NULL,
  `brand` int(11) DEFAULT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_inr` double DEFAULT NULL,
  `price_special` double DEFAULT NULL,
  `price_special_offer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `euro_to_inr` int(11) DEFAULT NULL,
  `percentage` int(11) DEFAULT NULL,
  `factor` double DEFAULT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `dnf` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isApproved` tinyint(1) DEFAULT 0,
  `rejected_note` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isUploaded` tinyint(1) NOT NULL DEFAULT 0,
  `is_uploaded_date` datetime DEFAULT NULL,
  `isFinal` tinyint(1) NOT NULL DEFAULT 0,
  `isListed` tinyint(1) NOT NULL DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `stock` int(4) NOT NULL DEFAULT 0,
  `is_on_sale` tinyint(1) NOT NULL DEFAULT 0,
  `purchase_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_link` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_link` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_link` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_attributer` int(10) UNSIGNED DEFAULT NULL,
  `last_imagecropper` int(10) UNSIGNED DEFAULT NULL,
  `last_selector` int(10) UNSIGNED DEFAULT NULL,
  `last_searcher` int(10) UNSIGNED DEFAULT NULL,
  `quick_product` tinyint(4) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `import_date` datetime DEFAULT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_scraped` tinyint(1) NOT NULL,
  `is_image_processed` tinyint(1) NOT NULL DEFAULT 0,
  `is_without_image` tinyint(1) NOT NULL DEFAULT 0,
  `is_price_different` tinyint(1) NOT NULL DEFAULT 0,
  `crop_count` int(11) NOT NULL DEFAULT 0,
  `is_crop_rejected` tinyint(4) NOT NULL DEFAULT 0,
  `crop_remark` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_crop_approved` tinyint(4) NOT NULL DEFAULT 0,
  `is_farfetched` int(11) NOT NULL DEFAULT 0,
  `approved_by` int(11) DEFAULT NULL,
  `reject_approved_by` int(11) DEFAULT NULL,
  `was_crop_rejected` tinyint(1) NOT NULL DEFAULT 0,
  `crop_rejected_by` int(11) DEFAULT NULL,
  `crop_approved_by` int(11) DEFAULT NULL,
  `is_being_cropped` tinyint(1) NOT NULL DEFAULT 0,
  `is_crop_ordered` tinyint(1) NOT NULL DEFAULT 0,
  `listing_remark` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_listing_rejected` tinyint(1) NOT NULL DEFAULT 0,
  `listing_rejected_by` int(11) DEFAULT NULL,
  `listing_rejected_on` date DEFAULT NULL,
  `is_corrected` tinyint(1) NOT NULL DEFAULT 0,
  `is_script_corrected` tinyint(1) NOT NULL DEFAULT 0,
  `is_authorized` tinyint(1) NOT NULL DEFAULT 0,
  `authorized_by` int(11) DEFAULT NULL,
  `crop_ordered_by` int(11) DEFAULT NULL,
  `is_crop_being_verified` tinyint(1) NOT NULL DEFAULT 0,
  `crop_approved_at` datetime DEFAULT NULL,
  `crop_rejected_at` datetime DEFAULT NULL,
  `crop_ordered_at` datetime DEFAULT NULL,
  `listing_approved_at` datetime DEFAULT NULL,
  `is_order_rejected` tinyint(1) NOT NULL DEFAULT 0,
  `manual_crop` tinyint(1) NOT NULL DEFAULT 0,
  `is_manual_cropped` tinyint(1) NOT NULL DEFAULT 0,
  `manual_cropped_by` int(11) DEFAULT NULL,
  `manual_cropped_at` datetime DEFAULT NULL,
  `is_titlecased` tinyint(1) NOT NULL DEFAULT 0,
  `is_listing_rejected_automatically` tinyint(1) NOT NULL DEFAULT 0,
  `was_auto_rejected` tinyint(1) NOT NULL DEFAULT 0,
  `is_being_ordered` tinyint(1) NOT NULL DEFAULT 0,
  `instruction_completed_at` datetime DEFAULT NULL,
  `is_auto_processing_failed` tinyint(1) NOT NULL DEFAULT 0,
  `is_crop_skipped` tinyint(1) NOT NULL DEFAULT 0,
  `cropped_at` datetime DEFAULT NULL,
  `is_enhanced` tinyint(1) NOT NULL DEFAULT 0,
  `is_pending` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_dispatch`
--

CREATE TABLE `product_dispatch` (
  `id` int(10) UNSIGNED NOT NULL,
  `modeof_shipment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `awb` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eta` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_time` datetime NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_location`
--

CREATE TABLE `product_location` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_location`
--

INSERT INTO `product_location` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Mulund', '2019-10-10 14:30:00', '2019-10-10 14:30:00'),
(2, 'Jogeshwari', '2019-10-10 14:30:00', '2019-10-10 14:30:00'),
(3, 'Malad', '2019-10-10 14:30:00', '2019-10-10 14:30:00'),
(4, 'Pune', '2019-10-10 14:30:00', '2019-10-10 14:30:00'),
(5, 'Dubai', '2019-10-10 14:30:00', '2019-10-10 14:30:00'),
(6, 'Customs', '2019-10-10 14:30:00', '2019-10-10 14:30:00'),
(7, 'Mumbai', '2019-10-10 14:30:00', '2019-10-10 14:30:00'),
(8, 'Rajkot', '2019-10-11 12:59:14', '2019-10-11 12:59:14'),
(9, 'Delhi', '2019-10-12 11:53:58', '2019-10-12 11:53:58'),
(11, 'Jogeshwari 1', '2019-10-14 12:10:59', '2019-10-14 12:10:59'),
(12, 'Kandivali', '2019-10-20 05:40:33', '2019-10-20 05:40:33');

-- --------------------------------------------------------

--
-- Table structure for table `product_location_history`
--

CREATE TABLE `product_location_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `location_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `courier_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `courier_details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_time` datetime NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_quicksell_groups`
--

CREATE TABLE `product_quicksell_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `quicksell_group_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_references`
--

CREATE TABLE `product_references` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_sizes`
--

CREATE TABLE `product_sizes` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `size` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_status`
--

CREATE TABLE `product_status` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_suppliers`
--

CREATE TABLE `product_suppliers` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_discounted` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `composition` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_templates`
--

CREATE TABLE `product_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `template_no` int(11) NOT NULL DEFAULT 0,
  `product_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `currency` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discounted_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `product_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'NULL',
  `is_processed` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proxies`
--

CREATE TABLE `proxies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `port` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reliability` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `purchase_handler` int(11) NOT NULL,
  `supplier_id` int(10) UNSIGNED DEFAULT NULL,
  `agent_id` int(10) UNSIGNED DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `purchase_status_id` int(10) UNSIGNED DEFAULT NULL,
  `supplier_phone` varchar(255) DEFAULT NULL,
  `whatsapp_number` varchar(255) NOT NULL DEFAULT '919152731486',
  `transaction_id` varchar(191) DEFAULT NULL,
  `transaction_date` datetime DEFAULT NULL,
  `transaction_amount` varchar(191) DEFAULT NULL,
  `bill_number` varchar(255) DEFAULT NULL,
  `shipper` varchar(191) DEFAULT NULL,
  `shipment_status` varchar(191) DEFAULT NULL,
  `shipment_cost` varchar(191) DEFAULT NULL,
  `shipment_date` datetime DEFAULT NULL,
  `proforma_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `proforma_id` varchar(191) DEFAULT NULL,
  `proforma_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_discounts`
--

CREATE TABLE `purchase_discounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `percentage` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_customer`
--

CREATE TABLE `purchase_order_customer` (
  `id` int(10) UNSIGNED NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_products`
--

CREATE TABLE `purchase_products` (
  `id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_product_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_product_supplier`
--

CREATE TABLE `purchase_product_supplier` (
  `product_id` int(10) UNSIGNED NOT NULL,
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `chat_message_id` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_status`
--

CREATE TABLE `purchase_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `push_notifications`
--

CREATE TABLE `push_notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sent_to` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_id` int(11) DEFAULT NULL,
  `isread` tinyint(1) NOT NULL DEFAULT 0,
  `reminder` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quick_replies`
--

CREATE TABLE `quick_replies` (
  `id` int(10) UNSIGNED NOT NULL,
  `text` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quick_sell_groups`
--

CREATE TABLE `quick_sell_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `group` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suppliers` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brands` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `special_price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categories` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `chq_number` varchar(255) DEFAULT NULL,
  `awb` varchar(255) DEFAULT NULL,
  `payment` varchar(255) DEFAULT NULL,
  `date_of_refund` timestamp NULL DEFAULT NULL,
  `date_of_issue` timestamp NULL DEFAULT NULL,
  `details` longtext DEFAULT NULL,
  `dispatch_date` timestamp NULL DEFAULT NULL,
  `date_of_request` timestamp NULL DEFAULT NULL,
  `credited` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rejected_leads`
--

CREATE TABLE `rejected_leads` (
  `id` int(10) UNSIGNED NOT NULL,
  `identifier` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `platform` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'instagram',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `remarks`
--

CREATE TABLE `remarks` (
  `id` int(10) NOT NULL,
  `taskid` int(10) DEFAULT NULL,
  `module_type` varchar(255) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `user_name` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `delete_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `id` int(11) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `reply` longtext NOT NULL,
  `model` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reply_categories`
--

CREATE TABLE `reply_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `resource_categories`
--

CREATE TABLE `resource_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `title` varchar(299) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(199) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resource_images`
--

CREATE TABLE `resource_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `cat_id` int(10) UNSIGNED NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image1` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image2` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `images` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_cat_id` int(11) NOT NULL,
  `is_pending` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `review_schedule_id` int(11) DEFAULT NULL,
  `account_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `review` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `is_approved` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `posted_date` datetime DEFAULT NULL,
  `platform` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `review_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review_schedules`
--

CREATE TABLE `review_schedules` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `posted_date` datetime DEFAULT NULL,
  `platform` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `review_count` int(11) DEFAULT NULL,
  `review_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(2, 'Admin', 'web', '2018-08-08 00:52:46', '2018-08-08 02:07:20'),
(4, 'Selectors', 'web', '2018-08-08 03:09:55', '2018-08-08 03:09:55'),
(5, 'Searchers', 'web', '2018-08-08 03:10:11', '2018-08-08 03:10:11'),
(6, 'Image Croppers', 'web', '2018-08-08 03:10:37', '2019-10-09 14:24:04'),
(7, 'Supervisors', 'web', '2018-08-08 03:11:07', '2018-08-08 03:11:07'),
(8, 'Listers', 'web', '2018-08-08 03:11:22', '2018-08-08 03:11:22'),
(9, 'Approvers', 'web', '2018-08-08 03:11:42', '2018-08-08 03:11:42'),
(10, 'Inventory', 'web', '2018-08-08 03:11:55', '2018-08-08 03:11:55'),
(11, 'Attribute', 'web', '2018-08-17 19:26:46', '2018-08-17 19:26:46'),
(12, 'Sales', 'web', '2018-08-22 17:15:23', '2018-08-22 17:15:23'),
(13, 'crm', 'web', '2018-10-08 22:07:08', '2018-10-08 22:07:08'),
(14, 'message', 'web', '2018-10-19 20:40:09', '2018-10-19 20:40:09'),
(15, 'Activity', 'web', '2018-10-25 17:41:49', '2018-10-25 17:41:49'),
(16, 'user', 'web', '2018-11-18 03:58:17', '2018-11-18 03:58:17'),
(17, 'Social Creator', 'web', '2018-12-27 20:07:37', '2018-12-27 20:07:37'),
(18, 'Social Manager', 'web', '2018-12-27 20:07:51', '2018-12-27 20:07:51'),
(19, 'HOD of CRM', 'web', '2018-12-28 22:01:13', '2018-12-28 22:01:13'),
(20, 'Developer', 'web', '2019-01-25 01:19:17', '2019-01-25 01:19:17'),
(21, 'Office Boy', 'web', '2019-03-15 23:26:10', '2019-06-02 02:27:34'),
(22, 'Review', 'web', '2019-04-12 02:02:38', '2019-04-12 02:02:38'),
(23, 'Delivery Coordinator', 'web', '2019-06-02 02:25:31', '2019-06-02 02:25:31'),
(24, 'Products Lister', 'web', '2019-06-06 15:43:00', '2019-06-06 15:43:00'),
(25, 'social-facebook-test', 'web', '2019-06-06 19:12:06', '2019-06-06 19:12:06'),
(26, 'Vendor', 'web', '2019-06-15 19:16:27', '2019-06-15 19:16:27'),
(27, 'Customer Care', 'web', '2019-06-18 03:01:21', '2019-06-18 03:01:21'),
(28, 'Crop Approval', 'web', '2019-06-20 17:29:22', '2019-06-20 17:29:22'),
(29, 'Stock Coordinator', 'web', '2019-06-22 18:23:44', '2019-06-22 18:23:44'),
(30, 'Carrier', 'web', '2019-06-23 00:13:24', '2019-06-23 00:13:24'),
(31, 'Crop Sequence', 'web', '2019-06-27 19:59:03', '2019-06-27 19:59:03'),
(32, 'Approved Listing', 'web', '2019-07-02 22:17:16', '2019-07-02 22:17:16'),
(33, 'Affiliate', 'web', '2019-07-03 23:53:43', '2019-07-03 23:53:43'),
(34, 'Instagram', 'web', '2019-07-16 16:36:53', '2019-07-16 16:36:53'),
(35, 'Rejected Listing', 'web', '2019-07-18 00:03:45', '2019-07-18 00:03:45'),
(36, 'InstagramBulkComment', 'web', '2019-07-22 18:42:02', '2019-07-22 18:42:02'),
(37, 'Legal', 'web', '2019-08-07 18:37:12', '2019-08-07 18:37:12'),
(38, 'Old Issues', 'web', '2019-08-07 18:37:29', '2019-08-07 18:37:29'),
(39, 'Blogger', 'web', '2019-08-07 18:37:45', '2019-08-07 18:37:45');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 2),
(1, 20),
(2, 2),
(2, 20),
(3, 2),
(3, 20),
(4, 2),
(4, 20),
(5, 2),
(5, 4),
(5, 5),
(5, 6),
(5, 7),
(5, 8),
(5, 9),
(5, 10),
(5, 11),
(5, 12),
(5, 20),
(6, 2),
(6, 9),
(6, 20),
(7, 2),
(7, 9),
(7, 20),
(8, 2),
(8, 9),
(8, 20),
(9, 2),
(9, 16),
(9, 20),
(10, 2),
(10, 16),
(10, 20),
(11, 2),
(11, 16),
(11, 20),
(12, 2),
(12, 20),
(13, 2),
(13, 4),
(13, 20),
(14, 2),
(14, 4),
(14, 20),
(15, 2),
(15, 4),
(15, 20),
(16, 2),
(16, 4),
(16, 20),
(17, 2),
(17, 5),
(17, 20),
(18, 2),
(18, 5),
(18, 20),
(19, 2),
(19, 5),
(19, 20),
(20, 2),
(20, 5),
(20, 20),
(21, 2),
(21, 20),
(22, 2),
(22, 20),
(23, 2),
(23, 7),
(23, 20),
(24, 2),
(24, 7),
(24, 20),
(25, 2),
(25, 20),
(26, 2),
(26, 6),
(26, 20),
(27, 2),
(27, 6),
(27, 20),
(28, 2),
(28, 6),
(28, 20),
(29, 2),
(29, 6),
(29, 20),
(30, 2),
(30, 8),
(30, 20),
(31, 2),
(31, 8),
(31, 20),
(32, 2),
(32, 9),
(32, 20),
(33, 2),
(33, 9),
(33, 20),
(34, 2),
(34, 10),
(34, 20),
(35, 2),
(35, 10),
(35, 20),
(36, 2),
(36, 7),
(36, 11),
(36, 20),
(37, 2),
(37, 7),
(37, 11),
(37, 20),
(38, 2),
(38, 7),
(38, 11),
(38, 20),
(39, 2),
(39, 7),
(39, 11),
(39, 20),
(40, 2),
(40, 15),
(40, 19),
(40, 20),
(41, 2),
(41, 19),
(41, 20),
(42, 2),
(42, 13),
(42, 14),
(42, 19),
(42, 20),
(43, 2),
(43, 13),
(43, 14),
(43, 19),
(43, 20),
(44, 2),
(44, 14),
(44, 19),
(44, 20),
(45, 2),
(45, 13),
(45, 14),
(45, 19),
(45, 20),
(46, 2),
(46, 13),
(46, 19),
(46, 20),
(47, 2),
(47, 13),
(47, 19),
(47, 20),
(48, 2),
(48, 13),
(48, 19),
(48, 20),
(49, 2),
(49, 19),
(49, 20),
(50, 2),
(50, 20),
(51, 2),
(51, 19),
(51, 20),
(52, 2),
(52, 19),
(52, 20),
(54, 2),
(54, 17),
(54, 18),
(54, 20),
(54, 25),
(54, 34),
(54, 36),
(55, 2),
(55, 18),
(55, 20),
(58, 2),
(58, 17),
(58, 18),
(58, 20),
(58, 25),
(60, 2),
(60, 20),
(61, 2),
(62, 2),
(62, 19),
(62, 21),
(63, 2),
(63, 22),
(64, 2),
(64, 21),
(64, 23),
(64, 29),
(64, 30),
(65, 2),
(65, 21),
(66, 2),
(66, 24),
(67, 2),
(67, 26),
(68, 2),
(68, 27),
(69, 2),
(69, 28),
(70, 2),
(70, 31),
(71, 2),
(71, 32),
(72, 2),
(72, 33),
(73, 2),
(74, 2),
(75, 2),
(75, 34),
(75, 36),
(76, 2),
(77, 2),
(78, 2),
(78, 35),
(79, 2),
(79, 36),
(80, 2),
(80, 37),
(81, 2),
(81, 37),
(82, 2),
(83, 2),
(83, 38),
(84, 2),
(84, 38),
(85, 2),
(85, 39),
(86, 2),
(87, 2);

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rude_words`
--

CREATE TABLE `rude_words` (
  `id` int(10) UNSIGNED NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `universal` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rude_words`
--

INSERT INTO `rude_words` (`id`, `value`, `created_at`, `updated_at`, `universal`) VALUES
(120, '4r5e', NULL, NULL, 0),
(121, '5h1t', NULL, '2018-06-06 09:07:36', 0),
(122, '5hit', NULL, NULL, 0),
(123, 'a55', NULL, NULL, 0),
(124, 'anal', NULL, NULL, 0),
(125, 'anus', NULL, NULL, 0),
(126, 'ar5e', NULL, NULL, 0),
(127, 'arrse', NULL, NULL, 0),
(128, 'arse', NULL, NULL, 0),
(129, 'ass', NULL, '2018-07-14 09:37:06', 0),
(130, 'ass-fucker', NULL, NULL, 0),
(131, 'asses', NULL, NULL, 0),
(132, 'assfucker', NULL, NULL, 0),
(133, 'assfukka', NULL, NULL, 0),
(134, 'asshole', NULL, NULL, 0),
(135, 'assholes', NULL, NULL, 0),
(136, 'asswhole', NULL, NULL, 0),
(137, 'a_s_s', NULL, NULL, 0),
(138, 'b!tch', NULL, NULL, 0),
(139, 'b00bs', NULL, NULL, 0),
(140, 'b17ch', NULL, NULL, 0),
(141, 'b1tch', NULL, NULL, 0),
(142, 'ballbag', NULL, NULL, 0),
(143, 'balls', NULL, NULL, 0),
(144, 'ballsack', NULL, NULL, 0),
(145, 'bastard', NULL, NULL, 0),
(146, 'beastial', NULL, NULL, 0),
(147, 'beastiality', NULL, NULL, 0),
(148, 'bellend', NULL, NULL, 0),
(149, 'bestial', NULL, NULL, 0),
(150, 'bestiality', NULL, NULL, 0),
(151, 'bi+ch', NULL, NULL, 0),
(152, 'biatch', NULL, NULL, 0),
(153, 'bitch', NULL, '2018-11-01 16:09:01', 1),
(154, 'bitcher', NULL, NULL, 0),
(155, 'bitchers', NULL, NULL, 0),
(156, 'bitches', NULL, NULL, 0),
(157, 'bitchin', NULL, NULL, 0),
(158, 'bitching', NULL, NULL, 0),
(159, 'bloody', NULL, NULL, 0),
(160, 'blow job', NULL, NULL, 0),
(161, 'blowjob', NULL, NULL, 0),
(162, 'blowjobs', NULL, NULL, 0),
(163, 'boiolas', NULL, NULL, 0),
(164, 'bollock', NULL, NULL, 0),
(165, 'bollok', NULL, NULL, 0),
(166, 'boner', NULL, NULL, 0),
(167, 'boob', NULL, '2018-06-06 09:08:38', 1),
(168, 'boobs', NULL, NULL, 0),
(169, 'booobs', NULL, NULL, 0),
(170, 'boooobs', NULL, NULL, 0),
(171, 'booooobs', NULL, NULL, 0),
(172, 'booooooobs', NULL, NULL, 0),
(173, 'breasts', NULL, NULL, 0),
(174, 'buceta', NULL, NULL, 0),
(175, 'bugger', NULL, NULL, 0),
(176, 'bum', NULL, NULL, 0),
(177, 'bunny fucker', NULL, NULL, 0),
(178, 'butt', NULL, '2018-04-09 16:20:40', 1),
(179, 'butthole', NULL, NULL, 0),
(180, 'buttmuch', NULL, NULL, 0),
(181, 'buttplug', NULL, NULL, 0),
(182, 'c0ck', NULL, NULL, 0),
(183, 'c0cksucker', NULL, NULL, 0),
(184, 'carpet muncher', NULL, NULL, 0),
(185, 'cawk', NULL, NULL, 0),
(186, 'chink', NULL, NULL, 0),
(187, 'cipa', NULL, NULL, 0),
(188, 'cl1t', NULL, NULL, 0),
(189, 'clit', NULL, NULL, 0),
(190, 'clitoris', NULL, NULL, 0),
(191, 'clits', NULL, NULL, 0),
(192, 'cnut', NULL, NULL, 0),
(193, 'cock', NULL, '2018-07-14 09:37:16', 0),
(194, 'cock-sucker', NULL, NULL, 0),
(195, 'cockface', NULL, NULL, 0),
(196, 'cockhead', NULL, NULL, 0),
(197, 'cockmunch', NULL, NULL, 0),
(198, 'cockmuncher', NULL, NULL, 0),
(199, 'cocks', NULL, NULL, 0),
(200, 'cocksuck', NULL, NULL, 0),
(201, 'cocksucked', NULL, NULL, 0),
(202, 'cocksucker', NULL, NULL, 0),
(203, 'cocksucking', NULL, NULL, 0),
(204, 'cocksucks', NULL, NULL, 0),
(205, 'cocksuka', NULL, NULL, 0),
(206, 'cocksukka', NULL, NULL, 0),
(207, 'cok', NULL, NULL, 0),
(208, 'cokmuncher', NULL, NULL, 0),
(209, 'coksucka', NULL, NULL, 0),
(210, 'coon', NULL, NULL, 0),
(212, 'crap', NULL, '2018-07-14 09:37:21', 0),
(213, 'cum', NULL, NULL, 0),
(214, 'cummer', NULL, NULL, 0),
(215, 'cumming', NULL, NULL, 0),
(216, 'cums', NULL, NULL, 0),
(217, 'cumshot', NULL, NULL, 0),
(218, 'cunilingus', NULL, NULL, 0),
(219, 'cunillingus', NULL, NULL, 0),
(220, 'cunnilingus', NULL, NULL, 0),
(221, 'cunt', NULL, '2018-07-14 09:37:25', 0),
(222, 'cuntlick', NULL, NULL, 0),
(223, 'cuntlicker', NULL, NULL, 0),
(224, 'cuntlicking', NULL, NULL, 0),
(225, 'cunts', NULL, NULL, 0),
(226, 'cyalis', NULL, NULL, 0),
(227, 'cyberfuc', NULL, NULL, 0),
(228, 'cyberfuck', NULL, NULL, 0),
(229, 'cyberfucked', NULL, NULL, 0),
(230, 'cyberfucker', NULL, NULL, 0),
(231, 'cyberfuckers', NULL, NULL, 0),
(232, 'cyberfucking', NULL, NULL, 0),
(233, 'd1ck', NULL, NULL, 0),
(234, 'damn', NULL, NULL, 0),
(235, 'dick', NULL, '2018-07-14 09:37:30', 0),
(236, 'dickhead', NULL, NULL, 0),
(237, 'dildo', NULL, NULL, 0),
(238, 'dildos', NULL, NULL, 0),
(239, 'dink', NULL, NULL, 0),
(240, 'dinks', NULL, NULL, 0),
(241, 'dirsa', NULL, NULL, 0),
(242, 'dlck', NULL, NULL, 0),
(243, 'dog-fucker', NULL, NULL, 0),
(244, 'doggin', NULL, NULL, 0),
(245, 'dogging', NULL, NULL, 0),
(246, 'donkeyribber', NULL, NULL, 0),
(247, 'doosh', NULL, NULL, 0),
(248, 'duche', NULL, '2018-04-09 16:23:54', 0),
(249, 'dyke', NULL, NULL, 0),
(250, 'ejaculate', NULL, NULL, 0),
(251, 'ejaculated', NULL, NULL, 0),
(252, 'ejaculates', NULL, NULL, 0),
(253, 'ejaculating', NULL, NULL, 0),
(254, 'ejaculatings', NULL, NULL, 0),
(255, 'ejaculation', NULL, NULL, 0),
(256, 'ejakulate', NULL, NULL, 0),
(257, 'f u c k', NULL, NULL, 0),
(258, 'f u c k e r', NULL, NULL, 0),
(259, 'f4nny', NULL, NULL, 0),
(260, 'fag', NULL, '2018-04-09 16:24:01', 0),
(261, 'fagging', NULL, NULL, 0),
(262, 'faggitt', NULL, NULL, 0),
(263, 'faggot', NULL, '2018-04-09 16:24:03', 1),
(264, 'faggs', NULL, NULL, 0),
(265, 'fagot', NULL, NULL, 0),
(266, 'fagots', NULL, NULL, 0),
(267, 'fags', NULL, '2018-04-09 16:24:05', 1),
(268, 'fanny', NULL, NULL, 0),
(269, 'fannyflaps', NULL, NULL, 0),
(270, 'fannyfucker', NULL, NULL, 0),
(271, 'fanyy', NULL, NULL, 0),
(272, 'fatass', NULL, NULL, 0),
(273, 'fcuk', NULL, '2018-04-09 16:25:03', 1),
(274, 'fcuker', NULL, NULL, 0),
(275, 'fcuking', NULL, NULL, 0),
(276, 'feck', NULL, NULL, 0),
(277, 'fecker', NULL, NULL, 0),
(278, 'felching', NULL, NULL, 0),
(279, 'fellate', NULL, NULL, 0),
(280, 'fellatio', NULL, NULL, 0),
(281, 'fingerfuck', NULL, NULL, 0),
(282, 'fingerfucked', NULL, NULL, 0),
(283, 'fingerfucker', NULL, NULL, 0),
(284, 'fingerfuckers', NULL, NULL, 0),
(285, 'fingerfucking', NULL, NULL, 0),
(286, 'fingerfucks', NULL, NULL, 0),
(287, 'fistfuck', NULL, NULL, 0),
(288, 'fistfucked', NULL, NULL, 0),
(289, 'fistfucker', NULL, NULL, 0),
(290, 'fistfuckers', NULL, NULL, 0),
(291, 'fistfucking', NULL, NULL, 0),
(292, 'fistfuckings', NULL, NULL, 0),
(293, 'fistfucks', NULL, NULL, 0),
(294, 'flange', NULL, NULL, 0),
(295, 'fook', NULL, NULL, 0),
(296, 'fooker', NULL, NULL, 0),
(297, 'fuck', NULL, '2018-04-09 16:25:08', 1),
(298, 'fucka', NULL, NULL, 0),
(299, 'fucked', NULL, NULL, 0),
(300, 'fucker', NULL, NULL, 0),
(301, 'fuckers', NULL, NULL, 0),
(302, 'fuckhead', NULL, NULL, 0),
(303, 'fuckheads', NULL, NULL, 0),
(304, 'fuckin', NULL, NULL, 0),
(305, 'fucking', NULL, NULL, 0),
(306, 'fuckings', NULL, NULL, 0),
(307, 'fuckingshitmotherfucker', NULL, NULL, 0),
(308, 'fuckme', NULL, NULL, 0),
(309, 'fucks', NULL, NULL, 0),
(310, 'fuckwhit', NULL, NULL, 0),
(311, 'fuckwit', NULL, NULL, 0),
(312, 'fudge packer', NULL, NULL, 0),
(313, 'fudgepacker', NULL, NULL, 0),
(314, 'fuk', NULL, NULL, 0),
(315, 'fuker', NULL, NULL, 0),
(316, 'fukker', NULL, NULL, 0),
(317, 'fukkin', NULL, NULL, 0),
(318, 'fuks', NULL, '2018-04-09 16:26:13', 1),
(319, 'fukwhit', NULL, NULL, 0),
(320, 'fukwit', NULL, NULL, 0),
(321, 'fux', NULL, NULL, 0),
(322, 'fux0r', NULL, NULL, 0),
(323, 'f_u_c_k', NULL, NULL, 0),
(324, 'gangbang', NULL, NULL, 0),
(325, 'gangbanged', NULL, NULL, 0),
(326, 'gangbangs', NULL, NULL, 0),
(327, 'gaylord', NULL, NULL, 0),
(328, 'gaysex', NULL, NULL, 0),
(329, 'goatse', NULL, NULL, 0),
(330, 'god-dam', NULL, NULL, 0),
(331, 'god-damned', NULL, NULL, 0),
(332, 'goddamn', NULL, NULL, 0),
(333, 'goddamned', NULL, NULL, 0),
(334, 'hardcoresex', NULL, NULL, 0),
(336, 'heshe', NULL, NULL, 0),
(337, 'hoar', NULL, NULL, 0),
(338, 'hoare', NULL, NULL, 0),
(339, 'hoer', NULL, NULL, 0),
(340, 'homo', NULL, NULL, 0),
(341, 'hore', NULL, NULL, 0),
(342, 'horniest', NULL, NULL, 0),
(343, 'horny', NULL, NULL, 0),
(344, 'hotsex', NULL, NULL, 0),
(345, 'jack-off', NULL, NULL, 0),
(346, 'jackoff', NULL, NULL, 0),
(347, 'jap', NULL, NULL, 0),
(348, 'jerk-off', NULL, NULL, 0),
(349, 'jism', NULL, NULL, 0),
(350, 'jiz', NULL, NULL, 0),
(351, 'jizm', NULL, NULL, 0),
(352, 'jizz', NULL, NULL, 0),
(353, 'kawk', NULL, NULL, 0),
(354, 'knob', NULL, NULL, 0),
(355, 'knobead', NULL, NULL, 0),
(356, 'knobed', NULL, NULL, 0),
(357, 'knobend', NULL, NULL, 0),
(358, 'knobhead', NULL, NULL, 0),
(359, 'knobjocky', NULL, NULL, 0),
(360, 'knobjokey', NULL, NULL, 0),
(361, 'kock', NULL, '2018-07-14 09:37:56', 0),
(362, 'kondum', NULL, NULL, 0),
(363, 'kondums', NULL, NULL, 0),
(364, 'kum', NULL, NULL, 0),
(365, 'kummer', NULL, NULL, 0),
(366, 'kumming', NULL, NULL, 0),
(367, 'kums', NULL, NULL, 0),
(368, 'kunilingus', NULL, NULL, 0),
(369, 'l3i+ch', NULL, NULL, 0),
(370, 'l3itch', NULL, NULL, 0),
(371, 'labia', NULL, NULL, 0),
(372, 'lmfao', NULL, NULL, 0),
(373, 'lust', NULL, '2018-07-14 09:38:03', 0),
(374, 'lusting', NULL, NULL, 0),
(375, 'm0f0', NULL, NULL, 0),
(376, 'm0fo', NULL, NULL, 0),
(377, 'm45terbate', NULL, NULL, 0),
(378, 'ma5terb8', NULL, NULL, 0),
(379, 'ma5terbate', NULL, NULL, 0),
(380, 'masochist', NULL, NULL, 0),
(381, 'master-bate', NULL, NULL, 0),
(382, 'masterb8', NULL, NULL, 0),
(383, 'masterbat*', NULL, NULL, 0),
(384, 'masterbat3', NULL, NULL, 0),
(385, 'masterbate', NULL, NULL, 0),
(386, 'masterbation', NULL, NULL, 0),
(387, 'masterbations', NULL, NULL, 0),
(388, 'masturbate', NULL, NULL, 0),
(389, 'mo-fo', NULL, NULL, 0),
(390, 'mof0', NULL, NULL, 0),
(391, 'mofo', NULL, '2018-04-09 16:27:43', 1),
(392, 'mothafuck', NULL, NULL, 0),
(393, 'mothafucka', NULL, NULL, 0),
(394, 'mothafuckas', NULL, NULL, 0),
(395, 'mothafuckaz', NULL, NULL, 0),
(396, 'mothafucked', NULL, NULL, 0),
(397, 'mothafucker', NULL, NULL, 0),
(398, 'mothafuckers', NULL, NULL, 0),
(399, 'mothafuckin', NULL, NULL, 0),
(400, 'mothafucking', NULL, NULL, 0),
(401, 'mothafuckings', NULL, NULL, 0),
(402, 'mothafucks', NULL, NULL, 0),
(403, 'mother fucker', NULL, NULL, 0),
(404, 'motherfuck', NULL, NULL, 0),
(405, 'motherfucked', NULL, NULL, 0),
(406, 'motherfucker', NULL, NULL, 0),
(407, 'motherfuckers', NULL, NULL, 0),
(408, 'motherfuckin', NULL, NULL, 0),
(409, 'motherfucking', NULL, NULL, 0),
(410, 'motherfuckings', NULL, NULL, 0),
(411, 'motherfuckka', NULL, NULL, 0),
(412, 'motherfucks', NULL, NULL, 0),
(413, 'muff', NULL, NULL, 0),
(414, 'mutha', NULL, NULL, 0),
(415, 'muthafecker', NULL, NULL, 0),
(416, 'muthafuckker', NULL, NULL, 0),
(417, 'muther', NULL, NULL, 0),
(418, 'mutherfucker', NULL, NULL, 0),
(419, 'n1gga', NULL, NULL, 0),
(420, 'n1gger', NULL, NULL, 0),
(421, 'nazi', NULL, '2018-07-14 09:38:10', 0),
(422, 'nigg3r', NULL, NULL, 0),
(423, 'nigg4h', NULL, NULL, 0),
(424, 'nigga', NULL, '2018-04-09 16:27:55', 1),
(425, 'niggah', NULL, NULL, 0),
(426, 'niggas', NULL, NULL, 0),
(427, 'niggaz', NULL, NULL, 0),
(428, 'nigger', NULL, '2018-07-14 09:38:18', 1),
(429, 'niggers', NULL, NULL, 0),
(430, 'nob', NULL, NULL, 0),
(431, 'nob jokey', NULL, NULL, 0),
(432, 'nobhead', NULL, NULL, 0),
(433, 'nobjocky', NULL, NULL, 0),
(434, 'nobjokey', NULL, NULL, 0),
(435, 'numbnuts', NULL, NULL, 0),
(436, 'nutsack', NULL, NULL, 0),
(437, 'orgasim', NULL, NULL, 0),
(438, 'orgasims', NULL, NULL, 0),
(439, 'orgasm', NULL, NULL, 0),
(440, 'orgasms', NULL, NULL, 0),
(441, 'p0rn', NULL, NULL, 0),
(442, 'pawn', NULL, NULL, 0),
(443, 'pecker', NULL, NULL, 0),
(444, 'penis', NULL, '2018-04-09 16:28:03', 1),
(445, 'penisfucker', NULL, NULL, 0),
(446, 'phonesex', NULL, NULL, 0),
(447, 'phuck', NULL, NULL, 0),
(448, 'phuk', NULL, NULL, 0),
(449, 'phuked', NULL, NULL, 0),
(450, 'phuking', NULL, NULL, 0),
(451, 'phukked', NULL, NULL, 0),
(452, 'phukking', NULL, NULL, 0),
(453, 'phuks', NULL, NULL, 0),
(454, 'phuq', NULL, NULL, 0),
(455, 'pigfucker', NULL, NULL, 0),
(456, 'pimpis', NULL, NULL, 0),
(457, 'piss', NULL, '2018-04-09 16:28:09', 1),
(458, 'pissed', NULL, NULL, 0),
(459, 'pisser', NULL, NULL, 0),
(460, 'pissers', NULL, NULL, 0),
(461, 'pisses', NULL, NULL, 0),
(462, 'pissflaps', NULL, NULL, 0),
(463, 'pissin', NULL, NULL, 0),
(464, 'pissing', NULL, NULL, 0),
(465, 'pissoff', NULL, NULL, 0),
(466, 'poop', NULL, '2018-04-09 16:28:12', 1),
(467, 'porn', NULL, '2018-04-09 16:28:12', 1),
(468, 'porno', NULL, NULL, 0),
(469, 'pornography', NULL, NULL, 0),
(470, 'pornos', NULL, NULL, 0),
(471, 'prick', NULL, NULL, 0),
(472, 'pricks', NULL, NULL, 0),
(473, 'pron', NULL, '2018-04-09 16:28:19', 1),
(474, 'pube', NULL, NULL, 0),
(475, 'pusse', NULL, NULL, 0),
(476, 'pussi', NULL, NULL, 0),
(477, 'pussies', NULL, NULL, 0),
(478, 'pussy', NULL, '2018-04-09 16:28:22', 1),
(479, 'pussys', NULL, NULL, 0),
(480, 'rectum', NULL, NULL, 0),
(481, 'retard', NULL, NULL, 0),
(482, 'rimjaw', NULL, NULL, 0),
(483, 'rimming', NULL, NULL, 0),
(484, 's hit', NULL, NULL, 0),
(485, 's.o.b.', NULL, NULL, 0),
(486, 'sadist', NULL, NULL, 0),
(487, 'schlong', NULL, NULL, 0),
(488, 'screwing', NULL, NULL, 0),
(489, 'scroat', NULL, NULL, 0),
(490, 'scrote', NULL, NULL, 0),
(491, 'scrotum', NULL, NULL, 0),
(492, 'semen', NULL, NULL, 0),
(493, 'sex', NULL, '2018-07-14 09:38:29', 0),
(494, 'sh!+', NULL, NULL, 0),
(495, 'sh!t', NULL, NULL, 0),
(496, 'sh1t', NULL, NULL, 0),
(497, 'shag', NULL, NULL, 0),
(498, 'shagger', NULL, NULL, 0),
(499, 'shaggin', NULL, NULL, 0),
(500, 'shagging', NULL, NULL, 0),
(501, 'shemale', NULL, NULL, 0),
(502, 'shi+', NULL, NULL, 0),
(503, 'shit', NULL, '2018-04-09 16:28:32', 1),
(504, 'shitdick', NULL, NULL, 0),
(505, 'shite', NULL, NULL, 0),
(506, 'shited', NULL, NULL, 0),
(507, 'shitey', NULL, NULL, 0),
(508, 'shitfuck', NULL, NULL, 0),
(509, 'shitfull', NULL, NULL, 0),
(510, 'shithead', NULL, NULL, 0),
(511, 'shiting', NULL, NULL, 0),
(512, 'shitings', NULL, NULL, 0),
(513, 'shits', NULL, NULL, 0),
(514, 'shitted', NULL, NULL, 0),
(515, 'shitter', NULL, NULL, 0),
(516, 'shitters', NULL, NULL, 0),
(517, 'shitting', NULL, NULL, 0),
(518, 'shittings', NULL, NULL, 0),
(519, 'shitty', NULL, NULL, 0),
(520, 'skank', NULL, NULL, 0),
(521, 'slut', NULL, '2018-04-09 16:28:44', 1),
(522, 'sluts', NULL, NULL, 0),
(523, 'smegma', NULL, NULL, 0),
(524, 'smut', NULL, NULL, 0),
(525, 'snatch', NULL, NULL, 0),
(526, 'son-of-a-bitch', NULL, NULL, 0),
(527, 'spunk', NULL, NULL, 0),
(528, 's_h_i_t', NULL, NULL, 0),
(529, 't1tt1e5', NULL, NULL, 0),
(530, 't1tties', NULL, NULL, 0),
(531, 'teets', NULL, NULL, 0),
(532, 'teez', NULL, NULL, 0),
(533, 'testical', NULL, NULL, 0),
(534, 'testicle', NULL, NULL, 0),
(535, 'titfuck', NULL, NULL, 0),
(536, 'tits', NULL, NULL, 0),
(537, 'titt', NULL, NULL, 0),
(538, 'tittie5', NULL, NULL, 0),
(539, 'tittiefucker', NULL, NULL, 0),
(540, 'titties', NULL, NULL, 0),
(541, 'tittyfuck', NULL, NULL, 0),
(542, 'tittywank', NULL, NULL, 0),
(543, 'titwank', NULL, NULL, 0),
(544, 'tosser', NULL, NULL, 0),
(545, 'turd', NULL, NULL, 0),
(546, 'tw4t', NULL, NULL, 0),
(547, 'twat', NULL, NULL, 0),
(548, 'twathead', NULL, NULL, 0),
(549, 'twatty', NULL, NULL, 0),
(550, 'twunt', NULL, NULL, 0),
(551, 'twunter', NULL, NULL, 0),
(552, 'v14gra', NULL, NULL, 0),
(553, 'v1gra', NULL, NULL, 0),
(554, 'vagina', NULL, NULL, 0),
(555, 'viagra', NULL, NULL, 0),
(556, 'vulva', NULL, NULL, 0),
(557, 'w00se', NULL, NULL, 0),
(558, 'wang', NULL, NULL, 0),
(559, 'wank', NULL, NULL, 0),
(560, 'wanker', NULL, NULL, 0),
(561, 'wanky', NULL, NULL, 0),
(562, 'whoar', NULL, NULL, 0),
(563, 'whore', NULL, NULL, 0),
(564, 'willies', NULL, NULL, 0),
(565, 'willy', NULL, NULL, 0),
(566, 'xrated', NULL, NULL, 0),
(567, 'xxx', NULL, NULL, 0),
(568, 'bollocks', NULL, NULL, 0),
(569, 'child-fucker', NULL, NULL, 0),
(570, 'Christ on a bike', NULL, NULL, 0),
(571, 'Christ on a cracker', NULL, NULL, 0),
(572, 'swear word', NULL, NULL, 0),
(573, 'godsdamn', NULL, NULL, 0),
(574, 'holy shit', NULL, NULL, 0),
(575, 'Jesus', NULL, '2018-07-14 09:38:45', 1),
(576, 'Jesus Christ', NULL, NULL, 0),
(577, 'Jesus H. Christ', NULL, NULL, 0),
(578, 'Jesus Harold Christ', NULL, NULL, 0),
(579, 'Jesus wept', NULL, NULL, 0),
(580, 'Jesus, Mary and Joseph', NULL, NULL, 0),
(581, 'Judas Priest', NULL, NULL, 0),
(582, 'shit ass', NULL, NULL, 0),
(583, 'shitass', NULL, NULL, 0),
(584, 'son of a bitch', NULL, NULL, 0),
(585, 'son of a motherless goat', NULL, NULL, 0),
(586, 'son of a whore', NULL, NULL, 0),
(587, 'sweet Jesus', NULL, NULL, 0),
(588, '2g1c', NULL, NULL, 0),
(589, '2 girls 1 cup', NULL, NULL, 0),
(590, 'acrotomophilia', NULL, NULL, 0),
(591, 'alabama hot pocket', NULL, NULL, 0),
(592, 'alaskan pipeline', NULL, NULL, 0),
(593, 'anilingus', NULL, NULL, 0),
(594, 'apeshit', NULL, NULL, 0),
(595, 'arsehole', NULL, NULL, 0),
(596, 'assmunch', NULL, NULL, 0),
(597, 'auto erotic', NULL, NULL, 0),
(598, 'autoerotic', NULL, NULL, 0),
(599, 'babeland', NULL, NULL, 0),
(600, 'baby batter', NULL, NULL, 0),
(601, 'baby juice', NULL, NULL, 0),
(602, 'ball gag', NULL, NULL, 0),
(603, 'ball gravy', NULL, NULL, 0),
(604, 'ball kicking', NULL, NULL, 0),
(605, 'ball licking', NULL, NULL, 0),
(606, 'ball sack', NULL, NULL, 0),
(607, 'ball sucking', NULL, NULL, 0),
(608, 'bangbros', NULL, NULL, 0),
(609, 'bareback', NULL, NULL, 0),
(610, 'barely legal', NULL, NULL, 0),
(611, 'barenaked', NULL, NULL, 0),
(612, 'bastardo', NULL, NULL, 0),
(613, 'bastinado', NULL, NULL, 0),
(614, 'bbw', NULL, NULL, 0),
(615, 'bdsm', NULL, NULL, 0),
(616, 'beaner', NULL, NULL, 0),
(617, 'beaners', NULL, NULL, 0),
(618, 'beaver cleaver', NULL, NULL, 0),
(619, 'beaver lips', NULL, NULL, 0),
(620, 'big black', NULL, NULL, 0),
(621, 'big breasts', NULL, NULL, 0),
(622, 'big knockers', NULL, NULL, 0),
(623, 'big tits', NULL, NULL, 0),
(624, 'bimbos', NULL, NULL, 0),
(625, 'birdlock', NULL, NULL, 0),
(626, 'black cock', NULL, NULL, 0),
(627, 'blonde action', NULL, NULL, 0),
(628, 'blonde on blonde action', NULL, NULL, 0),
(629, 'blow your load', NULL, NULL, 0),
(630, 'blue waffle', NULL, NULL, 0),
(631, 'blumpkin', NULL, NULL, 0),
(632, 'bondage', NULL, NULL, 0),
(633, 'booty call', NULL, NULL, 0),
(634, 'brown showers', NULL, NULL, 0),
(635, 'brunette action', NULL, NULL, 0),
(636, 'bukkake', NULL, NULL, 0),
(637, 'bulldyke', NULL, NULL, 0),
(638, 'bullet vibe', NULL, NULL, 0),
(639, 'bullshit', NULL, NULL, 0),
(640, 'bung hole', NULL, NULL, 0),
(641, 'bunghole', NULL, NULL, 0),
(642, 'busty', NULL, NULL, 0),
(643, 'buttcheeks', NULL, NULL, 0),
(644, 'camel toe', NULL, NULL, 0),
(645, 'camgirl', NULL, NULL, 0),
(646, 'camslut', NULL, NULL, 0),
(647, 'camwhore', NULL, NULL, 0),
(648, 'carpetmuncher', NULL, NULL, 0),
(649, 'chocolate rosebuds', NULL, NULL, 0),
(650, 'circlejerk', NULL, NULL, 0),
(651, 'cleveland steamer', NULL, NULL, 0),
(652, 'clover clamps', NULL, NULL, 0),
(653, 'clusterfuck', NULL, NULL, 0),
(654, 'coprolagnia', NULL, NULL, 0),
(655, 'coprophilia', NULL, NULL, 0),
(656, 'cornhole', NULL, NULL, 0),
(657, 'coons', NULL, NULL, 0),
(658, 'creampie', NULL, NULL, 0),
(659, 'darkie', NULL, NULL, 0),
(660, 'date rape', NULL, NULL, 0),
(661, 'daterape', NULL, NULL, 0),
(662, 'deep throat', NULL, NULL, 0),
(663, 'deepthroat', NULL, NULL, 0),
(664, 'dendrophilia', NULL, NULL, 0),
(665, 'dingleberry', NULL, NULL, 0),
(666, 'dingleberries', NULL, NULL, 0),
(667, 'dirty pillows', NULL, NULL, 0),
(668, 'dirty sanchez', NULL, NULL, 0),
(669, 'doggie style', NULL, NULL, 0),
(670, 'doggiestyle', NULL, NULL, 0),
(671, 'doggy style', NULL, NULL, 0),
(672, 'doggystyle', NULL, NULL, 0),
(673, 'dog style', NULL, NULL, 0),
(674, 'dolcett', NULL, NULL, 0),
(675, 'domination', NULL, NULL, 0),
(676, 'dominatrix', NULL, NULL, 0),
(677, 'dommes', NULL, NULL, 0),
(678, 'donkey punch', NULL, NULL, 0),
(679, 'double dong', NULL, NULL, 0),
(680, 'double penetration', NULL, NULL, 0),
(681, 'dp action', NULL, NULL, 0),
(682, 'dry hump', NULL, NULL, 0),
(683, 'dvda', NULL, NULL, 0),
(684, 'eat my ass', NULL, NULL, 0),
(685, 'ecchi', NULL, NULL, 0),
(686, 'erotic', NULL, NULL, 0),
(687, 'erotism', NULL, NULL, 0),
(688, 'escort', NULL, NULL, 0),
(689, 'eunuch', NULL, NULL, 0),
(690, 'fecal', NULL, NULL, 0),
(691, 'felch', NULL, NULL, 0),
(692, 'feltch', NULL, NULL, 0),
(693, 'female squirting', NULL, NULL, 0),
(694, 'femdom', NULL, NULL, 0),
(695, 'figging', NULL, NULL, 0),
(696, 'fingerbang', NULL, NULL, 0),
(697, 'fingering', NULL, NULL, 0),
(698, 'fisting', NULL, NULL, 0),
(699, 'foot fetish', NULL, NULL, 0),
(700, 'footjob', NULL, NULL, 0),
(701, 'frotting', NULL, NULL, 0),
(702, 'fuck buttons', NULL, NULL, 0),
(703, 'fucktards', NULL, NULL, 0),
(704, 'futanari', NULL, NULL, 0),
(705, 'gang bang', NULL, NULL, 0),
(706, 'gay sex', NULL, NULL, 0),
(707, 'genitals', NULL, NULL, 0),
(708, 'giant cock', NULL, NULL, 0),
(709, 'girl on', NULL, NULL, 0),
(710, 'girl on top', NULL, NULL, 0),
(711, 'girls gone wild', NULL, NULL, 0),
(712, 'goatcx', NULL, NULL, 0),
(713, 'god damn', NULL, NULL, 0),
(714, 'gokkun', NULL, NULL, 0),
(715, 'golden shower', NULL, NULL, 0),
(716, 'goodpoop', NULL, NULL, 0),
(717, 'goo girl', NULL, NULL, 0),
(718, 'goregasm', NULL, NULL, 0),
(719, 'grope', NULL, NULL, 0),
(720, 'group sex', NULL, NULL, 0),
(721, 'g-spot', NULL, NULL, 0),
(722, 'guro', NULL, NULL, 0),
(723, 'hand job', NULL, NULL, 0),
(724, 'handjob', NULL, NULL, 0),
(725, 'hard core', NULL, NULL, 0),
(726, 'hardcore', NULL, NULL, 0),
(727, 'hentai', NULL, NULL, 0),
(728, 'homoerotic', NULL, NULL, 0),
(729, 'honkey', NULL, NULL, 0),
(730, 'hooker', NULL, NULL, 0),
(731, 'hot carl', NULL, NULL, 0),
(732, 'hot chick', NULL, NULL, 0),
(733, 'how to kill', NULL, NULL, 0),
(734, 'how to murder', NULL, NULL, 0),
(735, 'huge fat', NULL, NULL, 0),
(736, 'humping', NULL, NULL, 0),
(737, 'incest', NULL, NULL, 0),
(738, 'intercourse', NULL, NULL, 0),
(739, 'jack off', NULL, NULL, 0),
(740, 'jail bait', NULL, NULL, 0),
(741, 'jailbait', NULL, NULL, 0),
(742, 'jelly donut', NULL, NULL, 0),
(743, 'jerk off', NULL, NULL, 0),
(744, 'jigaboo', NULL, NULL, 0),
(745, 'jiggaboo', NULL, NULL, 0),
(746, 'jiggerboo', NULL, NULL, 0),
(747, 'juggs', NULL, NULL, 0),
(748, 'kike', NULL, NULL, 0),
(749, 'kinbaku', NULL, NULL, 0),
(750, 'kinkster', NULL, NULL, 0),
(751, 'kinky', NULL, NULL, 0),
(752, 'knobbing', NULL, NULL, 0),
(753, 'leather restraint', NULL, NULL, 0),
(754, 'leather straight jacket', NULL, NULL, 0),
(755, 'lemon party', NULL, NULL, 0),
(756, 'lolita', NULL, NULL, 0),
(757, 'lovemaking', NULL, NULL, 0),
(758, 'make me come', NULL, NULL, 0),
(759, 'male squirting', NULL, NULL, 0),
(760, 'menage a trois', NULL, NULL, 0),
(761, 'milf', NULL, NULL, 0),
(762, 'missionary position', NULL, NULL, 0),
(763, 'mound of venus', NULL, NULL, 0),
(764, 'mr hands', NULL, NULL, 0),
(765, 'muff diver', NULL, NULL, 0),
(766, 'muffdiving', NULL, NULL, 0),
(767, 'nambla', NULL, NULL, 0),
(768, 'nawashi', NULL, NULL, 0),
(769, 'negro', NULL, NULL, 0),
(770, 'neonazi', NULL, NULL, 0),
(771, 'nig nog', NULL, NULL, 0),
(772, 'nimphomania', NULL, NULL, 0),
(773, 'nipple', NULL, NULL, 0),
(774, 'nipples', NULL, NULL, 0),
(775, 'nsfw images', NULL, NULL, 0),
(776, 'nude', NULL, NULL, 0),
(777, 'nudity', NULL, NULL, 0),
(778, 'nympho', NULL, NULL, 0),
(779, 'nymphomania', NULL, NULL, 0),
(780, 'octopussy', NULL, NULL, 0),
(781, 'omorashi', NULL, NULL, 0),
(782, 'one cup two girls', NULL, NULL, 0),
(783, 'one guy one jar', NULL, NULL, 0),
(784, 'orgy', NULL, NULL, 0),
(785, 'paedophile', NULL, NULL, 0),
(786, 'paki', NULL, NULL, 0),
(787, 'panties', NULL, NULL, 0),
(788, 'panty', NULL, NULL, 0),
(789, 'pedobear', NULL, NULL, 0),
(790, 'pedophile', NULL, NULL, 0),
(791, 'pegging', NULL, NULL, 0),
(792, 'phone sex', NULL, NULL, 0),
(793, 'piece of shit', NULL, NULL, 0),
(794, 'piss pig', NULL, NULL, 0),
(795, 'pisspig', NULL, NULL, 0),
(796, 'playboy', NULL, NULL, 0),
(797, 'pleasure chest', NULL, NULL, 0),
(798, 'pole smoker', NULL, NULL, 0),
(799, 'ponyplay', NULL, NULL, 0),
(800, 'poof', NULL, NULL, 0),
(801, 'poon', NULL, NULL, 0),
(802, 'poontang', NULL, NULL, 0),
(803, 'punany', NULL, NULL, 0),
(804, 'poop chute', NULL, NULL, 0),
(805, 'poopchute', NULL, NULL, 0),
(806, 'prince albert piercing', NULL, NULL, 0),
(807, 'pthc', NULL, NULL, 0),
(808, 'pubes', NULL, NULL, 0),
(809, 'queaf', NULL, NULL, 0),
(810, 'queef', NULL, NULL, 0),
(811, 'quim', NULL, NULL, 0),
(812, 'raghead', NULL, NULL, 0),
(813, 'raging boner', NULL, NULL, 0),
(814, 'rape', NULL, NULL, 0),
(815, 'raping', NULL, NULL, 0),
(816, 'rapist', NULL, NULL, 0),
(817, 'reverse cowgirl', NULL, NULL, 0),
(818, 'rimjob', NULL, NULL, 0),
(819, 'rosy palm', NULL, NULL, 0),
(820, 'rosy palm and her 5 sisters', NULL, NULL, 0),
(821, 'rusty trombone', NULL, NULL, 0),
(822, 'sadism', NULL, NULL, 0),
(823, 'santorum', NULL, NULL, 0),
(824, 'scat', NULL, NULL, 0),
(825, 'scissoring', NULL, NULL, 0),
(826, 'sexo', NULL, NULL, 0),
(827, 'sexy', NULL, NULL, 0),
(828, 'shaved beaver', NULL, NULL, 0),
(829, 'shaved pussy', NULL, NULL, 0),
(830, 'shibari', NULL, NULL, 0),
(831, 'shitblimp', NULL, NULL, 0),
(832, 'shota', NULL, NULL, 0),
(833, 'shrimping', NULL, NULL, 0),
(834, 'skeet', NULL, NULL, 0),
(835, 'slanteye', NULL, NULL, 0),
(836, 's&m', NULL, NULL, 0),
(837, 'snowballing', NULL, NULL, 0),
(838, 'sodomize', NULL, NULL, 0),
(839, 'sodomy', NULL, NULL, 0),
(840, 'spic', NULL, NULL, 0),
(841, 'splooge', NULL, NULL, 0),
(842, 'splooge moose', NULL, NULL, 0),
(843, 'spooge', NULL, NULL, 0),
(844, 'spread legs', NULL, NULL, 0),
(845, 'strap on', NULL, NULL, 0),
(846, 'strapon', NULL, NULL, 0),
(847, 'strappado', NULL, NULL, 0),
(848, 'strip club', NULL, NULL, 0),
(849, 'style doggy', NULL, NULL, 0),
(850, 'suck', NULL, NULL, 0),
(851, 'sucks', NULL, NULL, 0),
(852, 'suicide girls', NULL, NULL, 0),
(853, 'sultry women', NULL, NULL, 0),
(854, 'swastika', NULL, NULL, 0),
(855, 'swinger', NULL, NULL, 0),
(856, 'tainted love', NULL, NULL, 0),
(857, 'taste my', NULL, NULL, 0),
(858, 'tea bagging', NULL, NULL, 0),
(859, 'threesome', NULL, NULL, 0),
(860, 'throating', NULL, NULL, 0),
(861, 'tied up', NULL, NULL, 0),
(862, 'tight white', NULL, NULL, 0),
(863, 'titty', NULL, NULL, 0),
(864, 'tongue in a', NULL, NULL, 0),
(865, 'topless', NULL, NULL, 0),
(866, 'towelhead', NULL, NULL, 0),
(867, 'tranny', NULL, NULL, 0),
(868, 'tribadism', NULL, NULL, 0),
(869, 'tub girl', NULL, NULL, 0),
(870, 'tubgirl', NULL, NULL, 0),
(871, 'tushy', NULL, NULL, 0),
(872, 'twink', NULL, NULL, 0),
(873, 'twinkie', NULL, NULL, 0),
(874, 'two girls one cup', NULL, NULL, 0),
(875, 'undressing', NULL, NULL, 0),
(876, 'upskirt', NULL, NULL, 0),
(877, 'urethra play', NULL, NULL, 0),
(878, 'urophilia', NULL, NULL, 0),
(879, 'venus mound', NULL, NULL, 0),
(880, 'vibrator', NULL, NULL, 0),
(881, 'violet wand', NULL, NULL, 0),
(882, 'vorarephilia', NULL, NULL, 0),
(883, 'voyeur', NULL, NULL, 0),
(884, 'wetback', NULL, NULL, 0),
(885, 'wet dream', NULL, NULL, 0),
(886, 'white power', NULL, NULL, 0),
(887, 'wrapping men', NULL, NULL, 0),
(888, 'wrinkled starfish', NULL, NULL, 0),
(889, 'xx', NULL, NULL, 0),
(890, 'yaoi', NULL, NULL, 0),
(891, 'yellow showers', NULL, NULL, 0),
(892, 'yiffy', NULL, NULL, 0),
(893, 'zoophilia', NULL, NULL, 0),
(894, 'a54', NULL, NULL, 0),
(895, 'buttmunch', NULL, NULL, 0),
(896, 'donkeypunch', NULL, NULL, 0),
(897, 'fleshflute', NULL, NULL, 0),
(898, 'asswipe', NULL, NULL, 0),
(899, 'bitchass', NULL, NULL, 0),
(900, 'moo moo foo foo', NULL, NULL, 0),
(901, 'trumped', NULL, NULL, 0),
(902, 'assbag', NULL, NULL, 0),
(903, 'assbandit', NULL, NULL, 0),
(904, 'assbanger', NULL, NULL, 0),
(905, 'assbite', NULL, NULL, 0),
(906, 'assclown', NULL, NULL, 0),
(907, 'asscock', NULL, NULL, 0),
(908, 'asscracker', NULL, NULL, 0),
(909, 'assface', NULL, NULL, 0),
(910, 'assfuck', NULL, NULL, 0),
(911, 'assgoblin', NULL, NULL, 0),
(912, 'asshat', NULL, NULL, 0),
(913, 'ass-hat', NULL, NULL, 0),
(914, 'asshead', NULL, NULL, 0),
(915, 'asshopper', NULL, NULL, 0),
(916, 'ass-jabber', NULL, NULL, 0),
(917, 'assjacker', NULL, NULL, 0),
(918, 'asslick', NULL, NULL, 0),
(919, 'asslicker', NULL, NULL, 0),
(920, 'assmonkey', NULL, NULL, 0),
(921, 'assmuncher', NULL, NULL, 0),
(922, 'assnigger', NULL, NULL, 0),
(923, 'asspirate', NULL, NULL, 0),
(924, 'ass-pirate', NULL, NULL, 0),
(925, 'assshit', NULL, NULL, 0),
(926, 'assshole', NULL, NULL, 0),
(927, 'asssucker', NULL, NULL, 0),
(928, 'asswad', NULL, NULL, 0),
(929, 'axwound', NULL, NULL, 0),
(930, 'bampot', NULL, NULL, 0),
(931, 'bitchtits', NULL, NULL, 0),
(932, 'bitchy', NULL, NULL, 0),
(933, 'bollox', NULL, NULL, 0),
(934, 'brotherfucker', NULL, NULL, 0),
(935, 'bumblefuck', NULL, NULL, 0),
(936, 'butt plug', NULL, NULL, 0),
(937, 'buttfucka', NULL, NULL, 0),
(938, 'butt-pirate', NULL, NULL, 0),
(939, 'buttfucker', NULL, NULL, 0),
(940, 'chesticle', NULL, NULL, 0),
(941, 'chinc', NULL, NULL, 0),
(942, 'choad', NULL, NULL, 0),
(943, 'chode', NULL, NULL, 0),
(944, 'clitface', NULL, NULL, 0),
(945, 'clitfuck', NULL, NULL, 0),
(946, 'cockass', NULL, NULL, 0),
(947, 'cockbite', NULL, NULL, 0),
(948, 'cockburger', NULL, NULL, 0),
(949, 'cockfucker', NULL, NULL, 0),
(950, 'cockjockey', NULL, NULL, 0),
(951, 'cockknoker', NULL, NULL, 0),
(952, 'cockmaster', NULL, NULL, 0),
(953, 'cockmongler', NULL, NULL, 0),
(954, 'cockmongruel', NULL, NULL, 0),
(955, 'cockmonkey', NULL, NULL, 0),
(956, 'cocknose', NULL, NULL, 0),
(957, 'cocknugget', NULL, NULL, 0),
(958, 'cockshit', NULL, NULL, 0),
(959, 'cocksmith', NULL, NULL, 0),
(960, 'cocksmoke', NULL, NULL, 0),
(961, 'cocksmoker', NULL, NULL, 0),
(962, 'cocksniffer', NULL, NULL, 0),
(963, 'cockwaffle', NULL, NULL, 0),
(964, 'coochie', NULL, NULL, 0),
(965, 'coochy', NULL, NULL, 0),
(966, 'cooter', NULL, NULL, 0),
(967, 'cracker', NULL, NULL, 0),
(968, 'cumbubble', NULL, NULL, 0),
(969, 'cumdumpster', NULL, NULL, 0),
(970, 'cumguzzler', NULL, NULL, 0),
(971, 'cumjockey', NULL, NULL, 0),
(972, 'cumslut', NULL, NULL, 0),
(973, 'cumtart', NULL, NULL, 0),
(974, 'cunnie', NULL, NULL, 0),
(975, 'cuntass', NULL, NULL, 0),
(976, 'cuntface', NULL, NULL, 0),
(977, 'cunthole', NULL, NULL, 0),
(978, 'cuntrag', NULL, NULL, 0),
(979, 'cuntslut', NULL, NULL, 0),
(980, 'dago', NULL, NULL, 0),
(981, 'deggo', NULL, NULL, 0),
(982, 'dickbag', NULL, NULL, 0),
(983, 'dickbeaters', NULL, NULL, 0),
(984, 'dickface', NULL, NULL, 0),
(985, 'dickfuck', NULL, NULL, 0),
(986, 'dickfucker', NULL, NULL, 0),
(987, 'dickhole', NULL, NULL, 0),
(988, 'dickjuice', NULL, NULL, 0),
(989, 'dickmilk ', NULL, NULL, 0),
(990, 'dickmonger', NULL, NULL, 0),
(991, 'dicks', NULL, NULL, 0),
(992, 'dickslap', NULL, NULL, 0),
(993, 'dick-sneeze', NULL, NULL, 0),
(994, 'dicksucker', NULL, NULL, 0),
(995, 'dicksucking', NULL, NULL, 0),
(996, 'dicktickler', NULL, NULL, 0),
(997, 'dickwad', NULL, NULL, 0),
(998, 'dickweasel', NULL, NULL, 0),
(999, 'dickweed', NULL, NULL, 0),
(1000, 'dickwod', NULL, NULL, 0),
(1001, 'dike', NULL, NULL, 0),
(1002, 'dipshit', NULL, NULL, 0),
(1003, 'doochbag', NULL, NULL, 0),
(1004, 'dookie', NULL, NULL, 0),
(1005, 'douche', NULL, NULL, 0),
(1006, 'douchebag', NULL, NULL, 0),
(1007, 'douche-fag', NULL, NULL, 0),
(1008, 'douchewaffle', NULL, NULL, 0),
(1009, 'dumass', NULL, NULL, 0),
(1010, 'dumb ass', NULL, NULL, 0),
(1011, 'dumbass', NULL, NULL, 0),
(1012, 'dumbfuck', NULL, NULL, 0),
(1013, 'dumbshit', NULL, NULL, 0),
(1014, 'dumshit', NULL, NULL, 0),
(1015, 'fagbag', NULL, NULL, 0),
(1016, 'fagfucker', NULL, NULL, 0),
(1017, 'faggit', NULL, NULL, 0),
(1018, 'faggotcock', NULL, NULL, 0),
(1019, 'fagtard', NULL, NULL, 0),
(1020, 'flamer', NULL, NULL, 0),
(1021, 'fuckass', NULL, NULL, 0),
(1022, 'fuckbag', NULL, NULL, 0),
(1023, 'fuckboy', NULL, NULL, 0),
(1024, 'fuckbrain', NULL, NULL, 0),
(1025, 'fuckbutt', NULL, NULL, 0),
(1026, 'fuckbutter', NULL, NULL, 0),
(1027, 'fuckersucker', NULL, NULL, 0),
(1028, 'fuckface', NULL, NULL, 0),
(1029, 'fuckhole', NULL, NULL, 0),
(1030, 'fucknut', NULL, NULL, 0),
(1031, 'fucknutt', NULL, NULL, 0),
(1032, 'fuckoff', NULL, NULL, 0),
(1033, 'fuckstick', NULL, NULL, 0),
(1034, 'fucktard', NULL, NULL, 0),
(1035, 'fucktart', NULL, NULL, 0),
(1036, 'fuckup', NULL, NULL, 0),
(1037, 'fuckwad', NULL, NULL, 0),
(1038, 'fuckwitt', NULL, NULL, 0),
(1039, 'gay', NULL, NULL, 0),
(1040, 'gayass', NULL, NULL, 0),
(1041, 'gaybob', NULL, NULL, 0),
(1042, 'gaydo', NULL, NULL, 0),
(1043, 'gayfuck', NULL, NULL, 0),
(1044, 'gayfuckist', NULL, NULL, 0),
(1045, 'gaytard', NULL, NULL, 0),
(1046, 'gaywad', NULL, NULL, 0),
(1047, 'goddamnit', NULL, NULL, 0),
(1048, 'gooch', NULL, NULL, 0),
(1049, 'gook', NULL, NULL, 0),
(1050, 'gringo', NULL, NULL, 0),
(1051, 'guido', NULL, NULL, 0),
(1052, 'hard on', NULL, NULL, 0),
(1053, 'heeb', NULL, NULL, 0),
(1054, 'hoe', NULL, NULL, 0),
(1055, 'homodumbshit', NULL, NULL, 0),
(1056, 'jackass', NULL, NULL, 0),
(1057, 'jagoff', NULL, NULL, 0),
(1058, 'jerkass', NULL, NULL, 0),
(1059, 'jungle bunny', NULL, NULL, 0),
(1060, 'junglebunny', NULL, NULL, 0),
(1061, 'kooch', NULL, NULL, 0),
(1062, 'kootch', NULL, NULL, 0),
(1063, 'kraut', NULL, NULL, 0),
(1064, 'kunt', NULL, NULL, 0),
(1065, 'kyke', NULL, NULL, 0),
(1066, 'lameass', NULL, NULL, 0),
(1067, 'lardass', NULL, NULL, 0),
(1068, 'lesbian', NULL, NULL, 0),
(1069, 'lesbo', NULL, NULL, 0),
(1070, 'lezzie', NULL, NULL, 0),
(1071, 'mcfagget', NULL, NULL, 0),
(1073, 'minge', NULL, NULL, 0),
(1074, 'muffdiver', NULL, NULL, 0),
(1075, 'munging', NULL, NULL, 0),
(1076, 'nigaboo', NULL, NULL, 0),
(1077, 'niglet', NULL, NULL, 0),
(1078, 'nut sack', NULL, NULL, 0),
(1079, 'panooch', NULL, NULL, 0),
(1080, 'peckerhead', NULL, NULL, 0),
(1081, 'penisbanger', NULL, NULL, 0),
(1082, 'penispuffer', NULL, NULL, 0),
(1083, 'pissed off', NULL, NULL, 0),
(1084, 'polesmoker', NULL, NULL, 0),
(1085, 'pollock', NULL, NULL, 0),
(1086, 'poonani', NULL, NULL, 0),
(1087, 'poonany', NULL, NULL, 0),
(1088, 'porch monkey', NULL, NULL, 0),
(1089, 'porchmonkey', NULL, NULL, 0),
(1090, 'punanny', NULL, NULL, 0),
(1091, 'punta', NULL, NULL, 0),
(1092, 'pussylicking', NULL, NULL, 0),
(1093, 'puto', NULL, NULL, 0),
(1094, 'queer', NULL, NULL, 0),
(1095, 'queerbait', NULL, NULL, 0),
(1096, 'queerhole', NULL, NULL, 0),
(1097, 'renob', NULL, NULL, 0),
(1098, 'ruski', NULL, NULL, 0),
(1099, 'sand nigger', NULL, NULL, 0),
(1100, 'sandnigger', NULL, NULL, 0),
(1101, 'shitbag', NULL, NULL, 0),
(1102, 'shitbagger', NULL, NULL, 0),
(1103, 'shitbrains', NULL, NULL, 0),
(1104, 'shitbreath', NULL, NULL, 0),
(1105, 'shitcanned', NULL, NULL, 0),
(1106, 'shitcunt', NULL, NULL, 0),
(1107, 'shitface', NULL, NULL, 0),
(1108, 'shitfaced', NULL, NULL, 0),
(1109, 'shithole', NULL, NULL, 0),
(1110, 'shithouse', NULL, NULL, 0),
(1111, 'shitspitter', NULL, NULL, 0),
(1112, 'shitstain', NULL, NULL, 0),
(1113, 'shittiest', NULL, NULL, 0),
(1114, 'shiz', NULL, NULL, 0),
(1115, 'shiznit', NULL, NULL, 0),
(1116, 'skullfuck', NULL, NULL, 0),
(1117, 'slutbag', NULL, NULL, 0),
(1118, 'smeg', NULL, NULL, 0),
(1119, 'spick', NULL, NULL, 0),
(1120, 'spook', NULL, NULL, 0),
(1121, 'suckass', NULL, NULL, 0),
(1122, 'tard', NULL, NULL, 0),
(1123, 'thundercunt', NULL, NULL, 0),
(1124, 'twatlips', NULL, NULL, 0),
(1125, 'twats', NULL, NULL, 0),
(1126, 'twatwaffle', NULL, NULL, 0),
(1127, 'unclefucker', NULL, NULL, 0),
(1128, 'vag', NULL, NULL, 0),
(1129, 'vajayjay', NULL, NULL, 0),
(1130, 'va-j-j', NULL, NULL, 0),
(1131, 'vjayjay', NULL, NULL, 0),
(1132, 'wankjob', NULL, NULL, 0),
(1133, 'whorebag', NULL, NULL, 0),
(1134, 'whoreface', NULL, NULL, 0),
(1135, 'wop', NULL, NULL, 0),
(1136, 'fuck you', NULL, NULL, 0),
(1137, 'piss off', NULL, NULL, 0),
(1138, 'dick head', NULL, NULL, 0),
(1139, 'bloody hell', NULL, NULL, 0),
(1140, 'crikey', NULL, NULL, 0),
(1141, 'rubbish', NULL, NULL, 0),
(1142, 'taking the piss', NULL, NULL, 0),
(1143, 'jerk', NULL, NULL, 0),
(1144, 'knob end', NULL, NULL, 0),
(1145, 'lmao', NULL, NULL, 0),
(1146, 'omg', NULL, NULL, 0),
(1147, 'wtf', NULL, NULL, 0),
(1148, 'bint', NULL, NULL, 0),
(1149, 'ginger', NULL, NULL, 0),
(1150, 'git', NULL, NULL, 0),
(1151, 'minger', NULL, NULL, 0),
(1152, 'munter', NULL, NULL, 0),
(1153, 'sod off', NULL, NULL, 0),
(1154, 'chinky', NULL, NULL, 0),
(1155, 'choc ice', NULL, NULL, 0),
(1156, 'gippo', NULL, NULL, 0),
(1157, 'golliwog', NULL, NULL, 0),
(1158, 'hun', NULL, NULL, 0),
(1159, 'iap', NULL, NULL, 0),
(1160, 'jock', NULL, NULL, 0),
(1161, 'nig-nog', NULL, NULL, 0),
(1162, 'pikey', NULL, NULL, 0),
(1163, 'polack', NULL, NULL, 0),
(1164, 'sambo', NULL, NULL, 0),
(1165, 'slope', NULL, NULL, 0),
(1166, 'spade', NULL, NULL, 0),
(1167, 'taff', NULL, NULL, 0),
(1168, 'wog', NULL, NULL, 0),
(1169, 'beaver', NULL, NULL, 0),
(1170, 'beef curtains', NULL, NULL, 0),
(1171, 'bloodclaat', NULL, NULL, 0),
(1172, 'clunge', NULL, NULL, 0),
(1173, 'flaps', NULL, NULL, 0),
(1174, 'gash', NULL, NULL, 0),
(1175, 'punani', NULL, NULL, 0),
(1176, 'batty boy', NULL, NULL, 0),
(1177, 'bender', NULL, NULL, 0),
(1178, 'bum boy', NULL, NULL, 0),
(1179, 'bumclat', NULL, NULL, 0),
(1180, 'bummer', NULL, NULL, 0),
(1181, 'chi-chi man', NULL, NULL, 0),
(1182, 'chick with a dick', NULL, NULL, 0),
(1183, 'fudge-packer', NULL, NULL, 0),
(1184, 'gender bender', NULL, NULL, 0),
(1185, 'he-she', NULL, NULL, 0),
(1186, 'lezza/lesbo', NULL, NULL, 0),
(1187, 'pansy', NULL, NULL, 0),
(1188, 'shirt lifter', NULL, NULL, 0),
(1189, 'cretin', NULL, NULL, 0),
(1190, 'cripple', NULL, NULL, 0),
(1191, 'div', NULL, NULL, 0),
(1192, 'looney', NULL, NULL, 0),
(1193, 'midget', NULL, NULL, 0),
(1194, 'mong', NULL, NULL, 0),
(1195, 'nutter', NULL, NULL, 0),
(1196, 'psycho', NULL, NULL, 0),
(1197, 'schizo', NULL, NULL, 0),
(1198, 'veqtable', NULL, NULL, 0),
(1199, 'window licker', NULL, NULL, 0),
(1200, 'fenian', NULL, NULL, 0),
(1201, 'kafir', NULL, NULL, 0),
(1202, 'prod', NULL, NULL, 0),
(1203, 'taig', NULL, NULL, 0),
(1204, 'yid', NULL, NULL, 0),
(1205, 'iberian slap', NULL, NULL, 0),
(1206, 'middle finger', NULL, NULL, 0),
(1207, 'two fingers with tongue', NULL, NULL, 0),
(1208, 'two fingers', NULL, NULL, 0),
(1209, 'nonce', NULL, NULL, 0),
(1210, 'prickteaser', NULL, NULL, 0),
(1211, 'rapey', NULL, NULL, 0),
(1212, 'slag', NULL, NULL, 0),
(1213, 'tart', NULL, NULL, 0),
(1214, 'coffin dodger', NULL, NULL, 0),
(1215, 'old bag', NULL, NULL, 0),
(1216, 'frenchify', NULL, NULL, 0),
(1217, 'bescumber', NULL, NULL, 0),
(1218, 'microphallus', NULL, NULL, 0),
(1219, 'coccydynia', NULL, NULL, 0),
(1220, 'ninnyhammer', NULL, NULL, 0),
(1221, 'buncombe', NULL, NULL, 0),
(1222, 'hircismus', NULL, NULL, 0),
(1223, 'corpulent', NULL, NULL, 0),
(1224, 'feist', NULL, NULL, 0),
(1226, 'cacafuego', NULL, NULL, 0),
(1227, 'ass fuck', NULL, NULL, 0),
(1228, 'assfaces', NULL, NULL, 0),
(1229, 'assmucus', NULL, NULL, 0),
(1230, 'bang (one\'s) box', NULL, NULL, 0),
(1231, 'bastards', NULL, NULL, 0),
(1232, 'beef curtain', NULL, NULL, 0),
(1233, 'bitch tit', NULL, NULL, 0),
(1234, 'blow me', NULL, NULL, 0),
(1235, 'blow mud', NULL, NULL, 0),
(1236, 'blue waffle', NULL, NULL, 0),
(1237, 'blumpkin', NULL, NULL, 0),
(1238, 'bust a load', NULL, NULL, 0),
(1239, 'butt fuck', NULL, NULL, 0),
(1240, 'choade', NULL, NULL, 0),
(1241, 'chota bags', NULL, NULL, 0),
(1242, 'clit licker', NULL, NULL, 0),
(1243, 'clitty litter', NULL, NULL, 0),
(1244, 'cock pocket', NULL, NULL, 0),
(1245, 'cock snot', NULL, NULL, 0),
(1246, 'cocksuck', NULL, NULL, 0),
(1247, 'cocksucked', NULL, NULL, 0),
(1248, 'cocksuckers', NULL, NULL, 0),
(1249, 'cocksucks', NULL, NULL, 0),
(1250, 'cop some wood', NULL, NULL, 0),
(1251, 'cornhole', NULL, NULL, 0),
(1252, 'corp whore', NULL, NULL, 0),
(1253, 'cum chugger', NULL, NULL, 0),
(1254, 'cum dumpster', NULL, NULL, 0),
(1255, 'cum freak', NULL, NULL, 0),
(1256, 'cum guzzler', NULL, NULL, 0),
(1257, 'cumdump', NULL, NULL, 0),
(1258, 'cunt hair', NULL, NULL, 0),
(1259, 'cuntbag', NULL, NULL, 0),
(1260, 'cuntlick', NULL, NULL, 0),
(1261, 'cuntlicker', NULL, NULL, 0),
(1262, 'cuntlicking', NULL, NULL, 0),
(1263, 'cuntsicle', NULL, NULL, 0),
(1264, 'cunt-struck', NULL, NULL, 0),
(1265, 'cut rope', NULL, NULL, 0),
(1266, 'cyberfuck', NULL, NULL, 0),
(1267, 'cyberfucked', NULL, NULL, 0),
(1268, 'cyberfucking', NULL, NULL, 0),
(1269, 'dick hole', NULL, NULL, 0),
(1270, 'dick shy', NULL, NULL, 0),
(1271, 'dickheads', NULL, NULL, 0),
(1272, 'dirty Sanchez', NULL, NULL, 0),
(1273, 'eat a dick', NULL, NULL, 0),
(1274, 'eat hair pie', NULL, NULL, 0),
(1275, 'ejaculates', NULL, NULL, 0),
(1276, 'ejaculating', NULL, NULL, 0),
(1277, 'facial', NULL, NULL, 0),
(1278, 'faggots', NULL, NULL, 0),
(1279, 'fingerfuck', NULL, NULL, 0),
(1280, 'fingerfucked', NULL, NULL, 0),
(1281, 'fingerfucker', NULL, NULL, 0),
(1282, 'fingerfucking', NULL, NULL, 0),
(1283, 'fingerfucks', NULL, NULL, 0),
(1284, 'fist fuck', NULL, NULL, 0),
(1285, 'fistfucked', NULL, NULL, 0),
(1286, 'fistfucker', NULL, NULL, 0),
(1287, 'fistfuckers', NULL, NULL, 0),
(1288, 'fistfucking', NULL, NULL, 0),
(1289, 'fistfuckings', NULL, NULL, 0),
(1290, 'fistfucks', NULL, NULL, 0),
(1291, 'flog the log', NULL, NULL, 0),
(1292, 'fuc', NULL, NULL, 0),
(1293, 'fuck hole', NULL, NULL, 0),
(1294, 'fuck puppet', NULL, NULL, 0),
(1295, 'fuck trophy', NULL, NULL, 0),
(1296, 'fuck yo mama', NULL, NULL, 0),
(1297, 'fuck', NULL, NULL, 0),
(1298, 'fuck-ass', NULL, NULL, 0),
(1299, 'fuck-bitch', NULL, NULL, 0),
(1300, 'fuckedup', NULL, NULL, 0),
(1301, 'fuckme', NULL, NULL, 0),
(1302, 'fuckmeat', NULL, NULL, 0),
(1303, 'fucktoy', NULL, NULL, 0),
(1304, 'fukkers', NULL, NULL, 0),
(1305, 'fuq', NULL, NULL, 0),
(1306, 'gang-bang', NULL, NULL, 0),
(1307, 'gassy ass', NULL, NULL, 0),
(1308, 'god', NULL, NULL, 0),
(1309, 'ham flap', NULL, NULL, 0),
(1310, 'how to murdep', NULL, NULL, 0),
(1311, 'jackasses', NULL, NULL, 0),
(1312, 'jiz', NULL, NULL, 0),
(1313, 'jizm', NULL, NULL, 0),
(1314, 'kinky Jesus', NULL, NULL, 0),
(1315, 'kwif', NULL, NULL, 0),
(1316, 'mafugly', NULL, NULL, 0),
(1317, 'mothafucked', NULL, NULL, 0),
(1318, 'mothafucking', NULL, NULL, 0),
(1319, 'mother fucker', NULL, NULL, 0),
(1320, 'muff puff', NULL, NULL, 0),
(1321, 'need the dick', NULL, NULL, 0),
(1322, 'nut butter', NULL, NULL, 0),
(1323, 'pisses', NULL, NULL, 0),
(1324, 'pissin', NULL, NULL, 0),
(1325, 'pissoff', NULL, NULL, 0),
(1326, 'pussy fart', NULL, NULL, 0),
(1327, 'pussy palace', NULL, NULL, 0),
(1328, 'queaf', NULL, NULL, 0),
(1329, 'sandbar', NULL, NULL, 0),
(1330, 'sausage queen', NULL, NULL, 0),
(1331, 'shit fucker', NULL, NULL, 0),
(1332, 'shitheads', NULL, NULL, 0),
(1333, 'shitters', NULL, NULL, 0),
(1334, 'shittier', NULL, NULL, 0),
(1335, 'slope', NULL, NULL, 0),
(1336, 'slut bucket', NULL, NULL, 0),
(1337, 'smartass', NULL, NULL, 0),
(1338, 'smartasses', NULL, NULL, 0),
(1339, 'tit wank', NULL, NULL, 0),
(1340, 'tities', NULL, NULL, 0),
(1341, 'wiseass', NULL, NULL, 0),
(1342, 'wiseasses', NULL, NULL, 0),
(1343, 'boong', NULL, NULL, 0),
(1344, 'coonnass', NULL, NULL, 0),
(1345, 'darn', NULL, NULL, 0),
(1346, 'Breeder', NULL, NULL, 0),
(1347, 'Cocklump', NULL, NULL, 0),
(1348, 'Doublelift', NULL, NULL, 0),
(1349, 'Dumbcunt', NULL, NULL, 0),
(1350, 'Fuck off', NULL, NULL, 0),
(1351, 'Poopuncher', NULL, NULL, 0),
(1352, 'Sandler', NULL, NULL, 0),
(1353, 'cockeye', NULL, NULL, 0),
(1354, 'crotte', NULL, NULL, 0),
(1355, 'cus', NULL, NULL, 0),
(1356, 'foah', NULL, NULL, 0),
(1357, 'fucktwat', NULL, NULL, 0),
(1358, 'jaggi', NULL, NULL, 0),
(1359, 'kunja', NULL, NULL, 0),
(1360, 'pust', NULL, NULL, 0),
(1361, 'sanger', NULL, NULL, 0),
(1362, 'seks', NULL, NULL, 0),
(1363, 'zubb', NULL, NULL, 0),
(1364, 'zibbi', NULL, NULL, 0),
(1365, 'blah', '2018-04-04 12:13:26', '2018-04-04 12:13:26', 0),
(1366, 'blabla', '2018-04-04 12:13:52', '2018-04-04 12:13:52', 0),
(1367, 'jerk', '2018-04-09 16:26:48', '2018-04-09 16:26:48', 0),
(1368, 'your', '2019-01-29 13:32:43', '2019-01-29 13:32:55', 1),
(1369, 'face', '2019-01-29 13:32:46', '2019-01-29 13:32:55', 1),
(1370, 'scam', '2019-01-29 13:32:51', '2019-01-29 13:32:55', 1),
(1371, 'bla', '2019-02-04 12:53:29', '2019-02-04 12:53:29', 0),
(1372, 'fake', '2019-05-06 20:01:39', '2019-05-06 20:01:43', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(10) UNSIGNED NOT NULL,
  `author_id` int(10) UNSIGNED DEFAULT NULL,
  `date_of_request` date DEFAULT NULL,
  `sales_person_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_handle` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `selected_product` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allocated_to` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `finished_at` time DEFAULT NULL,
  `check_1` tinyint(1) DEFAULT 0,
  `check_2` tinyint(1) NOT NULL DEFAULT 0,
  `check_3` tinyint(1) NOT NULL DEFAULT 0,
  `sent_to_client` time DEFAULT NULL,
  `remark` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_item`
--

CREATE TABLE `sales_item` (
  `id` int(11) NOT NULL,
  `supplier` tinytext NOT NULL,
  `brand` tinytext NOT NULL,
  `product_link` mediumtext NOT NULL,
  `title` tinytext NOT NULL,
  `old_price` tinytext DEFAULT NULL,
  `new_price` tinytext NOT NULL,
  `description` longtext DEFAULT NULL,
  `dimension` mediumtext DEFAULT NULL,
  `SKU` text NOT NULL,
  `country` text DEFAULT NULL,
  `material_used` mediumtext DEFAULT NULL,
  `color` text DEFAULT NULL,
  `images` longtext DEFAULT NULL,
  `sizes` text DEFAULT NULL,
  `category` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `satutory_tasks`
--

CREATE TABLE `satutory_tasks` (
  `id` int(10) UNSIGNED NOT NULL,
  `category` int(11) DEFAULT NULL,
  `assign_from` int(11) NOT NULL,
  `assign_to` int(11) NOT NULL,
  `assign_status` int(2) DEFAULT NULL,
  `task_details` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `task_subject` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `remark` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recurring_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recurring_day` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completion_date` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_messages`
--

CREATE TABLE `scheduled_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent` tinyint(1) NOT NULL DEFAULT 0,
  `sending_time` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_groups`
--

CREATE TABLE `schedule_groups` (
  `id` int(11) NOT NULL,
  `images` text NOT NULL,
  `description` text DEFAULT NULL,
  `scheduled_for` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `scraped_products`
--

CREATE TABLE `scraped_products` (
  `id` int(11) NOT NULL,
  `website` varchar(255) NOT NULL,
  `is_excel` tinyint(4) NOT NULL DEFAULT 0,
  `sku` varchar(255) NOT NULL,
  `has_sku` tinyint(1) NOT NULL DEFAULT 0,
  `title` text NOT NULL,
  `brand_id` int(10) UNSIGNED NOT NULL,
  `description` longtext DEFAULT NULL,
  `images` mediumtext NOT NULL,
  `currency` varchar(3) DEFAULT NULL,
  `price` varchar(255) NOT NULL,
  `price_eur` decimal(8,2) DEFAULT NULL,
  `discounted_price_eur` decimal(8,2) DEFAULT NULL,
  `size_system` varchar(2) DEFAULT NULL,
  `properties` longtext DEFAULT NULL,
  `url` mediumtext DEFAULT NULL,
  `is_property_updated` tinyint(4) NOT NULL DEFAULT 0,
  `is_price_updated` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_enriched` tinyint(1) NOT NULL DEFAULT 0,
  `can_be_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `is_color_fixed` tinyint(1) NOT NULL DEFAULT 0,
  `is_sale` tinyint(1) NOT NULL DEFAULT 0,
  `original_sku` varchar(255) DEFAULT NULL,
  `discounted_price` varchar(191) DEFAULT NULL,
  `last_inventory_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `scrap_activities`
--

CREATE TABLE `scrap_activities` (
  `id` int(10) UNSIGNED NOT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scraped_product_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scrap_counts`
--

CREATE TABLE `scrap_counts` (
  `id` int(10) UNSIGNED NOT NULL,
  `link_count` int(11) NOT NULL,
  `scraped_date` date NOT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scrap_entries`
--

CREATE TABLE `scrap_entries` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` longtext NOT NULL,
  `site_name` varchar(16) NOT NULL DEFAULT 'GNB',
  `is_scraped` tinyint(1) NOT NULL DEFAULT 0,
  `is_product_page` tinyint(1) NOT NULL DEFAULT 0,
  `pagination` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_updated_on_server` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `scrap_remarks`
--

CREATE TABLE `scrap_remarks` (
  `id` int(10) UNSIGNED NOT NULL,
  `scrap_id` int(11) NOT NULL,
  `module_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scraper_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remark` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scrap_statistics`
--

CREATE TABLE `scrap_statistics` (
  `id` int(10) UNSIGNED NOT NULL,
  `supplier` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `brand` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `search_terms`
--

CREATE TABLE `search_terms` (
  `id` int(10) UNSIGNED NOT NULL,
  `term` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` int(11) NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seo_analytics`
--

CREATE TABLE `seo_analytics` (
  `id` int(10) UNSIGNED NOT NULL,
  `domain_authority` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linking_authority` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inbound_links` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ranking_keywords` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `val` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` char(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sitejabber_q_a_s`
--

CREATE TABLE `sitejabber_q_a_s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('question','answer','reply') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sku_color_references`
--

CREATE TABLE `sku_color_references` (
  `id` int(10) UNSIGNED NOT NULL,
  `brand_id` int(11) NOT NULL,
  `color_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sku_formats`
--

CREATE TABLE `sku_formats` (
  `id` int(10) UNSIGNED NOT NULL,
  `brand_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `sku_examples` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku_format` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku_format_without_color` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `social_tags`
--

CREATE TABLE `social_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sops`
--

CREATE TABLE `sops` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `name`) VALUES
(1, 'import'),
(2, 'scrape'),
(3, 'ai'),
(4, 'auto crop'),
(5, 'crop approval'),
(6, 'crop sequencing'),
(7, 'image enhancement'),
(8, 'crop approval confirmation'),
(9, 'final approval'),
(10, 'manual attribute'),
(11, 'push to magento'),
(12, 'in magento'),
(13, 'unable to scrape'),
(14, 'unable to scrape image'),
(15, 'is being cropped'),
(16, 'crop skipped'),
(17, 'is being enhanced'),
(18, 'crop rejected'),
(19, 'is being sequenced'),
(20, 'is being scraped'),
(21, 'manual cropping'),
(22, 'manual image upload');

-- --------------------------------------------------------

--
-- Table structure for table `status_changes`
--

CREATE TABLE `status_changes` (
  `id` int(10) UNSIGNED NOT NULL,
  `model_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `from_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` int(11) NOT NULL,
  `courier` varchar(255) NOT NULL,
  `package_from` varchar(255) DEFAULT NULL,
  `awb` varchar(255) NOT NULL,
  `l_dimension` varchar(255) DEFAULT NULL,
  `w_dimension` varchar(255) DEFAULT NULL,
  `h_dimension` varchar(255) DEFAULT NULL,
  `weight` decimal(10,3) DEFAULT NULL,
  `pcs` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stock_products`
--

CREATE TABLE `stock_products` (
  `id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `suggestions`
--

CREATE TABLE `suggestions` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL,
  `brand` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` int(11) NOT NULL DEFAULT 5,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suggestion_products`
--

CREATE TABLE `suggestion_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `suggestion_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(10) UNSIGNED NOT NULL,
  `supplier_category_id` int(10) UNSIGNED DEFAULT NULL,
  `supplier_status_id` int(10) UNSIGNED DEFAULT NULL,
  `supplier` varchar(191) CHARACTER SET utf8mb4 NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_handle` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_handle` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gst` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_flagged` tinyint(1) NOT NULL DEFAULT 0,
  `has_error` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `source` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `brands` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_updated` tinyint(1) NOT NULL DEFAULT 1,
  `frequency` int(11) NOT NULL DEFAULT 0,
  `reminder_message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scraper_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inventory_lifetime` int(11) NOT NULL DEFAULT 2,
  `is_blocked` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_brand_counts`
--

CREATE TABLE `supplier_brand_counts` (
  `id` int(10) UNSIGNED NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `cnt` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `brand_id` int(11) NOT NULL,
  `url` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_brand_count_histories`
--

CREATE TABLE `supplier_brand_count_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `supplier_brand_count_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `cnt` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_category`
--

CREATE TABLE `supplier_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_category_counts`
--

CREATE TABLE `supplier_category_counts` (
  `id` int(10) UNSIGNED NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `cnt` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_inventory`
--

CREATE TABLE `supplier_inventory` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `inventory` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_status`
--

CREATE TABLE `supplier_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_status`
--

INSERT INTO `supplier_status` (`id`, `name`) VALUES
(1, 'Active'),
(2, 'Inactive');

-- --------------------------------------------------------

--
-- Table structure for table `s_e_ranking`
--

CREATE TABLE `s_e_ranking` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_check_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `s_e_rankings`
--

CREATE TABLE `s_e_rankings` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_check_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `targeted_accounts`
--

CREATE TABLE `targeted_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `platform_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `platform` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `target_locations`
--

CREATE TABLE `target_locations` (
  `id` int(10) UNSIGNED NOT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(10) UNSIGNED NOT NULL,
  `category` int(11) DEFAULT NULL,
  `assign_from` int(11) NOT NULL,
  `assign_to` int(11) NOT NULL,
  `assign_status` int(2) DEFAULT NULL,
  `is_statutory` int(11) NOT NULL,
  `is_private` tinyint(1) NOT NULL DEFAULT 0,
  `is_watched` tinyint(1) NOT NULL DEFAULT 0,
  `is_flagged` tinyint(1) NOT NULL DEFAULT 0,
  `task_details` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_subject` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `completion_date` timestamp NULL DEFAULT NULL,
  `remark` text CHARACTER SET utf8 DEFAULT NULL,
  `is_completed` timestamp NULL DEFAULT NULL,
  `is_verified` datetime DEFAULT NULL,
  `sending_time` datetime DEFAULT NULL,
  `time_slot` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `planned_at` date DEFAULT NULL,
  `pending_for` int(11) NOT NULL DEFAULT 0,
  `recurring_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statutory_id` int(11) DEFAULT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks_history`
--

CREATE TABLE `tasks_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `date_time` datetime NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `old_assignee` int(11) NOT NULL,
  `new_assignee` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_attachments`
--

CREATE TABLE `task_attachments` (
  `id` int(10) UNSIGNED NOT NULL,
  `task_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_categories`
--

CREATE TABLE `task_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_approved` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_categories`
--

INSERT INTO `task_categories` (`id`, `parent_id`, `title`, `is_approved`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 0, 'Select a Category', 1, NULL, NULL, NULL),
(3, 0, 'Admin', 1, NULL, '2018-11-01 16:20:42', '2018-11-01 16:20:42'),
(4, 0, 'HR', 1, NULL, '2018-11-01 16:20:42', '2018-11-01 16:20:42'),
(5, 0, 'Finance', 1, NULL, '2018-11-01 16:20:42', '2018-11-01 16:20:42'),
(6, 0, 'Sales & Marketing', 1, NULL, '2018-11-01 16:20:42', '2018-11-01 16:20:42'),
(7, 0, 'Personal', 1, NULL, '2018-11-01 16:20:42', '2018-11-01 16:20:42'),
(8, 0, 'Accounts', 1, NULL, '2018-11-01 16:20:42', '2018-11-01 16:20:42'),
(9, 0, 'Office Boys', 1, NULL, '2018-11-01 16:20:42', '2018-11-01 16:20:42'),
(10, 0, 'Product', 1, NULL, '2018-11-01 16:20:42', '2018-11-01 16:20:42'),
(11, 0, 'Social Media', 1, NULL, '2018-11-01 16:20:42', '2018-11-01 16:20:42'),
(12, 0, 'Purchase', 1, NULL, '2018-11-01 16:20:42', '2018-11-01 16:20:42'),
(13, 0, 'Listings', 1, NULL, '2019-06-08 19:55:02', '2019-06-08 19:55:02'),
(14, 0, 'Magento', 1, NULL, '2019-06-08 19:56:42', '2019-06-08 19:56:42'),
(15, 0, 'Personal', 1, NULL, '2019-06-09 17:28:53', '2019-06-09 17:28:53'),
(16, 0, 'SEO', 1, NULL, '2019-06-09 17:57:53', '2019-06-09 17:57:53'),
(17, 0, 'Listings', 1, NULL, '2019-06-09 21:59:49', '2019-06-09 21:59:49'),
(18, 15, 'Subcategory', 1, NULL, '2019-06-11 01:22:09', '2019-06-11 01:22:09'),
(19, 12, 'Supplier', 1, NULL, '2019-06-15 14:31:07', '2019-06-15 14:31:07'),
(20, 0, 'Social -', 1, NULL, '2019-06-16 21:04:20', '2019-06-16 21:04:20'),
(21, 3, 'Personal', 1, NULL, '2019-06-16 21:04:40', '2019-06-16 21:04:40'),
(22, 0, 'Test Category', 1, '2019-06-16 22:27:01', '2019-06-16 22:26:17', '2019-06-16 22:27:01'),
(23, 0, 'Test aaa', 1, '2019-06-16 22:37:25', NULL, '2019-06-16 22:37:25'),
(24, 0, 'Developement', 1, NULL, '2019-06-17 20:04:05', '2019-06-17 20:04:05'),
(25, 0, 'scrapping', 1, NULL, '2019-06-17 23:31:55', '2019-06-17 23:31:55'),
(26, 24, 'Bugs', 1, NULL, '2019-06-18 00:32:48', '2019-06-18 00:32:48'),
(27, 0, 'Tasks', 1, NULL, '2019-06-19 18:37:51', '2019-06-19 18:37:51'),
(28, 0, 'Vendor', 1, NULL, '2019-06-20 16:54:17', '2019-06-20 16:54:17'),
(29, 0, 'Legal', 1, NULL, '2019-06-22 00:27:09', '2019-06-22 00:27:09'),
(30, 0, 'Travel', 1, NULL, '2019-06-22 16:33:06', '2019-06-22 16:33:06'),
(31, 1, 'Visa', 1, NULL, '2019-06-22 16:33:06', '2019-06-22 16:33:06'),
(32, 30, 'Visa', 1, NULL, '2019-06-22 16:33:32', '2019-06-22 16:33:32'),
(33, 0, 'Health', 1, NULL, '2019-06-26 00:52:19', '2019-06-26 00:52:19'),
(34, 0, 'Designing', 1, NULL, '2019-07-31 02:19:45', '2019-07-31 02:19:45');

-- --------------------------------------------------------

--
-- Table structure for table `task_types`
--

CREATE TABLE `task_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_types`
--

INSERT INTO `task_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Devtask', '2019-09-21 10:29:16', '2019-09-21 10:29:16'),
(2, 'Sub-Task', '2019-09-21 10:29:36', '2019-09-21 10:29:36'),
(3, 'Issue/Bug-Fix', '2019-09-21 10:29:55', '2019-09-21 10:29:55');

-- --------------------------------------------------------

--
-- Table structure for table `task_users`
--

CREATE TABLE `task_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `task_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `no_of_images` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_agents`
--

CREATE TABLE `tracker_agents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` mediumtext NOT NULL,
  `browser` varchar(191) NOT NULL,
  `browser_version` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name_hash` varchar(65) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_connections`
--

CREATE TABLE `tracker_connections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_cookies`
--

CREATE TABLE `tracker_cookies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_devices`
--

CREATE TABLE `tracker_devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kind` varchar(16) NOT NULL,
  `model` varchar(64) NOT NULL,
  `platform` varchar(64) NOT NULL,
  `platform_version` varchar(16) NOT NULL,
  `is_mobile` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_domains`
--

CREATE TABLE `tracker_domains` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_errors`
--

CREATE TABLE `tracker_errors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(191) DEFAULT NULL,
  `message` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_events`
--

CREATE TABLE `tracker_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_events_log`
--

CREATE TABLE `tracker_events_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED DEFAULT NULL,
  `log_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_geoip`
--

CREATE TABLE `tracker_geoip` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `country_code` varchar(2) DEFAULT NULL,
  `country_code3` varchar(3) DEFAULT NULL,
  `country_name` varchar(191) DEFAULT NULL,
  `region` varchar(2) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `area_code` bigint(20) DEFAULT NULL,
  `dma_code` double DEFAULT NULL,
  `metro_code` double DEFAULT NULL,
  `continent_code` varchar(2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_log`
--

CREATE TABLE `tracker_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_id` bigint(20) UNSIGNED NOT NULL,
  `path_id` bigint(20) UNSIGNED DEFAULT NULL,
  `query_id` bigint(20) UNSIGNED DEFAULT NULL,
  `method` varchar(10) NOT NULL,
  `route_path_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_ajax` tinyint(1) NOT NULL,
  `is_secure` tinyint(1) NOT NULL,
  `is_json` tinyint(1) NOT NULL,
  `wants_json` tinyint(1) NOT NULL,
  `error_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `referer_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_paths`
--

CREATE TABLE `tracker_paths` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_queries`
--

CREATE TABLE `tracker_queries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `query` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_query_arguments`
--

CREATE TABLE `tracker_query_arguments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `query_id` bigint(20) UNSIGNED NOT NULL,
  `argument` varchar(191) NOT NULL,
  `value` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_referers`
--

CREATE TABLE `tracker_referers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `domain_id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(191) NOT NULL,
  `host` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `medium` varchar(191) DEFAULT NULL,
  `source` varchar(191) DEFAULT NULL,
  `search_terms_hash` varchar(191) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_referers_search_terms`
--

CREATE TABLE `tracker_referers_search_terms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `referer_id` bigint(20) UNSIGNED NOT NULL,
  `search_term` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_routes`
--

CREATE TABLE `tracker_routes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `action` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_route_paths`
--

CREATE TABLE `tracker_route_paths` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `route_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_route_path_parameters`
--

CREATE TABLE `tracker_route_path_parameters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `route_path_id` bigint(20) UNSIGNED NOT NULL,
  `parameter` varchar(191) NOT NULL,
  `value` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_sessions`
--

CREATE TABLE `tracker_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `device_id` bigint(20) UNSIGNED DEFAULT NULL,
  `agent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_ip` varchar(191) NOT NULL,
  `referer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cookie_id` bigint(20) UNSIGNED DEFAULT NULL,
  `geoip_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_robot` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `language_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_sql_queries`
--

CREATE TABLE `tracker_sql_queries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sha1` varchar(40) NOT NULL,
  `statement` text NOT NULL,
  `time` double NOT NULL,
  `connection_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_sql_queries_log`
--

CREATE TABLE `tracker_sql_queries_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_id` bigint(20) UNSIGNED NOT NULL,
  `sql_query_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_sql_query_bindings`
--

CREATE TABLE `tracker_sql_query_bindings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sha1` varchar(40) NOT NULL,
  `serialized` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_sql_query_bindings_parameters`
--

CREATE TABLE `tracker_sql_query_bindings_parameters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sql_query_bindings_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_system_classes`
--

CREATE TABLE `tracker_system_classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `responsible_user` int(11) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auth_token_hubstaff` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `last_checked` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `agent_role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_assigned` int(10) UNSIGNED DEFAULT NULL,
  `is_planner_completed` tinyint(1) NOT NULL DEFAULT 1,
  `crop_approval_rate` decimal(8,2) NOT NULL,
  `crop_rejection_rate` decimal(8,2) NOT NULL,
  `listing_approval_rate` decimal(8,2) DEFAULT NULL,
  `listing_rejection_rate` decimal(8,2) DEFAULT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_auto_comment_histories`
--

CREATE TABLE `users_auto_comment_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `auto_comment_history_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `is_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `is_paid` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_actions`
--

CREATE TABLE `user_actions` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_customers`
--

CREATE TABLE `user_customers` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_logins`
--

CREATE TABLE `user_logins` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_at` timestamp NULL DEFAULT NULL,
  `logout_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE `user_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_manual_crop`
--

CREATE TABLE `user_manual_crop` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_products`
--

CREATE TABLE `user_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_product_feedbacks`
--

CREATE TABLE `user_product_feedbacks` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `senior_user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_handle` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gst` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_iban` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_swift` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `frequency` int(11) NOT NULL DEFAULT 0,
  `reminder_message` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_error` tinyint(4) NOT NULL DEFAULT 0,
  `is_blocked` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_categories`
--

CREATE TABLE `vendor_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payments`
--

CREATE TABLE `vendor_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `currency` int(11) NOT NULL DEFAULT 0,
  `payment_date` date DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `payable_amount` decimal(13,4) DEFAULT NULL,
  `paid_amount` decimal(13,4) DEFAULT NULL,
  `service_provided` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_hour` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_products`
--

CREATE TABLE `vendor_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `date_of_order` datetime NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `payment_terms` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `received_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recurring_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `views`
--

CREATE TABLE `views` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `viewable_id` int(11) NOT NULL,
  `viewable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `views` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `delivery_approval_id` int(11) DEFAULT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `travel_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reject_reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reject_count` tinyint(4) NOT NULL DEFAULT 0,
  `resubmit_count` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voucher_categories`
--

CREATE TABLE `voucher_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `waybills`
--

CREATE TABLE `waybills` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `awb` varchar(255) NOT NULL,
  `box_length` double(8,2) NOT NULL,
  `box_width` double(8,2) NOT NULL,
  `box_height` double(8,2) NOT NULL,
  `actual_weight` double(8,2) NOT NULL,
  `package_slip` varchar(255) NOT NULL,
  `pickup_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `whats_app_configs`
--

CREATE TABLE `whats_app_configs` (
  `id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_customer_support` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whats_app_groups`
--

CREATE TABLE `whats_app_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `task_id` int(11) NOT NULL,
  `group_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whats_app_group_numbers`
--

CREATE TABLE `whats_app_group_numbers` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zoom_meetings`
--

CREATE TABLE `zoom_meetings` (
  `id` int(10) UNSIGNED NOT NULL,
  `meeting_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meeting_topic` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meeting_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meeting_agenda` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `join_meeting_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_meeting_url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date_time` datetime NOT NULL,
  `meeting_duration` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `host_zoom_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zoom_recording` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_deleted_from_zoom` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activities_routines`
--
ALTER TABLE `activities_routines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Indexes for table `ads_schedules`
--
ALTER TABLE `ads_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `article_categories`
--
ALTER TABLE `article_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assets_category`
--
ALTER TABLE `assets_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assets_manager`
--
ALTER TABLE `assets_manager`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assigned_user_pages`
--
ALTER TABLE `assigned_user_pages`
  ADD PRIMARY KEY (`user_id`,`menu_page_id`),
  ADD KEY `assigned_user_pages_user_id_index` (`user_id`),
  ADD KEY `assigned_user_pages_menu_page_id_index` (`menu_page_id`);

--
-- Indexes for table `assinged_department_menu`
--
ALTER TABLE `assinged_department_menu`
  ADD PRIMARY KEY (`department_id`,`menu_page_id`),
  ADD KEY `assinged_department_menu_department_id_index` (`department_id`),
  ADD KEY `assinged_department_menu_menu_page_id_index` (`menu_page_id`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attachments_uploaded_to_index` (`uploaded_to`);

--
-- Indexes for table `attribute_replacements`
--
ALTER TABLE `attribute_replacements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `automated_messages`
--
ALTER TABLE `automated_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auto_comment_histories`
--
ALTER TABLE `auto_comment_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auto_replies`
--
ALTER TABLE `auto_replies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auto_reply_hashtags`
--
ALTER TABLE `auto_reply_hashtags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `back_linkings`
--
ALTER TABLE `back_linkings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `back_link_checker`
--
ALTER TABLE `back_link_checker`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `back_link_checkers`
--
ALTER TABLE `back_link_checkers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `benchmarks`
--
ALTER TABLE `benchmarks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bloggers`
--
ALTER TABLE `bloggers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogger_email_templates`
--
ALTER TABLE `blogger_email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogger_payments`
--
ALTER TABLE `blogger_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogger_payments_blogger_id_foreign` (`blogger_id`),
  ADD KEY `blogger_payments_status_index` (`status`);

--
-- Indexes for table `blogger_products`
--
ALTER TABLE `blogger_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogger_products_blogger_id_foreign` (`blogger_id`),
  ADD KEY `blogger_products_brand_id_foreign` (`brand_id`);

--
-- Indexes for table `blogger_product_images`
--
ALTER TABLE `blogger_product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogger_product_images_blogger_product_id_foreign` (`blogger_product_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `books_slug_index` (`slug`),
  ADD KEY `books_created_by_index` (`created_by`),
  ADD KEY `books_updated_by_index` (`updated_by`),
  ADD KEY `books_restricted_index` (`restricted`);

--
-- Indexes for table `bookshelves`
--
ALTER TABLE `bookshelves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookshelves_slug_index` (`slug`),
  ADD KEY `bookshelves_created_by_index` (`created_by`),
  ADD KEY `bookshelves_updated_by_index` (`updated_by`),
  ADD KEY `bookshelves_restricted_index` (`restricted`);

--
-- Indexes for table `bookshelves_books`
--
ALTER TABLE `bookshelves_books`
  ADD PRIMARY KEY (`bookshelf_id`,`book_id`),
  ADD KEY `bookshelves_books_book_id_foreign` (`book_id`);

--
-- Indexes for table `book_activities`
--
ALTER TABLE `book_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_activities_book_id_index` (`book_id`),
  ADD KEY `book_activities_user_id_index` (`user_id`),
  ADD KEY `book_activities_entity_id_index` (`entity_id`);

--
-- Indexes for table `book_comments`
--
ALTER TABLE `book_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_comments_entity_id_entity_type_index` (`entity_id`,`entity_type`),
  ADD KEY `book_comments_local_id_index` (`local_id`);

--
-- Indexes for table `book_images`
--
ALTER TABLE `book_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_images_type_index` (`type`),
  ADD KEY `book_images_uploaded_to_index` (`uploaded_to`);

--
-- Indexes for table `book_tags`
--
ALTER TABLE `book_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_tags_entity_id_entity_type_index` (`entity_id`,`entity_type`),
  ADD KEY `book_tags_name_index` (`name`),
  ADD KEY `book_tags_value_index` (`value`),
  ADD KEY `book_tags_order_index` (`order`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brand_category_price_range`
--
ALTER TABLE `brand_category_price_range`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brand_fans`
--
ALTER TABLE `brand_fans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brand_reviews`
--
ALTER TABLE `brand_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brand_tagged_posts`
--
ALTER TABLE `brand_tagged_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `broadcast_images`
--
ALTER TABLE `broadcast_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `budget_categories`
--
ALTER TABLE `budget_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulk_customer_replies_keywords`
--
ALTER TABLE `bulk_customer_replies_keywords`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD UNIQUE KEY `cache_key_unique` (`key`);

--
-- Indexes for table `call_busy_messages`
--
ALTER TABLE `call_busy_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `call_histories`
--
ALTER TABLE `call_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `call_recordings`
--
ALTER TABLE `call_recordings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `call_recordings_lead_id` (`lead_id`),
  ADD KEY `call_recordings_order_id` (`order_id`);

--
-- Indexes for table `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cases_lawyer_id_foreign` (`lawyer_id`);

--
-- Indexes for table `case_costs`
--
ALTER TABLE `case_costs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `case_costs_case_id_foreign` (`case_id`);

--
-- Indexes for table `case_receivables`
--
ALTER TABLE `case_receivables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `case_receivables_case_id_foreign` (`case_id`),
  ADD KEY `case_receivables_status_index` (`status`);

--
-- Indexes for table `cash_flows`
--
ALTER TABLE `cash_flows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_flows_user_id_foreign` (`user_id`),
  ADD KEY `cash_flows_updated_by_foreign` (`updated_by`),
  ADD KEY `cash_flows_status_index` (`status`),
  ADD KEY `cash_flows_order_status_index` (`order_status`),
  ADD KEY `cash_flows_currency_index` (`currency`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_maps`
--
ALTER TABLE `category_maps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chapters`
--
ALTER TABLE `chapters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chapters_book_id_index` (`book_id`),
  ADD KEY `chapters_slug_index` (`slug`),
  ADD KEY `chapters_priority_index` (`priority`),
  ADD KEY `chapters_created_by_index` (`created_by`),
  ADD KEY `chapters_updated_by_index` (`updated_by`),
  ADD KEY `chapters_restricted_index` (`restricted`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_messages_lead_id` (`lead_id`),
  ADD KEY `chat_messages_order_id` (`order_id`),
  ADD KEY `chat_messages_supplier_id_foreign` (`supplier_id`),
  ADD KEY `chat_messages_task_id_foreign` (`task_id`),
  ADD KEY `chat_messages_erp_user_foreign` (`erp_user`),
  ADD KEY `chat_messages_vendor_id_foreign` (`vendor_id`),
  ADD KEY `chat_messages_lawyer_id_foreign` (`lawyer_id`),
  ADD KEY `chat_messages_case_id_foreign` (`case_id`),
  ADD KEY `chat_messages_blogger_id_foreign` (`blogger_id`),
  ADD KEY `chat_messages_customer_id_index` (`customer_id`),
  ADD KEY `chat_messages_voucher_id_foreign` (`voucher_id`),
  ADD KEY `chat_messages_group_id_index` (`group_id`),
  ADD KEY `unique_id` (`unique_id`);

--
-- Indexes for table `cold_leads`
--
ALTER TABLE `cold_leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cold_lead_broadcasts`
--
ALTER TABLE `cold_lead_broadcasts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `color_names_references`
--
ALTER TABLE `color_names_references`
  ADD PRIMARY KEY (`id`),
  ADD KEY `erp_name` (`erp_name`);

--
-- Indexes for table `color_references`
--
ALTER TABLE `color_references`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments_stats`
--
ALTER TABLE `comments_stats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `communication_histories`
--
ALTER TABLE `communication_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `competitor_followers`
--
ALTER TABLE `competitor_followers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `competitor_pages`
--
ALTER TABLE `competitor_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaints_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `complaint_threads`
--
ALTER TABLE `complaint_threads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaint_threads_complaint_id_foreign` (`complaint_id`),
  ADD KEY `complaint_threads_account_id_foreign` (`account_id`);

--
-- Indexes for table `compositions`
--
ALTER TABLE `compositions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contacts_user_id_foreign` (`user_id`);

--
-- Indexes for table `contact_bloggers`
--
ALTER TABLE `contact_bloggers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courier`
--
ALTER TABLE `courier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_jobs`
--
ALTER TABLE `cron_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_job_reports`
--
ALTER TABLE `cron_job_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cropped_image_references`
--
ALTER TABLE `cropped_image_references`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `crop_amends`
--
ALTER TABLE `crop_amends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `customer_categories`
--
ALTER TABLE `customer_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_marketing_platforms`
--
ALTER TABLE `customer_marketing_platforms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daily_activities`
--
ALTER TABLE `daily_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daily_cash_flows`
--
ALTER TABLE `daily_cash_flows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_approvals`
--
ALTER TABLE `delivery_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_approvals_private_view_id_foreign` (`private_view_id`),
  ADD KEY `delivery_approvals_assigned_user_id_foreign` (`assigned_user_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designers`
--
ALTER TABLE `designers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `developer_comments`
--
ALTER TABLE `developer_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `developer_costs`
--
ALTER TABLE `developer_costs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `developer_messages_alert_schedules`
--
ALTER TABLE `developer_messages_alert_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `developer_modules`
--
ALTER TABLE `developer_modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `developer_tasks`
--
ALTER TABLE `developer_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `developer_task_comments`
--
ALTER TABLE `developer_task_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_user_id_foreign` (`user_id`);

--
-- Indexes for table `document_categories`
--
ALTER TABLE `document_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_histories`
--
ALTER TABLE `document_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_remarks`
--
ALTER TABLE `document_remarks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_send_histories`
--
ALTER TABLE `document_send_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dubbizles`
--
ALTER TABLE `dubbizles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `entity_permissions`
--
ALTER TABLE `entity_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restrictions_restrictable_id_restrictable_type_index` (`restrictable_id`,`restrictable_type`),
  ADD KEY `restrictions_role_id_index` (`role_id`),
  ADD KEY `restrictions_action_index` (`action`);

--
-- Indexes for table `erp_accounts`
--
ALTER TABLE `erp_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `erp_leads`
--
ALTER TABLE `erp_leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `erp_leads_lead_status_id_index` (`lead_status_id`),
  ADD KEY `erp_leads_customer_id_index` (`customer_id`),
  ADD KEY `erp_leads_product_id_index` (`product_id`),
  ADD KEY `erp_leads_brand_id_index` (`brand_id`),
  ADD KEY `erp_leads_category_id_index` (`category_id`);

--
-- Indexes for table `erp_lead_status`
--
ALTER TABLE `erp_lead_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `excel_importers`
--
ALTER TABLE `excel_importers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `excel_importer_details`
--
ALTER TABLE `excel_importer_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facebook_messages`
--
ALTER TABLE `facebook_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `flagged_instagram_posts`
--
ALTER TABLE `flagged_instagram_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gmail_data`
--
ALTER TABLE `gmail_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `googlescrapping`
--
ALTER TABLE `googlescrapping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hashtag_posts`
--
ALTER TABLE `hashtag_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hashtag_post_comments`
--
ALTER TABLE `hashtag_post_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hashtag_post_histories`
--
ALTER TABLE `hashtag_post_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hashtag_post_likes`
--
ALTER TABLE `hashtag_post_likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hash_tags`
--
ALTER TABLE `hash_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_whatsapp_number`
--
ALTER TABLE `history_whatsapp_number`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `image_schedules`
--
ALTER TABLE `image_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `image_tags`
--
ALTER TABLE `image_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `im_queues`
--
ALTER TABLE `im_queues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `influencers`
--
ALTER TABLE `influencers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `influencers_d_ms`
--
ALTER TABLE `influencers_d_ms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instagram_automated_messages`
--
ALTER TABLE `instagram_automated_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instagram_auto_comments`
--
ALTER TABLE `instagram_auto_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instagram_bulk_messages`
--
ALTER TABLE `instagram_bulk_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instagram_direct_messages`
--
ALTER TABLE `instagram_direct_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instagram_posts`
--
ALTER TABLE `instagram_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instagram_posts_comments`
--
ALTER TABLE `instagram_posts_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instagram_threads`
--
ALTER TABLE `instagram_threads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instagram_users_lists`
--
ALTER TABLE `instagram_users_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `insta_messages`
--
ALTER TABLE `insta_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instructions`
--
ALTER TABLE `instructions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instructions_customer_id_index` (`customer_id`);

--
-- Indexes for table `instruction_categories`
--
ALTER TABLE `instruction_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `joint_permissions`
--
ALTER TABLE `joint_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `joint_permissions_entity_id_entity_type_index` (`entity_id`,`entity_type`),
  ADD KEY `joint_permissions_role_id_index` (`role_id`),
  ADD KEY `joint_permissions_action_index` (`action`),
  ADD KEY `joint_permissions_has_permission_index` (`has_permission`),
  ADD KEY `joint_permissions_has_permission_own_index` (`has_permission_own`),
  ADD KEY `joint_permissions_created_by_index` (`created_by`);

--
-- Indexes for table `keywords`
--
ALTER TABLE `keywords`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keyword_instructions`
--
ALTER TABLE `keyword_instructions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keyword_to_categories`
--
ALTER TABLE `keyword_to_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lawyers`
--
ALTER TABLE `lawyers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lawyers_speciality_id_foreign` (`speciality_id`);

--
-- Indexes for table `lawyer_specialities`
--
ALTER TABLE `lawyer_specialities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `links_to_posts`
--
ALTER TABLE `links_to_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `listing_histories`
--
ALTER TABLE `listing_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `listing_payments`
--
ALTER TABLE `listing_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_excel_imports`
--
ALTER TABLE `log_excel_imports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_google_vision`
--
ALTER TABLE `log_google_vision`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_magento`
--
ALTER TABLE `log_magento`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_scraper`
--
ALTER TABLE `log_scraper`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_scraper_website_index` (`website`);

--
-- Indexes for table `log_scraper_vs_ai`
--
ALTER TABLE `log_scraper_vs_ai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_scraper_vs_ai_product_id_foreign` (`product_id`);

--
-- Indexes for table `marketing_platforms`
--
ALTER TABLE `marketing_platforms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `media_disk_directory_filename_extension_unique` (`disk`,`directory`(30),`filename`(140),`extension`(6)),
  ADD KEY `media_disk_directory_index` (`disk`,`directory`),
  ADD KEY `media_aggregate_type_index` (`aggregate_type`);

--
-- Indexes for table `mediables`
--
ALTER TABLE `mediables`
  ADD PRIMARY KEY (`media_id`,`mediable_type`,`mediable_id`,`tag`(50)),
  ADD KEY `mediables_mediable_id_mediable_type_index` (`mediable_id`,`mediable_type`),
  ADD KEY `mediables_tag_index` (`tag`),
  ADD KEY `mediables_order_index` (`order`);

--
-- Indexes for table `menu_pages`
--
ALTER TABLE `menu_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_user_id_foreign` (`userid`);

--
-- Indexes for table `message_queues`
--
ALTER TABLE `message_queues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_queues_chat_message_id_foreign` (`chat_message_id`);

--
-- Indexes for table `messsage_applications`
--
ALTER TABLE `messsage_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indexes for table `monetary_accounts`
--
ALTER TABLE `monetary_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `negative_reviews`
--
ALTER TABLE `negative_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_queues`
--
ALTER TABLE `notification_queues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_personal_access_clients_client_id_index` (`client_id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `old`
--
ALTER TABLE `old`
  ADD PRIMARY KEY (`serial_no`);

--
-- Indexes for table `old_categories`
--
ALTER TABLE `old_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `old_incomings`
--
ALTER TABLE `old_incomings`
  ADD PRIMARY KEY (`serial_no`);

--
-- Indexes for table `old_payments`
--
ALTER TABLE `old_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `old_remarks`
--
ALTER TABLE `old_remarks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_products`
--
ALTER TABLE `order_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sku` (`sku`);

--
-- Indexes for table `order_reports`
--
ALTER TABLE `order_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_statuses`
--
ALTER TABLE `order_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pages_book_id_index` (`book_id`),
  ADD KEY `pages_chapter_id_index` (`chapter_id`),
  ADD KEY `pages_slug_index` (`slug`),
  ADD KEY `pages_priority_index` (`priority`),
  ADD KEY `pages_created_by_index` (`created_by`),
  ADD KEY `pages_updated_by_index` (`updated_by`),
  ADD KEY `pages_restricted_index` (`restricted`),
  ADD KEY `pages_draft_index` (`draft`),
  ADD KEY `pages_template_index` (`template`);

--
-- Indexes for table `page_notes`
--
ALTER TABLE `page_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_notes_category_id_foreign` (`category_id`);

--
-- Indexes for table `page_notes_categories`
--
ALTER TABLE `page_notes_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_notes_categories_name_index` (`name`);

--
-- Indexes for table `page_revisions`
--
ALTER TABLE `page_revisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_revisions_page_id_index` (`page_id`),
  ADD KEY `page_revisions_slug_index` (`slug`),
  ADD KEY `page_revisions_book_slug_index` (`book_slug`),
  ADD KEY `page_revisions_type_index` (`type`),
  ADD KEY `page_revisions_revision_number_index` (`revision_number`);

--
-- Indexes for table `page_screenshots`
--
ALTER TABLE `page_screenshots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `passwords`
--
ALTER TABLE `passwords`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_histories`
--
ALTER TABLE `password_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `people_names`
--
ALTER TABLE `people_names`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permissions_route_permission_index` (`route`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `permission_role_permission_id_index` (`permission_id`),
  ADD KEY `permission_role_role_id_index` (`role_id`);

--
-- Indexes for table `permission_user`
--
ALTER TABLE `permission_user`
  ADD PRIMARY KEY (`user_id`,`permission_id`),
  ADD KEY `permission_user_user_id_index` (`user_id`),
  ADD KEY `permission_user_permission_id_index` (`permission_id`);

--
-- Indexes for table `picture_colors`
--
ALTER TABLE `picture_colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinterest_boards`
--
ALTER TABLE `pinterest_boards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pinterest_boards_pinterest_users_id_foreign` (`pinterest_users_id`);

--
-- Indexes for table `pinterest_users`
--
ALTER TABLE `pinterest_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pre_accounts`
--
ALTER TABLE `pre_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `price_comparison`
--
ALTER TABLE `price_comparison`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `price_comparison_site`
--
ALTER TABLE `price_comparison_site`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `priorities`
--
ALTER TABLE `priorities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `private_views`
--
ALTER TABLE `private_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `private_views_assigned_user_id_foreign` (`assigned_user_id`);

--
-- Indexes for table `private_view_products`
--
ALTER TABLE `private_view_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_brand_index` (`brand`),
  ADD KEY `products_supplier_index` (`supplier`(250)),
  ADD KEY `products_is_on_sale_index` (`is_on_sale`),
  ADD KEY `products_listing_approved_at_index` (`listing_approved_at`),
  ADD KEY `products_status_id_foreign` (`status_id`);
ALTER TABLE `products` ADD FULLTEXT KEY `products_sku_index` (`sku`);

--
-- Indexes for table `product_dispatch`
--
ALTER TABLE `product_dispatch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_dispatch_product_id_index` (`product_id`),
  ADD KEY `product_dispatch_created_by_index` (`created_by`);

--
-- Indexes for table `product_location`
--
ALTER TABLE `product_location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_location_history`
--
ALTER TABLE `product_location_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_location_history_product_id_index` (`product_id`),
  ADD KEY `product_location_history_created_by_index` (`created_by`);

--
-- Indexes for table `product_quicksell_groups`
--
ALTER TABLE `product_quicksell_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_references`
--
ALTER TABLE `product_references`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_references_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_status`
--
ALTER TABLE `product_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_suppliers`
--
ALTER TABLE `product_suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_templates`
--
ALTER TABLE `product_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proxies`
--
ALTER TABLE `proxies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchases_purchase_status_id_foreign` (`purchase_status_id`);

--
-- Indexes for table `purchase_discounts`
--
ALTER TABLE `purchase_discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_discounts_purchase_id_foreign` (`purchase_id`);

--
-- Indexes for table `purchase_order_customer`
--
ALTER TABLE `purchase_order_customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_customer_purchase_id_index` (`purchase_id`),
  ADD KEY `purchase_order_customer_customer_id_index` (`customer_id`);

--
-- Indexes for table `purchase_products`
--
ALTER TABLE `purchase_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_product_supplier`
--
ALTER TABLE `purchase_product_supplier`
  ADD KEY `purchase_product_supplier_product_id_index` (`product_id`),
  ADD KEY `purchase_product_supplier_supplier_id_index` (`supplier_id`),
  ADD KEY `purchase_product_supplier_chat_message_id_index` (`chat_message_id`);

--
-- Indexes for table `purchase_status`
--
ALTER TABLE `purchase_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `push_notifications`
--
ALTER TABLE `push_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quick_replies`
--
ALTER TABLE `quick_replies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quick_sell_groups`
--
ALTER TABLE `quick_sell_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rejected_leads`
--
ALTER TABLE `rejected_leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `remarks`
--
ALTER TABLE `remarks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reply_categories`
--
ALTER TABLE `reply_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resource_categories`
--
ALTER TABLE `resource_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resource_images`
--
ALTER TABLE `resource_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_account_id_foreign` (`account_id`),
  ADD KEY `reviews_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `review_schedules`
--
ALTER TABLE `review_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `review_schedules_account_id_foreign` (`account_id`),
  ADD KEY `review_schedules_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_user_user_id_index` (`user_id`),
  ADD KEY `role_user_role_id_index` (`role_id`);

--
-- Indexes for table `rude_words`
--
ALTER TABLE `rude_words`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_item`
--
ALTER TABLE `sales_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `satutory_tasks`
--
ALTER TABLE `satutory_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scheduled_messages`
--
ALTER TABLE `scheduled_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scheduled_messages_user_id_foreign` (`user_id`),
  ADD KEY `scheduled_messages_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `schedule_groups`
--
ALTER TABLE `schedule_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scraped_products`
--
ALTER TABLE `scraped_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scraped_products_sku_index` (`sku`),
  ADD KEY `scraped_products_last_inventory_at_index` (`last_inventory_at`),
  ADD KEY `scraped_products_is_excel_index` (`is_excel`);

--
-- Indexes for table `scrap_activities`
--
ALTER TABLE `scrap_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scrap_counts`
--
ALTER TABLE `scrap_counts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scrap_entries`
--
ALTER TABLE `scrap_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scrap_remarks`
--
ALTER TABLE `scrap_remarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scrap_remarks_scraper_name_index` (`scraper_name`);

--
-- Indexes for table `scrap_statistics`
--
ALTER TABLE `scrap_statistics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `search_terms`
--
ALTER TABLE `search_terms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `search_terms_entity_type_entity_id_index` (`entity_type`,`entity_id`),
  ADD KEY `search_terms_term_index` (`term`),
  ADD KEY `search_terms_entity_type_index` (`entity_type`),
  ADD KEY `search_terms_score_index` (`score`);

--
-- Indexes for table `seo_analytics`
--
ALTER TABLE `seo_analytics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sitejabber_q_a_s`
--
ALTER TABLE `sitejabber_q_a_s`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sku_color_references`
--
ALTER TABLE `sku_color_references`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sku_formats`
--
ALTER TABLE `sku_formats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_tags`
--
ALTER TABLE `social_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sops`
--
ALTER TABLE `sops`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status_changes`
--
ALTER TABLE `status_changes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_changes_user_id_foreign` (`user_id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_products`
--
ALTER TABLE `stock_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suggestions`
--
ALTER TABLE `suggestions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `suggestions_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `suggestion_products`
--
ALTER TABLE `suggestion_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `suggestion_products_suggestion_id_foreign` (`suggestion_id`),
  ADD KEY `suggestion_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suppliers_supplier_unique` (`supplier`),
  ADD KEY `suppliers_supplier_category_id_foreign` (`supplier_category_id`),
  ADD KEY `suppliers_supplier_status_id_foreign` (`supplier_status_id`),
  ADD KEY `suppliers_scraper_name_index` (`scraper_name`);

--
-- Indexes for table `supplier_brand_counts`
--
ALTER TABLE `supplier_brand_counts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_brand_count_histories`
--
ALTER TABLE `supplier_brand_count_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_category`
--
ALTER TABLE `supplier_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_category_counts`
--
ALTER TABLE `supplier_category_counts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_inventory`
--
ALTER TABLE `supplier_inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_status`
--
ALTER TABLE `supplier_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `s_e_rankings`
--
ALTER TABLE `s_e_rankings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `targeted_accounts`
--
ALTER TABLE `targeted_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `target_locations`
--
ALTER TABLE `target_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks_history`
--
ALTER TABLE `tasks_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_attachments`
--
ALTER TABLE `task_attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_categories`
--
ALTER TABLE `task_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_types`
--
ALTER TABLE `task_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_users`
--
ALTER TABLE `task_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_users_task_id_foreign` (`task_id`),
  ADD KEY `task_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tracker_agents`
--
ALTER TABLE `tracker_agents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tracker_agents_name_hash_unique` (`name_hash`),
  ADD KEY `tracker_agents_created_at_index` (`created_at`),
  ADD KEY `tracker_agents_updated_at_index` (`updated_at`),
  ADD KEY `tracker_agents_browser_index` (`browser`);

--
-- Indexes for table `tracker_connections`
--
ALTER TABLE `tracker_connections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_connections_created_at_index` (`created_at`),
  ADD KEY `tracker_connections_updated_at_index` (`updated_at`),
  ADD KEY `tracker_connections_name_index` (`name`);

--
-- Indexes for table `tracker_cookies`
--
ALTER TABLE `tracker_cookies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tracker_cookies_uuid_unique` (`uuid`),
  ADD KEY `tracker_cookies_created_at_index` (`created_at`),
  ADD KEY `tracker_cookies_updated_at_index` (`updated_at`);

--
-- Indexes for table `tracker_devices`
--
ALTER TABLE `tracker_devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tracker_devices_kind_model_platform_platform_version_unique` (`kind`,`model`,`platform`,`platform_version`),
  ADD KEY `tracker_devices_created_at_index` (`created_at`),
  ADD KEY `tracker_devices_updated_at_index` (`updated_at`),
  ADD KEY `tracker_devices_kind_index` (`kind`),
  ADD KEY `tracker_devices_model_index` (`model`),
  ADD KEY `tracker_devices_platform_index` (`platform`),
  ADD KEY `tracker_devices_platform_version_index` (`platform_version`);

--
-- Indexes for table `tracker_domains`
--
ALTER TABLE `tracker_domains`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_domains_created_at_index` (`created_at`),
  ADD KEY `tracker_domains_updated_at_index` (`updated_at`),
  ADD KEY `tracker_domains_name_index` (`name`);

--
-- Indexes for table `tracker_errors`
--
ALTER TABLE `tracker_errors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_errors_created_at_index` (`created_at`),
  ADD KEY `tracker_errors_updated_at_index` (`updated_at`),
  ADD KEY `tracker_errors_code_index` (`code`),
  ADD KEY `tracker_errors_message_index` (`message`);

--
-- Indexes for table `tracker_events`
--
ALTER TABLE `tracker_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_events_created_at_index` (`created_at`),
  ADD KEY `tracker_events_updated_at_index` (`updated_at`),
  ADD KEY `tracker_events_name_index` (`name`);

--
-- Indexes for table `tracker_events_log`
--
ALTER TABLE `tracker_events_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_events_log_created_at_index` (`created_at`),
  ADD KEY `tracker_events_log_updated_at_index` (`updated_at`),
  ADD KEY `tracker_events_log_event_id_index` (`event_id`),
  ADD KEY `tracker_events_log_class_id_index` (`class_id`),
  ADD KEY `tracker_events_log_log_id_index` (`log_id`);

--
-- Indexes for table `tracker_geoip`
--
ALTER TABLE `tracker_geoip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_geoip_created_at_index` (`created_at`),
  ADD KEY `tracker_geoip_updated_at_index` (`updated_at`),
  ADD KEY `tracker_geoip_latitude_index` (`latitude`),
  ADD KEY `tracker_geoip_longitude_index` (`longitude`),
  ADD KEY `tracker_geoip_country_code_index` (`country_code`),
  ADD KEY `tracker_geoip_country_code3_index` (`country_code3`),
  ADD KEY `tracker_geoip_country_name_index` (`country_name`),
  ADD KEY `tracker_geoip_city_index` (`city`);

--
-- Indexes for table `tracker_log`
--
ALTER TABLE `tracker_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_log_created_at_index` (`created_at`),
  ADD KEY `tracker_log_updated_at_index` (`updated_at`),
  ADD KEY `tracker_log_session_id_index` (`session_id`),
  ADD KEY `tracker_log_path_id_index` (`path_id`),
  ADD KEY `tracker_log_query_id_index` (`query_id`),
  ADD KEY `tracker_log_method_index` (`method`),
  ADD KEY `tracker_log_route_path_id_index` (`route_path_id`),
  ADD KEY `tracker_log_error_id_index` (`error_id`),
  ADD KEY `tracker_log_referer_id_index` (`referer_id`);

--
-- Indexes for table `tracker_paths`
--
ALTER TABLE `tracker_paths`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_paths_created_at_index` (`created_at`),
  ADD KEY `tracker_paths_updated_at_index` (`updated_at`),
  ADD KEY `tracker_paths_path_index` (`path`);

--
-- Indexes for table `tracker_queries`
--
ALTER TABLE `tracker_queries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_queries_created_at_index` (`created_at`),
  ADD KEY `tracker_queries_updated_at_index` (`updated_at`),
  ADD KEY `tracker_queries_query_index` (`query`);

--
-- Indexes for table `tracker_query_arguments`
--
ALTER TABLE `tracker_query_arguments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_query_arguments_created_at_index` (`created_at`),
  ADD KEY `tracker_query_arguments_updated_at_index` (`updated_at`),
  ADD KEY `tracker_query_arguments_query_id_index` (`query_id`),
  ADD KEY `tracker_query_arguments_argument_index` (`argument`),
  ADD KEY `tracker_query_arguments_value_index` (`value`);

--
-- Indexes for table `tracker_referers`
--
ALTER TABLE `tracker_referers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_referers_created_at_index` (`created_at`),
  ADD KEY `tracker_referers_updated_at_index` (`updated_at`),
  ADD KEY `tracker_referers_domain_id_index` (`domain_id`),
  ADD KEY `tracker_referers_url_index` (`url`),
  ADD KEY `tracker_referers_medium_index` (`medium`),
  ADD KEY `tracker_referers_source_index` (`source`),
  ADD KEY `tracker_referers_search_terms_hash_index` (`search_terms_hash`);

--
-- Indexes for table `tracker_referers_search_terms`
--
ALTER TABLE `tracker_referers_search_terms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_referers_search_terms_created_at_index` (`created_at`),
  ADD KEY `tracker_referers_search_terms_updated_at_index` (`updated_at`),
  ADD KEY `tracker_referers_search_terms_referer_id_index` (`referer_id`),
  ADD KEY `tracker_referers_search_terms_search_term_index` (`search_term`);

--
-- Indexes for table `tracker_routes`
--
ALTER TABLE `tracker_routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_routes_created_at_index` (`created_at`),
  ADD KEY `tracker_routes_updated_at_index` (`updated_at`),
  ADD KEY `tracker_routes_name_index` (`name`),
  ADD KEY `tracker_routes_action_index` (`action`);

--
-- Indexes for table `tracker_route_paths`
--
ALTER TABLE `tracker_route_paths`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_route_paths_created_at_index` (`created_at`),
  ADD KEY `tracker_route_paths_updated_at_index` (`updated_at`),
  ADD KEY `tracker_route_paths_route_id_index` (`route_id`),
  ADD KEY `tracker_route_paths_path_index` (`path`);

--
-- Indexes for table `tracker_route_path_parameters`
--
ALTER TABLE `tracker_route_path_parameters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_route_path_parameters_created_at_index` (`created_at`),
  ADD KEY `tracker_route_path_parameters_updated_at_index` (`updated_at`),
  ADD KEY `tracker_route_path_parameters_route_path_id_index` (`route_path_id`),
  ADD KEY `tracker_route_path_parameters_parameter_index` (`parameter`),
  ADD KEY `tracker_route_path_parameters_value_index` (`value`);

--
-- Indexes for table `tracker_sessions`
--
ALTER TABLE `tracker_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tracker_sessions_uuid_unique` (`uuid`),
  ADD KEY `tracker_sessions_created_at_index` (`created_at`),
  ADD KEY `tracker_sessions_updated_at_index` (`updated_at`),
  ADD KEY `tracker_sessions_user_id_index` (`user_id`),
  ADD KEY `tracker_sessions_device_id_index` (`device_id`),
  ADD KEY `tracker_sessions_agent_id_index` (`agent_id`),
  ADD KEY `tracker_sessions_client_ip_index` (`client_ip`),
  ADD KEY `tracker_sessions_referer_id_index` (`referer_id`),
  ADD KEY `tracker_sessions_cookie_id_index` (`cookie_id`),
  ADD KEY `tracker_sessions_geoip_id_index` (`geoip_id`),
  ADD KEY `tracker_sessions_language_id_index` (`language_id`);

--
-- Indexes for table `tracker_sql_queries`
--
ALTER TABLE `tracker_sql_queries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_sql_queries_created_at_index` (`created_at`),
  ADD KEY `tracker_sql_queries_updated_at_index` (`updated_at`),
  ADD KEY `tracker_sql_queries_sha1_index` (`sha1`),
  ADD KEY `tracker_sql_queries_time_index` (`time`);

--
-- Indexes for table `tracker_sql_queries_log`
--
ALTER TABLE `tracker_sql_queries_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_sql_queries_log_created_at_index` (`created_at`),
  ADD KEY `tracker_sql_queries_log_updated_at_index` (`updated_at`),
  ADD KEY `tracker_sql_queries_log_log_id_index` (`log_id`),
  ADD KEY `tracker_sql_queries_log_sql_query_id_index` (`sql_query_id`);

--
-- Indexes for table `tracker_sql_query_bindings`
--
ALTER TABLE `tracker_sql_query_bindings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_sql_query_bindings_created_at_index` (`created_at`),
  ADD KEY `tracker_sql_query_bindings_updated_at_index` (`updated_at`),
  ADD KEY `tracker_sql_query_bindings_sha1_index` (`sha1`);

--
-- Indexes for table `tracker_sql_query_bindings_parameters`
--
ALTER TABLE `tracker_sql_query_bindings_parameters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_sql_query_bindings_parameters_created_at_index` (`created_at`),
  ADD KEY `tracker_sql_query_bindings_parameters_updated_at_index` (`updated_at`),
  ADD KEY `tracker_sql_query_bindings_parameters_name_index` (`name`),
  ADD KEY `tracker_sqlqb_parameters` (`sql_query_bindings_id`);

--
-- Indexes for table `tracker_system_classes`
--
ALTER TABLE `tracker_system_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracker_system_classes_created_at_index` (`created_at`),
  ADD KEY `tracker_system_classes_updated_at_index` (`updated_at`),
  ADD KEY `tracker_system_classes_name_index` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `users_auto_comment_histories`
--
ALTER TABLE `users_auto_comment_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_actions`
--
ALTER TABLE `user_actions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_customers`
--
ALTER TABLE `user_customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_logins`
--
ALTER TABLE `user_logins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_manual_crop`
--
ALTER TABLE `user_manual_crop`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_manual_crop_user_id_foreign` (`user_id`),
  ADD KEY `user_manual_crop_product_id_foreign` (`product_id`);

--
-- Indexes for table `user_products`
--
ALTER TABLE `user_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_products_user_id_foreign` (`user_id`),
  ADD KEY `user_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `user_product_feedbacks`
--
ALTER TABLE `user_product_feedbacks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_categories`
--
ALTER TABLE `vendor_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_payments_vendor_id_foreign` (`vendor_id`),
  ADD KEY `vendor_payments_status_index` (`status`);

--
-- Indexes for table `vendor_products`
--
ALTER TABLE `vendor_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_products_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `views`
--
ALTER TABLE `views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `views_user_id_index` (`user_id`),
  ADD KEY `views_viewable_id_index` (`viewable_id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vouchers_delivery_approval_id_foreign` (`delivery_approval_id`);

--
-- Indexes for table `voucher_categories`
--
ALTER TABLE `voucher_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `waybills`
--
ALTER TABLE `waybills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- Indexes for table `whats_app_configs`
--
ALTER TABLE `whats_app_configs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `whats_app_groups`
--
ALTER TABLE `whats_app_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `whats_app_groups_group_id_index` (`group_id`);

--
-- Indexes for table `whats_app_group_numbers`
--
ALTER TABLE `whats_app_group_numbers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zoom_meetings`
--
ALTER TABLE `zoom_meetings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activities_routines`
--
ALTER TABLE `activities_routines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads_schedules`
--
ALTER TABLE `ads_schedules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agents`
--
ALTER TABLE `agents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `article_categories`
--
ALTER TABLE `article_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assets_category`
--
ALTER TABLE `assets_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assets_manager`
--
ALTER TABLE `assets_manager`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attribute_replacements`
--
ALTER TABLE `attribute_replacements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `automated_messages`
--
ALTER TABLE `automated_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auto_comment_histories`
--
ALTER TABLE `auto_comment_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auto_replies`
--
ALTER TABLE `auto_replies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auto_reply_hashtags`
--
ALTER TABLE `auto_reply_hashtags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `back_linkings`
--
ALTER TABLE `back_linkings`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `back_link_checker`
--
ALTER TABLE `back_link_checker`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `back_link_checkers`
--
ALTER TABLE `back_link_checkers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `benchmarks`
--
ALTER TABLE `benchmarks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bloggers`
--
ALTER TABLE `bloggers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blogger_email_templates`
--
ALTER TABLE `blogger_email_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blogger_payments`
--
ALTER TABLE `blogger_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blogger_products`
--
ALTER TABLE `blogger_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blogger_product_images`
--
ALTER TABLE `blogger_product_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookshelves`
--
ALTER TABLE `bookshelves`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `book_activities`
--
ALTER TABLE `book_activities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `book_comments`
--
ALTER TABLE `book_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `book_images`
--
ALTER TABLE `book_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `book_tags`
--
ALTER TABLE `book_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `brand_category_price_range`
--
ALTER TABLE `brand_category_price_range`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brand_fans`
--
ALTER TABLE `brand_fans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brand_reviews`
--
ALTER TABLE `brand_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brand_tagged_posts`
--
ALTER TABLE `brand_tagged_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `broadcast_images`
--
ALTER TABLE `broadcast_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budget_categories`
--
ALTER TABLE `budget_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bulk_customer_replies_keywords`
--
ALTER TABLE `bulk_customer_replies_keywords`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `call_busy_messages`
--
ALTER TABLE `call_busy_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `call_histories`
--
ALTER TABLE `call_histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `call_recordings`
--
ALTER TABLE `call_recordings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `case_costs`
--
ALTER TABLE `case_costs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `case_receivables`
--
ALTER TABLE `case_receivables`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_flows`
--
ALTER TABLE `cash_flows`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `category_maps`
--
ALTER TABLE `category_maps`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chapters`
--
ALTER TABLE `chapters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cold_leads`
--
ALTER TABLE `cold_leads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cold_lead_broadcasts`
--
ALTER TABLE `cold_lead_broadcasts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `color_names_references`
--
ALTER TABLE `color_names_references`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `color_references`
--
ALTER TABLE `color_references`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments_stats`
--
ALTER TABLE `comments_stats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `communication_histories`
--
ALTER TABLE `communication_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `competitor_followers`
--
ALTER TABLE `competitor_followers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `competitor_pages`
--
ALTER TABLE `competitor_pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaint_threads`
--
ALTER TABLE `complaint_threads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `compositions`
--
ALTER TABLE `compositions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_bloggers`
--
ALTER TABLE `contact_bloggers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courier`
--
ALTER TABLE `courier`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cron_jobs`
--
ALTER TABLE `cron_jobs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cron_job_reports`
--
ALTER TABLE `cron_job_reports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cropped_image_references`
--
ALTER TABLE `cropped_image_references`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crop_amends`
--
ALTER TABLE `crop_amends`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_categories`
--
ALTER TABLE `customer_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_marketing_platforms`
--
ALTER TABLE `customer_marketing_platforms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_activities`
--
ALTER TABLE `daily_activities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_cash_flows`
--
ALTER TABLE `daily_cash_flows`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_approvals`
--
ALTER TABLE `delivery_approvals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `designers`
--
ALTER TABLE `designers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `developer_comments`
--
ALTER TABLE `developer_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `developer_costs`
--
ALTER TABLE `developer_costs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `developer_messages_alert_schedules`
--
ALTER TABLE `developer_messages_alert_schedules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `developer_modules`
--
ALTER TABLE `developer_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `developer_tasks`
--
ALTER TABLE `developer_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `developer_task_comments`
--
ALTER TABLE `developer_task_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_categories`
--
ALTER TABLE `document_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_histories`
--
ALTER TABLE `document_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_remarks`
--
ALTER TABLE `document_remarks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_send_histories`
--
ALTER TABLE `document_send_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dubbizles`
--
ALTER TABLE `dubbizles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `entity_permissions`
--
ALTER TABLE `entity_permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_accounts`
--
ALTER TABLE `erp_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_leads`
--
ALTER TABLE `erp_leads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_lead_status`
--
ALTER TABLE `erp_lead_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `excel_importers`
--
ALTER TABLE `excel_importers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `excel_importer_details`
--
ALTER TABLE `excel_importer_details`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facebook_messages`
--
ALTER TABLE `facebook_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flagged_instagram_posts`
--
ALTER TABLE `flagged_instagram_posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gmail_data`
--
ALTER TABLE `gmail_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `googlescrapping`
--
ALTER TABLE `googlescrapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_members`
--
ALTER TABLE `group_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hashtag_posts`
--
ALTER TABLE `hashtag_posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hashtag_post_comments`
--
ALTER TABLE `hashtag_post_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hashtag_post_histories`
--
ALTER TABLE `hashtag_post_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hashtag_post_likes`
--
ALTER TABLE `hashtag_post_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hash_tags`
--
ALTER TABLE `hash_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history_whatsapp_number`
--
ALTER TABLE `history_whatsapp_number`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `image_schedules`
--
ALTER TABLE `image_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `image_tags`
--
ALTER TABLE `image_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `im_queues`
--
ALTER TABLE `im_queues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `influencers`
--
ALTER TABLE `influencers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `influencers_d_ms`
--
ALTER TABLE `influencers_d_ms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instagram_automated_messages`
--
ALTER TABLE `instagram_automated_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instagram_auto_comments`
--
ALTER TABLE `instagram_auto_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instagram_bulk_messages`
--
ALTER TABLE `instagram_bulk_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instagram_direct_messages`
--
ALTER TABLE `instagram_direct_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instagram_posts`
--
ALTER TABLE `instagram_posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instagram_posts_comments`
--
ALTER TABLE `instagram_posts_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instagram_threads`
--
ALTER TABLE `instagram_threads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instagram_users_lists`
--
ALTER TABLE `instagram_users_lists`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `insta_messages`
--
ALTER TABLE `insta_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instructions`
--
ALTER TABLE `instructions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instruction_categories`
--
ALTER TABLE `instruction_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `joint_permissions`
--
ALTER TABLE `joint_permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keywords`
--
ALTER TABLE `keywords`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keyword_instructions`
--
ALTER TABLE `keyword_instructions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `keyword_to_categories`
--
ALTER TABLE `keyword_to_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lawyers`
--
ALTER TABLE `lawyers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lawyer_specialities`
--
ALTER TABLE `lawyer_specialities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `links_to_posts`
--
ALTER TABLE `links_to_posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `listing_histories`
--
ALTER TABLE `listing_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `listing_payments`
--
ALTER TABLE `listing_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_excel_imports`
--
ALTER TABLE `log_excel_imports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_google_vision`
--
ALTER TABLE `log_google_vision`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_magento`
--
ALTER TABLE `log_magento`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_scraper`
--
ALTER TABLE `log_scraper`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_scraper_vs_ai`
--
ALTER TABLE `log_scraper_vs_ai`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marketing_platforms`
--
ALTER TABLE `marketing_platforms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_pages`
--
ALTER TABLE `menu_pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message_queues`
--
ALTER TABLE `message_queues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messsage_applications`
--
ALTER TABLE `messsage_applications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `monetary_accounts`
--
ALTER TABLE `monetary_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `negative_reviews`
--
ALTER TABLE `negative_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_queues`
--
ALTER TABLE `notification_queues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `old`
--
ALTER TABLE `old`
  MODIFY `serial_no` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `old_categories`
--
ALTER TABLE `old_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `old_incomings`
--
ALTER TABLE `old_incomings`
  MODIFY `serial_no` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `old_payments`
--
ALTER TABLE `old_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `old_remarks`
--
ALTER TABLE `old_remarks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_products`
--
ALTER TABLE `order_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_reports`
--
ALTER TABLE `order_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_statuses`
--
ALTER TABLE `order_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `page_notes`
--
ALTER TABLE `page_notes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `page_notes_categories`
--
ALTER TABLE `page_notes_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `page_revisions`
--
ALTER TABLE `page_revisions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `page_screenshots`
--
ALTER TABLE `page_screenshots`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `passwords`
--
ALTER TABLE `passwords`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_histories`
--
ALTER TABLE `password_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `people_names`
--
ALTER TABLE `people_names`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT for table `picture_colors`
--
ALTER TABLE `picture_colors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pinterest_boards`
--
ALTER TABLE `pinterest_boards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pinterest_users`
--
ALTER TABLE `pinterest_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pre_accounts`
--
ALTER TABLE `pre_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `price_comparison`
--
ALTER TABLE `price_comparison`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `price_comparison_site`
--
ALTER TABLE `price_comparison_site`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `priorities`
--
ALTER TABLE `priorities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `private_views`
--
ALTER TABLE `private_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `private_view_products`
--
ALTER TABLE `private_view_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_dispatch`
--
ALTER TABLE `product_dispatch`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_location`
--
ALTER TABLE `product_location`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_location_history`
--
ALTER TABLE `product_location_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_quicksell_groups`
--
ALTER TABLE `product_quicksell_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_references`
--
ALTER TABLE `product_references`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_status`
--
ALTER TABLE `product_status`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126575;

--
-- AUTO_INCREMENT for table `product_suppliers`
--
ALTER TABLE `product_suppliers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_templates`
--
ALTER TABLE `product_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `proxies`
--
ALTER TABLE `proxies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_discounts`
--
ALTER TABLE `purchase_discounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_customer`
--
ALTER TABLE `purchase_order_customer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_products`
--
ALTER TABLE `purchase_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_status`
--
ALTER TABLE `purchase_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `push_notifications`
--
ALTER TABLE `push_notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quick_replies`
--
ALTER TABLE `quick_replies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quick_sell_groups`
--
ALTER TABLE `quick_sell_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rejected_leads`
--
ALTER TABLE `rejected_leads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `remarks`
--
ALTER TABLE `remarks`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reply_categories`
--
ALTER TABLE `reply_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resource_categories`
--
ALTER TABLE `resource_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resource_images`
--
ALTER TABLE `resource_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review_schedules`
--
ALTER TABLE `review_schedules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `rude_words`
--
ALTER TABLE `rude_words`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1373;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_item`
--
ALTER TABLE `sales_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `satutory_tasks`
--
ALTER TABLE `satutory_tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scheduled_messages`
--
ALTER TABLE `scheduled_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule_groups`
--
ALTER TABLE `schedule_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scraped_products`
--
ALTER TABLE `scraped_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scrap_activities`
--
ALTER TABLE `scrap_activities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scrap_counts`
--
ALTER TABLE `scrap_counts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scrap_entries`
--
ALTER TABLE `scrap_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scrap_remarks`
--
ALTER TABLE `scrap_remarks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scrap_statistics`
--
ALTER TABLE `scrap_statistics`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `search_terms`
--
ALTER TABLE `search_terms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seo_analytics`
--
ALTER TABLE `seo_analytics`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sitejabber_q_a_s`
--
ALTER TABLE `sitejabber_q_a_s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sku_color_references`
--
ALTER TABLE `sku_color_references`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sku_formats`
--
ALTER TABLE `sku_formats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_tags`
--
ALTER TABLE `social_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sops`
--
ALTER TABLE `sops`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `status_changes`
--
ALTER TABLE `status_changes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_products`
--
ALTER TABLE `stock_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suggestions`
--
ALTER TABLE `suggestions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suggestion_products`
--
ALTER TABLE `suggestion_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_brand_counts`
--
ALTER TABLE `supplier_brand_counts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_brand_count_histories`
--
ALTER TABLE `supplier_brand_count_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_category`
--
ALTER TABLE `supplier_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_category_counts`
--
ALTER TABLE `supplier_category_counts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_inventory`
--
ALTER TABLE `supplier_inventory`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_status`
--
ALTER TABLE `supplier_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `s_e_rankings`
--
ALTER TABLE `s_e_rankings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `targeted_accounts`
--
ALTER TABLE `targeted_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `target_locations`
--
ALTER TABLE `target_locations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks_history`
--
ALTER TABLE `tasks_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_attachments`
--
ALTER TABLE `task_attachments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_categories`
--
ALTER TABLE `task_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `task_types`
--
ALTER TABLE `task_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `task_users`
--
ALTER TABLE `task_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_agents`
--
ALTER TABLE `tracker_agents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_connections`
--
ALTER TABLE `tracker_connections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_cookies`
--
ALTER TABLE `tracker_cookies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_devices`
--
ALTER TABLE `tracker_devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_domains`
--
ALTER TABLE `tracker_domains`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_errors`
--
ALTER TABLE `tracker_errors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_events`
--
ALTER TABLE `tracker_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_events_log`
--
ALTER TABLE `tracker_events_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_geoip`
--
ALTER TABLE `tracker_geoip`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_log`
--
ALTER TABLE `tracker_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_paths`
--
ALTER TABLE `tracker_paths`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_queries`
--
ALTER TABLE `tracker_queries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_query_arguments`
--
ALTER TABLE `tracker_query_arguments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_referers`
--
ALTER TABLE `tracker_referers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_referers_search_terms`
--
ALTER TABLE `tracker_referers_search_terms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_routes`
--
ALTER TABLE `tracker_routes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_route_paths`
--
ALTER TABLE `tracker_route_paths`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_route_path_parameters`
--
ALTER TABLE `tracker_route_path_parameters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_sessions`
--
ALTER TABLE `tracker_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_sql_queries`
--
ALTER TABLE `tracker_sql_queries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_sql_queries_log`
--
ALTER TABLE `tracker_sql_queries_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_sql_query_bindings`
--
ALTER TABLE `tracker_sql_query_bindings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_sql_query_bindings_parameters`
--
ALTER TABLE `tracker_sql_query_bindings_parameters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracker_system_classes`
--
ALTER TABLE `tracker_system_classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_auto_comment_histories`
--
ALTER TABLE `users_auto_comment_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_actions`
--
ALTER TABLE `user_actions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_customers`
--
ALTER TABLE `user_customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_logins`
--
ALTER TABLE `user_logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_manual_crop`
--
ALTER TABLE `user_manual_crop`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_products`
--
ALTER TABLE `user_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_product_feedbacks`
--
ALTER TABLE `user_product_feedbacks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_categories`
--
ALTER TABLE `vendor_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_products`
--
ALTER TABLE `vendor_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `views`
--
ALTER TABLE `views`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voucher_categories`
--
ALTER TABLE `voucher_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `waybills`
--
ALTER TABLE `waybills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `whats_app_configs`
--
ALTER TABLE `whats_app_configs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `whats_app_groups`
--
ALTER TABLE `whats_app_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `whats_app_group_numbers`
--
ALTER TABLE `whats_app_group_numbers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zoom_meetings`
--
ALTER TABLE `zoom_meetings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `orders` AUTO_INCREMENT = 2001;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
