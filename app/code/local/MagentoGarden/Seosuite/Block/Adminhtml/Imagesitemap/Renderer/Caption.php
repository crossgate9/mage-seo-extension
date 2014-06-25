<?php

class MagentoGarden_Seosuite_Block_Adminhtml_Imagesitemap_Renderer_Caption extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {
	public function render(Varien_Object $row) {
		$_content = $row->getData($this->getColumn()->getIndex());
		return substr($_content, 0, 100) . '...';
	}
}
