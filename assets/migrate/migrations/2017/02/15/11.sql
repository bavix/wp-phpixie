ALTER TABLE `brandsDealers`
	ADD INDEX `brandId` (`brandId`),
	ADD INDEX `dealerId` (`dealerId`);

ALTER TABLE `brandsHeadings`
	ADD INDEX `brandId` (`brandId`),
	ADD INDEX `headingId` (`headingId`);

ALTER TABLE `dealersHeadings`
	ADD INDEX `dealerId` (`dealerId`),
	ADD INDEX `headingId` (`headingId`);

ALTER TABLE `rolesPermissions`
	ADD INDEX `roleId` (`roleId`),
	ADD INDEX `permissionId` (`permissionId`);