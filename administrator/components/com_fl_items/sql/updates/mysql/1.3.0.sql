ALTER TABLE `#__fl_items_properties` ADD COLUMN `dimensions` varchar(155) NOT NULL DEFAULT "";
ALTER TABLE `#__fl_items_category` ADD COLUMN `forceExactImageSize` tinyint(1) NOT NULL DEFAULT 0;