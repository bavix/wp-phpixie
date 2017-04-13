CREATE TABLE IF NOT EXISTS `socials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `url` varchar(50) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `socials` (`id`, `title`, `url`, `createdAt`, `updatedAt`) VALUES
	(1, 'Facebook', 'facebook.com', '2016-12-01 13:47:01', '2016-12-01 13:51:20'),
	(2, 'Instagram', 'instagram.com', '2016-12-01 13:48:51', '2016-12-01 13:51:28'),
	(3, 'Twitter', 'twitter.com', '2016-12-01 13:48:58', '2016-12-01 13:51:35'),
	(4, 'YouTube', 'youtube.com', '2016-12-01 13:49:05', '2016-12-01 13:51:39'),
	(5, 'Tumblr', 'tumblr.com', '2016-12-01 13:49:13', '2016-12-01 13:51:47'),
	(6, 'Vimeo', 'vimeo.com', '2016-12-01 13:49:19', '2016-12-01 13:52:00'),
	(7, 'Flickr', 'www.flickr.com', '2016-12-01 13:49:24', '2016-12-01 13:52:32'),
	(8, 'Pinterest', 'pinterest.com', '2016-12-01 13:49:30', '2016-12-01 13:52:56'),
	(9, 'Google Plus', 'plus.google.com', '2016-12-01 13:49:38', '2016-12-01 13:53:17'),
	(10, 'LinkedIn', 'linkedin.com', '2016-12-01 13:49:48', '2016-12-01 13:53:29'),
	(11, 'imgur', 'imgur.com', '2016-12-01 13:49:58', '2016-12-01 13:53:47'),
	(12, 'YouKu', 'youku.com', '2016-12-01 13:55:02', '2016-12-01 13:55:02'),
	(13, 'Weibo', 'weibo.com', '2016-12-01 13:55:23', '2016-12-01 13:55:23');