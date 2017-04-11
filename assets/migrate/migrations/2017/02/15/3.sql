ALTER TABLE `invites`
	ADD COLUMN `expires` BIGINT(20) NOT NULL AFTER `active`;