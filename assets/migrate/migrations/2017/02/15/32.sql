ALTER TABLE `recoveryPasswords`
	ADD COLUMN `active` TINYINT(1) NOT NULL DEFAULT '1' AFTER `code`;

ALTER TABLE `recoveryPasswords`
	ADD COLUMN `try` TINYINT(1) NOT NULL DEFAULT '0' AFTER `active`;
