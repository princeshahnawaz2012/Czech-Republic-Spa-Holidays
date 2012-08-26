CREATE TABLE IF NOT EXISTS `ru_categories` (
  `category_id` mediumint(9) NOT NULL,
  `title` varchar(1024) NOT NULL,
  `short_desc` text NOT NULL,
  `desc` text NOT NULL,
  `seo_link` varchar(1024) NOT NULL,
  `metakeywords` text NOT NULL,
  `metadescription` text NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ru_cities` (
  `city_id` mediumint(9) NOT NULL,
  `title` varchar(66) NOT NULL,
  `desc` text NOT NULL,
  `flag_label` varchar(66) NOT NULL,
  `emblem_label` varchar(66) NOT NULL,
  PRIMARY KEY (`city_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ru_countries` (
  `country_id` mediumint(9) NOT NULL,
  `title` varchar(1025) NOT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ru_essential_infos` (
  `essential_info_id` mediumint(9) NOT NULL,
  `title` varchar(1024) NOT NULL,
  `short_desc` text NOT NULL,
  PRIMARY KEY (`essential_info_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ru_facilities` (
  `facility_id` mediumint(9) NOT NULL,
  `title` varchar(255) NOT NULL,
  `short_desc` text NOT NULL,
  PRIMARY KEY (`facility_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ru_illneses` (
  `illnese_id` mediumint(9) NOT NULL,
  `title` varchar(1024) NOT NULL,
  `short_desc` text NOT NULL,
  PRIMARY KEY (`illnese_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ru_medical_treatments` (
  `medical_treatment_id` mediumint(9) NOT NULL,
  `title` varchar(256) NOT NULL,
  `short_desc` text NOT NULL,
  PRIMARY KEY (`medical_treatment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ru_programmes` (
  `programme_id` int(11) NOT NULL,
  `title` varchar(1024) NOT NULL,
  `description` text NOT NULL,
  `included` text NOT NULL,
  `notincluded` text NOT NULL,
  `terms` text NOT NULL,
  `seo_link` tinytext NOT NULL,
  `metakeywords` text NOT NULL,
  `metadescription` text NOT NULL,
  PRIMARY KEY (`programme_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ru_programmes_images` (
  `programme_image_id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  PRIMARY KEY (`programme_image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ru_regions` (
  `region_id` mediumint(9) NOT NULL,
  `title` varchar(1024) NOT NULL,
  PRIMARY KEY (`region_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ru_rooms` (
  `room_id` mediumint(9) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`room_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ru_room_types` (
  `room_type_id` mediumint(9) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`room_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ru_spas` (
  `spa_id` mediumint(9) NOT NULL,
  `title` varchar(1024) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`spa_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ru_transfer_stations` (
  `station_id` mediumint(9) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`station_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
