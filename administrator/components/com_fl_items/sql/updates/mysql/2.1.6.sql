ALTER TABLE `#__fl_items_properties`
ADD COLUMN `includeOnForm`  tinyint(1) NOT NULL DEFAULT 1 AFTER `enableProperty`;
