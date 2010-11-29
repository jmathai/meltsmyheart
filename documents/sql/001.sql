CREATE TABLE IF NOT EXISTS `user` (
  `u_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `u_key` char(32) NOT NULL,
  `u_email` varchar(255) NOT NULL,
  `u_password` varchar(255) NOT NULL,
  `u_prefs` text NOT NULL,
  `u_accountType` enum('free','paid') NOT NULL,
  `u_dateCreated` int(11) NOT NULL,
  PRIMARY KEY (`u_id`),
  UNIQUE KEY `u_email` (`u_email`,`u_password`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `credential` (
  `c_u_id` int(10) unsigned NOT NULL,
  `c_service` enum('facebook','smugmug') NOT NULL,
  `c_token` varchar(255) NOT NULL,
  `c_secret` varchar(255) NOT NULL,
  PRIMARY KEY (`c_u_id`,`c_service`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
