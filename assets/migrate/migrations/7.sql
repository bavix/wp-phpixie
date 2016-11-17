ALTER TABLE `logs`
  ALTER `type` DROP DEFAULT;

ALTER TABLE `logs`
  CHANGE COLUMN `type` `type` ENUM ('created', 'updated', 'deleted') NOT NULL
  AFTER `userId`;

ALTER TABLE `brands`
  ALTER `title` DROP DEFAULT;

ALTER TABLE `brands`
  CHANGE COLUMN `title` `name` VARCHAR(50) NOT NULL
  AFTER `parentId`;