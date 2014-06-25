<?php

class MagentoGarden_Seosuite_Block_Adminhtml_Imagesitemap_Renderer_Valueid extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {
	public function render(Varien_Object $row) {
		return $row->getData($this->getColumn()->getIndex());
		$_storeviews = explode(',', $row->getData($index));
		$_result = $this->_storeviewNumberToArray($_storeviews);
		return implode(',', $_result);
		return Mage::helper('seosuite')->renderOriginColumn($row, $this->getColumn()->getIndex());
	}
}
