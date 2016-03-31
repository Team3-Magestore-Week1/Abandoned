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

DROP TABLE IF EXISTS {$this->getTable('abandoned/configonoff')};
CREATE TABLE {$this->getTable('abandoned/configonoff')} (

  `configonoff_id` int(11) unsigned NOT NULL auto_increment,
  `emailcustomer` varchar(255) NOT NULL default '',
  `status` int(11) NOT NULL default 0,
  PRIMARY KEY (`configonoff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();

