<?php
class Magestore_Abandoned_Block_System_Config_Discount extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    private $_count = 1;
    
    public function __construct()
    {
        $this->addColumn('number', array(
            'label' => Mage::helper('abandoned')->__('Remind Number'),
            'style' => 'width:auto',
        ));
        $this->addColumn('value', array(
            'label' => Mage::helper('abandoned')->__('Discount'),
            'style' => 'width:250px',
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('abandoned')->__('Add Discount');
        $this->setTemplate('abandoned/config/form/field/array.phtml');
        parent::__construct();
    }
    
    protected function _renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new Exception('Wrong column name specified.');
        }
        $column     = $this->_columns[$columnName];
        $inputName  = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';

        if ($column['renderer']) {
            return $column['renderer']->setInputName($inputName)->setColumnName($columnName)->setColumn($column)
                ->toHtml();
        }
        
        $disable = $columnName=='number'?'readonly':'';
        $class = $columnName=='number'?'input-text number-discount':'input-text';
        
        return '<input '.$disable.' type="text" name="' . $inputName . '" value="#{' . $columnName . '}" ' .
            ($column['size'] ? 'size="' . $column['size'] . '"' : '') . ' class="'.$class.'"'.
            (isset($column['style']) ? ' style="'.$column['style'] . '"' : '') . '/>';
    }
}