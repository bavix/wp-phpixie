ALTER TABLE `brands`
	CHANGE COLUMN `isOffroad` `isOffRoad` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'вредорожник' AFTER `isCarbon`;