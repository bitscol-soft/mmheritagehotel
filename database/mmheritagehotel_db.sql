-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 29, 2024 at 07:47 PM
-- Server version: 10.5.24-MariaDB
-- PHP Version: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mmheritagehotel_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_sections`
--

CREATE TABLE `about_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `about_heading` text DEFAULT NULL,
  `about_description` longtext DEFAULT NULL,
  `first_image` varchar(191) DEFAULT NULL,
  `second_image` varchar(191) DEFAULT NULL,
  `offer_title` text DEFAULT NULL,
  `offer_description` longtext DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `about_sections`
--

INSERT INTO `about_sections` (`id`, `about_heading`, `about_description`, `first_image`, `second_image`, `offer_title`, `offer_description`, `company_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'MM Heritage Hotel', 'Experience unparalleled luxury at MM Heritage Hotel Melaka in the heart of Malaysia’s Heritage capital. Surrounded by vibrant multicultural energy, our Hotel takes centre stage with its unique blend of panache and sophistication, combining extraordinary dining experiences, sleek and spacious accommodations, and legendary MM Heritage Hotel service. Our Hotel exudes success and style in the city, embodying the essence of upscale urban living in Melaka.', './assets/uploads/hotel/website/2024/Feb//1707045414.webp', './assets/uploads/hotel/website/2021/Dec//1640846232.webp', 'You\'ll Love All The Amenities We Offer!', 'Lorem ipsum dolor sit amet, ut magna aliqua.', 1, 2, NULL, '2024-02-01 17:51:29', '2024-02-05 02:16:44');

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `account_group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_control_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_subsidiary_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT 1,
  `balance_type` enum('Debit','Credit') DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `remarks` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `is_deletable` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `account_group_id`, `account_control_id`, `account_subsidiary_id`, `company_id`, `balance_type`, `opening_balance`, `remarks`, `status`, `is_deletable`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Purchase Return', 8, 24, 36, 1, 'Credit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:56', '2021-10-26 14:53:56'),
(2, 'Sales Return', 7, 23, 35, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:56', '2021-10-26 14:53:56'),
(4, 'Purchase', 6, 24, 36, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:57', '2021-10-26 15:04:03'),
(10, 'Bank Interest', 5, 19, 31, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:57', '2021-10-26 14:53:57'),
(12, 'Remuneration (owner)', 5, 17, 29, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:57', '2021-10-26 14:53:57'),
(14, 'Staff Salary', 5, 16, 28, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:57', '2021-10-26 14:53:57'),
(15, 'Conveyance Expense (All)', 5, 15, 27, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:58', '2021-10-26 14:53:58'),
(17, 'House Rent (Main Office)', 5, 14, 26, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:58', '2021-10-26 14:53:58'),
(18, 'Electricity Bill', 5, 13, 25, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:58', '2021-10-26 14:53:58'),
(21, 'Adjsutment Expense', 5, 12, 24, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:58', '2021-10-26 14:53:58'),
(22, 'Entertainment Expense', 5, 11, 23, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:58', '2021-10-26 14:53:58'),
(23, 'Service Revenue', 4, 10, 22, 1, 'Credit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:58', '2021-10-26 14:53:58'),
(25, 'Adjustment Revenue', 4, 8, 20, 1, 'Credit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:58', '2021-10-26 14:53:58'),
(26, 'Sales', 4, 7, 19, 1, 'Credit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:58', '2021-10-26 14:53:58'),
(27, 'Current Income', 3, 6, 18, 1, 'Credit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:58', '2021-10-26 14:53:58'),
(30, 'Capital', 3, 5, 17, 1, 'Credit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:58', '2021-10-26 14:53:58'),
(32, 'Loan', 2, 3, 3, 1, 'Credit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:58', '2021-11-04 17:05:04'),
(35, 'Accounts Payable Suppliers', 2, 3, 4, 1, 'Credit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:59', '2021-10-26 14:53:59'),
(40, 'Glass Decoration', 1, 2, 6, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:59', '2021-10-26 14:53:59'),
(42, 'Chairs', 1, 2, 7, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:59', '2021-10-26 14:53:59'),
(44, 'CEO Tables', 1, 2, 7, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:59', '2021-10-26 14:53:59'),
(45, 'Tables', 1, 2, 7, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:59', '2021-10-26 14:53:59'),
(47, 'A/C Receivables Customers', 1, 1, 8, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:59', '2021-10-26 14:53:59'),
(48, 'Bad Stock', 1, 1, 9, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:59', '2021-10-26 14:53:59'),
(50, 'Current Stock', 1, 1, 9, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:59', '2021-10-26 14:53:59'),
(52, 'Bank Name', 1, 1, 10, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:59', '2021-11-04 16:59:48'),
(54, 'Petty Cash', 1, 1, 11, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:59', '2021-10-26 14:53:59'),
(55, 'Cash', 1, 1, 11, 1, 'Credit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:59', '2021-10-26 14:53:59'),
(56, 'Cheque In Hand', 1, 1, 39, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:53:59', '2021-11-04 17:02:40'),
(58, 'Office Expenses', 5, 18, 30, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:54:00', '2021-10-26 14:54:00'),
(61, 'Credit Card', 1, 1, 39, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:54:00', '2021-11-04 17:02:48'),
(68, 'SHOP RENT', 5, 14, 26, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2021-10-26 14:54:00', '2021-10-26 14:54:00'),
(84, 'Dept Account1', 9, 27, 37, 1, 'Debit', 0.00, NULL, 1, 1, 1, 1, '2021-11-03 10:25:25', '2021-11-03 10:25:25'),
(85, 'Acm Dpt Account 1', 10, 28, 38, 1, 'Credit', 0.00, NULL, 1, 1, 1, 1, '2021-11-03 10:25:53', '2021-11-03 10:25:53'),
(1000, 'Booking', 1, 1, 8, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2023-01-09 14:53:56', '2023-01-10 14:53:56'),
(1001, 'Hotel Service Sale', 1, 1, 8, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2023-01-09 14:53:56', '2023-01-10 14:53:56'),
(1002, 'Restaurant Sale', 1, 1, 8, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2023-01-09 14:53:57', '2023-01-10 15:04:03'),
(1003, 'Bar Sale', 1, 1, 8, 1, 'Debit', 0.00, NULL, 1, 0, 1, 1, '2023-01-09 14:53:57', '2023-01-10 14:53:57');

-- --------------------------------------------------------

--
-- Table structure for table `account_controls`
--

CREATE TABLE `account_controls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `account_group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT 1,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `is_deletable` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_controls`
--

INSERT INTO `account_controls` (`id`, `name`, `account_group_id`, `company_id`, `status`, `is_deletable`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Current Asset', 1, 1, 1, 0, 1, 1, '2021-10-26 14:53:53', '2021-10-26 14:53:53'),
(2, 'Fixed Asset', 1, 1, 1, 0, 1, 1, '2021-10-26 14:53:53', '2021-10-26 14:53:53'),
(3, 'Short Term Liabilities', 2, 1, 1, 0, 1, 1, '2021-10-26 14:53:53', '2021-10-26 14:53:53'),
(4, 'Long Term liabilities', 2, 1, 1, 0, 1, 1, '2021-10-26 14:53:53', '2021-10-26 14:53:53'),
(5, 'Capital', 3, 1, 1, 0, 1, 1, '2021-10-26 14:53:53', '2021-10-26 14:53:53'),
(6, 'Retained Earning', 3, 1, 1, 0, 1, 1, '2021-10-26 14:53:54', '2021-10-26 14:53:54'),
(7, 'Sales', 4, 1, 1, 0, 1, 1, '2021-10-26 14:53:54', '2021-10-26 14:53:54'),
(8, 'Adjustment Revenue', 4, 1, 1, 0, 1, 1, '2021-10-26 14:53:54', '2021-10-26 14:53:54'),
(9, 'Other Revenue', 4, 1, 1, 0, 1, 1, '2021-10-26 14:53:54', '2021-10-26 14:53:54'),
(10, 'Service Revenue', 4, 1, 1, 0, 1, 1, '2021-10-26 14:53:54', '2021-10-26 14:53:54'),
(11, 'Entertainment Expense', 5, 1, 1, 0, 1, 1, '2021-10-26 14:53:54', '2021-10-26 14:53:54'),
(12, 'Adjustment Expense', 5, 1, 1, 0, 1, 1, '2021-10-26 14:53:54', '2021-10-26 14:53:54'),
(13, 'Utility Expenses', 5, 1, 1, 0, 1, 1, '2021-10-26 14:53:54', '2021-10-26 14:53:54'),
(14, 'House Rent', 5, 1, 1, 0, 1, 1, '2021-10-26 14:53:54', '2021-10-26 14:53:54'),
(15, 'Conveyance Expenses', 5, 1, 1, 0, 1, 1, '2021-10-26 14:53:54', '2021-10-26 14:53:54'),
(16, 'Salary Expenses', 5, 1, 1, 0, 1, 1, '2021-10-26 14:53:54', '2021-10-26 14:53:54'),
(17, 'Remuneration', 5, 1, 1, 0, 1, 1, '2021-10-26 14:53:54', '2021-10-26 14:53:54'),
(18, 'Miscellaneous Expenses', 5, 1, 1, 0, 1, 1, '2021-10-26 14:53:54', '2021-10-26 14:53:54'),
(19, 'Interest Expenses', 5, 1, 1, 0, 1, 1, '2021-10-26 14:53:54', '2021-10-26 14:53:54'),
(20, 'Depreciation Expense', 5, 1, 1, 0, 1, 1, '2021-10-26 14:53:54', '2021-10-26 14:53:54'),
(21, 'Remuneration Expense', 5, 1, 1, 0, 1, 1, '2021-10-26 14:53:54', '2021-10-26 14:53:54'),
(22, 'Sales Commission', 5, 1, 1, 0, 1, 1, '2021-10-26 14:53:54', '2021-10-26 14:53:54'),
(23, 'Sales Adjustment', 5, 1, 1, 0, 1, 1, '2021-10-26 14:53:54', '2021-10-26 14:53:54'),
(24, 'Purchase', 6, 1, 1, 0, 1, 1, '2021-10-26 14:53:54', '2021-10-26 14:53:54'),
(25, 'Sales Return', 7, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(26, 'Purchase Return', 8, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(27, 'None', 9, 1, 1, 1, 1, 1, '2021-11-03 10:19:55', '2021-11-03 10:19:55'),
(28, 'None', 10, 1, 1, 1, 1, 1, '2021-11-03 10:20:18', '2021-11-03 10:20:18');

-- --------------------------------------------------------

--
-- Table structure for table `account_groups`
--

CREATE TABLE `account_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `balance_type` enum('Debit','Credit') NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT 1,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `is_deletable` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_groups`
--

INSERT INTO `account_groups` (`id`, `name`, `balance_type`, `company_id`, `status`, `is_deletable`, `created_at`, `updated_at`) VALUES
(1, 'Asset', 'Debit', 1, 1, 0, '2021-10-26 14:53:53', '2021-10-26 14:53:53'),
(2, 'Liabilities', 'Credit', 1, 1, 0, '2021-10-26 14:53:53', '2021-10-26 14:53:53'),
(3, 'Owners Equity', 'Credit', 1, 1, 0, '2021-10-26 14:53:53', '2021-10-26 14:53:53'),
(4, 'Revenue', 'Credit', 1, 1, 0, '2021-10-26 14:53:53', '2021-10-26 14:53:53'),
(5, 'Expenses', 'Debit', 1, 1, 0, '2021-10-26 14:53:53', '2021-10-26 14:53:53'),
(6, 'Purchase', 'Debit', 1, 1, 0, '2021-10-26 14:53:53', '2021-10-26 14:53:53'),
(7, 'Sales Return', 'Debit', 1, 1, 0, '2021-10-26 14:53:53', '2021-10-26 14:53:53'),
(8, 'Purchase Return', 'Credit', 1, 1, 0, '2021-10-26 14:53:53', '2021-10-26 14:53:53'),
(9, 'Depreciation', 'Debit', 1, 1, 0, '2021-10-26 14:53:53', '2021-10-26 14:53:53'),
(10, 'Accumulated Depreciation', 'Credit', 1, 1, 0, '2021-10-26 14:53:53', '2021-10-26 14:53:53');

-- --------------------------------------------------------

--
-- Table structure for table `account_opening_balances`
--

CREATE TABLE `account_opening_balances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` varchar(191) DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_setups`
--

CREATE TABLE `account_setups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `is_deletable` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_subsidiaries`
--

CREATE TABLE `account_subsidiaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `account_group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_control_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT 1,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `is_deletable` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_subsidiaries`
--

INSERT INTO `account_subsidiaries` (`id`, `name`, `account_group_id`, `account_control_id`, `company_id`, `status`, `is_deletable`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Electricity Bill', 5, 13, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(2, 'Telephone Bill', 5, 13, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(3, 'Other Loans', 1, 3, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(4, 'Trading Acc Payable', 1, 3, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(5, 'Computer', 1, 2, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(6, 'Decoration', 1, 2, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(7, 'Furniture and Fixture', 1, 2, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(8, 'Trading Acc Receivables', 1, 1, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(9, 'Stock', 1, 1, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(10, 'Cash in Bank', 1, 1, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(11, 'Cash In Hand', 1, 1, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(12, 'SOFTWARE', 1, 2, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(13, 'None', 1, 1, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(14, 'None', 1, 2, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(15, 'None', 2, 4, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(16, 'None', 2, 3, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(17, 'None', 3, 5, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(18, 'None', 3, 6, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(19, 'None', 4, 7, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(20, 'None', 4, 8, 1, 1, 0, 1, 1, '2021-10-26 14:53:55', '2021-10-26 14:53:55'),
(21, 'None', 4, 9, 1, 1, 0, 1, 1, '2021-10-26 14:53:56', '2021-10-26 14:53:56'),
(22, 'None', 4, 10, 1, 1, 0, 1, 1, '2021-10-26 14:53:56', '2021-10-26 14:53:56'),
(23, 'None', 5, 11, 1, 1, 0, 1, 1, '2021-10-26 14:53:56', '2021-10-26 14:53:56'),
(24, 'None', 5, 12, 1, 1, 0, 1, 1, '2021-10-26 14:53:56', '2021-10-26 14:53:56'),
(25, 'None', 5, 13, 1, 1, 0, 1, 1, '2021-10-26 14:53:56', '2021-10-26 14:53:56'),
(26, 'None', 5, 14, 1, 1, 0, 1, 1, '2021-10-26 14:53:56', '2021-10-26 14:53:56'),
(27, 'None', 5, 15, 1, 1, 0, 1, 1, '2021-10-26 14:53:56', '2021-10-26 14:53:56'),
(28, 'None', 5, 16, 1, 1, 0, 1, 1, '2021-10-26 14:53:56', '2021-10-26 14:53:56'),
(29, 'None', 5, 17, 1, 1, 0, 1, 1, '2021-10-26 14:53:56', '2021-10-26 14:53:56'),
(30, 'None', 5, 18, 1, 1, 0, 1, 1, '2021-10-26 14:53:56', '2021-10-26 14:53:56'),
(31, 'None', 5, 19, 1, 1, 0, 1, 1, '2021-10-26 14:53:56', '2021-10-26 14:53:56'),
(32, 'None', 5, 20, 1, 1, 0, 1, 1, '2021-10-26 14:53:56', '2021-10-26 14:53:56'),
(33, 'None', 5, 25, 1, 1, 0, 1, 1, '2021-10-26 14:53:56', '2021-10-26 14:53:56'),
(34, 'None', 6, 22, 1, 1, 0, 1, 1, '2021-10-26 14:53:56', '2021-10-26 14:53:56'),
(35, 'None', 7, 23, 1, 1, 0, 1, 1, '2021-10-26 14:53:56', '2021-10-26 14:53:56'),
(36, 'None', 8, 24, 1, 1, 0, 1, 1, '2021-10-26 14:53:56', '2021-10-26 14:53:56'),
(37, 'None', 9, 27, 1, 1, 1, 1, 1, '2021-11-03 10:20:57', '2021-11-03 10:20:57'),
(38, 'None', 10, 28, 1, 1, 1, 1, 1, '2021-11-03 10:21:10', '2021-11-03 10:21:10'),
(39, 'Cheque In Bank', 1, 1, 1, 1, 1, 1, 1, '2021-11-04 17:02:23', '2021-11-04 17:02:23');

-- --------------------------------------------------------

--
-- Table structure for table `acc_categories`
--

CREATE TABLE `acc_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_collections`
--

CREATE TABLE `acc_collections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `date` varchar(191) NOT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `transaction_no` varchar(191) DEFAULT NULL,
  `remarks` varchar(191) DEFAULT NULL,
  `amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_customers`
--

CREATE TABLE `acc_customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `mobile` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `opening_balance` decimal(16,2) NOT NULL DEFAULT 0.00,
  `current_balance` decimal(16,2) NOT NULL DEFAULT 0.00,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `previous_due` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_damages`
--

CREATE TABLE `acc_damages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` varchar(191) NOT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `sale_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `total_amount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_damage_details`
--

CREATE TABLE `acc_damage_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `damage_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(20,2) NOT NULL DEFAULT 0.00,
  `price` decimal(20,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(20,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_payments`
--

CREATE TABLE `acc_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `date` varchar(191) NOT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `transaction_no` varchar(191) DEFAULT NULL,
  `remarks` varchar(191) DEFAULT NULL,
  `amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_purchases`
--

CREATE TABLE `acc_purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `sourceable_type` varchar(191) DEFAULT NULL,
  `sourceable_id` bigint(20) UNSIGNED DEFAULT NULL,
  `source` varchar(191) DEFAULT 'Account' COMMENT 'Account, Production and so on.',
  `date` varchar(191) NOT NULL,
  `invoice_no` varchar(191) NOT NULL,
  `qty_total` decimal(16,2) NOT NULL DEFAULT 0.00,
  `qty_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `previous_due` decimal(16,2) NOT NULL DEFAULT 0.00,
  `payable_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `due_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_purchase_details`
--

CREATE TABLE `acc_purchase_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_no` varchar(191) DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` double(16,2) NOT NULL DEFAULT 0.00,
  `price` decimal(16,2) NOT NULL DEFAULT 0.00,
  `amount` decimal(16,2) GENERATED ALWAYS AS (`quantity` * `price`) VIRTUAL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_purchase_exchange_details`
--

CREATE TABLE `acc_purchase_exchange_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(20,2) NOT NULL DEFAULT 0.00,
  `price` decimal(20,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(20,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_purchase_returns`
--

CREATE TABLE `acc_purchase_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` varchar(191) NOT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `total_return_amount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `total_exchange_amount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `total_discount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `total_payable` decimal(20,2) NOT NULL DEFAULT 0.00,
  `total_paid_amount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `total_due_amount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_purchase_return_details`
--

CREATE TABLE `acc_purchase_return_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_detail_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_type` varchar(191) NOT NULL DEFAULT 'Good' COMMENT 'Good, Damage',
  `quantity` decimal(20,2) NOT NULL DEFAULT 0.00,
  `price` decimal(20,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(20,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_sales`
--

CREATE TABLE `acc_sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `date` varchar(191) NOT NULL,
  `invoice_no` varchar(191) NOT NULL,
  `qty_total` decimal(16,2) NOT NULL DEFAULT 0.00,
  `qty_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `previous_due` decimal(16,2) NOT NULL DEFAULT 0.00,
  `payable_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `due_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_sale_details`
--

CREATE TABLE `acc_sale_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_no` varchar(191) DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` double(16,2) NOT NULL DEFAULT 0.00,
  `price` decimal(16,2) NOT NULL DEFAULT 0.00,
  `amount` decimal(16,2) GENERATED ALWAYS AS (`quantity` * `price`) VIRTUAL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_sale_exchange_details`
--

CREATE TABLE `acc_sale_exchange_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(20,2) NOT NULL DEFAULT 0.00,
  `price` decimal(20,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(20,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_sale_returns`
--

CREATE TABLE `acc_sale_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` varchar(191) NOT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `total_return_amount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `total_exchange_amount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `total_discount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `total_payable` decimal(20,2) NOT NULL DEFAULT 0.00,
  `total_paid_amount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `total_due_amount` decimal(20,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_sale_return_details`
--

CREATE TABLE `acc_sale_return_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_detail_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_type` varchar(191) NOT NULL DEFAULT 'Good' COMMENT 'Good, Damage',
  `quantity` decimal(20,2) NOT NULL DEFAULT 0.00,
  `price` decimal(20,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(20,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_stocks`
--

CREATE TABLE `acc_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `stockable_type` varchar(191) NOT NULL,
  `stockable_id` bigint(20) UNSIGNED NOT NULL,
  `stock_type` enum('In','Out') NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `quantity` double(8,2) NOT NULL,
  `amount` decimal(15,2) GENERATED ALWAYS AS (`price` * `quantity`) VIRTUAL,
  `actual_qty` double(8,2) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_stock_summaries`
--

CREATE TABLE `acc_stock_summaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `opening_qty` double(8,2) DEFAULT 0.00,
  `purchase_qty` double(8,2) DEFAULT 0.00,
  `sale_qty` double(8,2) DEFAULT 0.00,
  `issue_qty` double(8,2) DEFAULT 0.00,
  `purchase_return_qty` double(8,2) DEFAULT 0.00,
  `sale_return_qty` double(8,2) DEFAULT 0.00,
  `transfer_in_qty` double(8,2) DEFAULT 0.00,
  `transfer_out_qty` double(8,2) DEFAULT 0.00,
  `available_qty` double(8,2) GENERATED ALWAYS AS (`opening_qty` + `purchase_qty` + `purchase_return_qty` + `sale_return_qty` + `transfer_in_qty` - `sale_qty` - `issue_qty` - `transfer_out_qty`) VIRTUAL,
  `total_amount` decimal(15,2) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_suppliers`
--

CREATE TABLE `acc_suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `mobile` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `opening_balance` double(16,2) NOT NULL DEFAULT 0.00,
  `current_balance` double(16,2) NOT NULL DEFAULT 0.00,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `previous_due` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_name` varchar(191) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(191) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `causer_type` varchar(191) DEFAULT NULL,
  `causer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `bank_account_no` varchar(191) DEFAULT NULL,
  `bank_name` varchar(191) DEFAULT NULL,
  `branch_name` varchar(191) DEFAULT NULL,
  `branch_mobile` varchar(191) DEFAULT NULL,
  `branch_email` varchar(191) DEFAULT NULL,
  `branch_address` text DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `basic_rate_setups`
--

CREATE TABLE `basic_rate_setups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rate` decimal(15,2) NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `effected_month` varchar(191) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_number` varchar(191) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `purpose` varchar(191) DEFAULT NULL,
  `purpose_id` bigint(20) UNSIGNED DEFAULT NULL,
  `book_type` varchar(191) DEFAULT NULL,
  `platform_id` bigint(20) UNSIGNED DEFAULT NULL,
  `booking_type` varchar(191) DEFAULT NULL,
  `booking_from` varchar(191) DEFAULT NULL,
  `emergency_cont_phone` varchar(191) DEFAULT NULL,
  `emergency_cont_name` varchar(191) DEFAULT NULL,
  `check_in_date` date DEFAULT NULL,
  `check_out_date` date DEFAULT NULL,
  `booking_date` date DEFAULT NULL,
  `check_in_time` timestamp NULL DEFAULT NULL,
  `check_out_time` timestamp NULL DEFAULT NULL,
  `check_in_note` varchar(191) DEFAULT NULL,
  `sub_total` decimal(16,2) NOT NULL DEFAULT 0.00,
  `advanced_payment` decimal(16,2) NOT NULL DEFAULT 0.00,
  `deposits_money` decimal(16,2) DEFAULT 0.00,
  `payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_way` varchar(191) DEFAULT NULL,
  `vat_id` int(11) DEFAULT NULL,
  `vat_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `service_amount` decimal(8,2) DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0=>Reservation,1=>Check In,2=>Booked/Confrimation,3=>Check Out,4=>Cancel',
  `drop_flight` varchar(191) DEFAULT NULL,
  `pickup_flight` varchar(191) DEFAULT NULL,
  `drop` varchar(191) DEFAULT NULL,
  `pickup` varchar(191) DEFAULT NULL,
  `reference` varchar(191) DEFAULT NULL,
  `booking_pax` varchar(191) DEFAULT NULL,
  `child_pax` varchar(191) DEFAULT NULL,
  `adult_pax` varchar(191) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_room_in_invoice` varchar(191) DEFAULT '1',
  `company_id` int(11) DEFAULT NULL,
  `pay_by` bigint(20) UNSIGNED DEFAULT NULL,
  `card_info` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `booking_number`, `customer_id`, `purpose`, `purpose_id`, `book_type`, `platform_id`, `booking_type`, `booking_from`, `emergency_cont_phone`, `emergency_cont_name`, `check_in_date`, `check_out_date`, `booking_date`, `check_in_time`, `check_out_time`, `check_in_note`, `sub_total`, `advanced_payment`, `deposits_money`, `payment_id`, `payment_way`, `vat_id`, `vat_amount`, `service_amount`, `status`, `drop_flight`, `pickup_flight`, `drop`, `pickup`, `reference`, `booking_pax`, `child_pax`, `adult_pax`, `created_by`, `updated_by`, `created_at`, `updated_at`, `is_room_in_invoice`, `company_id`, `pay_by`, `card_info`) VALUES
(36, '2024-02-0005', 3, NULL, 2, NULL, 5, '', NULL, '0394752230', 'VIowteoGS3', '2024-02-29', '2024-03-01', '2024-02-29', '2024-02-29 09:16:28', NULL, NULL, 0.00, 0.00, 0.00, 1, NULL, 1, 28.55, 17.30, 1, '90kOXTLulx', 'aXOlK1gMY6', '3FiBUoZZBK', 'I4pKUmzdBd', 'habijabi', 'qTffWAeRhh', 're3ju2osiz', 'tnO4V0ZZQ2', 4, 4, '2024-02-29 09:16:28', '2024-02-29 09:16:28', '1', 1, NULL, 'hsR0iLWV7W'),
(43, '2024-02-0007', 17, '1', NULL, '2', NULL, '', '3', NULL, NULL, '2024-02-29', '2024-03-02', '2024-02-29', NULL, NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, 1, 138.60, 84.00, 2, 'Rrwk6uWONd', 'yTv33sKIsb', 'yne3cFrnDk', 'pR7ef3e7Kb', 'eCH6gvWyIq', NULL, NULL, NULL, 4, 4, '2024-02-29 09:57:53', '2024-02-29 09:57:53', '1', NULL, NULL, NULL),
(47, '2024-02-0011', 18, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '2024-03-01', '2024-02-29', '2024-02-29 10:43:26', '2024-02-29 11:20:13', NULL, 231.50, 0.00, 0.00, NULL, NULL, 1, 30.20, 18.30, 3, NULL, NULL, NULL, NULL, NULL, '2', '4', NULL, 4, 4, '2024-02-29 10:43:07', '2024-02-29 11:20:13', '1', NULL, NULL, NULL),
(48, '2024-02-0012', 18, NULL, NULL, NULL, 5, '', NULL, NULL, NULL, '2024-02-29', '2024-03-02', '2024-02-29', '2024-02-29 11:09:08', '2024-02-29 11:15:11', NULL, 0.00, 0.00, 0.00, 1, NULL, 1, 101.97, 61.80, 3, NULL, NULL, NULL, NULL, NULL, '6', '4', '2', 4, 4, '2024-02-29 11:08:51', '2024-02-29 11:15:11', '1', NULL, NULL, NULL),
(49, '2024-02-0013', 19, NULL, NULL, NULL, NULL, '', '3', NULL, NULL, '2024-03-01', '2024-03-06', '2024-02-29', '2024-02-29 11:35:51', '2024-02-29 11:36:39', NULL, 1897.50, 0.00, 0.00, 1, NULL, 1, 247.50, 150.00, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 4, '2024-02-29 11:32:38', '2024-02-29 11:36:39', '1', NULL, NULL, NULL),
(50, '2024-02-0014', 18, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-02-29', '2024-03-04', '2024-02-29', '2024-02-29 12:03:38', NULL, NULL, 0.00, 0.00, 0.00, 1, NULL, 1, 120.78, 73.20, 1, NULL, NULL, NULL, NULL, NULL, '6', '4', '2', 4, 4, '2024-02-29 12:02:32', '2024-02-29 12:03:38', '1', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_adjusts`
--

CREATE TABLE `booking_adjusts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` varchar(191) DEFAULT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `from_date` varchar(191) NOT NULL,
  `to_date` varchar(191) NOT NULL,
  `from_room_id` bigint(20) UNSIGNED DEFAULT NULL,
  `to_room_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `is_half_day` tinyint(4) NOT NULL DEFAULT 1,
  `is_transfer` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `booking_detail_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_carts`
--

CREATE TABLE `booking_carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `room_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nights` int(11) NOT NULL DEFAULT 1,
  `guest` int(11) NOT NULL DEFAULT 1,
  `infant` int(11) NOT NULL DEFAULT 0,
  `category_price` decimal(8,2) NOT NULL,
  `check_in_date` varchar(191) NOT NULL,
  `check_out_date` varchar(191) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=>cart, 2=>completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_date_details`
--

CREATE TABLE `booking_date_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `booking_detail_id` bigint(20) UNSIGNED DEFAULT NULL,
  `room_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_date_details`
--

INSERT INTO `booking_date_details` (`id`, `booking_id`, `booking_detail_id`, `room_id`, `date`, `created_at`, `updated_at`, `status`) VALUES
(49, 36, 12, 5, '2024-02-29', '2024-02-29 09:16:28', '2024-02-29 09:16:28', 1),
(50, 36, 12, 5, '2024-03-01', '2024-02-29 09:16:28', '2024-02-29 09:16:28', 1),
(70, 43, 19, 87, '2024-02-29', '2024-02-29 09:57:53', '2024-02-29 09:57:53', 2),
(71, 43, 19, 87, '2024-03-01', '2024-02-29 09:57:53', '2024-02-29 09:57:53', 2),
(72, 43, 19, 87, '2024-03-02', '2024-02-29 09:57:53', '2024-02-29 09:57:53', 2),
(79, 47, 23, 91, '2024-02-29', '2024-02-29 10:43:07', '2024-02-29 11:20:13', 3),
(80, 47, 23, 91, '2024-03-01', '2024-02-29 10:43:07', '2024-02-29 11:20:13', 3),
(81, 48, 24, 3, '2024-02-29', '2024-02-29 11:08:51', '2024-02-29 11:15:11', 3),
(82, 48, 24, 3, '2024-03-01', '2024-02-29 11:08:51', '2024-02-29 11:15:11', 3),
(83, 48, 24, 3, '2024-03-02', '2024-02-29 11:08:51', '2024-02-29 11:15:11', 3),
(84, 49, 25, 38, '2024-03-01', '2024-02-29 11:32:38', '2024-02-29 11:36:39', 3),
(85, 49, 25, 38, '2024-03-02', '2024-02-29 11:32:38', '2024-02-29 11:36:39', 3),
(86, 49, 25, 38, '2024-03-03', '2024-02-29 11:32:38', '2024-02-29 11:36:39', 3),
(87, 49, 25, 38, '2024-03-04', '2024-02-29 11:32:38', '2024-02-29 11:36:39', 3),
(88, 49, 25, 38, '2024-03-05', '2024-02-29 11:32:38', '2024-02-29 11:36:39', 3),
(89, 49, 25, 38, '2024-03-06', '2024-02-29 11:32:38', '2024-02-29 11:36:39', 3),
(90, 49, 25, 38, '2024-02-29', '2024-02-29 11:35:18', '2024-02-29 11:36:39', 3),
(91, 50, 26, 97, '2024-02-29', '2024-02-29 12:02:32', '2024-02-29 12:03:38', 1),
(92, 50, 26, 97, '2024-03-01', '2024-02-29 12:02:32', '2024-02-29 12:03:38', 1),
(93, 50, 26, 97, '2024-03-02', '2024-02-29 12:02:32', '2024-02-29 12:03:38', 1),
(94, 50, 26, 97, '2024-03-03', '2024-02-29 12:02:32', '2024-02-29 12:03:38', 1),
(95, 50, 26, 97, '2024-03-04', '2024-02-29 12:02:32', '2024-02-29 12:03:38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `booking_details`
--

CREATE TABLE `booking_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` varchar(191) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `guest_count` int(11) DEFAULT NULL,
  `infant_count` int(11) DEFAULT NULL,
  `child_count` varchar(191) DEFAULT NULL,
  `night_count` int(11) DEFAULT NULL,
  `room_discount` decimal(8,4) NOT NULL DEFAULT 0.0000,
  `discount_amount` int(11) DEFAULT NULL,
  `service_charge` decimal(8,2) NOT NULL,
  `total_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `current_room_rate` decimal(8,2) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `allow_breakfast` tinyint(4) DEFAULT 1,
  `breakfast_qty` varchar(191) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `discount_type` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_details`
--

INSERT INTO `booking_details` (`id`, `booking_id`, `category_id`, `room_id`, `guest_count`, `infant_count`, `child_count`, `night_count`, `room_discount`, `discount_amount`, `service_charge`, `total_amount`, `current_room_rate`, `status`, `allow_breakfast`, `breakfast_qty`, `created_by`, `updated_by`, `created_at`, `updated_at`, `discount_type`) VALUES
(12, '36', 2, 5, 2, 0, '0', 1, 10.0000, 10, 13.68, 173.00, 183.00, 1, 1, '12', 4, 4, '2024-02-29 09:16:28', '2024-02-29 09:16:28', 1),
(19, '43', 10, 87, 1, 0, NULL, 2, 0.0000, 0, 84.00, 840.00, 420.00, 1, 0, NULL, 4, 4, '2024-02-29 09:57:53', '2024-02-29 09:57:53', 0),
(23, '47', 2, 91, 2, 0, '4', 1, 0.0000, 0, 18.30, 183.00, 183.00, 3, 1, '6', 4, 4, '2024-02-29 10:43:07', '2024-02-29 11:20:13', 0),
(24, '48', 8, 3, 2, 0, '4', 2, 0.0000, 0, 61.80, 618.00, 309.00, 3, 1, '0', 4, 4, '2024-02-29 11:08:51', '2024-02-29 11:15:11', 0),
(25, '49', 9, 38, 1, 0, NULL, 5, 0.0000, 0, 150.00, 1500.00, 300.00, 3, 1, NULL, 4, 4, '2024-02-29 11:32:38', '2024-02-29 11:36:39', 0),
(26, '50', 2, 97, 2, 1, '3', 4, 0.0000, 0, 57.87, 732.00, 183.00, 1, 1, '0', 4, 4, '2024-02-29 12:02:32', '2024-02-29 12:03:38', 0);

-- --------------------------------------------------------

--
-- Table structure for table `booking_extra_charges`
--

CREATE TABLE `booking_extra_charges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `extra_amount` decimal(10,2) DEFAULT 0.00,
  `reason` longtext DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_guest_details`
--

CREATE TABLE `booking_guest_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `booking_detail_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `address` varchar(191) DEFAULT NULL,
  `phone` varchar(191) NOT NULL,
  `attach` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_member_details`
--

CREATE TABLE `booking_member_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `guest_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `gender` varchar(191) DEFAULT NULL,
  `age` varchar(191) NOT NULL DEFAULT '0',
  `relation` varchar(191) DEFAULT NULL,
  `registration_no` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_member_details`
--

INSERT INTO `booking_member_details` (`id`, `booking_id`, `guest_id`, `name`, `phone`, `email`, `gender`, `age`, `relation`, `registration_no`, `created_at`, `updated_at`) VALUES
(1, 36, 3, 'CY0o3VJphZ', '2348221932', 'I0xio2O2Wl', 'Female', 'r8JUjtXaK4', '1B311Id1FU', 'eoqVEut4Jb', '2024-02-29 09:16:28', '2024-02-29 09:16:28'),
(2, 36, 3, 'ZRoCq540M1', '8650946893', 'HfYYhWRJx3', 'Male', 'gSJKY3pSRe', '9dd1UncQc4', 'xVCuDxPOJx', '2024-02-29 09:16:28', '2024-02-29 09:16:28');

-- --------------------------------------------------------

--
-- Table structure for table `booking_notes`
--

CREATE TABLE `booking_notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` longtext DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_notes`
--

INSERT INTO `booking_notes` (`id`, `title`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Our standard check in time is 4:00 AM & check out time is 3:59 AM.', 1, NULL, NULL, '2024-02-01 17:51:20', '2024-02-01 17:51:20'),
(2, 'Check-in & Check-Out at 12:00 PM.', 1, NULL, NULL, '2024-02-01 17:51:20', '2024-02-01 17:51:20'),
(3, 'Early check in and late check out is chargeable and subject to availability. Please ensure rates.', 1, NULL, NULL, '2024-02-01 17:51:20', '2024-02-01 17:51:20'),
(4, 'In the event that you are enable to keep your reervation. PLease cancel the reservation 48 hours prior to arrival to avoid a one night accommodation charge. Cancellation after 48 hours prior to the date of arrival no-show one night accommodation charge will be applied on the provided credit card.', 1, NULL, NULL, '2024-02-01 17:51:20', '2024-02-01 17:51:20'),
(5, 'The actual Charges will be calculated by the hotel in its local currency.', 1, NULL, NULL, '2024-02-01 17:51:20', '2024-02-01 17:51:20'),
(6, 'Pets are not allow.', 1, NULL, NULL, '2024-02-01 17:51:20', '2024-02-01 17:51:20');

-- --------------------------------------------------------

--
-- Table structure for table `business_types`
--

CREATE TABLE `business_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_types`
--

INSERT INTO `business_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Knit', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(2, 'Sweater', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(3, 'Woven', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(4, 'Buying', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(5, 'Printing & Embroidery', '2024-02-01 17:49:17', '2024-02-01 17:49:17');

-- --------------------------------------------------------

--
-- Table structure for table `buyers`
--

CREATE TABLE `buyers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `contact_person` varchar(191) DEFAULT NULL,
  `image` varchar(191) DEFAULT 'default.png',
  `country_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `overseas_agent` text DEFAULT NULL,
  `brand` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `buyer_type` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `buyer_uploads`
--

CREATE TABLE `buyer_uploads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `contact_person` varchar(191) DEFAULT NULL,
  `overseas_agent` text DEFAULT NULL,
  `brand` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `buyer_type` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `card_authorized_information`
--

CREATE TABLE `card_authorized_information` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `authorized_info` varchar(191) DEFAULT NULL,
  `collect_amount` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `business_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `short_name` varchar(191) DEFAULT NULL,
  `head_office` text DEFAULT NULL,
  `factory` text DEFAULT NULL,
  `contact_name` varchar(191) DEFAULT NULL,
  `position` varchar(191) DEFAULT NULL,
  `phone_number` varchar(191) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `day_off` varchar(191) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `top_text` text DEFAULT NULL,
  `logo` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `business_type` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `group_id`, `business_type_id`, `name`, `code`, `short_name`, `head_office`, `factory`, `contact_name`, `position`, `phone_number`, `fax`, `email`, `day_off`, `country`, `top_text`, `logo`, `created_at`, `updated_at`, `business_type`) VALUES
(1, 1, NULL, 'MM Heritage Hotel', '1', 'MM Heritage Hotel', 'Here is Head office Information', 'Here is Factory office Information', 'Md. Mamun Bin Abdul Mannan', 'CEO', '60102224276', '+60102224276', 'admin@mmheritagehotel.com', NULL, 'Malaysia', NULL, 'mm_heritage_hotel_2024-02-04_65bfab9dcbd23.png', '2024-02-01 17:49:17', '2024-02-04 15:22:05', 'Hotel');

-- --------------------------------------------------------

--
-- Table structure for table `company_bank_accounts`
--

CREATE TABLE `company_bank_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `account_name` varchar(191) DEFAULT NULL,
  `account_number` varchar(191) DEFAULT NULL,
  `bank_name` varchar(191) DEFAULT NULL,
  `branch` varchar(191) DEFAULT NULL,
  `swift_code` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_bank_accounts`
--

INSERT INTO `company_bank_accounts` (`id`, `company_id`, `account_name`, `account_number`, `bank_name`, `branch`, `swift_code`, `created_at`, `updated_at`) VALUES
(1, 1, 'Company Design', '0012', 'IBBl', 'Dhanmondi-32', 'sw-001', '2024-02-01 17:49:17', '2024-02-04 15:22:05');

-- --------------------------------------------------------

--
-- Table structure for table `company_details`
--

CREATE TABLE `company_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `vat_no` varchar(191) DEFAULT NULL,
  `facsimile_number` varchar(191) DEFAULT NULL,
  `bonded_license` varchar(191) DEFAULT NULL,
  `membership_number` varchar(191) DEFAULT NULL,
  `bkmea_reg_no` varchar(191) DEFAULT NULL,
  `import_reg_certi` varchar(191) DEFAULT NULL,
  `export_reg_certi` varchar(191) DEFAULT NULL,
  `epb_reg_no` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `header` varchar(191) DEFAULT NULL,
  `footer` varchar(191) DEFAULT NULL,
  `organogram` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_details`
--

INSERT INTO `company_details` (`id`, `company_id`, `vat_no`, `facsimile_number`, `bonded_license`, `membership_number`, `bkmea_reg_no`, `import_reg_certi`, `export_reg_certi`, `epb_reg_no`, `created_at`, `updated_at`, `header`, `footer`, `organogram`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-01 17:49:17', '2024-02-04 15:22:05', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `company_user`
--

CREATE TABLE `company_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_user`
--

INSERT INTO `company_user` (`id`, `company_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 4, NULL, NULL),
(2, 1, 5, NULL, NULL),
(3, 1, 6, NULL, NULL),
(4, 1, 3, NULL, NULL),
(5, 1, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Afghanistan', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(2, 'Albania', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(3, 'Algeria', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(4, 'American Samoa', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(5, 'Andorra', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(6, 'Angola', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(7, 'Anguilla', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(8, 'Antarctica', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(9, 'Antigua and Barbuda', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(10, 'Argentina', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(11, 'Armenia', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(12, 'Aruba', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(13, 'Australia', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(14, 'Austria', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(15, 'Azerbaijan', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(16, 'Bahamas', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(17, 'Bahrain', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(18, 'Bangladesh', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(19, 'Barbados', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(20, 'Belarus', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(21, 'Belgium', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(22, 'Belize', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(23, 'Benin', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(24, 'Bermuda', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(25, 'Bhutan', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(26, 'Bolivia', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(27, 'Bosnia and Herzegowina', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(28, 'Botswana', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(29, 'Bouvet Island', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(30, 'Brazil', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(31, 'British Indian Ocean Territory', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(32, 'Brunei Darussalam', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(33, 'Bulgaria', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(34, 'Burkina Faso', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(35, 'Burundi', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(36, 'Cambodia', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(37, 'Cameroon', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(38, 'Canada', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(39, 'Cape Verde', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(40, 'Cayman Islands', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(41, 'Central African Republic', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(42, 'Chad', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(43, 'Chile', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(44, 'China', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(45, 'Christmas Island', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(46, 'Cocos (Keeling) Islands', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(47, 'Colombia', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(48, 'Comoros', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(49, 'Congo', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(50, 'Congo, the Democratic Republic of the', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(51, 'Cook Islands', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(52, 'Costa Rica', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(53, 'Cote d\'Ivoire', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(54, 'Croatia (Hrvatska)', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(55, 'Cuba', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(56, 'Cyprus', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(57, 'Czech Republic', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(58, 'Denmark', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(59, 'Djibouti', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(60, 'Dominica', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(61, 'Dominican Republic', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(62, 'East Timor', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(63, 'Ecuador', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(64, 'Egypt', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(65, 'El Salvador', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(66, 'Equatorial Guinea', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(67, 'Eritrea', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(68, 'Estonia', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(69, 'Ethiopia', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(70, 'Falkland Islands (Malvinas)', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(71, 'Faroe Islands', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(72, 'Fiji', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(73, 'Finland', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(74, 'France', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(75, 'France Metropolitan', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(76, 'French Guiana', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(77, 'French Polynesia', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(78, 'French Southern Territories', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(79, 'Gabon', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(80, 'Gambia', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(81, 'Georgia', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(82, 'Germany', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(83, 'Ghana', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(84, 'Gibraltar', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(85, 'Greece', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(86, 'Greenland', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(87, 'Grenada', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(88, 'Guadeloupe', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(89, 'Guam', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(90, 'Guatemala', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(91, 'Guinea', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(92, 'Guinea-Bissau', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(93, 'Guyana', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(94, 'Haiti', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(95, 'Heard and Mc Donald Islands', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(96, 'Holy See (Vatican City State)', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(97, 'Honduras', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(98, 'Hong Kong', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(99, 'Hungary', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(100, 'Iceland', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(101, 'India', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(102, 'Indonesia', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(103, 'Iran (Islamic Republic of)', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(104, 'Iraq', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(105, 'Ireland', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(106, 'Israel', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(107, 'Italy', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(108, 'Jamaica', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(109, 'Japan', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(110, 'Jordan', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(111, 'Kazakhstan', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(112, 'Kenya', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(113, 'Kiribati', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(114, 'Korea, Democratic People\'s Republic of', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(115, 'Korea, Republic of', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(116, 'Kuwait', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(117, 'Kyrgyzstan', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(118, 'Lao, People\'s Democratic Republic', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(119, 'Latvia', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(120, 'Lebanon', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(121, 'Lesotho', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(122, 'Liberia', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(123, 'Libyan Arab Jamahiriya', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(124, 'Liechtenstein', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(125, 'Lithuania', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(126, 'Luxembourg', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(127, 'Macau', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(128, 'Macedonia, The Former Yugoslav Republic of', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(129, 'Madagascar', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(130, 'Malawi', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(131, 'Malaysia', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(132, 'Maldives', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(133, 'Mali', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(134, 'Malta', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(135, 'Marshall Islands', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(136, 'Martinique', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(137, 'Mauritania', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(138, 'Mauritius', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(139, 'Mayotte', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(140, 'Mexico', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(141, 'Micronesia, Federated States of', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(142, 'Moldova, Republic of', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(143, 'Monaco', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(144, 'Mongolia', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(145, 'Montserrat', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(146, 'Morocco', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(147, 'Mozambique', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(148, 'Myanmar', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(149, 'Namibia', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(150, 'Nauru', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(151, 'Nepal', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(152, 'Netherlands', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(153, 'Netherlands Antilles', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(154, 'New Caledonia', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(155, 'New Zealand', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(156, 'Nicaragua', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(157, 'Niger', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(158, 'Nigeria', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(159, 'Niue', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(160, 'Norfolk Island', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(161, 'Northern Mariana Islands', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(162, 'Norway', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(163, 'Oman', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(164, 'Pakistan', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(165, 'Palau', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(166, 'Panama', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(167, 'Papua New Guinea', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(168, 'Paraguay', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(169, 'Peru', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(170, 'Philippines', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(171, 'Pitcairn', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(172, 'Poland', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(173, 'Portugal', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(174, 'Puerto Rico', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(175, 'Qatar', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(176, 'Reunion', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(177, 'Romania', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(178, 'Russian Federation', '2024-02-01 17:49:16', '2024-02-01 17:49:16'),
(179, 'Rwanda', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(180, 'Saint Kitts and Nevis', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(181, 'Saint Lucia', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(182, 'Saint Vincent and the Grenadines', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(183, 'Samoa', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(184, 'San Marino', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(185, 'Sao Tome and Principe', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(186, 'Saudi Arabia', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(187, 'Senegal', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(188, 'Seychelles', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(189, 'Sierra Leone', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(190, 'Singapore', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(191, 'Slovakia (Slovak Republic)', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(192, 'Slovenia', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(193, 'Solomon Islands', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(194, 'Somalia', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(195, 'South Africa', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(196, 'South Georgia and the South Sandwich Islands', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(197, 'Spain', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(198, 'Sri Lanka', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(199, 'St. Helena', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(200, 'St.\n                        Pierre and Miquelon', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(201, 'Sudan', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(202, 'Suriname', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(203, 'Svalbard and Jan Mayen Islands', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(204, 'Swaziland', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(205, 'Sweden', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(206, 'Switzerland', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(207, 'Syrian Arab Republic', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(208, 'Taiwan, Province of China', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(209, 'Tajikistan', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(210, '\n                        Tanzania, United Republic of', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(211, 'Thailand', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(212, 'Togo', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(213, 'Tokelau', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(214, 'Tonga', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(215, 'Trinidad and Tobago', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(216, 'Tunisia', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(217, 'Turkey', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(218, 'Turkmenistan', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(219, 'Turks and Caicos Islands', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(220, 'Tuvalu', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(221, 'Uganda', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(222, 'Ukraine', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(223, 'United Arab Emirates', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(224, 'United Kingdom', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(225, 'United States', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(226, 'United States Minor Outlying Islands', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(227, 'Uruguay', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(228, 'Uzbekistan', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(229, 'Vanuatu', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(230, 'Venezuela', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(231, 'Vietnam', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(232, 'Virgin Islands (British)', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(233, 'Virgin Islands (U.S.)', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(234, 'Wallis and Futuna Islands', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(235, 'Western Sahara', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(236, 'Yemen', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(237, 'Yugoslavia', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(238, 'Zambia', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(239, 'Zimbabwe', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(240, 'Any', '2024-02-01 17:49:17', '2024-02-01 17:49:17');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'AED', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(2, 'AFN', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(3, 'ALL', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(4, 'ANG', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(5, 'AOA', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(6, 'ARS', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(7, 'AUD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(8, 'AWG', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(9, 'AZN', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(10, 'BAM', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(11, 'BBD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(12, 'BDT', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(13, 'BGN', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(14, 'BHD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(15, 'BIF', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(16, 'BMD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(17, 'BND', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(18, 'BOB', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(19, 'BRL', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(20, 'BSD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(21, 'BTN', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(22, 'BWP', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(23, 'BYN', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(24, 'BZD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(25, 'CAD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(26, 'CDF', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(27, 'CHF', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(28, 'CLP', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(29, 'CNY', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(30, 'COP', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(31, 'CRC', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(32, 'CUP', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(33, 'CVE', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(34, 'CZK', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(35, 'DJF', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(36, 'DKK', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(37, 'DOP', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(38, 'DZD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(39, 'EGP', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(40, 'ERN', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(41, 'ETB', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(42, 'EUR', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(43, 'FJD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(44, 'FKP', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(45, 'GBP', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(46, 'GEL', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(47, 'GGP', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(48, 'GHS', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(49, 'GIP', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(50, 'GMD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(51, 'GNF', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(52, 'GTQ', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(53, 'GYD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(54, 'HKD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(55, 'HNL', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(56, 'HRK', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(57, 'HTG', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(58, 'HUF', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(59, 'IDR', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(60, 'ILS', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(61, 'IMP', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(62, 'INR', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(63, 'IQD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(64, 'IRR', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(65, 'ISK', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(66, 'JEP', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(67, 'JMD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(68, 'JOD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(69, 'JPY', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(70, 'KES', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(71, 'KGS', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(72, 'KHR', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(73, 'KMF', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(74, 'KPW', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(75, 'KRW', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(76, 'KWD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(77, 'KYD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(78, 'KZT', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(79, 'LAK', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(80, 'LBP', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(81, 'LKR', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(82, 'LRD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(83, 'LSL', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(84, 'LYD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(85, 'MAD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(86, 'MDL', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(87, 'MGA', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(88, 'MKD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(89, 'MMK', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(90, 'MNT', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(91, 'MOP', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(92, 'MUR', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(93, 'MVR', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(94, 'MWK', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(95, 'MXN', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(96, 'MYR', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(97, 'MZN', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(98, 'NAD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(99, 'NGN', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(100, 'NIO', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(101, 'NOK', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(102, 'NPR', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(103, 'NZD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(104, 'OMR', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(105, 'PEN', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(106, 'PGK', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(107, 'PHP', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(108, 'PKR', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(109, 'PLN', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(110, 'PYG', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(111, 'QAR', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(112, 'RON', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(113, 'RSD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(114, 'RUB', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(115, 'RWF', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(116, 'SAR', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(117, 'SBD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(118, 'SCR', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(119, 'SDG', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(120, 'SEK', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(121, 'SGD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(122, 'SHP', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(123, 'SLL', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(124, 'SOS', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(125, 'SRD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(126, 'SSP', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(127, 'STN', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(128, 'SYP', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(129, 'SZL', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(130, 'THB', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(131, 'TJS', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(132, 'TMT', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(133, 'TND', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(134, 'TOP', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(135, 'TRY', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(136, 'TTD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(137, 'TWD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(138, 'TZS', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(139, 'UAH', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(140, 'UGX', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(141, 'USD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(142, 'UYU', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(143, 'UZS', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(144, 'VES', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(145, 'VND', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(146, 'VUV', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(147, 'WST', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(148, 'XAF', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(149, 'XCD', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(150, 'XDR', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(151, 'XOF', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(152, 'XPF', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(153, 'YER', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(154, 'ZAR', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(155, 'ZMW', '2024-02-01 17:49:17', '2024-02-01 17:49:17');

-- --------------------------------------------------------

--
-- Table structure for table `currency_conversions`
--

CREATE TABLE `currency_conversions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `effected_date` varchar(191) NOT NULL,
  `currency_id` bigint(20) UNSIGNED NOT NULL,
  `rate` decimal(14,2) NOT NULL,
  `icon` varchar(191) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currency_conversions`
--

INSERT INTO `currency_conversions` (`id`, `effected_date`, `currency_id`, `rate`, `icon`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '2022-09-01', 12, 1.00, NULL, 1, NULL, '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(2, '2022-09-01', 141, 100.00, NULL, 1, NULL, '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(3, '2024-02-17', 96, 1.00, NULL, 1, 1, '2024-02-18 05:35:21', '2024-02-29 11:58:28');

-- --------------------------------------------------------

--
-- Table structure for table `customer_ledgers`
--

CREATE TABLE `customer_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `date` varchar(191) NOT NULL,
  `balance_type` enum('Debit','Credit') NOT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `c_r_m_customers`
--

CREATE TABLE `c_r_m_customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_name` varchar(191) DEFAULT NULL,
  `org_phone` varchar(191) DEFAULT NULL,
  `org_email` varchar(191) DEFAULT NULL,
  `c_name` varchar(191) DEFAULT NULL,
  `c_phone` varchar(191) DEFAULT NULL,
  `c_email` varchar(191) DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL,
  `street` varchar(191) DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `zip_code` int(11) DEFAULT NULL,
  `country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `leads` tinyint(1) DEFAULT NULL,
  `currency` varchar(191) DEFAULT NULL,
  `lead_status` varchar(191) DEFAULT NULL,
  `lead_source` varchar(191) DEFAULT NULL,
  `assigned` varchar(191) DEFAULT NULL,
  `contact_date` varchar(191) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `c_r_m_customers`
--

INSERT INTO `c_r_m_customers` (`id`, `org_name`, `org_phone`, `org_email`, `c_name`, `c_phone`, `c_email`, `tax_id`, `street`, `city`, `address`, `zip_code`, `country_id`, `leads`, `currency`, `lead_status`, `lead_source`, `assigned`, `contact_date`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'demo company', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-24 10:40:37', '2024-02-24 10:40:37'),
(2, 'MM HERITAGE HOTEL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-29 10:39:48', '2024-02-29 10:39:48');

-- --------------------------------------------------------

--
-- Table structure for table `department_user`
--

CREATE TABLE `department_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `designation_user`
--

CREATE TABLE `designation_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `designation_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `mail_template_id` bigint(20) UNSIGNED NOT NULL,
  `c_r_m_customer_id` bigint(20) UNSIGNED NOT NULL,
  `project_billing_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fund_transfers`
--

CREATE TABLE `fund_transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `from_account_id` bigint(20) UNSIGNED NOT NULL,
  `to_account_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `date` date NOT NULL,
  `reference` varchar(191) DEFAULT NULL,
  `is_approved` tinyint(4) NOT NULL DEFAULT 0,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `global_infos`
--

CREATE TABLE `global_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(191) NOT NULL,
  `value` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `global_infos`
--

INSERT INTO `global_infos` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'marital_status', 'Single', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(2, 'marital_status', 'Married', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(3, 'gender', 'Male', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(4, 'gender', 'Female', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(5, 'employee_type', 'Production', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(6, 'employee_type', 'Regular', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(7, 'religion', 'Muslim', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(8, 'religion', 'Hinduism', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(9, 'religion', 'Christan', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(10, 'religion', 'Buddhisum', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(11, 'religion', 'Other', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(12, 'salary_type', 'Monthly Based', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(13, 'salary_type', 'Hourly Based', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(14, 'p_bonus_type', 'Applicable', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(15, 'p_bonus_type', 'Not Applicable', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(16, 'blood_group', 'A+', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(17, 'blood_group', 'B+', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(18, 'blood_group', 'A-', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(19, 'blood_group', 'B-', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(20, 'blood_group', 'O+', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(21, 'blood_group', 'O-', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(22, 'blood_group', 'AB+', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(23, 'blood_group', 'AB-', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(24, 'employment_status', 'Permanent', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(25, 'employment_status', 'Casual', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(26, 'employment_status', 'Temporary', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(27, 'employee_facilities', 'Quarter Facilities', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(28, 'employee_facilities', 'Transport Facilities', '2024-02-01 17:49:17', '2024-02-01 17:49:17'),
(29, 'employee_facilities', 'No Facilities', '2024-02-01 17:49:17', '2024-02-01 17:49:17');

-- --------------------------------------------------------

--
-- Table structure for table `goods_requisitions`
--

CREATE TABLE `goods_requisitions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `goods_requisition_date` date NOT NULL,
  `goods_requisition_reference` varchar(191) DEFAULT NULL,
  `total_quantity` int(11) NOT NULL DEFAULT 0,
  `form_number` varchar(191) DEFAULT NULL,
  `issue_date` timestamp NULL DEFAULT NULL,
  `issue_number` varchar(191) DEFAULT NULL,
  `is_approved` tinyint(4) NOT NULL DEFAULT 0,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goods_requisition_details`
--

CREATE TABLE `goods_requisition_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quantity` double(15,2) NOT NULL DEFAULT 0.00,
  `remarks` varchar(191) DEFAULT NULL,
  `goods_requisition_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `price` double(15,3) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `logo` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fav_icon` varchar(191) DEFAULT NULL,
  `login_background_image` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `email`, `phone`, `address`, `logo`, `created_at`, `updated_at`, `fav_icon`, `login_background_image`) VALUES
(1, 'MM Heritage Hotel', 'booking@mmheritagehotel.com', '+60102224276', 'Jln Baiduri 1, Taman Pulau Melaka, 75000 Malacca, Malaysia', 'mm_heritage_hotel_2024-02-04_65bf785a77cf6.png', '2024-02-01 17:49:17', '2024-02-04 15:14:23', './assets/uploads/group/2024/1707059663.png', './assets/uploads/group/2024/1707047002.png');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_account_transactions`
--

CREATE TABLE `hotel_account_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` varchar(191) NOT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `source_type` varchar(191) NOT NULL,
  `source_id` bigint(20) UNSIGNED NOT NULL,
  `account_type_id` varchar(191) DEFAULT NULL,
  `payment_currency_id` varchar(191) DEFAULT '12',
  `currency_conversion_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `collection` decimal(16,2) NOT NULL DEFAULT 0.00,
  `discount` int(11) DEFAULT NULL,
  `vat_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `service_charge` decimal(8,2) NOT NULL DEFAULT 0.00,
  `extra_charge` decimal(10,2) DEFAULT 0.00,
  `due_amount` decimal(16,4) GENERATED ALWAYS AS (`total_amount` - `collection`) VIRTUAL,
  `total_due_amount` decimal(16,4) GENERATED ALWAYS AS (`total_amount` - `collection` - `discount`) VIRTUAL,
  `change_amount` decimal(8,2) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `datetime` varchar(191) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_account_transactions`
--

INSERT INTO `hotel_account_transactions` (`id`, `date`, `invoice_no`, `source_type`, `source_id`, `account_type_id`, `payment_currency_id`, `currency_conversion_id`, `total_amount`, `collection`, `discount`, `vat_amount`, `service_charge`, `extra_charge`, `change_amount`, `created_by`, `updated_by`, `created_at`, `datetime`, `updated_at`, `booking_id`) VALUES
(36, '2024-02-29', '2024-02-0005', 'Booking', 36, '1', '96', 3, 218.85, 5000.00, 0, 28.55, 17.30, NULL, NULL, 4, 4, '2024-02-29 09:16:28', '2024-02-29 15:16:28', '2024-02-29 09:16:28', 36),
(43, '2024-02-29', '2024-02-0007', 'Booking', 43, NULL, '96', 3, 1062.60, 0.00, 0, 138.60, 84.00, NULL, NULL, 4, 4, '2024-02-29 09:57:53', '2024-02-29 15:57:53', '2024-02-29 09:57:53', 43),
(47, '2024-02-29', '2024-02-0011', 'Booking', 47, '1', '96', 3, 231.50, 594.50, 0, 30.20, 18.30, NULL, NULL, 4, 4, '2024-02-29 10:43:07', '2024-02-29 16:43:07', '2024-02-29 11:20:13', 47),
(48, '2024-02-29', '2024-02-0012', 'Booking', 48, '1', '96', 3, 781.77, 781.77, 0, 101.97, 61.80, NULL, NULL, 4, 4, '2024-02-29 11:08:51', '2024-02-29 17:08:51', '2024-02-29 11:15:11', 48),
(49, '2024-02-29', '2024-02-0013', 'Booking', 49, '1', '96', 3, 1897.50, 1897.50, 0, 247.50, 150.00, NULL, NULL, 4, 4, '2024-02-29 11:32:38', '2024-02-29 17:32:38', '2024-02-29 11:36:39', 49),
(50, '2024-02-29', '2024-02-0014', 'Booking', 50, '1', '96', 3, 925.98, 925.98, 0, 120.78, 73.20, NULL, NULL, 4, 4, '2024-02-29 12:02:32', '2024-02-29 18:02:32', '2024-02-29 12:02:32', 50);

-- --------------------------------------------------------

--
-- Table structure for table `hotel_account_type`
--

CREATE TABLE `hotel_account_type` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `status` varchar(191) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_account_type`
--

INSERT INTO `hotel_account_type` (`id`, `account_id`, `name`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 55, 'Cash', '1', 1, 1, '2021-12-14 18:37:06', '2024-02-24 10:46:26');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_banners`
--

CREATE TABLE `hotel_banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `banner_title` varchar(191) DEFAULT NULL,
  `banner_sub_title` varchar(191) DEFAULT NULL,
  `banner_short_desc` varchar(191) DEFAULT NULL,
  `banner_image` varchar(191) DEFAULT NULL,
  `image_path` varchar(191) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_banners`
--

INSERT INTO `hotel_banners` (`id`, `banner_title`, `banner_sub_title`, `banner_short_desc`, `banner_image`, `image_path`, `status`, `company_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(5, NULL, NULL, NULL, './assets/uploads/hotel/website//2024/1707224031.jpg', NULL, 0, 1, 3, 1, '2024-02-06 12:53:51', '2024-02-18 10:01:33'),
(9, NULL, NULL, NULL, './assets/uploads/hotel/website//2024/1707460551.jpg', NULL, 1, 1, 3, NULL, '2024-02-09 06:35:51', '2024-02-09 06:35:51'),
(10, NULL, NULL, NULL, './assets/uploads/hotel/website//2024/1707534872.jpg', NULL, 1, 1, 3, NULL, '2024-02-10 03:14:31', '2024-02-10 03:14:32'),
(11, NULL, NULL, NULL, './assets/uploads/hotel/website//2024/1707904527.jpg', NULL, 1, 1, 3, NULL, '2024-02-14 09:55:26', '2024-02-14 09:55:27');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_booking_purpose`
--

CREATE TABLE `hotel_booking_purpose` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `rule` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=Pupose, 2=Platform',
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_booking_purpose`
--

INSERT INTO `hotel_booking_purpose` (`id`, `name`, `rule`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Travel', 1, 1, 1, NULL, '2024-02-01 17:51:20', '2024-02-01 17:51:20'),
(2, 'Official', 1, 1, 1, NULL, '2024-02-01 17:51:20', '2024-02-01 17:51:20'),
(4, 'Corporate', 2, 1, 1, NULL, '2024-02-01 17:51:20', '2024-02-01 17:51:20'),
(5, 'Orders', 2, 1, 1, NULL, '2024-02-01 17:51:20', '2024-02-01 17:51:20'),
(6, 'Goverment', 2, 1, 1, 1, '2024-02-19 12:15:41', '2024-02-19 12:15:41');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_customer_ledgers`
--

CREATE TABLE `hotel_customer_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hotel_guest_id` bigint(20) UNSIGNED NOT NULL,
  `date` varchar(191) NOT NULL,
  `source_type` varchar(191) DEFAULT NULL,
  `source_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `debit` decimal(10,2) DEFAULT 0.00,
  `credit` decimal(10,2) DEFAULT 0.00,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotel_features`
--

CREATE TABLE `hotel_features` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) DEFAULT NULL,
  `sub_title` varchar(191) DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_features`
--

INSERT INTO `hotel_features` (`id`, `title`, `sub_title`, `company_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'EXPERIENCE A GOOD STAY, ENJOY FANTASTIC OFFERS', 'FIND OUR FRIENDLY WELCOMING RECEPTION', 1, 1, NULL, '2024-02-01 17:51:29', '2024-02-01 17:51:29');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_feature_lists`
--

CREATE TABLE `hotel_feature_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) DEFAULT NULL,
  `sub_title` varchar(191) DEFAULT NULL,
  `feature_icon` varchar(191) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_feature_lists`
--

INSERT INTO `hotel_feature_lists` (`id`, `title`, `sub_title`, `feature_icon`, `status`, `company_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'MASTER BEDROOMS', 'RESORT INN', 'fa fa-bed', 1, NULL, 1, NULL, '2021-12-28 18:04:14', '2021-12-28 18:25:38'),
(2, 'SEA VIEW BALCONY', 'RESORT INN', 'fa fa-building', 1, NULL, 1, NULL, '2021-12-28 18:05:25', '2021-12-28 18:18:50'),
(3, 'Large Cafe', 'RESORT INN', 'fa fa-coffee', 1, NULL, 1, NULL, '2021-12-29 16:31:28', '2021-12-29 16:31:28'),
(4, 'Wifi Coverage', 'RESORT INN', 'fa fa-wifi', 1, NULL, 1, NULL, '2021-12-29 16:32:09', '2021-12-29 16:32:09');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_galleries`
--

CREATE TABLE `hotel_galleries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `gallery_text` varchar(191) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_galleries`
--

INSERT INTO `hotel_galleries` (`id`, `name`, `gallery_text`, `status`, `company_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(2, './assets/uploads/uploads/hotel/website/gallery/2024/2-1707216059-356880.jpg', 'Lobby', 1, 1, 1, NULL, '2024-02-06 10:40:59', '2024-02-06 10:40:59'),
(6, './assets/uploads/uploads/hotel/website/gallery/2024/6-1707223648-960860.jpg', 'Restaurant', 1, 1, 1, NULL, '2024-02-06 12:47:28', '2024-02-06 12:47:28'),
(7, './assets/uploads/uploads/hotel/website/gallery/2024/7-1707223704-928455.jpg', 'Room', 1, 1, 1, NULL, '2024-02-06 12:48:24', '2024-02-06 12:48:24'),
(8, './assets/uploads/uploads/hotel/website/gallery/2024/8-1707223720-827282.jpg', 'Room', 1, 1, 1, NULL, '2024-02-06 12:48:40', '2024-02-06 12:48:40');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_guest`
--

CREATE TABLE `hotel_guest` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone_no` varchar(191) DEFAULT NULL,
  `nid_no` varchar(191) DEFAULT NULL,
  `passport_expiry_date` varchar(191) DEFAULT NULL,
  `country_id` bigint(20) UNSIGNED DEFAULT NULL,
  `city_id` varchar(191) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `gender` varchar(191) DEFAULT NULL,
  `father_name` varchar(191) DEFAULT NULL,
  `age` varchar(191) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `reference` varchar(191) DEFAULT NULL,
  `profession` varchar(191) DEFAULT NULL,
  `spouse_name` varchar(191) DEFAULT NULL,
  `nid_front` varchar(191) DEFAULT NULL,
  `nid_back` varchar(191) DEFAULT NULL,
  `spouse_nid_front` varchar(191) DEFAULT NULL,
  `spouse_nid_back` varchar(191) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_bar` tinyint(4) DEFAULT 0,
  `is_stuff` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_guest`
--

INSERT INTO `hotel_guest` (`id`, `name`, `email`, `phone_no`, `nid_no`, `passport_expiry_date`, `country_id`, `city_id`, `company_id`, `gender`, `father_name`, `age`, `image`, `address`, `reference`, `profession`, `spouse_name`, `nid_front`, `nid_back`, `spouse_nid_front`, `spouse_nid_back`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `booking_id`, `is_bar`, `is_stuff`) VALUES
(1, 'Sakib Hossain', 'sakib129@gmail.com', '01717637555', '4545015413213', NULL, 18, NULL, NULL, '1', NULL, NULL, NULL, 'demo address', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2021-12-14 12:02:32', '2021-12-26 18:36:23', NULL, 0, 0),
(2, 'Sifat Hossain', 'sifat@gmail.com', '01765464541', '4545015413256465', NULL, 18, NULL, NULL, '1', NULL, NULL, NULL, 'Dhanmondi 15/A Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 4, '2021-12-14 16:34:25', '2024-02-23 03:44:23', NULL, 0, 0),
(3, 'chowdhury Hasan', 'chowdhury@gmail.com', '05468410556', '53654324567825', NULL, 18, NULL, 1, '1', NULL, NULL, NULL, 'demo address', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2021-12-21 13:31:28', '2024-02-29 11:37:47', NULL, 0, 0),
(4, 'Rayhan Ahmed', 'rayhan@gmail.com', '01744431354', '4545015413213', NULL, 18, NULL, NULL, '1', NULL, NULL, NULL, 'Dhanmondi 15/A Dhaka.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2021-12-22 17:20:17', '2021-12-26 18:26:49', NULL, 0, 0),
(5, 'Rezaul Korim', 'reza@gmail.com', '017868546655', '4545015413256465', NULL, 18, NULL, NULL, '1', NULL, NULL, NULL, '11/A Dhanmondi, Dhaka', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2021-12-22 17:51:29', '2024-02-29 11:37:27', NULL, 0, 0),
(8, 'MD MAMUN BIN ABDUL MANNAN', NULL, '0102224276', NULL, NULL, 131, NULL, NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 3, 4, '2024-02-28 07:01:05', '2024-02-29 08:55:38', NULL, 0, 0),
(9, 'Pavel', 'pavel@cblmoneytransfer.com', '0164670256', '0796266', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2024-02-29 04:50:45', '2024-02-29 04:50:45', NULL, 0, 0),
(18, 'MOHAMAD IZWAN BIN AHMAD', 'encikwan888@gmail.com', '0173801915', '891103045191', NULL, 131, NULL, NULL, '1', NULL, '35', NULL, 'C 6322 JALAN PULAU GADONG KLEBANG BESAR 75200 MELAKA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 4, NULL, '2024-02-29 10:39:49', '2024-02-29 12:02:32', 50, 0, 0),
(19, 'Nurul Faizurin Binti Abdul Rahim', 'nurul.faizurin@gmail.com', '0103581915', '901118016234', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '41A Jalan LP5 Taman Limbongan Permai 75200 Melaka', NULL, NULL, 'Mohamad Izwan Bin Ahmad', NULL, NULL, NULL, NULL, 1, 1, NULL, '2024-02-29 11:32:38', '2024-02-29 11:32:38', 49, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `hotel_guest_registration_terms`
--

CREATE TABLE `hotel_guest_registration_terms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` longtext DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_guest_registration_terms`
--

INSERT INTO `hotel_guest_registration_terms` (`id`, `title`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Guests are reminded that the hotel official departure time is 11 am and check-in time is 2 pm onward.', 1, NULL, NULL, '2024-02-01 17:51:20', '2024-02-01 17:51:20'),
(2, 'Bill must be settled upon presentation 1 hr before checking out.', 1, NULL, NULL, '2024-02-01 17:51:20', '2024-02-01 17:51:20'),
(3, 'The management reserves the right to charge you for any delayed staty or if you have incurred loss to property or for any missing items from the room even after your departure.', 1, NULL, NULL, '2024-02-01 17:51:20', '2024-02-01 17:51:20'),
(4, 'Guests are requested to leave money or any valuables in safety locker in the room.', 1, NULL, NULL, '2024-02-01 17:51:20', '2024-02-01 17:51:20'),
(5, 'You will be responsible for any damage done by yourself or guest visiting in the room.', 1, NULL, NULL, '2024-02-01 17:51:20', '2024-02-01 17:51:20'),
(6, 'All guest rooms are non smoking. Smokings are allowd only at resort designated area. There is a charge of bdt 10000/- for smoking in no smoking room as penalty.', 1, NULL, NULL, '2024-02-01 17:51:20', '2024-02-01 17:51:20');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_services`
--

CREATE TABLE `hotel_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotel_service_sales`
--

CREATE TABLE `hotel_service_sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `hotel_guest_id` bigint(20) UNSIGNED DEFAULT NULL,
  `guest_name` varchar(191) DEFAULT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `invoice_date` varchar(191) DEFAULT NULL,
  `delivered_at` varchar(191) DEFAULT NULL,
  `subtotal` decimal(8,2) NOT NULL,
  `payable_amount` decimal(8,2) NOT NULL,
  `discount` decimal(8,2) NOT NULL,
  `paid_amount` decimal(8,2) NOT NULL,
  `due_source` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotel_service_sale_items`
--

CREATE TABLE `hotel_service_sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hotel_service_sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hotel_service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(8,2) NOT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotel_transaction_ledgers`
--

CREATE TABLE `hotel_transaction_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `hotel_transaction_id` bigint(20) UNSIGNED NOT NULL,
  `date` varchar(191) NOT NULL,
  `source_id` bigint(20) UNSIGNED NOT NULL,
  `source_type` varchar(191) NOT NULL,
  `in` decimal(8,2) DEFAULT 0.00,
  `out` decimal(8,2) DEFAULT 0.00,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_type` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_transaction_ledgers`
--

INSERT INTO `hotel_transaction_ledgers` (`id`, `created_by`, `updated_by`, `hotel_transaction_id`, `date`, `source_id`, `source_type`, `in`, `out`, `remarks`, `created_at`, `datetime`, `updated_at`, `payment_type`) VALUES
(33, 4, 4, 36, '2024-02-29', 36, 'Booking', 5000.00, 0.00, 'Booking', '2024-02-29 09:16:28', '2024-02-29 15:16:28', '2024-02-29 09:16:28', 1),
(40, 4, 4, 43, '2024-02-29', 43, 'Booking', 0.00, 0.00, 'Booking', '2024-02-29 09:57:53', '2024-02-29 15:57:53', '2024-02-29 09:57:53', NULL),
(44, 4, 4, 47, '2024-02-29', 47, 'Booking', 50.00, 0.00, 'Booking', '2024-02-29 10:43:07', '2024-02-29 16:43:07', '2024-02-29 10:43:07', 1),
(45, 4, 4, 47, '2024-02-29', 47, 'Booking', 231.50, 0.00, 'Booking', '2024-02-29 10:52:51', '2024-02-29 16:52:51', '2024-02-29 10:52:51', 1),
(46, 4, 4, 47, '2024-02-29', 47, 'Booking', 50.00, 0.00, 'Booking', '2024-02-29 10:53:49', '2024-02-29 16:53:49', '2024-02-29 10:53:49', 1),
(47, 4, 4, 47, '2024-02-29', 47, 'Booking', 50.00, 0.00, 'Booking', '2024-02-29 10:56:26', '2024-02-29 16:56:26', '2024-02-29 10:56:26', 1),
(48, 4, 4, 48, '2024-02-29', 48, 'Booking', 50.00, 0.00, 'Booking', '2024-02-29 11:08:51', '2024-02-29 17:08:51', '2024-02-29 11:08:51', 1),
(49, 4, 4, 48, '2024-02-29', 48, 'Booking', 731.77, 0.00, 'Check Out', '2024-02-29 11:15:11', '2024-02-29 17:15:11', '2024-02-29 11:15:11', 1),
(50, 4, 4, 47, '2024-02-29', 47, 'Booking', 181.50, 0.00, 'Check Out', '2024-02-29 11:17:12', '2024-02-29 17:17:12', '2024-02-29 11:17:12', NULL),
(51, 4, 4, 47, '2024-02-29', 47, 'Booking', 181.50, 0.00, 'Check Out', '2024-02-29 11:19:53', '2024-02-29 17:19:53', '2024-02-29 11:19:53', NULL),
(52, 4, 4, 47, '2024-02-29', 47, 'Booking', 181.50, 0.00, 'Check Out', '2024-02-29 11:20:13', '2024-02-29 17:20:13', '2024-02-29 11:20:13', NULL),
(53, 4, 4, 49, '2024-02-29', 49, 'Booking', 0.00, 0.00, 'Booking', '2024-02-29 11:32:38', '2024-02-29 17:32:38', '2024-02-29 11:32:38', NULL),
(54, 4, 4, 49, '2024-02-29', 49, 'Booking', 1000.00, 0.00, 'Booking', '2024-02-29 11:35:18', '2024-02-29 17:35:18', '2024-02-29 11:35:18', 1),
(55, 4, 4, 49, '2024-02-29', 49, 'Booking', 897.50, 0.00, 'Check Out', '2024-02-29 11:36:39', '2024-02-29 17:36:39', '2024-02-29 11:36:39', 1),
(56, 4, 4, 50, '2024-02-29', 50, 'Booking', 925.98, 0.00, 'Booking', '2024-02-29 12:02:32', '2024-02-29 18:02:32', '2024-02-29 12:02:32', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hotel_vat`
--

CREATE TABLE `hotel_vat` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hotel_vat` bigint(20) UNSIGNED DEFAULT NULL,
  `resturent_vat` bigint(20) UNSIGNED NOT NULL,
  `bar_vat` tinyint(4) NOT NULL DEFAULT 0,
  `vat_number` varchar(191) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `room_service_charge` decimal(6,2) DEFAULT NULL,
  `room_rate` decimal(16,2) NOT NULL DEFAULT 0.00,
  `rst_service_charge` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_vat`
--

INSERT INTO `hotel_vat` (`id`, `hotel_vat`, `resturent_vat`, `bar_vat`, `vat_number`, `created_by`, `updated_by`, `created_at`, `updated_at`, `room_service_charge`, `room_rate`, `rst_service_charge`) VALUES
(1, 15, 10, 0, NULL, 1, NULL, '2024-02-01 17:51:20', '2024-02-01 17:51:20', 10.00, 126.50, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `id_card_settings`
--

CREATE TABLE `id_card_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `logo` varchar(191) DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `address` longtext DEFAULT NULL,
  `web_url` varchar(191) DEFAULT NULL,
  `mobile` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `signature` varchar(191) DEFAULT NULL,
  `issue_date` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `corporate_address` varchar(191) DEFAULT NULL,
  `group_logo` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `image_store_guests`
--

CREATE TABLE `image_store_guests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `guest_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `booking_number` varchar(191) DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inovoice_no`
--

CREATE TABLE `inovoice_no` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) DEFAULT NULL,
  `year` date DEFAULT NULL,
  `next_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_generate`
--

CREATE TABLE `invoice_generate` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `next_id` bigint(20) NOT NULL,
  `type` varchar(191) NOT NULL,
  `year` varchar(191) NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_generate`
--

INSERT INTO `invoice_generate` (`id`, `next_id`, `type`, `year`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Restaurant Sale', '2024-02', NULL, NULL, '2024-02-04 12:16:35', '2024-02-04 12:16:35'),
(2, 1, 'Bar Sale', '2024-02', NULL, NULL, '2024-02-06 12:56:44', '2024-02-06 12:56:44'),
(3, 1, 'Stock Adjust', '2024-02', NULL, NULL, '2024-02-12 05:44:26', '2024-02-12 05:44:26'),
(4, 15, 'Booking', '2024-02', NULL, NULL, '2024-02-24 05:35:57', '2024-02-29 12:02:32');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_nos`
--

CREATE TABLE `invoice_nos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) NOT NULL,
  `year` varchar(191) NOT NULL,
  `next_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_numbers`
--

CREATE TABLE `invoice_numbers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) NOT NULL,
  `year` varchar(191) DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `buyer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `others` varchar(191) DEFAULT NULL,
  `next_id` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `item_unit_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `opening_balance` int(11) NOT NULL DEFAULT 0,
  `rate` decimal(20,2) NOT NULL DEFAULT 0.00,
  `remaining_quantity` int(11) DEFAULT 0,
  `current_stock` int(11) DEFAULT 0,
  `average_rate` decimal(15,3) DEFAULT 0.000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_units`
--

CREATE TABLE `item_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `conversion` varchar(191) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kitchen_orders`
--

CREATE TABLE `kitchen_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `ticket_no` varchar(191) DEFAULT NULL,
  `customer_id` varchar(191) DEFAULT NULL,
  `customer_name` varchar(191) DEFAULT NULL,
  `table_no` varchar(191) DEFAULT NULL,
  `waiter_no` varchar(191) DEFAULT NULL,
  `total_qty` double DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `note` varchar(191) DEFAULT NULL,
  `order_status` varchar(191) DEFAULT 'Pending',
  `approve_time` date DEFAULT NULL,
  `cancel_time` date DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0,
  `date` date DEFAULT NULL,
  `is_bar` tinyint(4) DEFAULT 0,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `canceled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kitchen_order_details`
--

CREATE TABLE `kitchen_order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_name` varchar(191) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `qty` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `is_bar` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mail_templates`
--

CREATE TABLE `mail_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(191) DEFAULT NULL,
  `body` varchar(191) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_09_12_145555_create_countries_table', 1),
(5, '2019_09_13_121434_create_business_types_table', 1),
(6, '2019_09_14_050539_create_groups_table', 1),
(7, '2019_09_14_050541_create_companies_table', 1),
(8, '2019_09_14_050641_create_company_details_table', 1),
(9, '2019_09_14_050642_create_company_bank_accounts_table', 1),
(10, '2019_09_14_051636_create_modules_table', 1),
(11, '2019_09_14_051637_create_submodules_table', 1),
(12, '2019_09_14_051638_create_parent_permissions_table', 1),
(13, '2019_09_14_051639_create_permissions_table', 1),
(14, '2019_09_22_151458_create_global_infos_table', 1),
(15, '2019_10_04_155219_create_supplier_types_table', 1),
(16, '2019_10_05_124910_create_suppliers_table', 1),
(17, '2019_10_27_153854_create_currencies_table', 1),
(18, '2019_10_27_154945_create_buyers_table', 1),
(19, '2019_11_24_175729_create_company_user_table', 1),
(20, '2019_11_24_175742_create_permission_user_table', 1),
(21, '2019_12_04_154447_create_department_user_table', 1),
(22, '2019_12_04_154505_create_designation_user_table', 1),
(23, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(24, '2020_01_23_155627_create_order_types_table', 1),
(25, '2020_01_23_161847_create_seasons_table', 1),
(26, '2020_02_19_100952_create_buyer_uploads_table', 1),
(27, '2020_03_05_153145_create_permission_features_table', 1),
(28, '2020_03_07_144351_create_basic_rate_setups_table', 1),
(29, '2020_03_07_151524_add_header_footer_to_company_details_table', 1),
(30, '2020_03_09_112143_add_api_token_to_users_table', 1),
(31, '2020_03_18_132112_add_status_column_to_permissions_table', 1),
(32, '2020_03_24_181908_create_currency_conversions_table', 1),
(33, '2020_04_13_213507_create_task_notifications_table', 1),
(34, '2020_04_15_133719_create_sms_apis_table', 1),
(35, '2020_09_26_164240_create_system_settings_table', 1),
(36, '2020_12_13_150701_add_buyer_type_column_to_buyers_table', 1),
(37, '2020_12_21_151230_create_notifications_table', 1),
(38, '2020_12_26_225313_create_invoice_numbers_table', 1),
(39, '2021_01_16_171449_create_payment_schedules_table', 1),
(40, '2021_02_10_164143_add_employee_to_notifications_table', 1),
(41, '2021_02_22_190015_add_password_reset_token_to_users_table', 1),
(42, '2021_02_23_102110_create_user_login_statuses_table', 1),
(43, '2021_04_07_120927_add_fav_icon_to_groups_table', 1),
(44, '2021_04_29_172448_add_employee_full_id_column_to_users_table', 1),
(45, '2021_05_01_174928_add_organogram_column_to_companies_table', 1),
(46, '2021_05_02_091650_add_device_token_field_to_users_table', 1),
(47, '2021_05_02_142314_make_email_nullable_to_users_table', 1),
(48, '2021_06_12_123007_add_image_column_to_groups_table', 1),
(49, '2021_06_29_174454_create_id_card_settings_table', 1),
(50, '2021_07_01_112949_add_business_type_column_to_companies_table', 1),
(51, '2021_07_01_114818_create_user_credentials_table', 1),
(52, '2021_07_08_155345_add_group_info_to_id_card_settings_table', 1),
(53, '2021_09_13_094227_add_rank_field_to_modules_table', 1),
(54, '2021_10_12_172323_create_districts_table', 1),
(55, '2022_10_13_121742_add_pack_size_to_rst_products_table', 1),
(56, '2022_10_16_121125_change_phone_number_column_to_users_table', 1),
(57, '2022_11_12_102334_create_sessions_table', 1),
(58, '2022_11_19_111666_add_icon_column_to_currency_conversions_table', 1),
(59, '2022_11_20_121115_rename_effective_month_column_in_currency_conversions_table', 1),
(60, '2023_10_26_110341_create_activity_logs_table', 1),
(61, '2023_11_20_122439_change_storage_engine_for_all_tables', 1),
(62, '2019_02_20_124451_create_bar_product_categories_table', 2),
(63, '2019_02_20_124451_create_product_categories_table', 2),
(64, '2019_02_20_152130_create_bar_product_brands_table', 2),
(65, '2019_02_20_152130_create_product_brands_table', 2),
(66, '2019_02_20_161349_create_bar_product_units_table', 2),
(67, '2019_02_20_161349_create_product_units_table', 2),
(68, '2019_02_20_172520_create_bar_suppliers_table', 2),
(69, '2019_02_20_172520_create_rst_suppliers_table', 2),
(70, '2019_02_20_185407_create_bar_products_table', 2),
(71, '2019_02_20_185407_create_rst_products_table', 2),
(72, '2019_04_11_162508_create_bar_purchases_table', 2),
(73, '2019_04_11_162508_create_rst_purchases_table', 2),
(74, '2019_04_20_113137_create_bar_sales_table', 2),
(75, '2019_04_20_113137_create_sales_table', 2),
(76, '2019_04_23_132856_create_bar_sale_items_table', 2),
(77, '2019_04_23_132856_create_sale_items_table', 2),
(78, '2019_10_01_154802_create_item_units_table', 2),
(79, '2019_10_01_171849_create_items_table', 2),
(80, '2019_10_02_173645_create_purchases_table', 2),
(81, '2019_10_02_174018_create_purchase_details_table', 2),
(82, '2019_10_05_125049_create_purchase_receives_table', 2),
(83, '2019_10_05_125106_create_purchase_receive_details_table', 2),
(84, '2019_10_07_110849_create_goods_requisitions_table', 2),
(85, '2019_10_07_111115_create_goods_requisition_details_table', 2),
(86, '2019_11_06_104149_create_stocks_table', 2),
(87, '2020_01_08_171021_add_remaining_quantity_to_purchase_receive_details', 2),
(88, '2020_01_19_162707_add_price_to_goods_requisition_details_table', 2),
(89, '2020_01_19_162805_create_stock_trackings_table', 2),
(90, '2020_01_19_171626_add_remaining_quantity_to_items_table', 2),
(91, '2020_01_27_105439_add_current_stock_columnt_to_items_table', 2),
(92, '2020_01_27_120915_add_average_rate_columnt_to_items_table', 2),
(93, '2020_12_26_225313_create_invoice_nos_table', 2),
(94, '2020_12_27_224815_create_account_groups_table', 2),
(95, '2020_12_27_225103_create_account_setups_table', 2),
(96, '2020_12_27_225137_create_account_controls_table', 2),
(97, '2020_12_27_225151_create_account_subsidiaries_table', 2),
(98, '2020_12_27_225203_create_accounts_table', 2),
(99, '2020_12_27_225204_create_account_customers_table', 2),
(100, '2020_12_27_225205_create_acc_suppliers_table', 2),
(101, '2020_12_27_225214_create_banks_table', 2),
(102, '2020_12_27_225250_create_transactions_table', 2),
(103, '2020_12_27_225305_create_vouchers_table', 2),
(104, '2020_12_27_225313_create_voucher_details_table', 2),
(105, '2020_12_28_225305_create_fund_transfers_table', 2),
(106, '2021_02_24_225305_create_units_table', 2),
(107, '2021_02_24_225306_create_acc_categories_table', 2),
(108, '2021_02_24_225307_create_products_table', 2),
(109, '2021_02_24_225308_create_acc_products_table', 2),
(110, '2021_02_24_225310_create_acc_sales_table', 2),
(111, '2021_02_24_225311_create_acc_sale_details_table', 2),
(112, '2021_02_24_225312_create_acc_purchases_table', 2),
(113, '2021_02_24_225313_create_acc_purchase_details_table', 2),
(114, '2021_02_24_225314_create_acc_collections_table', 2),
(115, '2021_02_24_225315_create_acc_payments_table', 2),
(116, '2021_08_24_162335_create_project_names_table', 2),
(117, '2021_09_14_182348_create_product_stock_transections_table', 2),
(118, '2021_09_26_172730_create_requsition_stocks_table', 2),
(119, '2021_10_13_174342_create_c_r_m_customers_table', 2),
(120, '2021_10_16_095945_create_mail_templates_table', 2),
(121, '2021_10_16_165059_create_hotel_services_table', 2),
(122, '2021_10_16_165106_create_hotel_service_sales_table', 2),
(123, '2021_10_16_165111_create_hotel_service_sale_items_table', 2),
(124, '2021_10_17_112306_create_projects_table', 2),
(125, '2021_10_17_112321_create_project_details_table', 2),
(126, '2021_10_17_112332_create_project_billings_table', 2),
(127, '2021_10_18_094357_create_emails_table', 2),
(128, '2021_10_19_124721_create_customer_ledgers_table', 2),
(129, '2021_10_19_124730_create_supplier_ledgers_table', 2),
(130, '2021_10_30_173821_create_bar_product_stocks_table', 2),
(131, '2021_10_30_173821_create_rst_product_stocks_table', 2),
(132, '2021_10_31_101206_add_chalan_id_on_bar_purchases_tables', 2),
(133, '2021_10_31_101206_add_chalan_id_on_tables', 2),
(134, '2021_10_31_130458_create_acc_stocks_table', 2),
(135, '2021_10_31_130459_create_acc_stock_summaries_table', 2),
(136, '2021_10_31_144323_create_bar_purchase_details_table', 2),
(137, '2021_10_31_144323_create_rst_purchase_details_table', 2),
(138, '2021_10_31_808293_create_bar_product_ledgers_table', 2),
(139, '2021_10_31_808293_create_product_ledgers_table', 2),
(140, '2021_11_08_101238_add_vat_on_bar_purchase_details_table', 2),
(141, '2021_11_08_101238_add_vat_on_rst_purchase_details_table', 2),
(142, '2021_11_10_123509_add_company_factory_field_to_requsition_stocks_table', 2),
(143, '2021_11_14_134310_add_bar_payable_amount_on_bar_sales_table', 2),
(144, '2021_11_14_134310_add_payable_amount_on_rst_sales_table', 2),
(145, '2021_11_20_181759_add_branch_id_to_requsition_stocks_table', 2),
(146, '2021_11_29_180840_create_bar_sale_returns_table', 2),
(147, '2021_11_29_180840_create_rst_sale_returns_table', 2),
(148, '2021_11_29_181335_create_bar_sale_return_details_table', 2),
(149, '2021_11_29_181335_create_rst_sale_return_details_table', 2),
(150, '2021_11_30_163632_add_sale_return_quantity_to_bar_stocks_table', 2),
(151, '2021_11_30_163632_add_sale_return_quantity_to_rst_stocks_table', 2),
(152, '2021_12_04_101846_create_inovoice_no_table', 2),
(153, '2021_12_04_102903_create_bar_product_uploads_table', 2),
(154, '2021_12_04_102903_create_rst_product_uploads_table', 2),
(155, '2021_12_04_153404_create_payment_type_table', 2),
(156, '2021_12_06_161721_create_room_category_table', 2),
(157, '2021_12_06_161743_create_rooms_table', 2),
(158, '2021_12_06_163904_create_room_aminities_table', 2),
(159, '2021_12_06_163931_create_hotel_guest_table', 2),
(160, '2021_12_06_170239_create_booking_table', 2),
(161, '2021_12_06_170255_create_booking_details_table', 2),
(162, '2021_12_07_110329_create_room_photos_table', 2),
(163, '2021_12_14_123638_add_supplier_id_to_bar_purchases_table', 2),
(164, '2021_12_14_123638_add_supplier_id_to_rst_purchases_table', 2),
(165, '2021_12_14_152214_create_hotel_vat_table', 2),
(166, '2021_12_14_175225_create_hotel_account_transection', 2),
(167, '2021_12_14_181637_create_hotel_account_type_table', 2),
(168, '2021_12_15_104447_create_invoice_generate_table', 2),
(169, '2021_12_18_115811_add_advance_payment_to_booking_table', 2),
(170, '2021_12_18_121558_update_column_to_hotel_services', 2),
(171, '2021_12_18_130051_rename_column_to_bar_sales_table', 2),
(172, '2021_12_18_130051_rename_column_to_rst_sales_table', 2),
(173, '2021_12_18_134424_add_year_to_invoice_generate_table', 2),
(174, '2021_12_18_182205_add_booking_id_to_hotel_guests_table', 2),
(175, '2021_12_18_182217_add_booking_id_to_hotel_account_transactions_table', 2),
(176, '2021_12_19_100733_add_booking_id_to_hotel_service_sales_table', 2),
(177, '2021_12_21_132134_add_night_count_to_booking_details_table', 2),
(178, '2021_12_22_105559_add_booking_id_to_bar_sales_table', 2),
(179, '2021_12_22_105559_add_booking_id_to_rst_sales_table', 2),
(180, '2021_12_26_153723_change_booking_id_to_hotel_account_transaction_table', 2),
(181, '2021_12_28_121647_create_hotel_banners_table', 2),
(182, '2021_12_28_152316_create_hotel_features_table', 2),
(183, '2021_12_28_152334_create_hotel_feature_lists_table', 2),
(184, '2021_12_29_170539_create_about_sections_table', 2),
(185, '2021_12_29_172050_create_our_services_table', 2),
(186, '2021_12_29_172108_create_our_service_lists_table', 2),
(187, '2021_12_30_112145_add_more_column_to_bar_sales_table', 2),
(188, '2021_12_30_112145_add_more_column_to_rst_sales_table', 2),
(189, '2021_12_30_145440_add_vat_number_to_hotel_vat_table', 2),
(190, '2021_12_30_151410_create_website_settings_table', 2),
(191, '2022_01_01_150327_add_url_slug_to_room_categories_table', 2),
(192, '2022_01_01_170209_create_hotel_galleries_table', 2),
(193, '2022_01_02_131635_create_privacy_policies_table', 2),
(194, '2022_01_02_180530_add_more_column_to_room_categories_table', 2),
(195, '2022_01_03_192519_add_aminities_icon_to_room_aminities_table', 2),
(196, '2022_01_04_182214_change_location_map_to_website_settings_table', 2),
(197, '2022_01_26_115444_create_account_opening_balances_table', 2),
(198, '2022_02_06_123254_create_booking_carts_table', 2),
(199, '2022_02_06_182609_add_guest_name_to_hotel_service_sales_table', 2),
(200, '2022_02_06_183932_update_status_column_to_booking_table', 2),
(201, '2022_02_07_102510_add_payment_way_to_booking_table', 2),
(202, '2022_02_14_151700_set_nullable_amount_to_transactions_table', 2),
(203, '2022_03_02_190217_rename_hotel_transactions_table', 2),
(204, '2022_03_02_190422_add_date_to_hotel_account_transactions_table', 2),
(205, '2022_03_10_123423_create_rmreports_table', 2),
(206, '2022_03_12_111942_add_return_qty_to_bar_product_stocks_table', 2),
(207, '2022_03_12_111942_add_return_qty_to_rst_product_stocks_table', 2),
(208, '2022_03_12_152911_add_allow_breakfast_to_booking_details_table', 2),
(209, '2022_03_12_153130_add_invoice_to_hotel_account_transactions_table', 2),
(210, '2022_03_13_160646_create_booking_adjusts_table', 2),
(211, '2022_03_13_164904_create_night_audit_summaries_table', 2),
(212, '2022_03_15_115949_create_hotel_transaction_ledgers_table', 2),
(213, '2022_03_15_125920_create_booking_date_details_table', 2),
(214, '2022_03_20_181759_add_branch_id_to_rmreports_table', 2),
(215, '2022_03_21_165502_add_column_to_hotel_transaction_ledgers_table', 2),
(216, '2022_03_22_103232_update_hotel_booking_related_all_tables', 2),
(217, '2022_04_05_114144_create_booking_guest_details_table', 2),
(218, '2022_04_11_102338_create_night_audit_details_table', 2),
(219, '2022_04_12_104030_create_booking_member_details_table', 2),
(220, '2022_04_16_143908_create_sale_returns_table', 2),
(221, '2022_04_16_143909_create_sale_exchange_details_table', 2),
(222, '2022_04_16_143909_create_sale_return_details_table', 2),
(223, '2022_04_17_143908_create_damages_table', 2),
(224, '2022_04_17_143908_create_purchase_returns_table', 2),
(225, '2022_04_17_143909_create_damage_details_table', 2),
(226, '2022_04_17_143909_create_purchase_exchange_details_table', 2),
(227, '2022_04_17_143909_create_purchase_return_details_table', 2),
(228, '2022_05_25_120630_add_vat_amount_to_booking_table', 2),
(229, '2022_06_02_123509_set_company_nullable_to_invoice_nos_table', 2),
(230, '2022_06_06_104353_add_description_into_acc_products_table', 2),
(231, '2022_06_06_104353_add_description_into_acc_purchase_details_table', 2),
(232, '2022_06_06_104353_add_description_into_acc_sale_details_table', 2),
(233, '2022_09_25_153134_add_source_columns_in_acc_purchases_table', 2),
(234, '2022_09_29_145459_add_barcode_column_to_bar_products_table', 2),
(235, '2022_09_29_145459_add_barcode_column_to_rst_products_table', 2),
(236, '2022_09_29_162417_add_vat_amount_column_to_bar_products_table', 2),
(237, '2022_09_29_162417_add_vat_amount_column_to_rst_products_table', 2),
(238, '2022_09_29_171439_add_frid_card_to_rooms_table', 2),
(239, '2022_10_02_052511_create_rst_table_manages_table', 2),
(240, '2022_10_02_063338_add_type_column_to_rst_product_units_table', 2),
(241, '2022_10_02_065102_add_more_column_to_rst_products_table', 2),
(242, '2022_10_04_042930_drop_source_type_type_from_hotel_account_transactions_table', 2),
(243, '2022_10_04_063124_create_night_audit_room_details_table', 2),
(244, '2022_10_04_065755_add_more_column_to_night_audit_summaries_table', 2),
(245, '2022_10_09_041627_add_bar_vat_to_hotel_vat_table', 2),
(246, '2022_10_10_033750_add_column_to_rst_tables', 2),
(247, '2022_10_10_054749_add_payment_status_to_rst_sales_table', 2),
(248, '2022_10_10_060827_add_waiter_no_into_rst_sales_table', 2),
(249, '2022_10_10_061112_add_unit_id_to_rst_sale_details_table', 2),
(250, '2022_10_10_061238_add_soft_delete_to_rst_sales_table', 2),
(251, '2022_10_10_062307_add_vat_amount_column_to_rst_sale_items_table', 2),
(252, '2022_10_10_104621_add_table_id_to_rst_sales_table', 2),
(253, '2022_10_11_052511_create_if_not_exists_rst_table_manages_table', 2),
(254, '2022_10_12_102203_add_date_to_rooms_table', 2),
(255, '2022_10_13_035357_create_room_logs_table', 2),
(256, '2022_10_23_155459_add_room_service_charge_to_hotel_vat_table', 2),
(257, '2022_10_23_160657_add_service_charge_column_to_booking_table', 2),
(258, '2022_10_23_163821_add_passport_expiry_date_column_to_hotel_guest_table', 2),
(259, '2022_10_24_163745_add_more_column_to_hotel_account_transactions_table', 2),
(260, '2022_10_27_171843_change_payable_amount_to_rst_sales_table', 2),
(261, '2022_10_27_171863_update_payable_amount_to_rst_sales_table', 2),
(262, '2022_10_30_154550_add_status_column_to_booking_date_details_table', 2),
(263, '2022_10_30_163210_add_is_bar_column_to_hotel_guests_table', 2),
(264, '2022_10_31_035357_create_room_prices_table', 2),
(265, '2022_10_31_151516_add_allow_guest_wise_price_to_room_categories_table', 2),
(266, '2022_10_31_173520_add_parent_id_to_rst_product_categories_table', 2),
(267, '2022_11_01_095742_add_more_columns_to_booking_carts_table', 2),
(268, '2022_11_01_163901_change_booking_date_details_relation_on_delete', 2),
(269, '2022_11_01_164000_change_booking_date_details_relation_on_delete_cascade', 2),
(270, '2022_11_05_122621_drop_hotel_transaction_id_to_hotel_transaction_ledgers_table', 2),
(271, '2022_11_05_122625_update_hotel_transaction_relation_to_hotel_transaction_ledgers_table', 2),
(272, '2022_11_06_170943_add_small_unit_id_to_rst_sale_items_table', 2),
(273, '2022_11_06_171530_add_small_quantity_to_rst_sale_items_table', 2),
(274, '2022_11_07_123741_drop_transaction_id_to_night_audit_details_table', 2),
(275, '2022_11_07_123840_update_transaction_id_relation_to_night_audit_details_table', 2),
(276, '2022_11_07_125400_drop_available_quantity_to_rst_product_stocks_table', 2),
(277, '2022_11_07_125431_virtualise_available_quantity_to_rst_product_stocks_table', 2),
(278, '2022_11_10_163931_create_booking_notes_table', 2),
(279, '2022_11_10_165502_update_column_to_about_sections_table', 2),
(280, '2022_11_12_125620_add_service_charge_to_booking_details_table', 2),
(281, '2022_11_12_165451_drop_due_amount_to_hotel_account_transactions_table', 2),
(282, '2022_11_12_165516_add_due_amount_to_hotel_account_transactions_table', 2),
(283, '2022_11_14_111803_update_booking_status_to_booking_table', 2),
(284, '2022_11_14_192833_set_booking_detail_id_null_to_booking_date_details_table', 2),
(285, '2022_11_16_102203_add_smoking_status_to_rooms_table', 2),
(286, '2022_11_16_163210_add_columns_to_hotel_guests_table', 2),
(287, '2022_11_16_173217_add_columns_to_booking_table', 2),
(288, '2022_11_16_173225_drop_columns_to_hotel_guests_table', 2),
(289, '2022_11_16_183210_add_more_columns_to_hotel_guests_table', 2),
(290, '2022_11_19_095214_add_extra_charge_field_to_hotel_account_transactions_table', 2),
(291, '2022_11_19_122556_add_current_room_rate_to_booking_details_table', 2),
(292, '2022_11_21_163010_add_columns_to_hotel_account_transactions_table', 2),
(293, '2022_11_23_132254_add_date_time_field_to_hotel_account_transactions_table', 2),
(294, '2022_11_23_163910_add_currency_conversion_id_to_hotel_account_transactions_table', 2),
(295, '2022_11_23_166000_create_booking_extra_charges_table', 2),
(296, '2022_11_29_133217_add_type_column_to_booking_table', 2),
(297, '2022_11_29_163931_create_hotel_guest_registration_terms_table', 2),
(298, '2022_11_30_153217_change_type_column_name_to_book_type_booking_table', 2),
(299, '2022_12_06_111208_drop_current_room_rate_to_booking_details_table', 2),
(300, '2022_12_06_111351_add_current_room_ratte_to_booking_details_table', 2),
(301, '2022_12_06_123448_add_booking_detail_id_to_booking_adjusts_table', 2),
(302, '2022_12_06_133217_add_booking_pax_column_to_booking_table', 2),
(303, '2023_01_02_182415_add_change_amount_to_hotel_account_transactions_table', 2),
(304, '2023_01_09_163950_create_hotel_customer_ledgers_table', 2),
(305, '2023_01_11_202833_add_columns_to_hotel_customer_ledgers_table', 2),
(306, '2023_01_12_181637_add_account_id_to_hotel_account_type_table', 2),
(307, '2023_01_23_123449_add_booking_detail_id_column_to_booking_adjusts_table', 2),
(308, '2023_01_25_125440_hotel_account_type_id_migrate_to_hotel_account_type_table', 2),
(309, '2023_01_31_174525_add_audit_date_to_rst_product_ledgers_table', 2),
(310, '2023_02_02_112013_create_night_audit_transactions_table', 2),
(311, '2023_02_02_112555_add_transaction_ledger_id_to_night_audit_details_table', 2),
(312, '2023_02_02_122758_add_datetime_to_hotel_transaction_ledgers_table', 2),
(313, '2023_03_02_182605_add_total_collection_to_night_audit_details_table', 2),
(314, '2023_03_04_094228_add_total_amount_to_night_audit_details_table', 2),
(315, '2023_04_16_094228_add_image_to_hotel_guest_table', 2),
(316, '2023_05_01_014228_add_two_more_fields_to_hotel_guest_table', 2),
(317, '2023_05_01_015228_add_two_more_fields_to_booking_table', 2),
(318, '2023_05_01_016228_add_room_discount_to_booking_details_table', 2),
(319, '2023_05_09_011228_add_age_to_booking_member_details_table', 2),
(320, '2023_05_11_103950_create_hotel_booking_purpose_table', 2),
(321, '2023_05_11_112228_add_purpose_and_platform_to_booking_table', 2),
(322, '2023_05_18_101635_create_website_pages_table', 2),
(323, '2023_06_08_052511_create_rst_product_package_table', 2),
(324, '2023_06_08_072511_create_rst_product_package_details_table', 2),
(325, '2023_06_08_101530_add_package_id_to_rst_products_table', 2),
(326, '2023_07_10_173820_add_discount_type_colum_to_booking_details_table', 2),
(327, '2023_07_11_123722_add_discount_colum_to_rst_sale_items_table', 2),
(328, '2023_07_13_120342_add_rst_sale_id_colum_to_rst_sale_returns_table', 2),
(329, '2023_07_13_122453_add_item_discount_colum_to_rst_sale_return_details_table', 2),
(330, '2023_07_17_162057_add_booking_sale_id_colum_to_rst_sale_table', 2),
(331, '2023_07_19_160139_create_stock_adjustments_table', 2),
(332, '2023_07_19_161232_create_stock_adjustment_details_table', 2),
(333, '2023_07_27_143715_create_kitchen_orders_table', 2),
(334, '2023_07_27_144525_create_kitchen_order_details_table', 2),
(335, '2023_08_09_172700_change_booking_date_details_colum_type_to_booking_date_details', 2),
(336, '2023_08_12_123702_add_priceing_related_colum_to_rooms', 2),
(337, '2023_08_14_152709_add_is_room_in_invoice_colum_to_booking', 2),
(338, '2023_08_28_101753_vartual_colum_to_hotel_account_transactions_table', 2),
(339, '2023_08_28_150751_add_extra_note_colum_to_room_logs_table', 2),
(340, '2023_09_06_130253_create_rest_material_units_table', 2),
(341, '2023_09_06_130352_create_rest_materials_table', 2),
(342, '2023_09_09_124341_rst_material_purchase', 2),
(343, '2023_09_09_124442_rst_material_purchase_details', 2),
(344, '2023_09_17_110319_add_colum_to_night_audit_summaries_table', 2),
(345, '2023_09_17_152047_add_company_id_colum_to_booking_table', 2),
(346, '2023_09_18_120824_add_is_matrial_colum_to_rst_products_table', 2),
(347, '2023_09_19_144933_add_pay_by_colum_to_booking_table', 2),
(348, '2023_09_26_104047_create_rst_productions_table', 2),
(349, '2023_09_26_104847_create_rst_metrial_details_table', 2),
(350, '2023_09_26_104904_create_rst_finish_good_details_table', 2),
(351, '2023_10_03_181833_add_relation_colum_to_booking_member_details_table', 2),
(352, '2023_10_11_184930_create_image_store_guests_table', 2),
(353, '2023_10_15_164451_add_nullable_colum_in_image_store_guests_table', 2),
(354, '2023_10_29_175716_create_product_metrials_table', 2),
(355, '2023_11_30_181209_change_foreign_key_colum_to_kitchen_order_details_table', 2),
(356, '2023_12_28_100305_add_room_rate_colum_to_hotel_vat_table', 2),
(357, '2024_01_01_170419_add_some_pax_colum_to_booking_table', 2),
(358, '2024_01_03_154059_remove_colum_to_rst_product_uploads', 2),
(359, '2024_01_03_161108_add_colum_to_rst_product_uploads', 2),
(360, '2024_01_08_131359_add_column_allow_breakfast_qty_to_booking_details', 2),
(361, '2024_01_08_162611_add_column_card_info_to_booking', 2),
(362, '2024_01_10_155441_add_rst_service_charge_colum_to_hotel_vat', 2),
(363, '2024_01_10_170844_card_authorized_information_payment_collection', 2),
(364, '2024_01_22_104911_add_is_stuff_colum_to_hotel_guest_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  `is_migrate` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `rank` int(11) DEFAULT 999
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`, `status`, `is_migrate`, `created_at`, `updated_at`, `rank`) VALUES
(1, 'Global Setting', 1, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(2, 'User Access', 1, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 2),
(3, 'HRM', 0, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 3),
(4, 'General Store', 1, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 999),
(5, 'Merchandising', 0, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 999),
(6, 'Inventory', 0, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 999),
(7, 'Commercial', 0, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 999),
(8, 'News & Events', 0, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 999),
(9, 'Payment', 0, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 999),
(11, 'Employee Permission', 1, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 999),
(12, 'Knitting & Dyeing', 0, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 999),
(100001, 'Hospital', 0, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 999),
(110001, 'Pharmacy', 0, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 999),
(120001, 'HotelService', 1, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 6),
(130001, 'Restaurant', 1, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 7),
(150000, 'Account & Finance', 1, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 10),
(160000, 'Hotel', 1, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 5),
(160001, 'HotelWebsite', 1, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 4),
(170000, 'CRM', 1, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 11),
(200000, 'BanquetHall', 0, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 9),
(270000, 'Bar', 1, 0, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 8);

-- --------------------------------------------------------

--
-- Table structure for table `night_audit_details`
--

CREATE TABLE `night_audit_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `audit_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `transaction_ledger_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_amount` decimal(8,2) DEFAULT 0.00,
  `collection` decimal(8,2) DEFAULT 0.00,
  `due` decimal(8,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `night_audit_room_details`
--

CREATE TABLE `night_audit_room_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `audit_id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `night_audit_summaries`
--

CREATE TABLE `night_audit_summaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `date` varchar(191) NOT NULL,
  `collection` decimal(20,3) NOT NULL,
  `due_amount` decimal(20,3) NOT NULL DEFAULT 0.000,
  `total_check_in` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `total_check_out` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `total_reservation` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `total_cancelled` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `total_room` bigint(20) NOT NULL,
  `total_room_maintenance` bigint(20) NOT NULL DEFAULT 0,
  `total_booked_room` bigint(20) NOT NULL DEFAULT 0,
  `total_dirty_room` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_rest` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `night_audit_transactions`
--

CREATE TABLE `night_audit_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `audit_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_ledger_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `source_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) NOT NULL,
  `path` varchar(191) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `designation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `buyer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_types`
--

CREATE TABLE `order_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `our_services`
--

CREATE TABLE `our_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_heading` varchar(191) DEFAULT NULL,
  `service_background_img` varchar(191) DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `our_services`
--

INSERT INTO `our_services` (`id`, `service_heading`, `service_background_img`, `company_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Our Services', './assets/uploads/hotel/website/2021/Dec//1640782709.webp', 1, 1, NULL, '2024-02-01 17:51:29', '2024-02-01 17:51:29');

-- --------------------------------------------------------

--
-- Table structure for table `our_service_lists`
--

CREATE TABLE `our_service_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_title` varchar(191) DEFAULT NULL,
  `service_description` varchar(191) DEFAULT NULL,
  `service_list` varchar(191) DEFAULT NULL,
  `service_icon` varchar(191) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `our_service_lists`
--

INSERT INTO `our_service_lists` (`id`, `service_title`, `service_description`, `service_list`, `service_icon`, `status`, `company_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Stay First, Pay After!', 'Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates.', 'Decorated Room, Proper Air Conditioned, Private Balcony', 'fa fa-credit-card', 1, 1, 1, NULL, '2021-12-30 12:25:48', '2021-12-30 12:25:48'),
(2, '24 Hour Restaurant', 'Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates.', '24 hours room service, 24-hour Concierge service, 24 hour Electricity service', 'fa fa-clock-o', 1, 1, 1, 1, '2021-12-30 12:26:42', '2021-12-30 13:16:23');

-- --------------------------------------------------------

--
-- Table structure for table `parent_permissions`
--

CREATE TABLE `parent_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `submodule_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parent_permissions`
--

INSERT INTO `parent_permissions` (`id`, `name`, `submodule_id`, `created_at`, `updated_at`) VALUES
(1, 'Employee', 3, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(31, 'Item', 14, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(32, 'Purchase', 15, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(33, 'Create Requisition', 16, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(34, 'GS Report', 17, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(35, 'Group', 1, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(36, 'Company Info', 1, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(37, 'Buyer', 2, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(38, 'Supplier', 1, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(39, 'Item Unit', 2, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(43, 'Permission Access', 18, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(100, 'Currency Conversion', 1, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(118, 'Integration', 18, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(119, 'Device Api', 18, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(159, 'Id Card Setting', 1, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(160, 'Id Card Print', 3, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110002, 'Categories', 110002, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110003, 'Aminities', 110002, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110004, 'Room', 110002, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110005, 'Vat', 110002, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110006, 'Account Type', 110002, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110007, 'Hotel Booking', 110002, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110008, 'Hotel Guest', 110002, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110009, 'Homepage Banner', 110003, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110010, 'Homepage Feature', 110003, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110011, 'Our Service', 110003, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110012, 'Hotel Gallery', 110003, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110013, 'About Section', 110003, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110014, 'Privacy & Policy', 110003, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110015, 'Site Setting', 110003, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110016, 'Services', 110004, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110017, 'Sales', 110004, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110026, 'Guest', 110002, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110027, 'Night Audit', 110002, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110028, 'Report', 110002, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110044, 'Inventory Adjustment', 110007, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110045, 'HouseKeeping', 110008, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110046, 'Kitchen', 110009, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110047, 'Resturant Payment Collection', 110002, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110048, 'Booking', 110010, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110049, 'Categories', 110010, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110050, 'Aminities', 110010, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110051, 'Rooms', 110010, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110052, 'Packages', 110006, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(130001, 'Resturant Sales', 110005, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(130002, 'Resturant Purchase', 110005, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(130003, 'Resturant Inventory', 110005, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(130004, 'Resturant Table', 110005, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(130005, 'Resturant Reports', 110005, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(130006, 'Category', 110005, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(130007, 'Resturant Payment Collection', 110005, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(130008, 'Resturant Production', 110005, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(150000, 'Account Setup', 150000, '2021-11-17 15:33:03', '2021-11-17 15:33:03'),
(150001, 'Account Subsidiary', 150000, '2021-11-17 15:33:26', '2021-11-17 15:33:26'),
(150002, 'Account Chart', 150000, '2021-11-17 15:33:40', '2021-11-17 15:33:40'),
(150003, 'Payment Voucher', 150002, '2021-11-17 15:43:55', '2021-11-17 15:45:16'),
(150004, 'Receive Voucher', 150002, '2021-11-17 15:44:12', '2021-11-17 15:44:58'),
(150005, 'Contra Voucher', 150002, '2021-11-17 15:44:44', '2021-11-17 15:44:44'),
(150006, 'Journal Voucher', 150002, '2021-11-17 15:45:29', '2021-11-17 15:45:29'),
(150007, 'Account Product', 150001, '2021-11-17 15:46:59', '2021-11-17 15:46:59'),
(150008, 'Account Category', 150001, '2021-11-17 15:47:11', '2021-11-17 15:47:11'),
(150009, 'Account Unit', 150001, '2021-11-17 15:47:23', '2021-11-17 15:47:23'),
(150010, 'Account Customer', 150004, '2021-11-17 15:47:43', '2021-11-17 15:47:43'),
(150011, 'Account Supplier', 150004, '2021-11-17 15:48:00', '2021-11-17 15:48:00'),
(150012, 'Account Purchase', 150005, '2021-11-17 15:48:16', '2021-11-17 15:48:16'),
(150013, 'Account Sale', 150006, '2021-11-17 15:50:16', '2021-11-17 15:50:16'),
(150014, 'Account Ledger', 150007, '2021-11-17 15:50:41', '2021-11-17 15:50:41'),
(150015, 'Financial Report', 150007, '2021-11-17 15:51:06', '2021-11-17 15:51:06'),
(150016, 'Account Inventory', 150007, '2021-11-17 15:51:22', '2021-11-17 15:51:22'),
(170000, 'Customer Setup', 170000, '2024-02-01 17:51:49', '2024-02-01 17:51:49'),
(170001, 'Lead Generate', 170001, '2024-02-01 17:51:49', '2024-02-01 17:51:49'),
(170002, 'WorkOut', 170001, '2024-02-01 17:51:49', '2024-02-01 17:51:49'),
(170003, 'Project ', 170002, '2024-02-01 17:51:49', '2024-02-01 17:51:49'),
(170004, 'Project Bill', 170002, '2024-02-01 17:51:49', '2024-02-01 17:51:49'),
(170005, 'Project Type', 170002, '2024-02-01 17:51:49', '2024-02-01 17:51:49'),
(170006, 'Mail Template', 170003, '2024-02-01 17:51:49', '2024-02-01 17:51:49'),
(270001, 'Bar Sales', 110006, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(270002, 'Bar Purchase', 110006, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(270003, 'Bar Inventory', 110006, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(270004, 'Bar Table', 110006, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(270005, 'Bar Reports', 110006, '2024-02-01 17:49:26', '2024-02-01 17:49:26');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_type`
--

CREATE TABLE `payment_type` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `parent_permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`, `description`, `created_by`, `updated_by`, `parent_permission_id`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Employee', 'employees.index', NULL, 1, 1, 1, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(2, 'View', 'employees.view', NULL, 1, 1, 1, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(3, 'Create', 'employees.create', NULL, 1, 1, 1, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(4, 'Edit', 'employees.edit', NULL, 1, 1, 1, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(5, 'Delete', 'employees.delete', NULL, 1, 1, 1, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(6, 'Active', 'actives.index', NULL, 1, 1, 1, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(7, 'Group', 'groups.index', NULL, 1, 1, 35, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(8, 'View', 'groups.view', NULL, 1, 1, 35, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(9, 'Create', 'groups.create', NULL, 1, 1, 35, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(10, 'Edit', 'groups.edit', NULL, 1, 1, 35, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(11, 'Delete', 'groups.delete', NULL, 1, 1, 35, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(12, 'Company Info', 'company.infos.index', NULL, 1, 1, 36, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(13, 'View', 'company.infos.view', NULL, 1, 1, 36, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(14, 'Create', 'company.infos.create', NULL, 1, 1, 36, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(15, 'Edit', 'company.infos.edit', NULL, 1, 1, 36, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(16, 'Delete', 'company.infos.delete', NULL, 1, 1, 36, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(17, 'Supplier', 'suppliers.index', NULL, 1, 1, 38, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(18, 'View', 'suppliers.view', NULL, 1, 1, 38, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(19, 'Create', 'suppliers.create', NULL, 1, 1, 38, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(20, 'Edit', 'suppliers.edit', NULL, 1, 1, 38, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(21, 'Delete', 'suppliers.delete', NULL, 1, 1, 38, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(22, 'Currency Conversion', 'currency-conversions.index', NULL, 1, 1, 100, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(23, 'View', 'currency-conversions.view', NULL, 1, 1, 100, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(24, 'Create', 'currency-conversions.create', NULL, 1, 1, 100, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(25, 'Edit', 'currency-conversions.edit', NULL, 1, 1, 100, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(26, 'Delete', 'currency-conversions.delete', NULL, 1, 1, 100, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(27, 'Id Card Setting', 'id.card.settings.index', NULL, 1, 1, 159, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(28, 'View', 'id.card.settings.view', NULL, 1, 1, 159, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(29, 'Create', 'id.card.settings.create', NULL, 1, 1, 159, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(30, 'Edit', 'id.card.settings.edit', NULL, 1, 1, 159, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(31, 'Delete', 'id.card.settings.delete', NULL, 1, 1, 159, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(32, 'Id Card Print', 'id.card.prints.index', NULL, 1, 1, 160, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(33, 'View', 'id.card.prints.view', NULL, 1, 1, 160, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(34, 'Create', 'id.card.prints.create', NULL, 1, 1, 160, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(35, 'Permission Access', 'permission.accesses.index', NULL, 1, 1, 43, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(36, 'Permitted Users', 'permission.permitted.users', NULL, 1, 1, 43, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(37, 'Permission Create', 'permission.accesses.create', NULL, 1, 1, 43, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(38, 'Permission Edit', 'permission.accesses.edit', NULL, 1, 1, 43, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(39, 'Permission Delete', 'permission.accesses.delete', NULL, 1, 1, 43, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(40, 'Attendance Device', 'attendance.devices.index', NULL, 1, 1, 118, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(41, 'View', 'attendance.devices.view', NULL, 1, 1, 118, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(42, 'Create', 'attendance.devices.create', NULL, 1, 1, 118, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(43, 'Edit', 'attendance.devices.edit', NULL, 1, 1, 118, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(44, 'Delete', 'attendance.devices.delete', NULL, 1, 1, 118, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(45, 'Active/Inactive', 'attendance.devices.active-inactive', NULL, 1, 1, 118, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(46, 'Device Api', 'device.api', NULL, 1, 1, 119, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(47, 'Categories', 'categories.index', NULL, 1, 1, 110002, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(48, 'View', 'categories.view', NULL, 1, 1, 110002, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(49, 'Create', 'categories.create', NULL, 1, 1, 110002, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(50, 'Edit', 'categories.edit', NULL, 1, 1, 110002, '2024-02-01 17:49:26', '2024-02-01 17:49:26', 1),
(51, 'Delete', 'categories.delete', NULL, 1, 1, 110002, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(52, 'Aminities', 'aminities.index', NULL, 1, 1, 110003, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(53, 'View', 'aminities.view', NULL, 1, 1, 110003, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(54, 'Create', 'aminities.create', NULL, 1, 1, 110003, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(55, 'Edit', 'aminities.edit', NULL, 1, 1, 110003, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(56, 'Delete', 'aminities.delete', NULL, 1, 1, 110003, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(57, 'Room', 'rooms.index', NULL, 1, 1, 110004, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(58, 'View', 'rooms.view', NULL, 1, 1, 110004, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(59, 'Create', 'rooms.create', NULL, 1, 1, 110004, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(60, 'Edit', 'rooms.edit', NULL, 1, 1, 110004, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(61, 'Delete', 'rooms.delete', NULL, 1, 1, 110004, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(62, 'Vat & Service', 'vats.index', NULL, 1, 1, 110005, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(63, 'View', 'vats.view', NULL, 1, 1, 110005, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(64, 'Create', 'vats.create', NULL, 1, 1, 110005, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(65, 'Edit', 'vats.edit', NULL, 1, 1, 110005, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(66, 'Delete', 'vats.delete', NULL, 1, 1, 110005, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(67, 'Account Type', 'account.types.index', NULL, 1, 1, 110006, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(68, 'View', 'account.types.view', NULL, 1, 1, 110006, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(69, 'Create', 'account.types.create', NULL, 1, 1, 110006, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(70, 'Edit', 'account.types.edit', NULL, 1, 1, 110006, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(71, 'Delete', 'account.types.delete', NULL, 1, 1, 110006, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(72, 'Booking', 'bookings.index', NULL, 1, 1, 110007, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(73, 'View', 'bookings.view', NULL, 1, 1, 110007, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(74, 'Create', 'bookings.create', NULL, 1, 1, 110007, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(75, 'Edit', 'bookings.edit', NULL, 1, 1, 110007, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(76, 'Delete', 'bookings.delete', NULL, 1, 1, 110007, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(77, 'Guest', 'guests.index', NULL, 1, 1, 110008, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(78, 'View', 'guests.view', NULL, 1, 1, 110008, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(79, 'Create', 'guests.create', NULL, 1, 1, 110008, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(80, 'Edit', 'guests.edit', NULL, 1, 1, 110008, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(81, 'Delete', 'guests.delete', NULL, 1, 1, 110008, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(82, 'Banner', 'banners.index', NULL, 1, 1, 110009, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(83, 'View', 'banners.view', NULL, 1, 1, 110009, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(84, 'Create', 'banners.create', NULL, 1, 1, 110009, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(85, 'Edit', 'banners.edit', NULL, 1, 1, 110009, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(86, 'Delete', 'banners.delete', NULL, 1, 1, 110009, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(87, 'Feature', 'features.index', NULL, 1, 1, 110010, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(88, 'View', 'features.view', NULL, 1, 1, 110010, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(89, 'Create', 'features.create', NULL, 1, 1, 110010, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(90, 'Edit', 'features.edit', NULL, 1, 1, 110010, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(91, 'Delete', 'features.delete', NULL, 1, 1, 110010, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(92, 'Feature List', 'featurelists.index', NULL, 1, 1, 110010, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(93, 'View', 'featurelists.view', NULL, 1, 1, 110010, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(94, 'Create', 'featurelists.create', NULL, 1, 1, 110010, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(95, 'Edit', 'featurelists.edit', NULL, 1, 1, 110010, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(96, 'Delete', 'featurelists.delete', NULL, 1, 1, 110010, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(97, 'About Section', 'aboutsections.index', NULL, 1, 1, 110013, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(98, 'View', 'aboutsections.view', NULL, 1, 1, 110013, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(99, 'Create', 'aboutsections.create', NULL, 1, 1, 110013, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(100, 'Edit', 'aboutsections.edit', NULL, 1, 1, 110013, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(101, 'Delete', 'aboutsections.delete', NULL, 1, 1, 110013, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(102, 'Our Service', 'ourservices.index', NULL, 1, 1, 110011, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(103, 'View', 'ourservices.view', NULL, 1, 1, 110011, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(104, 'Create', 'ourservices.create', NULL, 1, 1, 110011, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(105, 'Edit', 'ourservices.edit', NULL, 1, 1, 110011, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(106, 'Delete', 'ourservices.delete', NULL, 1, 1, 110011, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(107, 'Our Service List', 'ourservicelists.index', NULL, 1, 1, 110011, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(108, 'View', 'ourservicelists.view', NULL, 1, 1, 110011, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(109, 'Create', 'ourservicelists.create', NULL, 1, 1, 110011, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(110, 'Edit', 'ourservicelists.edit', NULL, 1, 1, 110011, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(111, 'Delete', 'ourservicelists.delete', NULL, 1, 1, 110011, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(112, 'Gallery', 'galleries.index', NULL, 1, 1, 110012, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(113, 'View', 'galleries.view', NULL, 1, 1, 110012, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(114, 'Create', 'galleries.create', NULL, 1, 1, 110012, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(115, 'Edit', 'galleries.edit', NULL, 1, 1, 110012, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(116, 'Delete', 'galleries.delete', NULL, 1, 1, 110012, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(117, 'Website Setting', 'websitesettings.index', NULL, 1, 1, 110015, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(118, 'View', 'websitesettings.view', NULL, 1, 1, 110015, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(119, 'Create', 'websitesettings.create', NULL, 1, 1, 110015, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(120, 'Edit', 'websitesettings.edit', NULL, 1, 1, 110015, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(121, 'Delete', 'websitesettings.delete', NULL, 1, 1, 110015, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(122, 'Privacy Poilicy', 'privacypoilicies.index', NULL, 1, 1, 110014, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(123, 'View', 'privacypoilicies.view', NULL, 1, 1, 110014, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(124, 'Create', 'privacypoilicies.create', NULL, 1, 1, 110014, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(125, 'Edit', 'privacypoilicies.edit', NULL, 1, 1, 110014, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(126, 'Delete', 'privacypoilicies.delete', NULL, 1, 1, 110014, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(127, 'Hotel Service', 'hotel.services.index', NULL, 1, 1, 110016, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(128, 'View', 'hotel.services.view', NULL, 1, 1, 110016, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(129, 'Create', 'hotel.services.create', NULL, 1, 1, 110016, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(130, 'Edit', 'hotel.services.edit', NULL, 1, 1, 110016, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(131, 'Delete', 'hotel.services.delete', NULL, 1, 1, 110016, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(132, 'Sales', 'sales.index', NULL, 1, 1, 110017, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(133, 'View', 'sales.view', NULL, 1, 1, 110017, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(134, 'Create', 'sales.create', NULL, 1, 1, 110017, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(135, 'Edit', 'sales.edit', NULL, 1, 1, 110017, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(136, 'Delete', 'sales.delete', NULL, 1, 1, 110017, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(137, 'Advance', 'bookings.advance', NULL, 1, 1, 110007, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(138, 'Cancel', 'bookings.cancel', NULL, 1, 1, 110007, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(139, 'Discount', 'bookings.discount', NULL, 1, 1, 110007, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(140, 'Rst Sale', 'resturant.sales.index', NULL, 1, 1, 130001, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(141, 'View', 'resturant.sales.view', NULL, 1, 1, 130001, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(142, 'Create', 'resturant.sales.create', NULL, 1, 1, 130001, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(143, 'Edit', 'resturant.sales.edit', NULL, 1, 1, 130001, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(144, 'Delete', 'resturant.sales.delete', NULL, 1, 1, 130001, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(145, 'Quantity', 'rst.sales.quantity-update', NULL, 1, 1, 130001, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(146, 'Item Delete', 'resturant.sales-item.delete', NULL, 1, 1, 130001, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(147, 'Purchase', 'resturant.purchases.index', NULL, 1, 1, 130002, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(148, 'View', 'resturant.purchases.view', NULL, 1, 1, 130002, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(149, 'Create', 'resturant.purchases.create', NULL, 1, 1, 130002, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(150, 'Edit', 'resturant.purchases.edit', NULL, 1, 1, 130002, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(151, 'Delete', 'resturant.purchases.delete', NULL, 1, 1, 130002, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(152, 'Inventory', 'resturant.inventories.index', NULL, 1, 1, 130003, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(153, 'View', 'resturant.inventories.view', NULL, 1, 1, 130003, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(154, 'Create', 'resturant.inventories.create', NULL, 1, 1, 130003, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(155, 'Edit', 'resturant.inventories.edit', NULL, 1, 1, 130003, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(156, 'Delete', 'resturant.inventories.delete', NULL, 1, 1, 130003, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(157, 'Table', 'resturant.table-manages.index', NULL, 1, 1, 130004, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(158, 'View', 'resturant.table-manages.view', NULL, 1, 1, 130004, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(159, 'Create', 'resturant.table-manages.create', NULL, 1, 1, 130004, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(160, 'Edit', 'resturant.table-manages.edit', NULL, 1, 1, 130004, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(161, 'Delete', 'resturant.table-manages.delete', NULL, 1, 1, 130004, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(162, 'Reports', 'resturant.reports.index', NULL, 1, 1, 130005, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(163, 'View', 'resturant.reports.view', NULL, 1, 1, 130005, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(164, 'Create', 'resturant.reports.create', NULL, 1, 1, 130005, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(165, 'Edit', 'resturant.reports.edit', NULL, 1, 1, 130005, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(166, 'Delete', 'resturant.reports.delete', NULL, 1, 1, 130005, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(167, 'Payment Collection', 'resturant.payment-collection', NULL, 1, 1, 130007, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(168, 'Production Purchases', 'rst.purchase.index', NULL, 1, 1, 130008, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(169, 'View', 'rst.purchase.view', NULL, 1, 1, 130008, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(170, 'Create', 'rst.purchase.create', NULL, 1, 1, 130008, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(171, 'Edit', 'rst.purchase.edit', NULL, 1, 1, 130008, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(172, 'Delete', 'rst.purchase.delete', NULL, 1, 1, 130008, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(173, 'Approve', 'rst.purchase.approve', NULL, 1, 1, 130008, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(174, 'Bar Sale', 'bar.sales.index', NULL, 1, 1, 270001, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(175, 'View', 'bar.sales.view', NULL, 1, 1, 270001, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(176, 'Create', 'bar.sales.create', NULL, 1, 1, 270001, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(177, 'Edit', 'bar.sales.edit', NULL, 1, 1, 270001, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(178, 'Delete', 'bar.sales.delete', NULL, 1, 1, 270001, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(179, 'Bar Purchase', 'bar.purchases.index', NULL, 1, 1, 270002, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(180, 'View', 'bar.purchases.view', NULL, 1, 1, 270002, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(181, 'Create', 'bar.purchases.create', NULL, 1, 1, 270002, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(182, 'Edit', 'bar.purchases.edit', NULL, 1, 1, 270002, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(183, 'Delete', 'bar.purchases.delete', NULL, 1, 1, 270002, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(184, 'Bar Inventory', 'bar.inventories.index', NULL, 1, 1, 270003, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(185, 'View', 'bar.inventories.view', NULL, 1, 1, 270003, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(186, 'Create', 'bar.inventories.create', NULL, 1, 1, 270003, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(187, 'Edit', 'bar.inventories.edit', NULL, 1, 1, 270003, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(188, 'Delete', 'bar.inventories.delete', NULL, 1, 1, 270003, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(189, 'Bar Table', 'bar.table-manages.index', NULL, 1, 1, 270004, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(190, 'View', 'bar.table-manages.view', NULL, 1, 1, 270004, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(191, 'Create', 'bar.table-manages.create', NULL, 1, 1, 270004, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(192, 'Edit', 'bar.table-manages.edit', NULL, 1, 1, 270004, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(193, 'Delete', 'bar.table-manages.delete', NULL, 1, 1, 270004, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(194, 'Bar Reports', 'bar.reports.index', NULL, 1, 1, 270005, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(195, 'Cash Flow', 'bar.cash-flow.index', NULL, 1, 1, 270005, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(196, 'Sale Report', 'bar.sale-report.index', NULL, 1, 1, 270005, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(197, 'Purchase Report', 'bar.purchase-report.index', NULL, 1, 1, 270005, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(198, 'Quantity', 'bar.sales.quantity-update', NULL, 1, 1, 270001, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(199, 'Night Audit', 'hotel.night-audit.index', NULL, 1, 1, 110027, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(200, 'Create', 'hotel.night-audit.create', NULL, 1, 1, 110027, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(201, 'Edit', 'hotel.night-audit.edit', NULL, 1, 1, 110027, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(202, 'Delete ', 'hotel.night-audit.delete', NULL, 1, 1, 110027, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(203, 'Reports', 'hotel.report.index', NULL, 1, 1, 110028, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(204, 'Cash Flow ', 'hotel.cash-flow-report.index', NULL, 1, 1, 110028, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(205, 'Night Audit', 'hotel.night-audit-report.index', NULL, 1, 1, 110028, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(206, 'Monthly', 'hotel.monthly-report.index', NULL, 1, 1, 110028, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(207, 'Expected Arrival', 'hotel.expected-arrival.index', NULL, 1, 1, 110028, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(208, 'Expected Departure', 'hotel.expected-departure.index', NULL, 1, 1, 110028, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(209, 'In House Guest', 'hotel.in-house-guest.index', NULL, 1, 1, 110028, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(210, 'Check-In', 'hotel.daily-check-in', NULL, 1, 1, 110028, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(211, 'Check-Out', 'hotel.daily-check-out', NULL, 1, 1, 110028, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(212, 'In-House-Report', 'report.today-in-house', NULL, 1, 1, 110028, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(213, 'Over All', 'hotel.report.over-all', NULL, 1, 1, 110028, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(214, 'Payment Collection', 'hotel.booking-collection', NULL, 1, 1, 110047, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(215, 'Payment Collect', 'hotel.booking-collection-store', NULL, 1, 1, 110047, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(216, 'HouseKeeping', 'rst.stock-adjustment.index', NULL, 1, 1, 110045, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(217, 'Create', 'rst.stock-adjustment.create', NULL, 1, 1, 110044, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(218, 'View', 'rst.stock-adjustment.show', NULL, 1, 1, 110044, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(219, 'Edit', 'rst.stock-adjustment.edit', NULL, 1, 1, 110044, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(220, 'Delete', 'rst.stock-adjustment.delete', NULL, 1, 1, 110044, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(221, 'Index', 'Booking.HouseKeeping', NULL, 1, 1, 110045, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(222, 'Kitchen Access', 'kit.kitchen.all', NULL, 1, 1, 110046, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(223, 'Create', 'kit.kitchen.create', NULL, 1, 1, 110046, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(224, 'Index', 'kit.kitchen.index', NULL, 1, 1, 110046, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(225, 'Booking', 'banquet.booking', NULL, 1, 1, 110048, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(226, 'Index', 'banquet.booking.index', NULL, 1, 1, 110048, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(227, 'Create', 'banquet.booking.create', NULL, 1, 1, 110048, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(228, 'Edit', 'banquet.booking.edit', NULL, 1, 1, 110048, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(229, 'Delete', 'banquet.booking.delete', NULL, 1, 1, 110048, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(230, 'Categories', 'hall-categories', NULL, 1, 1, 110049, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(231, 'Index', 'hall-categories.index', NULL, 1, 1, 110049, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(232, 'Create', 'hall-categories.create', NULL, 1, 1, 110049, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(233, 'Edit', 'hall-categories.edit', NULL, 1, 1, 110049, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(234, 'Delete', 'hall-categories.delete', NULL, 1, 1, 110049, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(235, 'Aminities', 'banquet.aminities', NULL, 1, 1, 110050, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(236, 'Index', 'banquet.aminities.index', NULL, 1, 1, 110050, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(237, 'Create', 'banquet.aminities.create', NULL, 1, 1, 110050, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(238, 'Edit', 'banquet.aminities.edit', NULL, 1, 1, 110050, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(239, 'Delete', 'banquet.aminities.delete', NULL, 1, 1, 110050, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(240, 'Rooms', 'banquet.hall-rooms', NULL, 1, 1, 110051, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(241, 'Index', 'banquet.hall-rooms.index', NULL, 1, 1, 110051, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(242, 'Create', 'banquet.hall-rooms.create', NULL, 1, 1, 110051, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(243, 'Edit', 'banquet.hall-rooms.edit', NULL, 1, 1, 110051, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(244, 'Delete', 'banquet.hall-rooms.delete', NULL, 1, 1, 110051, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(245, 'Packages', 'bar.packages', NULL, 1, 1, 110052, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(246, 'Index', 'bar.packages.index', NULL, 1, 1, 110052, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(247, 'Create', 'bar.packages.create', NULL, 1, 1, 110052, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(248, 'Edit', 'bar.packages.edit', NULL, 1, 1, 110052, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(249, 'Delete', 'bar.packages.delete', NULL, 1, 1, 110052, '2024-02-01 17:49:27', '2024-02-01 17:49:27', 1),
(150000, 'Account Setup', 'account-setups.index', NULL, 1, 1, 150000, '2021-11-17 15:57:27', '2021-11-17 15:57:27', 1),
(150001, 'Account Group', 'account-groups.index', NULL, 1, 1, 150000, '2021-11-17 15:57:27', '2021-11-17 15:57:27', 1),
(150002, 'Account Control', 'account-controls.index', NULL, 1, 1, 150000, '2021-11-17 15:57:27', '2021-11-17 15:57:27', 1),
(150003, 'Account Subsidiary', 'account-subsidiaries.index', NULL, 1, 1, 150001, '2021-11-17 16:00:51', '2021-11-17 16:00:51', 1),
(150004, 'View', 'account-subsidiaries.view', NULL, 1, 1, 150001, '2021-11-17 16:00:51', '2021-11-17 16:00:51', 1),
(150005, 'Create', 'account-subsidiaries.create', NULL, 1, 1, 150001, '2021-11-17 16:00:51', '2021-11-17 16:00:51', 1),
(150006, 'Edit', 'account-subsidiaries.edit', NULL, 1, 1, 150001, '2021-11-17 16:00:51', '2021-11-17 16:00:51', 1),
(150007, 'Delete', 'account-subsidiaries.delete', NULL, 1, 1, 150001, '2021-11-17 16:00:51', '2021-11-17 16:00:51', 1),
(150008, 'Account Chart', 'account.index', NULL, 1, 1, 150002, '2021-11-17 16:04:37', '2021-11-17 16:04:37', 1),
(150009, 'View', 'account.view', NULL, 1, 1, 150002, '2021-11-17 16:04:37', '2021-11-17 16:04:37', 1),
(150010, 'Create', 'account.create', NULL, 1, 1, 150002, '2021-11-17 16:04:37', '2021-11-17 16:04:37', 1),
(150011, 'Edit', 'account.edit', NULL, 1, 1, 150002, '2021-11-17 16:04:37', '2021-11-17 16:04:37', 1),
(150012, 'Delete', 'account.delete', NULL, 1, 1, 150002, '2021-11-17 16:04:37', '2021-11-17 16:04:37', 1),
(150013, 'Payment Voucher', 'voucher-payments.index', NULL, 1, 1, 150003, '2021-11-17 16:10:29', '2021-11-17 16:10:29', 1),
(150014, 'View', 'voucher-payments.view', NULL, 1, 1, 150003, '2021-11-17 16:10:29', '2021-11-17 16:10:29', 1),
(150015, 'Create', 'voucher-payments.create', NULL, 1, 1, 150003, '2021-11-17 16:10:29', '2021-11-17 16:10:29', 1),
(150016, 'Edit', 'voucher-payments.edit', NULL, 1, 1, 150003, '2021-11-17 16:10:29', '2021-11-17 16:10:29', 1),
(150017, 'Delete', 'voucher-payments.delete', NULL, 1, 1, 150003, '2021-11-17 16:10:29', '2021-11-17 16:10:29', 1),
(150018, 'Receive Voucher', 'voucher-receives.index', NULL, 1, 1, 150004, '2021-11-17 16:12:03', '2021-11-17 16:12:03', 1),
(150019, 'View', 'voucher-receives.view', NULL, 1, 1, 150004, '2021-11-17 16:12:03', '2021-11-17 16:12:03', 1),
(150020, 'Create', 'voucher-receives.create', NULL, 1, 1, 150004, '2021-11-17 16:12:03', '2021-11-17 16:12:03', 1),
(150021, 'Edit', 'voucher-receives.edit', NULL, 1, 1, 150004, '2021-11-17 16:12:03', '2021-11-17 16:12:03', 1),
(150022, 'Delete', 'voucher-receives.delete', NULL, 1, 1, 150004, '2021-11-17 16:12:03', '2021-11-17 16:12:03', 1),
(150023, 'Contra Voucher', 'voucher-contras.index', NULL, 1, 1, 150005, '2021-11-17 16:13:58', '2021-11-17 16:13:58', 1),
(150024, 'View', 'voucher-contras.view', NULL, 1, 1, 150005, '2021-11-17 16:13:58', '2021-11-17 16:13:58', 1),
(150025, 'Create', 'voucher-contras.create', NULL, 1, 1, 150005, '2021-11-17 16:13:58', '2021-11-17 16:13:58', 1),
(150026, 'Edit', 'voucher-contras.edit', NULL, 1, 1, 150005, '2021-11-17 16:13:58', '2021-11-17 16:13:58', 1),
(150027, 'Delete', 'voucher-contras.delete', NULL, 1, 1, 150005, '2021-11-17 16:13:58', '2021-11-17 16:13:58', 1),
(150028, 'Voucher Journal', 'voucher-journals.index', NULL, 1, 1, 150006, '2021-11-17 16:14:43', '2021-11-17 16:14:43', 1),
(150029, 'View', 'voucher-journals.view', NULL, 1, 1, 150006, '2021-11-17 16:14:43', '2021-11-17 16:14:43', 1),
(150030, 'Create', 'voucher-journals.create', NULL, 1, 1, 150006, '2021-11-17 16:14:43', '2021-11-17 16:14:43', 1),
(150031, 'Edit', 'voucher-journals.edit', NULL, 1, 1, 150006, '2021-11-17 16:14:43', '2021-11-17 16:14:43', 1),
(150032, 'Delete', 'voucher-journals.delete', NULL, 1, 1, 150006, '2021-11-17 16:14:43', '2021-11-17 16:14:43', 1),
(150033, 'Account Product', 'account-products.index', NULL, 1, 1, 150007, '2021-11-17 16:18:04', '2021-11-17 16:18:04', 1),
(150034, 'View', 'account-products.view', NULL, 1, 1, 150007, '2021-11-17 16:18:04', '2021-11-17 16:18:04', 1),
(150035, 'Create', 'account-products.create', NULL, 1, 1, 150007, '2021-11-17 16:18:04', '2021-11-17 16:18:04', 1),
(150036, 'Edit', 'account-products.edit', NULL, 1, 1, 150007, '2021-11-17 16:18:04', '2021-11-17 16:18:04', 1),
(150037, 'Delete', 'account-products.delete', NULL, 1, 1, 150007, '2021-11-17 16:18:04', '2021-11-17 16:18:04', 1),
(150038, 'Account Category', 'account-categories.index', NULL, 1, 1, 150008, '2021-11-17 16:20:04', '2021-11-17 16:20:04', 1),
(150039, 'View', 'account-categories.view', NULL, 1, 1, 150008, '2021-11-17 16:20:04', '2021-11-17 16:20:04', 1),
(150040, 'Create', 'account-categories.create', NULL, 1, 1, 150008, '2021-11-17 16:20:04', '2021-11-17 16:20:04', 1),
(150041, 'Edit', 'account-categories.edit', NULL, 1, 1, 150008, '2021-11-17 16:20:04', '2021-11-17 16:20:04', 1),
(150042, 'Delete', 'account-categories.delete', NULL, 1, 1, 150008, '2021-11-17 16:20:04', '2021-11-17 16:20:04', 1),
(150043, 'Account Unit', 'account-units.index', NULL, 1, 1, 150009, '2021-11-17 16:20:25', '2021-11-17 16:20:25', 1),
(150044, 'View', 'account-units.view', NULL, 1, 1, 150009, '2021-11-17 16:20:25', '2021-11-17 16:20:25', 1),
(150045, 'Create', 'account-units.create', NULL, 1, 1, 150009, '2021-11-17 16:20:25', '2021-11-17 16:20:25', 1),
(150046, 'Edit', 'account-units.edit', NULL, 1, 1, 150009, '2021-11-17 16:20:26', '2021-11-17 16:20:26', 1),
(150047, 'Delete', 'account-units.delete', NULL, 1, 1, 150009, '2021-11-17 16:20:26', '2021-11-17 16:20:26', 1),
(150048, 'Account Customer', 'account-customers.index', NULL, 1, 1, 150010, '2021-11-17 16:24:19', '2021-11-17 16:24:19', 1),
(150049, 'View', 'account-customers.view', NULL, 1, 1, 150010, '2021-11-17 16:24:19', '2021-11-17 16:24:19', 1),
(150050, 'Create', 'account-customers.create', NULL, 1, 1, 150010, '2021-11-17 16:24:19', '2021-11-17 16:24:19', 1),
(150051, 'Edit', 'account-customers.edit', NULL, 1, 1, 150010, '2021-11-17 16:24:19', '2021-11-17 16:24:19', 1),
(150052, 'Delete', 'account-customers.delete', NULL, 1, 1, 150010, '2021-11-17 16:24:19', '2021-11-17 16:24:19', 1),
(150053, 'Account Supplier', 'account-suppliers.index', NULL, 1, 1, 150011, '2021-11-17 16:24:43', '2021-11-17 16:24:43', 1),
(150054, 'View', 'account-suppliers.view', NULL, 1, 1, 150011, '2021-11-17 16:24:43', '2021-11-17 16:24:43', 1),
(150055, 'Create', 'account-suppliers.create', NULL, 1, 1, 150011, '2021-11-17 16:24:43', '2021-11-17 16:24:43', 1),
(150056, 'Edit', 'account-suppliers.edit', NULL, 1, 1, 150011, '2021-11-17 16:24:43', '2021-11-17 16:24:43', 1),
(150057, 'Delete', 'account-suppliers.delete', NULL, 1, 1, 150011, '2021-11-17 16:24:43', '2021-11-17 16:24:43', 1),
(150058, 'Account Purchase', 'account-purchases.index', NULL, 1, 1, 150012, '2021-11-17 16:25:09', '2021-11-17 16:25:09', 1),
(150059, 'View', 'account-purchases.view', NULL, 1, 1, 150012, '2021-11-17 16:25:09', '2021-11-17 16:25:09', 1),
(150060, 'Create', 'account-purchases.create', NULL, 1, 1, 150012, '2021-11-17 16:25:09', '2021-11-17 16:25:09', 1),
(150061, 'Edit', 'account-purchases.edit', NULL, 1, 1, 150012, '2021-11-17 16:25:09', '2021-11-17 16:25:09', 1),
(150062, 'Delete', 'account-purchases.delete', NULL, 1, 1, 150012, '2021-11-17 16:25:09', '2021-11-17 16:25:09', 1),
(150063, 'Account Sale', 'account-sales.index', NULL, 1, 1, 150013, '2021-11-17 16:27:27', '2021-11-17 16:27:27', 1),
(150064, 'View', 'account-sales.view', NULL, 1, 1, 150013, '2021-11-17 16:27:27', '2021-11-17 16:27:27', 1),
(150065, 'Create', 'account-sales.create', NULL, 1, 1, 150013, '2021-11-17 16:27:28', '2021-11-17 16:27:28', 1),
(150066, 'Edit', 'account-sales.edit', NULL, 1, 1, 150013, '2021-11-17 16:27:28', '2021-11-17 16:27:28', 1),
(150067, 'Delete', 'account-sales.delete', NULL, 1, 1, 150013, '2021-11-17 16:27:28', '2021-11-17 16:27:28', 1),
(150068, 'Account Ledger', 'account-ledgers.index', NULL, 1, 1, 150014, '2021-11-17 16:29:08', '2021-11-17 16:29:08', 1),
(150069, 'Chart Of Account', 'report.chart-of-account', NULL, 1, 1, 150014, '2021-11-17 16:29:08', '2021-11-17 16:34:59', 1),
(150070, 'Ledger Journal', 'report.ledger-journal', NULL, 1, 1, 150014, '2021-11-17 16:29:08', '2021-11-17 16:34:26', 1),
(150071, 'Account Ledger', 'report.account-ledger', NULL, 1, 1, 150014, '2021-11-17 16:29:08', '2021-11-17 16:33:57', 1),
(150072, 'Customer Ledger', 'report.customer-ledger', NULL, 1, 1, 150014, '2021-11-17 16:29:09', '2021-11-17 16:33:22', 1),
(150073, 'Supplier Ledger', 'report.supplier-ledger', NULL, 1, 1, 150014, '2021-11-17 16:29:09', '2021-11-17 16:32:03', 1),
(150074, 'Subsidiary Ledger', 'report.subsidiary-wise-ledger', NULL, 1, 1, 150014, '2021-11-17 16:29:09', '2021-11-17 16:31:17', 1),
(150075, 'Financial Report', 'financial-reports.index', NULL, 1, 1, 150015, '2021-11-17 16:36:13', '2021-11-17 16:36:13', 1),
(150076, 'Trial Balance', 'report.trial-balance', NULL, 1, 1, 150015, '2021-11-17 16:36:13', '2021-11-17 16:38:49', 1),
(150077, 'Income Statement', 'report.income-statement', NULL, 1, 1, 150015, '2021-11-17 16:36:13', '2021-11-17 16:38:12', 1),
(150078, 'Equity Statement', 'report.equity-statement', NULL, 1, 1, 150015, '2021-11-17 16:36:13', '2021-11-17 16:37:44', 1),
(150079, 'Balance Sheet', 'report.balance-sheet', NULL, 1, 1, 150015, '2021-11-17 16:36:13', '2021-11-17 16:37:15', 1),
(150080, 'Cash Flow', 'report.cash.flow', NULL, 1, 1, 150015, '2021-11-17 16:36:14', '2021-11-17 16:36:49', 1),
(170000, 'Customer', 'crm.customers', NULL, 1, 1, 170000, '2024-02-01 17:51:49', '2024-02-01 17:51:49', 1),
(170001, 'View', 'crm.customers.index', NULL, 1, 1, 170000, '2024-02-01 17:51:49', '2024-02-01 17:51:49', 1),
(170002, 'Create', 'crm.customers.create', NULL, 1, 1, 170000, '2024-02-01 17:51:49', '2024-02-01 17:51:49', 1),
(170003, 'Edit', 'crm.customers.edit', NULL, 1, 1, 170000, '2024-02-01 17:51:49', '2024-02-01 17:51:49', 1),
(170004, 'Delete', 'crm.customers.delete', NULL, 1, 1, 170000, '2024-02-01 17:51:49', '2024-02-01 17:51:49', 1),
(170005, 'Lead', 'crm.leads', NULL, 1, 1, 170001, '2024-02-01 17:51:49', '2024-02-01 17:51:49', 1),
(170006, 'View', 'crm.leads.index', NULL, 1, 1, 170001, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170007, 'Create', 'crm.leads.create', NULL, 1, 1, 170001, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170008, 'Edit', 'crm.leads.edit', NULL, 1, 1, 170001, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170009, 'Delete', 'crm.leads.delete', NULL, 1, 1, 170001, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170010, 'WorkOut', 'crm.workouts', NULL, 1, 1, 170002, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170011, 'View', 'crm.workouts.index', NULL, 1, 1, 170002, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170012, 'Create', 'crm.workouts.create', NULL, 1, 1, 170002, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170013, 'Edit', 'crm.workouts.edit', NULL, 1, 1, 170002, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170014, 'Delete', 'crm.workouts.delete', NULL, 1, 1, 170002, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170015, 'Project', 'crm.projects', NULL, 1, 1, 170003, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170016, 'View', 'crm.projects.index', NULL, 1, 1, 170003, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170017, 'Create', 'crm.projects.create', NULL, 1, 1, 170003, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170018, 'Edit', 'crm.projects.edit', NULL, 1, 1, 170003, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170019, 'Delete', 'crm.projects.delete', NULL, 1, 1, 170003, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170020, 'Generate Billing', 'crm.generate-billing-invoice', NULL, 1, 1, 170003, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170021, 'Project Billing', 'crm.project-billings', NULL, 1, 1, 170004, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170022, 'View', 'crm.project-billings.index', NULL, 1, 1, 170004, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170023, 'Create', 'crm.project-billings.create', NULL, 1, 1, 170004, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170024, 'Edit', 'crm.project-billings.edit', NULL, 1, 1, 170004, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170025, 'Delete', 'crm.project-billings.delete', NULL, 1, 1, 170004, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170026, 'Payment Approve', 'crm.project-billings.payment', NULL, 1, 1, 170004, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170027, 'Project Type', 'crm.project-types', NULL, 1, 1, 170005, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170028, 'View', 'crm.project-types.index', NULL, 1, 1, 170005, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170029, 'Create', 'crm.project-types.create', NULL, 1, 1, 170005, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170030, 'Edit', 'crm.project-types.edit', NULL, 1, 1, 170005, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170031, 'Delete', 'crm.project-types.delete', NULL, 1, 1, 170005, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170032, 'Mail Template', 'crm.templates', NULL, 1, 1, 170006, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170033, 'View', 'crm.templates.index', NULL, 1, 1, 170006, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170034, 'Create', 'crm.templates.create', NULL, 1, 1, 170006, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170035, 'Edit', 'crm.templates.edit', NULL, 1, 1, 170006, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1),
(170036, 'Delete', 'crm.templates.delete', NULL, 1, 1, 170006, '2024-02-01 17:51:50', '2024-02-01 17:51:50', 1);

-- --------------------------------------------------------

--
-- Table structure for table `permission_features`
--

CREATE TABLE `permission_features` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_features`
--

INSERT INTO `permission_features` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Order Type', 1, '2024-02-01 17:49:27', '2024-02-01 17:49:27'),
(2, 'Company', 1, '2024-02-01 17:49:27', '2024-02-01 17:49:27'),
(3, 'Department', 1, '2024-02-01 17:49:27', '2024-02-01 17:49:27'),
(4, 'Designation', 1, '2024-02-01 17:49:27', '2024-02-01 17:49:27'),
(5, 'Buyer', 1, '2024-02-01 17:49:27', '2024-02-01 17:49:27');

-- --------------------------------------------------------

--
-- Table structure for table `permission_user`
--

CREATE TABLE `permission_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_user`
--

INSERT INTO `permission_user` (`id`, `permission_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 35, 2, NULL, NULL),
(2, 36, 2, NULL, NULL),
(3, 37, 2, NULL, NULL),
(4, 38, 2, NULL, NULL),
(5, 39, 2, NULL, NULL),
(6, 40, 2, NULL, NULL),
(7, 41, 2, NULL, NULL),
(8, 42, 2, NULL, NULL),
(9, 43, 2, NULL, NULL),
(10, 44, 2, NULL, NULL),
(11, 45, 2, NULL, NULL),
(12, 46, 2, NULL, NULL),
(13, 127, 2, NULL, NULL),
(14, 128, 2, NULL, NULL),
(15, 129, 2, NULL, NULL),
(16, 130, 2, NULL, NULL),
(17, 131, 2, NULL, NULL),
(18, 132, 2, NULL, NULL),
(19, 133, 2, NULL, NULL),
(20, 134, 2, NULL, NULL),
(21, 135, 2, NULL, NULL),
(22, 136, 2, NULL, NULL),
(23, 140, 2, NULL, NULL),
(24, 141, 2, NULL, NULL),
(25, 142, 2, NULL, NULL),
(26, 143, 2, NULL, NULL),
(27, 144, 2, NULL, NULL),
(28, 145, 2, NULL, NULL),
(29, 146, 2, NULL, NULL),
(30, 147, 2, NULL, NULL),
(31, 148, 2, NULL, NULL),
(32, 149, 2, NULL, NULL),
(33, 150, 2, NULL, NULL),
(34, 151, 2, NULL, NULL),
(35, 152, 2, NULL, NULL),
(36, 153, 2, NULL, NULL),
(37, 154, 2, NULL, NULL),
(38, 155, 2, NULL, NULL),
(39, 156, 2, NULL, NULL),
(40, 157, 2, NULL, NULL),
(41, 158, 2, NULL, NULL),
(42, 159, 2, NULL, NULL),
(43, 160, 2, NULL, NULL),
(44, 161, 2, NULL, NULL),
(45, 162, 2, NULL, NULL),
(46, 163, 2, NULL, NULL),
(47, 164, 2, NULL, NULL),
(48, 165, 2, NULL, NULL),
(49, 166, 2, NULL, NULL),
(50, 167, 2, NULL, NULL),
(51, 168, 2, NULL, NULL),
(52, 169, 2, NULL, NULL),
(53, 170, 2, NULL, NULL),
(54, 171, 2, NULL, NULL),
(55, 172, 2, NULL, NULL),
(56, 173, 2, NULL, NULL),
(57, 150000, 2, NULL, NULL),
(58, 150001, 2, NULL, NULL),
(59, 150002, 2, NULL, NULL),
(60, 150003, 2, NULL, NULL),
(61, 150004, 2, NULL, NULL),
(62, 150005, 2, NULL, NULL),
(63, 150006, 2, NULL, NULL),
(64, 150007, 2, NULL, NULL),
(65, 150008, 2, NULL, NULL),
(66, 150009, 2, NULL, NULL),
(67, 150010, 2, NULL, NULL),
(68, 150011, 2, NULL, NULL),
(69, 150012, 2, NULL, NULL),
(70, 150033, 2, NULL, NULL),
(71, 150034, 2, NULL, NULL),
(72, 150035, 2, NULL, NULL),
(73, 150036, 2, NULL, NULL),
(74, 150037, 2, NULL, NULL),
(75, 150038, 2, NULL, NULL),
(76, 150039, 2, NULL, NULL),
(77, 150040, 2, NULL, NULL),
(78, 150041, 2, NULL, NULL),
(79, 150042, 2, NULL, NULL),
(80, 150043, 2, NULL, NULL),
(81, 150044, 2, NULL, NULL),
(82, 150045, 2, NULL, NULL),
(83, 150046, 2, NULL, NULL),
(84, 150047, 2, NULL, NULL),
(85, 150013, 2, NULL, NULL),
(86, 150014, 2, NULL, NULL),
(87, 150015, 2, NULL, NULL),
(88, 150016, 2, NULL, NULL),
(89, 150017, 2, NULL, NULL),
(90, 150018, 2, NULL, NULL),
(91, 150019, 2, NULL, NULL),
(92, 150020, 2, NULL, NULL),
(93, 150021, 2, NULL, NULL),
(94, 150022, 2, NULL, NULL),
(95, 150023, 2, NULL, NULL),
(96, 150024, 2, NULL, NULL),
(97, 150025, 2, NULL, NULL),
(98, 150026, 2, NULL, NULL),
(99, 150027, 2, NULL, NULL),
(100, 150028, 2, NULL, NULL),
(101, 150029, 2, NULL, NULL),
(102, 150030, 2, NULL, NULL),
(103, 150031, 2, NULL, NULL),
(104, 150032, 2, NULL, NULL),
(105, 150048, 2, NULL, NULL),
(106, 150049, 2, NULL, NULL),
(107, 150050, 2, NULL, NULL),
(108, 150051, 2, NULL, NULL),
(109, 150052, 2, NULL, NULL),
(110, 150053, 2, NULL, NULL),
(111, 150054, 2, NULL, NULL),
(112, 150055, 2, NULL, NULL),
(113, 150056, 2, NULL, NULL),
(114, 150057, 2, NULL, NULL),
(115, 150058, 2, NULL, NULL),
(116, 150059, 2, NULL, NULL),
(117, 150060, 2, NULL, NULL),
(118, 150061, 2, NULL, NULL),
(119, 150062, 2, NULL, NULL),
(120, 150063, 2, NULL, NULL),
(121, 150064, 2, NULL, NULL),
(122, 150065, 2, NULL, NULL),
(123, 150066, 2, NULL, NULL),
(124, 150067, 2, NULL, NULL),
(125, 150068, 2, NULL, NULL),
(126, 150069, 2, NULL, NULL),
(127, 150070, 2, NULL, NULL),
(128, 150071, 2, NULL, NULL),
(129, 150072, 2, NULL, NULL),
(130, 150073, 2, NULL, NULL),
(131, 150074, 2, NULL, NULL),
(132, 150075, 2, NULL, NULL),
(133, 150076, 2, NULL, NULL),
(134, 150077, 2, NULL, NULL),
(135, 150078, 2, NULL, NULL),
(136, 150079, 2, NULL, NULL),
(137, 150080, 2, NULL, NULL),
(138, 47, 2, NULL, NULL),
(139, 48, 2, NULL, NULL),
(140, 49, 2, NULL, NULL),
(141, 50, 2, NULL, NULL),
(142, 51, 2, NULL, NULL),
(143, 52, 2, NULL, NULL),
(144, 53, 2, NULL, NULL),
(145, 54, 2, NULL, NULL),
(146, 55, 2, NULL, NULL),
(147, 56, 2, NULL, NULL),
(148, 57, 2, NULL, NULL),
(149, 58, 2, NULL, NULL),
(150, 59, 2, NULL, NULL),
(151, 60, 2, NULL, NULL),
(152, 61, 2, NULL, NULL),
(153, 62, 2, NULL, NULL),
(154, 63, 2, NULL, NULL),
(155, 64, 2, NULL, NULL),
(156, 65, 2, NULL, NULL),
(157, 66, 2, NULL, NULL),
(158, 67, 2, NULL, NULL),
(159, 68, 2, NULL, NULL),
(160, 69, 2, NULL, NULL),
(161, 70, 2, NULL, NULL),
(162, 71, 2, NULL, NULL),
(163, 72, 2, NULL, NULL),
(164, 73, 2, NULL, NULL),
(165, 74, 2, NULL, NULL),
(166, 75, 2, NULL, NULL),
(167, 76, 2, NULL, NULL),
(168, 137, 2, NULL, NULL),
(169, 138, 2, NULL, NULL),
(170, 139, 2, NULL, NULL),
(171, 77, 2, NULL, NULL),
(172, 78, 2, NULL, NULL),
(173, 79, 2, NULL, NULL),
(174, 80, 2, NULL, NULL),
(175, 81, 2, NULL, NULL),
(176, 199, 2, NULL, NULL),
(177, 200, 2, NULL, NULL),
(178, 201, 2, NULL, NULL),
(179, 202, 2, NULL, NULL),
(180, 203, 2, NULL, NULL),
(181, 204, 2, NULL, NULL),
(182, 205, 2, NULL, NULL),
(183, 206, 2, NULL, NULL),
(184, 207, 2, NULL, NULL),
(185, 208, 2, NULL, NULL),
(186, 209, 2, NULL, NULL),
(187, 210, 2, NULL, NULL),
(188, 211, 2, NULL, NULL),
(189, 212, 2, NULL, NULL),
(190, 213, 2, NULL, NULL),
(191, 214, 2, NULL, NULL),
(192, 215, 2, NULL, NULL),
(193, 217, 2, NULL, NULL),
(194, 218, 2, NULL, NULL),
(195, 219, 2, NULL, NULL),
(196, 220, 2, NULL, NULL),
(197, 216, 2, NULL, NULL),
(198, 221, 2, NULL, NULL),
(199, 222, 2, NULL, NULL),
(200, 223, 2, NULL, NULL),
(201, 224, 2, NULL, NULL),
(202, 82, 2, NULL, NULL),
(203, 83, 2, NULL, NULL),
(204, 84, 2, NULL, NULL),
(205, 85, 2, NULL, NULL),
(206, 86, 2, NULL, NULL),
(207, 87, 2, NULL, NULL),
(208, 88, 2, NULL, NULL),
(209, 89, 2, NULL, NULL),
(210, 90, 2, NULL, NULL),
(211, 91, 2, NULL, NULL),
(212, 92, 2, NULL, NULL),
(213, 93, 2, NULL, NULL),
(214, 94, 2, NULL, NULL),
(215, 95, 2, NULL, NULL),
(216, 96, 2, NULL, NULL),
(217, 102, 2, NULL, NULL),
(218, 103, 2, NULL, NULL),
(219, 104, 2, NULL, NULL),
(220, 105, 2, NULL, NULL),
(221, 106, 2, NULL, NULL),
(222, 107, 2, NULL, NULL),
(223, 108, 2, NULL, NULL),
(224, 109, 2, NULL, NULL),
(225, 110, 2, NULL, NULL),
(226, 111, 2, NULL, NULL),
(227, 112, 2, NULL, NULL),
(228, 113, 2, NULL, NULL),
(229, 114, 2, NULL, NULL),
(230, 115, 2, NULL, NULL),
(231, 116, 2, NULL, NULL),
(232, 97, 2, NULL, NULL),
(233, 98, 2, NULL, NULL),
(234, 99, 2, NULL, NULL),
(235, 100, 2, NULL, NULL),
(236, 101, 2, NULL, NULL),
(237, 122, 2, NULL, NULL),
(238, 123, 2, NULL, NULL),
(239, 124, 2, NULL, NULL),
(240, 125, 2, NULL, NULL),
(241, 126, 2, NULL, NULL),
(242, 117, 2, NULL, NULL),
(243, 118, 2, NULL, NULL),
(244, 119, 2, NULL, NULL),
(245, 120, 2, NULL, NULL),
(246, 121, 2, NULL, NULL),
(247, 170000, 2, NULL, NULL),
(248, 170001, 2, NULL, NULL),
(249, 170002, 2, NULL, NULL),
(250, 170003, 2, NULL, NULL),
(251, 170004, 2, NULL, NULL),
(252, 170005, 2, NULL, NULL),
(253, 170006, 2, NULL, NULL),
(254, 170007, 2, NULL, NULL),
(255, 170008, 2, NULL, NULL),
(256, 170009, 2, NULL, NULL),
(257, 170010, 2, NULL, NULL),
(258, 170011, 2, NULL, NULL),
(259, 170012, 2, NULL, NULL),
(260, 170013, 2, NULL, NULL),
(261, 170014, 2, NULL, NULL),
(262, 170015, 2, NULL, NULL),
(263, 170016, 2, NULL, NULL),
(264, 170017, 2, NULL, NULL),
(265, 170018, 2, NULL, NULL),
(266, 170019, 2, NULL, NULL),
(267, 170020, 2, NULL, NULL),
(268, 170021, 2, NULL, NULL),
(269, 170022, 2, NULL, NULL),
(270, 170023, 2, NULL, NULL),
(271, 170024, 2, NULL, NULL),
(272, 170025, 2, NULL, NULL),
(273, 170026, 2, NULL, NULL),
(274, 170027, 2, NULL, NULL),
(275, 170028, 2, NULL, NULL),
(276, 170029, 2, NULL, NULL),
(277, 170030, 2, NULL, NULL),
(278, 170031, 2, NULL, NULL),
(279, 170032, 2, NULL, NULL),
(280, 170033, 2, NULL, NULL),
(281, 170034, 2, NULL, NULL),
(282, 170035, 2, NULL, NULL),
(283, 170036, 2, NULL, NULL),
(284, 245, 2, NULL, NULL),
(285, 246, 2, NULL, NULL),
(286, 247, 2, NULL, NULL),
(287, 248, 2, NULL, NULL),
(288, 249, 2, NULL, NULL),
(289, 174, 2, NULL, NULL),
(290, 175, 2, NULL, NULL),
(291, 176, 2, NULL, NULL),
(292, 177, 2, NULL, NULL),
(293, 178, 2, NULL, NULL),
(294, 198, 2, NULL, NULL),
(295, 179, 2, NULL, NULL),
(296, 180, 2, NULL, NULL),
(297, 181, 2, NULL, NULL),
(298, 182, 2, NULL, NULL),
(299, 183, 2, NULL, NULL),
(300, 184, 2, NULL, NULL),
(301, 185, 2, NULL, NULL),
(302, 186, 2, NULL, NULL),
(303, 187, 2, NULL, NULL),
(304, 188, 2, NULL, NULL),
(305, 189, 2, NULL, NULL),
(306, 190, 2, NULL, NULL),
(307, 191, 2, NULL, NULL),
(308, 192, 2, NULL, NULL),
(309, 193, 2, NULL, NULL),
(310, 194, 2, NULL, NULL),
(311, 195, 2, NULL, NULL),
(312, 196, 2, NULL, NULL),
(313, 197, 2, NULL, NULL),
(314, 47, 4, NULL, NULL),
(315, 48, 4, NULL, NULL),
(316, 49, 4, NULL, NULL),
(317, 50, 4, NULL, NULL),
(318, 51, 4, NULL, NULL),
(319, 52, 4, NULL, NULL),
(320, 53, 4, NULL, NULL),
(321, 54, 4, NULL, NULL),
(322, 55, 4, NULL, NULL),
(323, 56, 4, NULL, NULL),
(324, 57, 4, NULL, NULL),
(325, 58, 4, NULL, NULL),
(326, 59, 4, NULL, NULL),
(327, 60, 4, NULL, NULL),
(328, 61, 4, NULL, NULL),
(329, 62, 4, NULL, NULL),
(330, 63, 4, NULL, NULL),
(331, 64, 4, NULL, NULL),
(332, 65, 4, NULL, NULL),
(333, 66, 4, NULL, NULL),
(334, 67, 4, NULL, NULL),
(335, 68, 4, NULL, NULL),
(336, 69, 4, NULL, NULL),
(337, 70, 4, NULL, NULL),
(338, 71, 4, NULL, NULL),
(339, 72, 4, NULL, NULL),
(340, 73, 4, NULL, NULL),
(341, 74, 4, NULL, NULL),
(342, 75, 4, NULL, NULL),
(343, 76, 4, NULL, NULL),
(344, 137, 4, NULL, NULL),
(345, 138, 4, NULL, NULL),
(346, 139, 4, NULL, NULL),
(347, 77, 4, NULL, NULL),
(348, 78, 4, NULL, NULL),
(349, 79, 4, NULL, NULL),
(350, 80, 4, NULL, NULL),
(351, 81, 4, NULL, NULL),
(352, 199, 4, NULL, NULL),
(353, 200, 4, NULL, NULL),
(354, 201, 4, NULL, NULL),
(355, 202, 4, NULL, NULL),
(356, 203, 4, NULL, NULL),
(357, 204, 4, NULL, NULL),
(358, 205, 4, NULL, NULL),
(359, 206, 4, NULL, NULL),
(360, 207, 4, NULL, NULL),
(361, 208, 4, NULL, NULL),
(362, 209, 4, NULL, NULL),
(363, 210, 4, NULL, NULL),
(364, 211, 4, NULL, NULL),
(365, 212, 4, NULL, NULL),
(366, 213, 4, NULL, NULL),
(367, 214, 4, NULL, NULL),
(368, 215, 4, NULL, NULL),
(369, 216, 4, NULL, NULL),
(370, 221, 4, NULL, NULL),
(371, 127, 5, NULL, NULL),
(372, 128, 5, NULL, NULL),
(373, 129, 5, NULL, NULL),
(374, 130, 5, NULL, NULL),
(375, 131, 5, NULL, NULL),
(376, 132, 5, NULL, NULL),
(377, 133, 5, NULL, NULL),
(378, 134, 5, NULL, NULL),
(379, 135, 5, NULL, NULL),
(380, 136, 5, NULL, NULL),
(381, 47, 5, NULL, NULL),
(382, 48, 5, NULL, NULL),
(383, 49, 5, NULL, NULL),
(384, 50, 5, NULL, NULL),
(385, 51, 5, NULL, NULL),
(386, 52, 5, NULL, NULL),
(387, 53, 5, NULL, NULL),
(388, 54, 5, NULL, NULL),
(389, 55, 5, NULL, NULL),
(390, 56, 5, NULL, NULL),
(391, 57, 5, NULL, NULL),
(392, 58, 5, NULL, NULL),
(393, 59, 5, NULL, NULL),
(394, 60, 5, NULL, NULL),
(395, 61, 5, NULL, NULL),
(396, 62, 5, NULL, NULL),
(397, 63, 5, NULL, NULL),
(398, 64, 5, NULL, NULL),
(399, 65, 5, NULL, NULL),
(400, 66, 5, NULL, NULL),
(401, 67, 5, NULL, NULL),
(402, 68, 5, NULL, NULL),
(403, 69, 5, NULL, NULL),
(404, 70, 5, NULL, NULL),
(405, 71, 5, NULL, NULL),
(406, 72, 5, NULL, NULL),
(407, 73, 5, NULL, NULL),
(408, 74, 5, NULL, NULL),
(409, 75, 5, NULL, NULL),
(410, 76, 5, NULL, NULL),
(411, 137, 5, NULL, NULL),
(412, 138, 5, NULL, NULL),
(413, 139, 5, NULL, NULL),
(414, 77, 5, NULL, NULL),
(415, 78, 5, NULL, NULL),
(416, 79, 5, NULL, NULL),
(417, 80, 5, NULL, NULL),
(418, 81, 5, NULL, NULL),
(419, 199, 5, NULL, NULL),
(420, 200, 5, NULL, NULL),
(421, 201, 5, NULL, NULL),
(422, 202, 5, NULL, NULL),
(423, 203, 5, NULL, NULL),
(424, 204, 5, NULL, NULL),
(425, 205, 5, NULL, NULL),
(426, 206, 5, NULL, NULL),
(427, 207, 5, NULL, NULL),
(428, 208, 5, NULL, NULL),
(429, 209, 5, NULL, NULL),
(430, 210, 5, NULL, NULL),
(431, 211, 5, NULL, NULL),
(432, 212, 5, NULL, NULL),
(433, 213, 5, NULL, NULL),
(434, 214, 5, NULL, NULL),
(435, 215, 5, NULL, NULL),
(436, 72, 6, NULL, NULL),
(437, 73, 6, NULL, NULL),
(438, 74, 6, NULL, NULL),
(439, 75, 6, NULL, NULL),
(440, 76, 6, NULL, NULL),
(441, 137, 6, NULL, NULL),
(442, 138, 6, NULL, NULL),
(443, 139, 6, NULL, NULL),
(444, 77, 6, NULL, NULL),
(445, 78, 6, NULL, NULL),
(446, 79, 6, NULL, NULL),
(447, 80, 6, NULL, NULL),
(448, 81, 6, NULL, NULL),
(449, 127, 3, NULL, NULL),
(450, 128, 3, NULL, NULL),
(451, 129, 3, NULL, NULL),
(452, 130, 3, NULL, NULL),
(453, 131, 3, NULL, NULL),
(454, 132, 3, NULL, NULL),
(455, 133, 3, NULL, NULL),
(456, 134, 3, NULL, NULL),
(457, 135, 3, NULL, NULL),
(458, 136, 3, NULL, NULL),
(459, 140, 3, NULL, NULL),
(460, 141, 3, NULL, NULL),
(461, 142, 3, NULL, NULL),
(462, 143, 3, NULL, NULL),
(463, 144, 3, NULL, NULL),
(464, 145, 3, NULL, NULL),
(465, 146, 3, NULL, NULL),
(466, 147, 3, NULL, NULL),
(467, 148, 3, NULL, NULL),
(468, 149, 3, NULL, NULL),
(469, 150, 3, NULL, NULL),
(470, 151, 3, NULL, NULL),
(471, 152, 3, NULL, NULL),
(472, 153, 3, NULL, NULL),
(473, 154, 3, NULL, NULL),
(474, 155, 3, NULL, NULL),
(475, 156, 3, NULL, NULL),
(476, 157, 3, NULL, NULL),
(477, 158, 3, NULL, NULL),
(478, 159, 3, NULL, NULL),
(479, 160, 3, NULL, NULL),
(480, 161, 3, NULL, NULL),
(481, 162, 3, NULL, NULL),
(482, 163, 3, NULL, NULL),
(483, 164, 3, NULL, NULL),
(484, 165, 3, NULL, NULL),
(485, 166, 3, NULL, NULL),
(486, 167, 3, NULL, NULL),
(487, 168, 3, NULL, NULL),
(488, 169, 3, NULL, NULL),
(489, 170, 3, NULL, NULL),
(490, 171, 3, NULL, NULL),
(491, 172, 3, NULL, NULL),
(492, 173, 3, NULL, NULL),
(493, 150000, 3, NULL, NULL),
(494, 150001, 3, NULL, NULL),
(495, 150002, 3, NULL, NULL),
(496, 150003, 3, NULL, NULL),
(497, 150004, 3, NULL, NULL),
(498, 150005, 3, NULL, NULL),
(499, 150006, 3, NULL, NULL),
(500, 150007, 3, NULL, NULL),
(501, 150008, 3, NULL, NULL),
(502, 150009, 3, NULL, NULL),
(503, 150010, 3, NULL, NULL),
(504, 150011, 3, NULL, NULL),
(505, 150012, 3, NULL, NULL),
(506, 150033, 3, NULL, NULL),
(507, 150034, 3, NULL, NULL),
(508, 150035, 3, NULL, NULL),
(509, 150036, 3, NULL, NULL),
(510, 150037, 3, NULL, NULL),
(511, 150038, 3, NULL, NULL),
(512, 150039, 3, NULL, NULL),
(513, 150040, 3, NULL, NULL),
(514, 150041, 3, NULL, NULL),
(515, 150042, 3, NULL, NULL),
(516, 150043, 3, NULL, NULL),
(517, 150044, 3, NULL, NULL),
(518, 150045, 3, NULL, NULL),
(519, 150046, 3, NULL, NULL),
(520, 150047, 3, NULL, NULL),
(521, 150013, 3, NULL, NULL),
(522, 150014, 3, NULL, NULL),
(523, 150015, 3, NULL, NULL),
(524, 150016, 3, NULL, NULL),
(525, 150017, 3, NULL, NULL),
(526, 150018, 3, NULL, NULL),
(527, 150019, 3, NULL, NULL),
(528, 150020, 3, NULL, NULL),
(529, 150021, 3, NULL, NULL),
(530, 150022, 3, NULL, NULL),
(531, 150023, 3, NULL, NULL),
(532, 150024, 3, NULL, NULL),
(533, 150025, 3, NULL, NULL),
(534, 150026, 3, NULL, NULL),
(535, 150027, 3, NULL, NULL),
(536, 150028, 3, NULL, NULL),
(537, 150029, 3, NULL, NULL),
(538, 150030, 3, NULL, NULL),
(539, 150031, 3, NULL, NULL),
(540, 150032, 3, NULL, NULL),
(541, 150048, 3, NULL, NULL),
(542, 150049, 3, NULL, NULL),
(543, 150050, 3, NULL, NULL),
(544, 150051, 3, NULL, NULL),
(545, 150052, 3, NULL, NULL),
(546, 150053, 3, NULL, NULL),
(547, 150054, 3, NULL, NULL),
(548, 150055, 3, NULL, NULL),
(549, 150056, 3, NULL, NULL),
(550, 150057, 3, NULL, NULL),
(551, 150058, 3, NULL, NULL),
(552, 150059, 3, NULL, NULL),
(553, 150060, 3, NULL, NULL),
(554, 150061, 3, NULL, NULL),
(555, 150062, 3, NULL, NULL),
(556, 150063, 3, NULL, NULL),
(557, 150064, 3, NULL, NULL),
(558, 150065, 3, NULL, NULL),
(559, 150066, 3, NULL, NULL),
(560, 150067, 3, NULL, NULL),
(561, 150068, 3, NULL, NULL),
(562, 150069, 3, NULL, NULL),
(563, 150070, 3, NULL, NULL),
(564, 150071, 3, NULL, NULL),
(565, 150072, 3, NULL, NULL),
(566, 150073, 3, NULL, NULL),
(567, 150074, 3, NULL, NULL),
(568, 150075, 3, NULL, NULL),
(569, 150076, 3, NULL, NULL),
(570, 150077, 3, NULL, NULL),
(571, 150078, 3, NULL, NULL),
(572, 150079, 3, NULL, NULL),
(573, 150080, 3, NULL, NULL),
(574, 47, 3, NULL, NULL),
(575, 48, 3, NULL, NULL),
(576, 49, 3, NULL, NULL),
(577, 50, 3, NULL, NULL),
(578, 51, 3, NULL, NULL),
(579, 52, 3, NULL, NULL),
(580, 53, 3, NULL, NULL),
(581, 54, 3, NULL, NULL),
(582, 55, 3, NULL, NULL),
(583, 56, 3, NULL, NULL),
(584, 57, 3, NULL, NULL),
(585, 58, 3, NULL, NULL),
(586, 59, 3, NULL, NULL),
(587, 60, 3, NULL, NULL),
(588, 61, 3, NULL, NULL),
(589, 62, 3, NULL, NULL),
(590, 63, 3, NULL, NULL),
(591, 64, 3, NULL, NULL),
(592, 65, 3, NULL, NULL),
(593, 66, 3, NULL, NULL),
(594, 67, 3, NULL, NULL),
(595, 68, 3, NULL, NULL),
(596, 69, 3, NULL, NULL),
(597, 70, 3, NULL, NULL),
(598, 71, 3, NULL, NULL),
(599, 72, 3, NULL, NULL),
(600, 73, 3, NULL, NULL),
(601, 74, 3, NULL, NULL),
(602, 75, 3, NULL, NULL),
(603, 76, 3, NULL, NULL),
(604, 137, 3, NULL, NULL),
(605, 138, 3, NULL, NULL),
(606, 139, 3, NULL, NULL),
(607, 77, 3, NULL, NULL),
(608, 78, 3, NULL, NULL),
(609, 79, 3, NULL, NULL),
(610, 80, 3, NULL, NULL),
(611, 81, 3, NULL, NULL),
(612, 199, 3, NULL, NULL),
(613, 200, 3, NULL, NULL),
(614, 201, 3, NULL, NULL),
(615, 202, 3, NULL, NULL),
(616, 203, 3, NULL, NULL),
(617, 204, 3, NULL, NULL),
(618, 205, 3, NULL, NULL),
(619, 206, 3, NULL, NULL),
(620, 207, 3, NULL, NULL),
(621, 208, 3, NULL, NULL),
(622, 209, 3, NULL, NULL),
(623, 210, 3, NULL, NULL),
(624, 211, 3, NULL, NULL),
(625, 212, 3, NULL, NULL),
(626, 213, 3, NULL, NULL),
(627, 214, 3, NULL, NULL),
(628, 215, 3, NULL, NULL),
(629, 217, 3, NULL, NULL),
(630, 218, 3, NULL, NULL),
(631, 219, 3, NULL, NULL),
(632, 220, 3, NULL, NULL),
(633, 216, 3, NULL, NULL),
(634, 221, 3, NULL, NULL),
(635, 222, 3, NULL, NULL),
(636, 223, 3, NULL, NULL),
(637, 224, 3, NULL, NULL),
(638, 82, 3, NULL, NULL),
(639, 83, 3, NULL, NULL),
(640, 84, 3, NULL, NULL),
(641, 85, 3, NULL, NULL),
(642, 86, 3, NULL, NULL),
(643, 87, 3, NULL, NULL),
(644, 88, 3, NULL, NULL),
(645, 89, 3, NULL, NULL),
(646, 90, 3, NULL, NULL),
(647, 91, 3, NULL, NULL),
(648, 92, 3, NULL, NULL),
(649, 93, 3, NULL, NULL),
(650, 94, 3, NULL, NULL),
(651, 95, 3, NULL, NULL),
(652, 96, 3, NULL, NULL),
(653, 102, 3, NULL, NULL),
(654, 103, 3, NULL, NULL),
(655, 104, 3, NULL, NULL),
(656, 105, 3, NULL, NULL),
(657, 106, 3, NULL, NULL),
(658, 107, 3, NULL, NULL),
(659, 108, 3, NULL, NULL),
(660, 109, 3, NULL, NULL),
(661, 110, 3, NULL, NULL),
(662, 111, 3, NULL, NULL),
(663, 112, 3, NULL, NULL),
(664, 113, 3, NULL, NULL),
(665, 114, 3, NULL, NULL),
(666, 115, 3, NULL, NULL),
(667, 116, 3, NULL, NULL),
(668, 97, 3, NULL, NULL),
(669, 98, 3, NULL, NULL),
(670, 99, 3, NULL, NULL),
(671, 100, 3, NULL, NULL),
(672, 101, 3, NULL, NULL),
(673, 122, 3, NULL, NULL),
(674, 123, 3, NULL, NULL),
(675, 124, 3, NULL, NULL),
(676, 125, 3, NULL, NULL),
(677, 126, 3, NULL, NULL),
(678, 117, 3, NULL, NULL),
(679, 118, 3, NULL, NULL),
(680, 119, 3, NULL, NULL),
(681, 120, 3, NULL, NULL),
(682, 121, 3, NULL, NULL),
(683, 170000, 3, NULL, NULL),
(684, 170001, 3, NULL, NULL),
(685, 170002, 3, NULL, NULL),
(686, 170003, 3, NULL, NULL),
(687, 170004, 3, NULL, NULL),
(688, 170005, 3, NULL, NULL),
(689, 170006, 3, NULL, NULL),
(690, 170007, 3, NULL, NULL),
(691, 170008, 3, NULL, NULL),
(692, 170009, 3, NULL, NULL),
(693, 170010, 3, NULL, NULL),
(694, 170011, 3, NULL, NULL),
(695, 170012, 3, NULL, NULL),
(696, 170013, 3, NULL, NULL),
(697, 170014, 3, NULL, NULL),
(698, 170015, 3, NULL, NULL),
(699, 170016, 3, NULL, NULL),
(700, 170017, 3, NULL, NULL),
(701, 170018, 3, NULL, NULL),
(702, 170019, 3, NULL, NULL),
(703, 170020, 3, NULL, NULL),
(704, 170021, 3, NULL, NULL),
(705, 170022, 3, NULL, NULL),
(706, 170023, 3, NULL, NULL),
(707, 170024, 3, NULL, NULL),
(708, 170025, 3, NULL, NULL),
(709, 170026, 3, NULL, NULL),
(710, 170027, 3, NULL, NULL),
(711, 170028, 3, NULL, NULL),
(712, 170029, 3, NULL, NULL),
(713, 170030, 3, NULL, NULL),
(714, 170031, 3, NULL, NULL),
(715, 170032, 3, NULL, NULL),
(716, 170033, 3, NULL, NULL),
(717, 170034, 3, NULL, NULL),
(718, 170035, 3, NULL, NULL),
(719, 170036, 3, NULL, NULL),
(720, 245, 3, NULL, NULL),
(721, 246, 3, NULL, NULL),
(722, 247, 3, NULL, NULL),
(723, 248, 3, NULL, NULL),
(724, 249, 3, NULL, NULL),
(725, 174, 3, NULL, NULL),
(726, 175, 3, NULL, NULL),
(727, 176, 3, NULL, NULL),
(728, 177, 3, NULL, NULL),
(729, 178, 3, NULL, NULL),
(730, 198, 3, NULL, NULL),
(731, 179, 3, NULL, NULL),
(732, 180, 3, NULL, NULL),
(733, 181, 3, NULL, NULL),
(734, 182, 3, NULL, NULL),
(735, 183, 3, NULL, NULL),
(736, 184, 3, NULL, NULL),
(737, 185, 3, NULL, NULL),
(738, 186, 3, NULL, NULL),
(739, 187, 3, NULL, NULL),
(740, 188, 3, NULL, NULL),
(741, 189, 3, NULL, NULL),
(742, 190, 3, NULL, NULL),
(743, 191, 3, NULL, NULL),
(744, 192, 3, NULL, NULL),
(745, 193, 3, NULL, NULL),
(746, 194, 3, NULL, NULL),
(747, 195, 3, NULL, NULL),
(748, 196, 3, NULL, NULL),
(749, 197, 3, NULL, NULL),
(750, 7, 2, NULL, NULL),
(751, 8, 2, NULL, NULL),
(752, 9, 2, NULL, NULL),
(753, 10, 2, NULL, NULL),
(754, 11, 2, NULL, NULL),
(755, 12, 2, NULL, NULL),
(756, 13, 2, NULL, NULL),
(757, 14, 2, NULL, NULL),
(758, 15, 2, NULL, NULL),
(759, 16, 2, NULL, NULL),
(760, 17, 2, NULL, NULL),
(761, 18, 2, NULL, NULL),
(762, 19, 2, NULL, NULL),
(763, 20, 2, NULL, NULL),
(764, 21, 2, NULL, NULL),
(765, 22, 2, NULL, NULL),
(766, 23, 2, NULL, NULL),
(767, 24, 2, NULL, NULL),
(768, 25, 2, NULL, NULL),
(769, 26, 2, NULL, NULL),
(770, 27, 2, NULL, NULL),
(771, 28, 2, NULL, NULL),
(772, 29, 2, NULL, NULL),
(773, 30, 2, NULL, NULL),
(774, 31, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `privacy_policies`
--

CREATE TABLE `privacy_policies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `privacy_header_title` varchar(191) DEFAULT NULL,
  `privacy_policy` text DEFAULT NULL,
  `terms_header_title` varchar(191) DEFAULT NULL,
  `terms_condition` text DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `privacy_policies`
--

INSERT INTO `privacy_policies` (`id`, `privacy_header_title`, `privacy_policy`, `terms_header_title`, `terms_condition`, `company_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Privacy & Policy Header', '<p>Privacy &amp; Policy Details</p>', 'Terms & Condition Header', '<p>Terms &amp; Condition Details</p>', 1, 1, NULL, '2024-02-04 11:17:19', '2024-02-04 11:17:19');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `product_type` varchar(16) DEFAULT '0',
  `product_code` varchar(255) DEFAULT '0',
  `purchase_price` decimal(16,2) DEFAULT 0.00,
  `selling_price` decimal(16,2) DEFAULT 0.00,
  `opening_quantity` decimal(16,2) DEFAULT 0.00,
  `current_stock` decimal(16,2) DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_ledgers`
--

CREATE TABLE `product_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `quantity` double(10,2) NOT NULL DEFAULT 0.00,
  `in` double(10,2) NOT NULL DEFAULT 0.00,
  `out` double(10,2) NOT NULL DEFAULT 0.00,
  `wastage` double(10,2) NOT NULL DEFAULT 0.00,
  `sourceable_type` varchar(191) NOT NULL,
  `sourceable_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_metrials`
--

CREATE TABLE `product_metrials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `units_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `item_price` double(10,2) DEFAULT NULL,
  `unit_vat_amount` double(10,2) DEFAULT 0.00,
  `remarks` text DEFAULT NULL,
  `is_bar` tinyint(4) DEFAULT 0,
  `status` tinyint(4) DEFAULT 0,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_stock_transections`
--

CREATE TABLE `product_stock_transections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `factory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `source_type` varchar(191) NOT NULL,
  `source_id` int(11) NOT NULL,
  `date` varchar(191) NOT NULL,
  `quantity` decimal(16,2) NOT NULL DEFAULT 0.00,
  `price` decimal(16,2) NOT NULL DEFAULT 0.00,
  `amount` decimal(16,2) GENERATED ALWAYS AS (`quantity` * `price`) VIRTUAL,
  `stock_type` enum('In','Out') NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `total_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `payment_status` varchar(191) DEFAULT NULL,
  `details` varchar(191) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `email_status` tinyint(1) NOT NULL DEFAULT 0,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_billings`
--

CREATE TABLE `project_billings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `warning_date` date DEFAULT NULL,
  `expired_date` date DEFAULT NULL,
  `total_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `payment_status` varchar(191) DEFAULT NULL,
  `payment_type` int(11) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `transection_no` bigint(20) UNSIGNED DEFAULT NULL,
  `details` varchar(191) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_details`
--

CREATE TABLE `project_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_name_id` bigint(20) UNSIGNED DEFAULT NULL,
  `billing_type` varchar(191) DEFAULT NULL,
  `note` varchar(191) DEFAULT NULL,
  `subscription_fee` decimal(16,2) NOT NULL DEFAULT 0.00,
  `price` decimal(16,2) NOT NULL DEFAULT 0.00,
  `start_date` date DEFAULT NULL,
  `warning_date` date DEFAULT NULL,
  `expired_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_names`
--

CREATE TABLE `project_names` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `purchase_date` date NOT NULL,
  `purchase_reference` varchar(191) DEFAULT NULL,
  `is_approved` tinyint(4) NOT NULL DEFAULT 0,
  `form_number` varchar(191) DEFAULT NULL,
  `total` decimal(20,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_details`
--

CREATE TABLE `purchase_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` double(8,2) NOT NULL DEFAULT 0.00,
  `received_quantity` double(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_receives`
--

CREATE TABLE `purchase_receives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `form_number` varchar(191) DEFAULT NULL,
  `is_approved` tinyint(4) NOT NULL DEFAULT 0,
  `purchase_receive_date` date NOT NULL,
  `purchase_receive_reference` varchar(191) DEFAULT NULL,
  `purchase_challan_number` varchar(191) DEFAULT NULL,
  `challan_image` varchar(191) DEFAULT 'default.png',
  `quantity` decimal(20,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_receive_details`
--

CREATE TABLE `purchase_receive_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `purchase_receive_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_details_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(20,2) NOT NULL DEFAULT 0.00,
  `rate` decimal(20,2) NOT NULL DEFAULT 0.00,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remaining_quantity` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requsition_stocks`
--

CREATE TABLE `requsition_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `type` varchar(191) DEFAULT NULL,
  `source_id` int(11) DEFAULT NULL,
  `source_number` varchar(191) DEFAULT NULL,
  `debit_qty` decimal(15,3) NOT NULL DEFAULT 0.000,
  `credit_qty` decimal(15,3) NOT NULL DEFAULT 0.000,
  `debit_rate` decimal(15,3) NOT NULL DEFAULT 0.000,
  `credit_rate` decimal(15,3) NOT NULL DEFAULT 0.000,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `factory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rest_materials`
--

CREATE TABLE `rest_materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `units_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `opening_balance` int(11) NOT NULL DEFAULT 0,
  `is_bar` int(11) DEFAULT 0,
  `rate` decimal(20,2) NOT NULL DEFAULT 0.00,
  `remaining_quantity` int(11) DEFAULT 0,
  `current_stock` int(11) DEFAULT 0,
  `average_rate` decimal(15,3) DEFAULT 0.000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rest_material_units`
--

CREATE TABLE `rest_material_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `conversion` varchar(191) DEFAULT NULL,
  `is_bar` int(11) DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rmreports`
--

CREATE TABLE `rmreports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `factory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` varchar(191) NOT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock` decimal(8,2) NOT NULL DEFAULT 0.00,
  `avg_rate` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `stock_in` decimal(8,2) DEFAULT NULL,
  `stock_out` decimal(8,2) DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `room_category` bigint(20) UNSIGNED DEFAULT NULL,
  `room_number` int(11) DEFAULT NULL,
  `smoking_status` varchar(191) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `f_r_id_card` varchar(191) DEFAULT NULL,
  `from_date` varchar(191) DEFAULT NULL,
  `to_date` varchar(191) DEFAULT NULL,
  `rent` double DEFAULT NULL,
  `beds` tinyint(4) DEFAULT NULL,
  `max_guests` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `room_category`, `room_number`, `smoking_status`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `f_r_id_card`, `from_date`, `to_date`, `rent`, `beds`, `max_guests`) VALUES
(3, 'Double Quad', 8, 201, 'No', 1, 4, 4, '2024-02-29 05:26:17', '2024-02-29 11:21:45', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Deluxe Queen', 2, 202, 'No', 1, 4, 4, '2024-02-29 05:27:33', '2024-02-29 05:27:33', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(5, 'Deluxe Queen', 2, 203, 'No', 1, 4, 4, '2024-02-29 05:28:04', '2024-02-29 05:28:04', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(6, 'Deluxe Queen', 2, 204, 'No', 1, 4, 4, '2024-02-29 05:28:22', '2024-02-29 11:57:05', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'Deluxe Queen', 2, 205, 'No', 1, 4, 4, '2024-02-29 05:28:44', '2024-02-29 05:28:44', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(8, 'Deluxe Queen', 2, 206, 'No', 1, 4, 4, '2024-02-29 05:29:22', '2024-02-29 05:29:22', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(9, 'Deluxe Queen', 2, 207, 'No', 1, 4, 4, '2024-02-29 05:29:43', '2024-02-29 05:29:43', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(10, 'Deluxe Queen', 2, 208, 'No', 1, 4, 4, '2024-02-29 05:30:23', '2024-02-29 05:30:23', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(11, 'Deluxe Queen', 2, 209, 'No', 1, 4, 4, '2024-02-29 05:30:43', '2024-02-29 05:30:43', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(12, 'Deluxe Queen', 2, 210, 'No', 1, 4, 4, '2024-02-29 05:31:03', '2024-02-29 12:06:21', NULL, NULL, NULL, NULL, NULL, NULL),
(13, 'Deluxe Queen', 2, 211, 'No', 1, 4, 4, '2024-02-29 05:31:51', '2024-02-29 05:31:51', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(14, 'Deluxe Queen', 2, 212, 'No', 1, 4, 4, '2024-02-29 05:32:31', '2024-02-29 05:32:31', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(15, 'Deluxe Queen', 2, 213, 'No', 1, 4, 4, '2024-02-29 05:32:55', '2024-02-29 05:32:55', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(16, 'Deluxe Queen', 2, 214, 'No', 1, 4, 4, '2024-02-29 05:33:13', '2024-02-29 05:33:13', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(17, 'Deluxe Queen', 2, 215, 'No', 1, 4, 4, '2024-02-29 05:33:30', '2024-02-29 05:33:30', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(18, 'Deluxe Queen', 2, 216, 'No', 1, 4, 4, '2024-02-29 05:34:35', '2024-02-29 05:34:35', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(19, 'Deluxe Queen', 2, 217, 'No', 1, 4, 4, '2024-02-29 05:35:08', '2024-02-29 05:35:08', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(20, 'Deluxe Queen', 2, 218, 'No', 1, 4, 4, '2024-02-29 05:35:24', '2024-02-29 05:35:24', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(21, 'Deluxe Queen', 2, 219, 'No', 1, 4, 4, '2024-02-29 05:36:11', '2024-02-29 05:36:11', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(22, 'Deluxe Queen', 2, 220, 'No', 1, 4, 4, '2024-02-29 05:36:46', '2024-02-29 05:36:46', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(23, 'Deluxe Queen', 2, 221, 'No', 1, 4, 4, '2024-02-29 05:37:09', '2024-02-29 05:37:09', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(24, 'Deluxe Queen', 2, 222, 'No', 1, 4, 4, '2024-02-29 05:42:15', '2024-02-29 05:42:15', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(25, 'Deluxe Queen', 2, 223, 'No', 1, 4, 4, '2024-02-29 05:42:29', '2024-02-29 05:42:29', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(26, 'Deluxe Queen', 2, 224, 'No', 1, 4, 4, '2024-02-29 05:42:46', '2024-02-29 05:42:46', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(27, 'Deluxe Queen', 2, 225, 'No', 1, 4, 4, '2024-02-29 05:43:05', '2024-02-29 05:43:05', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(28, 'Deluxe Queen', 2, 226, 'No', 1, 4, 4, '2024-02-29 05:43:17', '2024-02-29 05:43:17', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(29, 'Deluxe Queen', 2, 227, 'No', 1, 4, 4, '2024-02-29 05:43:33', '2024-02-29 05:43:33', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(30, 'Deluxe Queen', 2, 228, 'No', 1, 4, 4, '2024-02-29 05:43:46', '2024-02-29 05:43:46', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(31, 'Deluxe Queen', 2, 229, 'No', 1, 4, 4, '2024-02-29 05:44:03', '2024-02-29 05:44:03', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(32, 'Deluxe Queen', 2, 230, 'No', 1, 4, 4, '2024-02-29 05:44:19', '2024-02-29 05:44:19', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(33, 'Superior Twin', 1, 231, 'No', 1, 4, 4, '2024-02-29 06:18:31', '2024-02-29 10:10:13', NULL, NULL, NULL, NULL, NULL, NULL),
(34, 'Superior Twin', 1, 232, 'No', 1, 4, 4, '2024-02-29 06:18:45', '2024-02-29 06:18:45', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(35, 'Superior Twin', 1, 233, 'No', 1, 4, 4, '2024-02-29 06:18:57', '2024-02-29 06:18:57', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(36, 'Superior Twin', 1, 234, 'No', 1, 4, 4, '2024-02-29 06:19:32', '2024-02-29 06:19:32', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(37, 'Superior Twin', 1, 235, 'No', 1, 4, 4, '2024-02-29 06:19:57', '2024-02-29 06:19:57', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(38, 'Honey Moon Room', 9, 236, 'No', 1, 4, 4, '2024-02-29 06:20:44', '2024-02-29 11:38:39', NULL, NULL, NULL, NULL, NULL, NULL),
(39, 'Double Quad', 8, 237, 'No', 1, 4, 4, '2024-02-29 06:21:01', '2024-02-29 06:21:01', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(40, 'Superior Twin', 1, 238, 'No', 1, 4, 4, '2024-02-29 06:21:19', '2024-02-29 06:21:19', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(41, 'Superior Twin', 1, 239, 'No', 1, 4, 4, '2024-02-29 06:21:58', '2024-02-29 06:21:58', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(42, 'Superior Twin', 1, 240, 'No', 1, 4, 4, '2024-02-29 06:22:16', '2024-02-29 06:22:16', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(43, 'Superior Twin', 1, 241, 'No', 1, 4, 4, '2024-02-29 06:22:29', '2024-02-29 06:22:29', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(44, 'Superior Twin', 1, 242, 'No', 1, 4, 4, '2024-02-29 06:22:43', '2024-02-29 06:22:43', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(45, 'Superior Twin', 1, 243, 'No', 1, 4, 4, '2024-02-29 06:22:59', '2024-02-29 06:22:59', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(46, 'Superior Twin', 1, 244, 'No', 1, 4, 4, '2024-02-29 06:23:26', '2024-02-29 06:23:26', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(47, 'Superior Twin', 1, 245, 'No', 1, 4, 4, '2024-02-29 06:23:42', '2024-02-29 06:23:42', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(48, 'Superior Twin', 1, 246, 'No', 1, 4, 4, '2024-02-29 06:24:17', '2024-02-29 06:24:17', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(49, 'Superior Twin', 1, 247, 'No', 1, 4, 4, '2024-02-29 06:24:38', '2024-02-29 06:24:38', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(50, 'Superior Twin', 1, 248, 'No', 1, 4, 4, '2024-02-29 06:24:49', '2024-02-29 06:24:49', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(51, 'Superior Twin', 1, 249, 'No', 1, 4, 4, '2024-02-29 06:25:00', '2024-02-29 06:25:00', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(52, 'Superior Twin', 1, 250, 'No', 1, 4, 4, '2024-02-29 06:25:10', '2024-02-29 06:25:10', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(53, 'Superior Twin', 1, 251, 'No', 1, 4, 4, '2024-02-29 06:25:23', '2024-02-29 06:25:23', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(54, 'Superior Twin', 1, 252, 'No', 1, 4, 4, '2024-02-29 06:26:16', '2024-02-29 06:26:16', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(55, 'Superior Twin', 1, 253, 'No', 1, 4, 4, '2024-02-29 06:26:31', '2024-02-29 06:26:31', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(56, 'Superior Twin', 1, 254, 'No', 1, 4, 4, '2024-02-29 06:26:46', '2024-02-29 06:26:46', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(57, 'Superior Twin', 1, 255, 'No', 1, 4, 4, '2024-02-29 06:26:57', '2024-02-29 06:26:57', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(58, 'Superior Twin', 1, 256, 'No', 1, 4, 4, '2024-02-29 06:27:59', '2024-02-29 06:27:59', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(59, 'Superior Twin', 1, 257, 'No', 1, 4, 4, '2024-02-29 06:28:10', '2024-02-29 06:28:10', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(60, 'Superior Twin', 1, 258, 'No', 1, 4, 4, '2024-02-29 06:28:24', '2024-02-29 06:28:24', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(61, 'Superior Twin', 1, 259, 'No', 1, 4, 4, '2024-02-29 06:28:33', '2024-02-29 06:28:33', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(62, 'Superior Twin', 1, 260, 'No', 1, 4, 4, '2024-02-29 06:28:44', '2024-02-29 06:28:44', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(63, 'Superior Twin', 1, 261, 'No', 1, 4, 4, '2024-02-29 06:28:54', '2024-02-29 06:28:54', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(64, 'Superior Twin', 1, 262, 'No', 1, 4, 4, '2024-02-29 06:29:03', '2024-02-29 06:29:03', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(65, 'Superior Twin', 1, 263, 'No', 1, 4, 4, '2024-02-29 06:29:13', '2024-02-29 06:29:13', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(66, 'Superior Twin', 1, 264, 'No', 1, 4, 4, '2024-02-29 06:29:33', '2024-02-29 06:29:33', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(67, 'Superior Twin', 1, 265, 'No', 1, 4, 4, '2024-02-29 06:30:00', '2024-02-29 06:30:00', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(68, 'Superior Twin', 1, 266, 'No', 1, 4, 4, '2024-02-29 06:30:11', '2024-02-29 06:30:11', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(69, 'Superior Twin', 1, 267, 'No', 1, 4, 4, '2024-02-29 06:30:28', '2024-02-29 06:30:28', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(70, 'Superior Twin', 1, 268, 'No', 1, 4, 4, '2024-02-29 06:30:37', '2024-02-29 06:30:37', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(71, 'Superior Twin', 1, 269, 'No', 1, 4, 4, '2024-02-29 06:30:49', '2024-02-29 06:30:49', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(72, 'Superior Twin', 1, 270, 'No', 1, 4, 4, '2024-02-29 06:31:04', '2024-02-29 06:31:04', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(73, 'Superior Twin', 1, 2071, 'No', 1, 4, 4, '2024-02-29 06:37:59', '2024-02-29 06:37:59', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(74, 'Superior Twin', 1, 2072, 'No', 1, 4, 4, '2024-02-29 06:38:10', '2024-02-29 06:38:10', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(75, 'Superior Twin', 1, 2073, 'No', 1, 4, 4, '2024-02-29 06:38:30', '2024-02-29 06:38:30', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(76, 'Superior Twin', 1, 2074, 'No', 1, 4, 4, '2024-02-29 06:38:47', '2024-02-29 06:38:47', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(77, 'Superior Twin', 1, 2075, 'No', 1, 4, 4, '2024-02-29 06:39:00', '2024-02-29 06:39:00', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(78, 'Superior Twin', 1, 2076, 'No', 1, 4, 4, '2024-02-29 06:39:20', '2024-02-29 06:39:20', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(79, 'Superior Twin', 1, 2077, 'No', 1, 4, 4, '2024-02-29 06:39:36', '2024-02-29 06:39:36', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(80, 'Superior Twin', 1, 2078, 'No', 1, 4, 4, '2024-02-29 06:39:51', '2024-02-29 06:39:51', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(81, 'Superior Twin', 1, 2079, 'No', 1, 4, 4, '2024-02-29 06:40:07', '2024-02-29 06:40:07', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(82, 'Superior Twin', 1, 2080, 'No', 1, 4, 4, '2024-02-29 06:40:23', '2024-02-29 06:40:23', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(83, 'Superior Twin', 1, 2081, 'No', 1, 4, 4, '2024-02-29 06:40:37', '2024-02-29 06:40:37', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(84, 'Superior Twin', 1, 2082, 'No', 1, 4, 4, '2024-02-29 06:41:17', '2024-02-29 06:41:17', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(85, 'Superior Twin', 1, 2083, 'No', 1, 4, 4, '2024-02-29 06:41:40', '2024-02-29 06:41:40', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(86, 'Superior Twin', 1, 2084, 'No', 1, 4, 4, '2024-02-29 06:41:52', '2024-02-29 06:41:52', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(87, 'VVIP Room', 10, 2085, 'No', 1, 4, 4, '2024-02-29 06:42:11', '2024-02-29 06:42:11', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(88, 'Superior Twin', 1, 2086, 'No', 1, 4, 4, '2024-02-29 06:44:33', '2024-02-29 06:44:33', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(89, 'Deluxe Queen', 2, 2087, 'No', 1, 4, 4, '2024-02-29 06:45:16', '2024-02-29 06:45:16', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(90, 'Deluxe Queen', 2, 2088, 'No', 1, 4, 4, '2024-02-29 06:46:01', '2024-02-29 06:46:01', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(91, 'Deluxe Queen', 2, 2089, 'No', 1, 4, 4, '2024-02-29 06:46:31', '2024-02-29 11:21:15', NULL, NULL, NULL, NULL, NULL, NULL),
(92, 'Deluxe Queen', 2, 2090, 'No', 1, 4, 4, '2024-02-29 06:47:35', '2024-02-29 06:47:35', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(93, 'Double Quad', 8, 2091, 'No', 1, 4, 4, '2024-02-29 06:47:50', '2024-02-29 06:47:50', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(94, 'Double Quad', 8, 2092, 'No', 1, 4, 4, '2024-02-29 06:48:04', '2024-02-29 06:48:04', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(95, 'Deluxe Queen', 2, 2093, 'No', 1, 4, 4, '2024-02-29 06:48:23', '2024-02-29 06:48:23', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(96, 'Deluxe Queen', 2, 2094, 'No', 1, 4, 4, '2024-02-29 06:48:41', '2024-02-29 06:48:41', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(97, 'Deluxe Queen', 2, 2095, 'No', 1, 4, 4, '2024-02-29 06:49:00', '2024-02-29 06:49:00', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(98, 'Deluxe Queen', 2, 2096, 'No', 1, 4, 4, '2024-02-29 06:49:16', '2024-02-29 06:49:16', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(99, 'Deluxe Queen', 2, 2097, 'No', 1, 4, 4, '2024-02-29 06:49:28', '2024-02-29 06:49:28', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(100, 'Deluxe Queen', 2, 2098, 'No', 1, 4, 4, '2024-02-29 06:49:44', '2024-02-29 06:49:44', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(101, 'Deluxe Queen', 2, 2099, 'No', 1, 4, 4, '2024-02-29 06:50:26', '2024-02-29 06:50:26', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(102, 'Deluxe Queen', 2, 2100, 'No', 1, 4, 4, '2024-02-29 06:50:50', '2024-02-29 06:50:50', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(103, 'Deluxe Queen', 2, 2101, 'No', 1, 4, 4, '2024-02-29 06:53:37', '2024-02-29 06:53:37', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(104, 'Deluxe Queen', 2, 2102, 'No', 1, 4, 4, '2024-02-29 06:56:41', '2024-02-29 06:56:41', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL),
(105, 'Deluxe Queen', 2, 2103, 'No', 1, 4, 4, '2024-02-29 06:57:29', '2024-02-29 06:57:29', NULL, '2024-02-29', '2024-02-29', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room_aminities`
--

CREATE TABLE `room_aminities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `aminities_icon` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_aminities`
--

INSERT INTO `room_aminities` (`id`, `name`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `aminities_icon`) VALUES
(1, 'AC', 1, 1, 1, '2024-02-01 17:51:20', '2024-02-18 05:51:50', NULL),
(2, 'Shower', 1, 1, 1, '2024-02-01 17:51:20', '2024-02-01 17:51:20', NULL),
(3, 'TV', 1, 1, 1, '2024-02-01 17:51:20', '2024-02-01 17:51:20', NULL),
(4, 'Kitchen', 1, 1, 1, '2024-02-01 17:51:20', '2024-02-01 17:51:20', NULL),
(5, 'Fridge', 1, 1, 1, '2024-02-01 17:51:20', '2024-02-01 17:51:20', NULL),
(6, 'Guizer', 1, 1, 1, '2024-02-01 17:51:20', '2024-02-01 17:51:20', NULL),
(7, 'Restaurant', 1, 1, 1, '2024-02-01 17:51:20', '2024-02-01 17:51:20', NULL),
(8, 'Outdoor Swimming Pool', 1, 1, 1, '2024-02-01 17:51:20', '2024-02-18 05:52:18', NULL),
(9, 'Indoor Swimming Pool\r\n', 1, 1, 1, '2024-02-01 17:51:20', '2024-02-18 05:52:18', NULL),
(10, 'Private Parking', 1, 1, 1, '2024-02-01 17:51:20', '2024-02-18 05:52:18', NULL),
(11, 'Meeting Room', 1, 1, 1, '2024-02-01 17:51:20', '2024-02-18 05:52:18', NULL),
(12, '24hour Front Desk', 1, 1, 1, '2024-02-01 17:51:20', '2024-02-18 05:52:18', NULL),
(13, 'Wi-Fi in Public Areas', 1, 1, 1, '2024-02-01 17:51:20', '2024-02-18 05:52:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `room_categories`
--

CREATE TABLE `room_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `guest_capacity` int(11) DEFAULT NULL,
  `vat` int(11) DEFAULT NULL,
  `description` varchar(191) DEFAULT NULL,
  `room_aminities` varchar(191) DEFAULT NULL,
  `can_sleep` int(11) DEFAULT NULL,
  `bed_details` varchar(191) DEFAULT NULL,
  `room_sqft` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `url_slug` varchar(191) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `allow_guest_wise_price` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_categories`
--

INSERT INTO `room_categories` (`id`, `name`, `price`, `guest_capacity`, `vat`, `description`, `room_aminities`, `can_sleep`, `bed_details`, `room_sqft`, `status`, `url_slug`, `created_by`, `updated_by`, `created_at`, `updated_at`, `allow_guest_wise_price`) VALUES
(1, 'Superior Twin', 183, 2, 0, 'Superior Twin for 2 Persons', '1,2,3,4,10,12,13', 2, 'Two Single Bed', NULL, 1, 'superior-twin', 1, 1, '2024-02-01 17:51:20', '2024-02-29 05:48:50', 0),
(2, 'Deluxe Queen', 183, 2, 0, 'Deluxe Queen for 2 persons', '1,2,3,10,12,13', 2, 'Queen Bed', NULL, 1, 'deluxe-queen', 1, 1, '2024-02-18 05:40:06', '2024-02-29 05:49:56', 0),
(8, 'Double Quad', 309, 2, 0, NULL, '12,13', NULL, NULL, NULL, 1, 'double-quad', 1, 1, '2024-02-29 05:20:44', '2024-02-29 05:43:19', 0),
(9, 'Honey Moon Room', 300, 2, 0, NULL, '1,3,6,12,13', NULL, NULL, NULL, 1, 'honey-moon-room', 1, 1, '2024-02-29 05:45:24', '2024-02-29 05:45:24', 0),
(10, 'VVIP Room', 420, 2, 0, NULL, '1,2,3,5,6,7,11,12,13', NULL, NULL, NULL, 1, 'vvip-room', 1, 1, '2024-02-29 05:47:10', '2024-02-29 05:47:10', 0);

-- --------------------------------------------------------

--
-- Table structure for table `room_logs`
--

CREATE TABLE `room_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `date` varchar(191) NOT NULL,
  `status` varchar(191) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `note` text DEFAULT NULL,
  `booked_by` bigint(20) UNSIGNED DEFAULT NULL,
  `received_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_logs`
--

INSERT INTO `room_logs` (`id`, `created_by`, `room_id`, `date`, `status`, `remarks`, `created_at`, `updated_at`, `updated_by`, `note`, `booked_by`, `received_by`) VALUES
(1, 4, 3, '2024-02-29', 'Ready', 'Room status changed to Ready by @frontdesk1', '2024-02-29 06:16:36', '2024-02-29 06:16:36', 4, '', 4, 4),
(2, 4, 3, '2024-02-29', 'Dirty', 'Checkout and Room status goes to dirty by @ frontdesk1', '2024-02-29 11:15:11', '2024-02-29 11:15:11', 4, '', 4, 4),
(3, 4, 91, '2024-02-29', 'Dirty', 'Checkout and Room status goes to dirty by @ frontdesk1', '2024-02-29 11:17:12', '2024-02-29 11:17:12', 4, '', 4, 4),
(4, 4, 91, '2024-02-29', 'Dirty', 'Checkout and Room status goes to dirty by @ frontdesk1', '2024-02-29 11:19:53', '2024-02-29 11:19:53', 4, '', 4, 4),
(5, 4, 91, '2024-02-29', 'Dirty', 'Checkout and Room status goes to dirty by @ frontdesk1', '2024-02-29 11:20:13', '2024-02-29 11:20:13', 4, '', 4, 4),
(6, 4, 38, '2024-02-29', 'Dirty', 'Checkout and Room status goes to dirty by @ frontdesk1', '2024-02-29 11:36:39', '2024-02-29 11:36:39', 4, '', 4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `room_photos`
--

CREATE TABLE `room_photos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `relative_path` varchar(191) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_photos`
--

INSERT INTO `room_photos` (`id`, `name`, `category_id`, `relative_path`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'room_14122021_61b8334cba887.jpg', 1, 'assets/uploads/hotel/room/', 1, NULL, '2024-02-01 17:51:20', '2024-02-01 17:51:20'),
(4, 'room_22022024_65d77a0255d39.jpg', 1, 'assets/uploads/hotel/room/', NULL, NULL, '2024-02-22 16:44:50', '2024-02-22 16:44:50'),
(5, 'room_22022024_65d77a7cc0fa7.jpg', 2, 'assets/uploads/hotel/room/', NULL, NULL, '2024-02-22 16:46:52', '2024-02-22 16:46:52'),
(7, 'room_29022024_65e019777b08f.jpg', 8, 'assets/uploads/hotel/room/', NULL, NULL, '2024-02-29 05:43:19', '2024-02-29 05:43:19'),
(8, 'room_29022024_65e019f4a93f7.jpg', 9, 'assets/uploads/hotel/room/', NULL, NULL, '2024-02-29 05:45:24', '2024-02-29 05:45:24'),
(9, 'room_29022024_65e01a5e39764.jpg', 10, 'assets/uploads/hotel/room/', NULL, NULL, '2024-02-29 05:47:10', '2024-02-29 05:47:10');

-- --------------------------------------------------------

--
-- Table structure for table `room_prices`
--

CREATE TABLE `room_prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_category_id` bigint(20) UNSIGNED NOT NULL,
  `capacity` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_finish_good_details`
--

CREATE TABLE `rst_finish_good_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `production_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `item_price` double(10,2) DEFAULT NULL,
  `unit_vat_amount` double(10,2) DEFAULT 0.00,
  `is_bar` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_material_purchase`
--

CREATE TABLE `rst_material_purchase` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `manufacturer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `challan_no` varchar(191) DEFAULT NULL,
  `challan_id` varchar(191) DEFAULT NULL,
  `date` date NOT NULL,
  `subtotal` double(12,2) NOT NULL,
  `total_vat` double(12,2) DEFAULT NULL,
  `payable_amount` double(12,2) NOT NULL,
  `discount` double(12,2) NOT NULL,
  `paid_amount` double(12,2) NOT NULL,
  `due_amount` double(10,2) NOT NULL DEFAULT 0.00,
  `change_amount` double(10,2) NOT NULL DEFAULT 0.00,
  `is_bar` tinyint(4) DEFAULT 0,
  `is_approved` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_material_purchase_details`
--

CREATE TABLE `rst_material_purchase_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` double NOT NULL DEFAULT 0,
  `item_price` double NOT NULL,
  `unit_vat_amount` double DEFAULT 0,
  `is_bar` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_metrial_details`
--

CREATE TABLE `rst_metrial_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `production_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `item_price` double(10,2) DEFAULT NULL,
  `unit_vat_amount` double(10,2) DEFAULT 0.00,
  `is_bar` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_productions`
--

CREATE TABLE `rst_productions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `challan_no` varchar(191) DEFAULT NULL,
  `date` date NOT NULL,
  `subtotal` double(12,2) DEFAULT NULL,
  `total_vat` double(12,2) DEFAULT NULL,
  `payable_amount` double(12,2) DEFAULT NULL,
  `discount` double(12,2) DEFAULT NULL,
  `paid_amount` double(12,2) DEFAULT NULL,
  `due_amount` double(10,2) DEFAULT 0.00,
  `change_amount` double(10,2) DEFAULT 0.00,
  `is_bar` tinyint(4) DEFAULT 0,
  `is_approved` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_products`
--

CREATE TABLE `rst_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `barcode` varchar(191) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `stock_limit` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'stok limit value',
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_cost` decimal(8,2) NOT NULL DEFAULT 0.00,
  `sale_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `vat_amount` decimal(10,4) DEFAULT NULL,
  `opening_quantity` decimal(8,2) DEFAULT NULL,
  `available_quantity` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pack_size` decimal(8,2) DEFAULT NULL,
  `pack_quantity` int(11) DEFAULT NULL,
  `is_bar` tinyint(4) DEFAULT 0,
  `pack_unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_matrial` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_product_brands`
--

CREATE TABLE `rst_product_brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_bar` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_product_categories`
--

CREATE TABLE `rst_product_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_bar` tinyint(4) DEFAULT 0,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rst_product_categories`
--

INSERT INTO `rst_product_categories` (`id`, `company_id`, `created_by`, `updated_by`, `name`, `type`, `status`, `created_at`, `updated_at`, `is_bar`, `parent_id`) VALUES
(1, 1, 1, NULL, 'test', 1, 1, '2024-02-04 15:00:13', '2024-02-04 15:00:13', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rst_product_ledgers`
--

CREATE TABLE `rst_product_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `quantity` double(10,2) NOT NULL DEFAULT 0.00,
  `in` double(10,2) NOT NULL DEFAULT 0.00,
  `out` double(10,2) NOT NULL DEFAULT 0.00,
  `wastage` double(10,2) NOT NULL DEFAULT 0.00,
  `sourceable_type` varchar(191) NOT NULL,
  `sourceable_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_bar` tinyint(4) DEFAULT 0,
  `audit_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_product_package`
--

CREATE TABLE `rst_product_package` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `price` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `quantity` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `discount` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_product_package_details`
--

CREATE TABLE `rst_product_package_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_product_stocks`
--

CREATE TABLE `rst_product_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock_limitation` int(11) DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `opening_quantity` double(10,2) NOT NULL DEFAULT 0.00,
  `purchased_quantity` double(10,2) NOT NULL DEFAULT 0.00,
  `sold_quantity` double(10,2) NOT NULL DEFAULT 0.00,
  `return_quantity` double(10,2) DEFAULT 0.00,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_bar` tinyint(4) DEFAULT 0,
  `available_quantity` decimal(16,6) GENERATED ALWAYS AS (`opening_quantity` + `purchased_quantity` + `return_quantity` - `sold_quantity`) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_product_units`
--

CREATE TABLE `rst_product_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_bar` tinyint(4) DEFAULT 0,
  `type` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rst_product_units`
--

INSERT INTO `rst_product_units` (`id`, `company_id`, `created_by`, `updated_by`, `name`, `status`, `created_at`, `updated_at`, `is_bar`, `type`) VALUES
(1, 1, 1, NULL, 'kg', 1, '2024-02-04 15:01:27', '2024-02-04 15:01:27', 0, 'matrial');

-- --------------------------------------------------------

--
-- Table structure for table `rst_product_uploads`
--

CREATE TABLE `rst_product_uploads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `is_bar` tinyint(4) DEFAULT 0,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pack_unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `barcode` varchar(191) DEFAULT NULL,
  `stock_limit` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_cost` decimal(8,2) NOT NULL DEFAULT 0.00,
  `sale_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `vat_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `opening_quantity` decimal(8,2) DEFAULT NULL,
  `available_quantity` decimal(8,2) DEFAULT NULL,
  `pack_size` decimal(8,2) DEFAULT NULL,
  `pack_quantity` int(11) DEFAULT NULL,
  `is_matrial` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_purchases`
--

CREATE TABLE `rst_purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `manufacturer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `challan_no` varchar(191) DEFAULT NULL,
  `date` date NOT NULL,
  `subtotal` double(12,2) NOT NULL,
  `total_vat` double(12,2) DEFAULT NULL,
  `payable_amount` double(12,2) NOT NULL,
  `discount` double(12,2) NOT NULL,
  `paid_amount` double(12,2) NOT NULL,
  `due_amount` double(10,2) NOT NULL DEFAULT 0.00,
  `change_amount` double(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_bar` tinyint(4) DEFAULT 0,
  `challan_id` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_purchase_details`
--

CREATE TABLE `rst_purchase_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` double NOT NULL DEFAULT 0,
  `item_price` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_bar` tinyint(4) DEFAULT 0,
  `unit_vat_amount` double(10,2) DEFAULT 0.00,
  `unit_vat_percentage` double(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_sales`
--

CREATE TABLE `rst_sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `hotel_guest_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hotel_booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `guest_name` varchar(191) DEFAULT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `date` date NOT NULL,
  `subtotal` double(10,2) DEFAULT NULL,
  `discount` double(10,2) DEFAULT NULL,
  `previous_due` double(10,2) DEFAULT NULL,
  `paid_amount` double(10,2) DEFAULT NULL,
  `change_amount` double(10,2) DEFAULT NULL,
  `due_amount` double(10,2) DEFAULT NULL,
  `vat_amount` decimal(8,2) DEFAULT NULL,
  `service_amount` decimal(8,2) DEFAULT NULL,
  `payment_way` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_bar` tinyint(4) DEFAULT 0,
  `payment_status` varchar(191) DEFAULT NULL,
  `waiter_no` varchar(191) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `table_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payable_amount` decimal(16,4) GENERATED ALWAYS AS (`subtotal` + `vat_amount` + `service_amount` - `discount`) VIRTUAL,
  `pay_booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_sale_items`
--

CREATE TABLE `rst_sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `small_quantity` decimal(8,2) DEFAULT NULL,
  `sales_price` double(10,2) NOT NULL,
  `item_price` double(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_bar` tinyint(4) DEFAULT 0,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vat_amount` decimal(8,2) DEFAULT 0.00,
  `small_unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_discount` double(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_sale_returns`
--

CREATE TABLE `rst_sale_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `date` date NOT NULL,
  `subtotal` double(10,2) DEFAULT NULL,
  `payable_amount` double(10,2) DEFAULT NULL,
  `return_amount` double(10,2) DEFAULT NULL,
  `change_amount` double(10,2) DEFAULT NULL,
  `due_amount` double(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_bar` tinyint(4) DEFAULT 0,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_sale_return_details`
--

CREATE TABLE `rst_sale_return_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_return_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `item_price` double(10,2) NOT NULL,
  `total_amount` double(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_bar` tinyint(4) DEFAULT 0,
  `item_discount` double(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_suppliers`
--

CREATE TABLE `rst_suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(191) DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `previous_due` decimal(20,2) NOT NULL DEFAULT 0.00,
  `current_balance` decimal(20,2) NOT NULL DEFAULT 0.00,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `due_limit` double NOT NULL DEFAULT 99999999999999,
  `image` varchar(191) DEFAULT NULL,
  `company_name` varchar(191) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_bar` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rst_table_manages`
--

CREATE TABLE `rst_table_manages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `table_no` varchar(191) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `is_bar` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seasons`
--

CREATE TABLE `seasons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `smart_soft_payment_schedules`
--

CREATE TABLE `smart_soft_payment_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amount` double NOT NULL,
  `date` varchar(191) NOT NULL,
  `alert_date` varchar(191) NOT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT 0,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_apis`
--

CREATE TABLE `sms_apis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `username` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `sender_number` varchar(191) NOT NULL,
  `balance` int(11) NOT NULL DEFAULT 0,
  `url` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `type` varchar(191) NOT NULL,
  `source_id` int(11) NOT NULL,
  `source_number` varchar(191) NOT NULL,
  `debit_qty` decimal(15,3) NOT NULL DEFAULT 0.000,
  `credit_qty` decimal(15,3) NOT NULL DEFAULT 0.000,
  `debit_rate` decimal(15,3) NOT NULL DEFAULT 0.000,
  `credit_rate` decimal(15,3) NOT NULL DEFAULT 0.000,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustments`
--

CREATE TABLE `stock_adjustments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_qty` double DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `note` varchar(191) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `is_bar` tinyint(4) NOT NULL DEFAULT 1,
  `current_status` varchar(191) DEFAULT 'Pending',
  `approve_date` date DEFAULT NULL,
  `cancel_date` date DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `canceled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustment_details`
--

CREATE TABLE `stock_adjustment_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `stock_adjustment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_price` double DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `approved_quantity` double DEFAULT NULL,
  `stock_type` varchar(191) DEFAULT NULL,
  `adjustment_reason` varchar(191) NOT NULL DEFAULT 'Damaged',
  `is_bar` tinyint(4) NOT NULL DEFAULT 1,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_trackings`
--

CREATE TABLE `stock_trackings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `goods_requisition_detail_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) NOT NULL,
  `tracking_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `quantity` int(11) NOT NULL,
  `price` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `submodules`
--

CREATE TABLE `submodules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `module_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `submodules`
--

INSERT INTO `submodules` (`id`, `name`, `module_id`, `created_at`, `updated_at`) VALUES
(1, 'Group Info', 1, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(2, 'Merchandising Setup', 5, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(3, 'Employee Info', 3, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(14, 'Item', 4, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(15, 'Purchase', 4, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(16, 'Requisition', 4, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(17, 'GS Report', 4, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(18, 'Access Panel', 2, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110002, 'Front Desk', 160000, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110003, 'Hotel Website', 160001, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110004, 'Hotel Service', 120001, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110005, 'Restaurant', 130001, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110006, 'Bar', 270000, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110007, 'Stock Adjustment', 160000, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110008, 'House Keeping', 160000, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110009, 'Kitchen', 160000, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(110010, 'BanquetHall', 200000, '2024-02-01 17:49:26', '2024-02-01 17:49:26'),
(150000, 'Account Setup', 150000, '2021-11-17 15:09:50', '2021-11-17 15:24:41'),
(150001, 'Account Product', 150000, '2021-11-17 15:10:47', '2021-11-17 15:24:30'),
(150002, 'Account Voucher', 150000, '2021-11-17 15:10:37', '2021-11-17 15:24:20'),
(150004, 'Account Party', 150000, '2021-11-17 15:23:21', '2021-11-17 15:24:05'),
(150005, 'Account Purchase', 150000, '2021-11-17 15:23:53', '2021-11-17 15:23:53'),
(150006, 'Account Sale', 150000, '2021-11-17 15:25:06', '2021-11-17 15:25:06'),
(150007, 'Account Report', 150000, '2021-11-17 15:25:06', '2021-11-17 15:25:06'),
(170000, 'Customer', 170000, '2024-02-01 17:51:49', '2024-02-01 17:51:49'),
(170001, 'Lead', 170000, '2024-02-01 17:51:49', '2024-02-01 17:51:49'),
(170002, 'Project', 170000, '2024-02-01 17:51:49', '2024-02-01 17:51:49'),
(170003, 'Mail Template', 170000, '2024-02-01 17:51:49', '2024-02-01 17:51:49');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_type_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `website` varchar(191) DEFAULT NULL,
  `fax` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `head_office` text DEFAULT NULL,
  `factory_1` text DEFAULT NULL,
  `factory_2` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_ledgers`
--

CREATE TABLE `supplier_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `date` varchar(191) NOT NULL,
  `balance_type` enum('Debit','Credit') NOT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_types`
--

CREATE TABLE `supplier_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(191) NOT NULL,
  `value` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'general_store_reference_no_change', NULL, '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(2, 'out_work_date_picker', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(3, 'employee_summary_gross_salary_get', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(4, 'finger_id_get', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(5, 'employee_list_card_no', '1', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(6, 'custom_employee_full_id', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(7, 'employee_login_option', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(8, 'employee_attendance_chart', '1', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(9, 'dashboard', '1', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(10, 'line', '1', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(11, 'employee_signature', '1', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(12, 'topbar_background_color', 'rgba(188,186,186,0.92)', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(13, 'topbar_text_color', 'white', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(14, 'login_background_image', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(15, 'default_login_for', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(16, 'employee_general_shift', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(17, 'leave_recommender_required', '1', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(18, 'hierarchy_wise_employee_ordering', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(19, 'late_time_count_from', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(20, 'line_caption', 'Line', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(21, 'employee_group', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(22, 'mfs', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(23, 'employee_facilities', '1', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(24, 'visible_booking_ui_dashboard', '1', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(25, 'enable_account_transaction_for_hotel', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(26, 'date_start_end', '4:00 AM-3:59 AM', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(27, 'bulk_booking', '1', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(28, 'restaurant_can_sell_bar_product', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(29, 'root_currency', '96', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(30, 'bar_due_list_date', '1', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(31, 'mother_inventory', '1', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(32, 'account_transaction_when_night_audit', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(33, 'room_wise_pricing_booking', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(34, 'category_wise_booking', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(35, 'report_with_night_audit', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(36, 'rst_use_kitchen_module', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(37, 'use_vat_included', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(38, 'enable_only_image_for_pos_print', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20'),
(39, 'enable_only_company_name_for_pos_print', '0', '2024-02-01 17:49:17', '2024-02-18 05:36:20');

-- --------------------------------------------------------

--
-- Table structure for table `task_notifications`
--

CREATE TABLE `task_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `taskable_type` varchar(191) NOT NULL,
  `taskable_id` int(11) NOT NULL,
  `route_name` varchar(191) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transactionable_type` varchar(191) NOT NULL,
  `transactionable_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `date` varchar(191) NOT NULL,
  `redirect_path` varchar(191) DEFAULT NULL,
  `balance_type` varchar(191) DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(16,2) DEFAULT 0.00,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(191) DEFAULT NULL,
  `transaction_item_type` varchar(191) DEFAULT NULL,
  `batch_id` varchar(191) DEFAULT NULL,
  `debit_amount` decimal(16,4) DEFAULT 0.0000,
  `credit_amount` decimal(16,4) DEFAULT 0.0000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `transactionable_type`, `transactionable_id`, `created_at`, `updated_at`, `invoice_no`, `date`, `redirect_path`, `balance_type`, `account_id`, `amount`, `created_by`, `company_id`, `updated_by`, `description`, `transaction_item_type`, `batch_id`, `debit_amount`, `credit_amount`) VALUES
(2, 'Booking', 48, '2024-02-29 11:15:11', '2024-02-29 11:15:11', '2024-02-0012', '2024-02-29', NULL, 'credit', 26, 0.00, 4, 1, 4, NULL, 'Sale', 'Booking-48', 0.0000, 781.7700),
(3, 'Booking', 48, '2024-02-29 11:15:11', '2024-02-29 11:15:11', '2024-02-0012', '2024-02-29', NULL, 'debit', 1000, 0.00, 4, 1, 4, NULL, 'Customer Due', 'Booking-48', 781.7700, 781.7700),
(4, 'Booking', 48, '2024-02-29 11:15:11', '2024-02-29 11:15:11', '2024-02-0012', '2024-02-29', NULL, 'debit', 55, 0.00, 4, 1, 4, NULL, 'Payment', 'Booking-48', 781.7700, 0.0000),
(11, 'Booking', 47, '2024-02-29 11:20:13', '2024-02-29 11:20:13', '2024-02-0011', '2024-02-29', NULL, 'credit', 26, 0.00, 4, 1, 4, NULL, 'Sale', 'Booking-47', 0.0000, 231.5000),
(12, 'Booking', 47, '2024-02-29 11:20:13', '2024-02-29 11:20:13', '2024-02-0011', '2024-02-29', NULL, 'debit', 1000, 0.00, 4, 1, 4, NULL, 'Customer Due', 'Booking-47', 231.5000, 594.5000),
(13, 'Booking', 47, '2024-02-29 11:20:13', '2024-02-29 11:20:13', '2024-02-0011', '2024-02-29', NULL, 'debit', 55, 0.00, 4, 1, 4, NULL, 'Payment', 'Booking-47', 594.5000, 0.0000),
(14, 'Booking', 49, '2024-02-29 11:36:39', '2024-02-29 11:36:39', '2024-02-0013', '2024-02-29', NULL, 'credit', 26, 0.00, 4, 1, 4, NULL, 'Sale', 'Booking-49', 0.0000, 1897.5000),
(15, 'Booking', 49, '2024-02-29 11:36:39', '2024-02-29 11:36:39', '2024-02-0013', '2024-02-29', NULL, 'debit', 1000, 0.00, 4, 1, 4, NULL, 'Customer Due', 'Booking-49', 1897.5000, 1897.5000),
(16, 'Booking', 49, '2024-02-29 11:36:39', '2024-02-29 11:36:39', '2024-02-0013', '2024-02-29', NULL, 'debit', 55, 0.00, 4, 1, 4, NULL, 'Payment', 'Booking-49', 1897.5000, 0.0000);

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `phone_number` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `role_id` int(10) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `api_token` varchar(191) DEFAULT NULL,
  `password_reset_token` varchar(191) DEFAULT NULL,
  `employee_full_id` varchar(191) DEFAULT NULL,
  `device_token` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `company_id`, `name`, `email`, `email_verified_at`, `password`, `status`, `phone_number`, `address`, `type`, `created_by`, `branch_id`, `role_id`, `remember_token`, `created_at`, `updated_at`, `api_token`, `password_reset_token`, `employee_full_id`, `device_token`) VALUES
(1, 1, 'Mr. Admin', 'kabir.bitscol@gmail.com', '2024-02-01 00:00:00', '$2y$10$jBCte/zJbUV/a5hRBm3JjunAHxxfW1h4JD1WBjbx8ILwEQj448DkK', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-01 17:49:17', '2024-02-01 17:49:17', NULL, NULL, NULL, NULL),
(2, 1, 'Super admin', 'asmpavelsarwar@gmail.com', NULL, '$2y$10$uM2CHYX7VzBOkZOw0yQ.XuIwVr66ttmICPZKtUhoVELj4r9q5u/hu', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'ppG20lTk35xZqiILF4mhUVLyGnIsdjyWfifEeai8FIgxCgQSZ5daSXFpUJPw', '2024-02-01 17:59:58', '2024-02-04 11:33:05', NULL, NULL, NULL, NULL),
(3, 1, 'admin', 'admin@mmheritagehotel.com', NULL, '$2y$10$HRLikVR.3ftjCS6.TKeSm.LNmJ9BtY1EeyypbcUPhqn1BvxaKl7Qi', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'mYYDQyj1lUQFDx4LoZj0j46rnXjVd7JSOXb4r6oilDTI23Vi7SM5N2GmrxjN', '2024-02-04 12:03:30', '2024-02-04 12:03:30', NULL, NULL, NULL, NULL),
(4, 1, 'frontdesk1', 'frontdesk1@mmheritagehotel.com', NULL, '$2y$10$FdrQy5iiZKKsz2NgV.hMquxnM.fT.3E2xdENvRzmoZWOQGlPlD6JO', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'bAqAFBizL5AP351pugwtY3qCTCLK4CfLJ4rAjKShIRVuLdv0zeC7dkXrAo6T', '2024-02-04 12:05:03', '2024-02-04 12:05:03', NULL, NULL, NULL, NULL),
(5, 1, 'frontdesk2', 'frontdesk2@mmheritagehotel.com', NULL, '$2y$10$EdCfmZCi3xk8JfwmobrEEOklI/snI7Kd.LD66U7MoWEExpEMcTega', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-04 12:05:43', '2024-02-04 12:05:43', NULL, NULL, NULL, NULL),
(6, 1, 'booking', 'booking@mmheritagehotel.com', NULL, '$2y$10$We0sJQAyMp/D47QlKU8ryePl8YgrzB4NrTi3MJleRtDkXtxahMlBu', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-04 12:06:21', '2024-02-04 12:06:21', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_credentials`
--

CREATE TABLE `user_credentials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `secrete` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_credentials`
--

INSERT INTO `user_credentials` (`id`, `user_id`, `secrete`, `created_at`, `updated_at`) VALUES
(1, 2, 'admin12345@', '2024-02-01 17:59:58', '2024-02-04 11:33:05'),
(2, 3, 'admin@123', '2024-02-04 12:03:30', '2024-02-04 12:03:30'),
(3, 4, 'frontdesk@123', '2024-02-04 12:05:03', '2024-02-04 12:05:03'),
(4, 5, 'frontdesk@123', '2024-02-04 12:05:43', '2024-02-04 12:05:43'),
(5, 6, 'booking@123', '2024-02-04 12:06:21', '2024-02-04 12:06:21');

-- --------------------------------------------------------

--
-- Table structure for table `user_login_statuses`
--

CREATE TABLE `user_login_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` varchar(10) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(191) DEFAULT NULL,
  `description` varchar(191) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `date` date NOT NULL,
  `reference` varchar(191) DEFAULT NULL,
  `voucher_type` varchar(191) NOT NULL,
  `attachment` varchar(191) DEFAULT NULL,
  `is_approved` tinyint(4) NOT NULL DEFAULT 0,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voucher_details`
--

CREATE TABLE `voucher_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_no` varchar(191) DEFAULT NULL,
  `balance_type` enum('Debit','Credit') NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `website_pages`
--

CREATE TABLE `website_pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` text DEFAULT NULL,
  `slug` text DEFAULT NULL,
  `sub_title` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `website_settings`
--

CREATE TABLE `website_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_first_name` varchar(191) DEFAULT NULL,
  `site_last_name` varchar(191) DEFAULT NULL,
  `site_slogan` varchar(191) DEFAULT NULL,
  `phone_no` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `location_map` text DEFAULT NULL,
  `facebook_url` varchar(191) DEFAULT NULL,
  `twitter_url` varchar(191) DEFAULT NULL,
  `youtube_url` varchar(191) DEFAULT NULL,
  `linkedin_url` varchar(191) DEFAULT NULL,
  `meta_keyword` varchar(191) DEFAULT NULL,
  `meta_description` varchar(191) DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `website_settings`
--

INSERT INTO `website_settings` (`id`, `site_first_name`, `site_last_name`, `site_slogan`, `phone_no`, `email`, `address`, `location_map`, `facebook_url`, `twitter_url`, `youtube_url`, `linkedin_url`, `meta_keyword`, `meta_description`, `company_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'MM Heritage', 'Hotel', 'Where Every Moment Exceeds Expectation', '+60102224276', 'booking@mmheritagehotel.com', 'Jalan Baiduri 1, Taman Pulau Melaka, 75000 Malacca, Malaysia', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1993.465368693662!2d102.25428459999999!3d2.17999!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d1f1be56787b39%3A0xf4e4606e593c1b61!2sMM%20Heritage%20Hotel!5e0!3m2!1sen!2sbd!4v1707045538701!5m2!1sen!2sbd\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', NULL, NULL, NULL, NULL, NULL, NULL, 1, 3, NULL, '2024-02-01 17:51:29', '2024-02-06 09:14:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_sections`
--
ALTER TABLE `about_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounts_account_group_id_foreign` (`account_group_id`),
  ADD KEY `accounts_account_control_id_foreign` (`account_control_id`),
  ADD KEY `accounts_account_subsidiary_id_foreign` (`account_subsidiary_id`),
  ADD KEY `accounts_company_id_foreign` (`company_id`);

--
-- Indexes for table `account_controls`
--
ALTER TABLE `account_controls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_controls_account_group_id_foreign` (`account_group_id`),
  ADD KEY `account_controls_company_id_foreign` (`company_id`);

--
-- Indexes for table `account_groups`
--
ALTER TABLE `account_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_groups_company_id_foreign` (`company_id`);

--
-- Indexes for table `account_opening_balances`
--
ALTER TABLE `account_opening_balances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_setups`
--
ALTER TABLE `account_setups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_setups_company_id_foreign` (`company_id`);

--
-- Indexes for table `account_subsidiaries`
--
ALTER TABLE `account_subsidiaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_subsidiaries_account_group_id_foreign` (`account_group_id`),
  ADD KEY `account_subsidiaries_account_control_id_foreign` (`account_control_id`),
  ADD KEY `account_subsidiaries_company_id_foreign` (`company_id`);

--
-- Indexes for table `acc_categories`
--
ALTER TABLE `acc_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acc_categories_company_id_foreign` (`company_id`),
  ADD KEY `acc_categories_created_by_foreign` (`created_by`),
  ADD KEY `acc_categories_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `acc_collections`
--
ALTER TABLE `acc_collections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acc_collections_company_id_foreign` (`company_id`),
  ADD KEY `acc_collections_created_by_foreign` (`created_by`),
  ADD KEY `acc_collections_updated_by_foreign` (`updated_by`),
  ADD KEY `acc_collections_invoice_no_foreign` (`invoice_no`),
  ADD KEY `acc_collections_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `acc_customers`
--
ALTER TABLE `acc_customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acc_customers_account_id_foreign` (`account_id`),
  ADD KEY `acc_customers_company_id_foreign` (`company_id`),
  ADD KEY `acc_customers_created_by_foreign` (`created_by`),
  ADD KEY `acc_customers_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `acc_damages`
--
ALTER TABLE `acc_damages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acc_damages_company_id_foreign` (`company_id`);

--
-- Indexes for table `acc_damage_details`
--
ALTER TABLE `acc_damage_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_payments`
--
ALTER TABLE `acc_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acc_payments_company_id_foreign` (`company_id`),
  ADD KEY `acc_payments_created_by_foreign` (`created_by`),
  ADD KEY `acc_payments_invoice_no_foreign` (`invoice_no`);

--
-- Indexes for table `acc_purchases`
--
ALTER TABLE `acc_purchases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `acc_purchases_invoice_no_unique` (`invoice_no`),
  ADD KEY `acc_purchases_company_id_foreign` (`company_id`),
  ADD KEY `acc_purchases_created_by_foreign` (`created_by`),
  ADD KEY `acc_purchases_updated_by_foreign` (`updated_by`),
  ADD KEY `acc_purchases_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `acc_purchase_details`
--
ALTER TABLE `acc_purchase_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acc_purchase_details_product_id_foreign` (`product_id`),
  ADD KEY `acc_purchase_details_purchase_id_foreign` (`purchase_id`);

--
-- Indexes for table `acc_purchase_exchange_details`
--
ALTER TABLE `acc_purchase_exchange_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_purchase_returns`
--
ALTER TABLE `acc_purchase_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acc_purchase_returns_company_id_foreign` (`company_id`),
  ADD KEY `acc_purchase_returns_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `acc_purchase_return_details`
--
ALTER TABLE `acc_purchase_return_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_sales`
--
ALTER TABLE `acc_sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `acc_sales_invoice_no_unique` (`invoice_no`),
  ADD KEY `acc_sales_company_id_foreign` (`company_id`),
  ADD KEY `acc_sales_created_by_foreign` (`created_by`),
  ADD KEY `acc_sales_updated_by_foreign` (`updated_by`),
  ADD KEY `acc_sales_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `acc_sale_details`
--
ALTER TABLE `acc_sale_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acc_sale_details_product_id_foreign` (`product_id`),
  ADD KEY `acc_sale_details_sale_id_foreign` (`sale_id`);

--
-- Indexes for table `acc_sale_exchange_details`
--
ALTER TABLE `acc_sale_exchange_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_sale_returns`
--
ALTER TABLE `acc_sale_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acc_sale_returns_company_id_foreign` (`company_id`),
  ADD KEY `acc_sale_returns_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `acc_sale_return_details`
--
ALTER TABLE `acc_sale_return_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `acc_stocks`
--
ALTER TABLE `acc_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acc_stocks_stockable_type_stockable_id_index` (`stockable_type`,`stockable_id`),
  ADD KEY `acc_stocks_company_id_foreign` (`company_id`),
  ADD KEY `acc_stocks_product_id_foreign` (`product_id`),
  ADD KEY `acc_stocks_created_by_foreign` (`created_by`),
  ADD KEY `acc_stocks_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `acc_stock_summaries`
--
ALTER TABLE `acc_stock_summaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acc_stock_summaries_company_id_foreign` (`company_id`),
  ADD KEY `acc_stock_summaries_product_id_foreign` (`product_id`),
  ADD KEY `acc_stock_summaries_created_by_foreign` (`created_by`),
  ADD KEY `acc_stock_summaries_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `acc_suppliers`
--
ALTER TABLE `acc_suppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acc_suppliers_account_id_foreign` (`account_id`),
  ADD KEY `acc_suppliers_company_id_foreign` (`company_id`),
  ADD KEY `acc_suppliers_created_by_foreign` (`created_by`),
  ADD KEY `acc_suppliers_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `banks_company_id_foreign` (`company_id`),
  ADD KEY `banks_created_by_foreign` (`created_by`),
  ADD KEY `banks_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `basic_rate_setups`
--
ALTER TABLE `basic_rate_setups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `basic_rate_setups_created_by_foreign` (`created_by`),
  ADD KEY `basic_rate_setups_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_created_by_foreign` (`created_by`),
  ADD KEY `booking_updated_by_foreign` (`updated_by`),
  ADD KEY `booking_purpose_id_foreign` (`purpose_id`),
  ADD KEY `booking_platform_id_foreign` (`platform_id`),
  ADD KEY `booking_pay_by_foreign` (`pay_by`);

--
-- Indexes for table `booking_adjusts`
--
ALTER TABLE `booking_adjusts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_adjusts_booking_id_foreign` (`booking_id`),
  ADD KEY `booking_adjusts_created_by_foreign` (`created_by`),
  ADD KEY `booking_adjusts_updated_by_foreign` (`updated_by`),
  ADD KEY `booking_adjusts_from_room_id_foreign` (`from_room_id`),
  ADD KEY `booking_adjusts_to_room_id_foreign` (`to_room_id`),
  ADD KEY `booking_adjusts_booking_detail_id_foreign` (`booking_detail_id`);

--
-- Indexes for table `booking_carts`
--
ALTER TABLE `booking_carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_date_details`
--
ALTER TABLE `booking_date_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_date_details_booking_id_foreign` (`booking_id`),
  ADD KEY `booking_date_details_room_id_foreign` (`room_id`),
  ADD KEY `booking_date_details_booking_detail_id_foreign` (`booking_detail_id`);

--
-- Indexes for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_details_created_by_foreign` (`created_by`),
  ADD KEY `booking_details_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `booking_extra_charges`
--
ALTER TABLE `booking_extra_charges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_extra_charges_booking_id_foreign` (`booking_id`),
  ADD KEY `booking_extra_charges_created_by_foreign` (`created_by`),
  ADD KEY `booking_extra_charges_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `booking_guest_details`
--
ALTER TABLE `booking_guest_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_guest_details_booking_id_foreign` (`booking_id`),
  ADD KEY `booking_guest_details_booking_detail_id_foreign` (`booking_detail_id`);

--
-- Indexes for table `booking_member_details`
--
ALTER TABLE `booking_member_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_member_details_guest_id_foreign` (`guest_id`);

--
-- Indexes for table `booking_notes`
--
ALTER TABLE `booking_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_notes_created_by_foreign` (`created_by`),
  ADD KEY `booking_notes_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `business_types`
--
ALTER TABLE `business_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buyers`
--
ALTER TABLE `buyers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyers_group_id_foreign` (`group_id`),
  ADD KEY `buyers_created_by_foreign` (`created_by`),
  ADD KEY `buyers_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `buyer_uploads`
--
ALTER TABLE `buyer_uploads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_uploads_group_id_foreign` (`group_id`),
  ADD KEY `buyer_uploads_created_by_foreign` (`created_by`),
  ADD KEY `buyer_uploads_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `card_authorized_information`
--
ALTER TABLE `card_authorized_information`
  ADD PRIMARY KEY (`id`),
  ADD KEY `card_authorized_information_booking_id_foreign` (`booking_id`),
  ADD KEY `card_authorized_information_sale_id_foreign` (`sale_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `companies_code_unique` (`code`),
  ADD KEY `companies_group_id_foreign` (`group_id`),
  ADD KEY `companies_business_type_id_foreign` (`business_type_id`);

--
-- Indexes for table `company_bank_accounts`
--
ALTER TABLE `company_bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_bank_accounts_company_id_foreign` (`company_id`);

--
-- Indexes for table `company_details`
--
ALTER TABLE `company_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_details_company_id_foreign` (`company_id`);

--
-- Indexes for table `company_user`
--
ALTER TABLE `company_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_user_user_id_foreign` (`user_id`),
  ADD KEY `company_user_company_id_foreign` (`company_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currency_conversions`
--
ALTER TABLE `currency_conversions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `currency_conversions_currency_id_foreign` (`currency_id`),
  ADD KEY `currency_conversions_created_by_foreign` (`created_by`),
  ADD KEY `currency_conversions_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `customer_ledgers`
--
ALTER TABLE `customer_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_ledgers_customer_id_foreign` (`customer_id`),
  ADD KEY `customer_ledgers_sale_id_foreign` (`sale_id`),
  ADD KEY `customer_ledgers_account_id_foreign` (`account_id`),
  ADD KEY `customer_ledgers_created_by_foreign` (`created_by`),
  ADD KEY `customer_ledgers_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `c_r_m_customers`
--
ALTER TABLE `c_r_m_customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `c_r_m_customers_created_by_foreign` (`created_by`),
  ADD KEY `c_r_m_customers_updated_by_foreign` (`updated_by`),
  ADD KEY `c_r_m_customers_country_id_foreign` (`country_id`);

--
-- Indexes for table `department_user`
--
ALTER TABLE `department_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `designation_user`
--
ALTER TABLE `designation_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `designation_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emails_project_id_foreign` (`project_id`),
  ADD KEY `emails_mail_template_id_foreign` (`mail_template_id`),
  ADD KEY `emails_c_r_m_customer_id_foreign` (`c_r_m_customer_id`),
  ADD KEY `emails_project_billing_id_foreign` (`project_billing_id`),
  ADD KEY `emails_created_by_foreign` (`created_by`),
  ADD KEY `emails_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fund_transfers`
--
ALTER TABLE `fund_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fund_transfers_from_account_id_foreign` (`from_account_id`),
  ADD KEY `fund_transfers_to_account_id_foreign` (`to_account_id`),
  ADD KEY `fund_transfers_company_id_foreign` (`company_id`),
  ADD KEY `fund_transfers_created_by_foreign` (`created_by`),
  ADD KEY `fund_transfers_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `global_infos`
--
ALTER TABLE `global_infos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goods_requisitions`
--
ALTER TABLE `goods_requisitions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_requisitions_company_id_foreign` (`company_id`),
  ADD KEY `goods_requisitions_created_by_foreign` (`created_by`),
  ADD KEY `goods_requisitions_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `goods_requisition_details`
--
ALTER TABLE `goods_requisition_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_requisition_details_item_id_foreign` (`item_id`),
  ADD KEY `goods_requisition_details_company_id_foreign` (`company_id`),
  ADD KEY `goods_requisition_details_updated_by_foreign` (`updated_by`),
  ADD KEY `goods_requisition_details_goods_requisition_id_foreign` (`goods_requisition_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `groups_email_unique` (`email`),
  ADD UNIQUE KEY `groups_phone_unique` (`phone`);

--
-- Indexes for table `hotel_account_transactions`
--
ALTER TABLE `hotel_account_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_account_transection_source_type_source_id_index` (`source_type`,`source_id`),
  ADD KEY `hotel_account_transection_created_by_foreign` (`created_by`),
  ADD KEY `hotel_account_transection_updated_by_foreign` (`updated_by`),
  ADD KEY `hotel_account_transection_booking_id_foreign` (`booking_id`),
  ADD KEY `hotel_account_transactions_currency_conversion_id_foreign` (`currency_conversion_id`);

--
-- Indexes for table `hotel_account_type`
--
ALTER TABLE `hotel_account_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_account_type_created_by_foreign` (`created_by`),
  ADD KEY `hotel_account_type_updated_by_foreign` (`updated_by`),
  ADD KEY `hotel_account_type_account_id_foreign` (`account_id`);

--
-- Indexes for table `hotel_banners`
--
ALTER TABLE `hotel_banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotel_booking_purpose`
--
ALTER TABLE `hotel_booking_purpose`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotel_customer_ledgers`
--
ALTER TABLE `hotel_customer_ledgers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotel_features`
--
ALTER TABLE `hotel_features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotel_feature_lists`
--
ALTER TABLE `hotel_feature_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotel_galleries`
--
ALTER TABLE `hotel_galleries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotel_guest`
--
ALTER TABLE `hotel_guest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_guest_created_by_foreign` (`created_by`),
  ADD KEY `hotel_guest_updated_by_foreign` (`updated_by`),
  ADD KEY `hotel_guest_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `hotel_guest_registration_terms`
--
ALTER TABLE `hotel_guest_registration_terms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_guest_registration_terms_created_by_foreign` (`created_by`),
  ADD KEY `hotel_guest_registration_terms_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `hotel_services`
--
ALTER TABLE `hotel_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_services_company_id_foreign` (`company_id`),
  ADD KEY `hotel_services_created_by_foreign` (`created_by`);

--
-- Indexes for table `hotel_service_sales`
--
ALTER TABLE `hotel_service_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_service_sales_company_id_foreign` (`company_id`),
  ADD KEY `hotel_service_sales_created_by_foreign` (`created_by`),
  ADD KEY `hotel_service_sales_hotel_guest_id_foreign` (`hotel_guest_id`),
  ADD KEY `hotel_service_sales_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `hotel_service_sale_items`
--
ALTER TABLE `hotel_service_sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_service_sale_items_hotel_service_sale_id_foreign` (`hotel_service_sale_id`),
  ADD KEY `hotel_service_sale_items_hotel_service_id_foreign` (`hotel_service_id`);

--
-- Indexes for table `hotel_transaction_ledgers`
--
ALTER TABLE `hotel_transaction_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_transaction_ledgers_created_by_foreign` (`created_by`),
  ADD KEY `hotel_transaction_ledgers_hotel_transaction_id_foreign` (`hotel_transaction_id`);

--
-- Indexes for table `hotel_vat`
--
ALTER TABLE `hotel_vat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_vat_created_by_foreign` (`created_by`),
  ADD KEY `hotel_vat_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `id_card_settings`
--
ALTER TABLE `id_card_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_card_settings_company_id_foreign` (`company_id`);

--
-- Indexes for table `image_store_guests`
--
ALTER TABLE `image_store_guests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `image_store_guests_guest_id_foreign` (`guest_id`),
  ADD KEY `image_store_guests_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `inovoice_no`
--
ALTER TABLE `inovoice_no`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_generate`
--
ALTER TABLE `invoice_generate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_nos`
--
ALTER TABLE `invoice_nos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_nos_company_id_foreign` (`company_id`);

--
-- Indexes for table `invoice_numbers`
--
ALTER TABLE `invoice_numbers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `items_company_id_name_unique` (`company_id`,`name`),
  ADD KEY `items_created_by_foreign` (`created_by`),
  ADD KEY `items_updated_by_foreign` (`updated_by`),
  ADD KEY `items_item_unit_id_foreign` (`item_unit_id`);

--
-- Indexes for table `item_units`
--
ALTER TABLE `item_units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_units_group_id_name_unique` (`group_id`,`name`),
  ADD KEY `item_units_created_by_foreign` (`created_by`),
  ADD KEY `item_units_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `kitchen_orders`
--
ALTER TABLE `kitchen_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kitchen_orders_sale_id_foreign` (`sale_id`),
  ADD KEY `kitchen_orders_approved_by_foreign` (`approved_by`),
  ADD KEY `kitchen_orders_canceled_by_foreign` (`canceled_by`),
  ADD KEY `kitchen_orders_created_by_foreign` (`created_by`),
  ADD KEY `kitchen_orders_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `kitchen_order_details`
--
ALTER TABLE `kitchen_order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kitchen_order_details_order_id_foreign` (`order_id`),
  ADD KEY `kitchen_order_details_item_id_foreign` (`item_id`);

--
-- Indexes for table `mail_templates`
--
ALTER TABLE `mail_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mail_templates_created_by_foreign` (`created_by`),
  ADD KEY `mail_templates_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `night_audit_details`
--
ALTER TABLE `night_audit_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `night_audit_details_audit_id_foreign` (`audit_id`),
  ADD KEY `night_audit_details_transaction_id_foreign` (`transaction_id`),
  ADD KEY `night_audit_details_transaction_ledger_id_foreign` (`transaction_ledger_id`);

--
-- Indexes for table `night_audit_room_details`
--
ALTER TABLE `night_audit_room_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `night_audit_room_details_audit_id_foreign` (`audit_id`);

--
-- Indexes for table `night_audit_summaries`
--
ALTER TABLE `night_audit_summaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `night_audit_transactions`
--
ALTER TABLE `night_audit_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_types`
--
ALTER TABLE `order_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `our_services`
--
ALTER TABLE `our_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `our_service_lists`
--
ALTER TABLE `our_service_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parent_permissions`
--
ALTER TABLE `parent_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_permissions_submodule_id_foreign` (`submodule_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payment_type`
--
ALTER TABLE `payment_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_slug_unique` (`slug`),
  ADD KEY `permissions_created_by_foreign` (`created_by`),
  ADD KEY `permissions_updated_by_foreign` (`updated_by`),
  ADD KEY `permissions_parent_permission_id_foreign` (`parent_permission_id`);

--
-- Indexes for table `permission_features`
--
ALTER TABLE `permission_features`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission_features_name_unique` (`name`);

--
-- Indexes for table `permission_user`
--
ALTER TABLE `permission_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_user_user_id_foreign` (`user_id`),
  ADD KEY `permission_user_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `privacy_policies`
--
ALTER TABLE `privacy_policies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_company_id_foreign` (`company_id`),
  ADD KEY `products_created_by_foreign` (`created_by`);

--
-- Indexes for table `product_ledgers`
--
ALTER TABLE `product_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_ledgers_sourceable_type_sourceable_id_index` (`sourceable_type`,`sourceable_id`);

--
-- Indexes for table `product_metrials`
--
ALTER TABLE `product_metrials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_metrials_product_id_foreign` (`product_id`),
  ADD KEY `product_metrials_category_id_foreign` (`category_id`),
  ADD KEY `product_metrials_material_id_foreign` (`material_id`),
  ADD KEY `product_metrials_units_id_foreign` (`units_id`),
  ADD KEY `product_metrials_created_by_foreign` (`created_by`),
  ADD KEY `product_metrials_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `product_stock_transections`
--
ALTER TABLE `product_stock_transections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_stock_transections_company_id_foreign` (`company_id`),
  ADD KEY `product_stock_transections_product_id_foreign` (`product_id`),
  ADD KEY `product_stock_transections_created_by_foreign` (`created_by`),
  ADD KEY `product_stock_transections_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projects_invoice_no_unique` (`invoice_no`),
  ADD KEY `projects_created_by_foreign` (`created_by`),
  ADD KEY `projects_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `project_billings`
--
ALTER TABLE `project_billings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_billings_invoice_no_unique` (`invoice_no`),
  ADD KEY `project_billings_project_id_foreign` (`project_id`),
  ADD KEY `project_billings_created_by_foreign` (`created_by`),
  ADD KEY `project_billings_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `project_details`
--
ALTER TABLE `project_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_details_project_id_foreign` (`project_id`),
  ADD KEY `project_details_project_name_id_foreign` (`project_name_id`);

--
-- Indexes for table `project_names`
--
ALTER TABLE `project_names`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_company_id_foreign` (`company_id`),
  ADD KEY `purchases_created_by_foreign` (`created_by`),
  ADD KEY `purchases_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `purchase_details`
--
ALTER TABLE `purchase_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_details_company_id_foreign` (`company_id`),
  ADD KEY `purchase_details_created_by_foreign` (`created_by`),
  ADD KEY `purchase_details_updated_by_foreign` (`updated_by`),
  ADD KEY `purchase_details_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_details_item_id_foreign` (`item_id`);

--
-- Indexes for table `purchase_receives`
--
ALTER TABLE `purchase_receives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_receives_company_id_foreign` (`company_id`),
  ADD KEY `purchase_receives_created_by_foreign` (`created_by`),
  ADD KEY `purchase_receives_updated_by_foreign` (`updated_by`),
  ADD KEY `purchase_receives_purchase_id_foreign` (`purchase_id`);

--
-- Indexes for table `purchase_receive_details`
--
ALTER TABLE `purchase_receive_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_receive_details_company_id_foreign` (`company_id`),
  ADD KEY `purchase_receive_details_created_by_foreign` (`created_by`),
  ADD KEY `purchase_receive_details_updated_by_foreign` (`updated_by`),
  ADD KEY `purchase_receive_details_purchase_receive_id_foreign` (`purchase_receive_id`),
  ADD KEY `purchase_receive_details_purchase_details_id_foreign` (`purchase_details_id`),
  ADD KEY `purchase_receive_details_item_id_foreign` (`item_id`),
  ADD KEY `purchase_receive_details_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `requsition_stocks`
--
ALTER TABLE `requsition_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requsition_stocks_product_id_foreign` (`product_id`),
  ADD KEY `requsition_stocks_created_by_foreign` (`created_by`),
  ADD KEY `requsition_stocks_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `rest_materials`
--
ALTER TABLE `rest_materials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rest_materials_company_id_name_unique` (`company_id`,`name`),
  ADD KEY `rest_materials_created_by_foreign` (`created_by`),
  ADD KEY `rest_materials_updated_by_foreign` (`updated_by`),
  ADD KEY `rest_materials_units_id_foreign` (`units_id`);

--
-- Indexes for table `rest_material_units`
--
ALTER TABLE `rest_material_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rest_material_units_group_id_foreign` (`group_id`),
  ADD KEY `rest_material_units_created_by_foreign` (`created_by`),
  ADD KEY `rest_material_units_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `rmreports`
--
ALTER TABLE `rmreports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rooms_created_by_foreign` (`created_by`);

--
-- Indexes for table `room_aminities`
--
ALTER TABLE `room_aminities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_aminities_created_by_foreign` (`created_by`),
  ADD KEY `room_aminities_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `room_categories`
--
ALTER TABLE `room_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_categories_created_by_foreign` (`created_by`),
  ADD KEY `room_categories_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `room_logs`
--
ALTER TABLE `room_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_logs_created_by_foreign` (`created_by`),
  ADD KEY `room_logs_room_id_foreign` (`room_id`);

--
-- Indexes for table `room_photos`
--
ALTER TABLE `room_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_photos_category_id_foreign` (`category_id`),
  ADD KEY `room_photos_created_by_foreign` (`created_by`),
  ADD KEY `room_photos_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `room_prices`
--
ALTER TABLE `room_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rst_finish_good_details`
--
ALTER TABLE `rst_finish_good_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_finish_good_details_production_id_foreign` (`production_id`),
  ADD KEY `rst_finish_good_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `rst_material_purchase`
--
ALTER TABLE `rst_material_purchase`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_material_purchase_company_id_foreign` (`company_id`),
  ADD KEY `rst_material_purchase_created_by_foreign` (`created_by`);

--
-- Indexes for table `rst_material_purchase_details`
--
ALTER TABLE `rst_material_purchase_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_material_purchase_details_purchase_id_foreign` (`purchase_id`),
  ADD KEY `rst_material_purchase_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `rst_metrial_details`
--
ALTER TABLE `rst_metrial_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_metrial_details_production_id_foreign` (`production_id`),
  ADD KEY `rst_metrial_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `rst_productions`
--
ALTER TABLE `rst_productions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_productions_created_by_foreign` (`created_by`),
  ADD KEY `rst_productions_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `rst_products`
--
ALTER TABLE `rst_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_products_company_id_foreign` (`company_id`),
  ADD KEY `rst_products_unit_id_foreign` (`unit_id`),
  ADD KEY `rst_products_supplier_id_foreign` (`supplier_id`),
  ADD KEY `rst_products_category_id_index` (`category_id`),
  ADD KEY `rst_products_pack_unit_id_foreign` (`pack_unit_id`),
  ADD KEY `rst_products_package_id_foreign` (`package_id`);

--
-- Indexes for table `rst_product_brands`
--
ALTER TABLE `rst_product_brands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_product_brands_company_id_foreign` (`company_id`),
  ADD KEY `rst_product_brands_created_by_foreign` (`created_by`);

--
-- Indexes for table `rst_product_categories`
--
ALTER TABLE `rst_product_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_product_categories_company_id_foreign` (`company_id`),
  ADD KEY `rst_product_categories_created_by_foreign` (`created_by`),
  ADD KEY `rst_product_categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `rst_product_ledgers`
--
ALTER TABLE `rst_product_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_product_ledgers_sourceable_type_sourceable_id_index` (`sourceable_type`,`sourceable_id`);

--
-- Indexes for table `rst_product_package`
--
ALTER TABLE `rst_product_package`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_product_package_company_id_foreign` (`company_id`),
  ADD KEY `rst_product_package_created_by_foreign` (`created_by`),
  ADD KEY `rst_product_package_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `rst_product_package_details`
--
ALTER TABLE `rst_product_package_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_product_package_details_package_id_foreign` (`package_id`),
  ADD KEY `rst_product_package_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `rst_product_stocks`
--
ALTER TABLE `rst_product_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_product_stocks_created_by_foreign` (`created_by`),
  ADD KEY `rst_product_stocks_updated_by_foreign` (`updated_by`),
  ADD KEY `rst_product_stocks_company_id_foreign` (`company_id`);

--
-- Indexes for table `rst_product_units`
--
ALTER TABLE `rst_product_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_product_units_company_id_foreign` (`company_id`),
  ADD KEY `rst_product_units_created_by_foreign` (`created_by`);

--
-- Indexes for table `rst_product_uploads`
--
ALTER TABLE `rst_product_uploads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_product_uploads_company_id_foreign` (`company_id`),
  ADD KEY `rst_product_uploads_unit_id_foreign` (`unit_id`),
  ADD KEY `rst_product_uploads_category_id_foreign` (`category_id`),
  ADD KEY `rst_product_uploads_package_id_foreign` (`package_id`),
  ADD KEY `rst_product_uploads_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `rst_purchases`
--
ALTER TABLE `rst_purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_purchases_company_id_foreign` (`company_id`),
  ADD KEY `rst_purchases_created_by_foreign` (`created_by`);

--
-- Indexes for table `rst_purchase_details`
--
ALTER TABLE `rst_purchase_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_purchase_details_purchase_id_foreign` (`purchase_id`),
  ADD KEY `rst_purchase_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `rst_sales`
--
ALTER TABLE `rst_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_sales_company_id_foreign` (`company_id`),
  ADD KEY `rst_sales_created_by_foreign` (`created_by`),
  ADD KEY `rst_sales_table_id_foreign` (`table_id`),
  ADD KEY `rst_sales_pay_booking_id_foreign` (`pay_booking_id`);

--
-- Indexes for table `rst_sale_items`
--
ALTER TABLE `rst_sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_sale_items_sale_id_foreign` (`sale_id`),
  ADD KEY `rst_sale_items_unit_id_foreign` (`unit_id`),
  ADD KEY `rst_sale_items_small_unit_id_foreign` (`small_unit_id`);

--
-- Indexes for table `rst_sale_returns`
--
ALTER TABLE `rst_sale_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_sale_returns_company_id_foreign` (`company_id`),
  ADD KEY `rst_sale_returns_created_by_foreign` (`created_by`),
  ADD KEY `rst_sale_returns_sale_id_foreign` (`sale_id`);

--
-- Indexes for table `rst_sale_return_details`
--
ALTER TABLE `rst_sale_return_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rst_sale_return_details_sale_id_foreign` (`sale_id`),
  ADD KEY `rst_sale_return_details_sale_return_id_foreign` (`sale_return_id`);

--
-- Indexes for table `rst_suppliers`
--
ALTER TABLE `rst_suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rst_suppliers_code_unique` (`code`),
  ADD KEY `rst_suppliers_company_id_foreign` (`company_id`),
  ADD KEY `rst_suppliers_created_by_foreign` (`created_by`),
  ADD KEY `rst_suppliers_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `rst_table_manages`
--
ALTER TABLE `rst_table_manages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seasons`
--
ALTER TABLE `seasons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seasons_group_id_foreign` (`group_id`),
  ADD KEY `seasons_created_by_foreign` (`created_by`),
  ADD KEY `seasons_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `smart_soft_payment_schedules`
--
ALTER TABLE `smart_soft_payment_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_apis`
--
ALTER TABLE `sms_apis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sms_apis_created_by_foreign` (`created_by`),
  ADD KEY `sms_apis_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stocks_item_id_foreign` (`item_id`),
  ADD KEY `stocks_created_by_foreign` (`created_by`),
  ADD KEY `stocks_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_adjustments_supplier_id_foreign` (`supplier_id`),
  ADD KEY `stock_adjustments_company_id_foreign` (`company_id`),
  ADD KEY `stock_adjustments_approved_by_foreign` (`approved_by`),
  ADD KEY `stock_adjustments_canceled_by_foreign` (`canceled_by`),
  ADD KEY `stock_adjustments_created_by_foreign` (`created_by`),
  ADD KEY `stock_adjustments_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `stock_adjustment_details`
--
ALTER TABLE `stock_adjustment_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_adjustment_details_stock_adjustment_id_foreign` (`stock_adjustment_id`),
  ADD KEY `stock_adjustment_details_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `stock_trackings`
--
ALTER TABLE `stock_trackings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_trackings_goods_requisition_detail_id_foreign` (`goods_requisition_detail_id`);

--
-- Indexes for table `submodules`
--
ALTER TABLE `submodules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `submodules_module_id_foreign` (`module_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `suppliers_group_id_foreign` (`group_id`),
  ADD KEY `suppliers_supplier_type_id_foreign` (`supplier_type_id`),
  ADD KEY `suppliers_created_by_foreign` (`created_by`),
  ADD KEY `suppliers_updated_by_foreign` (`updated_by`),
  ADD KEY `suppliers_country_id_foreign` (`country_id`);

--
-- Indexes for table `supplier_ledgers`
--
ALTER TABLE `supplier_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_ledgers_supplier_id_foreign` (`supplier_id`),
  ADD KEY `supplier_ledgers_purchase_id_foreign` (`purchase_id`),
  ADD KEY `supplier_ledgers_account_id_foreign` (`account_id`),
  ADD KEY `supplier_ledgers_created_by_foreign` (`created_by`),
  ADD KEY `supplier_ledgers_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `supplier_types`
--
ALTER TABLE `supplier_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_settings_key_unique` (`key`);

--
-- Indexes for table `task_notifications`
--
ALTER TABLE `task_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_transactionable_type_transactionable_id_index` (`transactionable_type`,`transactionable_id`),
  ADD KEY `transactions_account_id_foreign` (`account_id`),
  ADD KEY `transactions_created_by_foreign` (`created_by`),
  ADD KEY `transactions_company_id_foreign` (`company_id`),
  ADD KEY `transactions_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `units_company_id_foreign` (`company_id`),
  ADD KEY `units_created_by_foreign` (`created_by`),
  ADD KEY `units_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_api_token_unique` (`api_token`);

--
-- Indexes for table `user_credentials`
--
ALTER TABLE `user_credentials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_login_statuses`
--
ALTER TABLE `user_login_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_login_statuses_user_id_foreign` (`user_id`),
  ADD KEY `user_login_statuses_company_id_foreign` (`company_id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vouchers_company_id_foreign` (`company_id`),
  ADD KEY `vouchers_created_by_foreign` (`created_by`),
  ADD KEY `vouchers_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `voucher_details`
--
ALTER TABLE `voucher_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voucher_details_account_id_foreign` (`account_id`),
  ADD KEY `voucher_details_voucher_id_foreign` (`voucher_id`);

--
-- Indexes for table `website_pages`
--
ALTER TABLE `website_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `website_settings`
--
ALTER TABLE `website_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_sections`
--
ALTER TABLE `about_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1004;

--
-- AUTO_INCREMENT for table `account_controls`
--
ALTER TABLE `account_controls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `account_groups`
--
ALTER TABLE `account_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `account_opening_balances`
--
ALTER TABLE `account_opening_balances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_setups`
--
ALTER TABLE `account_setups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_subsidiaries`
--
ALTER TABLE `account_subsidiaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `acc_categories`
--
ALTER TABLE `acc_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_collections`
--
ALTER TABLE `acc_collections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_customers`
--
ALTER TABLE `acc_customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_damages`
--
ALTER TABLE `acc_damages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_damage_details`
--
ALTER TABLE `acc_damage_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_payments`
--
ALTER TABLE `acc_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_purchases`
--
ALTER TABLE `acc_purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_purchase_details`
--
ALTER TABLE `acc_purchase_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_purchase_exchange_details`
--
ALTER TABLE `acc_purchase_exchange_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_purchase_returns`
--
ALTER TABLE `acc_purchase_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_purchase_return_details`
--
ALTER TABLE `acc_purchase_return_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_sales`
--
ALTER TABLE `acc_sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_sale_details`
--
ALTER TABLE `acc_sale_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_sale_exchange_details`
--
ALTER TABLE `acc_sale_exchange_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_sale_returns`
--
ALTER TABLE `acc_sale_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_sale_return_details`
--
ALTER TABLE `acc_sale_return_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_stocks`
--
ALTER TABLE `acc_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_stock_summaries`
--
ALTER TABLE `acc_stock_summaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acc_suppliers`
--
ALTER TABLE `acc_suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `basic_rate_setups`
--
ALTER TABLE `basic_rate_setups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `booking_adjusts`
--
ALTER TABLE `booking_adjusts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_carts`
--
ALTER TABLE `booking_carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_date_details`
--
ALTER TABLE `booking_date_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `booking_details`
--
ALTER TABLE `booking_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `booking_extra_charges`
--
ALTER TABLE `booking_extra_charges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_guest_details`
--
ALTER TABLE `booking_guest_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_member_details`
--
ALTER TABLE `booking_member_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `booking_notes`
--
ALTER TABLE `booking_notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `business_types`
--
ALTER TABLE `business_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `buyers`
--
ALTER TABLE `buyers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `buyer_uploads`
--
ALTER TABLE `buyer_uploads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `card_authorized_information`
--
ALTER TABLE `card_authorized_information`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `company_bank_accounts`
--
ALTER TABLE `company_bank_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `company_details`
--
ALTER TABLE `company_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `company_user`
--
ALTER TABLE `company_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `currency_conversions`
--
ALTER TABLE `currency_conversions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer_ledgers`
--
ALTER TABLE `customer_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `c_r_m_customers`
--
ALTER TABLE `c_r_m_customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `department_user`
--
ALTER TABLE `department_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `designation_user`
--
ALTER TABLE `designation_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fund_transfers`
--
ALTER TABLE `fund_transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `global_infos`
--
ALTER TABLE `global_infos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `goods_requisitions`
--
ALTER TABLE `goods_requisitions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goods_requisition_details`
--
ALTER TABLE `goods_requisition_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hotel_account_transactions`
--
ALTER TABLE `hotel_account_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `hotel_account_type`
--
ALTER TABLE `hotel_account_type`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hotel_banners`
--
ALTER TABLE `hotel_banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `hotel_booking_purpose`
--
ALTER TABLE `hotel_booking_purpose`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hotel_customer_ledgers`
--
ALTER TABLE `hotel_customer_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hotel_features`
--
ALTER TABLE `hotel_features`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hotel_feature_lists`
--
ALTER TABLE `hotel_feature_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hotel_galleries`
--
ALTER TABLE `hotel_galleries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `hotel_guest`
--
ALTER TABLE `hotel_guest`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `hotel_guest_registration_terms`
--
ALTER TABLE `hotel_guest_registration_terms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hotel_services`
--
ALTER TABLE `hotel_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hotel_service_sales`
--
ALTER TABLE `hotel_service_sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hotel_service_sale_items`
--
ALTER TABLE `hotel_service_sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hotel_transaction_ledgers`
--
ALTER TABLE `hotel_transaction_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `hotel_vat`
--
ALTER TABLE `hotel_vat`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `id_card_settings`
--
ALTER TABLE `id_card_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `image_store_guests`
--
ALTER TABLE `image_store_guests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inovoice_no`
--
ALTER TABLE `inovoice_no`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_generate`
--
ALTER TABLE `invoice_generate`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `invoice_nos`
--
ALTER TABLE `invoice_nos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_numbers`
--
ALTER TABLE `invoice_numbers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_units`
--
ALTER TABLE `item_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kitchen_orders`
--
ALTER TABLE `kitchen_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kitchen_order_details`
--
ALTER TABLE `kitchen_order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mail_templates`
--
ALTER TABLE `mail_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=365;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=270001;

--
-- AUTO_INCREMENT for table `night_audit_details`
--
ALTER TABLE `night_audit_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `night_audit_room_details`
--
ALTER TABLE `night_audit_room_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `night_audit_summaries`
--
ALTER TABLE `night_audit_summaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `night_audit_transactions`
--
ALTER TABLE `night_audit_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_types`
--
ALTER TABLE `order_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `our_services`
--
ALTER TABLE `our_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `our_service_lists`
--
ALTER TABLE `our_service_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `parent_permissions`
--
ALTER TABLE `parent_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=270006;

--
-- AUTO_INCREMENT for table `payment_type`
--
ALTER TABLE `payment_type`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170037;

--
-- AUTO_INCREMENT for table `permission_features`
--
ALTER TABLE `permission_features`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `permission_user`
--
ALTER TABLE `permission_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=775;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `privacy_policies`
--
ALTER TABLE `privacy_policies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_ledgers`
--
ALTER TABLE `product_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_metrials`
--
ALTER TABLE `product_metrials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_stock_transections`
--
ALTER TABLE `product_stock_transections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_billings`
--
ALTER TABLE `project_billings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_details`
--
ALTER TABLE `project_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_names`
--
ALTER TABLE `project_names`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_details`
--
ALTER TABLE `purchase_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_receives`
--
ALTER TABLE `purchase_receives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_receive_details`
--
ALTER TABLE `purchase_receive_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requsition_stocks`
--
ALTER TABLE `requsition_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rest_materials`
--
ALTER TABLE `rest_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rest_material_units`
--
ALTER TABLE `rest_material_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rmreports`
--
ALTER TABLE `rmreports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `room_aminities`
--
ALTER TABLE `room_aminities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `room_categories`
--
ALTER TABLE `room_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `room_logs`
--
ALTER TABLE `room_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `room_photos`
--
ALTER TABLE `room_photos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `room_prices`
--
ALTER TABLE `room_prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_finish_good_details`
--
ALTER TABLE `rst_finish_good_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_material_purchase`
--
ALTER TABLE `rst_material_purchase`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_material_purchase_details`
--
ALTER TABLE `rst_material_purchase_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_metrial_details`
--
ALTER TABLE `rst_metrial_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_productions`
--
ALTER TABLE `rst_productions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_products`
--
ALTER TABLE `rst_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_product_brands`
--
ALTER TABLE `rst_product_brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_product_categories`
--
ALTER TABLE `rst_product_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rst_product_ledgers`
--
ALTER TABLE `rst_product_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_product_package`
--
ALTER TABLE `rst_product_package`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_product_package_details`
--
ALTER TABLE `rst_product_package_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_product_stocks`
--
ALTER TABLE `rst_product_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_product_units`
--
ALTER TABLE `rst_product_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rst_product_uploads`
--
ALTER TABLE `rst_product_uploads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_purchases`
--
ALTER TABLE `rst_purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_purchase_details`
--
ALTER TABLE `rst_purchase_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_sales`
--
ALTER TABLE `rst_sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_sale_items`
--
ALTER TABLE `rst_sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_sale_returns`
--
ALTER TABLE `rst_sale_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_sale_return_details`
--
ALTER TABLE `rst_sale_return_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_suppliers`
--
ALTER TABLE `rst_suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rst_table_manages`
--
ALTER TABLE `rst_table_manages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seasons`
--
ALTER TABLE `seasons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `smart_soft_payment_schedules`
--
ALTER TABLE `smart_soft_payment_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_apis`
--
ALTER TABLE `sms_apis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_adjustment_details`
--
ALTER TABLE `stock_adjustment_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_trackings`
--
ALTER TABLE `stock_trackings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submodules`
--
ALTER TABLE `submodules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170004;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_ledgers`
--
ALTER TABLE `supplier_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_types`
--
ALTER TABLE `supplier_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `task_notifications`
--
ALTER TABLE `task_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_credentials`
--
ALTER TABLE `user_credentials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_login_statuses`
--
ALTER TABLE `user_login_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voucher_details`
--
ALTER TABLE `voucher_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `website_pages`
--
ALTER TABLE `website_pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `website_settings`
--
ALTER TABLE `website_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_account_control_id_foreign` FOREIGN KEY (`account_control_id`) REFERENCES `account_controls` (`id`),
  ADD CONSTRAINT `accounts_account_group_id_foreign` FOREIGN KEY (`account_group_id`) REFERENCES `account_groups` (`id`),
  ADD CONSTRAINT `accounts_account_subsidiary_id_foreign` FOREIGN KEY (`account_subsidiary_id`) REFERENCES `account_subsidiaries` (`id`),
  ADD CONSTRAINT `accounts_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);

--
-- Constraints for table `account_controls`
--
ALTER TABLE `account_controls`
  ADD CONSTRAINT `account_controls_account_group_id_foreign` FOREIGN KEY (`account_group_id`) REFERENCES `account_groups` (`id`),
  ADD CONSTRAINT `account_controls_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);

--
-- Constraints for table `account_groups`
--
ALTER TABLE `account_groups`
  ADD CONSTRAINT `account_groups_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);

--
-- Constraints for table `account_setups`
--
ALTER TABLE `account_setups`
  ADD CONSTRAINT `account_setups_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);

--
-- Constraints for table `account_subsidiaries`
--
ALTER TABLE `account_subsidiaries`
  ADD CONSTRAINT `account_subsidiaries_account_control_id_foreign` FOREIGN KEY (`account_control_id`) REFERENCES `account_controls` (`id`),
  ADD CONSTRAINT `account_subsidiaries_account_group_id_foreign` FOREIGN KEY (`account_group_id`) REFERENCES `account_groups` (`id`),
  ADD CONSTRAINT `account_subsidiaries_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);

--
-- Constraints for table `acc_categories`
--
ALTER TABLE `acc_categories`
  ADD CONSTRAINT `acc_categories_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `acc_categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `acc_categories_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `acc_collections`
--
ALTER TABLE `acc_collections`
  ADD CONSTRAINT `acc_collections_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `acc_collections_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `acc_collections_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `acc_customers` (`id`),
  ADD CONSTRAINT `acc_collections_invoice_no_foreign` FOREIGN KEY (`invoice_no`) REFERENCES `acc_sales` (`invoice_no`),
  ADD CONSTRAINT `acc_collections_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `acc_customers`
--
ALTER TABLE `acc_customers`
  ADD CONSTRAINT `acc_customers_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `acc_customers_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `acc_customers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `acc_customers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `acc_damages`
--
ALTER TABLE `acc_damages`
  ADD CONSTRAINT `acc_damages_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);

--
-- Constraints for table `acc_payments`
--
ALTER TABLE `acc_payments`
  ADD CONSTRAINT `acc_payments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `acc_payments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `acc_payments_invoice_no_foreign` FOREIGN KEY (`invoice_no`) REFERENCES `acc_purchases` (`invoice_no`);

--
-- Constraints for table `acc_purchases`
--
ALTER TABLE `acc_purchases`
  ADD CONSTRAINT `acc_purchases_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `acc_purchases_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `acc_purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `acc_suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `acc_purchases_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `acc_purchase_details`
--
ALTER TABLE `acc_purchase_details`
  ADD CONSTRAINT `acc_purchase_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `acc_purchase_details_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `acc_purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `acc_purchase_returns`
--
ALTER TABLE `acc_purchase_returns`
  ADD CONSTRAINT `acc_purchase_returns_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `acc_purchase_returns_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `acc_suppliers` (`id`);

--
-- Constraints for table `acc_sales`
--
ALTER TABLE `acc_sales`
  ADD CONSTRAINT `acc_sales_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `acc_sales_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `acc_sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `acc_customers` (`id`),
  ADD CONSTRAINT `acc_sales_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `acc_sale_details`
--
ALTER TABLE `acc_sale_details`
  ADD CONSTRAINT `acc_sale_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `acc_sale_details_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `acc_sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `acc_sale_returns`
--
ALTER TABLE `acc_sale_returns`
  ADD CONSTRAINT `acc_sale_returns_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `acc_sale_returns_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `acc_customers` (`id`);

--
-- Constraints for table `acc_stocks`
--
ALTER TABLE `acc_stocks`
  ADD CONSTRAINT `acc_stocks_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `acc_stocks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `acc_stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `acc_stocks_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `acc_stock_summaries`
--
ALTER TABLE `acc_stock_summaries`
  ADD CONSTRAINT `acc_stock_summaries_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `acc_stock_summaries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `acc_stock_summaries_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `acc_stock_summaries_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `acc_suppliers`
--
ALTER TABLE `acc_suppliers`
  ADD CONSTRAINT `acc_suppliers_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `acc_suppliers_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `acc_suppliers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `acc_suppliers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `banks`
--
ALTER TABLE `banks`
  ADD CONSTRAINT `banks_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `banks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `banks_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `basic_rate_setups`
--
ALTER TABLE `basic_rate_setups`
  ADD CONSTRAINT `basic_rate_setups_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `basic_rate_setups_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `booking_pay_by_foreign` FOREIGN KEY (`pay_by`) REFERENCES `booking_member_details` (`id`),
  ADD CONSTRAINT `booking_platform_id_foreign` FOREIGN KEY (`platform_id`) REFERENCES `hotel_booking_purpose` (`id`),
  ADD CONSTRAINT `booking_purpose_id_foreign` FOREIGN KEY (`purpose_id`) REFERENCES `hotel_booking_purpose` (`id`),
  ADD CONSTRAINT `booking_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `booking_adjusts`
--
ALTER TABLE `booking_adjusts`
  ADD CONSTRAINT `booking_adjusts_booking_detail_id_foreign` FOREIGN KEY (`booking_detail_id`) REFERENCES `booking_details` (`id`),
  ADD CONSTRAINT `booking_adjusts_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`),
  ADD CONSTRAINT `booking_adjusts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `booking_adjusts_from_room_id_foreign` FOREIGN KEY (`from_room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `booking_adjusts_to_room_id_foreign` FOREIGN KEY (`to_room_id`) REFERENCES `rooms` (`id`),
  ADD CONSTRAINT `booking_adjusts_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `booking_date_details`
--
ALTER TABLE `booking_date_details`
  ADD CONSTRAINT `booking_date_details_booking_detail_id_foreign` FOREIGN KEY (`booking_detail_id`) REFERENCES `booking_details` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_date_details_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`),
  ADD CONSTRAINT `booking_date_details_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD CONSTRAINT `booking_details_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `booking_details_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `booking_extra_charges`
--
ALTER TABLE `booking_extra_charges`
  ADD CONSTRAINT `booking_extra_charges_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`),
  ADD CONSTRAINT `booking_extra_charges_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `booking_extra_charges_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `booking_guest_details`
--
ALTER TABLE `booking_guest_details`
  ADD CONSTRAINT `booking_guest_details_booking_detail_id_foreign` FOREIGN KEY (`booking_detail_id`) REFERENCES `booking_details` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_guest_details_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_member_details`
--
ALTER TABLE `booking_member_details`
  ADD CONSTRAINT `booking_member_details_guest_id_foreign` FOREIGN KEY (`guest_id`) REFERENCES `hotel_guest` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_notes`
--
ALTER TABLE `booking_notes`
  ADD CONSTRAINT `booking_notes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `booking_notes_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `buyers`
--
ALTER TABLE `buyers`
  ADD CONSTRAINT `buyers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `buyers_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `buyers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `buyer_uploads`
--
ALTER TABLE `buyer_uploads`
  ADD CONSTRAINT `buyer_uploads_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `buyer_uploads_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `buyer_uploads_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `card_authorized_information`
--
ALTER TABLE `card_authorized_information`
  ADD CONSTRAINT `card_authorized_information_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`),
  ADD CONSTRAINT `card_authorized_information_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `rst_sales` (`id`);

--
-- Constraints for table `companies`
--
ALTER TABLE `companies`
  ADD CONSTRAINT `companies_business_type_id_foreign` FOREIGN KEY (`business_type_id`) REFERENCES `business_types` (`id`),
  ADD CONSTRAINT `companies_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`);

--
-- Constraints for table `company_bank_accounts`
--
ALTER TABLE `company_bank_accounts`
  ADD CONSTRAINT `company_bank_accounts_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);

--
-- Constraints for table `company_details`
--
ALTER TABLE `company_details`
  ADD CONSTRAINT `company_details_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `company_user`
--
ALTER TABLE `company_user`
  ADD CONSTRAINT `company_user_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `company_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `currency_conversions`
--
ALTER TABLE `currency_conversions`
  ADD CONSTRAINT `currency_conversions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `currency_conversions_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`),
  ADD CONSTRAINT `currency_conversions_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `customer_ledgers`
--
ALTER TABLE `customer_ledgers`
  ADD CONSTRAINT `customer_ledgers_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `customer_ledgers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `customer_ledgers_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `acc_customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_ledgers_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `acc_sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_ledgers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `c_r_m_customers`
--
ALTER TABLE `c_r_m_customers`
  ADD CONSTRAINT `c_r_m_customers_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `c_r_m_customers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `c_r_m_customers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `department_user`
--
ALTER TABLE `department_user`
  ADD CONSTRAINT `department_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `designation_user`
--
ALTER TABLE `designation_user`
  ADD CONSTRAINT `designation_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `emails`
--
ALTER TABLE `emails`
  ADD CONSTRAINT `emails_c_r_m_customer_id_foreign` FOREIGN KEY (`c_r_m_customer_id`) REFERENCES `c_r_m_customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emails_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `emails_mail_template_id_foreign` FOREIGN KEY (`mail_template_id`) REFERENCES `mail_templates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emails_project_billing_id_foreign` FOREIGN KEY (`project_billing_id`) REFERENCES `project_billings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emails_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emails_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `fund_transfers`
--
ALTER TABLE `fund_transfers`
  ADD CONSTRAINT `fund_transfers_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `fund_transfers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fund_transfers_from_account_id_foreign` FOREIGN KEY (`from_account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `fund_transfers_to_account_id_foreign` FOREIGN KEY (`to_account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `fund_transfers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `goods_requisitions`
--
ALTER TABLE `goods_requisitions`
  ADD CONSTRAINT `goods_requisitions_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `goods_requisitions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `goods_requisitions_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `goods_requisition_details`
--
ALTER TABLE `goods_requisition_details`
  ADD CONSTRAINT `goods_requisition_details_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `goods_requisition_details_goods_requisition_id_foreign` FOREIGN KEY (`goods_requisition_id`) REFERENCES `goods_requisitions` (`id`),
  ADD CONSTRAINT `goods_requisition_details_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `goods_requisition_details_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `hotel_account_transactions`
--
ALTER TABLE `hotel_account_transactions`
  ADD CONSTRAINT `hotel_account_transactions_currency_conversion_id_foreign` FOREIGN KEY (`currency_conversion_id`) REFERENCES `currency_conversions` (`id`),
  ADD CONSTRAINT `hotel_account_transection_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`),
  ADD CONSTRAINT `hotel_account_transection_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `hotel_account_transection_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `hotel_account_type`
--
ALTER TABLE `hotel_account_type`
  ADD CONSTRAINT `hotel_account_type_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hotel_account_type_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `hotel_account_type_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `hotel_guest`
--
ALTER TABLE `hotel_guest`
  ADD CONSTRAINT `hotel_guest_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`),
  ADD CONSTRAINT `hotel_guest_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `hotel_guest_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `hotel_guest_registration_terms`
--
ALTER TABLE `hotel_guest_registration_terms`
  ADD CONSTRAINT `hotel_guest_registration_terms_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `hotel_guest_registration_terms_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `hotel_services`
--
ALTER TABLE `hotel_services`
  ADD CONSTRAINT `hotel_services_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `hotel_services_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `hotel_service_sales`
--
ALTER TABLE `hotel_service_sales`
  ADD CONSTRAINT `hotel_service_sales_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`),
  ADD CONSTRAINT `hotel_service_sales_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `hotel_service_sales_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `hotel_service_sales_hotel_guest_id_foreign` FOREIGN KEY (`hotel_guest_id`) REFERENCES `hotel_guest` (`id`);

--
-- Constraints for table `hotel_service_sale_items`
--
ALTER TABLE `hotel_service_sale_items`
  ADD CONSTRAINT `hotel_service_sale_items_hotel_service_id_foreign` FOREIGN KEY (`hotel_service_id`) REFERENCES `hotel_services` (`id`),
  ADD CONSTRAINT `hotel_service_sale_items_hotel_service_sale_id_foreign` FOREIGN KEY (`hotel_service_sale_id`) REFERENCES `hotel_service_sales` (`id`);

--
-- Constraints for table `hotel_transaction_ledgers`
--
ALTER TABLE `hotel_transaction_ledgers`
  ADD CONSTRAINT `hotel_transaction_ledgers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `hotel_transaction_ledgers_hotel_transaction_id_foreign` FOREIGN KEY (`hotel_transaction_id`) REFERENCES `hotel_account_transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_vat`
--
ALTER TABLE `hotel_vat`
  ADD CONSTRAINT `hotel_vat_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `hotel_vat_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `id_card_settings`
--
ALTER TABLE `id_card_settings`
  ADD CONSTRAINT `id_card_settings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);

--
-- Constraints for table `image_store_guests`
--
ALTER TABLE `image_store_guests`
  ADD CONSTRAINT `image_store_guests_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`),
  ADD CONSTRAINT `image_store_guests_guest_id_foreign` FOREIGN KEY (`guest_id`) REFERENCES `hotel_guest` (`id`);

--
-- Constraints for table `invoice_nos`
--
ALTER TABLE `invoice_nos`
  ADD CONSTRAINT `invoice_nos_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `items_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `items_item_unit_id_foreign` FOREIGN KEY (`item_unit_id`) REFERENCES `item_units` (`id`),
  ADD CONSTRAINT `items_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `item_units`
--
ALTER TABLE `item_units`
  ADD CONSTRAINT `item_units_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `item_units_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `item_units_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `kitchen_orders`
--
ALTER TABLE `kitchen_orders`
  ADD CONSTRAINT `kitchen_orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `kitchen_orders_canceled_by_foreign` FOREIGN KEY (`canceled_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `kitchen_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `kitchen_orders_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `rst_sales` (`id`),
  ADD CONSTRAINT `kitchen_orders_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `kitchen_order_details`
--
ALTER TABLE `kitchen_order_details`
  ADD CONSTRAINT `kitchen_order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `kitchen_orders` (`id`);

--
-- Constraints for table `mail_templates`
--
ALTER TABLE `mail_templates`
  ADD CONSTRAINT `mail_templates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `mail_templates_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `night_audit_details`
--
ALTER TABLE `night_audit_details`
  ADD CONSTRAINT `night_audit_details_audit_id_foreign` FOREIGN KEY (`audit_id`) REFERENCES `night_audit_summaries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `night_audit_details_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `hotel_account_transactions` (`id`),
  ADD CONSTRAINT `night_audit_details_transaction_ledger_id_foreign` FOREIGN KEY (`transaction_ledger_id`) REFERENCES `hotel_transaction_ledgers` (`id`);

--
-- Constraints for table `night_audit_room_details`
--
ALTER TABLE `night_audit_room_details`
  ADD CONSTRAINT `night_audit_room_details_audit_id_foreign` FOREIGN KEY (`audit_id`) REFERENCES `night_audit_summaries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `parent_permissions`
--
ALTER TABLE `parent_permissions`
  ADD CONSTRAINT `parent_permissions_submodule_id_foreign` FOREIGN KEY (`submodule_id`) REFERENCES `submodules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `permissions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `permissions_parent_permission_id_foreign` FOREIGN KEY (`parent_permission_id`) REFERENCES `parent_permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permissions_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `permission_user`
--
ALTER TABLE `permission_user`
  ADD CONSTRAINT `permission_user_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `products_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `product_metrials`
--
ALTER TABLE `product_metrials`
  ADD CONSTRAINT `product_metrials_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `rst_product_categories` (`id`),
  ADD CONSTRAINT `product_metrials_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `product_metrials_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `rst_products` (`id`),
  ADD CONSTRAINT `product_metrials_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `rst_products` (`id`),
  ADD CONSTRAINT `product_metrials_units_id_foreign` FOREIGN KEY (`units_id`) REFERENCES `rst_product_units` (`id`),
  ADD CONSTRAINT `product_metrials_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `product_stock_transections`
--
ALTER TABLE `product_stock_transections`
  ADD CONSTRAINT `product_stock_transections_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_stock_transections_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `product_stock_transections_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_stock_transections_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_billings`
--
ALTER TABLE `project_billings`
  ADD CONSTRAINT `project_billings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_billings_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_billings_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_details`
--
ALTER TABLE `project_details`
  ADD CONSTRAINT `project_details_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_details_project_name_id_foreign` FOREIGN KEY (`project_name_id`) REFERENCES `project_names` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `purchases_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchases_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `purchase_details`
--
ALTER TABLE `purchase_details`
  ADD CONSTRAINT `purchase_details_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `purchase_details_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchase_details_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `purchase_details_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`),
  ADD CONSTRAINT `purchase_details_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `purchase_receives`
--
ALTER TABLE `purchase_receives`
  ADD CONSTRAINT `purchase_receives_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `purchase_receives_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchase_receives_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`),
  ADD CONSTRAINT `purchase_receives_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `purchase_receive_details`
--
ALTER TABLE `purchase_receive_details`
  ADD CONSTRAINT `purchase_receive_details_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `purchase_receive_details_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchase_receive_details_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `purchase_receive_details_purchase_details_id_foreign` FOREIGN KEY (`purchase_details_id`) REFERENCES `purchase_details` (`id`),
  ADD CONSTRAINT `purchase_receive_details_purchase_receive_id_foreign` FOREIGN KEY (`purchase_receive_id`) REFERENCES `purchase_receives` (`id`),
  ADD CONSTRAINT `purchase_receive_details_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `purchase_receive_details_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `requsition_stocks`
--
ALTER TABLE `requsition_stocks`
  ADD CONSTRAINT `requsition_stocks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `requsition_stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `requsition_stocks_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `rest_materials`
--
ALTER TABLE `rest_materials`
  ADD CONSTRAINT `rest_materials_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `rest_materials_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `rest_materials_units_id_foreign` FOREIGN KEY (`units_id`) REFERENCES `rest_material_units` (`id`),
  ADD CONSTRAINT `rest_materials_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `rest_material_units`
--
ALTER TABLE `rest_material_units`
  ADD CONSTRAINT `rest_material_units_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `rest_material_units_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `rest_material_units_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `room_aminities`
--
ALTER TABLE `room_aminities`
  ADD CONSTRAINT `room_aminities_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `room_aminities_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `room_categories`
--
ALTER TABLE `room_categories`
  ADD CONSTRAINT `room_categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `room_categories_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `room_logs`
--
ALTER TABLE `room_logs`
  ADD CONSTRAINT `room_logs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `room_logs_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `room_photos`
--
ALTER TABLE `room_photos`
  ADD CONSTRAINT `room_photos_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `room_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `room_photos_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `room_photos_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `rst_finish_good_details`
--
ALTER TABLE `rst_finish_good_details`
  ADD CONSTRAINT `rst_finish_good_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `rst_products` (`id`),
  ADD CONSTRAINT `rst_finish_good_details_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `rst_productions` (`id`);

--
-- Constraints for table `rst_material_purchase`
--
ALTER TABLE `rst_material_purchase`
  ADD CONSTRAINT `rst_material_purchase_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `rst_material_purchase_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `rst_material_purchase_details`
--
ALTER TABLE `rst_material_purchase_details`
  ADD CONSTRAINT `rst_material_purchase_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `rst_products` (`id`),
  ADD CONSTRAINT `rst_material_purchase_details_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `rst_material_purchase` (`id`);

--
-- Constraints for table `rst_metrial_details`
--
ALTER TABLE `rst_metrial_details`
  ADD CONSTRAINT `rst_metrial_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `rst_products` (`id`),
  ADD CONSTRAINT `rst_metrial_details_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `rst_productions` (`id`);

--
-- Constraints for table `rst_productions`
--
ALTER TABLE `rst_productions`
  ADD CONSTRAINT `rst_productions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `rst_productions_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `rst_products`
--
ALTER TABLE `rst_products`
  ADD CONSTRAINT `rst_products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `rst_product_categories` (`id`),
  ADD CONSTRAINT `rst_products_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `rst_products_pack_unit_id_foreign` FOREIGN KEY (`pack_unit_id`) REFERENCES `rst_product_units` (`id`),
  ADD CONSTRAINT `rst_products_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `rst_product_package` (`id`),
  ADD CONSTRAINT `rst_products_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `rst_suppliers` (`id`),
  ADD CONSTRAINT `rst_products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `rst_product_units` (`id`);

--
-- Constraints for table `rst_product_brands`
--
ALTER TABLE `rst_product_brands`
  ADD CONSTRAINT `rst_product_brands_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `rst_product_brands_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `rst_product_categories`
--
ALTER TABLE `rst_product_categories`
  ADD CONSTRAINT `rst_product_categories_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `rst_product_categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `rst_product_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `rst_product_categories` (`id`);

--
-- Constraints for table `rst_product_package`
--
ALTER TABLE `rst_product_package`
  ADD CONSTRAINT `rst_product_package_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `rst_product_package_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `rst_product_package_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `rst_product_package_details`
--
ALTER TABLE `rst_product_package_details`
  ADD CONSTRAINT `rst_product_package_details_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `rst_product_package` (`id`),
  ADD CONSTRAINT `rst_product_package_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `rst_products` (`id`);

--
-- Constraints for table `rst_product_stocks`
--
ALTER TABLE `rst_product_stocks`
  ADD CONSTRAINT `rst_product_stocks_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `rst_product_stocks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `rst_product_stocks_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `rst_product_units`
--
ALTER TABLE `rst_product_units`
  ADD CONSTRAINT `rst_product_units_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `rst_product_units_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `rst_product_uploads`
--
ALTER TABLE `rst_product_uploads`
  ADD CONSTRAINT `rst_product_uploads_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `rst_product_categories` (`id`),
  ADD CONSTRAINT `rst_product_uploads_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `rst_product_uploads_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `rst_product_package` (`id`),
  ADD CONSTRAINT `rst_product_uploads_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `rst_suppliers` (`id`),
  ADD CONSTRAINT `rst_product_uploads_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `rst_product_units` (`id`);

--
-- Constraints for table `rst_purchases`
--
ALTER TABLE `rst_purchases`
  ADD CONSTRAINT `rst_purchases_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `rst_purchases_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `rst_purchase_details`
--
ALTER TABLE `rst_purchase_details`
  ADD CONSTRAINT `rst_purchase_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `rst_products` (`id`),
  ADD CONSTRAINT `rst_purchase_details_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `rst_purchases` (`id`);

--
-- Constraints for table `rst_sales`
--
ALTER TABLE `rst_sales`
  ADD CONSTRAINT `rst_sales_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `rst_sales_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `rst_sales_pay_booking_id_foreign` FOREIGN KEY (`pay_booking_id`) REFERENCES `booking` (`id`),
  ADD CONSTRAINT `rst_sales_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `rst_table_manages` (`id`);

--
-- Constraints for table `rst_sale_items`
--
ALTER TABLE `rst_sale_items`
  ADD CONSTRAINT `rst_sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `rst_sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rst_sale_items_small_unit_id_foreign` FOREIGN KEY (`small_unit_id`) REFERENCES `rst_product_units` (`id`),
  ADD CONSTRAINT `rst_sale_items_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `rst_product_units` (`id`);

--
-- Constraints for table `rst_sale_returns`
--
ALTER TABLE `rst_sale_returns`
  ADD CONSTRAINT `rst_sale_returns_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `rst_sale_returns_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `rst_sale_returns_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `rst_sales` (`id`);

--
-- Constraints for table `rst_sale_return_details`
--
ALTER TABLE `rst_sale_return_details`
  ADD CONSTRAINT `rst_sale_return_details_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `rst_sales` (`id`),
  ADD CONSTRAINT `rst_sale_return_details_sale_return_id_foreign` FOREIGN KEY (`sale_return_id`) REFERENCES `rst_sale_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rst_suppliers`
--
ALTER TABLE `rst_suppliers`
  ADD CONSTRAINT `rst_suppliers_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `rst_suppliers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `rst_suppliers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `seasons`
--
ALTER TABLE `seasons`
  ADD CONSTRAINT `seasons_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `seasons_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `seasons_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `sms_apis`
--
ALTER TABLE `sms_apis`
  ADD CONSTRAINT `sms_apis_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sms_apis_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `stocks_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `stocks_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD CONSTRAINT `stock_adjustments_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `stock_adjustments_canceled_by_foreign` FOREIGN KEY (`canceled_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `stock_adjustments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `stock_adjustments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `stock_adjustments_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `rst_suppliers` (`id`),
  ADD CONSTRAINT `stock_adjustments_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `stock_adjustment_details`
--
ALTER TABLE `stock_adjustment_details`
  ADD CONSTRAINT `stock_adjustment_details_stock_adjustment_id_foreign` FOREIGN KEY (`stock_adjustment_id`) REFERENCES `stock_adjustments` (`id`),
  ADD CONSTRAINT `stock_adjustment_details_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `rst_suppliers` (`id`);

--
-- Constraints for table `stock_trackings`
--
ALTER TABLE `stock_trackings`
  ADD CONSTRAINT `stock_trackings_goods_requisition_detail_id_foreign` FOREIGN KEY (`goods_requisition_detail_id`) REFERENCES `goods_requisition_details` (`id`);

--
-- Constraints for table `submodules`
--
ALTER TABLE `submodules`
  ADD CONSTRAINT `submodules_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `suppliers_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `suppliers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `suppliers_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `suppliers_supplier_type_id_foreign` FOREIGN KEY (`supplier_type_id`) REFERENCES `supplier_types` (`id`),
  ADD CONSTRAINT `suppliers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `supplier_ledgers`
--
ALTER TABLE `supplier_ledgers`
  ADD CONSTRAINT `supplier_ledgers_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `supplier_ledgers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `supplier_ledgers_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `acc_purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_ledgers_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `acc_suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_ledgers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `transactions_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `units`
--
ALTER TABLE `units`
  ADD CONSTRAINT `units_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `units_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `units_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_login_statuses`
--
ALTER TABLE `user_login_statuses`
  ADD CONSTRAINT `user_login_statuses_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `user_login_statuses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD CONSTRAINT `vouchers_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `vouchers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `vouchers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `voucher_details`
--
ALTER TABLE `voucher_details`
  ADD CONSTRAINT `voucher_details_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `voucher_details_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
