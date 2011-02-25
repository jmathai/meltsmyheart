ALTER TABLE  `user` CHANGE  `u_dateCreated`  `u_dateCreated` INT( 11 ) UNSIGNED NOT NULL;
CREATE TABLE  `meltsmyheart`.`user_token` (
  `ut_u_id` INT UNSIGNED NOT NULL ,
  `ut_token` VARCHAR( 255 ) NOT NULL ,
  `ut_device` VARCHAR( 255 ) NOT NULL ,
  `ut_dateCreated` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (  `ut_u_id` ,  `ut_token` )
  ) ENGINE = INNODB;

