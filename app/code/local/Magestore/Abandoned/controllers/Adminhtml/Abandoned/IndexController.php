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
class Magestore_Abandoned_Adminhtml_Abandoned_IndexController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('abandoned/abandoned')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Abandoned Manager'), Mage::helper('adminhtml')->__('Abandoned Manager'));

        return $this;
    }
    
    public function indexAction(){
        $this->_initAction()
                ->renderLayout();
    }
    
    public function gridAction() {
        $this->getResponse()->setBody($this->getLayout()->createBlock('abandoned/adminhtml_abandoned_grid')->toHtml());
    }
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('abandoned');
    }
}