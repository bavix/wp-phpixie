ALTER TABLE `wheels`
	ALTER `collectionId` DROP DEFAULT;

ALTER TABLE `wheels`
	CHANGE COLUMN `collectionId` `collectionId` INT(11) NULL AFTER `brandId`;