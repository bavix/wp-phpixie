ALTER TABLE `videos`
  ADD `provider` VARCHAR(60) NULL AFTER `url`,
  ADD `title` VARCHAR(255) NULL AFTER `provider`,
  ADD `description` VARCHAR(500) NULL AFTER `title`,
  ADD `image` VARCHAR(255) NULL AFTER `description`,
  ADD `imageWidth` INT NULL AFTER `image`,
  ADD `imageHeight` INT NULL AFTER `imageWidth`,
  ADD `width` INT NULL AFTER `imageHeight`,
  ADD `height` INT NULL AFTER `width`,
  ADD `aspectRatio` FLOAT NULL AFTER `height`,
  ADD `authorName` VARCHAR(255) NULL AFTER `aspectRatio`,
  ADD `authorUrl` VARCHAR(255) NULL AFTER `authorName`;