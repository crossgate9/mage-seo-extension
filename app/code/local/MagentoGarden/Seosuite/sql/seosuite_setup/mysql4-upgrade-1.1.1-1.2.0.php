<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('seosuite_customurl')};
CREATE TABLE {$this->getTable('seosuite_customurl')} (
  `entity_id` int(11) unsigned NOT NULL auto_increment,
  `url` text NULL default '', 
  `priority` float(11) unsigned NOT NULL, 
  `changefraq` text NULL default '',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup(); 
