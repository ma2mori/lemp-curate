-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- ホスト: mysql
-- 生成日時: 2022 年 1 月 05 日 05:01
-- サーバのバージョン： 5.7.36
-- PHP のバージョン: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `curation_gc`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `articles`
--

CREATE TABLE `articles` (
  `id` bigint(100) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `img_src` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `article_to_tag`
--

CREATE TABLE `article_to_tag` (
  `id` bigint(100) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `article_views`
--

CREATE TABLE `article_views` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `n_user_id` varchar(255) NOT NULL,
  `total_view` bigint(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `comments`
--

CREATE TABLE `comments` (
  `id` bigint(100) NOT NULL,
  `n_user_id` varchar(255) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `anchor` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `contents`
--

CREATE TABLE `contents` (
  `id` bigint(100) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `content_body` text NOT NULL,
  `anchor` int(11) DEFAULT NULL,
  `come_lv` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- テーブルの構造 `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `topic_id` (`topic_id`);

--
-- テーブルのインデックス `article_to_tag`
--
ALTER TABLE `article_to_tag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- テーブルのインデックス `article_views`
--
ALTER TABLE `article_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- テーブルのインデックス `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- テーブルのインデックス `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `topic_id_2` (`topic_id`,`content_id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- テーブルのインデックス `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `articles`
--
ALTER TABLE `articles`
  MODIFY `id` bigint(100) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `article_to_tag`
--
ALTER TABLE `article_to_tag`
  MODIFY `id` bigint(100) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `article_views`
--
ALTER TABLE `article_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(100) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `contents`
--
ALTER TABLE `contents`
  MODIFY `id` bigint(100) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `article_to_tag`
--
ALTER TABLE `article_to_tag`
  ADD CONSTRAINT `article_to_tag_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `articles` (`topic_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `article_to_tag_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON UPDATE CASCADE;

--
-- テーブルの制約 `article_views`
--
ALTER TABLE `article_views`
  ADD CONSTRAINT `article_views_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `articles` (`topic_id`) ON UPDATE CASCADE;

--
-- テーブルの制約 `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `articles` (`topic_id`) ON UPDATE CASCADE;

--
-- テーブルの制約 `contents`
--
ALTER TABLE `contents`
  ADD CONSTRAINT `contents_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `articles` (`topic_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
