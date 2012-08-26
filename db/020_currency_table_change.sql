ALTER TABLE `currencies` DROP `com_abbr`;
ALTER TABLE  `currencies` CHANGE  `com_currency_id`  `com_currency_id` VARCHAR( 3 ) NOT NULL;

RENAME TABLE  `spas_rooms` TO  `spas_child_discounts` ;

CREATE TABLE IF NOT EXISTS `spas_rooms` (
  `com_spa_id` int(11) NOT NULL,
  `com_room_id` int(11) NOT NULL,
  `com_accomodation_price` float NOT NULL,
  `com_currency_id` varchar(3) NOT NULL,
  PRIMARY KEY (`com_spa_id`,`com_room_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE  `supplements` CHANGE  `com_currency_id`  `com_currency_id` VARCHAR( 3 ) NOT NULL;
ALTER TABLE  `transfers` CHANGE  `com_currency_id`  `com_currency_id` VARCHAR( 3 ) NOT NULL;

ALTER TABLE  `en_currencies` CHANGE  `currency_id`  `currency_id` VARCHAR( 3 ) NOT NULL;
ALTER TABLE  `cs_currencies` CHANGE  `currency_id`  `currency_id` VARCHAR( 3 ) NOT NULL;
ALTER TABLE  `de_currencies` CHANGE  `currency_id`  `currency_id` VARCHAR( 3 ) NOT NULL;
ALTER TABLE  `ru_currencies` CHANGE  `currency_id`  `currency_id` VARCHAR( 3 ) NOT NULL;
ALTER TABLE  `currencies_exchange` CHANGE  `com_currency_from_id`  `com_currency_from_id` VARCHAR( 3 ) NOT NULL ,
CHANGE  `com_currency_to_id`  `com_currency_to_id` VARCHAR( 3 ) NOT NULL;

ALTER TABLE `suppliers` DROP `com_order`;
ALTER TABLE  `suppliers` CHANGE  `com_contact_currency_id`  `com_contact_currency_id` VARCHAR( 3 ) NOT NULL;

ALTER TABLE  `illneses` CHANGE  `com_illnese`  `com_illnese_id` MEDIUMINT( 9 ) NOT NULL AUTO_INCREMENT;
