-- Dumping structure for table oauth_access_tokens
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `access_token` VARCHAR(40) NOT NULL,
  `client_id`    VARCHAR(80) NOT NULL,
  `user_id`      VARCHAR(255)  DEFAULT NULL,
  `expires`      TIMESTAMP   NOT NULL,
  `scope`        VARCHAR(2000) DEFAULT NULL,
  PRIMARY KEY (`access_token`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Dumping structure for table oauth_authorization_codes
CREATE TABLE IF NOT EXISTS `oauth_authorization_codes` (
  `authorization_code` VARCHAR(40) NOT NULL,
  `client_id`          VARCHAR(80) NOT NULL,
  `user_id`            VARCHAR(255)  DEFAULT NULL,
  `redirect_uri`       VARCHAR(2000) DEFAULT NULL,
  `expires`            TIMESTAMP   NOT NULL,
  `scope`              VARCHAR(2000) DEFAULT NULL,
  PRIMARY KEY (`authorization_code`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Dumping structure for table oauth_clients
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `client_id`     VARCHAR(80)   NOT NULL,
  `client_secret` VARCHAR(80)  DEFAULT NULL,
  `redirect_uri`  VARCHAR(2000) NOT NULL,
  `grant_types`   VARCHAR(80)  DEFAULT NULL,
  `scope`         VARCHAR(100) DEFAULT NULL,
  `user_id`       VARCHAR(80)  DEFAULT NULL,
  PRIMARY KEY (`client_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Dumping structure for table oauth_jwt
CREATE TABLE IF NOT EXISTS `oauth_jwt` (
  `client_id`  VARCHAR(80) NOT NULL,
  `subject`    VARCHAR(80)   DEFAULT NULL,
  `public_key` VARCHAR(2000) DEFAULT NULL,
  PRIMARY KEY (`client_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

-- Dumping structure for table oauth_refresh_tokens
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `refresh_token` VARCHAR(40) NOT NULL,
  `client_id`     VARCHAR(80) NOT NULL,
  `user_id`       VARCHAR(255)  DEFAULT NULL,
  `expires`       TIMESTAMP   NOT NULL,
  `scope`         VARCHAR(2000) DEFAULT NULL,
  PRIMARY KEY (`refresh_token`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

-- Dumping structure for table oauth_scopes
CREATE TABLE IF NOT EXISTS `oauth_scopes` (
  `scope`      TEXT,
  `is_default` TINYINT(1) DEFAULT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;