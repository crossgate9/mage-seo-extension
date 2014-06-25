<?php

class MagentoGarden_Seosuite_Model_Imagesitemap extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('seosuite/imagesitemap');
    }
	
	public function loadByValueId($_value_id) {
    	$_collection = $this->getCollection()
    					->addFieldToFilter('value_id', $_value_id);
    	if ($_collection->getSize() > 0) {
    		foreach ($_collection as $_imagesitemap) {
    			return $this->load($_imagesitemap->getData('entity_id'));
    		}
    	}
    	return $this;
    }
}
