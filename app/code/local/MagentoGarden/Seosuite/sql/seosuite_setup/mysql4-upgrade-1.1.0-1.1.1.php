<?php

$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('imagesitemap')} ADD  `product_id` INT NOT NULL AFTER `value_id`;
");

$installer->endSetup(); 
