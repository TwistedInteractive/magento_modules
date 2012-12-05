<?php
/**
 * (c) 2012
 * Author: Giel Berkers
 * Date: 22-3-12
 * Time: 17:00
 */

class {{NAMESPACE}}_{{MODULE_NAME}}_Block_Adminhtml_{{MODULE_NAME}}_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        if (Mage::getSingleton('adminhtml/session')->getExampleData()) {
            $data = Mage::getSingleton('adminhtml/session')->getExamplelData();
            Mage::getSingleton('adminhtml/session')->getExampleData(null);
        }
        elseif (Mage::registry('{{NAME_LOWERCASE}}_data'))
        {
            $data = Mage::registry('{{NAME_LOWERCASE}}_data')->getData();
        }
        else
        {
            $data = array();
        }

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
            'enctype' => 'multipart/form-data',
        ));

        $form->setUseContainer(true);

        $this->setForm($form);

        $fieldset = $form->addFieldset('{{NAME_LOWERCASE}}_form', array(
            'legend' => Mage::helper('{{NAME_LOWERCASE}}')->__('Information')
        ));

		// Fields is dynamic:
		{{FORM}}

		// Enabled:
		$fieldset->addField('enabled', 'select', array(
			'label' => Mage::helper('banners')->__('Enabled'),
			'name' => 'enabled',
			'values' => array(
				array(
					'value'     => 1,
					'label'     => Mage::helper('banners')->__('Yes'),
				),
				array(
					'value'     => 0,
					'label'     => Mage::helper('banners')->__('No'),
				),
			)
		));

		// Stores:
	    $values = array();
        $stores = Mage::app()->getStores();
        foreach($stores as $store)
        {
            $values[] = array(
                'value' => $store->getId(),
                'label' => $store->getName()
            );
        }

        $fieldset->addField('store_id', 'select', array(
            'label' => Mage::helper('{{NAME_LOWERCASE}}')->__('Store view'),
            'name' => 'store_id',
            'values' => $values
        ));

	    $form->setValues($data);

        return parent::_prepareForm();
    }
}
