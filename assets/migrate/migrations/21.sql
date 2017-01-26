ALTER TABLE `boltPatterns`
	ALTER `stud` DROP DEFAULT;

ALTER TABLE `boltPatterns`
	CHANGE COLUMN `stud` `bolt` DECIMAL(4,2) NOT NULL COMMENT 'Stud / Bolt' AFTER `id`;