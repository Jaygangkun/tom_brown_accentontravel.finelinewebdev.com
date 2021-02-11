SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for #__fl_items
-- ----------------------------
DROP TABLE IF EXISTS `#__fl_items`;
CREATE TABLE `#__fl_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_category_id` int(11) DEFAULT NULL,
  `name` varchar(256) DEFAULT NULL,
  `alias` varchar(256) DEFAULT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `isFeatured` tinyint(1) DEFAULT NULL,
  `showItem` tinyint(1) DEFAULT NULL,
  `parent_item_id` int(11) DEFAULT NULL,
  `parent_item_variation_id` int(11) DEFAULT NULL,
  `linked_user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for #__fl_items_category
-- ----------------------------
DROP TABLE IF EXISTS `#__fl_items_category`;
CREATE TABLE `#__fl_items_category` (
  `item_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_category_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `description` text,
  `noimage` varchar(255) NOT NULL,
  `isDescriptionEnabled` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `menuId` int(11) DEFAULT NULL,
  `showCategory` tinyint(1) DEFAULT '1',
  `hasImages` tinyint(1) DEFAULT '1',
  `isSingleImage` tinyint(1) DEFAULT '0',
  `hasImageCaptions` tinyint(1) DEFAULT '0',
  `isSubItem` tinyint(1) DEFAULT '0',
  `isNewFirst` tinyint(1) DEFAULT '0',
  `subItemParentId` int(11) DEFAULT NULL,
  `isLinkToUser` tinyint(1) DEFAULT '0',
  `usersEditOnly` tinyint(1) DEFAULT NULL,
  `usersUpdatePublish` tinyint(1) DEFAULT '0',
  `isFeaturedEnabled` tinyint(1) DEFAULT '0',
  `imageWidth` int(6) DEFAULT '0',
  `imageHeight` int(6) DEFAULT '0',
  `forceExactImageSize` tinyint(1) DEFAULT '0',
  `isForceMenuItem` tinyint(1) DEFAULT '0',
  `isHiddenParent` tinyint(1) DEFAULT '0',
  `isHeader` tinyint(1) DEFAULT '0',
  `addWatermark` tinyint(1) NOT NULL DEFAULT '0',
  `watermarkImage` varchar(255) NOT NULL,
  `watermarkPosition` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for #__fl_items_image
-- ----------------------------
DROP TABLE IF EXISTS `#__fl_items_image`;
CREATE TABLE `#__fl_items_image` (
  `item_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `filename` varchar(200) DEFAULT NULL,
  `caption` varchar(2048) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `showImage` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_image_id`)
) ENGINE=MyISAM AUTO_INCREMENT=183 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for #__fl_items_item_property
-- ----------------------------
DROP TABLE IF EXISTS `#__fl_items_item_property`;
CREATE TABLE `#__fl_items_item_property` (
  `items_item_property_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `item_property_id` int(11) NOT NULL,
  `value` text,
  PRIMARY KEY (`items_item_property_id`)
) ENGINE=InnoDB AUTO_INCREMENT=360 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for #__fl_items_item_sub_item
-- ----------------------------
DROP TABLE IF EXISTS `#__fl_items_item_sub_item`;
CREATE TABLE `#__fl_items_item_sub_item` (
  `item_sub_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `sub_item_id` int(11) NOT NULL,
  `sub_item_parent_id` int(11) NOT NULL,
  PRIMARY KEY (`item_sub_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for #__fl_items_option
-- ----------------------------
DROP TABLE IF EXISTS `#__fl_items_option`;
CREATE TABLE `#__fl_items_option` (
  `item_property_multi_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_property_id` int(11) NOT NULL,
  `option` varchar(255) NOT NULL,
  `needsDelete` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_property_multi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for #__fl_items_option_map
-- ----------------------------
DROP TABLE IF EXISTS `#__fl_items_option_map`;
CREATE TABLE `#__fl_items_option_map` (
  `item_id` int(11) NOT NULL,
  `item_property_id` int(11) NOT NULL,
  `item_property_multi_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for #__fl_items_properties
-- ----------------------------
DROP TABLE IF EXISTS `#__fl_items_properties`;
CREATE TABLE `#__fl_items_properties` (
  `item_property_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `dimensions` varchar(155) NOT NULL,
  `ordering` int(11) NOT NULL,
  `showInDirectory` tinyint(1) NOT NULL DEFAULT '0',
  `enableFilter` tinyint(1) NOT NULL DEFAULT '0',
  `isSearchable` tinyint(1) NOT NULL DEFAULT '0',
  `allowUserEdit` tinyint(1) NOT NULL DEFAULT '1',
  `enableProperty` tinyint(1) NOT NULL DEFAULT '1',
  `includeOnForm` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`item_property_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;
SET FOREIGN_KEY_CHECKS=1;
