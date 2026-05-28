-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2026-05-15 03:16:38
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+09:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `contact_form`
--

CREATE DATABASE IF NOT EXISTS contact_form CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE contact_form;

-- --------------------------------------------------------

--
-- テーブルの構造 `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `postal_code` varchar(8) NOT NULL,
  `pref_code` int(11) NOT NULL,
  `city` varchar(50) NOT NULL,
  `address_line` varchar(50) NOT NULL,
  `email` varchar(256) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `contacts`
--



-- --------------------------------------------------------

--
-- テーブルの構造 `prefectures`
--

CREATE TABLE `prefectures` (
  `id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `prefectures`
--

INSERT INTO `prefectures` (`id`, `region_id`, `name`) VALUES
(1, 1, '北海道'),
(2, 2, '青森県'),
(3, 2, '岩手県'),
(4, 2, '宮城県'),
(5, 2, '秋田県'),
(6, 2, '山形県'),
(7, 2, '福島県'),
(8, 3, '茨城県'),
(9, 3, '栃木県'),
(10, 3, '群馬県'),
(11, 3, '埼玉県'),
(12, 3, '千葉県'),
(13, 3, '東京都'),
(14, 3, '神奈川県'),
(15, 4, '新潟県'),
(16, 4, '富山県'),
(17, 4, '石川県'),
(18, 4, '福井県'),
(19, 4, '山梨県'),
(20, 4, '長野県'),
(21, 4, '岐阜県'),
(22, 4, '静岡県'),
(23, 4, '愛知県'),
(24, 5, '三重県'),
(25, 5, '滋賀県'),
(26, 5, '京都府'),
(27, 5, '大阪府'),
(28, 5, '兵庫県'),
(29, 5, '奈良県'),
(30, 5, '和歌山県'),
(31, 6, '鳥取県'),
(32, 6, '島根県'),
(33, 6, '岡山県'),
(34, 6, '広島県'),
(35, 6, '山口県'),
(36, 7, '徳島県'),
(37, 7, '香川県'),
(38, 7, '愛媛県'),
(39, 7, '高知県'),
(40, 8, '福岡県'),
(41, 8, '佐賀県'),
(42, 8, '長崎県'),
(43, 8, '熊本県'),
(44, 8, '大分県'),
(45, 8, '宮崎県'),
(46, 8, '鹿児島県'),
(47, 8, '沖縄県');

-- --------------------------------------------------------

--
-- テーブルの構造 `regions`
--

CREATE TABLE `regions` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `regions`
--

INSERT INTO `regions` (`id`, `name`) VALUES
(1, '北海道'),
(2, '東北'),
(3, '関東'),
(4, '中部'),
(5, '近畿'),
(6, '中国'),
(7, '四国'),
(8, '九州・沖縄');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `prefectures`
--
ALTER TABLE `prefectures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `region_id` (`region_id`);

--
-- テーブルのインデックス `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `prefectures`
--
ALTER TABLE `prefectures`
  ADD CONSTRAINT `prefectures_ibfk_1` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
