<?php
class Magestore_Abandoned_Block_Adminhtml_Report extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_abandoned';
    $this->_blockGroup = 'abandoned';
    $this->_headerText = Mage::helper('abandoned')->__('Abandoned Report');
    parent::__construct();
  }
}