ALTER TABLE `videos` ADD `identifier` VARCHAR(50) NULL AFTER `id`;
ALTER TABLE `wheelsLikes` ADD UNIQUE( `wheelId`, `userId`);
ALTER TABLE `wheelsFavourites` ADD UNIQUE( `wheelId`, `userId`);
ALTER TABLE `wheelsImages` ADD UNIQUE( `wheelId`, `imageId`);
ALTER TABLE `wheelsComments` ADD UNIQUE( `wheelId`, `commentId`);
ALTER TABLE `wheelsVideos` ADD UNIQUE( `wheelId`, `videoId`);