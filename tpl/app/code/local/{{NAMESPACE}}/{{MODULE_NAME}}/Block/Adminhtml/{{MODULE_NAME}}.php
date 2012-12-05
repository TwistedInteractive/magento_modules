<?php
/**
 * (c) 2012
 * Author: Giel Berkers
 * Date: 22-3-12
 * Time: 16:31
 */

class {{NAMESPACE}}_{{MODULE_NAME}}_Block_Adminhtml_{{MODULE_NAME}} extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected $_addButtonLabel = 'Add New';

    public function __construct()
    {
        // Create a grid af all created entries:
        parent::__construct();
        $this->_controller = 'adminhtml_{{NAME_LOWERCASE}}';
        $this->_blockGroup = '{{NAME_LOWERCASE}}';
        $this->_headerText = Mage::helper('{{NAME_LOWERCASE}}')->__('{{MODULE_NAME}}');
    }

}
