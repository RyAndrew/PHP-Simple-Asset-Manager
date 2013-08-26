SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE `assets` (
  `asset_id` int(11) NOT NULL auto_increment,
  `asset_name` varchar(30) NOT NULL,
  `type_id` int(11) default NULL,
  `asset_location` varchar(30) default NULL,
  `asset_status` varchar(30) NOT NULL,
  `tags_printed` int(11) NOT NULL default '0',
  PRIMARY KEY  (`asset_id`)
) ENGINE=InnoDB;

CREATE TABLE `asset_attributes` (
  `asset_attribute_id` int(11) NOT NULL auto_increment,
  `asset_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `attribute_value` varchar(30) default '',
  PRIMARY KEY  (`asset_attribute_id`)
) ENGINE=InnoDB;

CREATE TABLE `asset_links` (
  `asset_link_id` int(11) NOT NULL auto_increment,
  `asset_id` int(11) NOT NULL,
  `asset_linked_to` int(11) NOT NULL,
  `link_note` varchar(30) NOT NULL,
  PRIMARY KEY  (`asset_link_id`)
) ENGINE=InnoDB;

CREATE TABLE `asset_modules` (
  `asset_module_id` int(11) NOT NULL auto_increment,
  `asset_module_name` varchar(30) NOT NULL,
  `asset_module_location` varchar(30) NOT NULL,
  PRIMARY KEY  (`asset_module_id`)
) ENGINE=InnoDB;

CREATE TABLE `asset_notes` (
  `note_id` int(11) NOT NULL auto_increment,
  `note_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `note_type` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY  (`note_id`)
) ENGINE=InnoDB;

CREATE TABLE `asset_types` (
  `type_id` int(11) NOT NULL auto_increment,
  `type_name` varchar(30) NOT NULL,
  PRIMARY KEY  (`type_id`)
) ENGINE=InnoDB;

CREATE TABLE `asset_type_attributes` (
  `asset_type_attribute_id` int(11) NOT NULL auto_increment,
  `type_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  PRIMARY KEY  (`asset_type_attribute_id`)
) ENGINE=InnoDB;

CREATE TABLE `asset_type_modules` (
  `asset_type_module_id` int(11) NOT NULL auto_increment,
  `asset_type_id` int(11) NOT NULL,
  `asset_module_id` int(11) NOT NULL,
  PRIMARY KEY  (`asset_type_module_id`)
) ENGINE=InnoDB;

CREATE TABLE `attributes` (
  `attribute_id` int(11) NOT NULL auto_increment,
  `attribute_name` varchar(30) NOT NULL,
  PRIMARY KEY  (`attribute_id`)
) ENGINE=InnoDB;

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL default '0',
  `ip_address` varchar(45) NOT NULL default '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL default '0',
  `user_data` text NOT NULL,
  PRIMARY KEY  (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB;

CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL auto_increment,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `user_first_name` varchar(30) default NULL,
  `user_last_name` varchar(30) default NULL,
  `user_email` varchar(30) default NULL,
  `user_ip` varchar(30) NOT NULL,
  `class` varchar(30) default NULL,
  `method` varchar(30) default NULL,
  `user_id` int(11) default NULL,
  `asset_id` int(11) default NULL,
  `asset_name` varchar(30) default NULL,
  `attribute_id` int(11) default NULL,
  `attribute_name` varchar(30) default NULL,
  `type_id` int(11) default NULL,
  `type_name` varchar(30) default NULL,
  `data_name` varchar(30) default NULL,
  `data_from` varchar(50) default NULL,
  `data_to` varchar(50) default NULL,
  `description` text,
  PRIMARY KEY  (`log_id`)
) ENGINE=InnoDB;

CREATE TABLE `note_types` (
  `note_type_id` int(11) NOT NULL auto_increment,
  `note_type` varchar(30) NOT NULL,
  PRIMARY KEY  (`note_type_id`)
) ENGINE=InnoDB;

CREATE TABLE `users` (
  `user_id` tinyint(4) NOT NULL auto_increment,
  `user_email` varchar(30) NOT NULL,
  `user_first_name` varchar(30) NOT NULL,
  `user_last_name` varchar(30) NOT NULL,
  `user_password` varchar(150) NOT NULL,
  `user_salt` varchar(8) NOT NULL,
  `is_admin` tinyint(1) NOT NULL default '0',
  `is_disabled` tinyint(1) NOT NULL default '0',
  `user_theme` varchar(30) default NULL,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM;
