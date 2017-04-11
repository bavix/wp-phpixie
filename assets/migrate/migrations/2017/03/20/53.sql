CREATE TABLE `dealersSocials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dealerId` int(11) NOT NULL,
  `socialId` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relationship` (`dealerId`,`socialId`,`url`),
  KEY `dealerId` (`dealerId`),
  KEY `socialId` (`socialId`),
  CONSTRAINT `dealerssocials_ibfk_1` FOREIGN KEY (`dealerId`) REFERENCES `dealers` (`id`),
  CONSTRAINT `dealerssocials_ibfk_2` FOREIGN KEY (`socialId`) REFERENCES `socials` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `dealers` CHANGE `parentId` `parentId` INT(11) NULL;
UPDATE `dealers` SET `parentId`=NULL;

ALTER TABLE `dealers` ADD `web` VARCHAR(255) NULL AFTER `name`;



-- [brand - columns (webGroup, isCarbon, isOffRoad, isMultiple)] === dealer