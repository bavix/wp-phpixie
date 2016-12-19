ALTER TABLE `dealers`
	ADD COLUMN `name` VARCHAR(50) NOT NULL AFTER `parentId`,
	ADD COLUMN `createdAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `name`,
	ADD COLUMN `updatedAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `createdAt`;

INSERT INTO `permissions` (`title`, `name`) VALUES ('Доступ к дилерам брендов', 'cp.soc.branddealer');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (6, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Добавить дилеру к бренду', 'cp.soc.branddealer.add');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (6, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Редактирование дилера брендов', 'cp.soc.branddealer.edit');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (6, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Удалить дилеру из бренда', 'cp.soc.branddealer.delete');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (5, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Запросить удаление дилера из бренда', 'cp.soc.branddealer.pull-request');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (6, last_insert_id());
