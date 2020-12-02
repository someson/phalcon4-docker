CREATE TABLE IF NOT EXISTS `session_data` (
    `id` VARCHAR(35) NOT NULL COLLATE 'utf8_general_ci',
    `data` TEXT NULL DEFAULT NULL COLLATE 'utf8_general_ci',
    `created_on` INT(10) UNSIGNED NOT NULL,
    `modified_on` INT(10) UNSIGNED NULL DEFAULT NULL,
    PRIMARY KEY (`id`) USING BTREE
) COLLATE='utf8_general_ci' ENGINE=InnoDB;
