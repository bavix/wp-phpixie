ALTER TABLE `images` CHANGE `title` `description` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE `images` ADD `size` INT NOT NULL AFTER `userId`, ADD `width` INT NOT NULL AFTER `size`, ADD `height` INT NOT NULL AFTER `width`;
ALTER TABLE `images` CHANGE `description` `description` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;

CREATE TABLE `brandLogos` (
  `id` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `hash` char(6) NOT NULL,
  `brandId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `brandLogos`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `brandLogos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

CREATE TABLE `dealerLogos` (
  `id` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `hash` char(6) NOT NULL,
  `dealerId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `dealerLogos`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `dealerLogos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

ALTER TABLE `brands` ADD `brandLogoId` INT NOT NULL AFTER `parentId`;
ALTER TABLE `dealers` ADD `dealerLogoId` INT NOT NULL AFTER `parentId`;

ALTER TABLE `brandLogos` CHANGE `brandId` `brandId` INT(11) NULL;
ALTER TABLE `dealerLogos` CHANGE `dealerId` `dealerId` INT(11) NULL;