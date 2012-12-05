<?php
/**
 * (c) 2012
 * Author: Giel Berkers
 * Date: 22-3-12
 * Time: 13:48
 */

class {{NAMESPACE}}_{{MODULE_NAME}}_Model_Resource_Main extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('{{NAME_LOWERCASE}}/{{NAME_LOWERCASE}}', 'id');
    }
}
