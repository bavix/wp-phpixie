CREATE TABLE `addresses` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `country` VARCHAR(255) NULL ,
  `zipCode` VARCHAR(255) NULL ,
  `state` VARCHAR(255) NULL ,
  `city` VARCHAR(255) NULL ,
  `street` VARCHAR(255) NULL ,
  `streetNumber` VARCHAR(255) NULL ,
  `latitude` FLOAT NOT NULL ,
  `longitude` FLOAT NOT NULL ,
  `userId` INT NOT NULL ,
  `createdAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `updatedAt` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `brandsAddresses` (
  `id` int(11) NOT NULL,
  `brandId` int(11) NOT NULL,
  `addressId` int(11) NOT NULL
) ENGINE=InnoDB;

ALTER TABLE `brandsAddresses`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `brandsAddresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;