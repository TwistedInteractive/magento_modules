<?php
/**
 * (c) 2012
 * Author: Giel Berkers
 * Date: 22-3-12
 * Time: 12:12
 *
 * Usage:
 *
	$banners = Mage::getModel('banners/banner')->getStoreBanners();
	foreach($banners as $banner)
	{
		$banner->getBannerId();
		$banner->getTitle();
		$banner->getDescription();
		$banner->getEnabled();
		$banner->getLink();
		$banner->getLinktext();
		$banner->getImage();
		$banner->getStoreId();
		$banner->getAlign();
		$banner->getThumbnail();
		$banner->getSubtitle();
		$banner->getStyle();
	}
*/

class {{NAMESPACE}}_{{MODULE_NAME}}_Model_Main extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('{{NAME_LOWERCASE}}/main');
    }

    /**
     * Get the store items
     * @return object
     */
    public function getStoreItems()
    {
        $_items = $this->getCollection();
		$_items->addFieldToFilter('enabled', array('eq' => 1));
		$_items->addFieldToFilter('store_id', array('eq' => Mage::app()->getStore()->getId()));
        return $_items;
    }
}
