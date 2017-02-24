-- comments

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `text` varchar(300) NOT NULL,
  `userId` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `wheelsComments` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `wheelId` INT NOT NULL ,
  `commentId` INT NOT NULL , PRIMARY KEY (`id`)
) ENGINE = InnoDB;

-- videos

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `url` varchar(300) NOT NULL,
  `userId` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `wheelsVideos` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `wheelId` INT NOT NULL ,
  `videoId` INT NOT NULL , PRIMARY KEY (`id`)
) ENGINE = InnoDB;

-- likes
CREATE TABLE `wheelsLikes` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `wheelId` INT NOT NULL ,
  `userId` INT NOT NULL ,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

-- favorites
CREATE TABLE `wheelsFavorites` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `wheelId` INT NOT NULL ,
  `userId` INT NOT NULL ,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

-- count like, comments
ALTER TABLE `wheels`
  ADD `likeCount` INT NOT NULL DEFAULT '0' AFTER `name`,
  ADD `commentCount` INT NOT NULL DEFAULT '0' AFTER `likeCount`;

-- images
CREATE TABLE `wheelsImages` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `wheelId` INT NOT NULL ,
  `imageId` INT NOT NULL , PRIMARY KEY (`id`)
) ENGINE = InnoDB;

-- general image
CREATE TABLE `previewWheels` (
  `id` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `hash` char(6) NOT NULL,
  `wheelId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `previewWheels`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `previewWheels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

ALTER TABLE `wheels` ADD `previewWheelId` INT NOT NULL AFTER `collectionId`;
ALTER TABLE `wheels` CHANGE `previewWheelId` `previewWheelId` INT(11) NULL;
UPDATE `wheels` SET `previewWheelId`=null where previewWheelId=0;

ALTER TABLE `previewWheels` CHANGE `wheelId` `wheelId` INT(11) NULL DEFAULT NULL;