ALTER TABLE `invites`
	CHANGE COLUMN `active` `activated` TINYINT(1) NOT NULL DEFAULT '0' AFTER `roleId`;

ALTER TABLE `invites`
  ADD INDEX `token` (`token`);

ALTER TABLE `users`
  CHANGE COLUMN `login` `login` VARCHAR(16) NULL DEFAULT NULL AFTER `dropboxId`;