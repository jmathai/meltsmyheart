ALTER TABLE `child` ADD `c_t_name` VARCHAR( 255 ) NOT NULL DEFAULT 'default',
ADD `c_isActive` BOOLEAN NOT NULL DEFAULT '1';

CREATE TABLE `meltsmyheart`.`theme` (
  `t_name` VARCHAR( 255 ) NOT NULL ,
  `t_settings` VARCHAR( 255 ) NOT NULL DEFAULT '{}',
  `t_isActive` BOOLEAN NOT NULL DEFAULT '1',
  PRIMARY KEY ( `t_name` )
  ) ENGINE = InnoDB;


INSERT INTO `meltsmyheart`.`theme` ( `t_name` , `t_settings` , `t_isActive`)
  VALUES ( 'default', '{}', '1');
