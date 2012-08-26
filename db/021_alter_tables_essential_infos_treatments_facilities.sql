ALTER TABLE  `essential_infos` ADD  `com_order` MEDIUMINT( 9 ) NOT NULL ,
ADD  `com_active` TINYINT( 1 ) NOT NULL;

ALTER TABLE  `medical_treatments` ADD  `com_order` MEDIUMINT( 9 ) NOT NULL ,
ADD  `com_active` TINYINT( 1 ) NOT NULL;

ALTER TABLE  `facilities` ADD  `com_order` MEDIUMINT( 9 ) NOT NULL ,
ADD  `com_active` TINYINT( 1 ) NOT NULL;
