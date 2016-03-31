<?php

class Magestore_Abandoned_Model_Total_Quote_Abandoned extends Mage_Sales_Model_Quote_Address_Total_Abstract {

    public function __construct() {
        $this->setCode('abandoned');
    }

    public function collect(Mage_Sales_Model_Quote_Address $address) {
        $quote = $address->getQuote();
        
        $session = Mage::getSingleton('checkout/session');
                
        if ($address->getAddressType() == 'billing' && !$quote->isVirtual()) {
            return $this;
        }
        
        $abandoned = Mage::getModel('abandoned/abandoned')->getAbandoned($quote);
        if(!$abandoned->getId())
            return $this;
        $remindConfig = Mage::getStoreConfig('abandoned/general/discount_config');
        $remindConfig = unserialize($remindConfig);
        if(count($remindConfig)<1)
            return $this;
        $discountValue = 0;
        foreach($remindConfig as $config){
            if($abandoned->getRemindNum()==$config['number'])
                $discountValue = $config['value'];
        }
        $baseGrandTotal = $address->getBaseGrandTotal();
        $grandTotal = $address->getGrandTotal();
        $baseDiscount = $baseGrandTotal*$discountValue/100;
        $discount = $grandTotal*$discountValue/100;
        $address->setBaseGrandTotal($baseGrandTotal-$baseDiscount);
        $address->setGrandTotal($grandTotal-$discount);
        $address->setBaseAbandonedDiscount($baseDiscount);
        $address->setAbandonedDiscount($discount);
        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address) {
        $quote = $address->getQuote();
        if ($address->getAddressType() == 'billing' && !$quote->isVirtual()) {
            return $this;
        }
        if ($discount = $address->getAbandonedDiscount()) {
            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => Mage::helper('abandoned')->__('Abandoned Discount'),
                'value' => -$discount,
            ));
        }
        return $this;
    }

}
