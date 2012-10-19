RENAME TABLE  `transfer_stations` TO  `stations` ;
RENAME TABLE  `cs_transfer_stations` TO  `cs_stations` ;
RENAME TABLE  `en_transfer_stations` TO  `en_stations` ;
RENAME TABLE  `de_transfer_stations` TO  `de_stations` ;
RENAME TABLE  `ru_transfer_stations` TO  `ru_stations` ;
ALTER TABLE   `stations` ADD  `com_order` MEDIUMINT( 9 ) NOT NULL AFTER  `com_station_id`;
