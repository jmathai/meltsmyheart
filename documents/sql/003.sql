CREATE TABLE affiliate (
    a_id int(10) unsigned NOT NULL AUTO_INCREMENT,
    a_u_id int(10) unsigned NOT NULL,
    a_key varchar(255) NOT NULL,
    a_name varchar(255) NOT NULL,
    a_street varchar(255) NOT NULL,
    a_city varchar(255) NOT NULL,
    a_state varchar(255) NOT NULL,
    a_zip varchar(255) NOT NULL,
    a_dateCreated int(11) NOT NULL,
    a_isActive tinyint(1) NOT NULL DEFAULT '1',
    PRIMARY KEY (a_id),
    KEY a_u_id (a_u_id,a_key)
  ) ENGINE=InnoDB;

CREATE TABLE `affiliate_stat` (
  `as_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `as_a_id` INT UNSIGNED NOT NULL ,
  `as_userToken` VARCHAR( 255 ) NOT NULL ,
  `as_actions` SET( 'view', 'signup', 'upgrade' ) NOT NULL ,
  `as_dateCreated` INT UNSIGNED NOT NULL ,
  PRIMARY KEY ( `as_id` )
  ) ENGINE = InnoDB;

ALTER TABLE `affiliate_stat` ADD UNIQUE `affiliate_token` ( `as_a_id` , `as_userToken` ) ;
