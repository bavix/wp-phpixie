INSERT INTO `permissions` (`title`, `name`) VALUES ('Доступ к социалкам брендов', 'cp.soc.brandsocial');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (6, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Добавить социалку к бренду', 'cp.soc.brandsocial.add');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (6, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Редактирование социалок брендов', 'cp.soc.brandsocial.edit');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (6, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Удалить социалку из бренда', 'cp.soc.brandsocial.delete');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (5, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Запросить удаление социалки из бренда', 'cp.soc.brandsocial.pull-request');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (6, last_insert_id());

ALTER TABLE `brandsSocials`
	DROP INDEX `relationship`,
	ADD UNIQUE INDEX `relationship` (`brandId`, `socialId`, `url`);