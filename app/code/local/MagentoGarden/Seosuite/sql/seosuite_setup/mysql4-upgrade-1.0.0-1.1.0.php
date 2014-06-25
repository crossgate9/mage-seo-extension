<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('imagesitemap')};
CREATE TABLE {$this->getTable('imagesitemap')} (
  `entity_id` int(11) unsigned NOT NULL auto_increment,
  `value_id` int(11) NOT NULL,
  `title` varchar(255) NULL default ' ', 
  `caption` text NULL,
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup(); 
