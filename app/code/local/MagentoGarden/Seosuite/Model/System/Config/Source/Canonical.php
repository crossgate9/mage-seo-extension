<?php 

class MagentoGarden_Seosuite_Model_System_Config_Source_Canonical {
	public function toOptionArray() {
		return array(
			array('value' => 'default', 'label' => Mage::helper('seosuite')->__('Default')),
			array('value' => 'one-level', 'label' => Mage::helper('seosuite')->__('Include Top Level Category')),
			array('value' => 'all-level', 'label' => Mage::helper('seosuite')->__('Include All Level Categories')),
		);
	}
}
