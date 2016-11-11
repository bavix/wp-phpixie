CREATE TABLE IF NOT EXISTS `brands` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `parentId`   INT(11)      NOT NULL DEFAULT '0',
  `title`      VARCHAR(50)  NOT NULL,
  `webGroup`   VARCHAR(255) NOT NULL,
  `isCarbon`   TINYINT(1)   NOT NULL DEFAULT '0'
  COMMENT 'карбон',
  `isOffroad`  TINYINT(1)   NOT NULL DEFAULT '0'
  COMMENT 'вредорожник',
  `isMultiple` TINYINT(1)   NOT NULL DEFAULT '0'
  COMMENT 'мультибренд',
  `active`     TINYINT(1)   NOT NULL DEFAULT '1'
  COMMENT 'активность',
  `createdAt`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `brandsDealers` (
  `id`       INT(11) NOT NULL AUTO_INCREMENT,
  `brandId`  INT(11) NOT NULL,
  `dealerId` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relationship` (`brandId`, `dealerId`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `brandsHeadings` (
  `id`        INT(11) NOT NULL AUTO_INCREMENT,
  `brandId`   INT(11) NOT NULL,
  `headingId` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relationship` (`brandId`, `headingId`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `dealers` (
  `id`       INT(11) NOT NULL AUTO_INCREMENT,
  `parentId` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `dealersHeadings` (
  `id`        INT(11) NOT NULL AUTO_INCREMENT,
  `dealerId`  INT(11) NOT NULL,
  `headingId` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `relationship` (`dealerId`, `headingId`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `headings` (
  `id`        INT(11)     NOT NULL AUTO_INCREMENT,
  `parentId`  INT(11)     NOT NULL DEFAULT '0',
  `title`     VARCHAR(50) NOT NULL,
  `createdAt` TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COMMENT = 'Рубрики';

CREATE TABLE IF NOT EXISTS `images` (
  `id`        INT(11)      NOT NULL AUTO_INCREMENT,
  `title`     VARCHAR(140) NOT NULL,
  `name`      VARCHAR(140) NOT NULL,
  `hash`      CHAR(64)     NOT NULL,
  `extension` VARCHAR(7)   NOT NULL,
  `createdAt` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `invites` (
  `id`        INT(11)      NOT NULL AUTO_INCREMENT,
  `token`     CHAR(64)     NOT NULL,
  `email`     VARCHAR(255) NOT NULL,
  `userId`    INT(11)      NOT NULL,
  `roleId`    INT(11)      NOT NULL,
  `active`    TINYINT(1)   NOT NULL DEFAULT '1',
  `createdAt` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `logs` (
  `id`        INT(11)                                 NOT NULL AUTO_INCREMENT,
  `model`     VARCHAR(255)                            NOT NULL,
  `modelId`   INT(11)                                 NOT NULL,
  `method`    ENUM ('POST', 'PUT', 'PATCH', 'DELETE') NOT NULL,
  `userId`    INT(11)                                 NOT NULL,
  `content`   TEXT                                    NOT NULL,
  `createdAt` TIMESTAMP                               NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `menus` (
  `id`        INT(11)     NOT NULL AUTO_INCREMENT,
  `parentId`  INT(11)     NOT NULL DEFAULT '0',
  `sortId`    INT(11)     NOT NULL DEFAULT '99',
  `title`     VARCHAR(50) NOT NULL,
  `icon`      VARCHAR(50) NOT NULL DEFAULT 'fa-th-large',
  `httpPath`  VARCHAR(50)          DEFAULT NULL,
  `createdAt` TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `permissions` (
  `id`        INT(11)     NOT NULL AUTO_INCREMENT,
  `title`     VARCHAR(50) NOT NULL,
  `name`      VARCHAR(50) NOT NULL,
  `createdAt` TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `roles` (
  `id`        INT(11)     NOT NULL AUTO_INCREMENT,
  `title`     VARCHAR(50) NOT NULL,
  `left`      INT(11)              DEFAULT NULL,
  `right`     INT(11)              DEFAULT NULL,
  `depth`     INT(11)              DEFAULT NULL,
  `rootId`    INT(11)              DEFAULT NULL,
  `createdAt` TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `rolesPermissions` (
  `id`           INT(11) NOT NULL AUTO_INCREMENT,
  `roleId`       INT(11) NOT NULL,
  `permissionId` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permission` (`roleId`, `permissionId`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `tokens` (
  `id`        INT(11)     NOT NULL AUTO_INCREMENT,
  `series`    VARCHAR(50) NOT NULL,
  `userId`    INT(11)     NOT NULL,
  `challenge` VARCHAR(50) NOT NULL,
  `expires`   BIGINT(20)  NOT NULL,
  `createdAt` TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `users` (
  `id`           INT(11)   NOT NULL AUTO_INCREMENT,
  `googleId`     VARCHAR(255)       DEFAULT NULL,
  `facebookId`   VARCHAR(255)       DEFAULT NULL,
  `instagramId`  VARCHAR(255)       DEFAULT NULL,
  `githubId`     VARCHAR(255)       DEFAULT NULL,
  `vkId`         VARCHAR(255)       DEFAULT NULL,
  `twitterId`    VARCHAR(255)       DEFAULT NULL,
  `dropboxId`    VARCHAR(255)       DEFAULT NULL,
  `login`        VARCHAR(40)        DEFAULT NULL,
  `lastname`     VARCHAR(40)        DEFAULT NULL,
  `name`         VARCHAR(40)        DEFAULT NULL,
  `email`        VARCHAR(255)       DEFAULT NULL,
  `passwordHash` VARCHAR(255)       DEFAULT NULL,
  `roleId`       INT(11)   NOT NULL,
  `createdAt`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;