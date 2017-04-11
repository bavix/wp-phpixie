ALTER TABLE `images` ADD `itemId` INT NULL AFTER `userId`;

ALTER TABLE `wheels` CHANGE `previewWheelId` `imageId` INT(11) NULL DEFAULT NULL;
ALTER TABLE `dealers` CHANGE `dealerLogoId` `imageId` INT(11) NULL DEFAULT NULL;
ALTER TABLE `brands` CHANGE `brandLogoId` `imageId` INT(11) NULL DEFAULT NULL;