INSERT INTO `permissions` (`title`, `name`) VALUES ('Доступ к социалкам диллеров', 'cp.soc.dealersocial');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (7, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Добавить социалку к диллеру', 'cp.soc.dealersocial.add');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (7, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Редактирование социалок диллеров', 'cp.soc.dealersocial.edit');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (7, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Удалить социалку из диллера', 'cp.soc.dealersocial.delete');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (5, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Запросить удаление социалки из диллера', 'cp.soc.dealersocial.pull-request');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (7, last_insert_id());

ALTER TABLE `dealersSocials`
  DROP INDEX `relationship`,
  ADD UNIQUE INDEX `relationship` (`dealerId`, `socialId`, `url`);


INSERT INTO `permissions` (`title`, `name`) VALUES ('Доступ к рубрикам диллеров', 'cp.soc.dealerheading');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (6, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Добавить рубрику к диллеру', 'cp.soc.dealerheading.add');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (6, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Редактирование рубрики диллеров', 'cp.soc.dealerheading.edit');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (6, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Удалить рубрику из диллера', 'cp.soc.dealerheading.delete');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (5, last_insert_id());

INSERT INTO `permissions` (`title`, `name`) VALUES ('Запросить удаление рубрики из диллера', 'cp.soc.dealerheading.pull-request');
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (6, last_insert_id());
