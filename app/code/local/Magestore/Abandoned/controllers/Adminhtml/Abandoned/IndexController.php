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
    
    public function massStatusAction(){
        $params = $this->getRequest()->getParams();
        $success = 0;
        $fail = 0;
        if(isset($params['abandoned_id'])&&isset($params['status'])){
            foreach ($params['abandoned_id'] as $id){
                $model = Mage::getModel('abandoned/abandoned')->load($id);
                if($model->getId()){
                    $model->setStatus($params['status']);
                }
                try{
                    $model->save();
                    $success++;
                }catch(Exception $e){
                    $fail++;
                }
            }
        }
        if($success>0)
            $this->_getSession()->addSuccess(
                $this->__('Total of %d record(s) were successfully updated', count($success))
            );
        if($fail>0)
            $this->_getSession()->addError(
                $this->__('Total of %d record(s) were successfully updated', count($fail))
            );
        $this->_redirect('*/*/index');
    }
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('abandoned');
    }
}