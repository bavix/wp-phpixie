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

-- version 2


# update wheels

select count(1) c from wheelsLikes group by wheelId order by c desc limit 1;
select avg(cc.data) from (select avg(1) data from wheelsLikes group by wheelId) cc;

ALTER TABLE `wheels` ADD `favouriteCount` INT NOT NULL DEFAULT '0' AFTER `name`;

CREATE TRIGGER `wheelFavouriteCountDelete`
AFTER DELETE ON `wheelsFavourites`
FOR EACH ROW UPDATE `wheels`
SET `favouriteCount` = IF(`favouriteCount` = 0, 0, `favouriteCount` - 1) WHERE `id` = OLD.`wheelId`;

CREATE TRIGGER `wheelFavouriteCountInsert`
BEFORE INSERT ON `wheelsFavourites`
FOR EACH ROW UPDATE `wheels`
SET `favouriteCount` = `favouriteCount` + 1 WHERE `id` = NEW.`wheelId`;

update wheels as w
set popular = (
         ( -- favourite
           w.favouriteCount  / (select count(1) c from wheelsFavourites group by wheelId order by c desc limit 1) * ( 1.618 )
         ) +
         ( -- likes
           w.likeCount  / (select count(1) c from wheelsLikes group by wheelId order by c desc limit 1) * ( 0.809 )
         ) +
         ( -- comments
           LEAST(w.commentCount, (select avg(cc.data) from (select avg(1) data from wheelsLikes group by wheelId) cc))
           / (select count(1) c from wheelsComments group by wheelId order by c desc limit 1) * ( 0.20225 )
         )
       ) ;

# ALTER TABLE `brands` ADD `popular` FLOAT NOT NULL DEFAULT '-1' AFTER `webGroup`;
# ALTER TABLE `dealers` ADD `popular` FLOAT NOT NULL DEFAULT '-1' AFTER `web`;