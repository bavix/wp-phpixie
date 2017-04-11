-- likes
CREATE TRIGGER `wheelLikeCountInsert` BEFORE INSERT ON `wheelsLikes`
  FOR EACH ROW UPDATE `wheels` SET `likeCount` = `likeCount` + 1 WHERE `id` = NEW.`wheelId`;

CREATE TRIGGER `wheelLikeCountDelete` AFTER DELETE ON `wheelsLikes`
  FOR EACH ROW UPDATE `wheels` SET `likeCount` = IF(`likeCount` = 0, 0, `likeCount` - 1) WHERE `id` = OLD.`wheelId`;

-- comments
CREATE TRIGGER `wheelCommentCountInsert` BEFORE INSERT ON `wheelsComments`
  FOR EACH ROW UPDATE `wheels` SET `commentCount` = `commentCount` + 1 WHERE `id` = NEW.`wheelId`;

CREATE TRIGGER `wheelCommentCountDelete` AFTER DELETE ON `wheelsComments`
  FOR EACH ROW UPDATE `wheels` SET `commentCount` = IF(`commentCount` = 0, 0, `commentCount` - 1) WHERE `id` = OLD.`wheelId`;