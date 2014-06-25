<?php 

class MagentoGarden_Seosuite_Model_System_Config_Source_ShowStyle {
	public function toOptionArray() {
		return array(
			array('value' => 'tree', 'label' => Mage::helper('seosuite')->__('Tree Style')),
			array('value' => 'block', 'label' => Mage::helper('seosuite')->__('Block Style')),
		);
	}
}
