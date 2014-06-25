<?php

class MagentoGarden_Seosuite_Model_Mysql4_Customurl extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the smartlabel_id refers to the key field in your database table.
        $this->_init('seosuite/customurl', 'entity_id');
    }
}
