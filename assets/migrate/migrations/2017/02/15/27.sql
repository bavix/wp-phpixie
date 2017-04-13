ALTER TABLE `images`
	DROP COLUMN `name`,
	DROP COLUMN `extension`;

ALTER TABLE `images`
	ALTER `hash` DROP DEFAULT;

ALTER TABLE `images`
	CHANGE COLUMN `hash` `hash` CHAR(6) NOT NULL AFTER `title`;