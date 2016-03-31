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
 * Integration Resource Model
 * 
 * @category    Magestore
 * @package     Magestore_Abandoned
 * @author      Magestore Developer
 */
class Magestore_Abandoned_Model_Cron extends Varien_Object
{
    const XML_PATH_ADMIN_EMAIL_IDENTITY = 'trans_email/ident_general';

    public function run(){
        if (!Mage::helper('abandoned')->isAbandonedEnabled()){
            return;
        }
        $remindConfig = Mage::getStoreConfig('abandoned/general/discount_config');
        $remindConfig = unserialize($remindConfig);
        if(count($remindConfig)<1)
            return;
        $lastRemindConfig = end($remindConfig);
        $maxCountRemind = $lastRemindConfig['number'];
        $remindTime = Mage::getStoreConfig('abandoned/general/delay_time_remind');
        $now = Varien_Date::now();

        $collectionNin = Mage::getResourceModel('reports/quote_collection');
        $collectionNin->prepareForAbandonedReport();
        
        $collectionNin->getSelect()->columns(
                array('sub_time' => 'DATE_ADD(main_table.created_at,INTERVAL ' . $remindTime . ' DAY)')
        );
        $collectionNin->addFieldToFilter('DATE_ADD(main_table.created_at,INTERVAL ' . $remindTime . ' DAY)', array('to' => $now));
        $abandoned = Mage::getModel('abandoned/abandoned')
                ->getCollection()
                ->addFieldToSelect('quote_id')
                ->getData();
        $notRemindEmail = Mage::getModel('abandoned/configonoff')->getCollection()
                ->addFieldToFilter('status', Magestore_Abandoned_Model_Configonoff::STATUS_ENABLE)
                ->getColumnValues('emailcustomer');
        if(count($abandoned)>0)
            $collectionNin->addFieldToFilter('main_table.entity_id',
                    array('nin'=>$abandoned)
            );
        $this->abandoned($collectionNin, $remindConfig);
        $collectionIn = Mage::getResourceModel('reports/quote_collection');
        $collectionIn->prepareForAbandonedReport();
        $collectionIn->getSelect()->columns(
                array('sub_time' => 'DATE_ADD(main_table.created_at,INTERVAL ' . $remindTime . ' DAY)')
        );
        $collectionIn->addFieldToFilter('DATE_ADD(main_table.created_at,INTERVAL ' . $remindTime . ' DAY)', array('to' => $now));
        if(count($abandoned)>0)
            $collectionIn->addFieldToFilter('main_table.entity_id', 
                    array('in'=>$abandoned)
            );
        $collectionIn->getSelect()->join(array('abandoned' => $collectionIn->getTable('abandoned/abandoned')), 'main_table.entity_id = abandoned.quote_id'
                . ' AND abandoned.status = ' . Magestore_Abandoned_Model_Abandoned::STATUS_ENABLE
                . ' AND abandoned.is_success = 0'
                . ' AND abandoned.remind_num < '.$maxCountRemind
                . ' AND DATE_ADD(abandoned.last_remind_time, INTERVAL '.$remindTime.' DAY) <= "'.$now.'"'
        );
        $this->abandoned($collectionIn, $remindConfig);
    }
    
    public function abandoned($collection = null, $remindConfig){
        if(!$collection)
            return;
        foreach($collection as $item){
            $model = Mage::getModel('abandoned/abandoned')->getCollection()
                    ->addFieldToFilter('quote_id', $item->getEntityId())
                    ->getFirstItem();
            $model->saveAbandoned($item);
            $this->sendEmail($item, $model, $remindConfig);
            $this->sendSms($item, $model);
        }
    }

    public function sendEmail($item, $model, $remindConfig){
        $customerId = $item->getData('customer_id');
        $customer = Mage::getModel('customer/customer');
        if ($customerId) {
            $customer->load($customerId);
        }
         $quote = Mage::getModel('sales/quote')
             ->load($item->getEntityId());
        if ($quote->getId()) {
            $collection = $quote->getItemsCollection(false);
        }


        foreach($collection as $item){
            $product = Mage::getModel('catalog/product')->load($item['product_id']);
        }



        $currency = Mage::app()->getStore()->getBaseCurrency();
        $senderEmailConfig = $this->getEmailContact();

        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
        foreach($remindConfig as $remind){
           if($model->getData('remind_num') == $remind['number']){
              $remindValue = $remind['value'];
               $template = $remind['email_template'];
           }
        }
        $sendTo = array(
            array(
                'email' => $item->getData('customer_email'),
                'name' => $item->getData('customer_firstname'). ' '.$item->getData('customer_lastname'),
            )
        );

        $mailTemplate = Mage::getModel('core/email_template');

        foreach ($sendTo as $recipient) {

            $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                ->sendTransactional(
                    $template, $senderEmailConfig, $recipient['email'], $recipient['name'], array(
                        'sender_name' => $senderEmailConfig['name'],
                        'remind_value' => $remindValue,
                        'customer_name' => $recipient['name'],
                    )
                );
        }

        $translate->setTranslateInline(true);

        return $this;
    }

    public  function getEmailContact(){

        $senderDefault = Mage::getStoreConfig(self::XML_PATH_ADMIN_EMAIL_IDENTITY);

                $senderEmailConfig = array(
                    'email' => $senderDefault['email'],
                    'name' => $senderDefault['name'],
                );

        return $senderEmailConfig;
    }

    public function sendSms($item, $model){
        
    }
}