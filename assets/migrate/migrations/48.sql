ALTER TABLE `brands` CHANGE `parentId` `parentId` INT(11) NULL;
UPDATE `brands` SET `parentId` = NULL WHERE `parentId` = 0;