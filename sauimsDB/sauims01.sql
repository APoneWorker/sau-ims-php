/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : sauims

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2016-10-31 22:01:30
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for activity_check
-- ----------------------------
DROP TABLE IF EXISTS `activity_check`;
CREATE TABLE `activity_check` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(60) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `state` tinyint(4) NOT NULL DEFAULT '0',
  `admin_id` int(11) NOT NULL,
  `respond` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for club_log
-- ----------------------------
DROP TABLE IF EXISTS `club_log`;
CREATE TABLE `club_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(60) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `title` varchar(60) NOT NULL,
  `admin_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for club_register
-- ----------------------------
DROP TABLE IF EXISTS `club_register`;
CREATE TABLE `club_register` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(60) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `state` tinyint(4) NOT NULL DEFAULT '0',
  `admin_id` int(11) NOT NULL,
  `respond` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sau_notice
-- ----------------------------
DROP TABLE IF EXISTS `sau_notice`;
CREATE TABLE `sau_notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `title` varchar(60) NOT NULL,
  `right` int(11) NOT NULL DEFAULT '0',
  `club_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for year_check
-- ----------------------------
DROP TABLE IF EXISTS `year_check`;
CREATE TABLE `year_check` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(60) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `state` tinyint(4) NOT NULL DEFAULT '0',
  `admin_id` int(11) NOT NULL,
  `respond` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
