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
 * @package     Magestore_Abandoned
 * @copyright   Copyright (c) 2016 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * Integration Model
 * 
 * @category    Magestore
 * @package     Magestore_Abandoned
 * @author      Magestore Developer
 */
class Magestore_Abandoned_Model_Abandoned extends Mage_Core_Model_Abstract
{
    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;
    
    const IS_SUCCESS = 1;
    const IS_NOT_SUCCESS = 0;
    
    public function _construct()
    {
        parent::_construct();
        $this->_init('abandoned/abandoned');
    }
    
    public static function getStatusArray(){
        return array(
            self::STATUS_ENABLE => 'Enabled',
            self::STATUS_DISABLE => 'Disabled',
        );
    }
    
    public function saveAbandoned($item){
        $now = Varien_Date::now();
        $this->setQuoteId($item->getEntityId())
                ->setRemindNum($this->getRemindNum()+1)
                ->setLastRemindTime($now)
                ->setStatus(1);
        if(!$this->getId())
            $this->setId(null);
        return $this->save();
    }
    
    public function getAbandoned($quote){
        return $this->getCollection()
                ->addFieldToFilter('quote_id', $quote->getId())
                ->addFieldToFilter('remind_num', array('gt'=>0))
                ->addFieldToFilter('status', self::STATUS_ENABLE)
                ->addFieldToFilter('is_success', self::IS_NOT_SUCCESS)
                ->getFirstItem();
    }
    
    
}