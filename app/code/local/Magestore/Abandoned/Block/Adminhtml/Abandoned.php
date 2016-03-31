<?php
class Magestore_Abandoned_Block_Adminhtml_Abandoned extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_abandoned';
    $this->_blockGroup = 'abandoned';
    $this->_headerText = Mage::helper('membership')->__('Abandoned Manager');
    parent::__construct();
  }
}