<?php

$installer = $this;

$installer->startSetup();

$installer->run(" ALTER TABLE  {$this->getTable('sales_order_status')} ADD  `description` TEXT NOT NULL; ");

$installer->endSetup(); 