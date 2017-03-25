INSERT INTO `permissions` (`title`, `name`) VALUES (
  'Смена пароля пользователям',
   'sou.user.change-password'
  );
insert INTO `rolesPermissions` (`roleId`, `permissionId`) VALUES (1, last_insert_id());

-- popular
-- ALTER TABLE `wheels` ADD `popular` FLOAT NOT NULL DEFAULT '-1' AFTER `commentCount`;

-- SELECT id, `brandId`, `name`, if( likeCount + commentCount > 1 , if (likeCount>commentCount, commentCount/likeCount, likeCount/commentCount), -1) popular FROM `wheels` order by popular desc, likeCount desc, commentCount desc;

UPDATE `wheels` SET popular= if(
  likeCount + commentCount > 50,
  if (
    likeCount > commentCount,
    commentCount / likeCount,
    likeCount / commentCount
    ),
  -1
);