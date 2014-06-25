<?php

class MagentoGarden_Seosuite_Block_Adminhtml_Imagesitemap_Renderer_Productid extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {
	public function render(Varien_Object $row) {
		$_id = $row->getData($this->getColumn()->getIndex());
		$_product = Mage::getModel('catalog/product') -> load($_id);
		return $_product->getName();
	}
}
