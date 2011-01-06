SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `meltsmyheart`
--

-- --------------------------------------------------------

--
-- Table structure for table `child`
--

DROP TABLE IF EXISTS `child`;
CREATE TABLE IF NOT EXISTS `child` (
  `c_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `c_u_id` int(10) unsigned NOT NULL,
  `c_name` varchar(255) NOT NULL,
  `c_birthdate` bigint(20) unsigned NOT NULL,
  `c_domain` varchar(255) NOT NULL,
  PRIMARY KEY (`c_id`),
  UNIQUE KEY `c_domain` (`c_domain`),
  KEY `c_u_id` (`c_u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `credential`
--

DROP TABLE IF EXISTS `credential`;
CREATE TABLE IF NOT EXISTS `credential` (
  `c_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `c_u_id` int(10) unsigned NOT NULL,
  `c_service` enum('facebook','photagious','smugmug') NOT NULL,
  `c_token` varchar(255) NOT NULL,
  `c_secret` varchar(255) DEFAULT NULL,
  `c_uid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`c_id`),
  UNIQUE KEY `userToService` (`c_u_id`,`c_service`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `custom`
--

DROP TABLE IF EXISTS `custom`;
CREATE TABLE IF NOT EXISTS `custom` (
  `c_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `c_u_id` int(10) unsigned NOT NULL,
  `c_p_id` int(10) unsigned NOT NULL,
  `c_path` varchar(255) NOT NULL,
  `c_basePath` varchar(255) NOT NULL,
  `c_dateCreated` int(10) unsigned NOT NULL,
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

DROP TABLE IF EXISTS `photo`;
CREATE TABLE IF NOT EXISTS `photo` (
  `p_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `p_key` varchar(255) NOT NULL,
  `p_u_id` int(10) unsigned NOT NULL,
  `p_c_id` int(10) unsigned DEFAULT NULL,
  `p_thumbPath` varchar(255) DEFAULT NULL,
  `p_basePath` varchar(255) DEFAULT NULL,
  `p_originalPath` varchar(255) DEFAULT NULL,
  `p_caption` varchar(255) DEFAULT NULL,
  `p_exif` varchar(255) DEFAULT NULL,
  `p_meta` text,
  `p_use` tinyint(1) NOT NULL,
  `p_dateTaken` int(10) unsigned DEFAULT NULL,
  `p_dateCreated` int(10) unsigned NOT NULL,
  PRIMARY KEY (`p_id`),
  UNIQUE KEY `p_basePath` (`p_basePath`),
  UNIQUE KEY `key_child` (`p_key`,`p_c_id`),
  KEY `user_child` (`p_u_id`,`p_c_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

DROP TABLE IF EXISTS `token`;
CREATE TABLE IF NOT EXISTS `token` (
  `t_token` varchar(255) NOT NULL,
  UNIQUE KEY `t_token` (`t_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
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
