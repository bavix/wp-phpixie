ALTER TABLE `brands`
  ALTER `webGroup` DROP DEFAULT;

ALTER TABLE `brands`
  CHANGE COLUMN `webGroup` `webGroup` VARCHAR(255) NULL
  AFTER `title`,
  CHANGE COLUMN `isCarbon` `isCarbon` TINYINT(1) NOT NULL DEFAULT '0'
COMMENT 'карбон'
  AFTER `webGroup`,
  CHANGE COLUMN `isOffroad` `isOffroad` TINYINT(1) NOT NULL DEFAULT '0'
COMMENT 'вредорожник'
  AFTER `isCarbon`,
  CHANGE COLUMN `isMultiple` `isMultiple` TINYINT(1) NOT NULL DEFAULT '0'
COMMENT 'мультибренд'
  AFTER `isOffroad`,
  CHANGE COLUMN `active` `active` TINYINT(1) NOT NULL DEFAULT '1'
COMMENT 'активность'
  AFTER `isMultiple`;

ALTER TABLE `logs`
  ALTER `method` DROP DEFAULT,
  ALTER `userId` DROP DEFAULT;

ALTER TABLE `logs`
  CHANGE COLUMN `userId` `userId` INT(11) NOT NULL
  AFTER `modelId`,
  CHANGE COLUMN `method` `type` ENUM ('CREATE', 'UPDATE', 'DELETE') NOT NULL
  AFTER `userId`,
  CHANGE COLUMN `message` `data` TEXT NOT NULL
  AFTER `type`;

ALTER TABLE `logs`
  ALTER `userId` DROP DEFAULT;
ALTER TABLE `logs`
  CHANGE COLUMN `userId` `userId` INT(11) NULL AFTER `modelId`;