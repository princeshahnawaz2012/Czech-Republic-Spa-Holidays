CREATE TABLE IF NOT EXISTS  `articles` (
	`article_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`hits` INT NOT NULL,
	`active` BOOLEAN NOT NULL DEFAULT '1',
	`time` INT NOT NULL, `order` INT NOT NULL
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS  `en_articles` (
	`article_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`hits` INT NOT NULL DEFAULT '0',
	`time` INT NOT NULL ,
	`author_id` INT NOT NULL ,
	`title` TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
	`full` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
	`keywords` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	`description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	`edited_now` BOOLEAN NOT NULL DEFAULT '0',
	`editor_id` INT NULL DEFAULT NULL,
	`seo_link` TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;
