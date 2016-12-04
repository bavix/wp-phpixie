CREATE TABLE `brandsSocials` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`brandId` INT(11) NOT NULL,
	`socialId` INT(11) NOT NULL,
	`url` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `relationship` (`brandId`, `socialId`),
	INDEX `brandId` (`brandId`),
	INDEX `socialId` (`socialId`),
	CONSTRAINT `brandssocials_ibfk_1` FOREIGN KEY (`brandId`) REFERENCES `brands` (`id`),
	CONSTRAINT `brandssocials_ibfk_2` FOREIGN KEY (`socialId`) REFERENCES `socials` (`id`)
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
ROW_FORMAT=DYNAMIC;