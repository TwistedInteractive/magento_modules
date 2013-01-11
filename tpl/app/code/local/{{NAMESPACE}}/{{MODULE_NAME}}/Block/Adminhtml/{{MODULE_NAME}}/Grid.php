<?php
/**
 * (c) 2012
 * Author: Giel Berkers
 * Date: 22-3-12
 * Time: 16:45
 */

class {{NAMESPACE}}_{{MODULE_NAME}}_Block_Adminhtml_{{MODULE_NAME}}_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('{{NAME_LOWERCASE}}_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Create a collection of items to show in the grid
     * @return this
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('{{NAME_LOWERCASE}}/main')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare the columns
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => Mage::helper('{{NAME_LOWERCASE}}')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'id',
        ));

		$this->addColumn('order', array(
			'header' => Mage::helper('{{NAME_LOWERCASE}}')->__('Order'),
			'align' => 'right',
			'width' => '50px',
			'index' => 'order',
		));

		// Dit wordt dynamisch geladen:
		{{FIRST_COLUMN}}

		$values = array();
		$stores = Mage::app()->getStores();
		foreach($stores as $store)
		{
			$values[$store->getId()] = $store->getName();
		}

        $this->addColumn('store_id', array(
            'header' => Mage::helper('{{NAME_LOWERCASE}}')->__('Store'),
            'align' => 'left',
            'index' => 'store_id',
            'type' => 'options',
            'options' => $values
        ));

        $this->addColumn('enabled', array(
            'header' => Mage::helper('{{NAME_LOWERCASE}}')->__('Enabled'),
            'align' => 'left',
            'index' => 'enabled',
            'type' => 'options',
            'options' => array(
                '1' => Mage::helper('{{NAME_LOWERCASE}}')->__('Yes'),
                '0' => Mage::helper('{{NAME_LOWERCASE}}')->__('No')
            ),
            'width' => '50px'
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('id');
		// Check of dit werkt: dit was 'banner', en niet 'banners': :-/
		$this->getMassactionBlock()->setFormFieldName('{{NAME_LOWERCASE}}');

		$this->getMassactionBlock()->addItem('delete', array(
			'label' => Mage::helper('{{NAME_LOWERCASE}}')->__('Delete'),
			'url' => $this->getUrl('*/*/massDelete'),
		));

		$this->getMassactionBlock()->addItem('duplicate', array(
			'label' => Mage::helper('{{NAME_LOWERCASE}}')->__('Duplicate'),
			'url' => $this->getUrl('*/*/massDuplicate'),
		));

		return $this;
	}
}
