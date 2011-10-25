-- phpMyAdmin SQL Dump
-- version 2.6.2-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jan 25, 2006 at 09:04 PM
-- Server version: 4.1.12
-- PHP Version: 5.0.4
-- 
-- Database: `burning-edge`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `flare_admin_privs`
-- 

CREATE TABLE IF NOT EXISTS `flare_admin_privs` (
  `user_id` int(11) NOT NULL default '0',
  `extension_id` int(11) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `flare_jobs`
-- 

CREATE TABLE IF NOT EXISTS `flare_jobs` (
  `job_id` int(11) NOT NULL auto_increment,
  `job` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`job_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `flare_config`
-- 

CREATE TABLE IF NOT EXISTS `flare_config` (
  `name` varchar(255) NOT NULL default '',
  `value` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `extension_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`name`,`extension_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `flare_extensions`
-- 

CREATE TABLE IF NOT EXISTS `flare_extensions` (
  `extension_id` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  `displayed_name` varchar(64) NOT NULL default '',
  `admin_displayed_name` varchar(64) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `user_min_level` varchar(5) NOT NULL default '100',
  `display_order` char(2) NOT NULL default '1',
  `enabled` enum('0','1') NOT NULL default '0',
  `visible` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`extension_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `flare_services`
-- 

CREATE TABLE IF NOT EXISTS `flare_services` (
  `user_id` int(11) NOT NULL default '0',
  `service_name` varchar(32) NOT NULL default '',
  `service_status` enum('0','1','2') NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`service_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `flare_tmp`
-- 

CREATE TABLE IF NOT EXISTS `flare_tmp` (
  `tmp_id` int(11) NOT NULL auto_increment,
  `data` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`tmp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

INSERT IGNORE INTO `flare_config` VALUES ('default_extension', 'Accounts', 'Default Extension', 0);
INSERT IGNORE INTO `flare_config` VALUES ('use_debug', '0', 'System Debugging', 0);
INSERT IGNORE INTO `flare_config` VALUES ('update', '0', 'Determine whether an upgrade is available', 0);
INSERT IGNORE INTO `flare_config` VALUES ('version', '1.0 (nenshou)', 'Flare version', 0);
INSERT IGNORE INTO `flare_config` VALUES ('update_interval', '604800', 'Time between checking for updates', 0);
INSERT IGNORE INTO `flare_config` VALUES ('last_update_check', '1115440301', 'Time last check for update was run', 0);
INSERT IGNORE INTO `flare_config` VALUES ('use_strict', '1', 'Use strict config checking', 0);
