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
 * Abandoned Index Controller
 *
 * @category    Magestore
 * @package     Magestore_Abandoned
 * @author      Magestore Developer
 */
class Magestore_Abandoned_IndexController extends Mage_Core_Controller_Front_Action {

    public function testAction() {

        Mage::getModel('abandoned/cron')->run();
    }

    public function indexAction(){
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    protected function _getCoreSession() {
        return Mage::getSingleton('core/session');
    }

    public function createPostAction(){
        $customeremail = $this->_getSession()->getCustomer()->getEmail();
        $notification = $this->getRequest()->getPost('notification');
        $coreSession = $this->_getCoreSession();
        $collection = Mage::getModel('abandoned/configonoff')->getCollection()
                                                        ->addFieldToFilter('emailcustomer',$customeremail)->getFirstItem();
        $id = $collection->getConfigonoffId();
        $model =  Mage::getModel('abandoned/configonoff')->load($id);
        try{
            $model->setStatus($notification);
            $model->save();
            $successMessage = $this->__('Your account information has been saved.');
            $coreSession->addSuccess($successMessage);
            return $this->_redirect('abandoned/index/index');
        }catch (Exception $e){
            $coreSession->addError($e->getMessage());
        }

    }

}
