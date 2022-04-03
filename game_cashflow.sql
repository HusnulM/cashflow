-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2022 at 04:22 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

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
-- Functions
--
CREATE  FUNCTION `fCountAfiliator` (`pPlayerId` VARCHAR(30)) RETURNS INT(11) BEGIN
    DECLARE hasil int;
	
    SET hasil = (SELECT Count(*) From players WHERE afiliator = pPlayerId);
    	-- return the customer level
	RETURN (hasil);
END$$

CREATE  FUNCTION `fGetSaldoBank` (`pNorekening` VARCHAR(30)) RETURNS DECIMAL(15,2) BEGIN
    DECLARE saldo decimal(15,2);
	
    SET saldo = (SELECT balance from cashflows where to_acc = pNorekening order by id desc limit 1);
    	-- return the customer level
	RETURN (saldo);
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
  `bank_type` varchar(30) DEFAULT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bank_lists`
--

CREATE TABLE `bank_lists` (
  `bankid` varchar(20) NOT NULL,
  `deskripsi` varchar(50) NOT NULL,
  `createdby` varchar(50) NOT NULL,
  `createdon` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bank_lists`
--

INSERT INTO `bank_lists` (`bankid`, `deskripsi`, `createdby`, `createdon`) VALUES
('BCA', 'Bank Central Asia', 'admin', '2022-03-24'),
('BNI', 'Bank BNI', 'sys-admin', '2022-03-24'),
('BRI', 'Bank BRI', 'sys-admin', '2022-03-24'),
('MAN', 'Bank Mandiri', 'sys-admin', '2022-03-24');

-- --------------------------------------------------------

--
-- Table structure for table `biaya_adm_tf`
--

CREATE TABLE `biaya_adm_tf` (
  `bank_asal` varchar(20) NOT NULL,
  `bank_tujuan` varchar(20) NOT NULL,
  `biaya_adm` decimal(15,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `biaya_adm_tf`
--

INSERT INTO `biaya_adm_tf` (`bank_asal`, `bank_tujuan`, `biaya_adm`) VALUES
('BCA', 'BNI', '2500'),
('BCA', 'BRI', '3500'),
('BCA', 'MAN', '3500'),
('BNI', 'BRI', '2500'),
('BRI', 'BCA', '2500'),
('BRI', 'BNI', '5500');

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
  `bankcode` varchar(30) CHARACTER SET utf8mb4 NOT NULL,
  `bankacc` varchar(30) CHARACTER SET utf8mb4 NOT NULL,
  `totalcoin` decimal(15,2) NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coin_stocks`
--

INSERT INTO `coin_stocks` (`id`, `bankcode`, `bankacc`, `totalcoin`, `createdby`, `created_at`, `updated_at`) VALUES
(1, 'BCA', '90000001', '546500.00', 'Administrator', '2022-03-26 04:23:13', '2022-03-28 07:26:55'),
(2, 'BRI', '500000001', '5100000.00', 'Administrator', '2022-03-26 04:23:46', '2022-03-26 04:31:41'),
(3, 'BNI', '4000000001', '15000000.00', 'Administrator', '2022-03-26 04:24:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tgl_deposit` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `keterangan` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `bankacc` varchar(30) CHARACTER SET utf8mb4 DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
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
  `keterangan` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `bank_account` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
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
  `keterangan` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `bank_account` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
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
(4, 2, NULL, NULL),
(4, 3, NULL, NULL),
(5, 2, NULL, NULL),
(5, 3, NULL, NULL),
(6, 2, NULL, NULL),
(6, 3, NULL, NULL),
(7, 2, NULL, NULL),
(7, 3, NULL, NULL),
(8, 2, NULL, NULL),
(8, 3, NULL, NULL),
(9, 2, NULL, NULL),
(9, 3, NULL, NULL),
(10, 2, NULL, NULL),
(10, 3, NULL, NULL),
(11, 3, NULL, NULL),
(12, 2, NULL, NULL),
(12, 3, NULL, NULL),
(13, 2, NULL, NULL),
(13, 3, NULL, NULL),
(14, 3, NULL, NULL),
(15, 3, NULL, NULL),
(16, 3, NULL, NULL),
(17, 3, NULL, NULL),
(18, 3, NULL, NULL),
(19, 3, NULL, NULL),
(22, 3, NULL, NULL),
(23, 2, NULL, NULL),
(23, 3, NULL, NULL),
(24, 3, NULL, NULL),
(25, 2, NULL, NULL),
(25, 3, NULL, NULL),
(26, 2, NULL, NULL),
(27, 3, NULL, NULL),
(28, 3, NULL, NULL);

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
  `sort_num` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `route`, `menugroup`, `type`, `sort_num`, `created_at`, `updated_at`) VALUES
(1, 'Bank', 'master/bank', 1, NULL, 2, NULL, NULL),
(2, 'Player', 'master/player', 1, NULL, 3, NULL, NULL),
(3, 'TOP Up Coin', 'transaksi/topup', 2, NULL, NULL, NULL, NULL),
(4, 'Deposit', 'transaksi/deposit', 2, NULL, NULL, NULL, NULL),
(5, 'Withdraw', 'transaksi/withdraw', 2, NULL, NULL, NULL, NULL),
(6, 'Pindah Dana', 'transaksi/transfer', 2, NULL, NULL, NULL, NULL),
(7, 'Pemasukan', 'transaksi/pemasukan', 2, NULL, NULL, NULL, NULL),
(8, 'Pengeluaran', 'transaksi/pengeluaran', 2, NULL, NULL, NULL, NULL),
(9, 'Laporan Pemasukan', 'laporan/pemasukan', 3, NULL, NULL, NULL, NULL),
(10, 'Laporan Pengeluaran', 'laporan/pengeluaran', 3, NULL, NULL, NULL, NULL),
(11, 'Laporan Top Up', 'laporan/topup', 3, NULL, NULL, NULL, NULL),
(12, 'Laporan Mutasi', 'laporan/mutasi', 3, NULL, NULL, NULL, NULL),
(13, 'Laporan Saldo Bank', 'laporan/saldobank', 3, NULL, NULL, NULL, NULL),
(14, 'Data User', 'setting/users', 4, NULL, NULL, NULL, NULL),
(15, 'Data Menu Group', 'setting/menugroups', 4, NULL, NULL, NULL, NULL),
(16, 'Data Menu', 'setting/menus', 4, NULL, NULL, NULL, NULL),
(17, 'Data Role', 'setting/roles', 4, NULL, NULL, NULL, NULL),
(18, 'Data Menu Role', 'setting/menuroles', 4, NULL, NULL, NULL, NULL),
(19, 'Data User Role', 'setting/userroles', 4, NULL, NULL, NULL, NULL),
(20, 'Verifikasi Topup', 'transaksi/topup/verify', 2, NULL, NULL, NULL, NULL),
(21, 'Chart of Account', 'master/coa', 1, NULL, 4, NULL, NULL),
(22, 'Verifikasi Withdraw', 'transaksi/withdraw/verify', 2, NULL, NULL, NULL, NULL),
(23, 'Laporan Withdraw', 'laporan/withdraw', 3, NULL, NULL, NULL, NULL),
(24, 'Verifikasi Deposit', 'transaksi/deposit/verify', 2, NULL, NULL, NULL, NULL),
(25, 'Laporan Deposit', 'laporan/deposit', 3, NULL, NULL, NULL, NULL),
(26, 'Laporan Stock Coin', 'laporan/stockcoin', 3, NULL, NULL, NULL, NULL),
(27, 'Master List Bank', 'master/banklist', 1, NULL, 1, NULL, NULL),
(28, 'Biaya Adm Transfer Bank', 'master/biayaadmin', 1, NULL, NULL, NULL, NULL);

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
  `bankid` varchar(20) NOT NULL,
  `bankname` varchar(100) NOT NULL,
  `bankacc` varchar(30) NOT NULL,
  `afiliator` varchar(40) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Table structure for table `stock_coins`
--

CREATE TABLE `stock_coins` (
  `id` int(11) NOT NULL,
  `quantity` decimal(15,0) NOT NULL,
  `createdon` date NOT NULL,
  `updatedon` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(2, 1, NULL, NULL),
(4, 2, NULL, NULL);

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
  `usertype` varchar(25) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `email_verified_at`, `password`, `remember_token`, `usertype`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'husnulmub@gmail.com', 'sys-admin', NULL, '$2y$12$YdMPvKhIfJUuAkI32jDIlevVSU1RB.yd5ptte/Y/.5zOBMGRgi3z2', NULL, 'Administrator', '2022-01-19 02:45:03', NULL),
(2, 'admin1 Tes Update', 'admin1@mail.com', 'admin1', NULL, '$2y$12$WjFM6ZQeKYEtEE/E7OPYXOptssJvTqq0zOMHVKkwZj2LDC3q5..py', NULL, 'Admin', NULL, NULL),
(3, 'admin2', 'admin2@mail.com', 'admin2', NULL, '$2y$12$7KdC7vz8bPybDC1PdRCqg.7Zg0l9u6ALYsXD39FvohPE.qUtBAxiG', NULL, 'Admin', NULL, NULL),
(4, 'CS 1', 'cs1@mail.com', 'cs01', NULL, '$2y$12$Td7vFQhF6ym0JiegdG7wm.0hixSOASDMpepfR3BTQ07sjF7RaVMD6', NULL, 'CS', NULL, NULL),
(5, 'owner', 'Owner@mail.com', 'owner', NULL, '$2y$12$0narjQS6ETIpcFxNnMHWqeRsNc75L9GQC0PKT7nvr9BZaqbiPXu9u', NULL, 'Owner', NULL, NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_banks`
-- (See below for the actual view)
--
CREATE TABLE `v_banks` (
`id` int(11)
,`bankid` varchar(10)
,`bankname` varchar(100)
,`bank_accountnumber` varchar(40)
,`bank_accountname` varchar(100)
,`opening_balance` decimal(15,2)
,`bank_type` varchar(30)
,`createdby` varchar(50)
,`created_at` timestamp
,`updated_at` timestamp
,`saldo` decimal(15,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_cashflows`
-- (See below for the actual view)
--
CREATE TABLE `v_cashflows` (
`id` bigint(20) unsigned
,`transdate` date
,`note` varchar(100)
,`from_acc` varchar(30)
,`to_acc` varchar(30)
,`debit` decimal(15,2)
,`credit` decimal(15,2)
,`balance` decimal(15,2)
,`refdoc` varchar(50)
,`efile` varchar(250)
,`createdby` varchar(50)
,`created_at` timestamp
,`updated_at` timestamp
,`bank_type` varchar(30)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_coin_stocks`
-- (See below for the actual view)
--
CREATE TABLE `v_coin_stocks` (
`id` int(11)
,`bankid` varchar(10)
,`bankname` varchar(100)
,`bank_accountnumber` varchar(40)
,`bank_accountname` varchar(100)
,`opening_balance` decimal(15,2)
,`bank_type` varchar(30)
,`createdby` varchar(50)
,`created_at` timestamp
,`updated_at` timestamp
,`saldo` decimal(15,2)
,`totalcoin` decimal(15,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_deposit_players`
-- (See below for the actual view)
--
CREATE TABLE `v_deposit_players` (
`id` bigint(20) unsigned
,`idplayer` varchar(50)
,`playername` varchar(100)
,`amount` decimal(15,2)
,`topup_bonus` decimal(15,2)
,`topupdate` date
,`topup_status` varchar(50)
,`rekening_tujuan` varchar(30)
,`efile` varchar(191)
,`createdby` varchar(50)
,`created_at` timestamp
,`updated_at` timestamp
,`bankid` varchar(20)
,`bankname` varchar(100)
,`bankacc` varchar(30)
,`bankidto` varchar(10)
,`banknameto` varchar(100)
,`bank_type` varchar(30)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_expenses`
-- (See below for the actual view)
--
CREATE TABLE `v_expenses` (
`id` bigint(20) unsigned
,`tgl_pengeluaran` date
,`amount` decimal(15,2)
,`keterangan` varchar(100)
,`bank_account` varchar(100)
,`createdby` varchar(50)
,`created_at` timestamp
,`updated_at` timestamp
,`bank_type` varchar(30)
,`bank_accountname` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_incomes`
-- (See below for the actual view)
--
CREATE TABLE `v_incomes` (
`id` bigint(20) unsigned
,`tgl_pemasukan` date
,`amount` decimal(15,2)
,`keterangan` varchar(100)
,`bank_account` varchar(100)
,`createdby` varchar(50)
,`created_at` timestamp
,`updated_at` timestamp
,`bank_type` varchar(30)
,`bank_accountname` varchar(100)
);

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
-- Stand-in structure for view `v_players`
-- (See below for the actual view)
--
CREATE TABLE `v_players` (
`playerid` varchar(70)
,`playername` varchar(100)
,`bankname` varchar(100)
,`bankacc` varchar(30)
,`afiliator` varchar(40)
,`created_at` timestamp
,`updated_at` timestamp
,`totalafiliator` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_saldobank`
-- (See below for the actual view)
--
CREATE TABLE `v_saldobank` (
`bankid` varchar(10)
,`bankname` varchar(100)
,`bank_accountnumber` varchar(40)
,`bank_accountname` varchar(100)
,`saldo` decimal(15,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_topups`
-- (See below for the actual view)
--
CREATE TABLE `v_topups` (
`id` bigint(20) unsigned
,`tgl_deposit` date
,`amount` decimal(15,2)
,`keterangan` varchar(100)
,`bankacc` varchar(30)
,`bankname` varchar(100)
,`bank_accountname` varchar(100)
,`createdby` varchar(50)
,`created_at` timestamp
,`updated_at` timestamp
,`bank_type` varchar(30)
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
-- Stand-in structure for view `v_withdraws`
-- (See below for the actual view)
--
CREATE TABLE `v_withdraws` (
`id` bigint(20) unsigned
,`idplayer` varchar(50)
,`playername` varchar(100)
,`amount` decimal(15,2)
,`biaya_adm` decimal(15,0)
,`wdpdate` date
,`wd_status` varchar(50)
,`rekening_sumber` varchar(30)
,`efile` varchar(191)
,`createdby` varchar(50)
,`created_at` timestamp
,`updated_at` timestamp
,`bank_sumbername` varchar(100)
,`bank_sumberid` varchar(10)
,`bankid` varchar(20)
,`bankname` varchar(100)
,`bankacc` varchar(30)
,`bank_type` varchar(30)
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
  `biaya_adm` decimal(15,0) NOT NULL DEFAULT 0,
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
-- Structure for view `v_banks`
--
DROP TABLE IF EXISTS `v_banks`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `v_banks`  AS SELECT `banks`.`id` AS `id`, `banks`.`bankid` AS `bankid`, `banks`.`bankname` AS `bankname`, `banks`.`bank_accountnumber` AS `bank_accountnumber`, `banks`.`bank_accountname` AS `bank_accountname`, `banks`.`opening_balance` AS `opening_balance`, `banks`.`bank_type` AS `bank_type`, `banks`.`createdby` AS `createdby`, `banks`.`created_at` AS `created_at`, `banks`.`updated_at` AS `updated_at`, `fGetSaldoBank`(`banks`.`bank_accountnumber`) AS `saldo` FROM `banks` ;

-- --------------------------------------------------------

--
-- Structure for view `v_cashflows`
--
DROP TABLE IF EXISTS `v_cashflows`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `v_cashflows`  AS SELECT `a`.`id` AS `id`, `a`.`transdate` AS `transdate`, `a`.`note` AS `note`, `a`.`from_acc` AS `from_acc`, `a`.`to_acc` AS `to_acc`, `a`.`debit` AS `debit`, `a`.`credit` AS `credit`, `a`.`balance` AS `balance`, `a`.`refdoc` AS `refdoc`, `a`.`efile` AS `efile`, `a`.`createdby` AS `createdby`, `a`.`created_at` AS `created_at`, `a`.`updated_at` AS `updated_at`, `b`.`bank_type` AS `bank_type` FROM (`cashflows` `a` join `v_banks` `b` on(`a`.`to_acc` = `b`.`bank_accountnumber`)) ORDER BY `a`.`id` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_coin_stocks`
--
DROP TABLE IF EXISTS `v_coin_stocks`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `v_coin_stocks`  AS SELECT `a`.`id` AS `id`, `a`.`bankid` AS `bankid`, `a`.`bankname` AS `bankname`, `a`.`bank_accountnumber` AS `bank_accountnumber`, `a`.`bank_accountname` AS `bank_accountname`, `a`.`opening_balance` AS `opening_balance`, `a`.`bank_type` AS `bank_type`, `a`.`createdby` AS `createdby`, `a`.`created_at` AS `created_at`, `a`.`updated_at` AS `updated_at`, `a`.`saldo` AS `saldo`, `b`.`totalcoin` AS `totalcoin` FROM (`v_banks` `a` join `coin_stocks` `b` on(`a`.`bankid` = `b`.`bankcode` and `a`.`bank_accountnumber` = `b`.`bankacc`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_deposit_players`
--
DROP TABLE IF EXISTS `v_deposit_players`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `v_deposit_players`  AS SELECT `a`.`id` AS `id`, `a`.`idplayer` AS `idplayer`, `a`.`playername` AS `playername`, `a`.`amount` AS `amount`, `a`.`topup_bonus` AS `topup_bonus`, `a`.`topupdate` AS `topupdate`, `a`.`topup_status` AS `topup_status`, `a`.`rekening_tujuan` AS `rekening_tujuan`, `a`.`efile` AS `efile`, `a`.`createdby` AS `createdby`, `a`.`created_at` AS `created_at`, `a`.`updated_at` AS `updated_at`, `b`.`bankid` AS `bankid`, `b`.`bankname` AS `bankname`, `b`.`bankacc` AS `bankacc`, `c`.`bankid` AS `bankidto`, `c`.`bankname` AS `banknameto`, `c`.`bank_type` AS `bank_type` FROM ((`topups` `a` join `players` `b` on(`a`.`idplayer` = `b`.`playerid`)) left join `banks` `c` on(`a`.`rekening_tujuan` = `c`.`bank_accountnumber`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_expenses`
--
DROP TABLE IF EXISTS `v_expenses`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `v_expenses`  AS SELECT `a`.`id` AS `id`, `a`.`tgl_pengeluaran` AS `tgl_pengeluaran`, `a`.`amount` AS `amount`, `a`.`keterangan` AS `keterangan`, `a`.`bank_account` AS `bank_account`, `a`.`createdby` AS `createdby`, `a`.`created_at` AS `created_at`, `a`.`updated_at` AS `updated_at`, `b`.`bank_type` AS `bank_type`, `b`.`bank_accountname` AS `bank_accountname` FROM (`expenses` `a` join `v_banks` `b` on(`a`.`bank_account` = `b`.`bank_accountnumber`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_incomes`
--
DROP TABLE IF EXISTS `v_incomes`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `v_incomes`  AS SELECT `a`.`id` AS `id`, `a`.`tgl_pemasukan` AS `tgl_pemasukan`, `a`.`amount` AS `amount`, `a`.`keterangan` AS `keterangan`, `a`.`bank_account` AS `bank_account`, `a`.`createdby` AS `createdby`, `a`.`created_at` AS `created_at`, `a`.`updated_at` AS `updated_at`, `b`.`bank_type` AS `bank_type`, `b`.`bank_accountname` AS `bank_accountname` FROM (`incomes` `a` join `v_banks` `b` on(`a`.`bank_account` = `b`.`bank_accountnumber`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_menuroles`
--
DROP TABLE IF EXISTS `v_menuroles`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `v_menuroles`  AS SELECT `a`.`id` AS `id`, `a`.`name` AS `name`, `a`.`route` AS `route`, `a`.`menugroup` AS `menugroup`, `a`.`type` AS `type`, `a`.`created_at` AS `created_at`, `a`.`updated_at` AS `updated_at`, `a`.`groupname` AS `groupname`, `a`.`groupicon` AS `groupicon`, `a`.`_index` AS `_index`, `b`.`roleid` AS `roleid`, `b`.`menuid` AS `menuid`, `c`.`rolename` AS `rolename` FROM ((`v_menus` `a` join `menuroles` `b` on(`a`.`id` = `b`.`menuid`)) join `roles` `c` on(`b`.`roleid` = `c`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_menus`
--
DROP TABLE IF EXISTS `v_menus`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `v_menus`  AS SELECT `a`.`id` AS `id`, `a`.`name` AS `name`, `a`.`route` AS `route`, `a`.`menugroup` AS `menugroup`, `a`.`type` AS `type`, `a`.`created_at` AS `created_at`, `a`.`updated_at` AS `updated_at`, `b`.`description` AS `groupname`, `b`.`groupicon` AS `groupicon`, `b`.`_index` AS `_index` FROM (`menus` `a` left join `menugroups` `b` on(`a`.`menugroup` = `b`.`id`)) ORDER BY `a`.`menugroup` ASC, `a`.`id` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_players`
--
DROP TABLE IF EXISTS `v_players`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `v_players`  AS SELECT `players`.`playerid` AS `playerid`, `players`.`playername` AS `playername`, `players`.`bankname` AS `bankname`, `players`.`bankacc` AS `bankacc`, `players`.`afiliator` AS `afiliator`, `players`.`created_at` AS `created_at`, `players`.`updated_at` AS `updated_at`, `fCountAfiliator`(`players`.`playerid`) AS `totalafiliator` FROM `players` ORDER BY `players`.`playerid` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_saldobank`
--
DROP TABLE IF EXISTS `v_saldobank`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `v_saldobank`  AS SELECT `banks`.`bankid` AS `bankid`, `banks`.`bankname` AS `bankname`, `banks`.`bank_accountnumber` AS `bank_accountnumber`, `banks`.`bank_accountname` AS `bank_accountname`, `fGetSaldoBank`(`banks`.`bank_accountnumber`) AS `saldo` FROM `banks` ORDER BY `banks`.`bankid` ASC, `banks`.`bank_accountnumber` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_topups`
--
DROP TABLE IF EXISTS `v_topups`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `v_topups`  AS SELECT `a`.`id` AS `id`, `a`.`tgl_deposit` AS `tgl_deposit`, `a`.`amount` AS `amount`, `a`.`keterangan` AS `keterangan`, `a`.`bankacc` AS `bankacc`, `b`.`bankname` AS `bankname`, `b`.`bank_accountname` AS `bank_accountname`, `a`.`createdby` AS `createdby`, `a`.`created_at` AS `created_at`, `a`.`updated_at` AS `updated_at`, `b`.`bank_type` AS `bank_type` FROM (`deposits` `a` join `v_banks` `b` on(`a`.`bankacc` = `b`.`bank_accountnumber`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_usermenus`
--
DROP TABLE IF EXISTS `v_usermenus`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `v_usermenus`  AS SELECT `a`.`id` AS `id`, `a`.`name` AS `name`, `a`.`email` AS `email`, `b`.`roleid` AS `roleid`, `c`.`id` AS `role`, `c`.`rolename` AS `rolename`, `d`.`menuid` AS `menuid`, `e`.`name` AS `description`, `e`.`route` AS `route`, `e`.`menugroup` AS `menugroup`, `f`.`description` AS `groupname`, `f`.`groupicon` AS `groupicon`, `e`.`type` AS `type`, `f`.`_index` AS `_index` FROM (((((`users` `a` join `userroles` `b` on(`a`.`id` = `b`.`userid`)) join `roles` `c` on(`b`.`roleid` = `c`.`id`)) join `menuroles` `d` on(`c`.`id` = `d`.`roleid`)) join `menus` `e` on(`d`.`menuid` = `e`.`id`)) join `menugroups` `f` on(`e`.`menugroup` = `f`.`id`)) ORDER BY `f`.`_index` ASC, `a`.`id` ASC, `b`.`roleid` ASC, `d`.`menuid` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `v_userroles`
--
DROP TABLE IF EXISTS `v_userroles`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `v_userroles`  AS SELECT `a`.`id` AS `id`, `a`.`name` AS `name`, `a`.`email` AS `email`, `b`.`roleid` AS `roleid`, `c`.`rolename` AS `role` FROM ((`users` `a` join `userroles` `b` on(`a`.`id` = `b`.`userid`)) join `roles` `c` on(`b`.`roleid` = `c`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_withdraws`
--
DROP TABLE IF EXISTS `v_withdraws`;

CREATE ALGORITHM=UNDEFINED  SQL SECURITY DEFINER VIEW `v_withdraws`  AS SELECT `a`.`id` AS `id`, `a`.`idplayer` AS `idplayer`, `a`.`playername` AS `playername`, `a`.`amount` AS `amount`, `a`.`biaya_adm` AS `biaya_adm`, `a`.`wdpdate` AS `wdpdate`, `a`.`wd_status` AS `wd_status`, `a`.`rekening_sumber` AS `rekening_sumber`, `a`.`efile` AS `efile`, `a`.`createdby` AS `createdby`, `a`.`created_at` AS `created_at`, `a`.`updated_at` AS `updated_at`, `c`.`bankname` AS `bank_sumbername`, `c`.`bankid` AS `bank_sumberid`, `b`.`bankid` AS `bankid`, `b`.`bankname` AS `bankname`, `b`.`bankacc` AS `bankacc`, `c`.`bank_type` AS `bank_type` FROM ((`withdraws` `a` join `players` `b` on(`a`.`idplayer` = `b`.`playerid`)) left join `banks` `c` on(`a`.`rekening_sumber` = `c`.`bank_accountnumber`)) ;

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
-- Indexes for table `bank_lists`
--
ALTER TABLE `bank_lists`
  ADD PRIMARY KEY (`bankid`);

--
-- Indexes for table `biaya_adm_tf`
--
ALTER TABLE `biaya_adm_tf`
  ADD PRIMARY KEY (`bank_asal`,`bank_tujuan`);

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
-- Indexes for table `stock_coins`
--
ALTER TABLE `stock_coins`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cashflows`
--
ALTER TABLE `cashflows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `coin_stocks`
--
ALTER TABLE `coin_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `withdraws`
--
ALTER TABLE `withdraws`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
