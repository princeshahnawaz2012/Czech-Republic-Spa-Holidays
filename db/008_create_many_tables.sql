CREATE TABLE IF NOT EXISTS `categories` (
  `com_category_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `com_active` tinyint(1) NOT NULL,
  `com_picture_ext` varchar(3) DEFAULT NULL,
  `com_complex_treatments` tinyint(1) NOT NULL,
  PRIMARY KEY (`com_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `cities` (
  `com_city_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `com_flag_ext` varchar(3) DEFAULT NULL,
  `com_emblem_ext` varchar(3) DEFAULT NULL,
  `com_map_ext` varchar(3) DEFAULT NULL,
  `com_region_id` mediumint(9) NOT NULL,
  PRIMARY KEY (`com_city_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `countries` (
  `com_country_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `com_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`com_country_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `currencies` (
  `com_currency_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `com_abbr` varchar(3) NOT NULL,
  PRIMARY KEY (`com_currency_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `currencies_exchange` (
  `com_currency_from_id` mediumint(9) NOT NULL,
  `com_currency_to_id` mediumint(9) NOT NULL,
  `com_exchange` float NOT NULL,
  PRIMARY KEY (`com_currency_from_id`,`com_currency_to_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `essential_infos` (
  `com_essential_info_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`com_essential_info_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `facilities` (
  `com_facility_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`com_facility_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `illneses` (
  `com_illnese` mediumint(9) NOT NULL AUTO_INCREMENT,
  `com_order` mediumint(9) NOT NULL,
  `com_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`com_illnese`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `medical_treatments` (
  `com_medical_treatment_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`com_medical_treatment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `programmes` (
  `com_programme_id` int(11) NOT NULL AUTO_INCREMENT,
  `com_active` tinyint(1) NOT NULL,
  `com_category_id` mediumint(9) NOT NULL,
  PRIMARY KEY (`com_programme_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `programmes_illneses` (
  `com_programme_id` int(11) NOT NULL,
  `com_illnese_id` mediumint(9) NOT NULL,
  PRIMARY KEY (`com_programme_id`,`com_illnese_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `programmes_images` (
  `com_programme_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `com_image_ext` varchar(3) NOT NULL,
  `com_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`com_programme_image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `regions` (
  `com_region_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `com_country_id` mediumint(9) NOT NULL,
  `com_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`com_region_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rooms` (
  `com_room_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `com_active` tinyint(1) NOT NULL,
  `com_capacity` tinyint(1) NOT NULL,
  `com_num_infants` tinyint(1) NOT NULL,
  PRIMARY KEY (`com_room_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `room_types` (
  `com_room_type_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `com_order` mediumint(9) NOT NULL,
  `com_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`com_room_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `seasons` (
  `com_season_id` int(11) NOT NULL AUTO_INCREMENT,
  `com_date_from` date NOT NULL,
  `com_date_till` date NOT NULL,
  `com_title` varchar(255) NOT NULL,
  PRIMARY KEY (`com_season_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `seasons_prices` (
  `com_season_price_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `com_programme_id` int(11) NOT NULL,
  `com_spa_id` mediumint(9) NOT NULL,
  `com_season_id` int(11) NOT NULL,
  `com_supplier_id` mediumint(9) NOT NULL,
  `com_calc_type` tinyint(1) NOT NULL,
  `com_calc_percent` float NOT NULL,
  `com_room_id` mediumint(9) NOT NULL,
  `com_programme_type` tinyint(1) NOT NULL,
  `com_start_days` tinyint(1) NOT NULL,
  `com_num_min_dur_days` smallint(6) NOT NULL,
  `com_num_add_days` smallint(6) NOT NULL,
  `com_supplement_ids` mediumint(9) NOT NULL,
  `com_transfer_supplier_id` mediumint(9) NOT NULL,
  `com_price_per_day` float NOT NULL,
  `com_price_per_week` float NOT NULL,
  `com_discount_3_week` float NOT NULL,
  `com_discount_type` smallint(6) NOT NULL,
  `com_room_type_id` mediumint(9) NOT NULL,
  PRIMARY KEY (`com_season_price_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `spas` (
  `com_spa_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `com_active` tinyint(1) NOT NULL,
  `com_city_id` mediumint(9) NOT NULL,
  `com_contacts` text NOT NULL,
  `com_reservation_email` varchar(512) NOT NULL,
  `com_reservation_name` varchar(256) NOT NULL,
  `com_reservation_email2` varchar(512) DEFAULT NULL,
  `com_reservation_name2` varchar(256) DEFAULT NULL,
  `com_midseason_pay_type` tinyint(1) NOT NULL,
  PRIMARY KEY (`com_spa_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `spas_essential_infos` (
  `com_spa_id` mediumint(9) NOT NULL,
  `com_essential_info_id` mediumint(9) NOT NULL,
  PRIMARY KEY (`com_spa_id`,`com_essential_info_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `spas_facilities` (
  `com_spa_id` mediumint(9) NOT NULL,
  `com_facility_id` mediumint(9) NOT NULL,
  PRIMARY KEY (`com_spa_id`,`com_facility_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `spas_medical_treatments` (
  `com_spa_id` mediumint(9) NOT NULL,
  `com_medical_treatment_id` mediumint(9) NOT NULL,
  PRIMARY KEY (`com_spa_id`,`com_medical_treatment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `spas_rooms` (
  `com_spa_id` mediumint(9) NOT NULL,
  `com_room_id` mediumint(9) NOT NULL,
  `com_age_from` smallint(6) NOT NULL,
  `com_age_to` smallint(6) NOT NULL,
  `com_discount` float NOT NULL,
  PRIMARY KEY (`com_spa_id`,`com_room_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `spas_rooms_avalibilities` (
  `com_spa_id` mediumint(9) NOT NULL,
  `com_room_id` mediumint(9) NOT NULL,
  `com_date_from` date NOT NULL,
  `com_date_till` date NOT NULL,
  PRIMARY KEY (`com_spa_id`,`com_room_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `supplements` (
  `com_supplement_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `com_title` varchar(255) NOT NULL,
  `com_date_from` date NOT NULL,
  `com_date_till` date NOT NULL,
  `com_price` float NOT NULL,
  `com_currency_id` mediumint(9) NOT NULL,
  PRIMARY KEY (`com_supplement_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `suppliers` (
  `com_supplier_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `com_title` varchar(1024) NOT NULL,
  `com_office_contacts` text NOT NULL,
  `com_bank_details` text NOT NULL,
  `com_accounts_contact` varchar(256) NOT NULL,
  `com_accounts_email` varchar(512) NOT NULL,
  `com_contact_currency_id` mediumint(9) NOT NULL,
  `com_transfers_calc_type` tinyint(1) NOT NULL,
  `com_transfers_percent` float NOT NULL,
  PRIMARY KEY (`com_supplier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `transfers` (
  `com_spa_id` mediumint(9) NOT NULL,
  `com_supplier_id` mediumint(9) NOT NULL,
  `com_station_id` mediumint(9) NOT NULL,
  `com_to_hotel_price` float NOT NULL,
  `com_from_hotel_price` float NOT NULL,
  `com_both_price` float NOT NULL,
  `com_currency_id` mediumint(9) NOT NULL,
  PRIMARY KEY (`com_spa_id`,`com_supplier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `transfer_stations` (
  `com_station_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `com_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`com_station_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
