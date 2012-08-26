CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(40) NOT NULL UNIQUE,
  `fio` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` tinyint(3) unsigned NOT NULL,
  `permissions` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` int(11) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `users` 
  (`user_id`, `login`, `fio`, `email`, `password`, `role`, `permissions`, `created`, `last_login`, `status`) 
VALUES
  (NULL, 'admin', 'Superadmin', 'sergii.lapin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 1, '', '2012-03-25 20:49:20', 0, 1);
