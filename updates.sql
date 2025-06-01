ALTER TABLE `product_inventories` ADD `delivered` INT NULL DEFAULT '0' AFTER `commited`;

ALTER TABLE `product_bins` ADD `delivered` INT NULL DEFAULT '0' AFTER `commited`;


ALTER TABLE `transfers` ADD `reference` VARCHAR(191) NULL DEFAULT NULL AFTER `to_warehouse_id`;

ALTER TABLE `transfers` ADD `received` INT NULL DEFAULT '0' AFTER `quantity`, ADD `faulty` INT NULL DEFAULT '0' AFTER `received`;
