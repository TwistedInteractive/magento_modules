<?php
/**
 * (c) 2012
 * Author: Giel Berkers
 * Date: 22-3-12
 * Time: 16:57
 */

class {{NAMESPACE}}_{{MODULE_NAME}}_Block_Adminhtml_{{MODULE_NAME}}_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = '{{NAME_LOWERCASE}}';
        $this->_controller = 'adminhtml_{{NAME_LOWERCASE}}';
        $this->_mode = 'edit';

        $this->_addButton('save_and_continue', array(
            'label' => Mage::helper('{{NAME_LOWERCASE}}')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save'
        ), -100);

	    $this->_addButton('duplicate', array(
		    'label' => Mage::helper('{{NAME_LOWERCASE}}')->__('Duplicate'),
		    'onclick' => 'duplicate()',
		    'class' => 'save'
	    ), 0);

        $this->_updateButton('save', 'label', Mage::helper('{{NAME_LOWERCASE}}')->__('Save'));

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('form_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'edit_form');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'edit_form');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }

            function duplicate(){
                editForm.submit($('edit_form').action+'duplicate/1/');
            }
        ";


    }

    public function getHeaderText()
    {
        if (Mage::registry('{{NAME_LOWERCASE}}_data') && Mage::registry('{{NAME_LOWERCASE}}_data')->getId()) {
            return Mage::helper('{{NAME_LOWERCASE}}')->__('Edit "%s"', $this->htmlEscape(Mage::registry('{{NAME_LOWERCASE}}_data')->getTitle()));
        } else {
            return Mage::helper('{{NAME_LOWERCASE}}')->__('New');
        }
    }

}
