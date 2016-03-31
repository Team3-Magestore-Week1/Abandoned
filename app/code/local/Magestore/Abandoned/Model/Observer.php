<?php

class Magestore_Abandoned_Model_Observer {
    
    public function orderSaveAfter($observers) {
        $order = $observers->getEvent()->getOrder();
        $quote = $order->getQuote();
        $model = Mage::getModel('abandoned/abandoned')->load($quote->getId(), 'quote_id');
        if($model->getId()&&$quote->getBaseAbandonedDiscount()>0){
           $model->setAbandonedBaseDiscount($quote->getBaseAbandonedDiscount())
                   ->setIsSuccess(Magestore_Abandoned_Model_Abandoned::IS_SUCCESS);
           try{
               $model->save();
           }catch(Exception $e){}
        }
    }

}
