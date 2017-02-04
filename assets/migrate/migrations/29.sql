INSERT INTO `menus`
  (`parentId`, `sortId`, `title`, `httpPath`)
  VALUES (3, 4, 'Collection', 'cp.sow.collection');

-- просмотр
INSERT INTO `permissions`
  (`title`, `name`, `createdAt`, `updatedAt`)
  VALUES ('Раздел Коллекций Дисков', 'cp.sow.collection', NOW(), NOW());
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (8, last_insert_id());

-- запись
INSERT INTO `permissions`
  (`title`, `name`, `createdAt`, `updatedAt`)
  VALUES ('Добавить Коллекцию Дисков', 'cp.sow.collection.add', NOW(), NOW());
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (8, last_insert_id());

-- редактирование
INSERT INTO `permissions`
  (`title`, `name`, `createdAt`, `updatedAt`)
  VALUES ('Редактировать Коллекцию Дисков', 'cp.sow.collection.edit', NOW(), NOW());
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (8, last_insert_id());

-- удалить
INSERT INTO `permissions`
  (`title`, `name`, `createdAt`, `updatedAt`)
  VALUES ('Удалить Коллекцию Дисков', 'cp.sow.collection.delete', NOW(), NOW());
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (4, last_insert_id());

-- удалить
INSERT INTO `permissions`
  (`title`, `name`, `createdAt`, `updatedAt`)
  VALUES ('Запросить удаление Коллекции Дисков', 'cp.sow.collection.pull-request', NOW(), NOW());
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (8, last_insert_id());


-- upload image
ALTER TABLE `images`
	ADD COLUMN `userId` INT NOT NULL AFTER `hash`;

-- add new permission
INSERT INTO `permissions`
  (`title`, `name`, `createdAt`, `updatedAt`)
  VALUES ('Загрузка файлов', 'cp.upload', NOW(), NOW());

-- remove upload.image for heading role
delete from rolesPermissions
where roleId = 10 and (
  permissionId = (select id from permissions where name='cp.upload.image')
);

-- for brand/dealer/wheel roles
insert INTO `rolesPermissions`
(`roleId`, `permissionId`)
VALUES (6, (select id from permissions where name='cp.upload'));

insert INTO `rolesPermissions`
(`roleId`, `permissionId`)
VALUES (7, (select id from permissions where name='cp.upload'));

insert INTO `rolesPermissions`
(`roleId`, `permissionId`)
VALUES (8, (select id from permissions where name='cp.upload'));

-- video upload
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (8, 31);