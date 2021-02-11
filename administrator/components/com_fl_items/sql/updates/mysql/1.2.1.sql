ALTER TABLE `#__fl_items_category` ADD COLUMN `hasImageCaptions` tinyint NOT NULL;
ALTER TABLE `#__fl_items_image` ADD COLUMN `caption` varchar(2048) NOT NULL;