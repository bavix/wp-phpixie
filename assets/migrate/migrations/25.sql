ALTER TABLE `styles`
	ALTER `type` DROP DEFAULT;

ALTER TABLE `styles`
	CHANGE COLUMN `type` `type` ENUM('I','X','Y','V','O') NOT NULL COMMENT 'тип стиля' AFTER `parentId`;