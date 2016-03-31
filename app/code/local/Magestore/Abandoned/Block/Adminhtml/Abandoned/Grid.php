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

        $this->addColumn('customer_name', array(
            'header'    =>Mage::helper('abandoned')->__('Customer Name'),
            'index'     =>'customer_name',
            'sortable'  =>false
        ));

        $this->addColumn('email', array(
            'header'    =>Mage::helper('abandoned')->__('Email'),
            'index'     =>'email',
            'sortable'  =>false
        ));

        $currencyCode = Mage::app()->getStore()->getBaseCurrencyCode();
        
        $this->addColumn('subtotal', array(
            'header'        => Mage::helper('reports')->__('Subtotal'),
            'width'         => '80px',
            'type'          => 'currency',
            'currency_code' => $currencyCode,
            'index'         => 'subtotal',
            'sortable'      => false,
            'renderer'      => 'adminhtml/report_grid_column_renderer_currency',
            'rate'          => $this->getRate($currencyCode),
        ));
        
        $this->addColumn('abandoned_discount', array(
            'header'        => Mage::helper('reports')->__('Discount'),
            'width'         => '80px',
            'type'          => 'currency',
            'currency_code' => $currencyCode,
            'index'         => 'abandoned_discount',
            'sortable'      => false,
            'renderer'      => 'adminhtml/report_grid_column_renderer_currency',
            'rate'          => $this->getRate($currencyCode),
        ));
        
        $this->addColumn('created_at', array(
            'header'    =>Mage::helper('reports')->__('Created At'),
            'width'     =>'170px',
            'type'      =>'datetime',
            'index'     =>'created_at',
            'filter_index'=>'main_table.created_at',
            'sortable'  =>false
        ));

        $this->addColumn('updated_at', array(
            'header'    =>Mage::helper('reports')->__('Updated At'),
            'width'     =>'170px',
            'type'      =>'datetime',
            'index'     =>'updated_at',
            'filter_index'=>'main_table.updated_at',
            'sortable'  =>false
        ));
        
        

        $this->addColumn('status', array(
            'header' => Mage::helper('abandoned')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => 'Enabled',
                0 => 'Disabled',
            ),
        ));
        //$this->addExportType('*/*/exportCsv', Mage::helper('membership')->__('CSV'));
        //$this->addExportType('*/*/exportXml', Mage::helper('membership')->__('XML'));

        return parent::_prepareColumns();
    }

}
