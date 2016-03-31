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
 * Integration Adminhtml Controller
 *
 * @category    Magestore
 * @package     Magestore_Abandoned
 * @author      Magestore Developer
 */
class Magestore_Abandoned_Adminhtml_Abandoned_ReportController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('abandoned/abandoned')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Abandoned Report'), Mage::helper('adminhtml')->__('Abandoned Report'));

        return $this;
    }
    
    public function indexAction(){
        $this->_initAction()
                ->renderLayout();
    }
    
    public function searchAction(){
        $params = $this->getRequest()->getParams();
        $collection = Mage::getModel('abandoned/abandoned')->getCollection()
                ->addFieldToFilter('order_success_time', array(
                    'to'=> $params['to']
                ));
                
        Zend_Debug::dump($collection->getSelect()->__toString());die;
    }
    
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('abandoned');
    }
}