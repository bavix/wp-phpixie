ALTER TABLE `videos` ADD `identifier` VARCHAR(50) NULL AFTER `id`;
ALTER TABLE `wheelsLikes` ADD UNIQUE `unique_index` ( `wheelId`, `userId`);
ALTER TABLE `wheelsFavourites` ADD UNIQUE `unique_index` ( `wheelId`, `userId`);
ALTER TABLE `wheelsImages` ADD UNIQUE `unique_index` ( `wheelId`, `imageId`);
ALTER TABLE `wheelsComments` ADD UNIQUE `unique_index` ( `wheelId`, `commentId`);
ALTER TABLE `wheelsVideos` ADD UNIQUE `unique_index` ( `wheelId`, `videoId`);