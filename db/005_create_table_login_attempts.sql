CREATE TABLE IF NOT EXISTS `login_attempts` (
  `login_attempt_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(255) NOT NULL,
  `ip_address` varchar(16) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `time_active` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`login_attempt_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;