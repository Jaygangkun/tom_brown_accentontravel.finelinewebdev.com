/*
Navicat MySQL Data Transfer

Source Server         : Harley
Source Server Version : 50531
Source Host           : harley.finelineservers.com:3306
Source Database       : finelinewebsites

Target Server Type    : MYSQL
Target Server Version : 50531
File Encoding         : 65001

Date: 2015-09-30 08:56:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for #__flic
-- ----------------------------
CREATE TABLE IF NOT EXISTS `#__flic` (
  `flic_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `shortDescription` text,
  `description` text,
  `isFeatured` tinyint(1) NOT NULL DEFAULT '0',
  `showGallery` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '1',
  `checked_out` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `editor` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `create_by` int(11) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `metaTitle` varchar(255) DEFAULT NULL,
  `metaKeywords` varchar(255) DEFAULT NULL,
  `metaDescription` varchar(255) DEFAULT NULL,
  `resizeImageWidth` int(11) unsigned NOT NULL,
  `resizeImageHeight` int(11) unsigned NOT NULL,
  PRIMARY KEY (`flic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for #__flic_category
-- ----------------------------
CREATE TABLE IF NOT EXISTS `#__flic_category` (
  `flic_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `shortDescription` text,
  `description` text,
  `metaTitle` varchar(255) DEFAULT NULL,
  `metaKeywords` varchar(255) DEFAULT NULL,
  `metaDescription` varchar(255) DEFAULT NULL,
  `treeLeft` int(11) DEFAULT NULL,
  `treeRight` int(11) DEFAULT NULL,
  `treeLevel` text,
  `parent_flic_category_id` int(11) DEFAULT NULL,
  `showCategory` tinyint(1) DEFAULT '1',
  `checked_out` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `editor` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `create_by` int(11) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`flic_category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

INSERT INTO `#__flic_category` VALUES ('1', 'Banners', 'banners', '', '', '', '', '', '0', '4', '0', '0', '1', '0', '0000-00-00 00:00:00', '0', null, null, null, null);
-- ----------------------------
-- Table structure for #__flic_category_gallery
-- ----------------------------
CREATE TABLE IF NOT EXISTS `#__flic_category_gallery` (
  `flic_category_gallery_id` int(11) NOT NULL AUTO_INCREMENT,
  `flic_id` int(11) DEFAULT NULL,
  `flic_category_id` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `create_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`flic_category_gallery_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for #__flic_image
-- ----------------------------
CREATE TABLE IF NOT EXISTS `#__flic_image` (
  `flic_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `flic_id` int(11) NOT NULL,
  `filename` varchar(200) NOT NULL,
  `captionTitle` varchar(200) NOT NULL,
  `captionMessage` text NOT NULL,
  `url` varchar(200) NOT NULL,
  `newWindow` tinyint(1) NOT NULL DEFAULT '0',
  `messagePosition` int(1) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL,
  `showGalleryImage` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`flic_image_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
