INSERT INTO `permissions` (`title`, `name`) VALUES ('Доступ к рубрикам брендов', 'cp.soc.brandheading');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (6, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Добавить рубрику к бренду', 'cp.soc.brandheading.add');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (6, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Редактирование рубрики брендов', 'cp.soc.brandheading.edit');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (6, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Удалить рубрику из бренда', 'cp.soc.brandheading.delete');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (5, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Запросить удаление рубрики из бренда', 'cp.soc.brandheading.pull-request');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (6, last_insert_id());

ALTER TABLE `brandsSocials`
	DROP INDEX `relationship`,
	ADD UNIQUE INDEX `relationship` (`brandId`, `headingId`, `url`);