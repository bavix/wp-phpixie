INSERT INTO `permissions` (`title`, `name`) VALUES (
  'Смена пароля пользователям',
   'sou.user.change-password'
  );
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (1, last_insert_id());
