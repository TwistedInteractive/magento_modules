<?php
// Flat DB:
$this->startSetup();

// Maak de table:
$this->run(sprintf('
    CREATE TABLE `%s` (
        `id` int(11) NOT NULL auto_increment,
        `store_id` INT(11) NOT NULL,
        `enabled` INT(1),
        `order` INT(11) NOT NULL,
        {{INSTALL_SQL}}
        PRIMARY KEY  (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

', $this->getTable('{{NAME_LOWERCASE}}/{{NAME_LOWERCASE}}')));

$this->endSetup();