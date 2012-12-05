<?php
/**
 * (c) 2012
 * Author: Giel Berkers
 * Date: 22-3-12
 * Time: 13:59
 */

class {{NAMESPACE}}_{{MODULE_NAME}}_Model_Resource_Main_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('{{NAME_LOWERCASE}}/main');
    }
}
