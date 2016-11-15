ALTER TABLE `invites`
	CHANGE COLUMN `token` `token` CHAR(8) NOT NULL AFTER `id`;