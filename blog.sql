-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le :  ven. 31 jan. 2020 à 16:57
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
  `pseudo` varchar(300) NOT NULL,
  `content` mediumtext NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` varchar(300) NOT NULL,
  `title` varchar(300) DEFAULT NULL,
  `header_id` int(11) DEFAULT NULL,
  `extract` mediumtext,
  `content` mediumtext,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `slug` varchar(300) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `header_id` (`header_id`),
  KEY `author` (`author`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `author`, `title`, `header_id`, `extract`, `content`, `date_added`, `date_modified`, `status`, `slug`) VALUES
(3, 'Jun', 'Premier article', 37, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec orci mi, pretium ac pellentesque ullamcorper, iaculis at turpis. Nam posuere.', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus nisl augue, hendrerit non tincidunt dapibus, lobortis quis sapien. Maecenas imperdiet, lacus eget ornare efficitur, erat lorem condimentum justo, sed consectetur arcu nunc dignissim urna. Sed ultrices ligula sed lectus varius, et placerat enim varius. Suspendisse tempor lacus vitae leo fermentum, vel sollicitudin arcu posuere. Donec et augue et diam tristique fringilla. In non erat a tellus auctor sagittis. Maecenas sed nisl non lectus aliquet consequat in id magna. In tincidunt orci eu elit convallis viverra. In venenatis ut nibh eget malesuada. Maecenas dolor arcu, semper vel nisi tincidunt, ultricies volutpat elit. Cras ac consequat felis, at imperdiet ex. Phasellus ultricies nec elit nec euismod. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam condimentum congue semper. Mauris molestie quam sit amet placerat egestas. Suspendisse potenti. Quisque consequat auctor mi et scelerisque. Sed lobortis, neque ac auctor vulputate, ante elit aliquam lectus, id accumsan lorem ligula ac erat. Cras tempor sapien sed odio hendrerit, et fringilla nulla sagittis. Curabitur rutrum sapien vel pulvinar venenatis. Vestibulum posuere, nisl at tincidunt fringilla, massa eros hendrerit mi, sit amet aliquet lacus erat et arcu. Sed placerat aliquam pellentesque. Suspendisse tellus ante, egestas non quam convallis, tincidunt semper sem. Mauris dictum urna ac tellus maximus laoreet. Etiam vel nulla eget lacus ullamcorper vehicula eget sed velit. Nullam vel ligula tempus, bibendum neque ac, fringilla enim. Proin lacinia tortor dolor, nec fermentum nisl pharetra vel. Morbi finibus tortor a sagittis convallis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut vel porttitor massa. Sed eu magna metus. Integer et laoreet ante, a luctus dolor. Morbi faucibus gravida purus, vel dignissim massa elementum quis. Ut metus metus, pulvinar ut maximus sed, varius eu mauris. Ut consequat lorem eu pharetra pretium. Cras vulputate dignissim lorem et facilisis. Ut sed feugiat augue.</p>', '2020-01-28 14:29:56', '2020-01-28 14:29:56', 1, 'article-1'),
(4, 'Jun', '2e article', 27, NULL, NULL, '2020-01-28 15:26:16', '2020-01-28 15:26:16', 1, 'article-2'),
(5, 'Jun', '3e article', 27, NULL, NULL, '2020-01-28 15:26:16', '2020-01-28 15:26:16', 1, 'article-3'),
(6, 'Jun', '4e article', 27, NULL, NULL, '2020-01-28 15:26:16', '2020-01-28 15:26:16', 1, 'article-4'),
(7, 'Jun', '5e article', 27, NULL, NULL, '2020-01-28 15:26:16', '2020-01-28 15:26:16', 1, 'article-5'),
(8, 'Jun', '6e article', 27, NULL, NULL, '2020-01-28 15:26:16', '2020-01-28 15:26:16', 1, 'article-6'),
(9, 'Jun', '7e article', 27, NULL, NULL, '2020-01-28 15:26:16', '2020-01-28 15:26:16', 1, 'article-7'),
(10, 'Jun', '8e article', 27, NULL, NULL, '2020-01-28 15:26:16', '2020-01-28 15:26:16', 1, 'article-8'),
(11, 'Jun', '9e article', 27, NULL, NULL, '2020-01-28 15:26:16', '2020-01-28 15:26:16', 1, 'article-9'),
(12, 'Jun', '10e article', 27, NULL, NULL, '2020-01-28 15:26:16', '2020-01-28 15:26:16', 1, 'article-10'),
(13, 'Jun', '11e article', 27, NULL, NULL, '2020-01-28 15:26:16', '2020-01-28 15:26:16', 1, 'article-11'),
(14, 'Jun', '12e article', 27, NULL, NULL, '2020-01-28 15:26:16', '2020-01-28 15:26:16', 1, 'article-12'),
(18, 'Jun', 'Article Test', NULL, 'kjmljlkj', '<p>ljlkjmlkj</p>', '2020-01-30 14:03:40', '2020-01-30 14:03:40', 1, 'article-test'),
(19, 'Jun', 'Test2', NULL, 'ljlkjùlmkj', '<p>ljkmlùj</p>', '2020-01-30 14:05:41', '2020-01-30 14:05:41', 1, 'article-test-2'),
(20, 'Jun', 'Test3', NULL, 'kljljlm', '<p>kjmljml</p>', '2020-01-30 14:08:33', '2020-01-30 14:08:33', 1, 'article-test-3'),
(21, 'Jun', 'Test 4', NULL, 'kjlkjùml', '<p>jmlkjmlkj</p>', '2020-01-30 14:10:46', '2020-01-30 14:10:46', 1, 'article-test-4'),
(22, 'Jun', 'Test Image', 28, 'jlmjmlkj', '<p>lmjmljmlj</p>', '2020-01-30 15:20:14', '2020-01-30 15:20:14', 1, 'test-image');

-- --------------------------------------------------------

--
-- Structure de la table `social_networks`
--

DROP TABLE IF EXISTS `social_networks`;
CREATE TABLE IF NOT EXISTS `social_networks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `upload_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `upload_id` (`upload_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `uploads`
--

DROP TABLE IF EXISTS `uploads`;
CREATE TABLE IF NOT EXISTS `uploads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(300) NOT NULL DEFAULT '',
  `original_name` varchar(300) NOT NULL DEFAULT '',
  `alt` varchar(300) DEFAULT NULL,
  `type` varchar(300) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_name` (`file_name`),
  UNIQUE KEY `original_name` (`original_name`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `uploads`
--

INSERT INTO `uploads` (`id`, `file_name`, `original_name`, `alt`, `type`) VALUES
(27, 'postHeader.jpg', 'postHeader.jpg', 'skateboard', 'jpeg'),
(28, '39134505691082', 'adult-1850177_1920.jpg', 'poete', 'image/jpeg'),
(37, '49622528826810', 'paper-623167_1280.jpg', 'papier', 'image/jpeg'),
(39, '04830561711395', 'carbon.png', 'avatar', 'image/png');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(300) DEFAULT NULL,
  `email` varchar(300) NOT NULL,
  `password` varchar(300) NOT NULL,
  `role` tinyint(4) NOT NULL,
  `avatar_id` int(11) DEFAULT NULL,
  `first_name` varchar(300) DEFAULT NULL,
  `last_name` varchar(500) DEFAULT NULL,
  `title` varchar(300) DEFAULT NULL,
  `phone` varchar(300) DEFAULT NULL,
  `baseline` mediumtext,
  `introduction` mediumtext,
  `resume_id` int(11) DEFAULT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  KEY `avatar_id` (`avatar_id`),
  KEY `resume_id` (`resume_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `avatar_id`, `first_name`, `last_name`, `title`, `phone`, `baseline`, `introduction`, `resume_id`, `date_added`) VALUES
(15, NULL, 'test1', 'lkmlkm', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-01-21 14:11:03'),
(16, NULL, 'test2', ';,:mlk', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-01-21 14:11:53'),
(17, NULL, 'test3', 'kljlkjmj', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-01-21 14:11:53'),
(24, NULL, 'test9', 'lkjlkjm', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-01-21 14:13:57'),
(25, NULL, 'test4', 'ljmljm', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-01-21 14:13:57'),
(26, NULL, 'test5', 'jhkljhlk', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-01-21 14:13:57'),
(27, NULL, 'test6', 'kjhjkhl', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-01-21 14:13:57'),
(28, NULL, 'test7', 'kjhkjhl', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-01-21 14:13:57'),
(29, NULL, 'test8', 'jhkjhl', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-01-21 14:13:57'),
(30, 'Wawa', 'gregory.barile@gmail.com', '$2y$10$YGXhyIeOUcM4FwS5FwdlOOLvwwpN2osjQd9JbEoLxWg49nFbKZDvu', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-01-22 14:15:55'),
(34, 'gregorybarile', 'gregory@62rubystreet.com', '$2y$10$udfUsu2aVYzvQLGHZ0xJyeq3jiEy.r.tPtxNEyxJu8yCX8ROoR9dK', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-01-22 15:38:31'),
(35, 'zeze', 'ljlkjkl@kjmlkm.ki', '$2y$10$dvzvpg9.tcPRBEBJHRlya.l2.09ukSDMwQ.NmxFUGB.PYldN9pM3m', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-01-22 16:17:16'),
(36, 'yan', 'lkjljk@mkmk.fr', '$2y$10$NH8vDW7C7ycRqFzTBYDv5egUcoqGsYWhiCV.j2zaDH4gWKexiJlji', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-01-22 16:21:39'),
(37, 'yui', 'jlkjmlkj@mkmlk.gh', '$2y$10$yXqRbblSk/4oIO0IHhLccOjtBDiWw6SuOq39Mf5U7/KrZx/Hm.heK', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-01-22 16:22:27'),
(38, 'tyty', 'kljlm@kjlj.hy', '$2y$10$j6JIclbwWBjWgenwGZT/OubxMwIcu2Y6KrIpirlJyXccEgxA/qLnO', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-01-22 16:42:24'),
(39, 'oli', 'kjhjh@oioi.ki', '$2y$10$lAtPle5PRV4HfeMx5ebZUueV8LQqruG9.88HgO.Zoknyrz33dhjUC', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-01-22 16:44:29'),
(40, 'Jun', 'jun@gmail.com', '$2y$10$dVNQteoKiLzsniLegpLtauyINHtX8BAfvmkFDomVzo83B1zliapeG', 1, 39, 'Jérémy', 'Noiret', NULL, '06 64 22 28 11', 'Vers l\'infini et au-delà', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam feugiat auctor fringilla. Cras scelerisque laoreet ornare. Integer bibendum lectus at arcu euismod, vitae condimentum ligula blandit. Proin efficitur vel purus eu aliquet. Phasellus odio justo, porttitor eu aliquet sit amet, pretium ac urna. Nam a eleifend ex. Curabitur viverra nulla a dignissim sagittis. Sed viverra viverra mi rutrum auctor. Sed mollis est at elit vestibulum eleifend. Nullam auctor vulputate faucibus. Praesent placerat nulla nulla, vitae tincidunt magna semper eget. Suspendisse aliquam porttitor consectetur. Aenean sodales commodo velit, in condimentum massa pellentesque quis. Nullam sit amet tellus feugiat, ullamcorper est ut, finibus orci. Donec sed nulla vehicula, tincidunt nulla nec, suscipit metus. Ut tincidunt tortor nec metus scelerisque, ut auctor arcu vulputate. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla eget turpis vitae quam pellentesque laoreet. Aenean vel viverra urna, ut porttitor velit. Etiam dictum ipsum a tempor volutpat. Curabitur blandit turpis vel arcu fringilla, sit amet commodo sem feugiat. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras porttitor vehicula velit non interdum. In suscipit euismod ante porta convallis. Donec quis tempus tortor. Donec metus nulla, lobortis et lectus non, semper iaculis lectus. Sed consequat velit vitae nibh sollicitudin vehicula. Etiam tempus mi ut sem euismod, et interdum nibh aliquet. Vivamus a cursus diam, vitae laoreet ex. Proin vestibulum feugiat odio, nec consectetur augue pretium nec. Curabitur mauris libero, lobortis non mollis et, vestibulum sed velit. Mauris ullamcorper tincidunt bibendum. Suspendisse vel justo ut quam fringilla placerat. In vestibulum dictum quam sed suscipit.</p>', NULL, '2020-01-23 06:12:46');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Contraintes pour la table `network_links`
--
ALTER TABLE `network_links`
  ADD CONSTRAINT `network_links_ibfk_1` FOREIGN KEY (`network_id`) REFERENCES `social_networks` (`id`),
  ADD CONSTRAINT `network_links_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`header_id`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`author`) REFERENCES `users` (`username`);

--
-- Contraintes pour la table `social_networks`
--
ALTER TABLE `social_networks`
  ADD CONSTRAINT `social_networks_ibfk_1` FOREIGN KEY (`upload_id`) REFERENCES `uploads` (`id`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`avatar_id`) REFERENCES `uploads` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`resume_id`) REFERENCES `uploads` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
