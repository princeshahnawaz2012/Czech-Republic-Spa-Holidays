CREATE TABLE IF NOT EXISTS `en_currencies` (
  `currency_id` mediumint(9) NOT NULL,
  `title` varchar(1025) NOT NULL,
  PRIMARY KEY (`currency_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ru_currencies` (
  `currency_id` mediumint(9) NOT NULL,
  `title` varchar(1025) NOT NULL,
  PRIMARY KEY (`currency_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `de_currencies` (
  `currency_id` mediumint(9) NOT NULL,
  `title` varchar(1025) NOT NULL,
  PRIMARY KEY (`currency_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cs_currencies` (
  `currency_id` mediumint(9) NOT NULL,
  `title` varchar(1025) NOT NULL,
  PRIMARY KEY (`currency_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
