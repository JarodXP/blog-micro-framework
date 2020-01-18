-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le :  ven. 17 jan. 2020 à 16:41
-- Version du serveur :  5.7.28
-- Version de PHP :  7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `pseudo` varchar(300) DEFAULT NULL,
  `content` mediumtext NOT NULL,
  `date_added` datetime NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `network_links`
--

DROP TABLE IF EXISTS `network_links`;
CREATE TABLE IF NOT EXISTS `network_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `network_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `link` varchar(300) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `network_id` (`network_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(300) DEFAULT NULL,
  `extract` mediumtext,
  `content` mediumtext,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `posts_uploads`
--

DROP TABLE IF EXISTS `posts_uploads`;
CREATE TABLE IF NOT EXISTS `posts_uploads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `used_as` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `upload_id` (`upload_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `social_networks`
--

DROP TABLE IF EXISTS `social_networks`;
CREATE TABLE IF NOT EXISTS `social_networks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) DEFAULT NULL,
  `upload_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `upload_id` (`upload_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `uploads`
--

DROP TABLE IF EXISTS `uploads`;
CREATE TABLE IF NOT EXISTS `uploads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(300) NOT NULL DEFAULT 'NULL',
  `original_name` varchar(300) NOT NULL DEFAULT 'NULL',
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_name` (`file_name`),
  UNIQUE KEY `original_name` (`original_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(300) DEFAULT NULL,
  `email` varchar(300) NOT NULL DEFAULT 'NULL',
  `password` varchar(300) NOT NULL,
  `role` tinyint(4) NOT NULL,
  `first_name` varchar(300) DEFAULT NULL,
  `last_name` varchar(500) DEFAULT NULL,
  `title` varchar(300) DEFAULT NULL,
  `phone` varchar(300) DEFAULT NULL,
  `baseline` mediumtext,
  `introduction` mediumtext,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `users_uploads`
--

DROP TABLE IF EXISTS `users_uploads`;
CREATE TABLE IF NOT EXISTS `users_uploads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `upload_id` int(11) NOT NULL,
  `used_as` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `upload_id` (`upload_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
