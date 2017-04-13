CREATE TABLE `apps` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`userId` INT NOT NULL,
	`name` VARCHAR(100) NOT NULL,
	`active` TINYINT(1) NOT NULL DEFAULT '1',
	`createdAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updatedAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

ALTER TABLE `oauth_clients`
	ALTER `user_id` DROP DEFAULT;

ALTER TABLE `oauth_clients`
	ALTER `redirect_uri` DROP DEFAULT;

ALTER TABLE `oauth_clients`
	CHANGE COLUMN `redirect_uri` `redirect_uri` VARCHAR(2000) NULL AFTER `client_secret`;

ALTER TABLE `oauth_clients`
	CHANGE COLUMN `user_id` `appId` INT NOT NULL AFTER `scope`;

ALTER TABLE `apps`
	ADD UNIQUE INDEX `name` (`name`);