ALTER TABLE `logs`
	CHANGE COLUMN `content` `message` TEXT NOT NULL AFTER `userId`;