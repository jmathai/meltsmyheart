CREATE TABLE IF NOT EXISTS `child` (
  `c_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `c_u_id` int(10) unsigned NOT NULL,
  `c_name` varchar(255) NOT NULL,
  `c_birthdate` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`c_id`),
  KEY `c_u_id` (`c_u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `credential` (
  `c_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `c_u_id` int(10) unsigned NOT NULL,
  `c_service` enum('facebook','smugmug') NOT NULL,
  `c_token` varchar(255) NOT NULL,
  `c_secret` varchar(255) DEFAULT NULL,
  `c_uid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`c_id`),
  UNIQUE KEY `userToService` (`c_u_id`,`c_service`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `photo` (
  `p_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `p_u_id` int(10) unsigned NOT NULL,
  `p_key` varchar(255) NOT NULL,
  `p_meta` text NOT NULL,
  `p_dateCreated` int(10) unsigned NOT NULL,
  PRIMARY KEY (`p_id`),
  UNIQUE KEY `userId_key` (`p_u_id`,`p_key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
