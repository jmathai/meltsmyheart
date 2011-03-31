CREATE TABLE IF NOT EXISTS `recipient` (
  `r_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `r_u_id` int(10) unsigned NOT NULL,
  `r_name` varchar(255) NOT NULL,
  `r_email` varchar(255) NOT NULL,
  `r_mobile` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`r_id`),
  UNIQUE KEY `user_email` (`r_u_id`,`r_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `email_hash` (
  `eh_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `eh_hash` varchar(255) NOT NULL,
  `eh_email` varchar(255) NOT NULL,
  `eh_itemId` int(10) unsigned NOT NULL,
  `eh_itemType` enum('photo') NOT NULL,
  PRIMARY KEY (`eh_id`),
  UNIQUE KEY `eh_hash` (`eh_hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `comment_email` (
  `ce_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ce_to` varchar(255) NOT NULL,
  `ce_from` varchar(255) NOT NULL,
  `ce_subject` varchar(255) NOT NULL,
  `ce_body` text NOT NULL,
  `ce_bodyParsed` text,
  `ce_date` varchar(255) NOT NULL,
  `ce_status` enum('new','processed','error') NOT NULL DEFAULT 'new',
  PRIMARY KEY (`ce_id`),
  KEY `ce_status` (`ce_status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE `badge` (
  `b_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `b_c_id` int(10) unsigned NOT NULL,
  `b_bm_id` smallint(5) unsigned NOT NULL,
  `b_dateCreated` int(11) NOT NULL,
  PRIMARY KEY (`b_id`),
  UNIQUE KEY `b_c_id` (`b_c_id`,`b_bm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `badge_meta` (
  `bm_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `bm_tag` varchar(255) NOT NULL,
  `bm_name` varchar(255) NOT NULL,
  `bm_icon` varchar(255) NOT NULL,
  PRIMARY KEY (`bm_id`),
  UNIQUE KEY `bm_tag` (`bm_tag`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `badge_meta` (`bm_id`, `bm_tag`, `bm_name`, `bm_icon`) VALUES
(1, 'imnew', 'I''m New', 'im-new.png'),
(2, 'talker', 'Talker', 'talker.png'),
(3, 'Android', 'android', 'android.png'),
(4, 'apple', 'Apple', 'apple.png');

