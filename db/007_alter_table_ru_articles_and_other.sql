ALTER TABLE  `ru_articles` ADD  `editing_end` INT NULL DEFAULT NULL AFTER  `editor_id`;

ALTER TABLE  `en_articles` ADD  `editing_end` INT NULL DEFAULT NULL AFTER  `editor_id`;

ALTER TABLE  `de_articles` ADD  `editing_end` INT NULL DEFAULT NULL AFTER  `editor_id`;

ALTER TABLE  `cs_articles` ADD  `editing_end` INT NULL DEFAULT NULL AFTER  `editor_id`;

ALTER TABLE `ru_articles` DROP `edited_now`;

ALTER TABLE `en_articles` DROP `edited_now`;

ALTER TABLE `de_articles` DROP `edited_now`;

ALTER TABLE `cs_articles` DROP `edited_now`;
