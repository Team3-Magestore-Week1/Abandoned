<?php

/**
 * Magestore
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Integration
 * @copyright   Copyright (c) 2016 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */
/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * create abandoned table
 */
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('abandoned/abandoned')};
CREATE TABLE {$this->getTable('abandoned/abandoned')} (

  `abandoned_id` int(11) unsigned NOT NULL auto_increment,
  `quote_id` varchar(255) NOT NULL default '',
  `remind_num` int(11) NOT NULL default 0,
  `last_remind_time` DATETIME NULL,
  `status` SMALLINT(3) NOT NULL DEFAULT 0,
  `is_success` SMALLINT(3) NOT NULL DEFAULT 0,
  `quote_base_grand_total` DECIMAL(12,4) NULL,
  `abandoned_base_discount` DECIMAL(12,4) NULL,
  `quote_customer_name` varchar(255) NULL,
  `quote_customer_email` varchar(255) NULL,
  `quote_created_at` DATETIME NULL,
  `quote_updated_at` DATETIME NULL,
  `order_success_time` DATETIME NULL,
  PRIMARY KEY (`abandoned_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('abandoned/configonoff')};
CREATE TABLE {$this->getTable('abandoned/configonoff')} (

  `configonoff_id` int(11) unsigned NOT NULL auto_increment,
  `emailcustomer` varchar(255) NOT NULL default '',
  `status` int(11) NOT NULL default 0,
  PRIMARY KEY (`configonoff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();

