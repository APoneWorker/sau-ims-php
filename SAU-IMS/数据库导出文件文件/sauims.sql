/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : sauims

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2016-12-10 00:52:46
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of clubinfo
-- ----------------------------
INSERT INTO `clubinfo` VALUES ('1', '校社联');
INSERT INTO `clubinfo` VALUES ('2', '出云音乐协会');
INSERT INTO `clubinfo` VALUES ('3', '武术协会');

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
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of notice
-- ----------------------------
INSERT INTO `notice` VALUES ('1', '校社联管理系统建好了！', '2016-12-09 19:02:42', '管理系统', '0', '1');
INSERT INTO `notice` VALUES ('2', '请同学们填好个人资料', '2016-12-09 19:03:57', '请同学们填好个人资料', '0', '1');
INSERT INTO `notice` VALUES ('3', '出云要开展活动啦', '2016-12-09 19:04:45', '出云要开展活动啦', '0', '2');
INSERT INTO `notice` VALUES ('4', '武协要开展活动啦', '2016-12-09 19:07:35', '武协要开展活动啦', '0', '3');

-- ----------------------------
-- Table structure for task
-- ----------------------------
DROP TABLE IF EXISTS `task`;
CREATE TABLE `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `club_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of task
-- ----------------------------
INSERT INTO `task` VALUES ('1', '23wer', 'erwer', '0000-00-00 00:00:00', '1');
INSERT INTO `task` VALUES ('2', 'wqe', '3e', '0000-00-00 00:00:00', '2');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', '1', '28c8edde3d61a0411511d3b1866f0636', '2', '1', '1');
INSERT INTO `user` VALUES ('2', '2', '28c8edde3d61a0411511d3b1866f0636', '1', '2', '1');
INSERT INTO `user` VALUES ('3', '3', '28c8edde3d61a0411511d3b1866f0636', '1', '3', '1');

-- ----------------------------
-- Table structure for userinfo
-- ----------------------------
DROP TABLE IF EXISTS `userinfo`;
CREATE TABLE `userinfo` (
  `user_id` int(11) NOT NULL,
  `head_img` varchar(255) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`head_img`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of userinfo
-- ----------------------------
INSERT INTO `userinfo` VALUES ('2', null, '出云管理员');
INSERT INTO `userinfo` VALUES ('3', null, '武协管理员');
INSERT INTO `userinfo` VALUES ('1', '1.jpg', '校社联管理员');

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
  `read` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`notice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_notice
-- ----------------------------
INSERT INTO `user_notice` VALUES ('1', '1', '0');
INSERT INTO `user_notice` VALUES ('1', '2', '1');
INSERT INTO `user_notice` VALUES ('2', '1', '0');
INSERT INTO `user_notice` VALUES ('2', '2', '0');
INSERT INTO `user_notice` VALUES ('2', '3', '0');
INSERT INTO `user_notice` VALUES ('3', '1', '0');
INSERT INTO `user_notice` VALUES ('3', '2', '0');

-- ----------------------------
-- Table structure for user_task
-- ----------------------------
DROP TABLE IF EXISTS `user_task`;
CREATE TABLE `user_task` (
  `user_id` int(255) NOT NULL,
  `task_id` int(11) NOT NULL,
  `read` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`task_id`,`read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_task
-- ----------------------------
INSERT INTO `user_task` VALUES ('2', '1', '0');
INSERT INTO `user_task` VALUES ('2', '2', '0');

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
