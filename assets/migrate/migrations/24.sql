ALTER TABLE `boltPatterns`
	ALTER `bolt` DROP DEFAULT,
	ALTER `pcd` DROP DEFAULT;

ALTER TABLE `boltPatterns`
	CHANGE COLUMN `bolt` `bolt` FLOAT NOT NULL COMMENT 'Stud / Bolt' AFTER `id`,
	CHANGE COLUMN `pcd` `pcd` FLOAT NOT NULL COMMENT 'Pitch Circle Diameter' AFTER `bolt`;
