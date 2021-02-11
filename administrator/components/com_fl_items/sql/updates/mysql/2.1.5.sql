ALTER TABLE `#__fl_items_category`
ADD COLUMN `addWatermark`  tinyint(1) NOT NULL DEFAULT 0 AFTER `isHeader`,
ADD COLUMN `watermarkImage`  varchar(255) NOT NULL AFTER `addWatermark`,
ADD COLUMN `watermarkPosition`  int(2) NOT NULL AFTER `watermarkImage`;
