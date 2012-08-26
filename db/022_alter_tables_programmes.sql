-- temp fields
ALTER TABLE  `programmes` ADD  `com_spa_id` MEDIUMINT( 9 ) NOT NULL ,
 ADD  `com_city_id` MEDIUMINT( 9 ) NOT NULL ,
ADD  `com_price_from` FLOAT NOT NULL ,
ADD  `com_currency_id` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
-- end of temp fields


ALTER TABLE  `cs_programmes` ADD  `short_desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE  `ru_programmes` ADD  `short_desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE  `en_programmes` ADD  `short_desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE  `de_programmes` ADD  `short_desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE  `programmes_images` ADD  `com_programme_id` INT( 11 ) NOT NULL AFTER  `com_programme_image_id`;

CREATE VIEW `programmes_images_count` AS
SELECT  `com_programme_id`, COUNT(*) AS `num_images` FROM `programmes_images`
GROUP BY `com_programme_id`;

ALTER TABLE  `essential_infos` ADD  `com_picture_ext` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
