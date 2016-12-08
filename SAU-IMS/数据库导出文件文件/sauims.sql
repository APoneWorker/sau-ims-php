/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : sauims

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2016-12-01 11:34:23
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
-- Records of activity_check
-- ----------------------------
INSERT INTO `activity_check` VALUES ('1', 'ee', '2016-10-03 21:46:23', '0', '3', 'e');

-- ----------------------------
-- Table structure for clubinfo
-- ----------------------------
DROP TABLE IF EXISTS `clubinfo`;
CREATE TABLE `clubinfo` (
  `club_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`club_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of clubinfo
-- ----------------------------
INSERT INTO `clubinfo` VALUES ('1', '校社联');
INSERT INTO `clubinfo` VALUES ('2', '出云音乐协会');

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
-- Records of club_log
-- ----------------------------

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
-- Records of club_register
-- ----------------------------

-- ----------------------------
-- Table structure for notice
-- ----------------------------
DROP TABLE IF EXISTS `notice`;
CREATE TABLE `notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `title` varchar(60) NOT NULL,
  `right` int(11) NOT NULL DEFAULT '0',
  `club_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of notice
-- ----------------------------
INSERT INTO `notice` VALUES ('3', '啊第三方反反复复反反复复反反复复吩咐', '2016-11-27 12:03:47', '厄尔', '0', '1');
INSERT INTO `notice` VALUES ('4', '搜索', '2016-11-27 12:03:51', '嗖嗖嗖', '0', '2');
INSERT INTO `notice` VALUES ('5', '鹅鹅鹅', '2016-11-27 12:03:48', '轻轻巧巧', '0', '1');
INSERT INTO `notice` VALUES ('6', '333', '2016-11-27 12:03:52', '二万人', '0', '1');
INSERT INTO `notice` VALUES ('7', '\r\n$admin = ModelFactory::factory(\"SauAdmin\");//获取管理员身份\r\n$a =$admin->noticeList();\r\nprint_r( $a );', '2016-11-27 12:03:53', '代码', '0', '1');
INSERT INTO `notice` VALUES ('8', '<?php\r\n\r\nrequire \"../framework/ModelFactory.php\";\r\n<?php\r\n\r\nrequire \"../framework/ModelFactory.php\";\r\n<?php\r\n\r\nrequire \"../framework/ModelFactory.php\";\r\n<?php\r\n\r\nrequire \"../framework/ModelFactory.php\";\r\n<?php\r\n\r\nrequire \"../framework/ModelFactory.php\";\r\n', '2016-11-27 12:03:53', '大妈', '0', '1');
INSERT INTO `notice` VALUES ('9', '22', '2016-11-27 12:03:55', '2', '0', '2');
INSERT INTO `notice` VALUES ('10', 'we', '2016-11-27 12:03:56', '2e', '0', '2');
INSERT INTO `notice` VALUES ('11', '23', '2016-11-27 12:03:58', '2', '0', '2');
INSERT INTO `notice` VALUES ('12', '33', '2016-11-27 12:04:01', '2', '0', '1');
INSERT INTO `notice` VALUES ('14', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('15', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('16', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('17', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('18', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('19', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('20', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('21', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('22', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('23', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('24', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('25', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('26', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('27', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('28', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('29', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('30', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('31', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('32', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('33', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('34', 'text', '2016-11-11 11:11:11', 'title', '0', '1');
INSERT INTO `notice` VALUES ('35', 'text', '2016-11-11 11:11:11', 'title', '0', '1');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `right` int(11) NOT NULL DEFAULT '0',
  `club_id` int(11) NOT NULL DEFAULT '0',
  `salt` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', '1', '28c8edde3d61a0411511d3b1866f0636', '2', '1', '1');
INSERT INTO `user` VALUES ('2', '2', '2', '1', '2', '1');

-- ----------------------------
-- Table structure for user_club
-- ----------------------------
DROP TABLE IF EXISTS `user_club`;
CREATE TABLE `user_club` (
  `user_id` int(11) NOT NULL,
  `club_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`club_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_club
-- ----------------------------
INSERT INTO `user_club` VALUES ('1', '1');
INSERT INTO `user_club` VALUES ('1', '2');
INSERT INTO `user_club` VALUES ('2', '2');

-- ----------------------------
-- Table structure for user_notice
-- ----------------------------
DROP TABLE IF EXISTS `user_notice`;
CREATE TABLE `user_notice` (
  `user_id` int(11) NOT NULL,
  `notice_id` int(11) NOT NULL,
  `delete` tinyint(4) NOT NULL DEFAULT '0',
  `read` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`notice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_notice
-- ----------------------------
INSERT INTO `user_notice` VALUES ('1', '3', '1', '0');
INSERT INTO `user_notice` VALUES ('1', '4', '1', '1');
INSERT INTO `user_notice` VALUES ('1', '29', '0', '0');
INSERT INTO `user_notice` VALUES ('1', '30', '0', '0');
INSERT INTO `user_notice` VALUES ('1', '31', '0', '0');
INSERT INTO `user_notice` VALUES ('1', '32', '0', '0');
INSERT INTO `user_notice` VALUES ('1', '33', '0', '0');
INSERT INTO `user_notice` VALUES ('1', '34', '0', '0');
INSERT INTO `user_notice` VALUES ('1', '35', '0', '0');
INSERT INTO `user_notice` VALUES ('2', '29', '0', '0');
INSERT INTO `user_notice` VALUES ('2', '30', '0', '0');
INSERT INTO `user_notice` VALUES ('2', '31', '0', '0');
INSERT INTO `user_notice` VALUES ('2', '32', '0', '0');
INSERT INTO `user_notice` VALUES ('2', '33', '0', '0');
INSERT INTO `user_notice` VALUES ('2', '34', '0', '0');
INSERT INTO `user_notice` VALUES ('2', '35', '0', '0');

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

-- ----------------------------
-- Records of year_check
-- ----------------------------
SET FOREIGN_KEY_CHECKS=1;
