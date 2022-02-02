-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2022 at 07:46 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `game_cashflow`
--

DELIMITER $$
--
-- Procedures
--
CREATE  PROCEDURE `sp_NextNRiv` (IN `pObject` VARCHAR(30) CHARSET utf8mb4)  NO SQL
BEGIN

	DECLARE nextnumb bigint DEFAULT 0;
    
    Select currentnum INTO nextnumb from nrivs WHERE object = pObject;
    
    if nextnumb = ''
    then 
    	Select fromnumber INTO nextnumb from nrivs WHERE object = pObject;
    end if;
    select nextnumb;
    
    UPDATE nrivs set currentnum = nextnumb + 1 WHERE object = pObject;
    
 END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `acounting_docs`
--

CREATE TABLE `acounting_docs` (
  `docnum` varchar(20) NOT NULL,
  `docyear` int(11) NOT NULL,
  `trans_date` date NOT NULL,
  `trans_type` varchar(20) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `account` varchar(50) NOT NULL,
  `bank_account` varchar(50) NOT NULL,
  `idplayer` varchar(70) NOT NULL,
  `createdby` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `acounting_doc_items`
--

CREATE TABLE `acounting_doc_items` (
  `docnum` varchar(20) NOT NULL,
  `docyear` int(11) NOT NULL,
  `docitem` int(11) NOT NULL,
  `trans_date` date NOT NULL,
  `item_note` varchar(100) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `account` varchar(50) NOT NULL,
  `bank_account` varchar(50) NOT NULL,
  `idplayer` varchar(70) NOT NULL,
  `createdby` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` int(11) NOT NULL,
  `bankid` varchar(10) NOT NULL,
  `bankname` varchar(100) NOT NULL,
  `bank_accountnumber` varchar(40) NOT NULL,
  `bank_accountname` varchar(100) NOT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `bank_type` varchar(30) NOT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `bankid`, `bankname`, `bank_accountnumber`, `bank_accountname`, `opening_balance`, `bank_type`, `createdby`, `created_at`, `updated_at`) VALUES
(13, 'BRI', 'Bank BRI', '90000000001', 'Game Sport Center', '5000000.00', 'Depo', 'Administrator', '2022-01-28 03:17:23', '2022-01-28 06:45:17'),
(14, 'BCA', 'Bank BCA', '700000001', 'Game Sport Center', '5000000.00', 'Depo', 'Administrator', '2022-01-28 03:17:50', '2022-01-28 06:45:37'),
(15, 'BCA', 'Bank BCA', '700000002', 'Game Sport Center 2', '15000000.00', 'Depo', 'Administrator', '2022-01-28 03:18:41', '2022-01-28 06:45:49');

-- --------------------------------------------------------

--
-- Table structure for table `cashflows`
--

CREATE TABLE `cashflows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transdate` date NOT NULL,
  `note` varchar(100) DEFAULT NULL,
  `from_acc` varchar(30) DEFAULT NULL,
  `to_acc` varchar(30) DEFAULT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `refdoc` varchar(50) DEFAULT NULL,
  `efile` varchar(250) DEFAULT NULL,
  `createdby` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cashflows`
--

INSERT INTO `cashflows` (`id`, `transdate`, `note`, `from_acc`, `to_acc`, `debit`, `credit`, `balance`, `refdoc`, `efile`, `createdby`, `created_at`, `updated_at`) VALUES
(1, '2022-01-28', 'Saldo awal', '', '90000000001', '0.00', '5000000.00', '5000000.00', NULL, NULL, 'Administrator', '2022-01-28 06:45:17', NULL),
(2, '2022-01-28', 'Saldo awal', '', '700000001', '0.00', '5000000.00', '5000000.00', NULL, NULL, 'Administrator', '2022-01-28 06:45:37', NULL),
(3, '2022-01-28', 'Saldo awal', '', '700000002', '0.00', '15000000.00', '15000000.00', NULL, NULL, 'Administrator', '2022-01-28 06:45:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_accounts`
--

CREATE TABLE `chart_of_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account` varchar(50) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `account_ind` varchar(20) NOT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `chart_of_accounts`
--

INSERT INTO `chart_of_accounts` (`id`, `account`, `account_name`, `account_ind`, `createdby`, `created_at`, `updated_at`) VALUES
(2, '10001', 'Topup', 'Pemasukan', 'Administrator', '2022-01-22 15:54:17', NULL),
(4, '10002', 'Deposit', 'Pemasukan', 'Administrator', '2022-01-22 16:00:30', NULL),
(6, '20001', 'Withdraw', 'Pengeluaran', 'Administrator', '2022-01-22 16:03:15', NULL),
(9, '30001', 'Saldo Bank', 'Pemasukan', 'Administrator', '2022-01-22 18:19:30', NULL),
(10, '30002', 'Saldo Kas', 'Pemasukan', 'Administrator', '2022-01-22 18:20:02', NULL),
(11, '20002', 'Pengeluaran Lain-lain', 'Pengeluaran', 'Administrator', '2022-01-22 18:21:19', NULL),
(12, '10003', 'Pemasukan Lain-lain', 'Pemasukan', 'Administrator', '2022-01-22 18:22:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `coin_stocks`
--

CREATE TABLE `coin_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `totalcoin` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tgl_deposit` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `keterangan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_configurations`
--

CREATE TABLE `email_configurations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `driver` varchar(20) NOT NULL,
  `host` varchar(50) NOT NULL,
  `port` varchar(50) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `encryption` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `sender_name` varchar(50) NOT NULL,
  `sender_email` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tgl_pengeluaran` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `keterangan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_account` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tgl_pemasukan` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `keterangan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_account` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menugroups`
--

CREATE TABLE `menugroups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(50) NOT NULL,
  `groupicon` varchar(50) DEFAULT NULL,
  `_index` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `menugroups`
--

INSERT INTO `menugroups` (`id`, `description`, `groupicon`, `_index`, `created_at`, `updated_at`) VALUES
(1, 'Master Data', 'fas fa-database', 1, NULL, NULL),
(2, 'Transaksi', 'fas fa-copy', 2, NULL, NULL),
(3, 'Laporan', 'fas fa-file-alt', 3, NULL, NULL),
(4, 'Pengaturan', 'fas fa-cogs', 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menuroles`
--

CREATE TABLE `menuroles` (
  `menuid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `menuroles`
--

INSERT INTO `menuroles` (`menuid`, `roleid`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, NULL),
(2, 3, NULL, NULL),
(3, 3, NULL, NULL),
(4, 3, NULL, NULL),
(5, 3, NULL, NULL),
(6, 3, NULL, NULL),
(7, 3, NULL, NULL),
(8, 3, NULL, NULL),
(9, 3, NULL, NULL),
(10, 3, NULL, NULL),
(11, 3, NULL, NULL),
(12, 3, NULL, NULL),
(13, 3, NULL, NULL),
(14, 3, NULL, NULL),
(15, 3, NULL, NULL),
(16, 3, NULL, NULL),
(17, 3, NULL, NULL),
(18, 3, NULL, NULL),
(19, 3, NULL, NULL),
(20, 3, NULL, NULL),
(22, 3, NULL, NULL),
(23, 3, NULL, NULL),
(24, 3, NULL, NULL),
(25, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `route` varchar(100) NOT NULL,
  `menugroup` int(11) NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `route`, `menugroup`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Bank', 'master/bank', 1, NULL, NULL, NULL),
(2, 'Player', 'master/player', 1, NULL, NULL, NULL),
(3, 'TOP Up Coin', 'transaksi/topup', 2, NULL, NULL, NULL),
(4, 'Deposit', 'transaksi/deposit', 2, NULL, NULL, NULL),
(5, 'Withdraw', 'transaksi/withdraw', 2, NULL, NULL, NULL),
(6, 'Pindah Dana', 'transaksi/transfer', 2, NULL, NULL, NULL),
(7, 'Pemasukan', 'transaksi/pemasukan', 2, NULL, NULL, NULL),
(8, 'Pengeluaran', 'transaksi/pengeluaran', 2, NULL, NULL, NULL),
(9, 'Laporan Pemasukan', 'laporan/pemasukan', 3, NULL, NULL, NULL),
(10, 'Laporan Pengeluaran', 'laporan/pengeluaran', 3, NULL, NULL, NULL),
(11, 'Laporan Top Up', 'laporan/topup', 3, NULL, NULL, NULL),
(12, 'Laporan Mutasi', 'laporan/mutasi', 3, NULL, NULL, NULL),
(13, 'Laporan Saldo Bank', 'laporan/saldobank', 3, NULL, NULL, NULL),
(14, 'Data User', 'setting/users', 4, NULL, NULL, NULL),
(15, 'Data Menu Group', 'setting/menugroups', 4, NULL, NULL, NULL),
(16, 'Data Menu', 'setting/menus', 4, NULL, NULL, NULL),
(17, 'Data Role', 'setting/roles', 4, NULL, NULL, NULL),
(18, 'Data Menu Role', 'setting/menuroles', 4, NULL, NULL, NULL),
(19, 'Data User Role', 'setting/userroles', 4, NULL, NULL, NULL),
(20, 'Verifikasi Topup', 'transaksi/topup/verify', 2, NULL, NULL, NULL),
(21, 'Chart of Account', 'master/coa', 1, NULL, NULL, NULL),
(22, 'Verifikasi Withdraw', 'transaksi/withdraw/verify', 2, NULL, NULL, NULL),
(23, 'Laporan Withdraw', 'laporan/withdraw', 3, NULL, NULL, NULL),
(24, 'Verifikasi Deposit', 'transaksi/deposit/verify', 2, NULL, NULL, NULL),
(25, 'Laporan Deposit', 'laporan/deposit', 3, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2021_09_12_090939_create_roles_table', 1),
(6, '2021_09_12_090956_create_menus_table', 1),
(7, '2021_09_12_091012_create_menuroles_table', 1),
(8, '2021_09_12_091028_create_userroles_table', 1),
(9, '2021_09_12_100636_create_menugroups_table', 1),
(10, '2022_01_14_023050_create_banks_table', 1),
(11, '2022_01_18_083051_create_email_configurations_table', 2),
(12, '2022_01_18_083921_create_players_table', 3),
(13, '2022_01_19_150446_create_topups_table', 4),
(14, '2022_01_22_223229_create_chart_of_accounts_table', 5),
(15, '2022_01_22_235734_create_cashflows_table', 6),
(16, '2022_01_23_000431_create_acounting_docs_table', 6),
(17, '2022_01_23_000442_create_acounting_doc_items_table', 6),
(18, '2022_01_23_001321_create_nrivs_table', 6),
(19, '2022_01_23_002636_create_withdraws_table', 7),
(20, '2022_01_28_090704_create_deposits_table', 8),
(21, '2022_01_28_092027_create_transfers_table', 9),
(22, '2022_01_28_102755_create_incomes_table', 10),
(23, '2022_01_28_102834_create_expenses_table', 10),
(24, '2022_01_28_110824_create_coin_stocks_table', 11);

-- --------------------------------------------------------

--
-- Table structure for table `nrivs`
--

CREATE TABLE `nrivs` (
  `object` varchar(20) NOT NULL,
  `fromnumber` varchar(20) NOT NULL,
  `tonumber` varchar(20) NOT NULL,
  `currentnum` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `nrivs`
--

INSERT INTO `nrivs` (`object`, `fromnumber`, `tonumber`, `currentnum`, `created_at`, `updated_at`) VALUES
('ACCDOC', '5000000000', '5999999999', '5000000001', '2022-01-22 17:15:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `playerid` varchar(70) NOT NULL,
  `playername` varchar(100) NOT NULL,
  `bankname` varchar(100) NOT NULL,
  `bankacc` varchar(30) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`playerid`, `playername`, `bankname`, `bankacc`, `created_at`, `updated_at`) VALUES
('player01', 'BEJO KUNCORO', 'BANK BCA', '1234567893', NULL, NULL),
('player2', 'Bejo', 'Bank Mandiri', '1223456781', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rolename` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `rolename`, `created_at`, `updated_at`) VALUES
(1, 'ROLE_ADMIN', NULL, NULL),
(2, 'ROLE_CS', NULL, NULL),
(3, 'ROLE_ADMINISTRATOR', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `topups`
--

CREATE TABLE `topups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idplayer` varchar(50) NOT NULL,
  `playername` varchar(100) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `topup_bonus` decimal(15,2) NOT NULL DEFAULT 0.00,
  `topupdate` date NOT NULL,
  `topup_status` varchar(50) NOT NULL,
  `rekening_tujuan` varchar(30) DEFAULT NULL,
  `efile` varchar(191) DEFAULT NULL,
  `createdby` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tgl_transfer` date NOT NULL,
  `rekening_asal` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rekening_tujuan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jml_transfer` decimal(15,2) NOT NULL,
  `biaya_transfer` decimal(15,2) NOT NULL,
  `keterangan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdby` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transfers`
--

INSERT INTO `transfers` (`id`, `tgl_transfer`, `rekening_asal`, `rekening_tujuan`, `jml_transfer`, `biaya_transfer`, `keterangan`, `createdby`, `created_at`, `updated_at`) VALUES
(5, '2022-01-28', '700000001', '90000000001', '500000.00', '6500.00', 'Test pindah dana', 'Administrator', '2022-01-28 03:20:15', NULL),
(6, '2022-01-28', '700000001', '700000002', '1000000.00', '0.00', 'transfer antar BCA', 'Administrator', '2022-01-28 03:21:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `userroles`
--

CREATE TABLE `userroles` (
  `userid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `userroles`
--

INSERT INTO `userroles` (`userid`, `roleid`, `created_at`, `updated_at`) VALUES
(1, 3, '2022-01-19 02:45:03', NULL),
(2, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'husnulmub@gmail.com', 'sys-admin', NULL, '$2y$12$YdMPvKhIfJUuAkI32jDIlevVSU1RB.yd5ptte/Y/.5zOBMGRgi3z2', NULL, '2022-01-19 02:45:03', NULL),
(2, 'admin1 Tes Update', 'admin1@mail.com', 'admin1', NULL, '$2y$12$WjFM6ZQeKYEtEE/E7OPYXOptssJvTqq0zOMHVKkwZj2LDC3q5..py', NULL, NULL, NULL),
(3, 'admin2', 'admin2@mail.com', 'admin2', NULL, '$2y$12$7KdC7vz8bPybDC1PdRCqg.7Zg0l9u6ALYsXD39FvohPE.qUtBAxiG', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_menuroles`
-- (See below for the actual view)
--
CREATE TABLE `v_menuroles` (
`id` bigint(20) unsigned
,`name` varchar(100)
,`route` varchar(100)
,`menugroup` int(11)
,`type` varchar(20)
,`created_at` timestamp
,`updated_at` timestamp
,`groupname` varchar(50)
,`groupicon` varchar(50)
,`_index` int(11)
,`roleid` int(11)
,`menuid` int(11)
,`rolename` varchar(191)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_menus`
-- (See below for the actual view)
--
CREATE TABLE `v_menus` (
`id` bigint(20) unsigned
,`name` varchar(100)
,`route` varchar(100)
,`menugroup` int(11)
,`type` varchar(20)
,`created_at` timestamp
,`updated_at` timestamp
,`groupname` varchar(50)
,`groupicon` varchar(50)
,`_index` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_usermenus`
-- (See below for the actual view)
--
CREATE TABLE `v_usermenus` (
`id` bigint(20) unsigned
,`name` varchar(191)
,`email` varchar(50)
,`roleid` int(11)
,`role` bigint(20) unsigned
,`rolename` varchar(191)
,`menuid` int(11)
,`description` varchar(100)
,`route` varchar(100)
,`menugroup` int(11)
,`groupname` varchar(50)
,`groupicon` varchar(50)
,`type` varchar(20)
,`_index` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_userroles`
-- (See below for the actual view)
--
CREATE TABLE `v_userroles` (
`id` bigint(20) unsigned
,`name` varchar(191)
,`email` varchar(50)
,`roleid` int(11)
,`role` varchar(191)
);

-- --------------------------------------------------------

--
-- Table structure for table `withdraws`
--

CREATE TABLE `withdraws` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `idplayer` varchar(50) NOT NULL,
  `playername` varchar(100) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `wdpdate` date NOT NULL,
  `wd_status` varchar(50) NOT NULL,
  `rekening_sumber` varchar(30) DEFAULT NULL,
  `efile` varchar(191) DEFAULT NULL,
  `createdby` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure for view `v_menuroles`
--
DROP TABLE IF EXISTS `v_menuroles`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `v_menuroles`  AS  select `a`.`id` AS `id`,`a`.`name` AS `name`,`a`.`route` AS `route`,`a`.`menugroup` AS `menugroup`,`a`.`type` AS `type`,`a`.`created_at` AS `created_at`,`a`.`updated_at` AS `updated_at`,`a`.`groupname` AS `groupname`,`a`.`groupicon` AS `groupicon`,`a`.`_index` AS `_index`,`b`.`roleid` AS `roleid`,`b`.`menuid` AS `menuid`,`c`.`rolename` AS `rolename` from ((`v_menus` `a` join `menuroles` `b` on(`a`.`id` = `b`.`menuid`)) join `roles` `c` on(`b`.`roleid` = `c`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_menus`
--
DROP TABLE IF EXISTS `v_menus`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `v_menus`  AS  select `a`.`id` AS `id`,`a`.`name` AS `name`,`a`.`route` AS `route`,`a`.`menugroup` AS `menugroup`,`a`.`type` AS `type`,`a`.`created_at` AS `created_at`,`a`.`updated_at` AS `updated_at`,`b`.`description` AS `groupname`,`b`.`groupicon` AS `groupicon`,`b`.`_index` AS `_index` from (`menus` `a` left join `menugroups` `b` on(`a`.`menugroup` = `b`.`id`)) order by `a`.`menugroup`,`a`.`id` ;

-- --------------------------------------------------------

--
-- Structure for view `v_usermenus`
--
DROP TABLE IF EXISTS `v_usermenus`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `v_usermenus`  AS  select `a`.`id` AS `id`,`a`.`name` AS `name`,`a`.`email` AS `email`,`b`.`roleid` AS `roleid`,`c`.`id` AS `role`,`c`.`rolename` AS `rolename`,`d`.`menuid` AS `menuid`,`e`.`name` AS `description`,`e`.`route` AS `route`,`e`.`menugroup` AS `menugroup`,`f`.`description` AS `groupname`,`f`.`groupicon` AS `groupicon`,`e`.`type` AS `type`,`f`.`_index` AS `_index` from (((((`users` `a` join `userroles` `b` on(`a`.`id` = `b`.`userid`)) join `roles` `c` on(`b`.`roleid` = `c`.`id`)) join `menuroles` `d` on(`c`.`id` = `d`.`roleid`)) join `menus` `e` on(`d`.`menuid` = `e`.`id`)) join `menugroups` `f` on(`e`.`menugroup` = `f`.`id`)) order by `f`.`_index`,`a`.`id`,`b`.`roleid`,`d`.`menuid` ;

-- --------------------------------------------------------

--
-- Structure for view `v_userroles`
--
DROP TABLE IF EXISTS `v_userroles`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `v_userroles`  AS  select `a`.`id` AS `id`,`a`.`name` AS `name`,`a`.`email` AS `email`,`b`.`roleid` AS `roleid`,`c`.`rolename` AS `role` from ((`users` `a` join `userroles` `b` on(`a`.`id` = `b`.`userid`)) join `roles` `c` on(`b`.`roleid` = `c`.`id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acounting_docs`
--
ALTER TABLE `acounting_docs`
  ADD PRIMARY KEY (`docnum`,`docyear`);

--
-- Indexes for table `acounting_doc_items`
--
ALTER TABLE `acounting_doc_items`
  ADD PRIMARY KEY (`docnum`,`docyear`,`docitem`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cashflows`
--
ALTER TABLE `cashflows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chart_of_accounts_account_unique` (`account`),
  ADD UNIQUE KEY `chart_of_accounts_account_name_unique` (`account_name`);

--
-- Indexes for table `coin_stocks`
--
ALTER TABLE `coin_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_configurations`
--
ALTER TABLE `email_configurations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menugroups`
--
ALTER TABLE `menugroups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menuroles`
--
ALTER TABLE `menuroles`
  ADD PRIMARY KEY (`menuid`,`roleid`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nrivs`
--
ALTER TABLE `nrivs`
  ADD PRIMARY KEY (`object`);

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
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`playerid`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `topups`
--
ALTER TABLE `topups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userroles`
--
ALTER TABLE `userroles`
  ADD PRIMARY KEY (`userid`,`roleid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Indexes for table `withdraws`
--
ALTER TABLE `withdraws`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `cashflows`
--
ALTER TABLE `cashflows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `coin_stocks`
--
ALTER TABLE `coin_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_configurations`
--
ALTER TABLE `email_configurations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menugroups`
--
ALTER TABLE `menugroups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `topups`
--
ALTER TABLE `topups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `withdraws`
--
ALTER TABLE `withdraws`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
