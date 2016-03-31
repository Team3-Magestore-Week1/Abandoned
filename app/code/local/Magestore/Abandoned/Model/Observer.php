<?php
/**
 * Created by PhpStorm.
 * User: pikachu
 * Date: 3/31/16
 * Time: 10:49 AM
 */
class Magestore_Abandoned_Model_Observer {

    public function showLinks($observer){

        $storeId = Mage::app()->getStore()->getId();
        $block = $observer['block'];
        // zend_debug::dump(get_class($block));die('12');
        if ($block instanceof Mage_Customer_Block_Account_Navigation) {
            $topLinks = $block->getLayout()->getBlock('customer_account_navigation');
            if (isset($topLinks) && $topLinks != null) {
                $topLinks->addLink('Abandoned','adbandoned/index/index', 'Abandoned');
            }

        }
    }
}