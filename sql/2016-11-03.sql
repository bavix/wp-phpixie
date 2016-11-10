-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.13-log - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.3.0.5117
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for wbs
CREATE DATABASE IF NOT EXISTS `wbs` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `wbs`;

-- Dumping structure for table wbs.brands
CREATE TABLE IF NOT EXISTS `brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `webGroup` varchar(255) NOT NULL,
  `isCarbon` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'карбон',
  `isOffroad` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'вредорожник',
  ` isMultiple` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'мультибренд',
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'активность',
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table wbs.brands: ~0 rows (approximately)
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;

-- Dumping structure for table wbs.brandsDealers
CREATE TABLE IF NOT EXISTS `brandsDealers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brandId` int(11) NOT NULL,
  `dealerId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relationship` (`brandId`,`dealerId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table wbs.brandsDealers: ~0 rows (approximately)
/*!40000 ALTER TABLE `brandsDealers` DISABLE KEYS */;
/*!40000 ALTER TABLE `brandsDealers` ENABLE KEYS */;

-- Dumping structure for table wbs.brandsHeadings
CREATE TABLE IF NOT EXISTS `brandsHeadings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brandId` int(11) NOT NULL,
  `headingId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relationship` (`brandId`,`headingId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table wbs.brandsHeadings: ~0 rows (approximately)
/*!40000 ALTER TABLE `brandsHeadings` DISABLE KEYS */;
/*!40000 ALTER TABLE `brandsHeadings` ENABLE KEYS */;

-- Dumping structure for table wbs.configurations
CREATE TABLE IF NOT EXISTS `configurations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table wbs.configurations: ~0 rows (approximately)
/*!40000 ALTER TABLE `configurations` DISABLE KEYS */;
/*!40000 ALTER TABLE `configurations` ENABLE KEYS */;

-- Dumping structure for table wbs.dealers
CREATE TABLE IF NOT EXISTS `dealers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table wbs.dealers: ~0 rows (approximately)
/*!40000 ALTER TABLE `dealers` DISABLE KEYS */;
/*!40000 ALTER TABLE `dealers` ENABLE KEYS */;

-- Dumping structure for table wbs.dealersHeadings
CREATE TABLE IF NOT EXISTS `dealersHeadings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dealerId` int(11) NOT NULL,
  `headingId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relationship` (`dealerId`,`headingId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table wbs.dealersHeadings: ~0 rows (approximately)
/*!40000 ALTER TABLE `dealersHeadings` DISABLE KEYS */;
/*!40000 ALTER TABLE `dealersHeadings` ENABLE KEYS */;

-- Dumping structure for table wbs.headings
CREATE TABLE IF NOT EXISTS `headings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='Рубрики';

-- Dumping data for table wbs.headings: ~6 rows (approximately)
/*!40000 ALTER TABLE `headings` DISABLE KEYS */;
INSERT INTO `headings` (`id`, `parentId`, `title`, `createdAt`, `updatedAt`) VALUES
	(1, 0, 'WHEELS', '2016-10-29 10:55:27', '2016-10-29 10:55:27'),
	(2, 0, 'AERODYNAMICS', '2016-10-29 10:55:27', '2016-10-29 10:55:27'),
	(3, 0, 'EXHAUST', '2016-10-29 10:55:27', '2016-10-29 10:55:27'),
	(4, 0, 'BRAKES', '2016-10-29 10:55:27', '2016-10-29 10:55:27'),
	(5, 0, 'FORCING', '2016-10-29 10:55:27', '2016-10-29 10:55:27'),
	(6, 0, 'ACCESORIES', '2016-10-29 10:55:27', '2016-10-29 10:55:27');
/*!40000 ALTER TABLE `headings` ENABLE KEYS */;

-- Dumping structure for table wbs.images
CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(140) NOT NULL,
  `name` varchar(140) NOT NULL,
  `hash` char(64) NOT NULL,
  `extension` varchar(7) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table wbs.images: ~0 rows (approximately)
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
/*!40000 ALTER TABLE `images` ENABLE KEYS */;

-- Dumping structure for table wbs.menus
CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentId` int(11) NOT NULL DEFAULT '0',
  `sortId` int(11) NOT NULL DEFAULT '99',
  `title` varchar(50) NOT NULL,
  `icon` varchar(50) NOT NULL DEFAULT 'fa-th-large',
  `processor` varchar(50) DEFAULT NULL,
  `action` varchar(50) NOT NULL DEFAULT 'default',
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- Dumping data for table wbs.menus: ~12 rows (approximately)
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` (`id`, `parentId`, `sortId`, `title`, `icon`, `processor`, `action`, `createdAt`, `updatedAt`) VALUES
	(1, 0, 0, 'Dashboard', 'fa-dashboard', 'dashboard', 'default', '2016-10-28 18:00:23', '2016-10-29 22:44:59'),
	(2, 0, 99, 'Settings', 'fa-cogs', 'settings', 'default', '2016-10-28 18:00:23', '2016-10-28 18:40:51'),
	(3, 0, 2, 'Section of Wheels', 'fa-car', 'wheel', 'default', '2016-10-28 18:00:23', '2016-10-29 11:10:52'),
	(4, 3, 0, 'Bolt Pattern', 'fa-car', 'wheel', 'bolt-pattern', '2016-10-28 18:00:23', '2016-10-29 17:08:42'),
	(5, 3, 1, 'Styles', 'fa-car', 'wheel', 'styles', '2016-10-28 18:00:23', '2016-10-29 10:54:01'),
	(6, 3, 2, 'Wheel', 'fa-car', 'wheel', 'default', '2016-10-28 18:00:23', '2016-10-29 11:30:37'),
	(7, 0, 1, 'Catalogue', 'fa-database', 'catalogue', 'default', '2016-10-28 18:00:23', '2016-10-31 10:54:18'),
	(8, 7, 0, 'Heading', 'fa-car', 'heading', 'default', '2016-10-28 18:00:23', '2016-10-31 17:10:51'),
	(9, 7, 1, 'Brand', 'fa-car', 'brand', 'default', '2016-10-28 18:00:23', '2016-10-31 17:10:53'),
	(10, 7, 2, 'Dealer', 'fa-car', 'dealer', 'default', '2016-10-28 18:00:23', '2016-10-31 17:10:55'),
	(11, 0, 98, 'Roles and Permissions', 'fa-shield', 'role', 'default', '2016-10-31 12:34:53', '2016-10-31 17:11:07'),
	(12, 0, 97, 'User', 'fa-user', 'user', 'default', '2016-11-01 20:16:02', '2016-11-01 20:16:29');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;

-- Dumping structure for table wbs.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table wbs.permissions: ~38 rows (approximately)
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` (`id`, `title`, `name`, `createdAt`, `updatedAt`) VALUES
	(1, 'Доступ к административной панели', 'cp', '2016-10-25 06:25:31', '2016-10-28 18:58:41'),
	(2, 'Раздел Дисков', 'cp.wheel', '2016-10-29 09:02:34', '2016-10-29 09:25:53'),
	(3, 'Добавить Диск', 'cp.wheel.add', '2016-10-29 09:03:00', '2016-10-29 09:25:53'),
	(4, 'Редактировать Диск', 'cp.wheel.edit', '2016-10-29 09:03:00', '2016-10-29 09:25:53'),
	(5, 'Удалить Диск', 'cp.wheel.delete', '2016-10-29 09:03:00', '2016-10-29 09:25:53'),
	(6, 'Раздел Брендов', 'cp.brand', '2016-10-29 09:04:13', '2016-10-29 09:25:53'),
	(7, 'Добавить Бренд', 'cp.brand.add', '2016-10-29 09:04:31', '2016-10-29 09:25:53'),
	(8, 'Редактировать Бренд', 'cp.brand.edit', '2016-10-29 09:04:50', '2016-10-29 09:25:53'),
	(9, 'Удалить Бренд', 'cp.brand.delete', '2016-10-29 09:04:50', '2016-10-29 09:25:53'),
	(10, 'Раздел Пользователи', 'cp.user', '2016-10-29 09:05:44', '2016-10-29 09:25:53'),
	(11, 'Добавить Пользователя', 'cp.user.add', '2016-10-29 09:06:18', '2016-10-29 09:25:53'),
	(12, 'Редактировать Пользователя', 'cp.user.edit', '2016-10-29 09:06:18', '2016-10-31 11:51:36'),
	(13, 'Удалить Пользователя', 'cp.user.delete', '2016-10-29 09:06:18', '2016-10-31 11:51:36'),
	(14, 'Раздел Ролей', 'cp.role', '2016-10-29 09:11:08', '2016-10-31 11:51:36'),
	(15, 'Добавить Роль', 'cp.role.add', '2016-10-29 09:11:25', '2016-10-31 11:51:36'),
	(16, 'Редактировать Роль', 'cp.role.edit', '2016-10-29 09:11:25', '2016-10-31 11:51:36'),
	(17, 'Удалить Роль', 'cp.role.delete', '2016-10-29 09:11:25', '2016-10-31 11:51:36'),
	(18, 'Раздел Полномочий', 'cp.permission', '2016-10-29 09:11:25', '2016-10-31 11:51:36'),
	(19, 'Добавить Полномочие', 'cp.permission.add', '2016-10-29 09:11:25', '2016-10-31 11:51:36'),
	(20, 'Редактировать Полномочие', 'cp.permission.edit', '2016-10-29 09:11:25', '2016-10-31 11:51:36'),
	(21, 'Удалить Полномочие', 'cp.permission.delete', '2016-10-29 09:11:25', '2016-10-31 11:51:36'),
	(22, 'Раздел Дилеров', 'cp.dealer', '2016-10-29 09:04:50', '2016-10-31 12:05:39'),
	(23, 'Добавить Дилера', 'cp.dealer.add', '2016-10-29 09:04:50', '2016-10-31 12:05:50'),
	(24, 'Редактировать Дилера', 'cp.dealer.edit', '2016-10-29 09:04:50', '2016-10-31 12:05:59'),
	(25, 'Удалить Дилера', 'cp.dealer.delete', '2016-10-29 09:04:50', '2016-10-31 12:06:08'),
	(26, 'Раздел Рубрик', 'cp.heading', '2016-10-29 11:22:57', '2016-10-31 11:51:36'),
	(27, 'Добавить Рубрику', 'cp.heading.add', '2016-10-29 11:22:57', '2016-10-31 11:51:36'),
	(28, 'Редактировать Рубрику', 'cp.heading.edit', '2016-10-29 11:22:57', '2016-10-31 11:51:36'),
	(29, 'Удалить Рубрику', 'cp.heading.delete', '2016-10-29 11:22:57', '2016-10-31 11:51:36'),
	(30, 'Загрузка Изображений', 'cp.upload.image', '2016-10-29 17:17:03', '2016-10-31 11:51:36'),
	(31, 'Загрузка Видео', 'cp.upload.video', '2016-10-29 17:17:03', '2016-10-31 11:51:36'),
	(32, 'Доступ к каталогу', 'cp.catalogue', '2016-10-31 10:55:52', '2016-10-31 11:51:36'),
	(33, 'Попросить удаление рубрики', 'cp.heading.pull-request', '2016-11-02 22:33:50', '2016-11-02 22:33:50'),
	(34, 'Просмотр Dashboard', 'cp.dashboard', '2016-10-31 12:54:10', '2016-10-31 12:54:10'),
	(35, 'Попросить удаление диска', 'cp.wheel.pull-request', '2016-11-02 22:33:50', '2016-11-02 22:33:50'),
	(36, 'Попросить удаление бренда', 'cp.brand.pull-request', '2016-11-02 22:33:50', '2016-11-02 22:35:40'),
	(37, 'Попросить удаление дилера', 'cp.dealer.pull-request', '2016-11-02 22:33:50', '2016-11-02 22:35:43'),
	(38, 'Попросить удаление пользователя', 'cp.user.pull-request', '2016-11-02 22:33:50', '2016-11-02 22:33:50');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;

-- Dumping structure for table wbs.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `left` int(11) DEFAULT NULL,
  `right` int(11) DEFAULT NULL,
  `depth` int(11) DEFAULT NULL,
  `rootId` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table wbs.roles: ~10 rows (approximately)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `title`, `left`, `right`, `depth`, `rootId`, `createdAt`, `updatedAt`) VALUES
	(1, 'Administrator', 1, 20, 0, 1, '2016-10-07 21:03:24', '2016-10-29 11:19:02'),
	(2, 'User', 2, 5, 1, 1, '2016-10-18 19:49:02', '2016-10-29 11:13:01'),
	(3, 'Register', 3, 4, 2, 1, '2016-10-18 19:49:11', '2016-10-29 11:13:24'),
	(4, 'Chief Manager', 6, 19, 1, 1, '2016-10-18 19:49:11', '2016-10-29 11:19:02'),
	(5, 'Manager Catalogue', 11, 18, 2, 1, '2016-10-18 19:49:11', '2016-10-29 11:19:02'),
	(6, 'Manager Brand', 12, 13, 3, 1, '2016-10-18 19:49:11', '2016-10-29 11:14:55'),
	(7, 'Manager Dealer', 14, 15, 3, 1, '2016-10-18 19:49:11', '2016-10-29 11:15:48'),
	(8, 'Manager Wheel', 7, 8, 2, 1, '2016-10-18 19:49:11', '2016-10-29 11:15:53'),
	(9, 'Manager User', 9, 10, 2, 1, '2016-10-29 08:50:14', '2016-10-29 11:15:59'),
	(10, 'Manager Heading', 16, 17, 3, 1, '2016-10-29 11:16:17', '2016-10-29 11:19:02');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Dumping structure for table wbs.rolesPermissions
CREATE TABLE IF NOT EXISTS `rolesPermissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleId` int(11) NOT NULL,
  `permissionId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permission` (`roleId`,`permissionId`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table wbs.rolesPermissions: ~49 rows (approximately)
/*!40000 ALTER TABLE `rolesPermissions` DISABLE KEYS */;
INSERT INTO `rolesPermissions` (`id`, `roleId`, `permissionId`) VALUES
	(1, 1, 14),
	(2, 1, 15),
	(3, 1, 16),
	(4, 1, 17),
	(5, 1, 18),
	(6, 1, 19),
	(7, 1, 20),
	(8, 1, 21),
	(27, 4, 5),
	(33, 4, 13),
	(13, 5, 9),
	(20, 5, 25),
	(37, 5, 29),
	(9, 6, 1),
	(10, 6, 6),
	(11, 6, 7),
	(12, 6, 8),
	(14, 6, 30),
	(15, 6, 32),
	(40, 6, 34),
	(47, 6, 36),
	(16, 7, 1),
	(17, 7, 22),
	(18, 7, 23),
	(19, 7, 24),
	(21, 7, 30),
	(22, 7, 32),
	(41, 7, 34),
	(48, 7, 37),
	(23, 8, 1),
	(24, 8, 2),
	(25, 8, 3),
	(26, 8, 4),
	(28, 8, 30),
	(42, 8, 34),
	(46, 8, 35),
	(29, 9, 1),
	(30, 9, 10),
	(31, 9, 11),
	(32, 9, 12),
	(43, 9, 34),
	(45, 9, 38),
	(34, 10, 26),
	(35, 10, 27),
	(36, 10, 28),
	(38, 10, 30),
	(39, 10, 32),
	(49, 10, 33),
	(44, 10, 34);
/*!40000 ALTER TABLE `rolesPermissions` ENABLE KEYS */;

-- Dumping structure for table wbs.tokens
CREATE TABLE IF NOT EXISTS `tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `series` varchar(50) NOT NULL,
  `userId` int(11) NOT NULL,
  `challenge` varchar(50) NOT NULL,
  `expires` bigint(20) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table wbs.tokens: ~0 rows (approximately)
/*!40000 ALTER TABLE `tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `tokens` ENABLE KEYS */;

-- Dumping structure for table wbs.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `googleId` varchar(255) DEFAULT NULL,
  `facebookId` varchar(255) DEFAULT NULL,
  `instagramId` varchar(255) DEFAULT NULL,
  `githubId` varchar(255) DEFAULT NULL,
  `vkId` varchar(255) DEFAULT NULL,
  `twitterId` varchar(255) DEFAULT NULL,
  `dropboxId` varchar(255) DEFAULT NULL,
  `login` varchar(40) DEFAULT NULL,
  `lastname` varchar(40) DEFAULT NULL,
  `name` varchar(40) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `passwordHash` varchar(255) DEFAULT NULL,
  `roleId` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table wbs.users: ~1 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `googleId`, `facebookId`, `instagramId`, `githubId`, `vkId`, `twitterId`, `dropboxId`, `login`, `lastname`, `name`, `email`, `passwordHash`, `roleId`, `createdAt`, `updatedAt`) VALUES
	(1, NULL, NULL, NULL, '5111255', '77525486', NULL, NULL, 'rez1dent3', 'Бабичев', 'Максим', 'maksim.babichev95@gmail.com', '$2y$10$5PN0fk6ih8MbIxgb9Yhu0ORWKn7srgXBfAK4xxGBeU4ELv0SiABbC', 1, '2016-10-06 10:45:45', '2016-11-01 21:30:30');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
