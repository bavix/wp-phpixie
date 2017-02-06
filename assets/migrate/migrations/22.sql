CREATE TABLE `wheels` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`brandId` INT(11) NOT NULL,
	`name` INT(11) NOT NULL,
	`isCustom` TINYINT(1) NOT NULL DEFAULT '0',
	`isRetired` TINYINT(1) NOT NULL DEFAULT '0',
	`active` TINYINT(1) NOT NULL DEFAULT '1',
	`createdAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updatedAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;