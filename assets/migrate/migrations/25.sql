CREATE TABLE `styles` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`parentId` INT NOT NULL DEFAULT '0',
	`type` TINYINT NOT NULL,
	`number` ENUM('Simple','Double','Triple') NOT NULL,
	`spoke` TINYINT NOT NULL,
	`isTurned` TINYINT(1) NOT NULL,
	`createdAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updatedAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

ALTER TABLE `styles`
	ALTER `type` DROP DEFAULT;

ALTER TABLE `styles`
	CHANGE COLUMN `type` `type` ENUM('I','X','Y','V','O') NOT NULL COMMENT 'тип стиля' AFTER `parentId`;

ALTER TABLE `styles`
	CHANGE COLUMN `isTurned` `isTurned` TINYINT(1) NOT NULL DEFAULT '0' AFTER `spoke`;