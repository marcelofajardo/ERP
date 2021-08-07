-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost:80
-- Generation Time: Jul 07, 2020 at 12:35 PM
-- Server version: 5.7.30-0ubuntu0.16.04.1
-- PHP Version: 7.2.29-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sololuxury`
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
  `token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `instance_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `send_end` int(11) NOT NULL,
  `send_start` int(11) NOT NULL,
  `is_connected` int(11) NOT NULL DEFAULT '0',
  `last_online` datetime DEFAULT NULL,
  `frequency` int(11) DEFAULT NULL,
  `is_customer_support` int(11) NOT NULL DEFAULT '0',
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date NOT NULL,
  `platform` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `followers_count` int(11) DEFAULT NULL,
  `posts_count` int(11) DEFAULT NULL,
  `dp_count` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `is_processed` tinyint(1) NOT NULL DEFAULT '0',
  `broadcast` int(11) NOT NULL DEFAULT '0',
  `broadcasted_messages` int(11) NOT NULL DEFAULT '0',
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `manual_comment` int(11) NOT NULL DEFAULT '0',
  `bulk_comment` int(11) NOT NULL DEFAULT '0',
  `blocked` int(11) NOT NULL DEFAULT '0',
  `is_seeding` int(11) NOT NULL DEFAULT '0',
  `seeding_stage` int(11) NOT NULL DEFAULT '0',
  `comment_pending` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `properties` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_schedules_attachments`
--

CREATE TABLE `ads_schedules_attachments` (
  `ads_schedule_id` int(10) UNSIGNED NOT NULL,
  `attachment_id` int(10) UNSIGNED NOT NULL,
  `attachment_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `affiliates`
--

CREATE TABLE `affiliates` (
  `id` int(10) UNSIGNED NOT NULL,
  `hashtag_id` int(11) DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caption` longtext COLLATE utf8mb4_unicode_ci,
  `posted_at` datetime DEFAULT NULL,
  `source` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `facebook` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtube` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pinterest` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emailaddress` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_flagged` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `article_categories`
--

CREATE TABLE `article_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assets_category`
--

CREATE TABLE `assets_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `cat_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assets_manager`
--

CREATE TABLE `assets_manager` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `asset_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `purchase_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_cycle` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double(8,2) NOT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usage` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `archived` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assigned_user_pages`
--

CREATE TABLE `assigned_user_pages` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `menu_page_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `automated_messages`
--

CREATE TABLE `automated_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `status` int(11) NOT NULL DEFAULT '0',
  `caption` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `is_verified` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `is_active` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auto_reply_hashtags`
--

CREATE TABLE `auto_reply_hashtags` (
  `id` int(10) UNSIGNED NOT NULL,
  `text` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barcode_media`
--

CREATE TABLE `barcode_media` (
  `id` int(11) NOT NULL,
  `media_id` int(11) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'product',
  `type_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,0) NOT NULL DEFAULT '0',
  `extra` text,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `benchmarks`
--

CREATE TABLE `benchmarks` (
  `id` int(10) UNSIGNED NOT NULL,
  `selections` int(11) NOT NULL DEFAULT '0',
  `searches` int(11) NOT NULL DEFAULT '0',
  `attributes` int(11) NOT NULL DEFAULT '0',
  `supervisor` int(11) NOT NULL DEFAULT '0',
  `imagecropper` int(11) NOT NULL DEFAULT '0',
  `lister` int(11) NOT NULL DEFAULT '0',
  `approver` int(11) NOT NULL DEFAULT '0',
  `inventory` int(11) NOT NULL DEFAULT '0',
  `for_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `block_web_message_lists`
--

CREATE TABLE `block_web_message_lists` (
  `id` int(10) UNSIGNED NOT NULL,
  `object_id` int(11) DEFAULT NULL,
  `object_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `other` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `other` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogger_payments`
--

CREATE TABLE `blogger_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `blogger_id` int(10) UNSIGNED NOT NULL,
  `currency` int(11) NOT NULL DEFAULT '0',
  `payment_date` date DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `payable_amount` decimal(13,4) DEFAULT NULL,
  `paid_amount` decimal(13,4) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `other` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `images` text COLLATE utf8mb4_unicode_ci,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `other` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogger_product_images`
--

CREATE TABLE `blogger_product_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blogger_product_id` int(10) UNSIGNED NOT NULL,
  `other` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `restricted` tinyint(1) NOT NULL DEFAULT '0',
  `image_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `restricted` tinyint(1) NOT NULL DEFAULT '0',
  `image_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookshelves_books`
--

CREATE TABLE `bookshelves_books` (
  `bookshelf_id` int(10) UNSIGNED NOT NULL,
  `book_id` int(10) UNSIGNED NOT NULL,
  `order` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book_comments`
--

CREATE TABLE `book_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `entity_id` int(10) UNSIGNED NOT NULL,
  `entity_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `html` text COLLATE utf8mb4_unicode_ci,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `local_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `uploaded_to` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `euro_to_inr` double NOT NULL,
  `deduction_percentage` int(11) NOT NULL,
  `flash_sales_percentage` int(11) NOT NULL DEFAULT '0',
  `apply_b2b_discount_above` int(11) NOT NULL DEFAULT '0',
  `b2b_sales_discount` int(11) NOT NULL DEFAULT '0',
  `sales_discount` int(11) NOT NULL DEFAULT '0',
  `magento_id` int(11) UNSIGNED DEFAULT '0',
  `brand_segment` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku_strip_last` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku_add` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `references` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku_search_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_server_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
  `used` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
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
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` int(10) UNSIGNED NOT NULL,
  `budget_category_id` int(10) UNSIGNED NOT NULL,
  `budget_subcategory_id` int(10) UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `amount` int(11) NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budget_categories`
--

CREATE TABLE `budget_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulk_customer_replies_keywords`
--

CREATE TABLE `bulk_customer_replies_keywords` (
  `id` int(10) UNSIGNED NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `text_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_manual` tinyint(1) NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_processed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulk_customer_replies_keyword_customer`
--

CREATE TABLE `bulk_customer_replies_keyword_customer` (
  `keyword_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `call_busy_messages`
--

CREATE TABLE `call_busy_messages` (
  `id` int(11) NOT NULL,
  `lead_id` int(11) DEFAULT '0',
  `twilio_call_sid` varchar(255) DEFAULT NULL,
  `caller_sid` varchar(255) DEFAULT NULL,
  `message` text,
  `recording_url` varchar(200) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

CREATE TABLE `cases` (
  `id` int(10) UNSIGNED NOT NULL,
  `lawyer_id` int(10) UNSIGNED DEFAULT NULL,
  `case_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `for_against` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `court_detail` text COLLATE utf8mb4_unicode_ci,
  `whatsapp_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resource` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `last_date` date DEFAULT NULL,
  `next_date` date DEFAULT NULL,
  `cost_per_hearing` double(8,2) DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `other` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `other` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `case_receivables`
--

CREATE TABLE `case_receivables` (
  `id` int(10) UNSIGNED NOT NULL,
  `case_id` int(10) UNSIGNED NOT NULL,
  `currency` int(11) NOT NULL DEFAULT '0',
  `receivable_date` date DEFAULT NULL,
  `received_date` date DEFAULT NULL,
  `receivable_amount` decimal(13,4) DEFAULT NULL,
  `received_amount` decimal(13,4) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `other` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cash_flows`
--

CREATE TABLE `cash_flows` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `cash_flow_category_id` int(10) UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `date` date NOT NULL,
  `amount` int(11) NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expected` decimal(13,4) DEFAULT NULL,
  `actual` decimal(13,4) DEFAULT NULL,
  `cash_flow_able_id` int(11) DEFAULT NULL,
  `cash_flow_able_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `order_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `currency` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `simplyduty_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_after_autocrop` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `references` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_update_users`
--

CREATE TABLE `category_update_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
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
  `restricted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_categories`
--

CREATE TABLE `chatbot_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_dialogs`
--

CREATE TABLE `chatbot_dialogs` (
  `id` int(11) NOT NULL,
  `response_type` varchar(255) NOT NULL DEFAULT 'standard',
  `name` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `match_condition` varchar(255) NOT NULL,
  `metadata` varchar(255) DEFAULT NULL,
  `workspace_id` varchar(255) DEFAULT NULL,
  `previous_sibling` int(11) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dialog_type` enum('node','folder') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_dialog_responses`
--

CREATE TABLE `chatbot_dialog_responses` (
  `id` int(11) NOT NULL,
  `response_type` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `message_to_human_agent` int(11) NOT NULL DEFAULT '0',
  `chatbot_dialog_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_intents_annotations`
--

CREATE TABLE `chatbot_intents_annotations` (
  `id` int(11) NOT NULL,
  `question_example_id` int(11) NOT NULL,
  `chatbot_keyword_id` int(11) NOT NULL,
  `chatbot_value_id` int(11) DEFAULT NULL,
  `start_char_range` int(11) NOT NULL,
  `end_char_range` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_keywords`
--

CREATE TABLE `chatbot_keywords` (
  `id` int(11) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `workspace_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_keyword_values`
--

CREATE TABLE `chatbot_keyword_values` (
  `id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `chatbot_keyword_id` int(11) NOT NULL,
  `types` enum('synonyms','patterns') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_keyword_value_types`
--

CREATE TABLE `chatbot_keyword_value_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chatbot_keyword_value_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_questions`
--

CREATE TABLE `chatbot_questions` (
  `id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `workspace_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_question_examples`
--

CREATE TABLE `chatbot_question_examples` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `chatbot_question_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_replies`
--

CREATE TABLE `chatbot_replies` (
  `id` int(11) NOT NULL,
  `question` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reply` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `chat_id` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_settings`
--

CREATE TABLE `chatbot_settings` (
  `id` int(11) NOT NULL,
  `chat_name` varchar(255) DEFAULT NULL,
  `vendor` varchar(255) NOT NULL,
  `instance_id` varchar(255) NOT NULL,
  `workspace_id` varchar(255) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int(10) UNSIGNED NOT NULL,
  `sourceid` int(10) NOT NULL,
  `userid` int(11) NOT NULL,
  `messages` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_bot_keyword_groups`
--

CREATE TABLE `chat_bot_keyword_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `group_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_bot_phrase_groups`
--

CREATE TABLE `chat_bot_phrase_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `phrase_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `group_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `is_queue` int(11) NOT NULL,
  `unique_id` varchar(191) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `message` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lead_id` int(10) UNSIGNED DEFAULT NULL,
  `order_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `purchase_id` int(11) DEFAULT NULL,
  `supplier_id` int(10) UNSIGNED DEFAULT NULL,
  `vendor_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT '0',
  `sent_to_user_id` int(11) DEFAULT NULL,
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
  `site_development_id` int(11) DEFAULT NULL,
  `assigned_to` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `approved` tinyint(1) DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `sent` tinyint(1) NOT NULL DEFAULT '0',
  `is_delivered` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `is_read` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `error_status` int(11) NOT NULL DEFAULT '0',
  `resent` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_reminder` tinyint(1) NOT NULL DEFAULT '0',
  `media_url` varchar(2048) DEFAULT NULL,
  `is_processed_for_keyword` tinyint(1) NOT NULL DEFAULT '0',
  `document_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `old_id` varchar(191) DEFAULT NULL,
  `is_chatbot` int(11) DEFAULT '0',
  `message_application_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `chat_message_phrases`
--

CREATE TABLE `chat_message_phrases` (
  `id` int(11) NOT NULL,
  `phrase` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` int(11) NOT NULL DEFAULT '0',
  `word_id` int(11) DEFAULT NULL,
  `chat_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_message_words`
--

CREATE TABLE `chat_message_words` (
  `id` int(11) NOT NULL,
  `word` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `image` text COLLATE utf8mb4_unicode_ci,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `because_of` text COLLATE utf8mb4_unicode_ci,
  `status` int(11) NOT NULL DEFAULT '0',
  `messages_sent` int(11) NOT NULL DEFAULT '0',
  `account_id` int(11) DEFAULT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_gender_processed` tinyint(1) NOT NULL DEFAULT '0',
  `is_country_processed` tinyint(1) NOT NULL DEFAULT '0',
  `followed_by` int(11) DEFAULT NULL,
  `is_imported` tinyint(1) NOT NULL DEFAULT '0',
  `address` text COLLATE utf8mb4_unicode_ci,
  `customer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `image` text COLLATE utf8mb4_unicode_ci,
  `messages_sent` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `frequency_completed` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `is_send` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `narrative` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'common'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `is_stopped` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `competitor_followers`
--

CREATE TABLE `competitor_followers` (
  `id` int(10) UNSIGNED NOT NULL,
  `competitor_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `cursor` text COLLATE utf8mb4_unicode_ci,
  `is_processed` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `plan_of_action` text COLLATE utf8mb4_unicode_ci,
  `where` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thread_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `media_id` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `receipt_username` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_customer_flagged` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `other` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `country_duties`
--

CREATE TABLE `country_duties` (
  `id` int(10) UNSIGNED NOT NULL,
  `hs_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `origin` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `duty` decimal(8,2) NOT NULL,
  `vat` decimal(8,2) NOT NULL,
  `duty_percentage` decimal(8,2) NOT NULL,
  `vat_percentage` decimal(8,2) NOT NULL,
  `duty_group_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `country_groups`
--

CREATE TABLE `country_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `country_group_items`
--

CREATE TABLE `country_group_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `country_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_group_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(10) UNSIGNED NOT NULL,
  `magento_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `start` datetime NOT NULL,
  `expiration` datetime DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_fixed` double(8,2) NOT NULL DEFAULT '0.00',
  `discount_percentage` double(8,2) NOT NULL DEFAULT '0.00',
  `minimum_order_amount` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `maximum_usage` smallint(5) UNSIGNED DEFAULT NULL,
  `usage_count` smallint(5) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courier`
--

CREATE TABLE `courier` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cron_jobs`
--

CREATE TABLE `cron_jobs` (
  `id` int(10) UNSIGNED NOT NULL,
  `signature` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `error_count` int(11) NOT NULL DEFAULT '0',
  `last_error` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cropped_image_references`
--

CREATE TABLE `cropped_image_references` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `original_media_id` int(11) NOT NULL,
  `original_media_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_media_id` int(11) NOT NULL,
  `new_media_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `speed` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crop_amends`
--

CREATE TABLE `crop_amends` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `file_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `settings` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` double(8,2) DEFAULT NULL,
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
  `broadcast_number` varchar(191) DEFAULT NULL,
  `instahandler` varchar(255) DEFAULT NULL,
  `ig_username` varchar(255) DEFAULT NULL,
  `shoe_size` varchar(191) DEFAULT NULL,
  `clothing_size` varchar(191) DEFAULT NULL,
  `gender` varchar(191) DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT '1',
  `do_not_disturb` tinyint(1) NOT NULL DEFAULT '0',
  `is_blocked` tinyint(1) NOT NULL DEFAULT '0',
  `is_flagged` tinyint(1) NOT NULL DEFAULT '0',
  `is_error_flagged` tinyint(1) NOT NULL DEFAULT '0',
  `is_priority` tinyint(1) NOT NULL DEFAULT '0',
  `credit` varchar(191) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `notes` longtext,
  `instruction_completed_at` datetime DEFAULT NULL,
  `facebook_id` varchar(191) DEFAULT NULL,
  `frequency` int(11) DEFAULT NULL,
  `reminder_last_reply` int(11) NOT NULL DEFAULT '1',
  `reminder_from` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language` varchar(191) NOT NULL,
  `reminder_message` text,
  `is_categorized_for_bulk_messages` tinyint(1) NOT NULL DEFAULT '0',
  `customer_next_action_id` int(11) NOT NULL,
  `chat_session_id` varchar(255) DEFAULT NULL,
  `in_w_list` int(11) DEFAULT '0',
  `store_website_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `updated_by` int(11) NOT NULL,
  `currency` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_kyc_documents`
--

CREATE TABLE `customer_kyc_documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_live_chats`
--

CREATE TABLE `customer_live_chats` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL,
  `thread` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `seen` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_marketing_platforms`
--

CREATE TABLE `customer_marketing_platforms` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL,
  `marketing_platform_id` int(11) NOT NULL,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_next_actions`
--

CREATE TABLE `customer_next_actions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_activities`
--

CREATE TABLE `daily_activities` (
  `id` int(10) UNSIGNED NOT NULL,
  `time_slot` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity` longtext COLLATE utf8mb4_unicode_ci,
  `user_id` int(11) NOT NULL,
  `is_admin` int(11) DEFAULT NULL,
  `assist_msg` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `for_date` date NOT NULL,
  `pending_for` int(11) NOT NULL DEFAULT '0',
  `actual_start_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '0000-00-00 00:00:00',
  `is_completed` datetime DEFAULT NULL,
  `general_category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `database_historical_records`
--

CREATE TABLE `database_historical_records` (
  `id` int(10) UNSIGNED NOT NULL,
  `database_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` double(8,2) NOT NULL,
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
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  `status` varchar(191) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `image` mediumtext,
  `email` text,
  `social_handle` text,
  `instagram_handle` text,
  `site_link` text,
  `phone` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
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
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `developer_languages`
--

CREATE TABLE `developer_languages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `developer_messages_alert_schedules`
--

CREATE TABLE `developer_messages_alert_schedules` (
  `id` int(10) UNSIGNED NOT NULL,
  `time` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `module` int(11) NOT NULL DEFAULT '0',
  `completed` tinyint(4) NOT NULL DEFAULT '0',
  `estimate_time` timestamp NULL DEFAULT NULL,
  `estimate_minutes` int(11) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `task_type_id` int(11) NOT NULL,
  `is_resolved` tinyint(4) NOT NULL DEFAULT '0',
  `reference` text,
  `object` varchar(191) DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `responsible_user_id` int(11) NOT NULL,
  `master_user_id` int(11) NOT NULL DEFAULT '0',
  `master_user_priority` int(11) NOT NULL DEFAULT '0',
  `language` varchar(191) DEFAULT NULL,
  `hubstaff_task_id` int(10) UNSIGNED NOT NULL,
  `github_branch_name` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `developer_task_documents`
--

CREATE TABLE `developer_task_documents` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `developer_task_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `digital_marketing_platforms`
--

CREATE TABLE `digital_marketing_platforms` (
  `id` int(10) UNSIGNED NOT NULL,
  `platform` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_platform` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `digital_marketing_platform_components`
--

CREATE TABLE `digital_marketing_platform_components` (
  `id` int(10) UNSIGNED NOT NULL,
  `digital_marketing_platform_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `digital_marketing_platform_remarks`
--

CREATE TABLE `digital_marketing_platform_remarks` (
  `id` int(10) UNSIGNED NOT NULL,
  `digital_marketing_platform_id` int(11) NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `digital_marketing_solutions`
--

CREATE TABLE `digital_marketing_solutions` (
  `id` int(10) UNSIGNED NOT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` text COLLATE utf8mb4_unicode_ci,
  `digital_marketing_platform_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `digital_marketing_solution_attributes`
--

CREATE TABLE `digital_marketing_solution_attributes` (
  `id` int(10) UNSIGNED NOT NULL,
  `digital_marketing_solution_id` int(11) NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `digital_marketing_solution_researches`
--

CREATE TABLE `digital_marketing_solution_researches` (
  `id` int(10) UNSIGNED NOT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `priority` int(11) NOT NULL,
  `digital_marketing_solution_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `digital_marketing_usps`
--

CREATE TABLE `digital_marketing_usps` (
  `id` int(10) UNSIGNED NOT NULL,
  `digital_marketing_platform_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
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
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `from_email` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_categories`
--

CREATE TABLE `document_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `frequency` int(11) DEFAULT NULL,
  `reminder_message` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `duty_groups`
--

CREATE TABLE `duty_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hs_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duty` decimal(8,2) NOT NULL,
  `vat` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `duty_group_countries`
--

CREATE TABLE `duty_group_countries` (
  `id` int(10) UNSIGNED NOT NULL,
  `duty_group_id` bigint(20) UNSIGNED NOT NULL,
  `country_duty_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` int(10) UNSIGNED NOT NULL,
  `model_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'outgoing',
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  `from` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `template` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `additional_data` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cc` longtext COLLATE utf8mb4_unicode_ci,
  `bcc` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_addresses`
--

CREATE TABLE `email_addresses` (
  `id` int(10) UNSIGNED NOT NULL,
  `from_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `driver` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `host` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `port` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `encryption` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `template` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_accounts`
--

CREATE TABLE `erp_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `table` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `row_id` int(11) DEFAULT NULL,
  `transacted_by` int(11) NOT NULL,
  `debit` decimal(8,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(8,2) NOT NULL DEFAULT '0.00',
  `user_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `metadata` longtext COLLATE utf8mb4_unicode_ci,
  `remark` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_events`
--

CREATE TABLE `erp_events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_description` text COLLATE utf8mb4_unicode_ci,
  `start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` int(11) NOT NULL DEFAULT '0',
  `brand_id` text COLLATE utf8mb4_unicode_ci,
  `category_id` text COLLATE utf8mb4_unicode_ci,
  `number_of_person` int(11) DEFAULT '100',
  `product_start_date` datetime DEFAULT '0000-00-00 00:00:00',
  `product_end_date` datetime DEFAULT '0000-00-00 00:00:00',
  `minute` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `hour` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `day_of_month` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `month` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `day_of_week` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `next_run_date` datetime DEFAULT '0000-00-00 00:00:00',
  `is_closed` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `min_price` decimal(8,2) DEFAULT '0.00',
  `max_price` decimal(8,2) DEFAULT '0.00',
  `brand_segment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_lead_status`
--

CREATE TABLE `erp_lead_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_priorities`
--

CREATE TABLE `erp_priorities` (
  `id` int(10) UNSIGNED NOT NULL,
  `model_id` int(11) NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `is_sent_by_me` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flagged_instagram_posts`
--

CREATE TABLE `flagged_instagram_posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `media_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_categories`
--

CREATE TABLE `general_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `github_branch_states`
--

CREATE TABLE `github_branch_states` (
  `repository_id` int(11) NOT NULL,
  `branch_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ahead_by` int(11) NOT NULL,
  `behind_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_commit_author_username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_commit_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `github_groups`
--

CREATE TABLE `github_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `github_group_members`
--

CREATE TABLE `github_group_members` (
  `id` int(10) UNSIGNED NOT NULL,
  `github_groups_id` int(11) NOT NULL,
  `github_users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `github_repositories`
--

CREATE TABLE `github_repositories` (
  `id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `html` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `webhook` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `github_repository_groups`
--

CREATE TABLE `github_repository_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `github_repositories_id` int(11) NOT NULL,
  `github_groups_id` int(11) NOT NULL,
  `rights` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `github_repository_users`
--

CREATE TABLE `github_repository_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `github_repositories_id` int(11) NOT NULL,
  `github_users_id` int(11) NOT NULL,
  `rights` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `github_users`
--

CREATE TABLE `github_users` (
  `id` int(11) NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `googlescrapping`
--

CREATE TABLE `googlescrapping` (
  `id` int(11) NOT NULL,
  `keyword` text,
  `name` text NOT NULL,
  `link` mediumtext NOT NULL,
  `description` longtext NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `source` varchar(191) NOT NULL,
  `is_updated` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `google_analytics`
--

CREATE TABLE `google_analytics` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `google_server`
--

CREATE TABLE `google_server` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
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
  `image_url` text COLLATE utf8mb4_unicode_ci,
  `post_url` text COLLATE utf8mb4_unicode_ci,
  `created_date` datetime DEFAULT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  `number_comments` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hashtag_post_likes`
--

CREATE TABLE `hashtag_post_likes` (
  `id` int(11) NOT NULL,
  `username` varchar(191) NOT NULL,
  `profile_url` varchar(400) NOT NULL,
  `hashtag_post_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hash_tags`
--

CREATE TABLE `hash_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `platforms_id` int(11) NOT NULL,
  `hashtag` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT '5',
  `post_count` int(11) NOT NULL DEFAULT '0',
  `is_processed` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `historial_datas`
--

CREATE TABLE `historial_datas` (
  `id` int(10) UNSIGNED NOT NULL,
  `object` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `measuring_point` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hs_codes`
--

CREATE TABLE `hs_codes` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hs_code_groups`
--

CREATE TABLE `hs_code_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `hs_code_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `composition` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hs_code_groups_categories_compositions`
--

CREATE TABLE `hs_code_groups_categories_compositions` (
  `id` int(10) UNSIGNED NOT NULL,
  `hs_code_group_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `composition` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hs_code_settings`
--

CREATE TABLE `hs_code_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination_country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hubstaff_activities`
--

CREATE TABLE `hubstaff_activities` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `starts_at` datetime NOT NULL,
  `tracked` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `keyboard` int(11) NOT NULL,
  `mouse` int(11) NOT NULL,
  `overall` int(11) NOT NULL,
  `hubstaff_payment_account_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hubstaff_activity_notifications`
--

CREATE TABLE `hubstaff_activity_notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `min_percentage` double(8,2) NOT NULL DEFAULT '0.00',
  `actual_percentage` double(8,2) NOT NULL DEFAULT '0.00',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hubstaff_members`
--

CREATE TABLE `hubstaff_members` (
  `id` int(10) UNSIGNED NOT NULL,
  `hubstaff_user_id` int(11) NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `pay_rate` double(8,2) NOT NULL,
  `bill_rate` double(8,2) NOT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_activity_percentage` double(8,2) NOT NULL DEFAULT '0.00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hubstaff_payment_accounts`
--

CREATE TABLE `hubstaff_payment_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `accounted_at` datetime NOT NULL,
  `billing_start` datetime NOT NULL,
  `billing_end` datetime NOT NULL,
  `hrs` double(8,2) NOT NULL DEFAULT '0.00',
  `rate` double(8,2) NOT NULL DEFAULT '0.00',
  `currency` char(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `payment_currency` char(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INR',
  `ex_rate` double(8,2) NOT NULL DEFAULT '0.00',
  `status` int(11) NOT NULL DEFAULT '1',
  `payment_info` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_remark` text COLLATE utf8mb4_unicode_ci,
  `scheduled_on` datetime NOT NULL,
  `total_payout` double(8,2) NOT NULL DEFAULT '0.00',
  `total_paid` double(8,2) NOT NULL DEFAULT '0.00',
  `amount` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hubstaff_projects`
--

CREATE TABLE `hubstaff_projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `hubstaff_project_id` int(11) NOT NULL,
  `organisation_id` int(11) NOT NULL,
  `hubstaff_project_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hubstaff_project_description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hubstaff_project_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hubstaff_tasks`
--

CREATE TABLE `hubstaff_tasks` (
  `id` int(10) UNSIGNED NOT NULL,
  `hubstaff_task_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `hubstaff_project_id` int(11) NOT NULL,
  `summary` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `status` int(11) NOT NULL DEFAULT '1',
  `lifestyle` int(11) NOT NULL DEFAULT '0',
  `approved_user` int(11) UNSIGNED DEFAULT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `is_scheduled` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `posted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `image_schedules`
--

CREATE TABLE `image_schedules` (
  `id` int(11) NOT NULL,
  `image_id` int(10) UNSIGNED NOT NULL,
  `description` text,
  `scheduled_for` datetime DEFAULT NULL,
  `facebook` tinyint(4) NOT NULL,
  `instagram` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `facebook_post_id` varchar(255) DEFAULT NULL,
  `instagram_post_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `posted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `image_tags`
--

CREATE TABLE `image_tags` (
  `id` int(11) NOT NULL,
  `image_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `im_queues`
--

CREATE TABLE `im_queues` (
  `id` int(10) UNSIGNED NOT NULL,
  `im_client` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_to` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_from` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `image` text COLLATE utf8mb4_unicode_ci,
  `priority` int(11) DEFAULT '10',
  `marketing_message_type_id` int(11) DEFAULT NULL,
  `broadcast_id` int(11) DEFAULT NULL,
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
  `list_first_post` text COLLATE utf8mb4_unicode_ci,
  `list_second_post` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_automated_messages`
--

CREATE TABLE `instagram_automated_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `sender_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `receiver_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hashtag_posts',
  `message` text COLLATE utf8mb4_unicode_ci,
  `attachments` text COLLATE utf8mb4_unicode_ci,
  `status` int(11) NOT NULL DEFAULT '0',
  `reusable` int(11) NOT NULL DEFAULT '0',
  `use_count` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `options` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_bulk_messages`
--

CREATE TABLE `instagram_bulk_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(11) NOT NULL,
  `receipts` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_comment_queues`
--

CREATE TABLE `instagram_comment_queues` (
  `id` int(10) UNSIGNED NOT NULL,
  `message` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `is_send` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_configs`
--

CREATE TABLE `instagram_configs` (
  `id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_customer_support` int(11) NOT NULL DEFAULT '0',
  `frequency` int(11) DEFAULT NULL,
  `last_online` datetime DEFAULT NULL,
  `is_connected` int(11) NOT NULL DEFAULT '0',
  `send_start` int(11) NOT NULL,
  `send_end` int(11) NOT NULL,
  `instance_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `is_default` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `device_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `simcard_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `simcard_owner` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sim_card_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recharge_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `comments_count` int(11) DEFAULT NULL,
  `likes` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `metadata` text COLLATE utf8mb4_unicode_ci,
  `people_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `priority` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `last_message` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `following` int(11) DEFAULT NULL,
  `followers` int(11) DEFAULT NULL,
  `posts` int(11) DEFAULT NULL,
  `is_processed` int(11) NOT NULL DEFAULT '0',
  `is_manual` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `approved` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `media_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `instructions`
--

CREATE TABLE `instructions` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT '1',
  `instruction` longtext NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT '0',
  `assigned_from` int(10) UNSIGNED NOT NULL,
  `assigned_to` int(10) UNSIGNED NOT NULL,
  `pending` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_priority` tinyint(1) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `verified` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `skipped_count` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `instruction_times`
--

CREATE TABLE `instruction_times` (
  `id` int(10) UNSIGNED NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `instructions_id` int(11) NOT NULL,
  `total_minutes` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(10) UNSIGNED NOT NULL,
  `invoice_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE `issues` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `issue` longtext NOT NULL,
  `priority` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `module` varchar(191) NOT NULL,
  `responsible_user_id` int(11) DEFAULT NULL,
  `resolved_at` date DEFAULT NULL,
  `is_resolved` tinyint(1) NOT NULL DEFAULT '0',
  `submitted_by` int(11) DEFAULT NULL,
  `cost` decimal(8,2) NOT NULL DEFAULT '0.00',
  `subject` text,
  `estimate_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `has_permission` tinyint(1) NOT NULL DEFAULT '0',
  `has_permission_own` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keywords`
--

CREATE TABLE `keywords` (
  `id` int(10) UNSIGNED NOT NULL,
  `text` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `landing_page_products`
--

CREATE TABLE `landing_page_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `active` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laravel_github_logs`
--

CREATE TABLE `laravel_github_logs` (
  `log_time` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `log_file_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `commit_time` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `stacktrace` text COLLATE utf8mb4_unicode_ci,
  `commit` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laravel_logs`
--

CREATE TABLE `laravel_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `filename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `log` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `log_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `other` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lawyer_specialities`
--

CREATE TABLE `lawyer_specialities` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `comments` text COLLATE utf8mb4_unicode_ci,
  `assigned_user` int(10) NOT NULL,
  `source` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `leadsourcetxt` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `selected_product` text COLLATE utf8mb4_unicode_ci,
  `size` text COLLATE utf8mb4_unicode_ci,
  `brand` int(10) DEFAULT NULL,
  `multi_brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `multi_category` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `userid` int(11) NOT NULL,
  `assign_status` int(2) DEFAULT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `whatsapp_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lead_broadcasts_lead`
--

CREATE TABLE `lead_broadcasts_lead` (
  `lead_broadcast_id` int(11) NOT NULL,
  `lead_id` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `list_contacts`
--

CREATE TABLE `list_contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `list_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `livechatinc_settings`
--

CREATE TABLE `livechatinc_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `key` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `live_chat_users`
--

CREATE TABLE `live_chat_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_excel_imports`
--

CREATE TABLE `log_excel_imports` (
  `id` int(10) UNSIGNED NOT NULL,
  `filename` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_of_products` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_products_created` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `supplier_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_products_updated` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_google_cses`
--

CREATE TABLE `log_google_cses` (
  `id` int(10) UNSIGNED NOT NULL,
  `image_url` text COLLATE utf8mb4_unicode_ci,
  `keyword` text COLLATE utf8mb4_unicode_ci,
  `response` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_google_vision_reference`
--

CREATE TABLE `log_google_vision_reference` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_reference` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `composite_reference` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender_reference` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cnt` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `ignore` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_list_magentos`
--

CREATE TABLE `log_list_magentos` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `message` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `magento_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_scraper_old`
--

CREATE TABLE `log_scraper_old` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_sku` text COLLATE utf8mb4_unicode_ci,
  `brand` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `properties` text COLLATE utf8mb4_unicode_ci,
  `images` text COLLATE utf8mb4_unicode_ci,
  `size_system` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discounted_price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_sale` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `validated` tinyint(4) NOT NULL,
  `validation_result` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `raw_data` text COLLATE utf8mb4_unicode_ci,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_tineye`
--

CREATE TABLE `log_tineye` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image_url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `md5` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `response` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mailinglists`
--

CREATE TABLE `mailinglists` (
  `id` int(10) UNSIGNED NOT NULL,
  `website_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_id` int(11) NOT NULL,
  `remote_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mailinglist_emails`
--

CREATE TABLE `mailinglist_emails` (
  `id` int(10) UNSIGNED NOT NULL,
  `mailinglist_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `html` text COLLATE utf8mb4_unicode_ci,
  `scheduled_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `progress` int(11) NOT NULL DEFAULT '0',
  `total_emails_scheduled` int(11) NOT NULL DEFAULT '0',
  `total_emails_sent` int(11) NOT NULL DEFAULT '0',
  `total_emails_undelivered` int(11) NOT NULL DEFAULT '0',
  `api_template_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mailinglist_templates`
--

CREATE TABLE `mailinglist_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail_class` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_tpl` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_count` int(10) UNSIGNED NOT NULL,
  `text_count` int(10) UNSIGNED NOT NULL,
  `example_image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mailing_remarks`
--

CREATE TABLE `mailing_remarks` (
  `id` int(10) UNSIGNED NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mailing_template_files`
--

CREATE TABLE `mailing_template_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `mailing_id` int(11) NOT NULL,
  `path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marketing_message_types`
--

CREATE TABLE `marketing_message_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `userid` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED DEFAULT NULL,
  `assigned_to` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `moduleid` int(10) DEFAULT NULL,
  `moduletype` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `sent` tinyint(1) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0',
  `group_id` int(10) UNSIGNED NOT NULL,
  `sending_time` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messsage_applications`
--

CREATE TABLE `messsage_applications` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(496, '2019_09_12_181325_create_activities_table', 287),
(497, '2019_09_12_181325_create_attachments_table', 287),
(498, '2019_09_12_181325_create_books_table', 287),
(499, '2019_09_12_181325_create_bookshelves_books_table', 287),
(500, '2019_09_12_181325_create_bookshelves_table', 287),
(501, '2019_09_12_181325_create_chapters_table', 287),
(502, '2019_09_12_181325_create_comments_table', 287),
(503, '2019_09_12_181325_create_entity_permissions_table', 287),
(504, '2019_09_12_181325_create_images_table', 287),
(505, '2019_09_12_181325_create_joint_permissions_table', 287),
(506, '2019_09_12_181325_create_page_revisions_table', 287),
(507, '2019_09_12_181325_create_pages_table', 287),
(508, '2019_09_12_181325_create_search_terms_table', 287),
(509, '2019_09_12_181325_create_tags_table', 287),
(510, '2019_09_12_181325_create_views_table', 287),
(511, '2019_09_12_181326_add_foreign_keys_to_bookshelves_books_table', 287),
(512, '2019_09_10_110709_create_zoom_meetings_table', 288),
(513, '2019_09_11_154910_add_meeting_details_in_zoom_meetings_table', 288),
(514, '2019_09_12_095240_create_whats_app_groups_table', 288),
(515, '2019_09_12_100041_add_user_details_in_zoom_meetings_table', 288),
(516, '2019_09_12_100418_create_whats_app_group_numbers_table', 288),
(517, '2019_09_12_115712_update_chat_messages_table', 288),
(518, '2019_09_12_180913_create_task_types_table', 288),
(519, '2019_09_12_181343_alter_developer_tasks_add_task_type_id_column', 288),
(520, '2019_09_14_120126_add_delete_recording_flag_in_zoom_meetings_table', 288),
(521, '2019_09_17_110709_create_page_notes_table', 288),
(522, '2019_09_15_190004_update_permission_table', 289),
(523, '2019_09_15_153937_create_task_history_table', 290),
(524, '2019_09_18_154826_create_user_logs_table', 290),
(526, '2019_09_21_153937_create_history_whatsapp_number_table', 291),
(527, '2019_09_16_111826_create_purchase_product_supplier_table', 292),
(528, '2019_09_23_155645_create_password_histories_table', 292),
(529, '2019_09_23_170350_update_passwords_table', 292),
(530, '2019_09_23_170410_update_passwords_password_histories_table', 292),
(531, '2019_09_24_104429_update_password_histories_change_tables', 292),
(532, '2019_09_24_125132_alter_log_scraper_add_column_category', 292),
(533, '2019_09_25_103856_create_purchase_order_customer', 293),
(534, '2019_09_25_103856_create_purchase_status_table', 294),
(535, '2019_09_25_103856_alter_purchase_table_add_column_purchase_status_id', 295),
(536, '2019_09_25_103858_alter_purchase_table_add_column_purchase_status_id', 296),
(537, '2019_09_25_103859_alter_purchase_product_add_column_order_product_id', 296),
(538, '2019_09_25_123846_create_sku_formats_table', 297),
(539, '2019_09_26_172822_alter_log_scraper_add_column_raw', 297),
(540, '2019_09_29_123847_create_erp_lead_status_table', 297),
(541, '2019_09_29_123848_create_erp_leads_table', 298),
(542, '2019_09_30_155315_create_log_excel_imports_table', 299),
(543, '2019_09_30_183008_create_supplier_category_counts_table', 299),
(544, '2019_10_01_142624_create_supplier_brand_counts_table', 299),
(545, '2019_10_01_152631_update_supplier_brand_count_table', 299),
(546, '2019_10_03_111826_create_page_notes_categories_table', 299),
(547, '2019_10_03_111827_alter_page_notes_add_category_id_table', 300),
(548, '2019_09_04_183633_create_document_remarks_table', 301),
(549, '2019_09_04_195101_create_document_histories_table', 301),
(550, '2019_09_29_172620_create_developer_task_comments_table', 301),
(551, '2019_09_29_172858_add_createdby_to_developer_tasks_table', 301),
(552, '2019_09_29_201428_add_task_type_id_to_developers_table', 302),
(553, '2019_10_03_154557_create_excel_importers_table', 302),
(554, '2019_10_03_154609_create_excel_importer_details_table', 302),
(555, '2019_10_04_105949_alter_log_scraper_add_original_sku', 302),
(556, '2019_10_05_024053_update_excel_importer_table', 302),
(557, '2019_10_05_115329_update_vendors_table', 302),
(558, '2019_10_06_151303_update_document_tables', 302),
(559, '2019_10_08_013641_update_excel_importer_tables', 302),
(560, '2019_10_10_143914_create_product_quickshell_groups_table', 302),
(563, '2019_10_11_000000_create_product_location_history_table', 303),
(564, '2019_10_11_000001_alter_table_instructions', 304),
(565, '2019_10_10_220806_alter_chat_messages_add_columns_is_delivered_is_read', 305),
(566, '2019_10_12_000000_create_courier_table', 305),
(567, '2019_10_12_000000_create_product_location_table', 305),
(568, '2019_10_06_030629_create_task_attachments_table', 306),
(569, '2019_10_13_000000_create_product_disptach_table', 306),
(570, '2019_10_10_110957_update_vendor_table', 307),
(571, '2019_10_10_120342_update_supplier_table', 307),
(572, '2019_10_13_113036_create_product_quicksell_groups', 307),
(573, '2019_10_13_130441_update_products_table', 307),
(574, '2019_10_14_020213_update_supplier_brand_counts_table', 307),
(575, '2019_10_14_094002_create_supplier_brand_count_histories_table', 307),
(576, '2019_10_14_122114_create_quick_sell_groups_table', 307),
(577, '2019_10_14_122534_update_product_table', 307),
(578, '2019_10_16_130741_create_document_send_histories_table', 307),
(579, '2019_10_19_140553_create_product_templates_table', 308),
(580, '2019_10_17_121121_create_old_categories_table', 309),
(581, '2019_10_17_135959_update_old_table', 309),
(582, '2019_10_17_160036_update_chat_messages_table', 309),
(583, '2019_10_18_061215_alter_erp_leads_add_column_brand_segment_and_gender', 309),
(584, '2019_10_19_023403_create_old_payments_table', 309),
(585, '2019_10_19_041754_create_old_remarks_table', 309),
(586, '2019_10_20_125122_update_quick_sell_groups_table', 309),
(587, '2019_10_20_151020_update_quick_sell_groups_tables', 309),
(588, '2019_10_23_162531_update_resource_images_table', 310),
(589, '2019_10_23_221950_alter_instructions_table_add_product_id', 310),
(590, '2019_11_01_140553_create_templates_table', 311),
(591, '2019_10_17_160036_update_chat_messages_table_add_column_old_id', 312),
(592, '2019_10_30_085918_create_messsage_applications_table', 312),
(593, '2019_10_30_151141_update_chat_message_tables_add_column_message_application_id', 312),
(594, '2019_11_03_093226_update_hash_tags_tables', 312),
(595, '2019_11_03_095341_update_instagram_posts_comments_table', 312),
(596, '2019_11_03_102356_create_priorities_table', 312),
(597, '2019_11_03_164221_update_old_table_add_column_account_name', 312),
(598, '2019_11_03_195612_create_sku_color_references', 312),
(599, '2019_11_04_110938_update_instagram_posts_table', 312),
(600, '2019_11_04_220928_alter_templates_table_add_no_of_images', 312),
(601, '2019_11_06_063057_alter_product_templates_table_drop_product_id_foreign', 312),
(602, '2019_11_06_063251_alter_product_templates_table_change_product_id', 312),
(603, '2019_11_07_113051_create_im_queues_table', 312),
(604, '2019_11_08_225617_create_whats_app_configs_table', 312),
(605, '2019_11_08_225729_create_marketing_platforms_table', 312),
(606, '2019_11_08_225814_create_customer_marketing_platforms_table', 312),
(607, '2019_11_10_100037_alter_sku_format_add_sku_format_without_color', 312),
(608, '2019_11_10_112446_update_sku_formats_add_sku_example_column_table', 312),
(610, '2019_11_16_160036_update_product_dispatch_table_add_column_delivery_person', 313),
(611, '2019_11_11_151433_update_settings_add_column_welcome_message_table', 314),
(612, '2019_11_18_102326_rename_table_whats_app_configs_to_whatsapp_configs', 314),
(613, '2019_11_21_115403_alter_table_customer_add_broadcast_number', 314),
(614, '2019_11_21_162825_alter_chat_messages_table_add_queue', 314),
(615, '2019_11_21_221135_update_whatsapp_config_add_last_online_and_status_table', 314),
(616, '2019_11_21_114331_create_customer_next_action_table', 315),
(617, '2019_11_21_124341_alter_customers_table_add_customer_next_action_id', 315),
(618, '2019_11_22_222500_create_google_server_table', 315),
(619, '2019_11_27_120329_update_whatsapp_config_add_frequency_column', 316),
(620, '2019_11_27_183713_create_email_addresses_table', 316),
(621, '2019_11_28_113655_update_whatsapp_config_start_at_end_at_column', 316),
(622, '2019_11_28_210113_create_erp_priorities_table', 317),
(623, '2019_11_29_000331_alter_instructions_table_add_skipped_count', 317),
(624, '2019_10_13_023937_create_hubstaff_members', 318),
(625, '2019_11_17_175758_add_issue_table_column_to_developer_tasks_table', 318),
(626, '2019_11_28_132015_update_scraper_table_add_scraper_total_urls_scraper_existing_urls_scraper_new_urls_columns', 318),
(627, '2019_11_28_132530_create_scraper_results_table', 318),
(628, '2019_11_29_103258_update_whatsapp_config_table_date_add_status_device_name_sim_owner_column', 318),
(629, '2019_11_29_130327_create_marketing_message_types_table', 318),
(630, '2019_11_29_130650_update_im_queue_table_add_marketing_message_type_id_column', 318),
(631, '2019_11_29_173930_update_whatapp_config_table_add_sim_card_type_column', 318),
(632, '2019_11_30_170950_alter_tasks_table_add_approximate', 318),
(633, '2019_12_01_025124_create_instruction_times_table', 318),
(634, '2019_12_01_102159_update_whatsapp_config_add_status_column', 318),
(635, '2019_12_04_000331_alter_supplier_table_add_updated_by', 319),
(636, '2019_12_04_000332_alter_vendor_table_add_updated_by', 320),
(637, '2019_12_04_000333_alter_customer_table_add_updated_by', 321),
(638, '2019_12_05_115017_create_erp_events_table', 322),
(639, '2019_11_30_205052_create_customer_live_chats_table', 323),
(640, '2019_12_05_093945_update_live_chat_table_add_seen_add_status_column', 323),
(641, '2019_12_05_163418_create_livechatinc_settings_table', 323),
(642, '2019_12_05_165848_create_live_chat_users_table', 323),
(643, '2019_12_05_195319_update_livechat_column_add_username_add_key_columns', 323),
(644, '2019_12_07_000332_alter_suppliers_table_add_scraper_column', 324),
(645, '2019_12_06_102427_update_products_add_price_eur_colums', 325),
(646, '2019_12_06_185053_update_log_excel_imports_add_column_number_products_updated', 325),
(647, '2019_12_08_112842_create_scrape_queues', 325),
(648, '2019_12_09_062008_create_scrap_history_table', 326),
(649, '2019_12_11_000332_alter_order_table_column', 326),
(650, '2019_12_12_000332_alter_suppliers_table_add_scraper_column', 327),
(651, '2019_12_15_190018_create_list_magento', 328),
(652, '2019_12_16_132859_update_whats_app_config_is_connected_column', 328),
(653, '2019_12_17_103055_drop_category_maps', 328),
(654, '2019_12_17_103056_alter_products_table_add_barcode_column', 328),
(657, '2019_12_18_124824_create_scrapers_table', 329),
(658, '2019_12_11_015840_update_brands_table_add_column_google_server_id', 330),
(659, '2019_12_14_175836_create_log_google_cses_table', 330),
(660, '2019_12_17_105426_create_laravel_logs_table', 330),
(661, '2019_12_18_152443_alter_auto_replies_add_column_is_active', 330),
(662, '2019_12_19_000332_alter_categories_table_column', 330),
(667, '2019_12_19_081355_create_chat_message_words_table', 331),
(668, '2019_12_19_094947_create_chat_message_phrases_table', 331),
(669, '2019_12_19_104947_alter_chat_message_phrases_table_chat_id', 331),
(670, '2019_12_07_173545_update_categories_table_add_simplyduty_code_column', 332),
(671, '2019_12_07_174616_create_simply_duty_categories_table', 332),
(672, '2019_12_07_175144_create_simply_duty_currencies_table', 332),
(673, '2019_12_07_175310_create_simply_duty_countries_table', 332),
(674, '2019_12_07_175537_create_simply_duty_calculations_table', 332),
(675, '2019_12_08_151120_update_simply_duty_calculations_add_new_columns', 332),
(676, '2019_12_22_025706_create_chatbot_question_examples_table', 333),
(677, '2019_12_22_025706_create_chatbot_questions_table', 333),
(678, '2019_12_22_025706_create_chatbot_settings_table', 333),
(679, '2019_12_22_043134_create_chatbot_dialog_response_table', 333),
(680, '2019_12_22_043134_create_chatbot_dialog_table', 333),
(681, '2019_12_22_025706_create_chatbot_keywords_table', 333),
(682, '2019_12_22_025706_create_chatbot_keyword_values_table', 333),
(683, '2019_12_23_173545_update_customers_table_add_session_id', 333),
(684, '2019_12_24_012122_update_chat_messages_table_add_chatbot', 334),
(686, '2019_12_24_012123_create_chatbot_reply_table', 335),
(687, '2019_12_23_184610_create_chat_bot_keyword_groups_table', 336),
(688, '2019_12_23_184626_create_chat_bot_phrase_groups_table', 336),
(689, '2019_12_24_131315_update_brands_add_sales_columns', 336),
(690, '2019_12_24_131719_alter_products_add_column_price_eur_discounted', 336),
(691, '2019_12_24_135750_alter_product_suppliers_add_column_price_special', 336),
(692, '2019_12_21_191151_create_historial_datas_table', 337),
(693, '2019_12_21_201709_add_reference_column_in_developer_tasks_table', 337),
(694, '2019_12_26_144419_create_visitor_logs_table', 337),
(695, '2019_12_26_215024_add_sku_search_url_in_brands_table', 337),
(696, '2019_12_27_154621_create_log_tineye', 337),
(697, '2019_12_29_123721_add_speed_column_in_cropped_image_references_table', 337),
(698, '2019_12_29_170451_add_product_id_in_cropped_image_references_table', 337),
(699, '2019_12_31_000000_alter_product_table_has_mediables', 337),
(700, '2019_12_29_173545_update_chatbot_dialogs_add_fields', 338),
(701, '2020_01_02_150033_alter_supplier_add_scraped_brands', 339),
(702, '2020_01_04_090546_create_chatbot_intents_annotations_table', 339),
(703, '2020_01_04_123721_alter_developer_tasks_object_columns', 340),
(704, '2019_12_25_131857_add_column_is_send_comments_stats_table', 341),
(705, '2019_12_25_134001_add_comment_pending_accounts_table', 341),
(706, '2019_12_25_200404_create_instagram_comment_queues_table', 341),
(707, '2020_01_05_172322_add_new_columns_in_assets_manager_table', 341),
(708, '2020_01_07_172322_add_new_columns_in_chatbot_questions_table', 341),
(709, '2020_01_06_205933_create_chatbot_categories_table', 342),
(710, '2020_01_06_230946_alter_scrapers_table_add_status', 343),
(711, '2020_01_07_112251_create_scrap_influencers_table', 343),
(712, '2020_01_08_000000_add_new_columns_in_assets_manager_table', 343),
(713, '2020_01_08_071207_add_multiple_columns_in_scrapers_table', 344),
(714, '2020_01_08_071718_create_scraper_mappings_table', 344),
(715, '2020_01_09_000000_add_capacity_into_assets_manager_table', 344),
(716, '2020_01_06_181507_create_platforms_table', 345),
(717, '2020_01_06_183542_add_platform_id_in_hash_tags_table', 345),
(718, '2020_01_09_163727_add_column_auto_generate_product_in_templates_table', 345),
(719, '2020_01_14_000000_add_column_language_in_development_task', 345),
(720, '2019_12_19_171245_create_hs_code_groups_table', 346),
(721, '2019_12_21_111329_create_hs_code_groups_categories_compositions_table', 346),
(722, '2019_12_22_024336_update_simply_duty_categories_add_correct_composition_column', 346),
(723, '2019_12_22_144533_add_composition_column_hs_code_groups_table', 346),
(724, '2019_12_29_110412_create_hs_codes_table', 346),
(725, '2019_12_29_113054_create_hs_code_settings_table', 346),
(726, '2020_01_03_152642_add_new_magento_status_column_in_order_statuses_table', 346),
(727, '2020_01_12_113410_add_supplier_email_to_log_excel_imports_table', 346),
(728, '2020_01_13_140652_add_from_destionation_columns_in_hs_code_settings_table', 346),
(729, '2020_01_13_151109_add_language_column_to_suppliers_table_13-01-2020', 346),
(730, '2020_01_13_192327_change_format_from_integer_to_string_simply_duty_countries_table', 346),
(731, '2020_01_14_140859_alter_simply_duty_countries_table', 346),
(732, '2020_01_16_000000_alter_chat_message_table', 346),
(733, '2020_01_16_000001_alter_developer_task_table', 346),
(734, '2020_01_06_163601_add_user_hubstaff_refresh_token', 347),
(735, '2020_01_06_171458_add_hubstaff_user_email_column', 347),
(736, '2020_01_06_172550_change_hubstaff_token_db_type', 347),
(737, '2020_01_07_105417_create_hubstaff_projects_table', 347),
(738, '2020_01_07_111418_create_hubstaff_tasks_table', 347),
(739, '2020_01_08_163034_create_github_repositories_table', 347),
(740, '2020_01_08_163407_create_github_users_table', 347),
(741, '2020_01_08_164331_create_github_repository_users_table', 347),
(742, '2020_01_08_165048_create_github_groups_table', 347),
(743, '2020_01_08_165819_create_github_group_members', 347),
(744, '2020_01_08_173847_create_github_repository_groups', 347),
(745, '2020_01_16_113843_add_hubstaff_column_to_tasks', 347),
(746, '2020_01_16_114409_add_hubstaff_column_to_developer_tasks', 347),
(747, '2020_01_17_000001_alter_erp_priorities_user_id', 348),
(748, '2020_01_17_105642_create_hubstaff_activities_table', 349),
(749, '2020_01_17_145734_create_developer_task_documents_table', 349),
(750, '2020_01_17_152437_create_developer_languages_table', 349),
(751, '2020_01_18_151019_add_new_columns_to_scrap_influencers_tables', 350),
(752, '2020_01_18_165259_create_github_branch_state_table', 350),
(753, '2020_01_21_112613_add_branch_column_to_developer_tasks_table', 350),
(754, '2020_01_21_150649_add_column_to_branch_state_table', 351),
(755, '2020_01_22_000001_add_column_to_scrappers_table', 351),
(756, '2020_01_14_163035_create_affiliates_table', 352),
(757, '2020_01_17_115558_add_isflagged_and_title_to_affiliates', 352),
(758, '2020_01_20_203108_create_chatbot_keyword_value_types', 352),
(759, '2020_01_21_154054_add_new_types_column_in_chatbot_keyword_values_table', 352),
(760, '2020_01_15_133957_create_search_queues_table', 353),
(761, '2020_01_17_154416_create_mailinglists_table', 353),
(762, '2020_01_17_165917_create_services_table', 353),
(763, '2020_01_21_154552_add_mailing_list_contact_table', 353),
(764, '2020_01_22_121056_create_mailing_remarks_table', 353),
(766, '2020_01_23_071256_add_sizeeu_to_product_table', 354),
(767, '2020_01_23_031556_add_column_last_error_to_cron_job', 355),
(768, '2020_01_23_102342_add__hubstaff_activities_columns', 356),
(770, '2020_01_25_105858_create_store_websites_table', 357),
(771, '2020_01_25_140949_create_website_products_table', 357),
(774, '2020_01_28_122200_alter_store_website_remote', 358),
(775, '2020_01_23_111213_create_barcode_media_table', 359),
(776, '2020_01_23_031556_add_column_last_error_to_cron_job', 359),
(777, '2020_01_23_155355_add_new_column_broadcast_id_in_im_queues_tables', 360),
(778, '2020_01_23_163747_create_mailinglist_templates_table', 360),
(779, '2020_01_24_012657_create_public_keys_table', 360),
(780, '2020_01_24_105424_create_user_rates_table', 360),
(781, '2020_01_24_132520_create_mailing_template_files_table', 360),
(782, '2020_01_24_184616_create_mailinglist_emails_table', 360),
(783, '2020_01_25_122530_add_new_column_type_in_product_templates_table', 360),
(784, '2020_01_27_152614_add_language_column_to_customers_table_27-01-2020', 360),
(785, '2020_01_25_105858_create_store_websites_table', 359),
(786, '2020_01_28_122200_alter_store_website_remote', 359),
(787, '2020_01_28_160030_create_payment_methods_table', 361),
(788, '2020_01_28_160209_create_payments_table', 361),
(790, '2020_01_30_140949_create_store_website_category_table', 362),
(793, '2020_01_31_034749_alter_store_website_user_password', 363),
(794, '2020_01_15_120108_create_coupons_table', 364),
(795, '2020_01_15_120421_add_coupon_id_to_orders_table', 364),
(796, '2020_02_01_174257_create_laravel_log_github_table', 364),
(797, '2020_02_05_103116_add_accounted_column_to_hubstaff_activity', 364),
(798, '2020_02_05_153128_create_payment_account_table', 364),
(801, '2020_02_07_122916_alter_whatsapp_config_table', 365),
(802, '2020_01_24_151135_add_new_column_dialog_type_in_chatbot_dialogs_table', 366),
(803, '2020_02_07_113631_add_stacktrace_column', 367),
(804, '2020_02_11_121852_add_magento_status_column', 367),
(805, '2020_02_11_131620_add_is_active_in_permissions_table', 367),
(807, '2020_02_17_044720_alter_scraped_products_table', 368),
(808, '2019_12_06_112838_update_log_excel_imports_add_column_status', 369),
(809, '2019_12_07_123100_update_log_excel_imports_table_add_column_website', 369),
(811, '2020_02_20_000001_alter_column_in_customer_table', 370),
(813, '2020_02_20_070602_alter_chatbot_replies_table', 371),
(814, '2020_02_21_084943_create_user_events_table', 372),
(815, '2020_02_21_085015_create_user_event_attendees_table', 372),
(816, '2020_02_25_053016_alter_suggestion_table_chat_id', 372),
(817, '2020_02_26_034100_create_table_store_website_attach_brands_table', 373),
(819, '2020_02_26_044415_create_database_historical_records', 374),
(822, '2020_02_27_122715_alter_customer_table_reminder_fields', 375),
(823, '2020_02_27_122715_alter_vendor_table_reminder_fields', 376),
(824, '2020_01_24_115850_create_wetransfers_table', 377),
(825, '2020_02_27_130255_alter_add_crop_color_in_store_websites_table', 377),
(826, '2020_02_29_080822_add_supplier_in_wetransfers_tables', 377),
(827, '2020_03_02_112900_add_product_id_in_order_product_table', 377),
(828, '2020_02_21_051837_create_languages_table', 378),
(829, '2020_02_21_153941_create_translated_products_table', 378),
(830, '2020_03_02_141935_create_currency_table', 378),
(831, '2020_03_02_161427_add_currency_to_customer', 378),
(832, '2020_03_05_071610_add_product_id_to_scraper_products_table', 379),
(835, '2020_03_11_082627_create_block_web_message_list', 380),
(836, '2020_02_13_122007_make_hourly_rate_nullable_in_user_rates_table', 381),
(839, '2020_03_16_160327_create_email_templates', 382),
(840, '2020_03_15_174400_add_full_scrape_column_in_scrapers_table', 383),
(842, '2020_03_18_075200_add_mail_tpl_file_mailinglist_templates', 384),
(843, '2020_03_20_1295900_add_order_status_id_field_on_order', 385),
(844, '2020_03_21_012327_create_return_exchange_table', 386);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(845, '2020_03_21_014827_create_return_exchange_products_table', 387),
(846, '2020_04_07_045829_create_new_instagram_configs_table', 388),
(847, '2020_04_14_0339560_add_message_template_order_status_table', 388),
(848, '2020_04_15_061016_add_new_column_in_accounts_table', 388),
(849, '2020_04_27_033505_create_return_exchange_histories_table', 389),
(850, '2020_04_27_170048_add_likes_comments_count_in_instagram_posts_table', 389),
(851, '2020_04_27_170328_add_posts_followes_following_location_in_instagram_users_lists_table', 389),
(852, '2020_04_28_115812_create_site_developments_table', 390),
(853, '2020_04_28_120143_create_site_development_categories_table', 391),
(854, '2020_04_29_105826_add_site_development_id_in_chat_messages_table', 391),
(855, '2020_05_05_121243_create_page_instructions_table', 392),
(856, '2020_05_17_020105_add_social_profile_store_website_table', 392),
(857, '2020_05_18_012105_add_website_user_in_customer_table', 392),
(858, '2020_05_18_070804_create_digital_marketing_platform_remarks_table', 393),
(859, '2020_05_18_070804_create_digital_marketing_platform_table', 394),
(860, '2020_05_19_024804_create_digital_marketing_solution_attributes_table', 395),
(861, '2020_05_19_024804_create_digital_marketing_solutions_table', 396),
(862, '2020_05_19_024804_create_digital_marketing_usp_table', 397),
(863, '2020_05_20_013804_create_digital_marketing_solution_researches_table', 398),
(864, '2020_05_23_013720_add_participants_in_user_event_table', 399),
(865, '2020_05_27_092600_add_daily_activities_id_in_user_event_table', 399),
(866, '2020_05_27_100102_create_general_categories_table', 400),
(867, '2020_05_27_100103_add_general_category_id_in_daily_activities_table', 400),
(868, '2020_05_27_100103_add_general_category_id_in_task_table', 400),
(869, '2020_05_29_032000_add_stock_status_in_product_table', 400),
(870, '2020_05_29_124502_create_digital_marketing_platform_components_table', 401),
(871, '2020_05_30_120808_create_posts_table', 401),
(872, '2020_06_01_022102_create_store_website_goals_table', 402),
(873, '2020_06_01_022110_create_store_website_goal_remarks_table', 403),
(874, '2020_06_01_051420_add_shopify_id_in_product_table', 403),
(877, '2020_06_03_122020_add_landing_page_product_table', 404),
(878, '2020_06_01_115120_add_actual_start_date_in_task_table', 405),
(879, '2020_06_01_115103_add_actual_start_date_in_daily_activities_table', 406),
(880, '2020_06_06_030601_add_customer_kyc_documents_table', 407),
(881, '2020_06_09_011415_add_site_development_status_table', 408),
(882, '2020_06_08_063420_add_product_category_histories_table', 409),
(883, '2020_06_09_041420_add_status_field_in_vendor_table', 410),
(884, '2020_06_11_031820_add_product_verifying_users_table', 411),
(885, '2020_06_06_073819_add_is_manual_and_is_processed_in_instagram_users_lists_table', 412),
(886, '2020_06_16_050809_add_fields_in_hubstaff_payment_accounts', 413),
(887, '2020_06_18_112800_alter_field_user_id_in_hubstaff_member', 414),
(888, '2020_06_18_012200_alter_field_min_activity_percentage_in_table_hubstaff_members', 415),
(889, '2020_06_18_032220_add_hubstaff_activity_notification_table', 416),
(890, '2020_06_19_103320_add_category_update_user_table', 417),
(892, '2020_06_19_033020_store_website_product_attributes_table', 418),
(893, '2020_06_23_071120_create_country_duty_table', 419),
(894, '2020_06_23_071220_create_duty_group_table', 419),
(896, '2020_06_25_115914_price_override_table', 420),
(897, '2020_06_25_115920_alter_table_store_website', 421),
(899, '2020_06_26_044220_alter_table_price_override_table', 422),
(900, '2020_06_25_053220_add_magneto_value_to_store_website_brands_table', 423),
(901, '2020_06_29_154553_site_development_hidden_categories_table', 424),
(903, '2020_06_30_090439_add_currency_to_orders_table', 425),
(904, '2020_06_30_075314_create_table_user_bank_information', 426),
(906, '2020_07_01_061920_add_api_key_in_store_website_table', 427),
(907, '2020_07_01_103058_create_invoices_table', 428),
(908, '2020_07_01_103427_add_invoice_id_to_orders_table', 428),
(909, '2020_07_02_033410_create_country_groups_table', 428),
(910, '2020_07_02_033420_create_country_group_items_table', 428),
(911, '2020_07_02_044220_alter_table_price_override_country_group', 429),
(913, '2020_07_03_011358_create_product_color_history_table', 430),
(914, '2020_07_03_110446_edit_store_website_table_for_some_fields', 431),
(915, '2020_07_04_083453_create_product_translations_table', 431),
(916, '2020_07_04_084133_add_active_to_languages_table', 431);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monetary_accounts`
--

CREATE TABLE `monetary_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `currency` int(11) NOT NULL DEFAULT '1',
  `amount` decimal(13,4) DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `short_note` mediumtext COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `other` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `reply` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
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
  `isread` tinyint(1) NOT NULL DEFAULT '0',
  `reminder` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_queues`
--

CREATE TABLE `notification_queues` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
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
  `reminder` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `is_blocked` int(11) NOT NULL DEFAULT '0',
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gst` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_iban` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_swift` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `pending_payment` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_payable` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `old_categories`
--

CREATE TABLE `old_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `clothing_size` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shoe_size` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `advance_detail` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `advance_date` date DEFAULT NULL,
  `balance_amount` int(11) DEFAULT NULL,
  `sales_person` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_phone_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_status_id` int(11) DEFAULT NULL,
  `date_of_delivery` date DEFAULT NULL,
  `estimated_delivery_date` date DEFAULT NULL,
  `note_if_any` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assign_status` int(2) DEFAULT NULL,
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `refund_answer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refund_answer_date` datetime DEFAULT NULL,
  `auto_messaged` int(11) NOT NULL DEFAULT '0',
  `auto_messaged_date` timestamp NULL DEFAULT NULL,
  `auto_emailed` tinyint(4) NOT NULL DEFAULT '0',
  `auto_emailed_date` timestamp NULL DEFAULT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `is_priority` tinyint(1) NOT NULL DEFAULT '0',
  `coupon_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `whatsapp_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` int(10) UNSIGNED DEFAULT '1',
  `purchase_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipment_date` datetime DEFAULT NULL,
  `reschedule_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `purchase_id` int(10) UNSIGNED DEFAULT NULL,
  `batch_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `order_statuses`
--

CREATE TABLE `order_statuses` (
  `id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `magento_status` varchar(191) DEFAULT NULL,
  `message_text_tpl` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `restricted` tinyint(1) NOT NULL DEFAULT '0',
  `draft` tinyint(1) NOT NULL DEFAULT '0',
  `markdown` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `revision_count` int(11) NOT NULL,
  `template` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `page_instructions`
--

CREATE TABLE `page_instructions` (
  `id` int(10) UNSIGNED NOT NULL,
  `page` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instruction` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `page_notes_categories`
--

CREATE TABLE `page_notes_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `payment_method_id` int(10) UNSIGNED NOT NULL,
  `note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double(8,2) NOT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `route` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_user`
--

CREATE TABLE `permission_user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `platforms`
--

CREATE TABLE `platforms` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_id` int(11) NOT NULL,
  `type` enum('post','album','story') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post',
  `ig` longtext COLLATE utf8mb4_unicode_ci,
  `caption` text COLLATE utf8mb4_unicode_ci,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `status` enum('1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `scheduled_at` datetime DEFAULT NULL,
  `posted_at` datetime DEFAULT NULL,
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
  `instagram` int(11) NOT NULL DEFAULT '0',
  `facebook` int(11) NOT NULL DEFAULT '0',
  `pinterest` int(11) NOT NULL DEFAULT '0',
  `twitter` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `price_overrides`
--

CREATE TABLE `price_overrides` (
  `id` int(10) UNSIGNED NOT NULL,
  `store_website_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `brand_segment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `country_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_group_id` int(11) DEFAULT NULL,
  `type` enum('PERCENTAGE','FIXED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PERCENTAGE',
  `calculated` enum('+','-') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '+',
  `value` decimal(8,2) NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `private_view_products`
--

CREATE TABLE `private_view_products` (
  `id` int(11) NOT NULL,
  `private_view_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `status_id` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `short_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` text COLLATE utf8mb4_unicode_ci,
  `size_eu` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(8,2) DEFAULT NULL,
  `price_eur_special` decimal(8,2) NOT NULL DEFAULT '0.00',
  `price_eur_discounted` double NOT NULL DEFAULT '0',
  `stage` tinyint(1) NOT NULL DEFAULT '1',
  `measurement_size_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lmeasurement` text COLLATE utf8mb4_unicode_ci,
  `hmeasurement` text COLLATE utf8mb4_unicode_ci,
  `dmeasurement` text COLLATE utf8mb4_unicode_ci,
  `size_value` int(4) DEFAULT NULL,
  `composition` longtext COLLATE utf8mb4_unicode_ci,
  `made_in` varchar(191) CHARACTER SET utf8 DEFAULT NULL,
  `brand` int(11) DEFAULT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_inr` double DEFAULT NULL,
  `price_inr_special` double DEFAULT '0',
  `price_inr_discounted` double NOT NULL DEFAULT '0',
  `price_special_offer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `euro_to_inr` int(11) DEFAULT NULL,
  `percentage` int(11) DEFAULT NULL,
  `factor` double DEFAULT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `dnf` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isApproved` tinyint(1) DEFAULT '0',
  `rejected_note` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isUploaded` tinyint(1) NOT NULL DEFAULT '0',
  `is_uploaded_date` datetime DEFAULT NULL,
  `isFinal` tinyint(1) NOT NULL DEFAULT '0',
  `isListed` tinyint(1) NOT NULL DEFAULT '0',
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `stock` int(4) NOT NULL DEFAULT '0',
  `is_on_sale` tinyint(1) NOT NULL DEFAULT '0',
  `purchase_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock_status` int(11) DEFAULT NULL,
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_link` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_link` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_link` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_attributer` int(10) UNSIGNED DEFAULT NULL,
  `last_imagecropper` int(10) UNSIGNED DEFAULT NULL,
  `last_selector` int(10) UNSIGNED DEFAULT NULL,
  `last_searcher` int(10) UNSIGNED DEFAULT NULL,
  `quick_product` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `import_date` datetime DEFAULT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `shopify_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_scraped` tinyint(1) NOT NULL,
  `is_image_processed` tinyint(1) NOT NULL DEFAULT '0',
  `is_without_image` tinyint(1) NOT NULL DEFAULT '0',
  `is_price_different` tinyint(1) NOT NULL DEFAULT '0',
  `crop_count` int(11) NOT NULL DEFAULT '0',
  `is_crop_rejected` tinyint(4) NOT NULL DEFAULT '0',
  `crop_remark` text COLLATE utf8mb4_unicode_ci,
  `is_crop_approved` tinyint(4) NOT NULL DEFAULT '0',
  `is_farfetched` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) DEFAULT NULL,
  `reject_approved_by` int(11) DEFAULT NULL,
  `was_crop_rejected` tinyint(1) NOT NULL DEFAULT '0',
  `crop_rejected_by` int(11) DEFAULT NULL,
  `crop_approved_by` int(11) DEFAULT NULL,
  `is_being_cropped` tinyint(1) NOT NULL DEFAULT '0',
  `is_crop_ordered` tinyint(1) NOT NULL DEFAULT '0',
  `listing_remark` text COLLATE utf8mb4_unicode_ci,
  `is_listing_rejected` tinyint(1) NOT NULL DEFAULT '0',
  `listing_rejected_by` int(11) DEFAULT NULL,
  `listing_rejected_on` date DEFAULT NULL,
  `is_corrected` tinyint(1) NOT NULL DEFAULT '0',
  `is_script_corrected` tinyint(1) NOT NULL DEFAULT '0',
  `is_authorized` tinyint(1) NOT NULL DEFAULT '0',
  `authorized_by` int(11) DEFAULT NULL,
  `crop_ordered_by` int(11) DEFAULT NULL,
  `is_crop_being_verified` tinyint(1) NOT NULL DEFAULT '0',
  `crop_approved_at` datetime DEFAULT NULL,
  `crop_rejected_at` datetime DEFAULT NULL,
  `crop_ordered_at` datetime DEFAULT NULL,
  `listing_approved_at` datetime DEFAULT NULL,
  `is_order_rejected` tinyint(1) NOT NULL DEFAULT '0',
  `manual_crop` tinyint(1) NOT NULL DEFAULT '0',
  `is_manual_cropped` tinyint(1) NOT NULL DEFAULT '0',
  `manual_cropped_by` int(11) DEFAULT NULL,
  `manual_cropped_at` datetime DEFAULT NULL,
  `is_titlecased` tinyint(1) NOT NULL DEFAULT '0',
  `is_listing_rejected_automatically` tinyint(1) NOT NULL DEFAULT '0',
  `was_auto_rejected` tinyint(1) NOT NULL DEFAULT '0',
  `is_being_ordered` tinyint(1) NOT NULL DEFAULT '0',
  `instruction_completed_at` datetime DEFAULT NULL,
  `is_auto_processing_failed` tinyint(1) NOT NULL DEFAULT '0',
  `is_crop_skipped` tinyint(1) NOT NULL DEFAULT '0',
  `cropped_at` datetime DEFAULT NULL,
  `is_enhanced` tinyint(1) NOT NULL DEFAULT '0',
  `is_pending` int(11) NOT NULL DEFAULT '0',
  `is_barcode_check` int(11) DEFAULT NULL,
  `has_mediables` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products_new`
--

CREATE TABLE `products_new` (
  `id` int(10) UNSIGNED NOT NULL,
  `status_id` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `name` text CHARACTER SET utf8mb4,
  `short_description` longtext CHARACTER SET utf8mb4,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(8,2) DEFAULT NULL,
  `price_eur_special` decimal(8,2) NOT NULL DEFAULT '0.00',
  `price_eur_discounted` double NOT NULL DEFAULT '0',
  `stage` tinyint(1) NOT NULL DEFAULT '1',
  `measurement_size_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lmeasurement` text COLLATE utf8mb4_unicode_ci,
  `hmeasurement` text COLLATE utf8mb4_unicode_ci,
  `dmeasurement` text COLLATE utf8mb4_unicode_ci,
  `size_value` int(4) DEFAULT NULL,
  `composition` longtext COLLATE utf8mb4_unicode_ci,
  `made_in` varchar(191) CHARACTER SET utf8 DEFAULT NULL,
  `brand` int(11) DEFAULT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_inr` double DEFAULT NULL,
  `price_inr_special` double DEFAULT '0',
  `price_inr_discounted` double NOT NULL DEFAULT '0',
  `price_special_offer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `euro_to_inr` int(11) DEFAULT NULL,
  `percentage` int(11) DEFAULT NULL,
  `factor` double DEFAULT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `dnf` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isApproved` tinyint(1) DEFAULT '0',
  `rejected_note` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isUploaded` tinyint(1) NOT NULL DEFAULT '0',
  `is_uploaded_date` datetime DEFAULT NULL,
  `isFinal` tinyint(1) NOT NULL DEFAULT '0',
  `isListed` tinyint(1) NOT NULL DEFAULT '0',
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `stock` int(4) NOT NULL DEFAULT '0',
  `is_on_sale` tinyint(1) NOT NULL DEFAULT '0',
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
  `quick_product` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `import_date` datetime DEFAULT NULL,
  `status` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_scraped` tinyint(1) NOT NULL,
  `is_image_processed` tinyint(1) NOT NULL DEFAULT '0',
  `is_without_image` tinyint(1) NOT NULL DEFAULT '0',
  `is_price_different` tinyint(1) NOT NULL DEFAULT '0',
  `crop_count` int(11) NOT NULL DEFAULT '0',
  `is_crop_rejected` tinyint(4) NOT NULL DEFAULT '0',
  `crop_remark` text COLLATE utf8mb4_unicode_ci,
  `is_crop_approved` tinyint(4) NOT NULL DEFAULT '0',
  `is_farfetched` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) DEFAULT NULL,
  `reject_approved_by` int(11) DEFAULT NULL,
  `was_crop_rejected` tinyint(1) NOT NULL DEFAULT '0',
  `crop_rejected_by` int(11) DEFAULT NULL,
  `crop_approved_by` int(11) DEFAULT NULL,
  `is_being_cropped` tinyint(1) NOT NULL DEFAULT '0',
  `is_crop_ordered` tinyint(1) NOT NULL DEFAULT '0',
  `listing_remark` text COLLATE utf8mb4_unicode_ci,
  `is_listing_rejected` tinyint(1) NOT NULL DEFAULT '0',
  `listing_rejected_by` int(11) DEFAULT NULL,
  `listing_rejected_on` date DEFAULT NULL,
  `is_corrected` tinyint(1) NOT NULL DEFAULT '0',
  `is_script_corrected` tinyint(1) NOT NULL DEFAULT '0',
  `is_authorized` tinyint(1) NOT NULL DEFAULT '0',
  `authorized_by` int(11) DEFAULT NULL,
  `crop_ordered_by` int(11) DEFAULT NULL,
  `is_crop_being_verified` tinyint(1) NOT NULL DEFAULT '0',
  `crop_approved_at` datetime DEFAULT NULL,
  `crop_rejected_at` datetime DEFAULT NULL,
  `crop_ordered_at` datetime DEFAULT NULL,
  `listing_approved_at` datetime DEFAULT NULL,
  `is_order_rejected` tinyint(1) NOT NULL DEFAULT '0',
  `manual_crop` tinyint(1) NOT NULL DEFAULT '0',
  `is_manual_cropped` tinyint(1) NOT NULL DEFAULT '0',
  `manual_cropped_by` int(11) DEFAULT NULL,
  `manual_cropped_at` datetime DEFAULT NULL,
  `is_titlecased` tinyint(1) NOT NULL DEFAULT '0',
  `is_listing_rejected_automatically` tinyint(1) NOT NULL DEFAULT '0',
  `was_auto_rejected` tinyint(1) NOT NULL DEFAULT '0',
  `is_being_ordered` tinyint(1) NOT NULL DEFAULT '0',
  `instruction_completed_at` datetime DEFAULT NULL,
  `is_auto_processing_failed` tinyint(1) NOT NULL DEFAULT '0',
  `is_crop_skipped` tinyint(1) NOT NULL DEFAULT '0',
  `cropped_at` datetime DEFAULT NULL,
  `is_enhanced` tinyint(1) NOT NULL DEFAULT '0',
  `is_pending` int(11) NOT NULL DEFAULT '0',
  `is_barcode_check` int(11) DEFAULT NULL,
  `has_mediables` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_attributes`
--

CREATE TABLE `product_attributes` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `has_mediables` int(11) DEFAULT NULL,
  `is_barcode_check` int(11) DEFAULT NULL,
  `was_auto_rejected` int(11) DEFAULT '0',
  `is_crop_skipped` int(11) DEFAULT '0',
  `is_auto_processing_failed` int(11) DEFAULT '0',
  `is_being_ordered` tinyint(1) NOT NULL DEFAULT '0',
  `is_listing_rejected_automatically` tinyint(4) DEFAULT '0',
  `is_titlecased` tinyint(4) DEFAULT '0',
  `manual_cropped_at` datetime DEFAULT NULL,
  `manual_cropped_by` int(11) DEFAULT NULL,
  `is_manual_cropped` tinyint(1) NOT NULL DEFAULT '0',
  `manual_crop` tinyint(1) NOT NULL DEFAULT '0',
  `is_order_rejected` tinyint(1) NOT NULL DEFAULT '0',
  `cropped_at` datetime DEFAULT NULL,
  `instruction_completed_at` datetime DEFAULT NULL,
  `is_crop_being_verified` tinyint(1) NOT NULL DEFAULT '0',
  `authorized_by` int(11) DEFAULT NULL,
  `is_authorized` tinyint(1) NOT NULL DEFAULT '0',
  `is_being_cropped` tinyint(1) NOT NULL DEFAULT '0',
  `was_crop_rejected` tinyint(1) NOT NULL DEFAULT '0',
  `import_date` datetime DEFAULT NULL,
  `is_price_different` tinyint(1) NOT NULL DEFAULT '0',
  `last_searcher` int(11) DEFAULT NULL,
  `last_selector` int(11) DEFAULT NULL,
  `last_attributer` int(11) DEFAULT NULL,
  `is_enhanced` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_category_histories`
--

CREATE TABLE `product_category_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `old_category_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_color_histories`
--

CREATE TABLE `product_color_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `old_color` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_dispatch`
--

CREATE TABLE `product_dispatch` (
  `id` int(10) UNSIGNED NOT NULL,
  `modeof_shipment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_person` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `awb` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eta` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_time` datetime NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_location`
--

CREATE TABLE `product_location` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_location_history`
--

CREATE TABLE `product_location_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `location_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `courier_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `courier_details` text COLLATE utf8mb4_unicode_ci,
  `date_time` datetime NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_quickshell_groups`
--

CREATE TABLE `product_quickshell_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `quicksell_group_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `supplier_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_special` double NOT NULL DEFAULT '0',
  `price_discounted` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` text COLLATE utf8mb4_unicode_ci,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `composition` text COLLATE utf8mb4_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_templates`
--

CREATE TABLE `product_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `template_no` int(11) NOT NULL DEFAULT '0',
  `product_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `currency` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `discounted_price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `product_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_processed` int(11) DEFAULT '0',
  `category_id` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_translations`
--

CREATE TABLE `product_translations` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_verifying_users`
--

CREATE TABLE `product_verifying_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `public_keys`
--

CREATE TABLE `public_keys` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `proforma_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `proforma_id` varchar(191) DEFAULT NULL,
  `proforma_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_customer`
--

CREATE TABLE `purchase_order_customer` (
  `id` int(10) UNSIGNED NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_products`
--

CREATE TABLE `purchase_products` (
  `id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_product_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_product_supplier`
--

CREATE TABLE `purchase_product_supplier` (
  `product_id` int(10) UNSIGNED NOT NULL,
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `chat_message_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_status`
--

CREATE TABLE `purchase_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `isread` tinyint(1) NOT NULL DEFAULT '0',
  `reminder` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quick_replies`
--

CREATE TABLE `quick_replies` (
  `id` int(10) UNSIGNED NOT NULL,
  `text` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `details` longtext,
  `dispatch_date` timestamp NULL DEFAULT NULL,
  `date_of_request` timestamp NULL DEFAULT NULL,
  `credited` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `remarks`
--

CREATE TABLE `remarks` (
  `id` int(10) NOT NULL,
  `taskid` int(10) DEFAULT NULL,
  `module_type` varchar(255) DEFAULT NULL,
  `remark` text,
  `user_name` text,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `id` int(11) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `reply` longtext NOT NULL,
  `model` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reply_categories`
--

CREATE TABLE `reply_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `resource_categories`
--

CREATE TABLE `resource_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(299) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(199) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resource_images`
--

CREATE TABLE `resource_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `cat_id` int(10) UNSIGNED NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image1` text COLLATE utf8mb4_unicode_ci,
  `image2` text COLLATE utf8mb4_unicode_ci,
  `created_by` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `images` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_cat_id` int(11) NOT NULL,
  `is_pending` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `return_exchanges`
--

CREATE TABLE `return_exchanges` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(11) NOT NULL,
  `type` enum('refund','exchange') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason_for_refund` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refund_amount` decimal(8,2) DEFAULT '0.00',
  `status` int(11) NOT NULL,
  `pickup_address` text COLLATE utf8mb4_unicode_ci,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `return_exchange_histories`
--

CREATE TABLE `return_exchange_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `return_exchange_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `return_exchange_products`
--

CREATE TABLE `return_exchange_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `return_exchange_id` int(11) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `order_product_id` text COLLATE utf8mb4_unicode_ci,
  `name` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
  `is_approved` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `posted_date` datetime DEFAULT NULL,
  `platform` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `review_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rude_words`
--

CREATE TABLE `rude_words` (
  `id` int(10) UNSIGNED NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `universal` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `check_1` tinyint(1) DEFAULT '0',
  `check_2` tinyint(1) NOT NULL DEFAULT '0',
  `check_3` tinyint(1) NOT NULL DEFAULT '0',
  `sent_to_client` time DEFAULT NULL,
  `remark` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `old_price` tinytext,
  `new_price` tinytext NOT NULL,
  `description` longtext,
  `dimension` mediumtext,
  `SKU` text NOT NULL,
  `country` text,
  `material_used` mediumtext,
  `color` text,
  `images` longtext,
  `sizes` text,
  `category` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_messages`
--

CREATE TABLE `scheduled_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` text COLLATE utf8mb4_unicode_ci,
  `data` text COLLATE utf8mb4_unicode_ci,
  `sent` tinyint(1) NOT NULL DEFAULT '0',
  `sending_time` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_groups`
--

CREATE TABLE `schedule_groups` (
  `id` int(11) NOT NULL,
  `images` text NOT NULL,
  `description` text,
  `scheduled_for` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `scraped_products`
--

CREATE TABLE `scraped_products` (
  `id` int(11) NOT NULL,
  `website` varchar(255) NOT NULL,
  `is_excel` tinyint(4) NOT NULL DEFAULT '0',
  `sku` varchar(255) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `has_sku` tinyint(1) NOT NULL DEFAULT '0',
  `title` text NOT NULL,
  `brand_id` int(10) UNSIGNED NOT NULL,
  `description` longtext,
  `images` mediumtext NOT NULL,
  `currency` varchar(3) DEFAULT NULL,
  `price` varchar(255) NOT NULL,
  `price_eur` decimal(8,2) DEFAULT NULL,
  `discounted_price_eur` decimal(8,2) DEFAULT NULL,
  `size_system` varchar(2) DEFAULT NULL,
  `properties` longtext,
  `url` mediumtext,
  `is_property_updated` tinyint(4) NOT NULL DEFAULT '0',
  `is_price_updated` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_enriched` tinyint(1) NOT NULL DEFAULT '0',
  `can_be_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `is_color_fixed` tinyint(1) NOT NULL DEFAULT '0',
  `is_sale` tinyint(1) NOT NULL DEFAULT '0',
  `original_sku` varchar(255) DEFAULT NULL,
  `discounted_price` varchar(191) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `validated` int(11) DEFAULT NULL,
  `validation_result` text,
  `raw_data` text,
  `last_inventory_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `scrapers`
--

CREATE TABLE `scrapers` (
  `id` int(10) UNSIGNED NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `parent_supplier_id` int(11) DEFAULT '0',
  `scraper_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_scrape` tinyint(4) NOT NULL DEFAULT '0',
  `scraper_type` int(11) DEFAULT NULL,
  `scraper_total_urls` int(11) NOT NULL DEFAULT '0',
  `scraper_new_urls` int(11) NOT NULL DEFAULT '0',
  `scraper_existing_urls` int(11) NOT NULL DEFAULT '0',
  `scraper_start_time` int(11) NOT NULL,
  `scraper_logic` text COLLATE utf8mb4_unicode_ci,
  `scraper_made_by` int(11) DEFAULT NULL,
  `scraper_priority` int(11) DEFAULT NULL,
  `inventory_lifetime` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `next_step_in_product_flow` int(11) DEFAULT NULL,
  `end_time` datetime NOT NULL,
  `start_time` datetime NOT NULL,
  `product_url_selector` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designer_url_selector` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `starting_urls` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time_out` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `run_gap` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '24',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `server_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scraper_mappings`
--

CREATE TABLE `scraper_mappings` (
  `id` int(10) UNSIGNED NOT NULL,
  `scrapers_id` int(11) NOT NULL,
  `selector` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `function` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parameter` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scraper_results`
--

CREATE TABLE `scraper_results` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `scraper_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_urls` int(11) NOT NULL,
  `existing_urls` int(11) NOT NULL,
  `new_urls` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scraper_server_histories`
--

CREATE TABLE `scraper_server_histories` (
  `id` int(11) NOT NULL,
  `scraper_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `scrape_queues`
--

CREATE TABLE `scrape_queues` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `done` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scrap_activities`
--

CREATE TABLE `scrap_activities` (
  `id` int(10) UNSIGNED NOT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scraped_product_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scrap_entries`
--

CREATE TABLE `scrap_entries` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` longtext NOT NULL,
  `site_name` varchar(16) NOT NULL DEFAULT 'GNB',
  `is_scraped` tinyint(1) NOT NULL DEFAULT '0',
  `is_product_page` tinyint(1) NOT NULL DEFAULT '0',
  `pagination` longtext,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_updated_on_server` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `scrap_histories`
--

CREATE TABLE `scrap_histories` (
  `id` int(11) NOT NULL,
  `operation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int(11) NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scrap_influencers`
--

CREATE TABLE `scrap_influencers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `followers` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `following` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `posts` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `search_queues`
--

CREATE TABLE `search_queues` (
  `id` int(10) UNSIGNED NOT NULL,
  `search_type` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `search_term` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int(11) DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `welcome_message` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `simply_duty_calculations`
--

CREATE TABLE `simply_duty_calculations` (
  `id` int(10) UNSIGNED NOT NULL,
  `vat` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vat_rate` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vat_minimis` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duty_minimis` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_type_destination` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_type_origin` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `insurance` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duty_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duty_hscode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duty` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duty_rate` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `simply_duty_categories`
--

CREATE TABLE `simply_duty_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `correct_composition` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `simply_duty_countries`
--

CREATE TABLE `simply_duty_countries` (
  `id` int(10) UNSIGNED NOT NULL,
  `country_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `simply_duty_currencies`
--

CREATE TABLE `simply_duty_currencies` (
  `id` int(10) UNSIGNED NOT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_developments`
--

CREATE TABLE `site_developments` (
  `id` int(10) UNSIGNED NOT NULL,
  `site_development_category_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `developer_id` int(11) DEFAULT NULL,
  `website_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_development_categories`
--

CREATE TABLE `site_development_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_development_hidden_categories`
--

CREATE TABLE `site_development_hidden_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `store_website_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_development_statuses`
--

CREATE TABLE `site_development_statuses` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `social_tags`
--

CREATE TABLE `social_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stock_products`
--

CREATE TABLE `stock_products` (
  `id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `store_websites`
--

CREATE TABLE `store_websites` (
  `id` int(11) NOT NULL,
  `website` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `cropper_color_name` varchar(191) DEFAULT NULL,
  `cropper_color` varchar(191) DEFAULT NULL,
  `is_published` int(11) NOT NULL DEFAULT '0',
  `remote_software` varchar(191) DEFAULT NULL,
  `magento_url` varchar(191) DEFAULT NULL,
  `magento_username` varchar(191) DEFAULT NULL,
  `magento_password` varchar(191) DEFAULT NULL,
  `api_token` varchar(191) DEFAULT NULL,
  `instagram` varchar(191) DEFAULT NULL,
  `instagram_remarks` text,
  `facebook` varchar(191) DEFAULT NULL,
  `facebook_remarks` text,
  `country_duty` varchar(191) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `server_ip` varchar(191) DEFAULT NULL,
  `username` varchar(191) DEFAULT NULL,
  `password` varchar(191) DEFAULT NULL,
  `staging_username` varchar(191) DEFAULT NULL,
  `staging_password` varchar(191) DEFAULT NULL,
  `mysql_username` varchar(191) DEFAULT NULL,
  `mysql_password` varchar(191) DEFAULT NULL,
  `mysql_staging_username` varchar(191) DEFAULT NULL,
  `mysql_staging_password` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `store_website_brands`
--

CREATE TABLE `store_website_brands` (
  `id` int(10) UNSIGNED NOT NULL,
  `brand_id` int(11) NOT NULL,
  `markup` double(8,2) DEFAULT '0.00',
  `magento_value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_website_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store_website_categories`
--

CREATE TABLE `store_website_categories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `remote_id` int(11) DEFAULT NULL,
  `store_website_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store_website_goals`
--

CREATE TABLE `store_website_goals` (
  `id` int(10) UNSIGNED NOT NULL,
  `goal` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `solution` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_website_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store_website_goal_remarks`
--

CREATE TABLE `store_website_goal_remarks` (
  `id` int(10) UNSIGNED NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_website_goal_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store_website_product_attributes`
--

CREATE TABLE `store_website_product_attributes` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `store_website_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `number` int(11) NOT NULL DEFAULT '5',
  `chat_message_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `is_flagged` tinyint(1) NOT NULL DEFAULT '0',
  `has_error` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `source` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `brands` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `scraped_brands_raw` longtext COLLATE utf8mb4_unicode_ci,
  `scraped_brands` longtext COLLATE utf8mb4_unicode_ci,
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_updated` tinyint(1) NOT NULL DEFAULT '1',
  `frequency` int(11) NOT NULL DEFAULT '0',
  `reminder_message` text COLLATE utf8mb4_unicode_ci,
  `is_blocked` tinyint(4) NOT NULL DEFAULT '0',
  `updated_by` int(11) NOT NULL,
  `language` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `url` longtext COLLATE utf8mb4_unicode_ci,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `url` longtext COLLATE utf8mb4_unicode_ci,
  `brand_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_category`
--

CREATE TABLE `supplier_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_status`
--

CREATE TABLE `supplier_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table 159`
--

CREATE TABLE `table 159` (
  `COL 1` varchar(50) DEFAULT NULL,
  `COL 2` varchar(6) DEFAULT NULL,
  `COL 3` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `is_private` tinyint(1) NOT NULL DEFAULT '0',
  `is_watched` tinyint(1) NOT NULL DEFAULT '0',
  `is_flagged` tinyint(1) NOT NULL DEFAULT '0',
  `task_details` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_subject` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `completion_date` timestamp NULL DEFAULT NULL,
  `remark` text CHARACTER SET utf8,
  `actual_start_date` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `is_completed` timestamp NULL DEFAULT NULL,
  `general_category_id` int(11) DEFAULT NULL,
  `is_verified` datetime DEFAULT NULL,
  `sending_time` datetime DEFAULT NULL,
  `time_slot` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `planned_at` date DEFAULT NULL,
  `pending_for` int(11) NOT NULL DEFAULT '0',
  `recurring_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statutory_id` int(11) DEFAULT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `approximate` int(11) NOT NULL DEFAULT '0',
  `hubstaff_task_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_categories`
--

CREATE TABLE `task_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_approved` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_types`
--

CREATE TABLE `task_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_users`
--

CREATE TABLE `task_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `task_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `no_of_images` int(11) NOT NULL DEFAULT '0',
  `auto_generate_product` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `translated_products`
--

CREATE TABLE `translated_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `name` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `auth_token_hubstaff` text COLLATE utf8mb4_unicode_ci,
  `refresh_token_hubstaff` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `last_checked` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `agent_role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_assigned` int(10) UNSIGNED DEFAULT NULL,
  `is_planner_completed` tinyint(1) NOT NULL DEFAULT '1',
  `crop_approval_rate` decimal(8,2) NOT NULL,
  `crop_rejection_rate` decimal(8,2) NOT NULL,
  `listing_approval_rate` decimal(8,2) DEFAULT NULL,
  `listing_rejection_rate` decimal(8,2) DEFAULT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_auto_comment_histories`
--

CREATE TABLE `users_auto_comment_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `auto_comment_history_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `is_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_bank_informations`
--

CREATE TABLE `user_bank_informations` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ifsc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_events`
--

CREATE TABLE `user_events` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `daily_activity_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_event_attendees`
--

CREATE TABLE `user_event_attendees` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_event_id` int(11) NOT NULL,
  `contact` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `suggested_time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_event_participants`
--

CREATE TABLE `user_event_participants` (
  `id` int(10) UNSIGNED NOT NULL,
  `object` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_id` int(11) NOT NULL,
  `user_event_id` int(11) NOT NULL
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_products`
--

CREATE TABLE `user_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `content` text COLLATE utf8mb4_unicode_ci,
  `message` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_rates`
--

CREATE TABLE `user_rates` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `hourly_rate` double DEFAULT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `notes` longtext COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `frequency` int(11) NOT NULL DEFAULT '0',
  `reminder_last_reply` int(11) NOT NULL DEFAULT '1',
  `reminder_from` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reminder_message` text COLLATE utf8mb4_unicode_ci,
  `has_error` tinyint(4) NOT NULL DEFAULT '0',
  `is_blocked` tinyint(4) NOT NULL DEFAULT '0',
  `updated_by` int(11) NOT NULL,
  `status` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payments`
--

CREATE TABLE `vendor_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `currency` int(11) NOT NULL DEFAULT '0',
  `payment_date` date DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `payable_amount` decimal(13,4) DEFAULT NULL,
  `paid_amount` decimal(13,4) DEFAULT NULL,
  `service_provided` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_hour` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `other` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_products`
--

CREATE TABLE `vendor_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `date_of_order` datetime NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `payment_terms` text COLLATE utf8mb4_unicode_ci,
  `delivery_date` datetime DEFAULT NULL,
  `received_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_details` text COLLATE utf8mb4_unicode_ci,
  `recurring_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_logs`
--

CREATE TABLE `visitor_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `ip` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `browser` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visits` int(11) NOT NULL DEFAULT '1',
  `last_visit` datetime NOT NULL,
  `page_current` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chats` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reject_reason` text COLLATE utf8mb4_unicode_ci,
  `reject_count` tinyint(4) NOT NULL DEFAULT '0',
  `resubmit_count` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voucher_categories`
--

CREATE TABLE `voucher_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `website_products`
--

CREATE TABLE `website_products` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `store_website_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wetransfers`
--

CREATE TABLE `wetransfers` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_processed` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_configs`
--

CREATE TABLE `whatsapp_configs` (
  `id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_customer_support` int(11) NOT NULL,
  `frequency` int(11) NOT NULL,
  `last_online` datetime DEFAULT NULL,
  `is_connected` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `send_start` int(11) NOT NULL,
  `send_end` int(11) NOT NULL,
  `device_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `simcard_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `simcard_owner` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sim_card_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recharge_date` date DEFAULT NULL,
  `instance_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` int(11) DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '0'
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `start_meeting_url` text COLLATE utf8mb4_unicode_ci,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Indexes for table `affiliates`
--
ALTER TABLE `affiliates`
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
-- Indexes for table `barcode_media`
--
ALTER TABLE `barcode_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_id` (`media_id`),
  ADD KEY `type` (`type`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `benchmarks`
--
ALTER TABLE `benchmarks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `block_web_message_lists`
--
ALTER TABLE `block_web_message_lists`
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
  ADD KEY `bookshelves_slug_index` (`slug`(191)),
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
-- Indexes for table `category_update_users`
--
ALTER TABLE `category_update_users`
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
-- Indexes for table `chatbot_categories`
--
ALTER TABLE `chatbot_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chatbot_dialogs`
--
ALTER TABLE `chatbot_dialogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chatbot_dialog_responses`
--
ALTER TABLE `chatbot_dialog_responses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chatbot_intents_annotations`
--
ALTER TABLE `chatbot_intents_annotations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chatbot_keywords`
--
ALTER TABLE `chatbot_keywords`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chatbot_keyword_values`
--
ALTER TABLE `chatbot_keyword_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chatbot_keyword_value_types`
--
ALTER TABLE `chatbot_keyword_value_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chatbot_questions`
--
ALTER TABLE `chatbot_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chatbot_question_examples`
--
ALTER TABLE `chatbot_question_examples`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chatbot_replies`
--
ALTER TABLE `chatbot_replies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chatbot_settings`
--
ALTER TABLE `chatbot_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_bot_keyword_groups`
--
ALTER TABLE `chat_bot_keyword_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_bot_phrase_groups`
--
ALTER TABLE `chat_bot_phrase_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_messages_order_id` (`order_id`),
  ADD KEY `chat_messages_task_id_foreign` (`task_id`),
  ADD KEY `chat_messages_erp_user_foreign` (`erp_user`),
  ADD KEY `chat_messages_vendor_id_foreign` (`vendor_id`),
  ADD KEY `chat_messages_lawyer_id_foreign` (`lawyer_id`),
  ADD KEY `chat_messages_case_id_foreign` (`case_id`),
  ADD KEY `chat_messages_blogger_id_foreign` (`blogger_id`),
  ADD KEY `chat_messages_customer_id_index` (`customer_id`),
  ADD KEY `chat_messages_voucher_id_foreign` (`voucher_id`),
  ADD KEY `chat_messages_group_id_index` (`group_id`);

--
-- Indexes for table `chat_message_phrases`
--
ALTER TABLE `chat_message_phrases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_message_words`
--
ALTER TABLE `chat_message_words`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `country_duties`
--
ALTER TABLE `country_duties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `country_groups`
--
ALTER TABLE `country_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `country_group_items`
--
ALTER TABLE `country_group_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
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
  ADD PRIMARY KEY (`id`),
  ADD KEY `cropped_image_references_product_id_index` (`product_id`);

--
-- Indexes for table `crop_amends`
--
ALTER TABLE `crop_amends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_language_index` (`language`);

--
-- Indexes for table `customer_categories`
--
ALTER TABLE `customer_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_kyc_documents`
--
ALTER TABLE `customer_kyc_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_live_chats`
--
ALTER TABLE `customer_live_chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_live_chats_customer_id_index` (`customer_id`);

--
-- Indexes for table `customer_marketing_platforms`
--
ALTER TABLE `customer_marketing_platforms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_next_actions`
--
ALTER TABLE `customer_next_actions`
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
-- Indexes for table `database_historical_records`
--
ALTER TABLE `database_historical_records`
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
-- Indexes for table `developer_languages`
--
ALTER TABLE `developer_languages`
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
-- Indexes for table `developer_task_documents`
--
ALTER TABLE `developer_task_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `digital_marketing_platforms`
--
ALTER TABLE `digital_marketing_platforms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `digital_marketing_platform_components`
--
ALTER TABLE `digital_marketing_platform_components`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `digital_marketing_platform_remarks`
--
ALTER TABLE `digital_marketing_platform_remarks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `digital_marketing_solutions`
--
ALTER TABLE `digital_marketing_solutions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `digital_marketing_solution_attributes`
--
ALTER TABLE `digital_marketing_solution_attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `digital_marketing_solution_researches`
--
ALTER TABLE `digital_marketing_solution_researches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `digital_marketing_usps`
--
ALTER TABLE `digital_marketing_usps`
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
-- Indexes for table `duty_groups`
--
ALTER TABLE `duty_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `duty_group_countries`
--
ALTER TABLE `duty_group_countries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `duty_group_countries_duty_group_id_foreign` (`duty_group_id`),
  ADD KEY `duty_group_countries_country_duty_id_foreign` (`country_duty_id`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_addresses`
--
ALTER TABLE `email_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_templates_key_index` (`key`);

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
-- Indexes for table `erp_events`
--
ALTER TABLE `erp_events`
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
-- Indexes for table `erp_priorities`
--
ALTER TABLE `erp_priorities`
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
-- Indexes for table `general_categories`
--
ALTER TABLE `general_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `github_branch_states`
--
ALTER TABLE `github_branch_states`
  ADD PRIMARY KEY (`repository_id`,`branch_name`);

--
-- Indexes for table `github_groups`
--
ALTER TABLE `github_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `github_group_members`
--
ALTER TABLE `github_group_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `github_repositories`
--
ALTER TABLE `github_repositories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `github_repository_groups`
--
ALTER TABLE `github_repository_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `github_repository_users`
--
ALTER TABLE `github_repository_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `github_users`
--
ALTER TABLE `github_users`
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
-- Indexes for table `google_server`
--
ALTER TABLE `google_server`
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
  ADD PRIMARY KEY (`id`),
  ADD KEY `hash_tags_platforms_id_index` (`platforms_id`);

--
-- Indexes for table `historial_datas`
--
ALTER TABLE `historial_datas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_whatsapp_number`
--
ALTER TABLE `history_whatsapp_number`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hs_codes`
--
ALTER TABLE `hs_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hs_code_groups`
--
ALTER TABLE `hs_code_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hs_code_groups_categories_compositions`
--
ALTER TABLE `hs_code_groups_categories_compositions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hs_code_settings`
--
ALTER TABLE `hs_code_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hubstaff_activities`
--
ALTER TABLE `hubstaff_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hubstaff_activity_notifications`
--
ALTER TABLE `hubstaff_activity_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hubstaff_members`
--
ALTER TABLE `hubstaff_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hubstaff_payment_accounts`
--
ALTER TABLE `hubstaff_payment_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hubstaff_projects`
--
ALTER TABLE `hubstaff_projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hubstaff_tasks`
--
ALTER TABLE `hubstaff_tasks`
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
-- Indexes for table `instagram_comment_queues`
--
ALTER TABLE `instagram_comment_queues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `instagram_configs`
--
ALTER TABLE `instagram_configs`
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
-- Indexes for table `instruction_times`
--
ALTER TABLE `instruction_times`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
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
-- Indexes for table `landing_page_products`
--
ALTER TABLE `landing_page_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laravel_logs`
--
ALTER TABLE `laravel_logs`
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
-- Indexes for table `list_contacts`
--
ALTER TABLE `list_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `livechatinc_settings`
--
ALTER TABLE `livechatinc_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `live_chat_users`
--
ALTER TABLE `live_chat_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_excel_imports`
--
ALTER TABLE `log_excel_imports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_google_cses`
--
ALTER TABLE `log_google_cses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_google_vision`
--
ALTER TABLE `log_google_vision`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_google_vision_reference`
--
ALTER TABLE `log_google_vision_reference`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `log_google_vision_reference_type_value_unique` (`type`,`value`);

--
-- Indexes for table `log_list_magentos`
--
ALTER TABLE `log_list_magentos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_magento`
--
ALTER TABLE `log_magento`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_scraper_old`
--
ALTER TABLE `log_scraper_old`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_scraper_website_index` (`website`);

--
-- Indexes for table `log_scraper_vs_ai`
--
ALTER TABLE `log_scraper_vs_ai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_scraper_vs_ai_product_id_foreign` (`product_id`);

--
-- Indexes for table `log_tineye`
--
ALTER TABLE `log_tineye`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mailinglists`
--
ALTER TABLE `mailinglists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mailinglist_emails`
--
ALTER TABLE `mailinglist_emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mailinglist_templates`
--
ALTER TABLE `mailinglist_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mailing_remarks`
--
ALTER TABLE `mailing_remarks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mailing_template_files`
--
ALTER TABLE `mailing_template_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `marketing_message_types`
--
ALTER TABLE `marketing_message_types`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `media_disk_directory_index` (`disk`,`directory`),
  ADD KEY `media_aggregate_type_index` (`aggregate_type`);

--
-- Indexes for table `mediables`
--
ALTER TABLE `mediables`
  ADD PRIMARY KEY (`media_id`,`mediable_type`,`mediable_id`,`tag`),
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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
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
  ADD KEY `product_id` (`product_id`);

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
-- Indexes for table `page_instructions`
--
ALTER TABLE `page_instructions`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `permissions_route_index` (`route`);

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
-- Indexes for table `platforms`
--
ALTER TABLE `platforms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
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
-- Indexes for table `price_overrides`
--
ALTER TABLE `price_overrides`
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
  ADD KEY `products_supplier_index` (`supplier`(191)),
  ADD KEY `products_is_on_sale_index` (`is_on_sale`),
  ADD KEY `products_listing_approved_at_index` (`listing_approved_at`),
  ADD KEY `products_status_id_foreign` (`status_id`),
  ADD KEY `stock` (`stock`),
  ADD KEY `fk_index_created_at` (`created_at`),
  ADD KEY `deleted_at` (`deleted_at`),
  ADD KEY `supplier` (`supplier`(191)),
  ADD KEY `sku` (`sku`);

--
-- Indexes for table `products_new`
--
ALTER TABLE `products_new`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_brand_index` (`brand`),
  ADD KEY `products_supplier_index` (`supplier`(250)),
  ADD KEY `products_is_on_sale_index` (`is_on_sale`),
  ADD KEY `products_listing_approved_at_index` (`listing_approved_at`),
  ADD KEY `products_status_id_foreign` (`status_id`),
  ADD KEY `stock` (`stock`),
  ADD KEY `fk_index_created_at` (`created_at`),
  ADD KEY `deleted_at` (`deleted_at`);

--
-- Indexes for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_category_histories`
--
ALTER TABLE `product_category_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_color_histories`
--
ALTER TABLE `product_color_histories`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `product_quickshell_groups`
--
ALTER TABLE `product_quickshell_groups`
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
-- Indexes for table `product_translations`
--
ALTER TABLE `product_translations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_verifying_users`
--
ALTER TABLE `product_verifying_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proxies`
--
ALTER TABLE `proxies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `public_keys`
--
ALTER TABLE `public_keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
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
-- Indexes for table `return_exchanges`
--
ALTER TABLE `return_exchanges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `return_exchanges_customer_id_index` (`customer_id`);

--
-- Indexes for table `return_exchange_histories`
--
ALTER TABLE `return_exchange_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `return_exchange_histories_return_exchange_id_index` (`return_exchange_id`),
  ADD KEY `return_exchange_histories_user_id_index` (`user_id`);

--
-- Indexes for table `return_exchange_products`
--
ALTER TABLE `return_exchange_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `return_exchange_products_return_exchange_id_index` (`return_exchange_id`),
  ADD KEY `return_exchange_products_status_id_index` (`status_id`);

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
  ADD KEY `scraped_products_is_excel_index` (`is_excel`),
  ADD KEY `scraped_products_website_at_index` (`website`),
  ADD KEY `scraped_products_product_id_index` (`product_id`);

--
-- Indexes for table `scrapers`
--
ALTER TABLE `scrapers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scraper_mappings`
--
ALTER TABLE `scraper_mappings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scraper_results`
--
ALTER TABLE `scraper_results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scraper_server_histories`
--
ALTER TABLE `scraper_server_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scrape_queues`
--
ALTER TABLE `scrape_queues`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `scrap_histories`
--
ALTER TABLE `scrap_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scrap_influencers`
--
ALTER TABLE `scrap_influencers`
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
-- Indexes for table `search_queues`
--
ALTER TABLE `search_queues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `search_terms`
--
ALTER TABLE `search_terms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `search_terms_entity_type_entity_id_index` (`entity_type`,`entity_id`),
  ADD KEY `search_terms_term_index` (`term`(191)),
  ADD KEY `search_terms_entity_type_index` (`entity_type`),
  ADD KEY `search_terms_score_index` (`score`);

--
-- Indexes for table `seo_analytics`
--
ALTER TABLE `seo_analytics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `simply_duty_calculations`
--
ALTER TABLE `simply_duty_calculations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `simply_duty_categories`
--
ALTER TABLE `simply_duty_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `simply_duty_countries`
--
ALTER TABLE `simply_duty_countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `simply_duty_currencies`
--
ALTER TABLE `simply_duty_currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sitejabber_q_a_s`
--
ALTER TABLE `sitejabber_q_a_s`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_developments`
--
ALTER TABLE `site_developments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_development_categories`
--
ALTER TABLE `site_development_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_development_hidden_categories`
--
ALTER TABLE `site_development_hidden_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_development_statuses`
--
ALTER TABLE `site_development_statuses`
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
-- Indexes for table `store_websites`
--
ALTER TABLE `store_websites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`),
  ADD KEY `is_published` (`is_published`);

--
-- Indexes for table `store_website_brands`
--
ALTER TABLE `store_website_brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_website_categories`
--
ALTER TABLE `store_website_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_website_goals`
--
ALTER TABLE `store_website_goals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_website_goal_remarks`
--
ALTER TABLE `store_website_goal_remarks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_website_product_attributes`
--
ALTER TABLE `store_website_product_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_website_product_attributes_product_id_foreign` (`product_id`),
  ADD KEY `store_website_product_attributes_store_website_id_foreign` (`store_website_id`);

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
  ADD KEY `suppliers_language_index` (`language`);

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
-- Indexes for table `translated_products`
--
ALTER TABLE `translated_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

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
-- Indexes for table `user_bank_informations`
--
ALTER TABLE `user_bank_informations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_customers`
--
ALTER TABLE `user_customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_events`
--
ALTER TABLE `user_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_event_attendees`
--
ALTER TABLE `user_event_attendees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_event_participants`
--
ALTER TABLE `user_event_participants`
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
-- Indexes for table `user_rates`
--
ALTER TABLE `user_rates`
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
-- Indexes for table `visitor_logs`
--
ALTER TABLE `visitor_logs`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `website_products`
--
ALTER TABLE `website_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wetransfers`
--
ALTER TABLE `wetransfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `whatsapp_configs`
--
ALTER TABLE `whatsapp_configs`
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=957;
--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=473;
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
-- AUTO_INCREMENT for table `affiliates`
--
ALTER TABLE `affiliates`
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `assets_manager`
--
ALTER TABLE `assets_manager`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
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
-- AUTO_INCREMENT for table `barcode_media`
--
ALTER TABLE `barcode_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=510;
--
-- AUTO_INCREMENT for table `benchmarks`
--
ALTER TABLE `benchmarks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `block_web_message_lists`
--
ALTER TABLE `block_web_message_lists`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `bloggers`
--
ALTER TABLE `bloggers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `bookshelves`
--
ALTER TABLE `bookshelves`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `book_activities`
--
ALTER TABLE `book_activities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `book_comments`
--
ALTER TABLE `book_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9070;
--
-- AUTO_INCREMENT for table `brand_category_price_range`
--
ALTER TABLE `brand_category_price_range`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;
--
-- AUTO_INCREMENT for table `category_update_users`
--
ALTER TABLE `category_update_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `chapters`
--
ALTER TABLE `chapters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `chatbot_categories`
--
ALTER TABLE `chatbot_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `chatbot_dialogs`
--
ALTER TABLE `chatbot_dialogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=260;
--
-- AUTO_INCREMENT for table `chatbot_dialog_responses`
--
ALTER TABLE `chatbot_dialog_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;
--
-- AUTO_INCREMENT for table `chatbot_intents_annotations`
--
ALTER TABLE `chatbot_intents_annotations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `chatbot_keywords`
--
ALTER TABLE `chatbot_keywords`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `chatbot_keyword_values`
--
ALTER TABLE `chatbot_keyword_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8382;
--
-- AUTO_INCREMENT for table `chatbot_keyword_value_types`
--
ALTER TABLE `chatbot_keyword_value_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `chatbot_questions`
--
ALTER TABLE `chatbot_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
--
-- AUTO_INCREMENT for table `chatbot_question_examples`
--
ALTER TABLE `chatbot_question_examples`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=997;
--
-- AUTO_INCREMENT for table `chatbot_replies`
--
ALTER TABLE `chatbot_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;
--
-- AUTO_INCREMENT for table `chatbot_settings`
--
ALTER TABLE `chatbot_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `chat_bot_keyword_groups`
--
ALTER TABLE `chat_bot_keyword_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `chat_bot_phrase_groups`
--
ALTER TABLE `chat_bot_phrase_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1013880;
--
-- AUTO_INCREMENT for table `chat_message_phrases`
--
ALTER TABLE `chat_message_phrases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15009;
--
-- AUTO_INCREMENT for table `chat_message_words`
--
ALTER TABLE `chat_message_words`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1618;
--
-- AUTO_INCREMENT for table `cold_leads`
--
ALTER TABLE `cold_leads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35355;
--
-- AUTO_INCREMENT for table `cold_lead_broadcasts`
--
ALTER TABLE `cold_lead_broadcasts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `color_names_references`
--
ALTER TABLE `color_names_references`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1567;
--
-- AUTO_INCREMENT for table `color_references`
--
ALTER TABLE `color_references`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `contact_bloggers`
--
ALTER TABLE `contact_bloggers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `country_duties`
--
ALTER TABLE `country_duties`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `country_groups`
--
ALTER TABLE `country_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `country_group_items`
--
ALTER TABLE `country_group_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `courier`
--
ALTER TABLE `courier`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `cron_jobs`
--
ALTER TABLE `cron_jobs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;
--
-- AUTO_INCREMENT for table `cron_job_reports`
--
ALTER TABLE `cron_job_reports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1055030;
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3004;
--
-- AUTO_INCREMENT for table `customer_categories`
--
ALTER TABLE `customer_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer_kyc_documents`
--
ALTER TABLE `customer_kyc_documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `customer_live_chats`
--
ALTER TABLE `customer_live_chats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer_marketing_platforms`
--
ALTER TABLE `customer_marketing_platforms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `customer_next_actions`
--
ALTER TABLE `customer_next_actions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `daily_activities`
--
ALTER TABLE `daily_activities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16082;
--
-- AUTO_INCREMENT for table `daily_cash_flows`
--
ALTER TABLE `daily_cash_flows`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `database_historical_records`
--
ALTER TABLE `database_historical_records`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
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
-- AUTO_INCREMENT for table `developer_languages`
--
ALTER TABLE `developer_languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `developer_messages_alert_schedules`
--
ALTER TABLE `developer_messages_alert_schedules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `developer_modules`
--
ALTER TABLE `developer_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;
--
-- AUTO_INCREMENT for table `developer_tasks`
--
ALTER TABLE `developer_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2097;
--
-- AUTO_INCREMENT for table `developer_task_comments`
--
ALTER TABLE `developer_task_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `developer_task_documents`
--
ALTER TABLE `developer_task_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `digital_marketing_platforms`
--
ALTER TABLE `digital_marketing_platforms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `digital_marketing_platform_components`
--
ALTER TABLE `digital_marketing_platform_components`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `digital_marketing_platform_remarks`
--
ALTER TABLE `digital_marketing_platform_remarks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `digital_marketing_solutions`
--
ALTER TABLE `digital_marketing_solutions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `digital_marketing_solution_attributes`
--
ALTER TABLE `digital_marketing_solution_attributes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `digital_marketing_solution_researches`
--
ALTER TABLE `digital_marketing_solution_researches`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `digital_marketing_usps`
--
ALTER TABLE `digital_marketing_usps`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
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
-- AUTO_INCREMENT for table `duty_groups`
--
ALTER TABLE `duty_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `duty_group_countries`
--
ALTER TABLE `duty_group_countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `email_addresses`
--
ALTER TABLE `email_addresses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `entity_permissions`
--
ALTER TABLE `entity_permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `erp_accounts`
--
ALTER TABLE `erp_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `erp_events`
--
ALTER TABLE `erp_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `erp_leads`
--
ALTER TABLE `erp_leads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;
--
-- AUTO_INCREMENT for table `erp_lead_status`
--
ALTER TABLE `erp_lead_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `erp_priorities`
--
ALTER TABLE `erp_priorities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
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
-- AUTO_INCREMENT for table `general_categories`
--
ALTER TABLE `general_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `github_group_members`
--
ALTER TABLE `github_group_members`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `github_repository_groups`
--
ALTER TABLE `github_repository_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `github_repository_users`
--
ALTER TABLE `github_repository_users`
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
-- AUTO_INCREMENT for table `google_server`
--
ALTER TABLE `google_server`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `historial_datas`
--
ALTER TABLE `historial_datas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `history_whatsapp_number`
--
ALTER TABLE `history_whatsapp_number`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `hs_codes`
--
ALTER TABLE `hs_codes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hs_code_groups`
--
ALTER TABLE `hs_code_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `hs_code_groups_categories_compositions`
--
ALTER TABLE `hs_code_groups_categories_compositions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hs_code_settings`
--
ALTER TABLE `hs_code_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `hubstaff_activity_notifications`
--
ALTER TABLE `hubstaff_activity_notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `hubstaff_members`
--
ALTER TABLE `hubstaff_members`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT for table `hubstaff_payment_accounts`
--
ALTER TABLE `hubstaff_payment_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hubstaff_projects`
--
ALTER TABLE `hubstaff_projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hubstaff_tasks`
--
ALTER TABLE `hubstaff_tasks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=228;
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
-- AUTO_INCREMENT for table `instagram_comment_queues`
--
ALTER TABLE `instagram_comment_queues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instagram_configs`
--
ALTER TABLE `instagram_configs`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;
--
-- AUTO_INCREMENT for table `instruction_categories`
--
ALTER TABLE `instruction_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instruction_times`
--
ALTER TABLE `instruction_times`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `joint_permissions`
--
ALTER TABLE `joint_permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1401;
--
-- AUTO_INCREMENT for table `keywords`
--
ALTER TABLE `keywords`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `keyword_instructions`
--
ALTER TABLE `keyword_instructions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `keyword_to_categories`
--
ALTER TABLE `keyword_to_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `landing_page_products`
--
ALTER TABLE `landing_page_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `laravel_logs`
--
ALTER TABLE `laravel_logs`
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7712;
--
-- AUTO_INCREMENT for table `links_to_posts`
--
ALTER TABLE `links_to_posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `listing_histories`
--
ALTER TABLE `listing_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;
--
-- AUTO_INCREMENT for table `listing_payments`
--
ALTER TABLE `listing_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `list_contacts`
--
ALTER TABLE `list_contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `livechatinc_settings`
--
ALTER TABLE `livechatinc_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `live_chat_users`
--
ALTER TABLE `live_chat_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `log_excel_imports`
--
ALTER TABLE `log_excel_imports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `log_google_cses`
--
ALTER TABLE `log_google_cses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `log_google_vision`
--
ALTER TABLE `log_google_vision`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `log_google_vision_reference`
--
ALTER TABLE `log_google_vision_reference`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;
--
-- AUTO_INCREMENT for table `log_list_magentos`
--
ALTER TABLE `log_list_magentos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=922;
--
-- AUTO_INCREMENT for table `log_magento`
--
ALTER TABLE `log_magento`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `log_scraper_old`
--
ALTER TABLE `log_scraper_old`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=883764;
--
-- AUTO_INCREMENT for table `log_scraper_vs_ai`
--
ALTER TABLE `log_scraper_vs_ai`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `log_tineye`
--
ALTER TABLE `log_tineye`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mailinglists`
--
ALTER TABLE `mailinglists`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `mailinglist_emails`
--
ALTER TABLE `mailinglist_emails`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `mailinglist_templates`
--
ALTER TABLE `mailinglist_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `mailing_remarks`
--
ALTER TABLE `mailing_remarks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mailing_template_files`
--
ALTER TABLE `mailing_template_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `marketing_message_types`
--
ALTER TABLE `marketing_message_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `marketing_platforms`
--
ALTER TABLE `marketing_platforms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1537930;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51229;
--
-- AUTO_INCREMENT for table `messsage_applications`
--
ALTER TABLE `messsage_applications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=917;
--
-- AUTO_INCREMENT for table `monetary_accounts`
--
ALTER TABLE `monetary_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `negative_reviews`
--
ALTER TABLE `negative_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `notification_queues`
--
ALTER TABLE `notification_queues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2020;
--
-- AUTO_INCREMENT for table `order_products`
--
ALTER TABLE `order_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT for table `order_reports`
--
ALTER TABLE `order_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `order_statuses`
--
ALTER TABLE `order_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `page_instructions`
--
ALTER TABLE `page_instructions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `page_notes`
--
ALTER TABLE `page_notes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `page_notes_categories`
--
ALTER TABLE `page_notes_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
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
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;
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
-- AUTO_INCREMENT for table `platforms`
--
ALTER TABLE `platforms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
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
-- AUTO_INCREMENT for table `price_overrides`
--
ALTER TABLE `price_overrides`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=296560;
--
-- AUTO_INCREMENT for table `product_attributes`
--
ALTER TABLE `product_attributes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT for table `product_category_histories`
--
ALTER TABLE `product_category_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `product_color_histories`
--
ALTER TABLE `product_color_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `product_dispatch`
--
ALTER TABLE `product_dispatch`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `product_location`
--
ALTER TABLE `product_location`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `product_location_history`
--
ALTER TABLE `product_location_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `product_quicksell_groups`
--
ALTER TABLE `product_quicksell_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `product_quickshell_groups`
--
ALTER TABLE `product_quickshell_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `product_references`
--
ALTER TABLE `product_references`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;
--
-- AUTO_INCREMENT for table `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `product_status`
--
ALTER TABLE `product_status`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116774;
--
-- AUTO_INCREMENT for table `product_suppliers`
--
ALTER TABLE `product_suppliers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81978;
--
-- AUTO_INCREMENT for table `product_templates`
--
ALTER TABLE `product_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `product_translations`
--
ALTER TABLE `product_translations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `product_verifying_users`
--
ALTER TABLE `product_verifying_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;
--
-- AUTO_INCREMENT for table `proxies`
--
ALTER TABLE `proxies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `public_keys`
--
ALTER TABLE `public_keys`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `push_notifications`
--
ALTER TABLE `push_notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `quick_replies`
--
ALTER TABLE `quick_replies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `quick_sell_groups`
--
ALTER TABLE `quick_sell_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `rejected_leads`
--
ALTER TABLE `rejected_leads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `remarks`
--
ALTER TABLE `remarks`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `reply_categories`
--
ALTER TABLE `reply_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
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
-- AUTO_INCREMENT for table `return_exchanges`
--
ALTER TABLE `return_exchanges`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `return_exchange_histories`
--
ALTER TABLE `return_exchange_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `return_exchange_products`
--
ALTER TABLE `return_exchange_products`
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `rude_words`
--
ALTER TABLE `rude_words`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268807;
--
-- AUTO_INCREMENT for table `scrapers`
--
ALTER TABLE `scrapers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT for table `scraper_mappings`
--
ALTER TABLE `scraper_mappings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `scraper_results`
--
ALTER TABLE `scraper_results`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `scraper_server_histories`
--
ALTER TABLE `scraper_server_histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `scrape_queues`
--
ALTER TABLE `scrape_queues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `scrap_activities`
--
ALTER TABLE `scrap_activities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `scrap_counts`
--
ALTER TABLE `scrap_counts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `scrap_entries`
--
ALTER TABLE `scrap_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;
--
-- AUTO_INCREMENT for table `scrap_histories`
--
ALTER TABLE `scrap_histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `scrap_influencers`
--
ALTER TABLE `scrap_influencers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `scrap_remarks`
--
ALTER TABLE `scrap_remarks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;
--
-- AUTO_INCREMENT for table `scrap_statistics`
--
ALTER TABLE `scrap_statistics`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT for table `search_queues`
--
ALTER TABLE `search_queues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `search_terms`
--
ALTER TABLE `search_terms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT for table `seo_analytics`
--
ALTER TABLE `seo_analytics`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `simply_duty_calculations`
--
ALTER TABLE `simply_duty_calculations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `simply_duty_categories`
--
ALTER TABLE `simply_duty_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;
--
-- AUTO_INCREMENT for table `simply_duty_countries`
--
ALTER TABLE `simply_duty_countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;
--
-- AUTO_INCREMENT for table `simply_duty_currencies`
--
ALTER TABLE `simply_duty_currencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;
--
-- AUTO_INCREMENT for table `sitejabber_q_a_s`
--
ALTER TABLE `sitejabber_q_a_s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `site_developments`
--
ALTER TABLE `site_developments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `site_development_categories`
--
ALTER TABLE `site_development_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `site_development_hidden_categories`
--
ALTER TABLE `site_development_hidden_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `site_development_statuses`
--
ALTER TABLE `site_development_statuses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `sku_color_references`
--
ALTER TABLE `sku_color_references`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `sku_formats`
--
ALTER TABLE `sku_formats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `social_tags`
--
ALTER TABLE `social_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sops`
--
ALTER TABLE `sops`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;
--
-- AUTO_INCREMENT for table `status_changes`
--
ALTER TABLE `status_changes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
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
-- AUTO_INCREMENT for table `store_websites`
--
ALTER TABLE `store_websites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `store_website_brands`
--
ALTER TABLE `store_website_brands`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `store_website_categories`
--
ALTER TABLE `store_website_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;
--
-- AUTO_INCREMENT for table `store_website_goals`
--
ALTER TABLE `store_website_goals`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `store_website_goal_remarks`
--
ALTER TABLE `store_website_goal_remarks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `store_website_product_attributes`
--
ALTER TABLE `store_website_product_attributes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `suggestions`
--
ALTER TABLE `suggestions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `suggestion_products`
--
ALTER TABLE `suggestion_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=545;
--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4661;
--
-- AUTO_INCREMENT for table `supplier_brand_counts`
--
ALTER TABLE `supplier_brand_counts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `supplier_brand_count_histories`
--
ALTER TABLE `supplier_brand_count_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `supplier_category`
--
ALTER TABLE `supplier_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `supplier_category_counts`
--
ALTER TABLE `supplier_category_counts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `supplier_inventory`
--
ALTER TABLE `supplier_inventory`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `supplier_status`
--
ALTER TABLE `supplier_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `task_types`
--
ALTER TABLE `task_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `task_users`
--
ALTER TABLE `task_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `translated_products`
--
ALTER TABLE `translated_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT for table `users_auto_comment_histories`
--
ALTER TABLE `users_auto_comment_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_actions`
--
ALTER TABLE `user_actions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;
--
-- AUTO_INCREMENT for table `user_bank_informations`
--
ALTER TABLE `user_bank_informations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_customers`
--
ALTER TABLE `user_customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_events`
--
ALTER TABLE `user_events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `user_event_attendees`
--
ALTER TABLE `user_event_attendees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `user_event_participants`
--
ALTER TABLE `user_event_participants`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_logins`
--
ALTER TABLE `user_logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13264;
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `user_rates`
--
ALTER TABLE `user_rates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `vendor_categories`
--
ALTER TABLE `vendor_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `vendor_products`
--
ALTER TABLE `vendor_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `views`
--
ALTER TABLE `views`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `visitor_logs`
--
ALTER TABLE `visitor_logs`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `website_products`
--
ALTER TABLE `website_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `wetransfers`
--
ALTER TABLE `wetransfers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `whatsapp_configs`
--
ALTER TABLE `whatsapp_configs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `whats_app_groups`
--
ALTER TABLE `whats_app_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `whats_app_group_numbers`
--
ALTER TABLE `whats_app_group_numbers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `zoom_meetings`
--
ALTER TABLE `zoom_meetings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `blogger_payments`
--
ALTER TABLE `blogger_payments`
  ADD CONSTRAINT `blogger_payments_blogger_id_foreign` FOREIGN KEY (`blogger_id`) REFERENCES `bloggers` (`id`);

--
-- Constraints for table `blogger_products`
--
ALTER TABLE `blogger_products`
  ADD CONSTRAINT `blogger_products_blogger_id_foreign` FOREIGN KEY (`blogger_id`) REFERENCES `bloggers` (`id`),
  ADD CONSTRAINT `blogger_products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);

--
-- Constraints for table `blogger_product_images`
--
ALTER TABLE `blogger_product_images`
  ADD CONSTRAINT `blogger_product_images_blogger_product_id_foreign` FOREIGN KEY (`blogger_product_id`) REFERENCES `blogger_products` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `bookshelves_books`
--
ALTER TABLE `bookshelves_books`
  ADD CONSTRAINT `bookshelves_books_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bookshelves_books_bookshelf_id_foreign` FOREIGN KEY (`bookshelf_id`) REFERENCES `bookshelves` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `call_recordings`
--
ALTER TABLE `call_recordings`
  ADD CONSTRAINT `call_recordings_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cases`
--
ALTER TABLE `cases`
  ADD CONSTRAINT `cases_lawyer_id_foreign` FOREIGN KEY (`lawyer_id`) REFERENCES `lawyers` (`id`);

--
-- Constraints for table `case_costs`
--
ALTER TABLE `case_costs`
  ADD CONSTRAINT `case_costs_case_id_foreign` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `case_receivables`
--
ALTER TABLE `case_receivables`
  ADD CONSTRAINT `case_receivables_case_id_foreign` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`);

--
-- Constraints for table `cash_flows`
--
ALTER TABLE `cash_flows`
  ADD CONSTRAINT `cash_flows_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cash_flows_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_blogger_id_foreign` FOREIGN KEY (`blogger_id`) REFERENCES `bloggers` (`id`),
  ADD CONSTRAINT `chat_messages_case_id_foreign` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`),
  ADD CONSTRAINT `chat_messages_erp_user_foreign` FOREIGN KEY (`erp_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `chat_messages_lawyer_id_foreign` FOREIGN KEY (`lawyer_id`) REFERENCES `lawyers` (`id`),
  ADD CONSTRAINT `chat_messages_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chat_messages_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`),
  ADD CONSTRAINT `chat_messages_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`),
  ADD CONSTRAINT `chat_messages_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`);

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `complaint_threads`
--
ALTER TABLE `complaint_threads`
  ADD CONSTRAINT `complaint_threads_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `complaint_threads_complaint_id_foreign` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`);

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `delivery_approvals`
--
ALTER TABLE `delivery_approvals`
  ADD CONSTRAINT `delivery_approvals_assigned_user_id_foreign` FOREIGN KEY (`assigned_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `delivery_approvals_private_view_id_foreign` FOREIGN KEY (`private_view_id`) REFERENCES `private_views` (`id`);

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `erp_leads`
--
ALTER TABLE `erp_leads`
  ADD CONSTRAINT `erp_leads_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `erp_leads_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `erp_leads_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `erp_leads_lead_status_id_foreign` FOREIGN KEY (`lead_status_id`) REFERENCES `erp_lead_status` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `erp_leads_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lawyers`
--
ALTER TABLE `lawyers`
  ADD CONSTRAINT `lawyers_speciality_id_foreign` FOREIGN KEY (`speciality_id`) REFERENCES `lawyer_specialities` (`id`);

--
-- Constraints for table `log_scraper_vs_ai`
--
ALTER TABLE `log_scraper_vs_ai`
  ADD CONSTRAINT `log_scraper_vs_ai_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `mediables`
--
ALTER TABLE `mediables`
  ADD CONSTRAINT `mediables_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `message_queues`
--
ALTER TABLE `message_queues`
  ADD CONSTRAINT `message_queues_chat_message_id_foreign` FOREIGN KEY (`chat_message_id`) REFERENCES `chat_messages` (`id`);

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `page_notes`
--
ALTER TABLE `page_notes`
  ADD CONSTRAINT `page_notes_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `page_notes_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_user`
--
ALTER TABLE `permission_user`
  ADD CONSTRAINT `permission_user_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pinterest_boards`
--
ALTER TABLE `pinterest_boards`
  ADD CONSTRAINT `pinterest_boards_pinterest_users_id_foreign` FOREIGN KEY (`pinterest_users_id`) REFERENCES `pinterest_users` (`id`);

--
-- Constraints for table `private_views`
--
ALTER TABLE `private_views`
  ADD CONSTRAINT `private_views_assigned_user_id_foreign` FOREIGN KEY (`assigned_user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`);

--
-- Constraints for table `product_dispatch`
--
ALTER TABLE `product_dispatch`
  ADD CONSTRAINT `product_dispatch_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_dispatch_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_location_history`
--
ALTER TABLE `product_location_history`
  ADD CONSTRAINT `product_location_history_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_location_history_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_references`
--
ALTER TABLE `product_references`
  ADD CONSTRAINT `product_references_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_purchase_status_id_foreign` FOREIGN KEY (`purchase_status_id`) REFERENCES `purchase_status` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_discounts`
--
ALTER TABLE `purchase_discounts`
  ADD CONSTRAINT `purchase_discounts_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`);

--
-- Constraints for table `purchase_order_customer`
--
ALTER TABLE `purchase_order_customer`
  ADD CONSTRAINT `purchase_order_customer_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_order_customer_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `reviews_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `review_schedules`
--
ALTER TABLE `review_schedules`
  ADD CONSTRAINT `review_schedules_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `review_schedules_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `scheduled_messages`
--
ALTER TABLE `scheduled_messages`
  ADD CONSTRAINT `scheduled_messages_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `scheduled_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `status_changes`
--
ALTER TABLE `status_changes`
  ADD CONSTRAINT `status_changes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `suggestions`
--
ALTER TABLE `suggestions`
  ADD CONSTRAINT `suggestions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `suggestion_products`
--
ALTER TABLE `suggestion_products`
  ADD CONSTRAINT `suggestion_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `suggestion_products_suggestion_id_foreign` FOREIGN KEY (`suggestion_id`) REFERENCES `suggestions` (`id`);

--
-- Constraints for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `suppliers_supplier_category_id_foreign` FOREIGN KEY (`supplier_category_id`) REFERENCES `supplier_category` (`id`),
  ADD CONSTRAINT `suppliers_supplier_status_id_foreign` FOREIGN KEY (`supplier_status_id`) REFERENCES `supplier_status` (`id`);

--
-- Constraints for table `task_users`
--
ALTER TABLE `task_users`
  ADD CONSTRAINT `task_users_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`);

--
-- Constraints for table `user_manual_crop`
--
ALTER TABLE `user_manual_crop`
  ADD CONSTRAINT `user_manual_crop_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `user_manual_crop_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_products`
--
ALTER TABLE `user_products`
  ADD CONSTRAINT `user_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `user_products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  ADD CONSTRAINT `vendor_payments_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `vendor_products`
--
ALTER TABLE `vendor_products`
  ADD CONSTRAINT `vendor_products_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD CONSTRAINT `vouchers_delivery_approval_id_foreign` FOREIGN KEY (`delivery_approval_id`) REFERENCES `delivery_approvals` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
