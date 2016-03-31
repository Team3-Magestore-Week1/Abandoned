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
        $to = $params['to'].' 23:59:59';
        $start = new DateTime($params['from']);
        $end = new DateTime($params['to']);
        $end->modify('+1 day');
        $interval = new DateInterval('P1D'); // 1 Day interval
        $period = new DatePeriod($start, $interval, $end);
        foreach ($period as $day) {
            $timeArray[] = $day->format('Y-m-d');
        }
        $format = '%Y-%m-%d';
        foreach ($timeArray as $time) {
            $collection = Mage::getModel('abandoned/abandoned')->getCollection()
                    ->addFieldToFilter('is_success', Magestore_Abandoned_Model_Abandoned::IS_SUCCESS);
            $collection->getSelect()->where("date(order_success_time) = DATE_FORMAT('$time','$format')");
            $countSuccess[] = $collection->getSize();
            $sumDiscount[] = array_sum($collection->getColumnValues('abandoned_base_discount'));
            $sumTotal[] = array_sum($collection->getColumnValues('quote_base_grand_total'));
        }
        return $this->getResponse()->setBody(json_encode(
                array(
                    'timeArray' => $timeArray,
                    'countSuccess' => $countSuccess, 
                    'sumDiscount' => $sumDiscount,
                    'sumTotal' => $sumTotal
                )));
    }
    
    public function getTimeZone()
    {
        return Mage::getStoreConfig('general/locale/timezone');
    }
    
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('abandoned');
    }
}