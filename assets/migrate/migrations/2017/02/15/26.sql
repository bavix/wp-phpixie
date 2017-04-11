INSERT INTO `menus` (`parentId`, `sortId`, `title`, `httpPath`) VALUES (2, 1, 'API', 'cp.settings.api');
INSERT INTO `permissions` (`title`, `name`, `createdAt`, `updatedAt`) VALUES ('Раздел Настроек API', 'cp.settings.api', '2017-01-31 21:28:07', '2017-01-31 21:28:14');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (1, last_insert_id());

INSERT INTO `menus` (`parentId`, `sortId`, `title`, `httpPath`) VALUES (2, 2, 'Cache', 'cp.settings.cache');
INSERT INTO `permissions` (`title`, `name`, `createdAt`, `updatedAt`) VALUES ('Раздел Настроек Cache', 'cp.settings.cache', '2017-01-31 21:28:07', '2017-01-31 21:28:14');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (1, last_insert_id());

-- patch wheels
ALTER TABLE `wheels`
	ALTER `name` DROP DEFAULT;

ALTER TABLE `wheels`
	CHANGE COLUMN `name` `name` VARCHAR(100) NOT NULL AFTER `collectionId`;