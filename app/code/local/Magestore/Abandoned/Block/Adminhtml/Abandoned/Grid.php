<?php

class Magestore_Abandoned_Block_Adminhtml_Abandoned_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('abandonedGrid');
        $this->setDefaultSort('abandoned_id');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        
        $collection = Mage::getModel('abandoned/abandoned')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('abandoned_id', array(
            'header' => Mage::helper('abandoned')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'abandoned_id',
        ));

        $this->addColumn('quote_customer_name', array(
            'header'    =>Mage::helper('abandoned')->__('Customer Name'),
            'index'     =>'quote_customer_name',
            'sortable'  =>false
        ));

        $this->addColumn('quote_customer_email', array(
            'header'    =>Mage::helper('abandoned')->__('Email'),
            'index'     =>'quote_customer_email',
            'sortable'  =>false
        ));

        $currencyCode = Mage::app()->getStore()->getBaseCurrencyCode();
        
        $this->addColumn('quote_base_grand_total', array(
            'header'        => Mage::helper('reports')->__('Grand Total'),
            'width'         => '80px',
            'type'          => 'currency',
            'currency_code' => $currencyCode,
            'index'         => 'quote_base_grand_total',
            'sortable'      => false,
            'renderer'      => 'adminhtml/report_grid_column_renderer_currency',
            'rate'          => $this->getRate($currencyCode),
        ));
        
        $this->addColumn('abandoned_base_discount', array(
            'header'        => Mage::helper('reports')->__('Discount'),
            'width'         => '80px',
            'type'          => 'currency',
            'currency_code' => $currencyCode,
            'index'         => 'abandoned_base_discount',
            'sortable'      => false,
            'renderer'      => 'adminhtml/report_grid_column_renderer_currency',
            'rate'          => $this->getRate($currencyCode),
        ));
        
        $this->addColumn('quote_created_at', array(
            'header'    =>Mage::helper('reports')->__('Created At'),
            'width'     =>'170px',
            'type'      =>'datetime',
            'index'     =>'quote_created_at',
            'filter_index'=>'quote_created_at',
            'sortable'  =>false
        ));

        $this->addColumn('quote_updated_at', array(
            'header'    =>Mage::helper('reports')->__('Updated At'),
            'width'     =>'170px',
            'type'      =>'datetime',
            'index'     =>'quote_updated_at',
            'filter_index'=>'quote_updated_at',
            'sortable'  =>false
        ));
        
        $this->addColumn('remind_num', array(
            'header' => Mage::helper('abandoned')->__('Remind Number'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'remind_num',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('abandoned')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => Magestore_Abandoned_Model_Abandoned::getStatusArray(),
        ));
        
        $this->addColumn('is_success', array(
            'header' => Mage::helper('abandoned')->__('Purchased'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'is_success',
            'type' => 'options',
            'options' => Magestore_Abandoned_Model_Abandoned::getSuccessArray(),
        ));
        
        return parent::_prepareColumns();
    }
    
    public function getGridUrl() {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('abandoned_id');
        $this->getMassactionBlock()->setFormFieldName('abandoned_id');
        $statuses = Magestore_Abandoned_Model_Abandoned::getStatusArray();
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('abandoned')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('abandoned')->__('Status'),
                    'values' => $statuses
                )
            )
        ));
        return $this;
    }

}
