ALTER TABLE  `categories` ADD  `com_order` MEDIUMINT NOT NULL;

ALTER TABLE  `cities` ADD  `com_order` MEDIUMINT NOT NULL;

ALTER TABLE  `countries` ADD  `com_order` MEDIUMINT NOT NULL;

ALTER TABLE  `regions` ADD  `com_order` MEDIUMINT NOT NULL;

ALTER TABLE  `spas` ADD  `com_order` MEDIUMINT NOT NULL;

ALTER TABLE  `suppliers` ADD  `com_order` MEDIUMINT NOT NULL;

ALTER TABLE  `programmes` ADD  `com_order` INT NOT NULL;

ALTER TABLE  `programmes_images` ADD  `com_order` INT NOT NULL;
